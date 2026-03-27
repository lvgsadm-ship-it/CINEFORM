<?php

namespace Modules\Registro\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Security\Entities\User;
use Modules\Comun\Entities\PersonalData;
use Modules\Security\Entities\Profile;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro
     * @return Renderable
     */
    public function index()
    {
        $documentTypes = DB::table('security_document_types')->get();
        $genders = DB::table('security_genders')->get();
        $countries = DB::table('security_countries')->orderBy('name')->get();

        return view('registro::register', compact('documentTypes', 'genders', 'countries'));
    }

    /**
     * Guarda el nuevo usuario y perfil
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:security_users,email',
            'password' => 'required|min:6|confirmed',
            'tipo_dni' => 'required',
            'dni' => [
                'required', 
                'unique:security_users,username', 
                Rule::unique(PersonalData::class, 'dni')
            ],
            'pasaporte' => [
                'nullable', 
                Rule::unique(PersonalData::class, 'pasaporte')
            ],
            'rif' => [
                'nullable', 
                Rule::unique(PersonalData::class, 'rif')
            ],
            'reg_nac_cine' => [
                'nullable', 
                Rule::unique(PersonalData::class, 'reg_nac_cine')
            ],
            'genero' => 'required',
            'primer_nombre' => 'required|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'id_pais' => 'required',
            'telefono' => 'required',
        ], [
            'dni.unique' => 'Este DNI ya se encuentra registrado en el sistema.',
            'email.unique' => 'Este correo electrónico ya está en uso por otro usuario.',
            'pasaporte.unique' => 'Este pasaporte ya se encuentra registrado.',
            'rif.unique' => 'Este RIF ya se encuentra registrado.',
            'reg_nac_cine.unique' => 'Este Registro Nacional de Cine ya se encuentra registrado.',
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear el usuario
            $user = new User([
                'username' => $request->dni, // Usamos el DNI como username, o el nombre de usuario que decidan
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // Campos de auditoría y activación requeridos por el Módulo de Seguridad
            $user->register_date = now();
            $user->ip = $request->ip();
            $user->active = 0; // activo por defecto (según lógica detected en SecurityController)
            $user->save();

            // 2. Asignar perfil de "Participante" (Asumiendo que existe en security_profiles)
            $perfilParticipante = 3;
            if ($perfilParticipante) {
                DB::table('security_profiles_users')->insert([
                    'id_users' => $user->id,
                    'id_rol' => $perfilParticipante,
                    'status' => true,
                    'creado_por' => $user->id,
                    'creado_en' => now()
                ]);
            }

            // 3. Crear registro en personas
            PersonalData::create([
                'user_id' => $user->id,
                'tipo_dni' => $request->tipo_dni,
                'dni' => $request->dni,
                'pasaporte' => $request->pasaporte,
                'rif' => $request->rif,
                'reg_nac_cine' => $request->reg_nac_cine,
                'genero' => $request->genero,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'telefono' => $request->telefono,
                'telefono_opcional' => $request->telefono_opcional,
                'id_pais' => $request->id_pais,
                'id_estado' => $request->id_estado,
                'id_municipio' => $request->id_municipio,
                'id_parroquia' => $request->id_parroquia,
                'direccion' => $request->direccion,
                'creado_por' => $user->id,
                'creado_en' => now(),
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registro completado de forma exitosa. Ahora puede iniciar sesión.');

        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error en el registro: ' . $e->getMessage())->withInput();
        }
    }

    // Métodos AJAX para los selects dependientes
    public function getEstados($pais_id)
    {
        try {
            $estados = DB::table('comun.estados')->where('id_pais', $pais_id)->select('id_estado as id', 'nombre')->orderBy('nombre')->get();
        } catch (\Exception $e) {
            $estados = DB::table('comun_estados')->where('id_pais', $pais_id)->select('id as id', 'nombre')->orderBy('nombre')->get();
        }
        return response()->json($estados);
    }

    public function getMunicipios($estado_id)
    {
        try {
            $municipios = DB::table('comun.municipios')->where('id_estado', $estado_id)->select('id_municipio as id', 'nombre')->orderBy('nombre')->get();
        } catch (\Exception $e) {
            $municipios = DB::table('comun_municipios')->where('id_estado', $estado_id)->select('id as id', 'nombre')->orderBy('nombre')->get();
        }
        return response()->json($municipios);
    }

    public function getParroquias($municipio_id)
    {
        try {
            $parroquias = DB::table('comun.parroquias')->where('id_municipio', $municipio_id)->select('id_parroquia as id', 'nombre')->orderBy('nombre')->get();
        } catch (\Exception $e) {
            $parroquias = DB::table('comun_parroquias')->where('id_municipio', $municipio_id)->select('id as id', 'nombre')->orderBy('nombre')->get();
        }
        return response()->json($parroquias);
    }
}
