<?php

namespace Modules\Taller\Services;

/**
 * Servicio: Condicional de Estados del Curso
 * 
 * Resuelve qué vista parcial debe mostrarse en función del estado del curso
 * y el rol del usuario, eliminando la necesidad de múltiples if/elseif.
 * 
 * @author Sistema de Gestión de Cursos
 * @version 1.0
 */
class CondicionalEstadoCurso
{
    /**
     * Mapa de configuración que asocia estados con sus vistas correspondientes
     * 
     * Estructura: [id_estado => [rol/condición => vista_parcial]]
     * 
     * Estados del curso:
     * 1 = Por Aceptar
     * 3 = Declinado
     * 4 = En Edición
     * 5 = En Aprobación
     * 6 = Inscripciones
     * 7 = En Progreso
     * 8 = Finalizado
     * 9 = Cerrado
     */
    private const MAPA_ACCIONES = [
        1 => [ // Estado: Por Aceptar
            'facilitador' => 'partials.curso-actions.por-aceptar-facilitador',
        ],

        3 => [ // Estado: Declinado
            'coordinador' => 'partials.curso-actions.declinado-coordinador',
            'facilitador' => 'partials.curso-actions.declinado-facilitador',
        ],

        4 => [ // Estado: En Edición
            'facilitador' => 'partials.curso-actions.en-edicion-facilitador',
            'default' => 'partials.curso-actions.en-edicion-general',
        ],

        5 => [ // Estado: En Aprobación
            'coordinador' => 'partials.curso-actions.en-aprobacion-coordinador',
            'facilitador' => 'partials.curso-actions.en-aprobacion-facilitador',
        ],

        6 => [ // Estado: Inscripciones
            'coordinador' => 'partials.curso-actions.inscripciones-coordinador',
            'facilitador' => 'partials.curso-actions.inscripciones-facilitador',
            'inscrito' => 'partials.curso-actions.ya-inscrito',
            'cupos' => 'partials.curso-actions.cupos-disponibles',
            'default' => 'partials.curso-actions.sin-cupos',
        ],

        7 => [ // Estado: En Progreso
            'coordinador' => 'partials.curso-actions.en-progreso-coordinador',
            'facilitador' => 'partials.curso-actions.en-progreso-facilitador',
            'default' => 'partials.curso-actions.en-progreso-estudiante',
        ],

        8 => [ // Estado: Finalizado
            'default' => 'partials.curso-actions.finalizado',
        ],

        9 => [ // Estado: Cerrado
            'default' => 'partials.curso-actions.cerrado',
        ],
    ];

    /**
     * Resuelve qué vista parcial debe mostrarse según el contexto del curso y usuario
     * 
     * Prioridad de resolución:
     * 1. Coordinador (profile_id = 4)
     * 2. Facilitador (instructor del curso)
     * 3. Usuario inscrito
     * 4. Usuario con cupos disponibles
     * 5. Vista por defecto
     * 
     * @param int $estadoId ID del estado actual del curso
     * @param bool $esCoordinador Si el usuario es coordinador
     * @param bool $esFacilitador Si el usuario es el facilitador del curso
     * @param mixed $inscripcion Objeto de inscripción si el usuario está inscrito
     * @param int $cuposDisponibles Cantidad de cupos disponibles
     * @return string|null Ruta de la vista parcial a incluir
     */
    public function resolverVista($estadoId, $esCoordinador, $esFacilitador, $inscripcion, $cuposDisponibles)
    {
        // Obtener las acciones disponibles para este estado
        $accionesDelEstado = self::MAPA_ACCIONES[$estadoId] ?? [];

        // Sin acciones configuradas para este estado
        if (empty($accionesDelEstado)) {
            return null;
        }

        // Prioridad 1: Coordinador
        if ($esCoordinador && isset($accionesDelEstado['coordinador'])) {
            return $accionesDelEstado['coordinador'];
        }

        // Prioridad 2: Facilitador
        if ($esFacilitador && isset($accionesDelEstado['facilitador'])) {
            return $accionesDelEstado['facilitador'];
        }

        // Prioridad 3: Usuario inscrito
        if ($inscripcion && isset($accionesDelEstado['inscrito'])) {
            return $accionesDelEstado['inscrito'];
        }

        // Prioridad 4: Cupos disponibles
        if ($cuposDisponibles > 0 && isset($accionesDelEstado['cupos'])) {
            return $accionesDelEstado['cupos'];
        }

        // Prioridad 5: Vista por defecto
        return $accionesDelEstado['default'] ?? null;
    }
}
