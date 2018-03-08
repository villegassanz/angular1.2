<?php 

namespace App;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Plantel extends Model 
{
	protected $primaryKey='id_plantel';
	public $incrementing = true;
	public $timestamps =false;
	public $table ='plantel';

	
    protected $fillable = [
        'id_plantel','modalidad', 'clave','localidad','municipio','distrito','region'
    ];
    protected $hidden =[
        
    ];
    public function allPlanteles(){
         $plantel = DB::select('SELECT plantel.*, concat(plantel.modalidad, " - ",  plantel.localidad) AS nombreCompleto , concat(plantel.id_plantel, "-",  plantel.localidad) AS datosCompletos FROM plantel');
        return $plantel;
    }
    public function getPlantel($id){
        $plantel = DB::select('SELECT plantel.* FROM plantel WHERE plantel.id_plantel = ' .$id);
        if(is_null($plantel)){
            return false; 
        }
        return $plantel;
    }
    public function updatePlantel($id){
        $plantel = self::find($id);
        if(is_null($plantel)){
            return false;
        }
        $input = Input::all();
        $plantel->fill($input);
        $plantel->save();
        return $plantel;
    }
    public function savePlantel(){
        $input = Input::all();
        //$input['password'] = Hash::make($input['password']);
        $plantel = new Plantel();
        $plantel->fill($input);
        $plantel->save();

        return $plantel;
    }
    public function getPlantelesporRango($numInicio, $numTama){
         $plantel = DB::select('SELECT * from plantel LIMIT ' .$numInicio .',' .$numTama);
        return $plantel;
    }
    public function getMunicipios(){
         $plantel = DB::select('SELECT CONCAT(municipio.cveMunicipio, "-", municipio.nomMunicipio) AS IdMunicipio, municipio.* FROM municipio');
        return $plantel;
    }
    public function getDistritos($id_distrito){
         $plantel = DB::select('SELECT * FROM distrito INNER JOIN region ON distrito.id_region = region.id WHERE region.id=?',[$id_distrito]);
        return $plantel;
    }
     public function getLocalidadesSegunCodigoPostal($codigoPostal){
         $plantel = DB::select('SELECT * FROM municipiocodigopostal INNER JOIN municipio ON municipiocodigopostal.idMunicipio=municipio.idMunicipio 
            INNER JOIN localidad ON localidad.cveMunicipioL=municipio.cveMunicipio WHERE municipiocodigopostal.codigoPostal=?', [$codigoPostal]);
        return $plantel;
    }
    public function getRegiones(){
         $plantel = DB::select('SELECT CONCAT(region.id,"-",region.region) AS IdRegion, region.* FROM region');
        return $plantel;
    }
    public function getLocalidadesSegunIdmunicipio($id_municipio){
         $plantel = DB::select('SELECT * FROM localidad INNER JOIN municipio ON municipio.cveMunicipio=localidad.cveMunicipioL WHERE municipio.cveMunicipio=?',[$id_municipio]);
        return $plantel;
    }

/*
    public function addCambiosPlanteles($idPlantel){
         $plantel=DB::unprepared('INSERT IGNORE INTO planteles_cambios(id_plantel) VALUES ('
            .$idPlantel .')');
        return $plantel;
    } */
}