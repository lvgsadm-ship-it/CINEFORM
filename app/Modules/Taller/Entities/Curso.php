<?php

namespace Modules\Taller\Entities;

use App\Enums\EstadoCurso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Taller\Entities\Curso;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'taller_cursos';
    protected $primaryKey = 'id_curso';

    protected $fillable = [
        'id_curso',
        'nombre',
        'id_modalidad',
        'id_persona',
        'descripcion',
        'duracion',
        'horas',
        'cantidad_cupos',
        'creado_por',
        'creado_en',
        'fecha_inicio',
        'fecha_fin'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'creado_en' => 'datetime',
        'status' => EstadoCurso::class
    ];

    /**
     * Obtener los valores posibles para el campo status
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            EstadoCurso::por_aceptar->value => 'Por Aceptar',
            EstadoCurso::inscripcion->value => 'Inscripción',
            EstadoCurso::en_curso->value => 'En Curso',
            EstadoCurso::finalizado->value => 'Finalizado',
            EstadoCurso::cerrado->value => 'Cerrado',
        ];
    }

    public function persona()
    {
        return $this->belongsTo(\Modules\Comun\Entities\PersonalData::class, 'id_persona', 'id');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'id_modalidad', 'id_modalidad');
    }
    /**
     * Get the current estado of the curso.
     */
    public function estadoActual()
    {
        return $this->belongsToMany(Estado::class, 'curso_estado', 'id_curso', 'id_estado')
            ->withPivot('created_at', 'motivo')
            ->orderBy('curso_estado.created_at', 'desc')
            ->take(1);
    }

    /**
     * Get the current estado attribute.
     */
    public function getEstadoActualAttribute()
    {
        if ($this->relationLoaded('estados')) {
            return $this->estados->first();
        }
        return $this->estadoActual()->first();
    }

    /**
     * Get the current status of the curso.
     */
    public function getStatusAttribute()
    {
        return $this->estado_actual;
    }

    /**
     * Get all estados for the curso.
     */
    public function estados()
    {
        return $this->belongsToMany(Estado::class, 'curso_estado', 'id_curso', 'id_estado')
            ->withPivot('created_at', 'motivo')
            ->orderBy('curso_estado.created_at', 'desc');
    }

    /**
     * Add a new estado to the curso.
     */

    public function contenidos()
    {
        return $this->hasMany(ContenidoCurso::class, 'id_curso');
    }

    /**
     * Get all inscripciones for the curso.
     */
    public function inscripciones()
    {
        return $this->hasMany(\Modules\Taller\Entities\Inscripcion::class, 'id_curso', 'id_curso');
    }

    // En app/Modules/Taller/Entities/Curso.php

    /**
     * Actualiza el estado del curso
     *
     * @param int $idEstado
     * @param string|null $motivo
     * @return $this
     */
    public function agregarEstado($idEstado, $motivo = null)
    {
        // Verificar si el estado existe
        $estado = \Modules\Taller\Entities\Estado::findOrFail($idEstado);

        // Verificar si ya existe un registro para este curso
        $existeEstado = DB::table('curso_estado')
            ->where('id_curso', $this->id_curso)
            ->first();

        if ($existeEstado) {
            // Actualizar el registro existente
            DB::table('curso_estado')
                ->where('id_curso', $this->id_curso)
                ->update([
                    'id_estado' => $idEstado,
                    'motivo' => $motivo,
                    'updated_at' => now()
                ]);
        }
        return $this;
    }

}