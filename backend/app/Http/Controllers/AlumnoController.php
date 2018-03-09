<?php
 
namespace App\Http\Controllers;

use App\Alumno;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class AlumnoController extends Controller
{ 
	protected $alumno = null;

    public function __construct(Alumno $alumno){
    	$this->alumno = $alumno;
    }

    public function allAlumnos(){
    		return $this->alumno->allAlumnos();
    	//return User::orderBy('id', 'asc')->get();
		 
	}
	///////////////////////////////////////
	public function login($correo, $password){
		$alumno = $this->alumno->login($correo, $password);
		if(!$alumno){
			return Response::json(['response' => 'user no encontrado'],404);
		}
		return Response::json($alumno,200);
	}
	public function getAlumno($id){
		$alumno = $this->alumno->getAlumno($id);
		if(!$alumno){
			return Response::json(['response' => 'Alumno no encontrado'],404);
		}
		return Response::json($alumno,200);
	}
	//ALUMNOS QUE ESTAN INHABILITADOS
	public function getAlumnoInhabilitados(){
		$alumno = $this->alumno->getAlumnoInhabilitados();
		if(!$alumno){
			return Response::json(['response' => 'Alumno no encontrado'],404);
		}
		return Response::json($alumno,200);
	} 
	//alumno inhabilitado
	
	public function saveAlumno(){
		try{
    	  return $this->alumno->saveAlumno();
      } catch(\Illuminate\Database\QueryException $ex){
      	return Response::json(['response' => $ex->getCode()],500);
      }
	}

	public function updateAlumno($id){
		$alumno = $this->alumno->updateAlumno($id);
		if(!$alumno){
		return Response::json(['response' => 'Alumno no encontrado'], 404);
		}
		return Response::json($alumno, 200);
	}

	public function deleteUsuario($id){
        $alumno = $this->alumno->deleteUsuario($id);
        if(!$alumno){
        return Response::json(['response' => $alumno], 404);
        }
        return Response::json($alumno, 200);
    }
    //DAR DE ALTA UN ALUMNO
	public function AltaAlumno($id){
        $alumno = $this->alumno->AltaAlumno($id);
        if(!$alumno){
        return Response::json(['response' => $alumno], 404);
        }
        return Response::json($alumno, 200);
    }

	
}
