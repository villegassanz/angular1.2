<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Periodo extends Model 
{
	protected $primaryKey='id_periodo';
	public $incrementing = true;
	public $timestamps =false;
	public $table ='periodo';

	
    protected $fillable = [
        'id_periodo','nombre', 'inicio','fin', 'estatus'
    ];
    protected $hidden =[
        
    ];
    public function allPeriodos(){
        $periodo = DB::select('SELECT * FROM periodo ORDER BY nombre DESC',[]);
        return $periodo;
        //return self::all();
    }
    public function getPeriodoActivo(){
        $periodo = DB::select('SELECT * FROM periodo WHERE periodo.estatus=1');
        return $periodo;
    }
    public function getPeriodo($id){
        $periodo = self::find($id);
        if(is_null($periodo)){
            return false;
        }
        return $periodo;
    }
    public function updatePeriodo($id){
        $periodo = self::find($id);
        if(is_null($periodo)){
            return false;
        }
        $input = Input::all();
        $periodo->fill($input);
        $periodo->save();
        return $periodo;
    }
    public function savePeriodo(){
        $input = Input::all();
        $input['estatus'] = 2;
        $periodo = new Periodo();
        $periodo->fill($input);
        $periodo->save();

        return $periodo;
    }
    public function periodoEnEspera(){
        $periodo = DB::select('SELECT * FROM periodo WHERE periodo.estatus=2');
        return $periodo;
    }
    public function getgruposPeriodoEnEspera(){
        $periodo = DB::select('SELECT periodo.id_periodo, periodo.nombre AS nombre_periodo, grupo.id_grupo, grupo.nombre AS nombre_grupo FROM periodo INNER JOIN grupo ON periodo.id_periodo=grupo.id_periodo WHERE periodo.estatus=2');
        return $periodo;
    }  
    public function eliminarPeriodoNuevo($id_periodo){
        $periodo = DB::select('DELETE FROM periodo WHERE periodo.id_periodo='."'$id_periodo'");
        return "SE ELIMINO";
    } 

    public function getGruposXPeriodo($id_periodo){
        $periodo = DB::select('SELECT grupo.* FROM grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE periodo.id_periodo = ? ORDER BY grupo.nombre',[$id_periodo]);
        return $periodo;
    }
}