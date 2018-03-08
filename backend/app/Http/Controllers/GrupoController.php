<?php

namespace App\Http\Controllers;
use App\Grupo;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class GrupoController extends Controller
{
	protected $grupo=null;
	public function __construct(Grupo $grupo)
	{ 
		$this->grupo=$grupo;
	}
	public function allGrupos(){
		return $this->grupo->allGrupos();
	}
	public function getGrupoById($id){
		$grupo = $this->grupo->getGrupo($id);
		if(!$grupo){
			return Response::json(['response' => 'Grupo no encontrado'], 404);
		}
		return Response::json($grupo, 200);
	}
 
	public function saveGrupo(){
		try {
			$grupo=$this->grupo->saveGrupo();
			return Response::json(['response'=>$grupo],200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function updateGrupo($id){
		$grupo = $this->grupo->updateGrupo($id);
		if(!$grupo){
			return Response::json(['response' => 'Grupo no encontrado'], 404);
		}
		return Response::json($grupo,200);
	}

	public function mostrarGrupoSegunPeriodo(){
		$grupo = $this->grupo->mostrarGrupoSegunPeriodo();
		if(!$grupo){
			return Response::json(['response' => 'Grupo no existe'], 404);
		}
		return Response::json($grupo,200);
	}
	public function getHorarioPorGrupo($id_grupo, $id_plantel){
		$grupo = $this->grupo->getHorarioPorGrupo($id_grupo, $id_plantel);
		if(!$grupo){
			return Response::json(['response' => 'Datos no encontrados'], 404);
		}
		return Response::json($grupo,200);
	}
	public function getHorarioPorGrupoIdAlumno($id_alumno){
		$grupo = $this->grupo->getHorarioPorGrupoIdAlumno($id_alumno);
		if(!$grupo){
			return Response::json(['response' => 'Datos no encontrados'], 404);
		}
		return Response::json($grupo,200);
	}
} 
