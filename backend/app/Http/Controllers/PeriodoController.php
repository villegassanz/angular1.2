<?php

namespace App\Http\Controllers;
use App\Periodo;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class PeriodoController extends Controller
{
	protected $periodo=null;
	public function __construct(Periodo $periodo)
	{
		$this->periodo=$periodo;
	}
	public function allPeriodos(){
		return $this->periodo->allPeriodos();
	}
	public function getPeriodoById($id){
		$periodo = $this->periodo->getPeriodo($id);
		if(!$periodo){
			return Response::json(['response' => 'Periodo no encontrado'], 404);
		}
		return Response::json($periodo, 200);
	}
	public function updatePeriodo($id){
		$periodo = $this->periodo->updatePeriodo($id);
		if(!$periodo){
			return Response::json(['response' => 'Periodo no encontrado'], 404);
		}
		return Response::json($periodo,200);
	}
	public function savePeriodo(){
		try {
			return $this->periodo->savePeriodo();
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function periodoEnEspera(){
		$periodo = $this->periodo->periodoEnEspera();
		if(!$periodo){
			return Response::json(['response' => 'No hay periodos en espera'], 404);
		}
		return Response::json($periodo, 200);
	} 
	public function getPeriodoActivo(){
		$periodo = $this->periodo->getPeriodoActivo();
		if(!$periodo){
			return Response::json(['response' => 'No hay periodos en espera'], 404);
		}
		return Response::json($periodo, 200);
	}
	public function getgruposPeriodoEnEspera(){
		$periodo = $this->periodo->getgruposPeriodoEnEspera();
		if(!$periodo){
			return Response::json(['response' => 'No hay grupos en periodo de espera'], 404);
		}
		return Response::json($periodo, 200);
	}
	public function eliminarPeriodoNuevo($id_periodo){
		try {
			$periodo = $this->periodo->eliminarPeriodoNuevo($id_periodo);
			return Response::json(['response'=>$periodo],200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	} 
	public function getGruposXPeriodo($id_periodo){
		$periodo = $this->periodo->getGruposXPeriodo($id_periodo);
		if(!$periodo){
			return Response::json(['response' => 'No hay grupos en este periodo'], 404);
		}
		return Response::json($periodo, 200);
	}
}
