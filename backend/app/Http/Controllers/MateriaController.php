<?php

namespace App\Http\Controllers;
use App\Materia;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class MateriaController extends Controller
{
	protected $materia=null;
	public function __construct(Materia $materia)
	{
		$this->materia=$materia;
	}
	public function allMaterias(){
		return $this->materia->allMaterias();
	}
	public function getMateria($id){
		$materia = $this->materia->getMateria($id);
		if(!$materia){
			return Response::json(['response' => 'Materia no encontrado'], 404);
		}
		return Response::json($materia, 200);
	}
	public function saveMateria(){
		try {
			return $this->materia->saveMateria();
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function updateMateria($id){
		$materia = $this->materia->updateMateria($id);
		if(!$materia){
			return Response::json(['response' => 'Materia no encontrado'], 404);
		}
		return Response::json($materia,200);
	}
	public function getMateriasSegunSemestre($semestre){
		$materia = $this->materia->getMateriasSegunSemestre($semestre);
		if(!$materia){
			return Response::json(['response' => 'No existen materias'], 404);
		}
		return Response::json($materia,200); 
	}
	public function getDocenteCamDisAndSemestre($capo_disc, $idDocente, $semestre){
		$materia = $this->materia->getDocenteCamDisAndSemestre($capo_disc, $idDocente, $semestre);
		if(!$materia){
			return Response::json(['response' => 'No existen docente con ese campo'], 404);
		}
		return Response::json($materia,200); 
	}
}
