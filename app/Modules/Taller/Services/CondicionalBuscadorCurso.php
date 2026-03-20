<?php

namespace Modules\Taller\Services;

use Illuminate\Support\Facades\Session;

/**
 * Servicio: Condicional de Estados del Curso
 *
 * Resuelve qué vista parcial debe mostrarse en función del rol activo
 * almacenado en sesión (profile_id), que se establece cuando el usuario
 * selecciona su perfil en el login.
 *
 * IDs de perfiles:
 *   1 = Administrador
 *   2 = Facilitador
 *   3 = Participante
 *   4 = Coordinador
 */
class CondicionalBuscadorCurso
{
    private const MAPA_ROLES = [
        1 => 'partials.Buscador-actions.CursosCoordinador', // Administrador ve vista completa
        2 => 'partials.Buscador-actions.Cursos',            // Facilitador
        3 => 'partials.Buscador-actions.Cursos',            // Participante
        4 => 'partials.Buscador-actions.CursosCoordinador', // Coordinador
    ];

    /**
     * Resuelve la vista parcial según el perfil activo en sesión.
     *
     * @param  bool $esCoordinador  Compatibilidad hacia atrás (ignorado si hay sesión)
     * @return string|null          Ruta de la vista parcial a incluir
     */
    public function resolverVista(bool $esCoordinador = false): ?string
    {
        $profileId = Session::get('profile_id');

        if ($profileId && isset(self::MAPA_ROLES[$profileId])) {
            return self::MAPA_ROLES[$profileId];
        }

        // Fallback: si no hay sesión, usar el parámetro de compatibilidad
        return $esCoordinador
            ? 'partials.Buscador-actions.CursosCoordinador'
            : 'partials.Buscador-actions.Cursos';
    }

    /**
     * Indica si el rol activo en sesión es coordinador o administrador.
     */
    public static function esCoordinadorOAdmin(): bool
    {
        $profileId = Session::get('profile_id');
        return in_array($profileId, [1, 4]);
    }

    /**
     * Indica si el rol activo en sesión es facilitador.
     */
    public static function esFacilitador(): bool
    {
        return Session::get('profile_id') == 2;
    }

    /**
     * Indica si el rol activo en sesión es participante.
     */
    public static function esParticipante(): bool
    {
        return Session::get('profile_id') == 3;
    }
}
