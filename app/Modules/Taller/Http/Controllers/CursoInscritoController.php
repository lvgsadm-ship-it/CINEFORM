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
     * @return \Illuminate\View\View
     */
public function index()
{
    $personalDataController = new PersonalDataController();
    $persona = $personalDataController->DatosPersonales();
    
    // Obtener todos los cursos del usuario
    $cursosInscritos = Inscripcion::where('id_persona', $persona->id)
        ->with('curso')
        ->get()
        ->pluck('curso')
        ->filter();
    
    return view('taller::a.CursosInscritos', compact('persona', 'cursosInscritos'));
}
}
