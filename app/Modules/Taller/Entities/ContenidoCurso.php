<?php

namespace Modules\Taller\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContenidoCurso extends Model
{
    use HasFactory;

    protected $table = 'taller_contenido_cursos';
    protected $primaryKey = 'id_contenido_curso';
    
    protected $fillable = [
        'id_curso',
        'titulo',
        'descripcion',
        'tipo_contenido',
        'url_contenido',
        'orden'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}
