<?php
namespace Modules\Participante\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        
        // 📊 Estadísticas rápidas
        $stats = [
            'solicitudes_talleres' => DB::table('participante.solicitudes_talleres')
                ->where('user_id', $userId)->count(),
            'cursos_inscrito' => DB::table('talleres.inscripciones')
                ->where('id_persona', Auth::user()->id_persona)
                ->where('estado', true)->count(),
            'evaluaciones_pendientes' => DB::table('talleres.evaluaciones')
                ->where('id_persona', Auth::user()->id_persona)
                ->whereNull('calificacion')->count(),
            'certificados' => DB::table('talleres.certificados')
                ->where('id_persona', Auth::user()->id_persona)
                ->where('aprobado', true)->count()
        ];

        return view('participante::dashboard', compact('stats'));
    }
}
