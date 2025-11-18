<?php

namespace Modules\Comun\Http\Controllers;
use Modules\Taller\Entities\Curso;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Security\Entities\User;

class PersonalDataController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('comun::index');
    }


    public function prueba()
{
    // Obtener el usuario autenticado con sus datos personales
    $user = auth()->user()->load('personalData');
    
    // Verificar si el usuario tiene datos personales
    if (!$user->personalData) {
        return view('comun::a.prueba', ['cursos' => collect()]);
    }
    
    // Obtener los cursos de la persona con la relación de modalidad cargada
    $cursos = \Modules\Taller\Entities\Curso::with('modalidad')
        ->where('id_persona', $user->personalData->id_persona)
        ->orderBy('creado_en', 'desc')
        ->paginate(10); // Add pagination with 10 items per pages
        
    return view('taller::a.prueba', compact('cursos'));
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
