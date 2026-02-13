<?php

namespace Modules\Participante\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participante extends Model
{
    protected $table = null; // LÓGICO
    
    protected $appends = ['crypt_id', 'full_name'];
    
    public static function all($columns = ['*'])
    {
        return self::query()->limit(100)->get();
    }
    
    public static function query()
    {
        return (new static)->newQuery()->from(self::buildQuery());
    }
    
    private static function buildQuery()
    {
        return DB::table('security_users as u')
            ->join('comun.personas as p', 'u.id_persona', '=', 'p.id_persona')
            ->join('security_profiles_users as pu', 'u.id', '=', 'pu.id_users')
            ->where('pu.id_rol', 5) // PERFIL PARTICIPANTE
            ->where('pu.status', 'activo')
            ->select([
                'u.id',
                'p.id_persona',
                'p.dni as document',
                DB::raw("CONCAT(COALESCE(p.primer_nombre,''), ' ', 
                                COALESCE(p.segundo_nombre,''), ' ',
                                COALESCE(p.primer_apellido,''), ' ',
                                COALESCE(p.segundo_apellido,'')) as full_name"),
                'p.email',
                'u.username',
                'u.active',
                'pu.status as profile_status'
            ]);
    }
    
    public function getCryptIdAttribute()
    {
        return Encryptor::encrypt($this->id);
    }
    
    public function getFullNameAttribute()
    {
        return $this->attributes['full_name'] ?? '';
    }
}
