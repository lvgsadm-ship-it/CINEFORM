<?php
namespace Modules\Participante\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudTallerController extends Controller
{
    public function index()
    {
        $estados = DB::table('comun.estados')->get();
        $tiposPrograma = DB::table('talleres.tipo_programas_academicos')->where('status', 'activo')->get();
        
        return view('participante::solicitar-taller', compact('estados', 'tiposPrograma'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tipo_programa_id' => 'required',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'descripcion' => 'required'
        ]);
        
        DB::table('participante.solicitudes_talleres')->insert([
            'user_id' => Auth::user()->id,
            'id_persona' => Auth::user()->id_persona,
            'tipo_programa_id' => $request->tipo_programa_id,
            'estado_id' => $request->estado_id,
            'municipio_id' => $request->municipio_id,
            'descripcion' => $request->descripcion,
            'status' => 'pendiente',
            'creado_en' => now()
        ]);
        
        return redirect()->route('participante.dashboard')
            ->with('success', 'Solicitud enviada correctamente');
    }
}
