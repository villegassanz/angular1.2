<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Alumno extends Model
{
	protected $primaryKey='id_alumno';
 
    public $incrementing = true;

    public $timestamps = false;

    public $table = 'alumno';

    protected $fillable = [
        'id_alumno', 'nombre', 'apellido_paterno','apellido_materno','genero',
        'correo', 'password'
    ];  
    //campo que no va a regresar el get
    protected $hidden =[
        'id_rol', 'id_plantel' 
    ]; 
    public function allAlumnos(){
      $alumno = DB::select('SELECT alumno.*  from alumno');
      return $alumno;
    }
     public function getAlumno($id){
        $alumno = DB::select('SELECT alumno.* from alumno where alumno.id_alumno=?',[$id]);
      return $alumno;
    }

    public function login($correo, $password){
         $alumno = DB::select('SELECT alumno.* from alumno WHERE alumno.correo=? AND alumno.password=?',[$correo, $password]);
      return $alumno;
    } 
 
   
    public function saveAlumno(){
        $input = Input::all();
        //$input['password'] = Hash::make($input['password']);
        $alumno = new Alumno();
        $alumno->fill($input);
        $alumno->id_rol=4;
        $alumno->save();
        return $alumno;
    }
  
     public function updateAlumno($id){
        $alumno = self::find($id);
        if(is_null($alumno)){
            return false;
        }
        $input = Input::all();
        $alumno->fill($input);
        $alumno->save();
        return $alumno;
    }
 
    //BAJA DE ALUMNO
    public function deleteUsuario($id){
        $alumno = DB::select('DELETE from  alumno WHERE alumno.id_alumno=?',[$id]);
      return $alumno;
    }
 
    public function llamarProceEstatusCali(){
      DB::unprepared('CALL EstatusCali()');
      return 'hecho';
    }
}
 