<?php 

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
   protected $user = null;

    public function __construct(User $user){
    	$this->user = $user;
    }

    public function allUsuarios(){
    	//return User::orderBy('id', 'asc')->get();
		return $this->user->allUsuarios();
	}

    public function getUsuario($id){

        $user = $this->user->getUsuario($id);
        if(!$user){
            return Response::json(['response' => 'Usuario no encontrado'],404);
        }
        return Response::json($user,200);
    }

     public function  getAllUsuariosGrupos(){
        
        $user = $this->user-> getAllUsuariosGrupos();
        if(!$user){
            return Response::json(['response' => 'Usuario no encontrado'],404);
        }
        return Response::json($user,200);
    }
   

	public function saveUsuario(){
		return $this->user->saveUsuario();
	}

    public function saveGrupoAlumno(){
        return $this->user->saveGrupoAlumno();
    }

    public function updateUsuario($id){
        $user = $this->user->updateUsuario($id);
        if(!$user){
            return Response::json(['response' => 'Usuario no encontrado'], 404);
        }

        return Response::json($user, 200);
    }
/*
    public function deleteUsuario($id){
        $user = $this->user->deleteUsuario($id);
        if(!$user){
        return Response::json(['response' => $user], 404);
        }
        return Response::json($user, 200);
    }*/
}