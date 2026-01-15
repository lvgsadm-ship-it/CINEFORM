<?php

namespace Modules\Taller\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Taller\Entities\Curso;
use Modules\Taller\Entities\Estado;

class Estado extends Model
{
    protected $table = 'estados';
    protected $primaryKey = 'id_estado';

    protected $fillable = [
        'nombre',
        'descripcion',
        'motivo_rechazo'
    ];

    /**
     * Get all cursos that have this estado.
     */
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_estado', 'id_estado', 'id_curso')
            ->withPivot(['created_at', 'motivo'])  // Añadir 'motivo' aquí
            ->orderBy('curso_estado.created_at', 'desc');
    }
}
