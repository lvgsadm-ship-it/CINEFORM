<?php

namespace Modules\Taller\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Comun\Entities\PersonalData;
use Modules\Taller\Entities\Curso;
use Modules\Taller\Entities\ContenidoCurso;

class EditarCursoController extends BaseController
{
    /**
     * Muestra el formulario de edición del curso
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('Iniciando edición de curso', ['curso_id' => $id, 'user_id' => Auth::id()]);

        $curso = Curso::findOrFail($id);

        // Obtener los datos de la persona autenticada
        $persona = PersonalData::where('document', Auth::user()->document)->first();

        if (!$persona) {
            Log::error('No se encontraron datos personales para el usuario', [
                'user_id' => Auth::id(),
                'document' => Auth::user()->document
            ]);
            abort(403, 'No se encontraron tus datos de perfil. Por favor, contacta al administrador.');
        }

        Log::info('Datos del curso', [
            'curso_id' => $curso->id_curso,
            'curso_id_persona' => $curso->id_persona,
            'usuario_actual_id' => $persona->id,
            'son_iguales' => $curso->id_persona == $persona->id ? 'Sí' : 'No',
            'es_coordinador' => Auth::user()->profile_id == 4 ? 'Sí' : 'No'
        ]);

        // Verificar que el usuario autenticado es el Facilitador del curso o un Coordinador
        if (Auth::user()->profile_id == 4) {
            Log::info('Usuario autenticado es el Coordinador de la institucion', [
                'usuario_actual' => $persona->id,
                'documento_usuario' => $persona->document,
                'usuario' => Auth::user()
            ]);
        } elseif ($curso->id_persona != $persona->id) {
            Log::warning('Intento de edición no autorizado', [
                'curso_id' => $curso->id_curso,
                'usuario_esperado' => $curso->id_persona,
                'usuario_actual' => $persona->id,
                'documento_usuario' => $persona->document,
                'usuario' => Auth::user()
            ]);
            abort(403, 'No tienes permiso para editar este curso.');
        }

        // Obtener las modalidades para el select
        $modalidades = \Modules\Taller\Entities\Modalidad::all();

        // Obtener tipos de evaluación
        $tiposEvaluacion = \Modules\Taller\Entities\TipoEvaluacion::all();

        // Cargar la relación de modalidad
        $curso->load('modalidad');

        // Cargar los contenidos del curso ordenados
        $contenidos = $curso->contenidos()->orderBy('orden')->orderBy('id_contenido_curso')->get();

        Log::info('Permiso de edición concedido');
        return view('taller::a.CursoEditar', compact('curso', 'modalidades', 'contenidos', 'tiposEvaluacion'));
    }

    /**
     * Actualiza un curso existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Log de depuración
        Log::info('Iniciando actualización de curso', [
            'curso_id' => $id,
            'user_id' => Auth::id()
        ]);

        try {
            // Obtener el usuario autenticado
            $user = Auth::user();
            if (!$user) {
                Log::error('Usuario no autenticado');
                return redirect()->route('login');
            }

            // Obtener los datos de la persona asociada al usuario
            $persona = \Modules\Comun\Entities\PersonalData::where('document', $user->document)->first();

            if (!$persona) {
                Log::error('No se encontraron datos de persona para el usuario', [
                    'user_id' => $user->id,
                    'document' => $user->document
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'No se encontró tu perfil de persona. Contacta al administrador.']);
            }

            $idPersona = $persona->id;

            // Buscar el curso con sus relaciones
            $curso = Curso::with('contenidos')->find($id);

            if (!$curso) {
                Log::error('Curso no encontrado', ['curso_id' => $id]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'El curso solicitado no existe.']);
            }

            // Verificar que el usuario es el propietario del curso o un Coordinador
            if ($user->profile_id != 4 && $curso->id_persona != $idPersona) {
                Log::warning('Intento de edición no autorizado', [
                    'curso_id' => $id,
                    'usuario_esperado' => $curso->id_persona,
                    'usuario_actual' => $idPersona,
                    'user' => $user->toArray()
                ]);

                return back()
                    ->withInput()
                    ->withErrors(['error' => 'No tienes permiso para editar este curso.']);
            }

            // Validación de campos del curso
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'id_modalidad' => 'required|exists:modalidad,id_modalidad',
                'descripcion' => 'nullable|string',
                'duracion' => 'nullable|integer|min:1',
                'horas' => 'nullable|integer|min:1',
                'cantidad_cupos' => 'nullable|integer|min:0',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
                'status' => 'boolean',
                'contenidos' => 'nullable|array',
                'contenidos.*.id' => 'nullable|integer',
                'contenidos.*.titulo' => 'required|string|max:255',
                // 'contenidos.*.tipo_contenido' => 'required|in:video,documento,enlace',
                'contenidos.*.url_contenido' => 'required|url',
                'contenidos.*.descripcion' => 'nullable|string',
                'contenidos.*.descripcion_breve' => 'nullable|string',
                'contenidos.*.orden' => 'nullable|integer|min:0',
                'contenidos.*.es_evaluacion' => 'nullable|boolean',
                'contenidos.*.id_tipo_evaluacion' => 'nullable|exists:tipo_evaluaciones,id_tipo_evaluacion',
                'contenidos.*.ponderacion' => 'nullable|numeric|min:0|max:100'
            ]);

            DB::beginTransaction();

            // Actualizar el curso
            $curso->update([
                'nombre' => $validatedData['nombre'],
                'id_modalidad' => $validatedData['id_modalidad'],
                'descripcion' => $validatedData['descripcion'] ?? null,
                'duracion' => $validatedData['duracion'] ?? null,
                'horas' => $validatedData['horas'] ?? null,
                'cantidad_cupos' => $validatedData['cantidad_cupos'] ?? null,
                'fecha_inicio' => $validatedData['fecha_inicio'] ?? null,
                'fecha_fin' => $validatedData['fecha_fin'] ?? null,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            Log::info('Curso actualizado', ['curso_id' => $curso->id_curso]);

            // Sincronizar contenidos
            if (isset($validatedData['contenidos']) && is_array($validatedData['contenidos'])) {
                $contenidosData = collect($validatedData['contenidos']);
                Log::debug('Contenidos a procesar:', $contenidosData->toArray());

                // Obtener IDs de contenidos existentes
                $contenidosExistentes = $curso->contenidos()->pluck('id_contenido_curso')->toArray();
                $contenidosRecibidos = $contenidosData->pluck('id')->filter()->values()->toArray();

                Log::debug('Contenidos existentes:', $contenidosExistentes);
                Log::debug('Contenidos recibidos:', $contenidosRecibidos);

                // Eliminar contenidos que ya no están en la lista
                $contenidosAEliminar = array_diff($contenidosExistentes, $contenidosRecibidos);
                if (!empty($contenidosAEliminar)) {
                    Log::info('Eliminando contenidos:', $contenidosAEliminar);
                    ContenidoCurso::whereIn('id_contenido_curso', $contenidosAEliminar)->delete();
                }

                // Actualizar o crear contenidos
                foreach ($contenidosData as $index => $contenidoData) {
                    try {
                        $esEvaluacion = isset($contenidoData['es_evaluacion']) ? (bool) $contenidoData['es_evaluacion'] : false;

                        $dataToSave = [
                            'titulo' => $contenidoData['titulo'],
                            'url_contenido' => $contenidoData['url_contenido'],
                            'descripcion' => $contenidoData['descripcion'] ?? $contenidoData['descripcion_breve'] ?? null,
                            'orden' => isset($contenidoData['orden']) ? (int) $contenidoData['orden'] : $index + 1,
                            'es_evaluacion' => $esEvaluacion,
                            'id_tipo_evaluacion' => $esEvaluacion ? ($contenidoData['id_tipo_evaluacion'] ?? null) : null,
                            'ponderacion' => $esEvaluacion ? ($contenidoData['ponderacion'] ?? null) : null
                        ];

                        if (isset($contenidoData['descripcion_breve'])) {
                            $dataToSave['descripcion_breve'] = $contenidoData['descripcion_breve'];
                        }

                        if (!empty($contenidoData['id'])) {
                            // Actualizar contenido existente
                            $contenido = ContenidoCurso::where('id_contenido_curso', $contenidoData['id'])
                                ->where('id_curso', $curso->id_curso)
                                ->first();

                            if ($contenido) {
                                $contenido->update($dataToSave);
                                Log::debug('Contenido actualizado:', $contenido->toArray());
                            }
                        } else {
                            // Crear nuevo contenido
                            $dataToSave['descripcion_breve'] = $dataToSave['descripcion_breve'] ?? mb_substr($dataToSave['descripcion'] ?? '', 0, 100) . '...';
                            $dataToSave['creado_por'] = Auth::id();
                            $dataToSave['actualizado_por'] = Auth::id();

                            $nuevoContenido = $curso->contenidos()->create($dataToSave);
                            Log::debug('Nuevo contenido creado:', $nuevoContenido->toArray());
                        }
                    } catch (\Exception $e) {
                        Log::error('Error al procesar contenido', [
                            'contenido_data' => $contenidoData,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e;
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('taller.cursos.show', $curso->id_curso)
                ->with('success', 'Curso y contenidos actualizados exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al actualizar curso', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar el curso: ' . $e->getMessage(), [
                'curso_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Ocurrió un error al actualizar el curso: ' . $e->getMessage()]);
        }
    }
}
