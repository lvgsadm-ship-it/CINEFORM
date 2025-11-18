<?php

namespace App\Modules\Comun\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Comun\Entities\Curso;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ComunController extends Controller
{
    public function prueba()
    {
        // Obtener el ID de la persona del usuario autenticado
        $idPersona = Auth::user()->id_persona;
        
        // Obtener los cursos con la relación de modalidad cargada
        $cursos = Curso::with('modalidad')
            ->where('id_persona', $idPersona)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('comun::a.prueba', compact('cursos'));
    }
}