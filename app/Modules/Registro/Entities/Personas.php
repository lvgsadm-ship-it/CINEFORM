<?php

namespace Modules\Registro\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Personas extends Model
{
    use HasFactory;

    protected $table = "comun.personas";
    protected $primaryKey = "id_persona";
    public $timestamps = false;
    protected $fillable = [
        'tipo_dni', 
        'dni', 
        'pasaporte', 
        'rif', 
        'reg_nac_cine', 
        'genero',
        'primer_nombre', 
        'segundo_nombre', 
        'primer_apellido', 
        'segundo_apellido',
        'telefono', 
        'telefono_opcional', 
        'idPais', 
        'idEstado', 
        'idMunicipio', 
        'idParroquia',
        'direccion', 
        'creado_por',
        'creado_en',
        'actualizado_por',
        'actualizado_en'];
    
   /*  protected static function newFactory()
    {
        return \Modules\Registro\Database\factories\PersonasFactory::new();
    } */
/***********************************************************************************************************/
    public function user() //relación inversa
    {
        return $this->belongsTo(\Modules\Security\Entities\User::class);
    }
      public function getCellPhoneAttribute() {
        return $this->getCountry->dial_code . ' ' . $this->phone;
    }
    public function getFullDocumentAttribute() {
        return $this->getDocumentType->code . '-' . $this->document;
    }
    
    function getCountry() {
        return $this->belongsTo(\Modules\Security\Entities\Countries::class, 'country_id');
    }

    public function getDocumentType() {
        return $this->belongsTo(\Modules\Security\Entities\DocumentType::class, 'document_type_id');
    }

    // Relaciones Eloquent

    public function tipoDni()
    {
        return $this->belongsTo(\Modules\Security\Entities\DocumentType::class, 'tipo_dni');
    }

    public function generoRef()
    {
        return $this->belongsTo(\Modules\Security\Entities\Genders::class, 'genero');
    }

    public function pais()
    {
        return $this->belongsTo(\Modules\Security\Entities\Countries::class, 'idPais');
    }

    public function estado()
    {
        return $this->belongsTo(\Modules\Parametros\Entities\Estados::class, 'idEstado');
    }

    public function municipio()
    {
        return $this->belongsTo(\Modules\Parametros\Entities\Municipios::class, 'idMunicipio');
    }

    public function parroquia()
    {
        return $this->belongsTo(\Modules\Parametros\Entities\Parroquias::class, 'idParroquia');
    }

    public function usuario()
    {
        return $this->hasOne(\Modules\Security\Entities\User::class, 'id_persona');
    }

    // Consultas útiles
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->primer_nombre} {$this->segundo_nombre} {$this->primer_apellido} {$this->segundo_apellido}");
    }

    public function getTelefonoCompletoAttribute()
    {
        return $this->pais?->dial_code . ' ' . $this->telefono;
    }

    // Cambiar contraseña a través de la relación usuario
    public function cambiarClave($nuevaClave)
    {
        if ($this->usuario) {
            $this->usuario->password = bcrypt($nuevaClave);
            $this->usuario->save();
            return true;
        }
        return false;
    }

    // Actualizar datos personales (seguro)
    public function actualizarDatos($data)
    {
        $this->fill($data);
        return $this->save();
    }

    // Búsquedas y consultas rápidas
    public static function buscarPorDocumento($tipo_dni, $dni)
    {
        return self::where('tipo_dni', $tipo_dni)->where('dni', $dni)->first();
    }

    public function scopeBuscarPorNombres($query, $primer_nombre = null, $segundo_nombre = null, $primer_apellido = null, $segundo_apellido = null)
    {
        return $query
            ->when($primer_nombre, function ($q) use ($primer_nombre) {
                $q->where('primer_nombre', 'like', "%{$primer_nombre}%");
            })
            ->when($segundo_nombre, function ($q) use ($segundo_nombre) {
                $q->where('segundo_nombre', 'like', "%{$segundo_nombre}%");
            })
            ->when($primer_apellido, function ($q) use ($primer_apellido) {
                $q->where('primer_apellido', 'like', "%{$primer_apellido}%");
            })
            ->when($segundo_apellido, function ($q) use ($segundo_apellido) {
                $q->where('segundo_apellido', 'like', "%{$segundo_apellido}%");
            });
    }

    public static function buscarPorNombres($primer_nombre = null, $segundo_nombre = null, $primer_apellido = null, $segundo_apellido = null)
    {
        return self::when($primer_nombre, function ($query) use ($primer_nombre) {
                    $query->where('primer_nombre', 'like', '%' . $primer_nombre . '%');
                })
                ->when($segundo_nombre, function ($query) use ($segundo_nombre) {
                    $query->where('segundo_nombre', 'like', '%' . $segundo_nombre . '%');
                })
                ->when($primer_apellido, function ($query) use ($primer_apellido) {
                    $query->where('primer_apellido', 'like', '%' . $primer_apellido . '%');
                })
                ->when($segundo_apellido, function ($query) use ($segundo_apellido) {
                    $query->where('segundo_apellido', 'like', '%' . $segundo_apellido . '%');
                })
                ->get();
    }
}
