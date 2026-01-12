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

        return view('taller::a.CursoDetalle', compact('curso'));
    }
}