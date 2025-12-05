<?php

namespace Modules\Comun\Http\Controllers;
use Modules\Taller\Entities\Curso;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Modules\Security\Entities\User;
use Modules\Taller\Http\Controllers\BaseController;

class PersonalDataController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('comun::index');
    }


   public function DatosPersonales()
{
    // Obtener el usuario autenticado con sus datos personales
    $user = $this->getUsuarioAutenticado();
    
    // Obtener el registro de la tabla comun_personas que coincida con el documento del usuario
    return \DB::table('comun_personas')
        ->select([
            'id',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'document'
        ])
        ->where('document', $user->document)
        ->first(); 
}

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('comun::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('comun::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('comun::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
