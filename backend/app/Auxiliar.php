<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Auxiliar extends Model 
{
    protected $primaryKey='id_auxiliar';
    public $incrementing = true;
    public $timestamps =false;
    public $table ='auxiliar';

     
    protected $fillable = [
        'id_auxiliar','nombre','apellido_paterno','apellido_materno','fecha_nacimiento','genero','curp', 'puesto', 'correo', 'password', 'altaAlumno', 'editarAlumno','bajaAlumno','asignarAlumnoGrupo','generarCertificado','generarKardex','historialAlumno', 'altaDocente', 'editarDocente', 'bajaDocente','altaResponsable','bajaResponsable','identificador'
    ];
    protected $hidden =[
        'password','id_rol'
    ];
    public function allAuxiliares(){
        return self::all();
    }
    public function getAuxiliar($id){
        $auxiliar = self::find($id);
        if(is_null($auxiliar)){
            return false;
        }
        return $auxiliar;
    }

    public function saveAuxiliar(){
        $input = Input::all();
        $input['password'] = Hash::make($input['password']);
        $auxiliar = new Auxiliar();
        $auxiliar->id_rol=1;
        $auxiliar->fill($input);
        $auxiliar->save();

        return $auxiliar;
    }
    public function updateAuxiliar($id){
        $auxiliar = self::find($id);
        if(is_null($auxiliar)){
            return false;
        }
        $input = Input::all();
        $auxiliar->fill($input);
        $auxiliar->save();
        return $auxiliar;
    }
}