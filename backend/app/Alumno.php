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
        'curp', 'password'
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

    public function login($user, $password){
         $alumno = DB::select('SELECT alumno.* from alumno WHERE alumno.nombre=? AND alumno.password=?',[$user, $password]);
      return $alumno;
    } 
    //ALUMNOS QUE ESTAN INHABILITADOS
    public function getAlumnoInhabilitados(){
        $alumno = DB::select('SELECT alumno.*, plantel.localidad AS nombre_plantel, (SELECT grupo.nombre FROM grupo_alumno INNER JOIN grupo ON grupo_alumno.id_grupo = grupo.id_grupo INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo 
          INNER JOIN alumno ON grupo_alumno.id_alumno=alumno.id_alumno
          WHERE grupo_alumno.id_alumno = alumno.id_alumno AND alumno.estado=0 ORDER BY grupo.nombre DESC LIMIT 1)AS nombre_grupo
         FROM alumno INNER JOIN plantel ON plantel.id_plantel = alumno.id_plantel INNER JOIN users ON users.curp = alumno.curp WHERE users.estado=0');
      return $alumno;
    }
    //ALUMNO INHABILITADO
    public function getAlumnobyIdInhabilitado($id){
        $alumno = DB::select('SELECT alumno.*, plantel.localidad AS nombre_plantel, (SELECT grupo.nombre FROM grupo INNER JOIN grupo_alumno ON grupo_alumno.id_grupo = grupo.id_grupo INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo WHERE periodo.estatus=1 AND grupo_alumno.id_alumno = ' ."'$id'" .') AS nombre_grupo FROM alumno INNER JOIN plantel ON plantel.id_plantel = alumno.id_plantel INNER JOIN users ON users.curp = alumno.curp WHERE alumno.id_alumno=' ."'$id'" .' AND users.estado=0');
      return $alumno;
    }
    /*
    public function saveAlumno(){
        $input = Input::all();
        $input['password'] = Hash::make($input['password']);
        $alumno = new Alumno();
        $alumno->id_rol=4;
        $alumno->fill($input);
        $alumno->save();

        return $alumno;
    }*/
    public function saveAlumno(){
        $input = Input::all();
        //$input['password'] = Hash::make($input['password']);
        $alumno = new Alumno();
        $alumno->fill($input);
        $alumno->id_rol=4;
        $alumno->save();
        return $alumno;
    }
    /*
    public function updateAlumno($id){
        $alumno = self::find($id);
        if(is_null($alumno)){
            return false;
        }
        $input = Input::all();
        $alumno->fill($input);
        $alumno->save();
        return $alumno;
    }*/
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
 
   public function alumnosPorPlantel($idPlantel){
        $alumno = DB::select('SELECT alumno.*, 
        plantel.localidad AS nombre_plantel, 
        plantel.clave, 
        IF((SELECT grupo.nombre 
                FROM grupo_alumno 
                INNER JOIN grupo ON grupo_alumno.id_grupo = grupo.id_grupo 
                INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo 
                WHERE periodo.estatus=1 
                AND alumno.id_plantel=? 
                AND grupo_alumno.id_alumno = alumno.id_alumno) IS NOT NULL, (SELECT grupo.nombre 
                FROM grupo_alumno 
                INNER JOIN grupo ON grupo_alumno.id_grupo = grupo.id_grupo 
                INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo 
                WHERE periodo.estatus=1 
                AND alumno.id_plantel=? 
                AND grupo_alumno.id_alumno = alumno.id_alumno), "") AS nombre_grupo 
FROM alumno 
  INNER JOIN plantel ON plantel.id_plantel = alumno.id_plantel 
    INNER JOIN users ON users.curp = alumno.curp 
WHERE alumno.id_plantel=?
  AND users.estado=1', [$idPlantel,$idPlantel,$idPlantel]);
        return $alumno;
      }

    /*
    public function alumnosPorPlantel($idPlantel){
      $alumno = DB::select('SELECT alumno.*, plantel.localidad AS nombre_plantel, plantel.clave, (SELECT grupo.nombre FROM grupo_alumno INNER JOIN grupo ON grupo_alumno.id_grupo = grupo.id_grupo INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo WHERE periodo.estatus=1 AND alumno.id_plantel=' ."'$idPlantel'" .' AND grupo_alumno.id_alumno = alumno.id_alumno) AS nombre_grupo FROM alumno INNER JOIN plantel ON plantel.id_plantel = alumno.id_plantel INNER JOIN users ON users.curp = alumno.curp WHERE alumno.id_plantel=' ."'$idPlantel'" .' AND users.estado=1');
      return $alumno;
    }
     */

    public function getBusqedaPersonalizadaAlumno($miClave, $miValor){
      $alumno = DB::select('SELECT alumno.*, grupo.nombre AS grupo, plantel.clave FROM alumno INNER JOIN grupo_alumno ON alumno.id_alumno=grupo_alumno.id_alumno INNER JOIN grupo ON grupo_alumno.id_grupo = grupo.id_grupo INNER JOIN plantel ON plantel.id_plantel = alumno.id_plantel INNER JOIN users ON alumno.curp = users.curp WHERE alumno.' .$miClave .' = '."'$miValor'" .' AND users.estado=1');
      return $alumno;
    }

    public function alumnosSegunPlantelConNrolista($idPlantel){
        $alumno = DB::select('SELECT COUNT(*)as total from alumno inner JOIN plantel on alumno.id_plantel=plantel.id_plantel and alumno.nLista != "" and alumno.id_plantel='."'$idPlantel'");
        return $alumno;
    }
    //BAJA DE ALUMNO
    public function deleteUsuario($id){
        $alumno = self::find($id);
        if(is_null($alumno)){
            return false;
        }
        $alumno->estado = 0;
        $alumno->save();
        return $alumno;
    }
     //ALTA DE ALUMNO
    public function AltaAlumno($id){
        $alumno = self::find($id);
        if(is_null($alumno)){
            return false;
        }
        $alumno->estado = 1;
        $alumno->save();
        return $alumno;
    }
    //URL DE LA SECUNDARIAS DISPONIBLES
    public function getAllSecundarias(){
        $alumno = DB::select('SELECT concat(secundaria.centro_educativo, " -> ", secundaria.localidad ) AS nombreCompleto, secundaria.clave, secundaria.localidad, secundaria.centro_educativo FROM secundaria');
        return $alumno;
    } 

    public function getAlumnosPorDocenteYMateria($id_docente, $id_materia){
        $alumno = DB::select('SELECT alumno.*, materia.nombre AS nombre_materia, grupo.nombre AS nombre_grupo FROM grupo_materia_docente INNER JOIN grupo_alumno ON grupo_alumno.id_grupo = grupo_materia_docente.id_grupo INNER JOIN alumno ON alumno.id_alumno = grupo_alumno.id_alumno INNER JOIN materia ON materia.id_materia = grupo_materia_docente.id_materia INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo INNER JOIN docente ON docente.id_docente = grupo_materia_docente.id_docente WHERE grupo_materia_docente.id_docente = ? AND grupo_materia_docente.id_materia = ? AND docente.id_plantel = alumno.id_plantel AND alumno.estado = 1 GROUP BY alumno.id_alumno', [$id_docente, $id_materia]);
        return $alumno; 
    }
    public function getAllAlumnosParaPasarSemestre(){
     // DB::unprepared('CALL EstatusCali()');
        $alumno = DB::select('SELECT alumno.id_alumno, alumno.id_plantel, grupo.nombre AS nombre_grupo FROM alumno INNER JOIN grupo_alumno ON alumno.id_alumno=grupo_alumno.id_alumno INNER JOIN grupo ON grupo_alumno.id_grupo=grupo.id_grupo WHERE grupo.nombre!="S6" AND alumno.estado=1');
        return $alumno;
    }
    public function llamarProceEstatusCali(){
      DB::unprepared('CALL EstatusCali()');
      return 'hecho';
    }
}
 