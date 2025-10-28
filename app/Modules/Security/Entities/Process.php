<?php

namespace Modules\Security\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EncryptationId;

class Process extends Model
{
    use HasFactory,
        EncryptationId;
    protected $table = "security_processes";
    protected $appends = ['crypt_id',  'profile_array'];
    protected $hidden = ['id'];
    public $timestamps = false;
    
    
   
    protected $fillable = [];
   
    
   


    public function getProfileArrayAttribute() {
        $list_id = array();
        foreach ($this->getProfile as $key => $value) {
            $list_id[] = $value->id;
        }
        return $list_id;
    }

    public function getProfile() {
        return $this->belongsToMany(Profile::class, 'security_profile_processes')
                        ->withPivot('profile_id', 'process_id', 'actions')
                ;
    }
}
