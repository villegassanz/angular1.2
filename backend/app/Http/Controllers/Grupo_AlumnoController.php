<?php

namespace App\Http\Controllers;
use App\Grupo_Alumno;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class Grupo_AlumnoController extends Controller
{
	protected $grupo_alumno=null;
	public function __construct(Grupo_Alumno $grupo_alumno)
	{
		$this->grupo_alumno=$grupo_alumno;
	}
	public function saveGrupoAlumno(){
		try {
			$this->grupo_alumno->saveGrupoAlumno();
			return Response::json(['response'=>'ok____'],200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function savegrupoMateriaDocente(){
		try {
			$this->grupo_alumno->savegrupoMateriaDocente();
			return Response::json(['response'=>'ok____'],200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	//SE UTILIZA PARA OBTENER LAS MATERIAS A SUBIR CALIFICACIONES DE LOS ALUMNOS
	public function getMateriasAsignarCalificaciones($id_docente, $id_grupo){
		$materias = $this->grupo_alumno->getMateriasAsignarCalificaciones($id_docente, $id_grupo);
		return Response::json($materias,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	}

	//OBTENER ALUMNOS POR GRUPO PARA QUE EL DOCNETE ASIGNE CALIFICACIONES
	public function getAlumnosPorGrupoAsignarCalif($id_plantel, $id_grupo){
		$getalumnos = $this->grupo_alumno->getAlumnosPorGrupoAsignarCalif($id_plantel, $id_grupo);
		return Response::json($getalumnos,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	}
	public function guardarCalificaciones(){
		try {
			$tabla = $this->grupo_alumno->guardarCalificaciones(); 
			return Response::json($tabla,200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function guardarCalificacionesPendientes(){
		try {
			$tabla = $this->grupo_alumno->guardarCalificacionesPendientes(); 
			return Response::json($tabla,200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function getCalificacionesxMateria($id_docente, $id_grupo,$id_materia){
		$grupo_alumno = $this->grupo_alumno->getCalificacionesxMateria($id_docente, $id_grupo,$id_materia);
		return Response::json($grupo_alumno,200);
	}
	public function getMateriasYaCursadas($grupo, $plantel){
		$grupo_alumno = $this->grupo_alumno->getMateriasYaCursadas($grupo, $plantel);
		return Response::json($grupo_alumno,200);
	}
	public function getPeridosYaCursados($periodo){
		$grupo_alumno = $this->grupo_alumno->getPeridosYaCursados($periodo);
		return Response::json($grupo_alumno,200);
	}
	public function getGruposDelPeridoCursado($semestre, $Nombreperiodo){
		$grupo_alumno = $this->grupo_alumno->getGruposDelPeridoCursado($semestre, $Nombreperiodo);
		return Response::json($grupo_alumno,200);
	}
	public function getCalificacionesxGrupo($id_grupo, $id_alumno){
		$grupo_alumno = $this->grupo_alumno->getCalificacionesxGrupo($id_grupo, $id_alumno);
		return Response::json($grupo_alumno,200);
	}
	public function getCalificacionesxMateriaVista($id_docente, $id_grupo,$id_materia){
		$grupo_alumno = $this->grupo_alumno->getCalificacionesxMateriaVista($id_docente, $id_grupo,$id_materia);
		return Response::json($grupo_alumno,200);
	}
	public function SaveAlumnosAlSigSemestre(){ 
		$getalumnos = $this->grupo_alumno->SaveAlumnosAlSigSemestre();
		return Response::json($getalumnos,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	}
	public function getAlumnosPendientes($id_docente){ 
		$grupo_alumno = $this->grupo_alumno->getAlumnosPendientes($id_docente);
		return Response::json($grupo_alumno,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	}
	public function getCalificacionesSemestreActual($id_alumno){ 
		$grupo_alumno = $this->grupo_alumno->getCalificacionesSemestreActual($id_alumno);
		return Response::json($grupo_alumno,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	} 
	public function getIDsToCalificacion($id_grupo, $id_plantel){ 
		$grupo_alumno = $this->grupo_alumno->getIDsToCalificacion($id_grupo, $id_plantel);
		return Response::json($grupo_alumno,200); 
		// return Response::json(['response' => 'Evento creado' ], 200);
	} 
}
 