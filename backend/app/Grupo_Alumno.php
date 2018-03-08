<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Grupo_Alumno extends Model  
{
    //protected $primaryKey='id_plantel';
    public $incrementing = false;
    public $timestamps =false;
    public $table ='grupo_alumno';

    
    protected $fillable = [
        'id_alumno','id_grupo','id_plantel'
    ];
    protected $hidden =[
        
    ]; 
    public function saveGrupoAlumno(){
         $input = Input::all();
         $existe = DB::select('SELECT * FROM grupo_alumno WHERE grupo_alumno.id_alumno = ' .$input['id_alumno']);
         if(!$existe){ 
            DB::table('grupo_alumno')->insert(['id_alumno' => $input['id_alumno'], 'id_grupo' => $input['id_grupo'], 'id_plantel' => $input['id_plantel']]); 
            //DB::insert('INSERT INTO token_password (correo, token) VALUES (?, ?)', [$email, $token]);
         }else{
            DB::update('UPDATE grupo_alumno SET grupo_alumno.id_grupo = ? WHERE grupo_alumno.id_alumno = ?', [$input['id_grupo'], $input['id_alumno']]);
         }
        /*$input = Input::all();
         $existe = DB::select('SELECT * FROM grupo_alumno WHERE grupo_alumno.id_alumno = ' .$input['id_alumno']);
         var_dump($input);*/
         /*$input = Input::all();
        DB::table('grupo_alumno')->insert(
        ['id_alumno' => $input['id_alumno'], 'id_grupo' => $input['id_grupo'], 'id_plantel' => $input['id_plantel']]
        );*/
    }
    public function SaveAlumnosAlSigSemestre(){
         $inputArray = Input::all();
         foreach ($inputArray as $key => $input) {
            DB::table('grupo_alumno')->insert(['id_alumno' => $input['id_alumno'], 'id_grupo' => $input['id_grupo'], 'id_plantel' => $input['id_plantel']]);
         }    
    }
    public function savegrupoMateriaDocente(){
        //print_r(" - entro al modelo - ");
        $inputArray = Input::all();
        //$input = Input::get('horarioDocente');
        foreach ($inputArray as $key => $input) {
            DB::table('grupo_materia_docente')->insert(
            ['id_docente' => $input['id_docente'], 'id_grupo' => $input['id_grupo'], 'id_materia' => $input['id_materia'],'dia' => $input['dia'],'hora' => $input['hora']]
            );
        } 
    }
    /*
    public function savegrupoMateriaDocente(){
        $input = Input::all();
        DB::table('grupo_materia_docente')->insert(
        ['id_docente' => $input['id_docente'], 'id_grupo' => $input['id_grupo'], 'id_materia' => $input['id_materia'],'dia' => $input['dia'],'hora' => $input['hora']]
        );
        
    }*/
     
    //SE UTILIZA PARA OBTENER LAS MATERIAS A SUBIR CALIFICACIONES DE LOS ALUMNOS
   public function getMateriasAsignarCalificaciones($id_docente, $id_grupo){
        $materias = DB::select('SELECT grupo_materia_docente.*, materia.nombre AS nombreMateria, grupo.nombre AS nombreGrupo FROM grupo_materia_docente
            INNER JOIN materia on grupo_materia_docente.id_materia=materia.id_materia 
            INNER JOIN grupo on grupo_materia_docente.id_grupo= grupo.id_grupo
            WHERE grupo_materia_docente.id_docente = ? AND grupo.id_grupo=? GROUP BY materia.nombre',[$id_docente, $id_grupo]);
        return $materias; 
    }
    //OBTENER ALUMNOS POR GRUPO PARA QUE EL DOCNETE ASIGNE CALIFICACIONES
      public function getAlumnosPorGrupoAsignarCalif($id_plantel, $id_grupo){
        $getalumnos = DB::select('SELECT grupo_alumno.*, alumno.numero_control as numero_control, alumno.nombre as nombre, alumno.apellido_paterno AS apellido_paterno, alumno.apellido_materno as apellido_materno, grupo.nombre AS nombreGrupo
            FROM grupo_alumno INNER JOIN alumno  ON grupo_alumno.id_alumno = alumno.id_alumno 
            INNER JOIN grupo ON grupo_alumno.id_grupo= grupo.id_grupo WHERE alumno.estado=1 AND grupo_alumno.id_plantel = ? AND grupo.id_grupo = ?', [$id_plantel, $id_grupo]);
        return $getalumnos; 
    }

    public function guardarCalificaciones(){
        $inputArrayCalif = Input::all();
        //$input = Input::get('horarioDocente');
        foreach ($inputArrayCalif as $key => $input) {
            $existe = DB::select('SELECT calificacion.* FROM calificacion WHERE calificacion.id_alumno = ' .$input['id_alumno'] .' AND ((calificacion.id_grupo = ' .$input['id_grupo'] .' AND calificacion.id_materia = ' .$input['id_materia'] .') AND calificacion.id_docente = ' .$input['id_docente'] .')');
/*
            $curp_alumno=DB::select('SELECT alumno.curp, alumno.id_alumno, calificacion.evaluacion1, calificacion.evaluacion2, calificacion.evaluacionFinal FROM calificacion INNER JOIN alumno ON alumno.id_alumno = calificacion.id_alumno WHERE calificacion.id_alumno = ? GROUP BY alumno.curp LIMIT 1', [$input['id_alumno']]);*/

            if(!$existe){ 
                DB::table('calificacion')->insert(['id_alumno' => $input['id_alumno'], 'id_grupo' => $input['id_grupo'], 'id_materia' => $input['id_materia'],'id_docente' => $input['id_docente'], 'evaluacion1' => $input['evaluacion1'], 'evaluacion2' => $input['evaluacion2'], 'evaluacionFinal' => $input['evaluacionFinal']]
                );
             }else{
                DB::update('UPDATE calificacion SET calificacion.evaluacion1 = ?, calificacion.evaluacion2 = ?, calificacion.evaluacionFinal = ? WHERE calificacion.id_alumno = ? AND ((calificacion.id_grupo = ? AND calificacion.id_materia = ?) AND calificacion.id_docente = ?)', [$input['evaluacion1'], $input['evaluacion2'], $input['evaluacionFinal'], $input['id_alumno'], $input['id_grupo'], $input['id_materia'], $input['id_docente']]);
             }
             //return $curp_alumno;
        }  
        //$input = Input::all();
         //return $curp_alumno;
    } 

    public function guardarCalificacionesPendientes(){
        $inputArrayCalif = Input::all();
        //$input = Input::get('horarioDocente');
        foreach ($inputArrayCalif as $key => $input) {
                DB::update('UPDATE calificacion SET calificacion.extraordinario1 = ?, calificacion.extraordinario2 = ?, calificacion.especial = ? WHERE calificacion.id_alumno = ? AND ((calificacion.id_grupo = ? AND calificacion.id_materia = ?) AND calificacion.id_docente = ?)', [$input['extraordinario1'], $input['extraordinario2'], $input['especial'], $input['id_alumno'], $input['id_grupo'], $input['id_materia'], $input['id_docente']]);
                /*
                if($input['extraordinario1']>=6 || $input['extraordinario2']>=6 || $input['especial']>=6){
                    DB::update('UPDATE calificacion SET calificacion.estatus="APROBO" WHERE calificacion.id_alumno = ? AND calificacion.id_grupo = ? AND calificacion.id_materia =? AND calificacion.id_docente =?',[$input['id_alumno'], $input['id_grupo'], $input['id_materia'], $input['id_docente']]);
                }else{
                    DB::update('UPDATE calificacion SET calificacion.estatus="REPROBO" WHERE calificacion.id_alumno = ? AND calificacion.id_grupo = ? AND calificacion.id_materia =? AND calificacion.id_docente =?',[$input['id_alumno'], $input['id_grupo'], $input['id_materia'], $input['id_docente']]);
                } 
                 */
            } 
        //$input = Input::all();
         //return $curp_alumno;
    } 
/*
    public function getCalificacionesxMateria($id_docente, $id_grupo,$id_materia){
        $tabla = DB::select('SELECT calificacion.* FROM calificacion WHERE calificacion.id_docente = ' .$id_docente .' AND calificacion.id_grupo = ' .$id_grupo .' AND calificacion.id_materia=' ."'$id_materia'");
        return $tabla;
    } */
     public function getCalificacionesxMateria($id_docente, $id_grupo,$id_materia){
        $tabla = DB::select('SELECT calificacion.*, (SELECT alumno.curp FROM alumno WHERE calificacion.id_alumno = alumno.id_alumno) AS curp FROM calificacion WHERE calificacion.id_docente = ' .$id_docente .' AND calificacion.id_grupo = ' .$id_grupo .' AND calificacion.id_materia=' ."'$id_materia'");
        return $tabla;
    }

    public function getMateriasYaCursadas($grupo, $plantel){
        $tabla = DB::select('SELECT materia.*, docente.id_docente FROM materia INNER JOIN docente on docente.campo_disciplinario = materia.campo_disciplinario AND docente.id_plantel = ' .$plantel .' WHERE materia.semestre = ' .$grupo .' GROUP BY materia.nombre ORDER BY materia.semestre');
        return $tabla;
    }

    public function getPeridosYaCursados($periodo){
        $tabla=DB::select('SELECT * FROM periodo WHERE periodo.nombre>=' ."'$periodo'" .' AND periodo.estatus !=1  GROUP BY periodo.nombre');
        return $tabla;
    }
    public function getGruposDelPeridoCursado($semestre, $Nombreperiodo){
        $tabla=DB::select('SELECT grupo.* FROM grupo INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo WHERE SUBSTRING(grupo.nombre, 2)= ' .$semestre .' AND periodo.nombre='."'$Nombreperiodo'");
        return $tabla;
    }
     public function getCalificacionesxGrupo($id_grupo, $id_alumno){
        $tabla = DB::select('SELECT calificacion.* FROM calificacion WHERE calificacion.id_grupo = ' .$id_grupo .' and calificacion.id_alumno=' ."'$id_alumno'");
        return $tabla;
    } 
    public function getCalificacionesxMateriaVista($id_docente, $id_grupo,$id_materia){
            $tabla = DB::select('SELECT calificacion.*, alumno.nombre AS nombre_Alumno, alumno.apellido_paterno, alumno.apellido_materno, grupo.nombre AS nombre_Grupo FROM calificacion 
                INNER JOIN alumno ON calificacion.id_alumno=alumno.id_alumno 
                INNER JOIN grupo ON calificacion.id_grupo=grupo.id_grupo
                INNER JOIN docente ON calificacion.id_docente=docente.id_docente
                INNER JOIN materia ON calificacion.id_materia=materia.id_materia
                WHERE docente.id_docente=' .$id_docente .' AND grupo.id_grupo=' .$id_grupo .' AND materia.id_materia=' ."'$id_materia'");
        return $tabla;
    }

    public function getAlumnosPendientes($id_docente){
        $tabla=DB::select('SELECT alumno.id_alumno, materia.id_materia, grupo.id_grupo, materia.nombre AS nombre_materia, calificacion.extraordinario1, calificacion.extraordinario2, calificacion.especial, alumno.nombre, alumno.apellido_paterno, alumno.apellido_materno, grupo.nombre AS nombre_Grupo FROM calificacion 
                INNER JOIN alumno ON calificacion.id_alumno=alumno.id_alumno 
                INNER JOIN grupo ON calificacion.id_grupo=grupo.id_grupo
                INNER JOIN docente ON calificacion.id_docente=docente.id_docente
                INNER JOIN materia ON calificacion.id_materia=materia.id_materia
                WHERE docente.id_docente= ? AND alumno.estatusSemestreCal = "PENDIENTE" AND
                calificacion.estatus="REPROBO"
                ',[$id_docente]);
        return $tabla;
    }
    public function getCalificacionesSemestreActual($id_alumno){
        $tabla = DB::select('SELECT calificacion.*, materia.nombre AS nombre_materia, periodo.nombre AS nombre_periodo,
             plantel.localidad AS nombre_plantel,
            CONCAT(alumno.nombre," ",alumno.apellido_paterno," ",alumno.apellido_materno) AS nombre_completo FROM calificacion
            INNER JOIN grupo ON grupo.id_grupo=calificacion.id_grupo
            INNER JOIN periodo ON periodo.id_periodo=grupo.id_periodo
            INNER JOIN materia ON materia.id_materia=calificacion.id_materia
            INNER JOIN alumno ON alumno.id_alumno=calificacion.id_alumno
            INNER JOIN plantel ON plantel.id_plantel=alumno.id_plantel
            WHERE periodo.estatus=1 AND calificacion.id_alumno=' ."'$id_alumno'");
        return $tabla;
    }
    public function getIDsToCalificacion($id_grupo, $id_plantel){
        $tabla = DB::select('SELECT grupo_materia_docente.id_docente, grupo_materia_docente.id_grupo, grupo_materia_docente.id_materia FROM
            grupo_materia_docente 
            INNER JOIN grupo ON grupo.id_grupo = grupo_materia_docente.id_grupo 
            INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo
            INNER JOIN docente ON docente.id_docente=grupo_materia_docente.id_docente
            WHERE periodo.estatus = 1 AND grupo_materia_docente.id_grupo = ? AND docente.id_plantel=? GROUP BY grupo_materia_docente.id_materia',[$id_grupo, $id_plantel]);
        return $tabla;
    } 
}