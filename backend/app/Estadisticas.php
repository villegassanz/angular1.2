<?php  
 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB; 
   
class Estadisticas extends Model 
{ 
    public function getTotalAlumnoxGenero(){
        $tabla = DB::select('SELECT
				(SELECT COUNT(alumno.genero) FROM alumno INNER JOIN grupo_alumno ON grupo_alumno.id_alumno = alumno.id_alumno INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE alumno.genero = "HOMBRE" AND periodo.estatus = 1) HOMBRES,
			    (SELECT COUNT(alumno.genero) FROM alumno INNER JOIN grupo_alumno ON grupo_alumno.id_alumno = alumno.id_alumno INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE alumno.genero = "MUJER" AND periodo.estatus = 1) MUJERES',[]);
        return $tabla;
    }  

    public function getAlumnoxGeneroxPlantel($id_plantel){
    	$tabla = DB::select('
    		SELECT
				(SELECT COUNT(alumno.genero) FROM alumno INNER JOIN grupo_alumno ON grupo_alumno.id_alumno = alumno.id_alumno INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE alumno.id_plantel = ? AND alumno.genero = "HOMBRE" AND periodo.estatus = 1) HOMBRES,
			    (SELECT COUNT(alumno.genero) FROM alumno INNER JOIN grupo_alumno ON grupo_alumno.id_alumno = alumno.id_alumno INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE alumno.id_plantel = ? AND alumno.genero = "MUJER" AND periodo.estatus = 1) MUJERES', [$id_plantel, $id_plantel]);
    	return $tabla;
    }
 
    public function getDatosOfAproOrReproXPeriodoxPlantel($id_periodo, $id_grupo, $id_plantel){
        $tabla = DB::select('
            SELECT materia.nombre AS MATERIA,
                materia.semestre AS SEMESTRE,
                grupo.nombre AS grupo,
                SUM(CASE WHEN calificacion.estatus = "REPROBO" THEN 1 ELSE 0 END) AS REPROBADOS,
                SUM(CASE WHEN calificacion.estatus = "APROBO" THEN 1 ELSE 0 END) AS APROBADOS,
                COUNT(calificacion.id_alumno) AS ALUMNOS
            FROM materia
                INNER JOIN calificacion ON calificacion.id_materia = materia.id_materia
                INNER JOIN grupo ON grupo.id_grupo = calificacion.id_grupo
                INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo
                INNER JOIN alumno ON alumno.id_alumno = calificacion.id_alumno
            WHERE periodo.id_periodo = ?
                AND grupo.id_grupo = ?
                AND alumno.id_plantel = ?
            GROUP BY materia.nombre', [$id_periodo, $id_grupo, $id_plantel]);
        return $tabla;
    }

    public function getDatosOfEgresadosXPeriodoXPlantel($id_periodo, $id_plantel){
        $tabla = DB::select('
            SELECT 
                SUM(CASE WHEN SUBSTRING(alumno.estatusSemestreCal, 1, 6) = "EGRESO" THEN 1 ELSE 0 END) AS EGRESADOS, 
                SUM(CASE WHEN SUBSTRING(alumno.estatusSemestreCal, 1, 6) != "EGRESO" THEN 1 ELSE 0 END) AS SIN_EGRESAR
            FROM alumno 
                INNER JOIN grupo_alumno ON grupo_alumno.id_alumno = alumno.id_alumno 
                INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo 
                INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo 
            WHERE grupo.nombre = "S6" 
                AND periodo.id_periodo = ? 
                AND alumno.id_plantel = ?',[$id_periodo, $id_plantel]);
        return $tabla;
    }
} 
