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
        $cursosInscritos = Inscripcion::where('id_persona', $persona->id)
            ->with(['curso.estados', 'curso.modalidad'])
            ->get()
            ->pluck('curso')
            ->filter();

        return view('taller::a.CursosInscritos', compact('persona', 'cursosInscritos'));
    }
}
