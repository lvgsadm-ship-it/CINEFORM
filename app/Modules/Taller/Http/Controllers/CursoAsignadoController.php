<?php

namespace Modules\Taller\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Taller\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\Taller\Entities\Curso;
use Modules\Comun\Entities\PersonalData;

class CursoAsignadoController extends BaseController
{
    /**
     * Muestra los cursos asignados al facilitador (donde es responsable)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = $this->getUsuarioAutenticado();

        if ($this->usuarioSinDatosPersonales()) {
            return view('taller::a.CursosAsignados', ['cursos' => collect()]);
        }

        // Primero obtenemos solo los IDs de los cursos
        $cursosIds = Curso::where('id_persona', $user->personalData->id)
            ->pluck('id_curso');

        // Luego cargamos todo lo necesario
        $cursos = Curso::with([
        'modalidad',
        'estados' => function($query) {
            $query->orderBy('curso_estado.created_at', 'desc');
        }
        ])
            ->whereIn('id_curso', $cursosIds)
            ->withCount(['contenidos as total_contenidos'])
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        // Procesamos la colección para agregar el estado actual
        $cursos->getCollection()->transform(function($curso) {
            $curso->estado_actual = $curso->estados->first();
            return $curso;
        });

        return view('taller::a.CursosAsignados', compact('cursos'));
    }



/**
 * Accept a course (legacy method)
 * 
 * @param int $id_curso
 * @return \Illuminate\Http\RedirectResponse
 */
public function aceptar($id_curso)
{
    try {
        // Call the new method
        $response = $this->aceptarCurso($id_curso);
        $data = $response->getData();
        
        if ($data->success) {
            return redirect()->route('taller.mis-cursos-asignados')
                ->with('success', $data->message);
        }
        
        return back()->with('error', $data->message);
    } catch (\Exception $e) {
        return back()->with('error', 'Error al aceptar el curso: ' . $e->getMessage());
    }
}
    /**
     * Update the course status to accepted (status_id = 6)
     *
     * @param int $id_curso
     * @return \Illuminate\Http\Response
     */
   /**
 * Update the course status to accepted (status_id = 6)
 *
 * @param int $id_curso
 * @return \Illuminate\Http\Response
 */
public function aceptarCurso($id_curso)
{
    try {
        $user = auth()->user()->load('personalData');
        
        if (!$user->personalData) {
            return response()->json(['success' => false, 'message' => 'Usuario no tiene datos personales asociados'], 403);
        }

        // Verificar que el curso existe y pertenece al facilitador actual
        $curso = Curso::where('id_curso', $id_curso)
            ->where('id_persona', $user->personalData->id)
            ->firstOrFail();

        // Actualizar el estado existente del curso a 6 (Aceptado)
        $updated = DB::table('curso_estado')
            ->where('id_curso', $id_curso)
            ->latest('created_at')
            ->update([
                'id_estado' => 4, // ID del estado "Aceptado"
                'updated_at' => now()
            ]);


        return response()->json([
            'success' => true, 
            'message' => 'Curso aceptado exitosamente',
            'estado_actual' => [
                'id_estado' => 4,
                'updated_at' => now()->toDateTimeString()
            ]
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['success' => false, 'message' => 'Curso no encontrado o no autorizado'], 404);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al actualizar el estado del curso: ' . $e->getMessage()], 500);
    }
}



}