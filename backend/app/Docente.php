<?php 

namespace App; 
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Docente extends Model 
{
	protected $primaryKey='id_docente';
	public $incrementing = true;
	public $timestamps =false;
	public $table ='docente';

	
    protected $fillable = [
        'id_docente','id_plantel','nombre', 'apellido_paterno','apellido_materno','fecha_nacimiento','genero','curp','codigo_postal','correo','municipio','localidad','colonia','calle','numero','password','puesto', 'localidad_plantel', 'rfc', 'licenciatura', 'postgrado','telefono','campo_disciplinario','permiso','identificador'
    ];
    protected $hidden =[
        'password','id_rol','permiso_add'
    ];
    public function allDocentes(){
        $docente = DB::select('SELECT docente.* from docente where docente.estado=1 AND docente.puesto="DOCENTE" ');
        return $docente;
    }
    public function allResponsables(){
        $docente = DB::select('SELECT docente.* from docente where docente.estado=1 AND docente.puesto="RESPONSABLE"');
        return $docente;
    }
    public function getDocenteBy($id){
        $docente = DB::select('SELECT * from docente where docente.id_docente='."'$id'");
        return $docente;
    }
    public function saveDocente(){
        $input = Input::all();
        $input['password'] = Hash::make($input['password']);
        $docente = new Docente();
        if($input['puesto'] == 'RESPONSABLE'){
            $docente->id_rol=2;   
        }else{
            $docente->id_rol=3;
        }
        $docente->permiso=0;
        $docente->estado=1;  
        $docente->fill($input);
        $docente->save();

        return $docente;
    }
    //BAJA DE DOCENTE
    public function deleteDocente($id){
        $docente = self::find($id);
        if(is_null($docente)){
            return false;
        }
        $docente->estado = 0;
        $docente->save();
        return $docente;
    }
    public function updateDocente($id){
        $docente = self::find($id);
        if(is_null($docente)){
            return false;
        }
        $input = Input::all();
        /*if(isset($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }*/
        if($input['puesto'] == 'RESPONSABLE'){
                $docente->id_rol=2;   
            }else{
                $docente->id_rol=3;
        }
        $docente->fill($input);
        $docente->save();
        return $docente;
    }
    public function habilitarDocente($id){
        $docente = self::find($id);
        if(is_null($docente)){
            return false;
        }
        $input = Input::all();
        $docente->fill($input);
        $docente->estado=1;
        $docente->save();
        return $docente;
    } 
    public function docentesSegunPlantel($idPlantel){
        $docente = DB::select('SELECT * from docente 
        INNER JOIN plantel 
        on plantel.id_plantel= docente.id_plantel and docente.id_rol=3 and plantel.id_plantel='."'$idPlantel'");
        return $docente;
    } 
    public function getPlantelesconPermiso(){
        $docente = DB::select('SELECT plantel.id_plantel, docente.id_docente, plantel.modalidad, plantel.clave, plantel.localidad, docente.permiso, docente.permiso AS permiso_origen from plantel INNER JOIN docente on plantel.id_plantel= docente.id_plantel and docente.id_rol=2');
        return $docente; 
    } 
    /*
    public function getDirectoresconPermisoRango($numInicio, $numTama){
        $docente = DB::select('SELECT plantel.id_plantel, docente.id_docente, plantel.modalidad, plantel.clave, plantel.localidad, docente.permiso from plantel INNER JOIN docente on plantel.id_plantel= docente.id_plantel and docente.id_rol=2 LIMIT ' .$numInicio .',' .$numTama);
        return $docente;
    }
 */
    public function getPermisoDirector($idPlantel){
        $docente = DB::select('SELECT docente.permiso from docente INNER JOIN plantel on plantel.id_plantel= docente.id_plantel AND docente.id_rol=2 AND plantel.id_plantel ='."'$idPlantel'");
        return $docente;
    }
    public function updateDocentesPermisos($id_rol){
        print_r(" - entro al modelo - ");
        $docente = Input::get('docentesPermisos');
        //print_r($docente);
        foreach ($docente as $key => $docenteObj) {
            DB::table('docente')
            ->where([
                ['id_docente','=', $docenteObj["id_docente"]],
                ['id_rol','=', $id_rol]
                ])->update($docenteObj);
        } 
        print_r("salio del modelo");
    } 
    public function inhabilitar($fecha_completa, $hora, $minuto, $rol, $estado_Ins){

        DB::unprepared('DROP EVENT IF EXISTS actualiza_permiso'); 

        DB::unprepared('DROP TABLE IF EXISTS info_eventos');

        $docente=DB::unprepared('CREATE TABLE info_eventos (evento varchar(20) 
                                COLLATE utf8mb4_unicode_ci NOT NULL, 
                                estado int(11) NOT NULL) ENGINE=InnoDB 
                                DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        //$existe_registro = DB::select('SELECT info_eventos.* FROM info_eventos WHERE info_eventos.evento = "inscripciones",[]);

        //$docente=DB::unprepared('CREATE TABLE planteles_cambios (id_plantel int(3),PRIMARY KEY(id_plantel))');
 
        $docente=DB::unprepared("INSERT INTO info_eventos (evento, estado) 
                                VALUES ('inscripciones',$estado_Ins)");

       /*$docente=DB::unprepared('CREATE EVENT actualiza_permiso'.' ON '.' SCHEDULE AT "'.$fecha_completa.' '.$hora.':'.$minuto.':00.000000" DO UPDATE docente SET docente.permiso=0 where docente.id_rol='.$rol);*/
       $docente=DB::unprepared('CREATE EVENT actualiza_permiso'.' ON '.' SCHEDULE AT "'.$fecha_completa.' '.$hora.':'.$minuto.':00.000000" DO CALL UpNoLista()');
       //CREATE EVENT actualiza_numero ON  SCHEDULE AT "2017-07-27 08:59:00" DO UPDATE calificaciones SET calificaciones.editable_B1 = 0;
       return $docente;
    }
    //PARA SABER SI EL DOCENTE TIENE PERMISO DE INSCRIPCION EN AUMNOCONTROLER
    public function getEstadoDeInscripcion(){
        $docente = DB::select('SELECT estado FROM info_eventos WHERE evento="inscripciones"');
        return $docente;
    }
    //PARA SABER SI QUE HORAS YA ESTAN OCUPADAS EN EL PLANTEL ESPECIFICADO EN CARGA ACADEMICA DEL FRONTEND 
    public function getCargaAcademica($id_plantel){
        $docente = DB::select('SELECT grupo_materia_docente.* FROM grupo_materia_docente INNER JOIN docente ON docente.id_docente = grupo_materia_docente.id_docente INNER JOIN plantel ON plantel.id_plantel = docente.id_plantel INNER JOIN grupo ON grupo.id_grupo=grupo_materia_docente.id_grupo INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo where periodo.estatus=1 AND plantel.id_plantel =' .$id_plantel);
        return $docente;
    }
    //PARA VER EL HORARIO DEL DOCENTE
    public function getCargaAcademicaDocente($id_docente){
        $docente = DB::select('
            SELECT grupo_materia_docente.*,  
                docente.nombre AS nombre_docente,
                docente.apellido_paterno AS apellido_paterno_docente,
                docente.apellido_materno AS apellido_materno_docente,
                grupo.nombre as nombre_grupo, 
                materia.nombre AS nombre_materia 
            FROM grupo_materia_docente
                INNER JOIN docente ON grupo_materia_docente.id_docente=docente.id_docente
                INNER JOIN grupo ON grupo_materia_docente.id_grupo=grupo.id_grupo 
                INNER JOIN materia ON grupo_materia_docente.id_materia=materia.id_materia
                INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo
            WHERE periodo.estatus=1 
                AND grupo_materia_docente.id_docente=' .$id_docente);
        return $docente;
    }

    public function getMateriasYaAgregadasEnCargaAca($id_docente, $id_grupo){
        $docente = DB::select('
           SELECT grupo_materia_docente.*
            FROM grupo_materia_docente
                INNER JOIN docente ON grupo_materia_docente.id_docente=docente.id_docente
                INNER JOIN grupo ON grupo_materia_docente.id_grupo=grupo.id_grupo 
                INNER JOIN materia ON grupo_materia_docente.id_materia=materia.id_materia
                INNER JOIN periodo ON grupo.id_periodo=periodo.id_periodo
            WHERE periodo.estatus=1 
                AND grupo_materia_docente.id_docente=? AND grupo_materia_docente.id_grupo=? 
                GROUP by grupo_materia_docente.id_materia', [$id_docente, $id_grupo]);
        return $docente;
    }
    /*
    public function getMateriasDelDocente($id_docente){
        $docente = DB::select('SELECT grupo_materia_docente.id_docente, materia.nombre as nombre_materia FROM grupo_materia_docente
            INNER JOIN materia on grupo_materia_docente.id_materia=materia.id_materia
            where grupo_materia_docente.id_docente = ' ."'$id_docente'" .' GROUP BY materia.nombre');
        return $docente;
    }*/

    public function getDatosporDocenteGrupoMateria($id_docente, $id_grupo, $id_materia){
        $docente = DB::select('SELECT * FROM (SELECT CONCAT(docente.nombre, " ", docente.apellido_paterno , " ", docente.apellido_materno) AS nombre_docente FROM docente WHERE docente.id_docente = ' ."'$id_docente'" .') AS t1
        ,(SELECT grupo.nombre AS nombre_grupo FROM grupo WHERE grupo.id_grupo =' ."'$id_grupo'" .') AS t2,(SELECT materia.nombre AS nombre_materia FROM materia WHERE materia.id_materia = ' ."'$id_materia'" .') AS t3');
        return $docente;
    }
    //OBTENER DOCENTE APARTIR DE LA TABAL USERS Y DOCENTE, PARA OBTENER LAS MATERIAS DEL DOCENTE EN SU CARGA ACADEMICA (TABLA=GRUPO_MATERIA_DOCENTE)
    public function getIdDocenteApartirDeCurp($curp){
        $docente = DB::select('SELECT users.nombre,users.apellido_paterno,users.apellido_materno,users.curp, users.id_rol, docente.id_docente FROM users INNER JOIN docente ON users.curp=docente.curp WHERE docente.curp = ' ."'$curp'" .' AND docente.id_rol=3 AND users.id_rol=3');
        return $docente;
    }
    public function getDocenteInhabilitados(){
        $docente = DB::select('SELECT docente.* FROM docente WHERE docente.estado=0');
        return $docente;
    }
    public function getDocenteInhabilitadoById($idDocente){
        $docente = DB::select('SELECT docente.* FROM docente WHERE docente.id_docente=' .$idDocente);
        return $docente;
    }
    public function updateCambiarDePlantelDocente($id){
        $docente = self::find($id);
        if(is_null($docente)){
            return false;
        }
        $input = Input::all();
        $docente->estado=1;
        //omP[0]['localidad'];
        $docente->fill($input);
        $docente->save();
        return $docente;
        /*$input = Input::all();
        $docente = DB::update('UPDATE docente SET docente.id_plantel = ?, docente.estado = 1 WHERE docente.id_docente = ?',[$input['id_plantel'], $input['id_docente']]);
        return $docente;*/
    }
 
    public function getMateriasSimple($id_docente){
        $docente = DB::select('SELECT materia.* FROM materia INNER JOIN grupo_materia_docente ON grupo_materia_docente.id_materia = materia.id_materia INNER JOIN grupo ON grupo_materia_docente.id_grupo=grupo.id_grupo INNER JOIN periodo ON
            grupo.id_periodo=periodo.id_periodo WHERE periodo.estatus=1 AND grupo_materia_docente.id_docente = ? GROUP BY materia.nombre', [$id_docente]);
        return $docente;
    }
    public function getCarrerasAceptadas(){
        $docente = DB::select('SELECT * FROM carreras_aceptadas');
        return $docente;
    }  
    public function getTotalDocenteOrResponsableByPlantel($tipo, $id_plantel){
        if($tipo == "DOCENTE"){
            $docente = DB::select('SELECT COUNT(docente.puesto) AS total_docentes FROM docente WHERE docente.puesto = "DOCENTE" AND docente.id_plantel = ? AND docente.estado = 1', [$id_plantel]);
        }
        if ($tipo == "RESPONSABLE") {
            $docente = DB::select('SELECT COUNT(docente.puesto) AS total_responsable FROM docente WHERE docente.puesto = "RESPONSABLE" AND docente.id_plantel = ? AND docente.estado = 1', [$id_plantel]);
        }
        return $docente;
    } 
    
} 