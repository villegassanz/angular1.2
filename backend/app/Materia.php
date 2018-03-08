<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB;

class Materia extends Model 
{
	protected $primaryKey='id_materia';
	public $incrementing = true;
	public $timestamps =false;
	public $table ='materia';

	
    protected $fillable = [
        'id_materia','nombre', 'creditos','campo_disciplinario', 'semestre', 'horas'
    ];
    protected $hidden =[
        
    ];
    public function allMaterias(){
        return self::all();
    }
    public function getMateria($id){
        $materia = self::find($id);
        if(is_null($materia)){
            return false;
        }
        return $materia;
    }
    public function updateMateria($id){
        $materia = self::find($id);
        if(is_null($materia)){
            return false;
        }
        $input = Input::all();
        $materia->fill($input);
        $materia->save();
        return $materia;
    }
    public function saveMateria(){
        $input = Input::all();
        //$input['password'] = Hash::make($input['password']);
        $materia = new Materia();
        $materia->fill($input);
        $materia->save();

        return $materia;
    }
    public function getMateriasSegunSemestre($semestre){
        $docente = DB::select('SELECT * from materia where materia.semestre='."'$semestre'");
        return $docente;
    }
    public function getDocenteCamDisAndSemestre($capo_disc, $idDocente, $semestre){
        $materia = DB::select('SELECT materia.* from materia INNER JOIN docente on docente.campo_disciplinario = materia.campo_disciplinario WHERE materia.campo_disciplinario='."'$capo_disc'" .' and docente.id_docente=' ."'$idDocente'" .' and materia.semestre=' 
            ."'$semestre'");
        return $materia;
    }
     
}