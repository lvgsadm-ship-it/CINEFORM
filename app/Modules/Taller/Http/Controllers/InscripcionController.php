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

            if ($curso->estado_actual->id_estado != 6) {
                return response()->json([
                    'success' => false,
                    'message' => 'El curso no se encuentra en estado de inscripción.',
                    'data' => $curso
                ], 400);
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

        $inscripcion = Inscripcion::findOrFail($id);
        $curso = $inscripcion->curso;

        if ($curso->estado_actual->id_estado != 6) {
            return response()->json([
                'success' => false,
                'message' => 'El curso ya ha iniciado, no se puede gestionar el cupo.',
                'data' => $curso
            ], 403);
        }

        if ($this->usuarioSinDatosPersonales()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron los datos personales del usuario. Por favor, complete su perfil primero.'
            ], 404);
        }

        if ($inscripcion->id_persona != $user->personalData->id) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizada para cancelar esta inscripción.'
            ], 403);
        }

        $inscripcion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inscripción cancelada correctamente.',
            'cupos_restantes' => $curso->fresh()->cantidad_cupos
        ]);
    }
}