<?php

namespace Modules\Taller\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Taller\Entities\Curso;

class CursoDetalleController extends BaseController
{
    /**
     * Muestra el detalle de un curso específico
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
   public function show($id)
{
    $curso = Curso::with([
        'modalidad', 
        'contenidos', 
        'inscripciones.persona',
        'persona' // Load full persona relationship
    ])->find($id);
    
    // Debug information
    \Log::info('Curso ID: ' . $curso->id_curso);
    \Log::info('Persona ID: ' . $curso->id_persona);
    \Log::info('Persona data:', $curso->persona ? $curso->persona->toArray() : 'No persona found');
    
    return view('taller::a.CursoDetalle', compact('curso'));
}
}