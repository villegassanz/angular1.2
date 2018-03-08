<?php 
 
namespace App\Http\Controllers;

use App\Globales;

use Illuminate\Http\Request;

use App\Http\Requests; 

use Illuminate\Support\Facades\Response; 

class GlobalesController extends Controller
{
	protected $tabla = null;

    public function __construct(Globales $tabla){
    	$this->tabla = $tabla;
    }
 
	public function getTamaTabla($nomTabla){
		$tabla = $this->tabla->getTamaTabla($nomTabla);
		if(!$tabla){
			return Response::json(['response' => 'La tabla no existe'], 404);
		}
		return Response::json($tabla,200);
	}

	public function getUltimoRegistro($nomTabla){
		$tabla = $this->tabla->getUltimoRegistro($nomTabla);
		if(!$tabla){
			return Response::json(['response' => 'La tabla no existe'], 404);
		}
		return Response::json($tabla,200);
	}

	public function insertHistorialUsuario($id_usuario, $tipo_usuario, $accion, $id_objetivo, $tipo_objetivo, $campos_cambiados, $valores_antiguos, $valores_nuevos){
		try {
			$this->tabla->insertHistorialUsuario($id_usuario, $tipo_usuario, $accion, $id_objetivo, $tipo_objetivo, $campos_cambiados, $valores_antiguos, $valores_nuevos);
			return Response::json(['response' => 'Resgistro Agregado a Historial_Usuario' ], 200);
		} catch (\Illuminate\Database\QueryException $ex) {
			//echo "entro al catch";
			return Response::json(['response'=>$ex],500);
			//return Response::json(['response'=>$ex->getCode()],500);
		}
	}

	public function getUltimosRegistrosIngresados($rol, $usuario, $id_plantel, $accion, $horas){
		$tabla = $this->tabla->getUltimosRegistrosIngresados($rol, $usuario, $id_plantel, $accion, $horas);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron registros'], 404);
		}
		return Response::json($tabla,200);
	} 

	public function getIDSyTIPOconCURPS($curp_usuario, $rol_usuario, $curp_objetivo, $rol_objetivo){
		$tabla = $this->tabla->getIDSyTIPOconCURPS($curp_usuario, $rol_usuario, $curp_objetivo, $rol_objetivo);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron registros'], 404);
		}
		return Response::json($tabla,200);
	} 

	public function getIDSyTIPOconCURPStoNoUsers($curp_usuario, $rol_usuario){
		$tabla = $this->tabla->getIDSyTIPOconCURPStoNoUsers($curp_usuario, $rol_usuario);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron registros'], 404);
		}
		return Response::json($tabla,200);
	} 
 
	public function getDatosToCertificadoPDF($id_alumno){
		$tabla = $this->tabla->getDatosToCertificadoPDF($id_alumno);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron registros'], 404);
		}
		return Response::json($tabla,200);
	}
	public function getDatosToKardexPDF($id_alumno){
		$tabla = $this->tabla->getDatosToKardexPDF($id_alumno);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron registros'], 404);
		}
		return Response::json($tabla,200);
	}

	public function getDatosToLista($id_docente, $id_materia){
		$tabla = $this->tabla->getDatosToLista($id_docente, $id_materia);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron registros'], 404);
		}
		return Response::json($tabla,200);
	}

	public function getDatosGenerales(){
		$tabla = $this->tabla->getDatosGenerales();
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron datos'], 404);
		}
		return Response::json($tabla,200);
	}

	public function setDatosGenerales($tipo){
		try {
			$tabla = $this->tabla->setDatosGenerales($tipo); 
			return Response::json(['response'=>'ok____'],200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}

	public function getDatosValidacionesGenerales($usuario){
		$tabla = $this->tabla->getDatosValidacionesGenerales($usuario);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron datos'], 404);
		}
		return Response::json($tabla,200);
	}

	public function getHistorialUsuario(){
		$tabla = $this->tabla->getHistorialUsuario();
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron datos en el historial'], 404);
		}
		return Response::json($tabla,200);
	}

	public function getCarrerasAceptadas(){
		$tabla = $this->tabla->getCarrerasAceptadas();
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron datos de las carreras'], 404);
		}
		return Response::json($tabla,200);
	}

	public function getCarreraById($id_carrera){
		$tabla = $this->tabla->getCarreraById($id_carrera);
		if(!$tabla){
			return Response::json(['response' => 'no se encontro la carrera'], 404);
		}
		return Response::json($tabla,200);
	}

	public function updateOrAddCarrera($tipo){
		try {
			$salida = $this->tabla->updateOrAddCarrera($tipo);
			//print_r($salida);
			return Response::json(['response'=>$salida],200);
		} catch (\Illuminate\Database\QueryException $ex) {
			return Response::json(['response'=>$ex->getCode()],500);
		}
	}
	
	public function uploadImgs(){
		try {
			$salida = $this->tabla->uploadImgs();
			return Response::json(['response' => 'Imagenes Agregadas Exitosamente'], 200);
		} catch (\Illuminate\Database\QueryException $ex) {
			//echo "entro al catch";
			return Response::json(['response'=>$ex],500);
			//return Response::json(['response'=>$ex->getCode()],500);
		}
	}

	public function getUploadImgs(){
		$tabla = $this->tabla->getUploadImgs();
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron imagenes'], 404);
		}
		return Response::json($tabla,200);
	}

	public function createEventEvalucionExtraEspecial($evento, $nombre, $fecha, $hora, $minuto){
		try {
			$this->tabla->createEventEvalucionExtraEspecial($evento, $nombre, $fecha, $hora, $minuto);
			return Response::json(['response' => 'Evento Creado Exitosamente'], 200);
		} catch (\Illuminate\Database\QueryException $ex) {
			//echo "entro al catch";
			return Response::json(['response'=>$ex],500);
			//return Response::json(['response'=>$ex->getCode()],500);
		}
	} 

	public function getAlumntosEgresadosToCertificadoPDF($id_plantel){
		$tabla = $this->tabla->getAlumntosEgresadosToCertificadoPDF($id_plantel);
		if(!$tabla){
			return Response::json(['response' => 'no se encontraron alumnos egresados'], 404);
		}
		return Response::json($tabla,200);
	}
}
