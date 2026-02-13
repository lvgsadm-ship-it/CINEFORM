<?php
namespace Modules\Participante\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Encryptor;

class Profile extends Model
{
    protected $table = 'security_profiles';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'name', 'description', 'active', 'user_id', 
        'register_date', 'ip'
    ];
    
    public $appends = ['crypt_id'];
    
    public function getCryptIdAttribute()
    {
        return Encryptor::encrypt($this->id);
    }
    
    // 🔍 Método estático para obtener ID del perfil "Participante"
    public static function getParticipanteProfileId()
    {
        return self::where('name', 'Participante')->first()->id ?? 3;
    }
}
