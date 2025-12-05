<?php

namespace Modules\Taller\Http\Controllers;

use Modules\Taller\Entities\Curso;
use Modules\Comun\Entities\PersonalData;
use Modules\Taller\Entities\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CursoController extends BaseController
{

    /**
     * Muestra la lista de cursos paginados
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Actualiza el estado de un curso
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Actualiza el estado de un curso
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function updateStatus(Request $request, $id)
{
    try {
        $curso = Curso::findOrFail($id);
        $curso->agregarEstado($request->id_estado);
        
        return response()->json([
            'success' => true,
            'message' => 'Estado del curso actualizado correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el estado: ' . $e->getMessage()
        ], 500);
    }
}
    
    /**
     * Muestra la lista de cursos
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Obtener la persona asociada al usuario autenticado
        $persona = PersonalData::where('document', Auth::user()->document)->first();
        
        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron datos personales para el usuario actual'
            ], 404);
        }

        // Obtener cursos que están en estado de inscripción (id_estado = 6) con conteo de contenidos
        $cursos = Curso::with(['modalidad', 'inscripciones'])
            ->withCount('contenidos')
            ->whereHas('estados', function($query) {
                $query->where('estados.id_estado', 6) // Especificamos la tabla para evitar ambigüedad
                      ->whereIn('curso_estado.id', function($q) {
                          $q->select(DB::raw('MAX(id)'))
                            ->from('curso_estado')
                            ->groupBy('id_curso');
                      });
            })
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(12);

        return view('taller::a.Cursos', compact('cursos'));
    }




   
    
    /**
     * Obtiene los cursos de la persona autenticada
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Obtiene los cursos en los que el usuario está inscrito como participante
     *
     * @return \Illuminate\Http\Response
     */
    public function misCursosParticipante()
    {
        // Obtener la persona asociada al usuario autenticado
        $persona = PersonalData::where('document', Auth::user()->document)->first();
        
        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron datos personales para el usuario actual'
            ], 404);
        }
        
        // Obtener los cursos en los que la persona está inscrita
        $cursosParticipante = Curso::whereHas('inscripciones', function($query) use ($persona) {
                $query->where('id_persona', $persona->id);
            })
            ->with(['modalidad', 'inscripciones'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $cursosParticipante
        ]);
    }
    /**
     * Obtiene un curso específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Muestra los detalles de un curso específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Obtener la persona asociada al usuario autenticado
        $persona = PersonalData::where('document', Auth::user()->document)->first();
        
        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron datos personales para el usuario actual'
            ], 404);
        }
        
        // Buscar el curso con sus relaciones
        $curso = Curso::with(['modalidad', 'contenidos', 'inscripciones.persona'])
            ->find($id);
        
        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado'
            ], 404);
        }
        
        // Verificar si el usuario es el facilitador o un participante
        $esFacilitador = $curso->id_persona == $persona->id;
        $esParticipante = $curso->inscripciones->contains('id_persona', $persona->id);
        
        if (!$esFacilitador && !$esParticipante) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver este curso'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'data' => $curso,
            'es_facilitador' => $esFacilitador,
            'es_participante' => $esParticipante
        ]);
    }

    /**
     * Almacena un nuevo curso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Almacena un nuevo curso en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $request->validate([
        'id_curso' => 'required|exists:taller_cursos,id_curso',
    ]);

    $curso = Curso::findOrFail($request->id_curso);
    $user = Auth::user();

    // Get the personal data for the authenticated user
    $personalData = \Modules\Comun\Entities\PersonalData::where('document', $user->document)->first();

    if (!$personalData) {
        return response()->json([
            'success' => false,
            'message' => 'No se encontraron los datos personales del usuario.'
        ], 404);
    }

    // Verificar si el usuario es el propietario del curso
    if ($curso->id_persona == $personalData->id) {
        return response()->json([
            'success' => false,
            'message' => 'No puedes inscribirte en tu propio curso.'
        ], 403);
    }

    // Verificar si el usuario ya está inscrito en el curso
    $inscripcionExistente = Inscripcion::where('id_curso', $request->id_curso)
        ->where('id_persona', $personalData->id)
        ->first();

    if ($inscripcionExistente) {
        return response()->json([
            'success' => false,
            'message' => 'Ya estás inscrito en este curso.'
        ], 409);
    }

    // Verificar si hay cupos disponibles
    if ($curso->cantidad_cupos <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'No hay cupos disponibles para este curso.'
        ], 400);
    }

    // Crear la inscripción
    $inscripcion = Inscripcion::create([
        'id_curso' => $request->id_curso,
        'id_persona' => $personalData->id,
        'fecha_inscripcion' => now()
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Inscripción realizada con éxito.',
        'data' => $inscripcion,
        'cupos_restantes' => $curso->fresh()->cantidad_cupos
    ], 201);
}

  
  /**
 * Elimina un curso
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function destroy($id)
{
    $user = Auth::user();
    $personalData = \Modules\Comun\Entities\PersonalData::where('document', $user->document)->first();

    if (!$personalData) {
        return response()->json([
            'success' => false,
            'message' => 'No se encontraron los datos personales del usuario.'
        ], 404);
    }

    $inscripcion = Inscripcion::where('id_inscripcion', $id)
        ->where('id_persona', $personalData->id)
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
