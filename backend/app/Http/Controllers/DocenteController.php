<?php  

namespace App\Http\Controllers;
use App\Docente;
use Illuminate\Http\Request;
  
use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class DocenteController extends Controller
{
	protected $docente=null;
	public function __construct(Docente $docente)
	{
		$this->docente=$docente;
	}
	public function allDocentes(){
		return $this->docente->allDocentes();
	}
	public function allResponsables(){
		return $this->docente->allResponsables();
	}

	public function getDocenteBy($id){
		$docente = $this->docente->getDocenteBy($id);
		if(!$docente){
			return Response::json(['response' => 'Docente no encontrado'], 404);
		}
		return Response::json($docente, 200);
	}
	//DAR DE BAJ UN DOCENTE
	public function deleteDocente($id){
        $docente = $this->docente->deleteDocente($id);
        if(!$docente){
        return Response::json(['response' => $docente], 404);
        }
        return Response::json($docente, 200);
    }

	public function saveDocente(){
		try {
			return $this->docente->saveDocente();
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	public function updateDocente($id){
		$docente = $this->docente->updateDocente($id);
		if(!$docente){
			return Response::json(['response' => 'Docente no encontrado'], 404);
		}
		return Response::json($docente,200);
	}
	public function habilitarDocente($id){
		$docente = $this->docente->habilitarDocente($id);
		if(!$docente){
			return Response::json(['response' => 'Docente no encontrado'], 404);
		}
		return Response::json($docente,200);
	}
	public function docentesSegunPlantel($idPlantel){
		$docente = $this->docente->docentesSegunPlantel($idPlantel);
		if(!$docente){
			return Response::json(['response' => 'Docente no existe'], 404);
		}
		return Response::json($docente,200);
	}
	public function getPlantelesconPermiso(){
		$docente = $this->docente->getPlantelesconPermiso();
		if(!$docente){
			return Response::json(['response' => 'No existen planteles'], 404);
		}
		return Response::json($docente,200);
	}
	public function getDirectoresconPermisoRango($numInicio, $numTama){
		$docente = $this->docente->getDirectoresconPermisoRango($numInicio, $numTama);
		if(!$docente){
			return Response::json(['response' => 'No existen planteles'], 404);
		}
		return Response::json($docente,200);
	}
	public function getPermisoDirector($idPlantel){
		$docente = $this->docente->getPermisoDirector($idPlantel);
		if(!$docente){
			return Response::json(['response' => 'No existen el director'], 404);
		}
		return Response::json($docente,200); 
	}
	public function inhabilitar($fecha_completa, $hora, $minuto, $rol, $estado_Ins){
		$aux = $this->docente->inhabilitar($fecha_completa, $hora, $minuto, $rol, $estado_Ins);
		return Response::json(['response' => 'Evento creado' ], 200);
	}
	public function updateDocentesPermisos($id_rol){
		print_r("entro al controlador - ");
		try {
			print_r("entro al try del controlador - ");
			$this->docente->updateDocentesPermisos($id_rol);
		} catch (\Illuminate\Database\QueryException $ex) {
			print_r("entro al catch del controlador - ");
			return Response::json(['response'=>$ex],500);
		}
		print_r("salio del controlador - ");
	}
	public function getEstadoDeInscripcion(){
		$docente = $this->docente->getEstadoDeInscripcion();
		return Response::json($docente,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	}
	public function getCargaAcademica($id_plantel){
		$docente = $this->docente->getCargaAcademica($id_plantel);
		return Response::json($docente,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	} 
	//VER EL HORARIO DEL DOCENTE
	public function getCargaAcademicaDocente($id_docente){
		$docente = $this->docente->getCargaAcademicaDocente($id_docente);
		return Response::json($docente,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	}/* 
	public function getMateriasDelDocente($id_docente){
		$docente = $this->docente->getMateriasDelDocente($id_docente);
		return Response::json($docente,200); 
		//return Response::json(['response' => 'Evento creado' ], 200);
	} */
	public function getDatosporDocenteGrupoMateria($id_docente, $id_grupo, $id_materia){
		$docente = $this->docente->getDatosporDocenteGrupoMateria($id_docente, $id_grupo, $id_materia);
		if(!$docente){
			return Response::json(['response' => 'Datos no encontrado'], 404);
		}
		return Response::json($docente, 200);
	} 
	public function getIdDocenteApartirDeCurp($curp){
		$docente = $this->docente->getIdDocenteApartirDeCurp($curp);
		if(!$docente){
			return Response::json(['response' => 'Datos no encontrado'], 404);
		}
		return Response::json($docente, 200);
	} 
	public function getDocenteInhabilitados(){
		$docente = $this->docente->getDocenteInhabilitados();
		if(!$docente){
			return Response::json(['response' => 'Datos no encontrado'], 404);
		}
		return Response::json($docente, 200);
	}
	public function getDocenteInhabilitadoById($idDocente){
		$docente = $this->docente->getDocenteInhabilitadoById($idDocente);
		if(!$docente){
			return Response::json(['response' => 'Datos no encontrado'], 404);
		}
		return Response::json($docente, 200);
	}
	public function updateCambiarDePlantelDocente($id){
		$docente = $this->docente->updateCambiarDePlantelDocente($id);
		if(!$docente){
			return Response::json(['response' => 'Datos no encontrado'], 404);
		}
		return Response::json($docente, 200);
	}

	public function getMateriasSimple($id_docente){
		$docente = $this->docente->getMateriasSimple($id_docente);
		if(!$docente){
			return Response::json(['response' => 'materias no encontradas'], 404);
		}
		return Response::json($docente, 200);
	}
	public function getCarrerasAceptadas(){
		$docente = $this->docente->getCarrerasAceptadas();
		if(!$docente){
			return Response::json(['response' => 'no hay carreras'], 404);
		}
		return Response::json($docente, 200);
	}
	public function getTotalDocenteOrResponsableByPlantel($tipo, $id_plantel){
		$docente = $this->docente->getTotalDocenteOrResponsableByPlantel($tipo, $id_plantel);
		if(!$docente){
			return Response::json(['response' => 'no hay carreras'], 404);
		}
		return Response::json($docente, 200);
	} 
	public function getMateriasYaAgregadasEnCargaAca($id_docente, $id_grupo){
		$docente = $this->docente->getMateriasYaAgregadasEnCargaAca($id_docente, $id_grupo);
		if(!$docente){
			return Response::json(['response' => 'no hay materias'], 404);
		}
		return Response::json($docente, 200);
	}
}  
