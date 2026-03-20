<?php

namespace Modules\Taller\Http\Controllers;
use Modules\Comun\Http\Controllers\PersonalDataController;
use Illuminate\Http\Request;
use Modules\Taller\Entities\Inscripcion;
use Modules\Taller\Entities\Curso;
use Illuminate\Support\Facades\Log;

class CursoInscritoController extends BaseController
{
    /**
     * Muestra los cursos en los que el usuario está inscrito
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if ($this->usuarioSinDatosPersonales()) {
            return redirect()->back()->with('error', 'No se encontraron datos personales.');
        }

        $persona = $this->getUsuarioAutenticado()->personalData;

        // Obtener todos los cursos del usuario
        $cursosInscritos = Inscripcion::where('id_persona', $persona->id_persona)
            ->with(['curso.estados', 'curso.modalidad'])
            ->get()
            ->pluck('curso')
            ->filter();

        // Procesar los cursos para la vista
        $this->procesarCursosParaVista($cursosInscritos);

        return view('taller::a.CursosInscritos', compact('persona', 'cursosInscritos'));
    }

    /**
     * Procesa cada curso para agregar propiedades calculadas necesarias en la vista
     *
     * @param \Illuminate\Support\Collection $cursos
     * @return void
     */
    private function procesarCursosParaVista($cursos)
    {
        foreach ($cursos as $curso) {
            $estadoActual = $curso->estado_actual;
            $estadoId = $estadoActual ? $estadoActual->id_estado : 0;

            // Modalidad
            $curso->modalidadNombre = $curso->modalidad->nombre_modalidad ?? 'No especificada';
            $curso->modalidadIcon = strtolower($curso->modalidadNombre) === 'presencial' ? 'fa-building' : 'fa-laptop';

            // Estado y Badge
            if ($estadoId == 6) {
                $curso->estadoTexto = 'Abierto a inscripciones';
                $curso->badgeClass = 'bg-success';
            } elseif ($estadoId == 7) {
                $curso->estadoTexto = 'En curso';
                $curso->badgeClass = 'bg-success';
            } elseif ($estadoId == 8) {
                $curso->estadoTexto = 'Finalizado';
                $curso->badgeClass = 'bg-danger';
            } elseif ($estadoId == 9) {
                $curso->estadoTexto = 'Cerrado';
                $curso->badgeClass = 'bg-danger';
            } else {
                $curso->estadoTexto = 'Sin estado';
                $curso->badgeClass = 'bg-secondary';
            }
        }
    }
}
