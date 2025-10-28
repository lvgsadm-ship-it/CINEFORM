<?php

namespace Modules\Security\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EncryptationId;

class Profile extends Model {

    use HasFactory,
        EncryptationId;

    protected $table = "security_profiles";
    protected $hidden = ['id', 'user_id'];
    protected $appends = ['crypt_id'];
    public $timestamps = false;
    
    
    public function getPermissions(){
        return $this->belongsToMany(Process::class, 'security_profile_processes')
                        ->withPivot('process_id', 'profile_id', 'actions');
    }
    
    
    
    
}
