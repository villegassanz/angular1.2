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
	public function login($user, $password){
		$alumno = $this->alumno->login($user, $password);
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
	public function getAlumnobyIdInhabilitado($id){
		$alumno = $this->alumno->getAlumnobyIdInhabilitado($id);
		if(!$alumno){
			return Response::json(['response' => 'Alumno no encontrado'],404);
		}
		return Response::json($alumno,200);
	}

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

	

	public function alumnosPorPlantel($idPlantel){
		$alumno = $this->alumno->alumnosPorPlantel($idPlantel);
		if(!$alumno){
			return Response::json(['response' => 'Alumno no existe'], 404);
		}
		return Response::json($alumno,200);
	}

	public function getBusqedaPersonalizadaAlumno($miClave, $miValor){
		$alumno = $this->alumno->getBusqedaPersonalizadaAlumno($miClave, $miValor);
		if(!$alumno){
			return Response::json(['response' => 'Alumno con los parametros no existe'], 404);
		}
		return Response::json($alumno,200);
	}
	public function alumnosSegunPlantelConNrolista($idPlantel){
		$alumno = $this->alumno->alumnosSegunPlantelConNrolista($idPlantel);
		if(!$alumno){
			return Response::json(['response' => 'Alumno no existe'], 404);
		}
		return Response::json($alumno,200);
	}
	//DAR DE BAJ UN ALUMNO
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
    //URL DE LA SECUNDARIAS DISPONIBLES
    public function getAllSecundarias(){
		$alumno = $this->alumno->getAllSecundarias();
		if(!$alumno){
			return Response::json(['response' => 'Secundaria no existe'], 404);
		}
		return Response::json($alumno,200);
	}
	//OBTENER LOS ALUMNOS DE UN DOCENTE SEGUN SU MATERIA
	public function getAlumnosPorDocenteYMateria($id_docente, $id_materia){
		$alumno = $this->alumno->getAlumnosPorDocenteYMateria($id_docente, $id_materia);
		if(!$alumno){
			return Response::json(['response' => 'Secundaria no existe'], 404);
		}
		return Response::json($alumno,200);
	}
	public function getAllAlumnosParaPasarSemestre(){
		$alumno = $this->alumno->getAllAlumnosParaPasarSemestre();
		if(!$alumno){
			return Response::json(['response' => 'Alumnos no existen'], 404);
		}
		return Response::json($alumno,200);
	}
	public function llamarProceEstatusCali(){
		try{ 
    	  $valor = $this->alumno->llamarProceEstatusCali();
    	  return Response::json(['response' => $valor],200);
      } catch(\Illuminate\Database\QueryException $ex){
      	return Response::json(['response' => $ex->getCode()],500);
      }
	}
	
}
