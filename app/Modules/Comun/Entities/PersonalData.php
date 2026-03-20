<?php

namespace Modules\Comun\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Security\Entities\User;

class PersonalData extends Model
{
    use HasFactory;

    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'dni'
    ];

    protected $table = "comun.personas";
    protected $primaryKey = "id_persona";
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

            $this->primer_apellido,

        ])));
    }

    protected static function newFactory()
    {

        return \Modules\Comun\Database\factories\PersonalDataFactory::new();
    }
    public function securityUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cursos()
    {
        return $this->hasMany(\Modules\Taller\Entities\Curso::class, 'id_persona', 'id_persona');
    }

    public function especializaciones()
    {
        return $this->belongsToMany(Especializacion::class, 'comun_personas_especializacion', 'id_persona', 'id_especializacion')
            ->withPivot('anos_experiencia');
    }
}
