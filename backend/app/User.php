<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;


 
class User extends Authenticatable
{

  
     public $incrementing = false;

     public $timestamps = false;

     protected $table = 'users';

     protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','nombre', 'apellido_paterno', 'apellido_materno', 'curp','email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

     public function allUsuarios(){
        return self::all();
    }

   public function getUsuario($id){
        
        //$user = self::find($id);
        $user = DB::select('SELECT users.id, users.nombre, users.apellido_paterno, users.apellido_materno, users.curp, users.email, users.id_rol, users.id_plantel, users.estado, docente.id_docente AS id_por_tabla FROM users INNER JOIN docente ON docente.curp = users.id');
        if(is_null($user)){
            return false;
        }
        return $user;
        
    /*
        $user = DB::table('users')
        ->where('id', '=', $id)
        ->get();

        return $user;
    */
        
        $user = DB::table('alumno_grupo')
        ->where('grupo_id_ag', '=', $id)
        ->get();

        return $user;

    }

     public function getAllUsuariosGrupos(){      
        $user = DB::select('SELECT curp_alumno, nombre from alumnos WHERE alumnos.curp_alumno NOT IN (SELECT alumno_curp from alumno_grupo) GROUP BY alumnos.curp_alumno');

        // ->whereNotIn('al')
        //->groupBy('alumno_curp')
        //->where('curp_alumno', '=', $id)
       

        return $user;
    }

    //SELECT * from alumnos WHERE alumnos.curp_alumno NOT IN (SELECT alumno_curp from alumno_grupo) GROUP BY alumnos.curp_alumno;

    public function saveGrupoAlumno(){
         $materias = Input::get('integrantes');
        //$data = array(
        //    ['id_materia'=>16, 'area_id'=>1],
        //    ['id_materia'=>17, 'area_id'=>2],
            //...
        //);
         foreach ($materias as $key => $student) {
        
            DB::table('alumno_grupo')->insert($student);
        
        }

    }

     public function updateUsuario($id){

        $user = self::find($id);
        if(is_null($user)){
            return false;
        }
        
        $input = Input::all(); 

        if(isset($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }
        
        $user->fill($input);
        $user->save();
        return $user;
    }

    public function saveUsuario(){
        $input = Input::all();
        $input['password'] = Hash::make($input['password']);
        $user = new User();
        $user->fill($input);
        $user->save();
        return $user;
    }
/*
    public function deleteUsuario($id){
        $user = self::find($id);
        if(is_null($user)){
            return false;
        }
        $user->estado = 0;
        $user->save();
        return $user;
    }*/
}
