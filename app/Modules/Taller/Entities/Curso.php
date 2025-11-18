<?php

namespace Modules\Taller\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'descripcion'
    ];

public function persona()
{
    return $this->belongsTo(\Modules\Comun\Entities\PersonalData::class, 'id_persona', 'id');
}

public function modalidad()
{
    return $this->belongsTo(\Modules\Taller\Entities\Modalidad::class, 'id_modalidad');
}

public function contenidos()
{
    return $this->hasMany(ContenidoCurso::class, 'id_curso', 'id_curso');
}
}