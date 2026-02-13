<?php
namespace Modules\Participante\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\Request as RequestFacade;
use App\Helpers\Encryptor;
use Illuminate\Support\Facades\DB;

class ParticipanteController extends Controller
{
    public function index()
    {
        return view('participante::index');
    }

    public function list(Request $request)
    {
        if (RequestFacade::ajax()) {
            
            $search = $request->search ?? '';
            
            $participantes = DB::table('security_users as u')
                ->join('comun.personas as p', 'u.id_persona', '=', 'p.id_persona')
                ->join('security_profiles_users as pu', function($join) {
                    $join->on('u.id', '=', 'pu.id_users')
                         ->where('pu.id_rol', 5); // 👥 Perfil Participante
                })
                ->where('pu.status', 'activo')
                ->when($search, function($query, $search) {
                    $query->where(function($q) use ($search) {
                        $q->whereRaw("CONCAT(COALESCE(p.primer_nombre,''), ' ', COALESCE(p.segundo_nombre,''), ' ', COALESCE(p.primer_apellido,''), ' ', COALESCE(p.segundo_apellido,'')) ILIKE ?", ['%' . strtoupper($search) . '%'])
                          ->orWhere('p.dni', 'ILIKE', '%' . strtoupper($search) . '%')
                          ->orWhere('p.email', 'ILIKE', '%' . strtoupper($search) . '%');
                    });
                })
                ->select([
                    'u.id',
                    'u.id_persona',
                    'p.dni',
                    DB::raw("CONCAT(COALESCE(p.primer_nombre,''), ' ', 
                                   COALESCE(p.segundo_nombre,''), ' ',
                                   COALESCE(p.primer_apellido,''), ' ',
                                   COALESCE(p.segundo_apellido,'')) as full_name"),
                    'p.email',
                    'u.username',
                    'u.active'
                ])
                ->limit(100)
                ->get();

            return DataTables::of($participantes)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $cryptId = Encryptor::encrypt($row->id);
                    return '<div class="text-center">
                        <a href="' . route('participantes.profile', $cryptId) . '" 
                           class="btn btn-icon btn-link btn-warning btn-xs" title="Ver Perfil">
                            <span class="fa fa-user"></span>
                        </a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function profile($id)
    {
        $id = Encryptor::decrypt($id);
        
        $participante = DB::table('security_users as u')
            ->join('comun.personas as p', 'u.id_persona', '=', 'p.id_persona')
            ->join('security_profiles_users as pu', 'u.id', '=', 'pu.id_users')
            ->where('u.id', $id)
            ->where('pu.id_rol', 5)
            ->first();

        if (!$participante) {
            return redirect()->route('participantes')->with('error', 'Participante no encontrado');
        }

        return view('participante::profile', compact('participante'));
    }
}
