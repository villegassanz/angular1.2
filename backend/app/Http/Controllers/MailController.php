<?php

namespace App\Http\Controllers;

use App\Correo;
use Illuminate\Http\Request;

use Mail;
use Session;
 
use App\Http\Requests;
use Illuminate\Support\Facades\Response;

class MailController extends Controller
{
 
    protected $tabla = null;

    public function __construct(Correo $tabla){
        $this->tabla = $tabla;
    }

    public function checkEmail($correo, $user){
        $tabla = $this->tabla->checkEmail($correo, $user);
        if(!$tabla){
            return Response::json(['response' => 'No existe el email'], 404);
        }
        return Response::json($tabla,200);
    }

    public function comprobarEstadoEmailUsuario($identificador, $pass){
        $tabla = $this->tabla->comprobarEstadoEmailUsuario($identificador, $pass);
        if(!$tabla){
            return Response::json(['response' => 'Usuario no encontrado'], 404);
        }
        return Response::json($tabla, 200);
    }

    public function getTokenToChangePassword($email, $user){
        $tabla = $this->tabla->getTokenToChangePassword($email, $user);
        if(!$tabla){
            return Response::json(['response' => 'Token no encontrado'], 404);
        }
        return Response::json($tabla, 200);
    }

    public function comprobarTokenPassword($token){
        $tabla = $this->tabla->comprobarTokenPassword($token);
        if(!$tabla){
            return Response::json(['response' => 'Token no encontrado'], 404);
        }
        return Response::json($tabla, 200);
    }
 
    public function changePassword($user, $correo, $newpassword){
        $tabla = $this->tabla->changePassword($user, $correo, $newpassword);
        if(!$tabla){
            return Response::json(['response' => 'No se pudo cambiar la contraseña'], 404);
        }
        if($tabla == 'contraseña repetida'){
            return Response::json(['response' => $tabla], 404);
         }
        return Response::json($tabla, 200);
    }

    public function sendEmail($name, $correo, $tipo, $mensaje){
        $tabla = $this->tabla->sendEmailwithBody($name, $correo, $tipo, $mensaje);
        if(!$tabla){
            return Response::json($tabla, 404);
        }
        return Response::json($tabla, 200);
    }
} 
