<?php

namespace Modules\Security\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\EncryptationId;
use Modules\Comun\Entities\PersonalData;

class User extends Authenticatable
{

    use HasApiTokens,
        HasFactory,
        Notifiable,
        EncryptationId;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "security_users";
    protected $fillable = [
        'username',
        'email',
        'password',
    ];
    public $timestamps = false;
    /* protected $appends = ['cell_phone', 'full_document']; */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        /* 'password',
        'username', */
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    public function getCellPhoneAttribute()
    {
        return $this->getCountry?->dial_code ? ($this->getCountry->dial_code . ' ' . $this->phone) : ($this->phone ?? null);
    }
    public function getFullDocumentAttribute()
    {
        return $this->getDocumentType?->code ? ($this->getDocumentType->code . '-' . $this->document) : ($this->document ?? null);
    }

    function getProfile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    function getCountry()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }

    /*   
      function getOffices() {
          return $this->belongsToMany(\Modules\Library\Entities\Offices::class, 'library_office_users', 'user_id', 'office_id');
      }
       */
    public function getDocumentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function getPerfiles()
    {
        return $this->belongsToMany(
            \Modules\Security\Entities\Profile::class,
            'security_profiles_users',  // Nombre tabla pivote
            'id_users',                 // FK usuario en pivote
            'id_rol'                   // FK perfil en pivote
        )
        ->select('security_profiles.*')
        ->withPivot('id_rol','status', 'fecha_aprobacion', 'aprobado_por', 'creado_por', 'creado_en', 'actualizado_por', 'actualizado_en');        
    } 

    public function getPerfilesArray()
    {
        $perfiles = $this->getPerfiles; // obtiene los perfiles relacionados
        $resultado = [];

        foreach ($perfiles as $perfil) {
            $resultado[] = $perfil->id;
        }

        return $resultado;
    }

    public function getProfiles()
    {
        return $this->hasMany(ProfileUser::class, 'id_users');
    }

    public function getPersona()
    {
        return $this->hasOne(\Modules\Registro\Entities\Personas::class, 'user_id', 'id'); // Ajusta foreign key si es necesario
    }

    public function personalData()
    {
        return $this->hasOne(\Modules\Comun\Entities\PersonalData::class, 'user_id', 'id');
    }

    public function getIdPersonaAttribute()
    {
        return $this->getPersona?->id_persona;
    }

    public function getModules()
    {
        $Modules = Modulo::join('security_menus', 'security_menus.module_id', '=', 'security_modules.id')
            ->join('security_processes', 'security_processes.menu_id', '=', 'security_menus.id')
            ->join('security_profile_processes', 'security_profile_processes.process_id', '=', 'security_processes.id')
            ->where('security_profile_processes.profile_id', session()->get('profile_id')) //$this->profile_id)
            ->groupBy("security_modules.id", "security_modules.name", "security_modules.description", "security_modules.icon", "security_modules.order")
            ->select('security_modules.*')
            ->get()
            //->toSql()
        ;
        return $Modules;
    }

    public function getProcesses()
    {
        $Processes = Modulo::join('security_menus', 'security_menus.module_id', '=', 'security_modules.id')
            ->join('security_processes', 'security_processes.menu_id', '=', 'security_menus.id')
            ->join('security_profile_processes', 'security_profiles_processes.process_id', '=', 'security_processes.id')
            ->where('security_profile_processes.profile_id', session()->get('profile_id')) //$this->profile_id)
            ->groupBy("security_processes.id", "security_processes.name", "security_processes.description", "security_processes.icon", "security_processes.route", "security_processes.actions", "security_processes.order")
            ->select('security_processes.*')
            ->get()
            //->toSql()
        ;
        return $Processes;
    }

    public function captureMenu()
    {

        if (!session()->get('MODULE') == null) {
            return $this->getMenu(session()->get('MODULE'));
        } else {
            return [];
        }
    }

    public function capturePerfil()
    {
        if (session()->get('MODULE') == null) {
            return $this->getPerfiles->toArray();
        } else {
            return [];
        }
    }

    public function getMenu($Module)
    {
        $Menu = Menu::orderBy('order')->where("module_id", $Module)->with('getProcess')->get()->toArray();
        foreach ($Menu as $key => $value) {
            foreach ($value['get_process'] as $key2 => $value2) {
                if (!in_array(/* $this->profile_id */ session()->get('profile_id'), $value2['profile_array'])) {
                    unset($Menu[$key]['get_process'][$key2]);
                    unset($Menu[$key]['process'][$key2]);
                }
            }
            if (count($Menu[$key]['get_process']) == 0) {
                unset($Menu[$key]);
            }
        }
        return $Menu;
    }

    public function getShortNameAttribute()
    {
        if ($this->document_type->is_natural === false) {
            $name = ucwords(Lower($this->full_name));
        } else {
            $nameParts = explode(' ', $this->full_name);
            $name = ucwords(Lower(implode(' ', array_slice($nameParts, 0, 2))));
        }
        return $name;
    }

    public function verifyPermission($route = null)
    {

        $position_point = strpos($route, '.');
        if ($position_point === false) {
            $routeFather = $route;
            $routeSon = "";
        } else {

            $nameRoute = explode(".", $route);

            $routeFather = $nameRoute[1];
            $routeSon = $nameRoute[0];
        }

        $has_permision = Process::join("security_profile_processes", "security_profile_processes.process_id", "security_processes.id")
            ->select("*")
            ->where("route", $routeFather)
            ->where("profile_id", session()->get('profile_id')) //$this->profile_id)
            ->first();

        if ($has_permision != null) {
            if ($routeSon == '') {
                return true;
            } else {
                $actions = explode("|", $has_permision->actions);
                if (in_array($routeSon, $actions)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}
