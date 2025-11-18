<?php

namespace Modules\Taller\Entities;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'modalidad';
    protected $primaryKey = 'id_modalidad';
    
    protected $fillable = [
        'nombre_modalidad',
        'descripcion',
        'status'
    ];
}