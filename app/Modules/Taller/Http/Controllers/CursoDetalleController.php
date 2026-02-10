<?php

namespace Modules\Taller\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Taller\Entities\Curso;
use Modules\Taller\Entities\Inscripcion;
use Modules\Comun\Entities\PersonalData;
use Modules\Taller\Services\CondicionalEstadoCurso;
/**
 * Controlador: CursoDetalleController
 * 
 * Maneja la visualización detallada de un curso específico.
 * Toda la lógica de negocio, consultas y cálculos se realiza aquí
 * para mantener las vistas limpias y seguir el patrón MVC.
 * 
 * @version 2.0 - Refactorizado para separación de responsabilidades
 */
class CursoDetalleController extends BaseController
{
    /**
     * Muestra el detalle de un curso específico
     *
     * @param int $id ID del curso a mostrar
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $condicional = new CondicionalEstadoCurso();

        // 1. Cargar el curso con sus relaciones
        $curso = Curso::with([
            'modalidad',
            'contenidos' => function ($query) {
                $query->orderBy('orden', 'asc')->orderBy('id_contenido_curso', 'asc');
            },
            'inscripciones.persona',
            'persona.user', // Cargar relación completa con usuario
            'estados' // ✅ Relación correcta (el accessor 'estado_actual' usa esta)
        ])->findOrFail($id);

        // 2. Obtener datos del usuario autenticado
        $datosUsuario = $this->obtenerDatosUsuario();

        // 3. Calcular datos específicos del curso y usuario
        $datosCurso = $this->calcularDatosCurso($curso, $datosUsuario);

        // 4. Resolver qué vista parcial mostrar (usando los datos reales)
        $vistaParcial = $condicional->resolverVista(
            $curso->estado_actual->id_estado,
            $datosUsuario['esCoordinador'],
            $datosCurso['esFacilitador'],
            $datosCurso['inscripcion'],
            $datosCurso['CuposDisponibles']
        );

        // 5. Retornar vista con todas las variables necesarias
        return view('taller::a.CursoDetalle', array_merge(
            compact('curso', 'vistaParcial'),
            $datosUsuario,
            $datosCurso
        ));
    }

    /**
     * Obtiene los datos del usuario autenticado
     * 
     * @return array Datos del usuario (id_persona, esCoordinador, etc.)
     */
    private function obtenerDatosUsuario(): array
    {
        if (!auth()->check()) {
            return [
                'user' => null,
                'idPersona' => null,
                'esCoordinador' => false,
                'esFacilitador' => false,
            ];
        }

        $user = auth()->user();

        // Obtener datos personales del usuario
        $personalData = PersonalData::where('document', $user->document)->first();
        $idPersona = $personalData ? $personalData->id : null;

        return [
            'user' => $user,
            'idPersona' => $idPersona,
            'esCoordinador' => $user->profile_id == 4,
            'personalData' => $personalData,
        ];
    }

    /**
     * Calcula todos los datos relacionados al curso y el usuario
     * 
     * @param Curso $curso Instancia del curso
     * @param array $datosUsuario Datos del usuario autenticado
     * @return array Datos calculados del curso
     */
    private function calcularDatosCurso(Curso $curso, array $datosUsuario): array
    {
        $idPersona = $datosUsuario['idPersona'];

        // Verificar si el usuario es el facilitador del curso
        $esFacilitador = $idPersona && $curso->id_persona == $idPersona;

        // Obtener inscripción del usuario (si existe)
        $inscripcion = $idPersona
            ? Inscripcion::where('id_curso', $curso->id_curso)
                ->where('id_persona', $idPersona)
                ->first()
            : null;

        // Calcular cupos disponibles
        $cuposDisponibles = $curso->cantidad_cupos;

        // Calcular promedio del estudiante
        $datosPromedio = $this->calcularPromedioEstudiante($curso, $idPersona, $inscripcion);

        return [
            'esFacilitador' => $esFacilitador,
            'inscripcion' => $inscripcion,
            'CuposDisponibles' => $cuposDisponibles,
            'puntosObtenidos' => $datosPromedio['puntosObtenidos'],
            'ponderacionEvaluada' => $datosPromedio['ponderacionEvaluada'],
            'promedioEstudiante' => $datosPromedio['promedioEstudiante'],
            'debeMostrarPromedio' => $datosPromedio['debeMostrarPromedio'],
        ];
    }

    /**
     * Calcula el promedio del estudiante basado en las evaluaciones completadas
     * 
     * @param Curso $curso Instancia del curso
     * @param int|null $idPersona ID de la persona
     * @param \Modules\Taller\Entities\Inscripcion|null $inscripcion Inscripción del usuario
     * @return array Datos del promedio calculado
     */
    private function calcularPromedioEstudiante(Curso $curso, ?int $idPersona, $inscripcion): array
    {
        // Estados donde se debe mostrar el promedio: En Progreso (7), Finalizado (8), Cerrado (9)
        $estadosPromedio = [7, 8, 9];
        $debeMostrarPromedio = in_array($curso->estado_actual->id_estado, $estadosPromedio);

        $datosPromedio = [
            'puntosObtenidos' => 0,
            'ponderacionEvaluada' => 0,
            'promedioEstudiante' => 0,
            'debeMostrarPromedio' => $debeMostrarPromedio,
        ];

        // Solo calcular si el usuario está inscrito y el curso está en un estado válido
        if (!$inscripcion || !$debeMostrarPromedio || !$idPersona) {
            return $datosPromedio;
        }

        // Obtener todas las calificaciones del estudiante para este curso
        $calificacionesEstudiante = DB::table('taller_calificaciones')
            ->where('id_persona', $idPersona)
            ->where('id_curso', $curso->id_curso)
            ->get()
            ->keyBy('id_contenido_curso'); // Indexar por id_contenido_curso para búsqueda rápida

        $puntosObtenidos = 0;
        $ponderacionEvaluada = 0;

        // Recorrer contenidos del curso que sean evaluaciones
        foreach ($curso->contenidos as $contenido) {
            if (!$contenido->es_evaluacion) {
                continue;
            }

            // Verificar si existe una calificación para este contenido
            $calificacion = $calificacionesEstudiante->get($contenido->id_contenido_curso);

            if ($calificacion && isset($calificacion->calificacion)) {
                // Calcular puntos ponderados: (nota * ponderación) / 100
                $puntosObtenidos += ($calificacion->calificacion * $contenido->ponderacion) / 100;
                $ponderacionEvaluada += $contenido->ponderacion;
            }
        }

        // Calcular promedio sobre lo evaluado (base 100)
        $promedioEstudiante = $ponderacionEvaluada > 0
            ? ($puntosObtenidos / $ponderacionEvaluada) * 100
            : 0;

        return [
            'puntosObtenidos' => $puntosObtenidos,
            'ponderacionEvaluada' => $ponderacionEvaluada,
            'promedioEstudiante' => round($promedioEstudiante, 2),
            'debeMostrarPromedio' => $debeMostrarPromedio,
        ];
    }
}