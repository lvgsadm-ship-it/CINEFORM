<?php

namespace App\Modules\Comun\Http\Controllers;

use App\Modules\Comun\Entities\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CursoController extends Controller
{
    /**
     * Obtiene los cursos de la persona autenticada
     *
     * @return \Illuminate\Http\Response
     */
    public function misCursos()
    {
        // Obtener el ID de la persona asociada al usuario autenticado
        $idPersona = Auth::user()->id_persona;
        
        // Obtener los cursos de la persona
        $cursos = Curso::where('id_persona', $idPersona)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $cursos
        ]);
    }

    /**
     * Obtiene un curso específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $idPersona = Auth::user()->id_persona;
        
        $curso = Curso::where('id', $id)
            ->where('id_persona', $idPersona)
            ->first();
            
        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado o no autorizado'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $curso
        ]);
    }

    /**
     * Almacena un nuevo curso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'horas_academicas' => 'nullable|integer|min:1',
            'descripcion' => 'nullable|string',
        ]);
        
        $curso = new Curso();
        $curso->id_persona = Auth::user()->id_persona;
        $curso->nombre = $request->nombre;
        $curso->institucion = $request->institucion;
        $curso->fecha_inicio = $request->fecha_inicio;
        $curso->fecha_fin = $request->fecha_fin;
        $curso->horas_academicas = $request->horas_academicas;
        $curso->descripcion = $request->descripcion;
        $curso->estado = 'completado'; // o cualquier estado por defecto
        $curso->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Curso registrado exitosamente',
            'data' => $curso
        ], 201);
    }

    /**
     * Actualiza un curso existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $idPersona = Auth::user()->id_persona;
        
        $curso = Curso::where('id', $id)
            ->where('id_persona', $idPersona)
            ->first();
            
        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado o no autorizado'
            ], 404);
        }
        
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'institucion' => 'sometimes|required|string|max:255',
            'fecha_inicio' => 'sometimes|required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'horas_academicas' => 'nullable|integer|min:1',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|in:en_curso,completado,cancelado',
        ]);
        
        $curso->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Curso actualizado exitosamente',
            'data' => $curso
        ]);
    }

    /**
     * Elimina un curso
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $idPersona = Auth::user()->id_persona;
        
        $curso = Curso::where('id', $id)
            ->where('id_persona', $idPersona)
            ->first();
            
        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado o no autorizado'
            ], 404);
        }
        
        $curso->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Curso eliminado exitosamente'
        ]);
    }
}
