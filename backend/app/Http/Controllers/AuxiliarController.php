<?php

namespace App\Http\Controllers;
use App\Auxiliar;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class AuxiliarController extends Controller
{
	protected $Auxiliar=null;
	public function __construct(Auxiliar $auxiliar)
	{
		$this->auxiliar=$auxiliar;
	}
	public function allAuxiliares(){
		return $this->auxiliar->allAuxiliares();
	}
	public function getAuxiliar($id){
		$auxiliar = $this->auxiliar->getAuxiliar($id);
		if(!$auxiliar){
			return Response::json(['response' => 'Auxiliar no encontrado'], 404);
		}
		return Response::json($auxiliar, 200);
	}
	public function updateAuxiliar($id){
		$auxiliar = $this->auxiliar->updateAuxiliar($id);
		if(!$auxiliar){
			return Response::json(['response' => 'Auxiliar no encontrado'], 404);
		}
		return Response::json($auxiliar,200);
	}
	public function saveAuxiliar(){
		try {
			return $this->auxiliar->saveAuxiliar();
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		} 
	}
}
