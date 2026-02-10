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
class CondicionalBuscadorCurso
{
    private const MAPA_ACCIONES = [
        'coordinador' => 'partials.buscador-actions.CursosCoordinador',
        'default' => 'partials.buscador-actions.Cursos',


    ];

    /**
     * @param bool $esCoordinador Si el usuario es coordinador
     * @return string|null Ruta de la vista parcial a incluir
     */
    public function resolverVista($esCoordinador)
    {
        // Prioridad 1: Coordinador
        if ($esCoordinador && isset(self::MAPA_ACCIONES['coordinador'])) {
            return self::MAPA_ACCIONES['coordinador'];
        }

        // Prioridad 2: Vista por defecto
        return self::MAPA_ACCIONES['default'] ?? null;
    }
}
