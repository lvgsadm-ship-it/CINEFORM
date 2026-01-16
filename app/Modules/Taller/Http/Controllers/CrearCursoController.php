<?php

namespace Modules\Taller\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Comun\Entities\PersonalData;
use Modules\Taller\Entities\Curso;
use Modules\Taller\Entities\ContenidoCurso;
use Modules\Security\Entities\User;

class CrearCursoController extends BaseController
{
    /**
     * Muestra el formulario de creación del curso
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        // Verificar si el usuario es Coordinador (ID 4)
        if (Auth::user()->profile_id != 4) {
            abort(403, 'Solo los coordinadores pueden crear cursos.');
        }

        // Obtener las modalidades para el select
        $modalidades = \Modules\Taller\Entities\Modalidad::all();

        // Obtener tipos de evaluación
        $tiposEvaluacion = \Modules\Taller\Entities\TipoEvaluacion::all();

        // Obtener Facilitadores (Perfil 2 según migration create_security_profiles_table)
        $facilitadores = User::where('profile_id', 2)
            ->with('personalData') // Usar la relación correcta definida en User model
            ->get()
            ->filter(function ($user) {
                return $user->personalData != null; // Filtrar usuarios que tengan datos personales
            });

        return view('taller::a.CursoCrear', compact('modalidades', 'tiposEvaluacion', 'facilitadores'));
    }

    /**
     * Almacena un nuevo curso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Verificar permiso nuevamente
        if (Auth::user()->profile_id != 4) {
            abort(403, 'Acceso denegado.');
        }

        try {
            // Validación
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'id_modalidad' => 'required|exists:modalidad,id_modalidad',
                'id_persona' => 'required|exists:comun_personas,id', // Facilitador
                'descripcion' => 'nullable|string',
                'duracion' => 'nullable|integer|min:1',
                'horas' => 'nullable|integer|min:1',
                'cantidad_cupos' => 'nullable|integer|min:0',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
                'contenidos' => 'nullable|array',
                'contenidos.*.titulo' => 'required|string|max:255',
                'contenidos.*.url_contenido' => 'required|url',
                'contenidos.*.descripcion' => 'nullable|string',
                'contenidos.*.descripcion_breve' => 'nullable|string',
                'contenidos.*.orden' => 'nullable|integer|min:0',
                'contenidos.*.es_evaluacion' => 'nullable|boolean',
                'contenidos.*.id_tipo_evaluacion' => 'nullable|exists:tipo_evaluaciones,id_tipo_evaluacion',
                'contenidos.*.ponderacion' => 'nullable|numeric|min:0|max:100'
            ]);

            DB::beginTransaction();

            // Crear el curso
            // Nota: id_curso es autoincremental, no se pasa.
            // create() asignará automáticamente timestamps si el modelo lo permite.
            // 'status' inicial logic: 
            // Si el coordinador lo crea, ¿en qué estado nace? 
            // Asumiremos estado inicial o el que defina el negocio. Por ahora no pasamos status explícito si no es necesario,
            // o lo definimos. Entities/Curso tiene getStatuses() pero no un default claro en código visible.
            // Asumiremos que la BD tiene default o se maneja por lógica de negocio.
            // Revisando Curso model: "status" se cast a Enum.
            // Vamos a crearlo base.

            $cursoData = [
                'nombre' => $validatedData['nombre'],
                'id_modalidad' => $validatedData['id_modalidad'],
                'id_persona' => $validatedData['id_persona'], // Facilitador asignado
                'descripcion' => $validatedData['descripcion'] ?? null,
                'duracion' => $validatedData['duracion'] ?? null,
                'horas' => $validatedData['horas'] ?? null,
                'cantidad_cupos' => $validatedData['cantidad_cupos'] ?? null,
                'fecha_inicio' => $validatedData['fecha_inicio'] ?? null,
                'fecha_fin' => $validatedData['fecha_fin'] ?? null,
                'creado_por' => Auth::id(),
                'creado_en' => now(),
            ];

            $curso = Curso::create($cursoData);

            // Asignar estado inicial: Por Aceptar (1)
            // Se usa la tabla pivote curso_estado
            DB::table('curso_estado')->insert([
                'id_curso' => $curso->id_curso,
                'id_estado' => 1, // Por Aceptar
                'created_at' => now(),
                'updated_at' => now()
            ]);


            Log::info('Curso creado', ['curso_id' => $curso->id_curso, 'creado_por' => Auth::id()]);

            // Guardar contenidos si existen
            if (isset($validatedData['contenidos']) && is_array($validatedData['contenidos'])) {
                foreach ($validatedData['contenidos'] as $index => $contenidoData) {
                    $esEvaluacion = isset($contenidoData['es_evaluacion']) ? (bool) $contenidoData['es_evaluacion'] : false;

                    $dataToSave = [
                        'titulo' => $contenidoData['titulo'],
                        'url_contenido' => $contenidoData['url_contenido'],
                        'descripcion' => $contenidoData['descripcion'] ?? $contenidoData['descripcion_breve'] ?? null,
                        'descripcion_breve' => $contenidoData['descripcion_breve'] ?? mb_substr($contenidoData['descripcion'] ?? '', 0, 100) . '...',
                        'orden' => isset($contenidoData['orden']) ? (int) $contenidoData['orden'] : $index + 1,
                        'es_evaluacion' => $esEvaluacion,
                        'id_tipo_evaluacion' => $esEvaluacion ? ($contenidoData['id_tipo_evaluacion'] ?? null) : null,
                        'ponderacion' => $esEvaluacion ? ($contenidoData['ponderacion'] ?? null) : null,
                        'creado_por' => Auth::id(),
                        'actualizado_por' => Auth::id() // Se usa create() de la relación hasMany
                    ];

                    $curso->contenidos()->create($dataToSave);
                }
            }

            DB::commit();

            return redirect()
                ->route('taller.cursos.index')
                ->with('success', 'Curso creado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear curso: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Ocurrió un error al crear el curso: ' . $e->getMessage()]);
        }
    }
}
