<?php 

namespace App\Http\Controllers;
use App\Plantel;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response; 

class PlantelController extends Controller
{
	protected $plantel=null;
	public function __construct(Plantel $plantel)
	{
		$this->plantel=$plantel;
	}
	public function allPlanteles(){
		return $this->plantel->allPlanteles();
	}
	
	public function getPlantel($id){
		$plantel = $this->plantel->getPlantel($id);
		if(!$plantel){
			return Response::json(['response'=>'plantel no encontrado'],404);
		}
		return Response::json($plantel,200);
	}

	public function updatePlantel($id){
		$plantel = $this->plantel->updatePlantel($id);
		if(!$plantel){
			return Response::json(['response' => 'Plantel no encontrado'], 404);
		}
		return Response::json($plantel,200);
	}

	public function savePlantel(){
		try {
			return $this->plantel->savePlantel();
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function getPlantelesporRango($numInicio, $numTama){
        $plantel = $this->plantel->getPlantelesporRango($numInicio, $numTama);
        if(!$plantel){
            return Response::json(['response' => 'No existe el plantel'], 404);
        }
        return Response::json($plantel,200);
    }
    
    public function getMunicipios(){
        $plantel = $this->plantel->getMunicipios();
        if(!$plantel){
            return Response::json(['response' => 'No existen registros'], 404);
        }
        return Response::json($plantel,200);
    }
    public function getDistritos($id_distrito){
        $plantel = $this->plantel->getDistritos($id_distrito);
        if(!$plantel){
            return Response::json(['response' => 'No existen registros'], 404);
        }
        return Response::json($plantel,200);
    }
    public function getRegiones(){
        $plantel = $this->plantel->getRegiones();
        if(!$plantel){
            return Response::json(['response' => 'No existen registros'], 404);
        }
        return Response::json($plantel,200);
    }
    public function getLocalidadesSegunCodigoPostal($codigoPostal){
        $plantel = $this->plantel->getLocalidadesSegunCodigoPostal($codigoPostal);
        if(!$plantel){
            return Response::json(['response' => 'No existen registros'], 404);
        }
        return Response::json($plantel,200);
    }
    public function getLocalidadesSegunIdmunicipio($id_municipio){
        $plantel = $this->plantel->getLocalidadesSegunIdmunicipio($id_municipio);
        if(!$plantel){
            return Response::json(['response' => 'No existen registros'], 404);
        }
        return Response::json($plantel,200);
    }
    /*
    public function addCambiosPlanteles($idPlantel){
		$plantel = $this->plantel->addCambiosPlanteles($idPlantel);
		return Response::json(['response' => 'OK' ], 200);
	}*/
}
