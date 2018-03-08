<?php 
 
namespace App\Http\Controllers;

use App\Estadisticas;

use Illuminate\Http\Request;

use App\Http\Requests; 

use Illuminate\Support\Facades\Response; 
 
class EstadisticasController extends Controller
{
	protected $tabla = null;

    public function __construct(Estadisticas $tabla){
    	$this->tabla = $tabla;
    } 

    public function getTotalAlumnoxGenero(){
		$tabla = $this->tabla->getTotalAlumnoxGenero();
		if(!$tabla){
			return Response::json(['response' => 'SIN DATOS'], 404);
		}
		return Response::json($tabla,200);
	
    }
    public function getAlumnoxGeneroxPlantel($id_plantel){
		$tabla = $this->tabla->getAlumnoxGeneroxPlantel($id_plantel);
		if(!$tabla){
			return Response::json(['response' => 'SIN DATOS'], 404);
		}
		return Response::json($tabla,200);
	
    }
    public function getDatosOfAproOrReproXPeriodoxPlantel($id_periodo, $id_grupo, $id_plantel){
		$tabla = $this->tabla->getDatosOfAproOrReproXPeriodoxPlantel($id_periodo, $id_grupo, $id_plantel);
		if(!$tabla){
			return Response::json(['response' => 'SIN DATOS'], 404);
		}
		return Response::json($tabla,200);
    }

    
	public function getDatosOfEgresadosXPeriodoXPlantel($id_periodo, $id_plantel){
		$tabla = $this->tabla->getDatosOfEgresadosXPeriodoXPlantel($id_periodo, $id_plantel);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron alumnos egresados'], 404);
		}
		return Response::json($tabla,200);
	}
}
