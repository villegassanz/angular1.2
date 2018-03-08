<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Grupo extends Model 
{
    protected $primaryKey='id_grupo';
    public $incrementing = true;
    public $timestamps =false;
    public $table ='grupo';

     
    protected $fillable = [
        'id_grupo','nombre', 'id_periodo'
    ];
    protected $hidden =[
        
    ];
    public function allGrupos(){
        return self::all();
    }
    public function getGrupo($id){
        $grupo = self::find($id);
        if(is_null($grupo)){
            return false;
        }
        return $grupo;
    }
    public function updateGrupo($id){
        $grupo = self::find($id);
        if(is_null($grupo)){
            return false;
        }
        $input = Input::all();
        $grupo->fill($input);
        $grupo->save();
        return $grupo;
    }
    
    public function saveGrupo(){
        $input = Input::all();
        //$input['password'] = Hash::make($input['password']);
        /*$grupo = new Grupo();
        $grupo->fill($input); 
        $grupo->save();
        return $grupo; */ 
        $mensaje=" ";
        $inputArray = Input::all();
        foreach ($inputArray as $key => $input) {
            $existe = DB::select('SELECT * FROM grupo WHERE grupo.id_periodo = ? AND grupo.nombre=?', [$input['id_periodo'], $input['nombre']]);
            if(!$existe){
                DB::table('grupo')->insert(
                ['nombre' => $input['nombre'], 'id_periodo' => $input['id_periodo']]
                );
                $mensaje="GRUPOS ASIGNADOS";
            }else{
                $mensaje="LOS GRUPOS YA EXISTEN";
            }
        }
        return $mensaje;
    }

    public function mostrarGrupoSegunPeriodo(){
       // $grupo = DB::table('periodo')->select('name', 'email as user_email')->get();
       /* $grupo = DB::table('periodo')
            ->join('grupo', 'periodo.id_periodo', '=', 'grupo.id_periodo')
            ->where('periodo.estatus', '=', 1)
            ->select('grupo.nombre','periodo.nombre as n_p','grupo.id_grupo' )
            ->get();*/
        $grupo = DB::select('SELECT grupo.* from grupo INNER JOIN periodo on grupo.id_periodo=periodo.id_periodo WHERE periodo.estatus=1 ORDER BY grupo.nombre');
        return $grupo;
    }

     public function getHorarioPorGrupo($id_grupo, $id_plantel){
        $grupo = DB::select('SELECT grupo_materia_docente.*, docente.nombre as nombre_docente, grupo.nombre as nombre_grupo, materia.nombre as nombre_materia FROM grupo_materia_docente
            INNER JOIN docente on grupo_materia_docente.id_docente=docente.id_docente
            INNER JOIN grupo on grupo_materia_docente.id_grupo=grupo.id_grupo 
            INNER JOIN materia on grupo_materia_docente.id_materia=materia.id_materia
            INNER JOIN periodo ON periodo.id_periodo=grupo.id_periodo
            where periodo.estatus=1 AND grupo.id_grupo=? AND docente.id_plantel=?',[$id_grupo, $id_plantel]);
        return $grupo; 
    }
     public function getHorarioPorGrupoIdAlumno($id_alumno){
        $grupo = DB::select('SELECT grupo_alumno.id_grupo FROM grupo_alumno INNER JOIN grupo ON grupo_alumno.id_grupo=grupo.id_grupo INNER JOIN periodo ON periodo.id_periodo=grupo.id_periodo
        WHERE periodo.estatus=1 AND grupo_alumno.id_alumno=' ."'$id_alumno'");
        return $grupo;
    }
}