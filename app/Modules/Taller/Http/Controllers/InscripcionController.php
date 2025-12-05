<?php

namespace Modules\Taller\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Taller\Entities\Inscripcion;
use Modules\Taller\Entities\Curso;
use Carbon\Carbon;
use Modules\Taller\Http\Controllers\BaseController;

class InscripcionController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'id_curso' => 'required|exists:taller_cursos,id_curso',
    ]);

    try {
        $curso = Curso::findOrFail($request->id_curso);
        $user = $this->getUsuarioAutenticado();
        
        if ($this->usuarioSinDatosPersonales()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron los datos personales del usuario.'
            ], 404);
        }

        // Verificar si el usuario es el propietario del curso
        if ($curso->id_persona == $user->personalData->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes inscribirte en tu propio curso.'
            ], 403);
        }

        // Verificar si ya está inscrito
        $yaInscrito = Inscripcion::where('id_curso', $curso->id_curso)
            ->where('id_persona', $user->personalData->id)
            ->exists();

        if ($yaInscrito) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en este curso.'
            ], 400);
        }

        // Verificar si hay cupos disponibles
        $inscritos = Inscripcion::where('id_curso', $curso->id_curso)->count();
        if ($curso->cantidad_cupos !== null && $inscritos >= $curso->cantidad_cupos) {
            return response()->json([
                'success' => false,
                'message' => 'No hay cupos disponibles para este curso.'
            ], 400);
        }

        // Crear la inscripción
        $inscripcion = Inscripcion::create([
            'id_curso' => $curso->id_curso,
            'id_persona' => $user->personalData->id,
            'fecha_inscripcion' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inscripción exitosa.',
            'data' => $inscripcion
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al procesar la inscripción: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = $this->getUsuarioAutenticado();

        if ($this->usuarioSinDatosPersonales()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron los datos personales del usuario. Por favor, complete su perfil primero.'
            ], 404);
        }

        $inscripcion = Inscripcion::where('id_inscripcion', $id)
            ->where('id_persona', $user->personalData->id)
            ->first();

        if (!$inscripcion) {
            return response()->json([
                'success' => false,
                'message' => 'Inscripción no encontrada o no autorizada para cancelar.'
            ], 404);
        }

        $curso = $inscripcion->curso;
        $inscripcion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inscripción cancelada correctamente.',
            'cupos_restantes' => $curso->fresh()->cantidad_cupos
        ]);
    }
}