<?php

namespace Modules\Comun\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Security\Entities\User;


class PersonalData extends Model
{
    use HasFactory;

    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'document'
    ];
    
    protected $table = "comun_personas";
    public $timestamps = false;
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNombreCompletoAttribute()
    {
        return trim(implode(' ', array_filter([
            $this->primer_nombre,
            $this->segundo_nombre,
            $this->primer_apellido,
            $this->segundo_apellido
        ])));
    }
    
    protected static function newFactory()
    {

        return \Modules\Comun\Database\factories\PersonalDataFactory::new();
    }
    public function securityUser()
{
    return $this->hasOne(\App\Models\User::class, 'document', 'document');
}

public function user()
{
    return $this->belongsTo(User::class, 'document', 'document');
}

public function cursos()
{
    return $this->hasMany(\Modules\Taller\Entities\Curso::class, 'id_persona', 'id');
}

}
