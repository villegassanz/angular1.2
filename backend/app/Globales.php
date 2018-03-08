<?php  
 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\DB; 
   
class Globales extends Model 
{
    public function getTamaTabla($nomTabla){
        $opcionDocente = '';
        if($nomTabla == 'director'){
            $opcionDocente = ' WHERE docente.id_rol="2"';
            $nomTabla = 'docente';
        }
        else if($nomTabla == 'docente'){
            $opcionDocente = ' WHERE docente.id_rol="3"';
        } 
        $tabla=DB::select('SELECT count(' .$nomTabla .'.id_' .$nomTabla .') as tama FROM ' .$nomTabla .$opcionDocente);
        return $tabla;
    } 
 
    public function insertHistorialUsuario($id_usuario, $tipo_usuario, $accion, $id_objetivo, $tipo_objetivo, $campos_cambiados, $valores_antiguos, $valores_nuevos){ 
        DB::insert('INSERT INTO historial_usuario (id_usuario, tipo_usuario, accion, id_objetivo, tipo_objetivo, campos_cambiados, valores_antiguos, valores_nuevos) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [$id_usuario, $tipo_usuario, $accion, $id_objetivo, $tipo_objetivo, $campos_cambiados, $valores_antiguos, $valores_nuevos]);
    }
 
    public function getUltimosRegistrosIngresados($rol, $usuario, $id_plantel, $accion, $horas){
        if ($rol == 0 || $rol == 1) {
            if($usuario == 'docente'){
                 $tabla=DB::select('SELECT historial_usuario.* FROM historial_usuario INNER JOIN docente ON docente.id_docente = historial_usuario.id_objetivo WHERE (historial_usuario.tipo_objetivo = "DOCENTE" OR historial_usuario.tipo_objetivo = "RESPONSABLE") AND historial_usuario.accion = ? AND historial_usuario.fecha >= (SELECT date_sub(NOW(), INTERVAL ? DAY_HOUR)) AND historial_usuario.fecha <= (SELECT NOW())',[$accion, $horas]);
                return $tabla;
            }
            if($usuario == 'alumno'){ 
                $tabla=DB::select('SELECT historial_usuario.* FROM historial_usuario INNER JOIN alumno ON alumno.id_alumno = historial_usuario.id_objetivo WHERE historial_usuario.tipo_objetivo = "ALUMNO" AND historial_usuario.accion = ? AND historial_usuario.fecha >= (SELECT date_sub(NOW(), INTERVAL ? DAY_HOUR)) AND historial_usuario.fecha <= (SELECT NOW())',[$accion, $horas]);
                return $tabla;
            }
            if($usuario == 'auxiliar'){ 
                $tabla=DB::select('SELECT historial_usuario.* FROM historial_usuario INNER JOIN auxiliar ON auxiliar.id_auxiliar = historial_usuario.id_objetivo WHERE historial_usuario.tipo_objetivo = "AUXILIAR" AND historial_usuario.accion = ? AND historial_usuario.fecha >= (SELECT date_sub(NOW(), INTERVAL ? DAY_HOUR)) AND historial_usuario.fecha <= (SELECT NOW())',[$accion, $horas]);
                return $tabla;
            }
        }else{
            if($usuario == 'alumno'){ 
                $tabla=DB::select('SELECT historial_usuario.* FROM historial_usuario INNER JOIN alumno ON alumno.id_alumno = historial_usuario.id_objetivo 

                    WHERE historial_usuario.tipo_objetivo = "ALUMNO" AND alumno.id_plantel = ? AND historial_usuario.accion = ? AND historial_usuario.fecha >= (SELECT date_sub(NOW(), INTERVAL ? DAY_HOUR)) AND historial_usuario.fecha <= (SELECT NOW())',[$id_plantel, $accion, $horas]);
                return $tabla;
            }
        }
    }

    /*
    SELECT historial_usuario.* FROM historial_usuario INNER JOIN docente ON docente.id_docente = historial_usuario.id_objetivo WHERE (((historial_usuario.fecha >= (SELECT date_sub(NOW(), INTERVAL 6 DAY_HOUR)) AND historial_usuario.fecha <= (SELECT NOW())) AND docente.id_plantel = 1)) AND historial_usuario.accion = "INSERT" 
    UNION ALL 
    SELECT historial_usuario.* FROM historial_usuario INNER JOIN alumno ON alumno.id_alumno = historial_usuario.id_objetivo WHERE (((historial_usuario.fecha >= (SELECT date_sub(NOW(), INTERVAL 6 DAY_HOUR)) AND historial_usuario.fecha <= (SELECT NOW())) AND alumno.id_plantel = 1)) AND historial_usuario.accion = "INSERT"
     */
     
    public function getIDSyTIPOconCURPStoNoUsers($curp_usuario, $rol_usuario){
        $tabla = DB::select('SELECT t1.* FROM 
        (SELECT 0 AS id_usuario, "ADMIN" AS tipo_usuario FROM users WHERE users.curp = ' ."'$curp_usuario'" .' AND users.id_rol = ' ."'$rol_usuario'" .'
        UNION ALL
            SELECT auxiliar.id_auxiliar AS id_usuario, "AUXILIAR" AS tipo_usuario FROM auxiliar WHERE auxiliar.curp = ' ."'$curp_usuario'" .'
        UNION ALL
        SELECT docente.id_docente AS id_usuario, "DOCENTE" AS tipo_usuario FROM docente WHERE docente.curp = ' ."'$curp_usuario'" .' AND docente.puesto = "DOCENTE" AND docente.id_rol = ' .$rol_usuario .'
        UNION ALL
        SELECT docente.id_docente AS id_usuario, "RESPONSABLE" AS tipo_usuario FROM docente WHERE docente.curp = ' ."'$curp_usuario'" .' AND docente.puesto = "RESPONSABLE" AND docente.id_rol = ' .$rol_usuario .'
        UNION ALL
        SELECT alumno.id_alumno AS id_usuario, "ALUMNO" AS tipo_usuario FROM alumno WHERE alumno.curp = ' ."'$curp_usuario'" .') AS t1');
        return $tabla;
    }
  
    public function getIDSyTIPOconCURPS($curp_usuario, $rol_usuario, $curp_objetivo, $rol_objetivo){
        $tabla=DB::select('SELECT t1.*, t2.* FROM 
        (SELECT 0 AS id_usuario, "ADMIN" AS tipo_usuario FROM users WHERE users.curp = ' ."'$curp_usuario'" .' AND users.id_rol = ' .$rol_usuario .'
        UNION ALL
            SELECT auxiliar.id_auxiliar AS id_usuario, "AUXILIAR" AS tipo_usuario FROM auxiliar WHERE auxiliar.curp = ' ."'$curp_usuario'" .'
        UNION ALL
        SELECT docente.id_docente AS id_usuario, "DOCENTE" AS tipo_usuario FROM docente WHERE docente.curp = ' ."'$curp_usuario'" .' AND docente.puesto = "DOCENTE" AND docente.id_rol = ' .$rol_usuario .'
        UNION ALL
        SELECT docente.id_docente AS id_usuario, "RESPONSABLE" AS tipo_usuario FROM docente WHERE docente.curp = ' ."'$curp_usuario'" .' AND docente.puesto = "RESPONSABLE" AND docente.id_rol = ' .$rol_usuario .'
        UNION ALL
        SELECT alumno.id_alumno AS id_usuario, "ALUMNO" AS tipo_usuario FROM alumno WHERE alumno.curp = ' ."'$curp_usuario'" .') AS t1, 



        (SELECT 0 AS id_objetivo, "ADMIN" AS tipo_objetivo FROM users WHERE users.curp = ' ."'$curp_objetivo'" .' AND users.id_rol = 0
        UNION ALL
         SELECT auxiliar.id_auxiliar AS id_objetivo, "AUXILIAR" AS tipo_objetivo FROM auxiliar WHERE auxiliar.curp = ' ."'$curp_objetivo'" .'
        UNION ALL
        SELECT docente.id_docente AS id_objetivo, "DOCENTE" AS tipo_objetivo FROM docente WHERE docente.curp = ' ."'$curp_objetivo'" .' AND docente.puesto = "DOCENTE" AND docente.id_rol = ' .$rol_objetivo .'
        UNION ALL
        SELECT docente.id_docente AS id_objetivo, "RESPONSABLE" AS tipo_objetivo FROM docente WHERE docente.curp = ' ."'$curp_objetivo'" .' AND docente.puesto = "RESPONSABLE" AND docente.id_rol = ' .$rol_objetivo .'
        UNION ALL
        SELECT alumno.id_alumno AS id_objetivo, "ALUMNO" AS tipo_objetivo FROM alumno WHERE alumno.curp = ' ."'$curp_objetivo'" .') AS t2');
        return $tabla;
    }
 
    public function getDatosToCertificadoPDF($id_alumno){
        $tabla=DB::select('
            SELECT 
                (SELECT periodo.nombre FROM periodo WHERE periodo.estatus = 1) AS periodo_actual,
                (SELECT datos_generales.coordinador_general FROM datos_generales WHERE datos_generales.id = 1) AS coordinador_general ,
                (SELECT datos_generales.dir_edu_media_sup FROM datos_generales WHERE datos_generales.id = 1) AS dir_edu_media_sup, 
                plantel.modalidad AS plantel_numero_plantel,
                plantel.clave AS plantel_clave, 
                plantel.localidad AS plantel_nombre, 
                CONCAT(alumno.nombre, " ", alumno.apellido_paterno, " ", alumno.apellido_materno) AS alumno_nombre_completo, 
                alumno.numero_control AS alumno_numero_control, 
                materia.semestre AS materia_semestre, 
                materia.creditos AS materia_creditos, 
                materia.nombre AS materia_nombre, 
                calificacion.evaluacion1 , 
                calificacion.evaluacion2 , 
                calificacion.evaluacionFinal, 
                CONCAT(docente.nombre," ",docente.apellido_paterno," ",docente.apellido_materno)AS nombre_completo_responsable 
            FROM alumno 
                INNER JOIN calificacion ON calificacion.id_alumno = alumno.id_alumno 
                INNER JOIN materia ON materia.id_materia = calificacion.id_materia 
                INNER JOIN plantel ON plantel.id_plantel = alumno.id_plantel 
                INNER JOIN grupo ON grupo.id_grupo=calificacion.id_grupo 
                INNER JOIN periodo ON periodo.id_periodo=grupo.id_periodo 
                INNER JOIN docente ON docente.id_plantel=plantel.id_plantel
            WHERE periodo.estatus !=1 AND 
                docente.puesto="RESPONSABLE" AND 
                alumno.id_alumno =?', [$id_alumno]);
        return $tabla;
    }

    public function getDatosToKardexPDF($id_alumno){
        $tabla=DB::select('
            SELECT 
                (SELECT periodo.nombre 
                    FROM periodo 
                    WHERE periodo.estatus = 1) 
                AS periodo_actual,
                (SELECT IF(SUBSTRING(alumno.estatusSemestreCal, 1, 6) = "EGRESO", 7, SUBSTRING(grupo.nombre, 2, 1)) FROM grupo_alumno 
                        INNER JOIN alumno ON alumno.id_alumno = grupo_alumno.id_alumno 
                        INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo 
                    WHERE alumno.id_alumno = ?
                    ORDER BY grupo.nombre 
                    DESC LIMIT 1) 
                AS semestre_actual,
                plantel.clave AS plantel_clave,
                plantel.modalidad AS plantel_numero_plantel,
                plantel.localidad AS plantel_nombre, 
                alumno.nombre AS alumno_nombre, 
                alumno.apellido_paterno AS alumno_ap,
                alumno.apellido_materno AS alumno_am,
                alumno.numero_control AS alumno_numero_control,
                alumno.curp AS alumno_curp,
                alumno.genero AS alumno_genero,
                alumno.fecha_nacimiento AS alumno_fechaNac,
                materia.semestre AS materia_semestre, 
                materia.nombre AS materia_nombre,
                periodo.nombre AS periodo_nombre,
                calificacion.evaluacion1, 
                calificacion.evaluacion2, 
                calificacion.evaluacionFinal,  
                calificacion.extraordinario1, 
                calificacion.extraordinario2, 
                calificacion.especial
            FROM alumno 
                INNER JOIN calificacion ON calificacion.id_alumno = alumno.id_alumno 
                INNER JOIN materia ON materia.id_materia = calificacion.id_materia 
                INNER JOIN plantel ON plantel.id_plantel = alumno.id_plantel 
                INNER JOIN grupo ON grupo.id_grupo=calificacion.id_grupo 
                INNER JOIN periodo ON periodo.id_periodo=grupo.id_periodo 
                INNER JOIN docente ON docente.id_plantel=plantel.id_plantel
            WHERE periodo.estatus !=1 AND 
                docente.puesto="RESPONSABLE" AND 
                alumno.id_alumno = ?', [$id_alumno,$id_alumno]);
        return $tabla;
    }

    public function getDatosToLista($id_docente, $id_materia){
        $tabla = DB::select('
            SELECT CONCAT(periodo.inicio, "/", periodo.fin) AS periodo_ciclo_escolar,
                (SELECT CONCAT(docente.nombre, " ", docente.apellido_paterno, " ", docente.apellido_materno) 
                FROM docente 
                WHERE docente.id_plantel = plantel.id_plantel 
                AND docente.puesto = "RESPONSABLE") AS responsable_nombre_completo, 
                grupo.nombre AS grupo_nombre,
                materia.nombre AS materia_nombre, 
                materia.semestre AS materia_semestre,
                plantel.clave AS plantel_clave,
                plantel.localidad AS plantel_nombre, 
                CONCAT(docente.nombre, " ", docente.apellido_paterno, " ", docente.apellido_materno) AS docente_nombre_completo,
                alumno.numero_control AS alumno_numero_control, 
                CONCAT(alumno.nombre, " ", alumno.apellido_paterno, " ", alumno.apellido_materno) AS alumno_nombre_completo, 
                calificacion.evaluacion1, 
                calificacion.evaluacion2, 
                calificacion.evaluacionFinal,
                alumno.nLista AS alumno_nLista
            FROM calificacion 
                INNER JOIN alumno ON alumno.id_alumno = calificacion.id_alumno 
                INNER JOIN docente ON docente.id_docente = calificacion.id_docente 
                INNER JOIN plantel ON plantel.id_plantel = docente.id_plantel 
                INNER JOIN materia ON materia.id_materia = calificacion.id_materia 
                INNER JOIN grupo ON grupo.id_grupo = calificacion.id_grupo 
                INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo 
            WHERE docente.id_docente = ? 
                AND materia.id_materia = ? AND periodo.estatus=1 ORDER BY alumno_nLista', [$id_docente, $id_materia]);
        return $tabla;
    } 

    public function getDatosGenerales(){
        $tabla=DB::select('SELECT datos_generales.* FROM datos_generales', []);
        return $tabla;
    }

    public function setDatosGenerales($tipo){
        $input = Input::all();
        switch ($tipo) {
            case 'cambio_coordinador':
                DB::update('UPDATE datos_generales SET coordinador_general = ? WHERE id = 1', [$input['coordinador_general']]);
                break;
            case 'cambio_dir_edu_media_sup':
                DB::update('UPDATE datos_generales SET dir_edu_media_sup = ? WHERE id = 1', [$input['dir_edu_media_sup']]);
                break;
            case 'fecha_evaluaciones':
                DB::update('UPDATE datos_generales SET fecha_inicio_eva1 = ?, fecha_fin_eva1 = ?, fecha_inicio_eva2 = ?, fecha_fin_eva2 = ?, fecha_inicio_evaf = ?, fecha_fin_evaf = ?, fecha_inicio_extra1 = ?, fecha_fin_extra1 = ?, fecha_inicio_extra2 = ?, fecha_fin_extra2 = ?, fecha_inicio_especial = ?, fecha_fin_especial = ? WHERE id = 1', [$input['fecha_inicio_eva1'], $input['fecha_fin_eva1'], $input['fecha_inicio_eva2'], $input['fecha_fin_eva2'], $input['fecha_inicio_evaf'], $input['fecha_fin_evaf'], $input['fecha_inicio_extra1'], $input['fecha_fin_extra1'], $input['fecha_inicio_extra2'], $input['fecha_fin_extra2'], $input['fecha_inicio_especial'], $input['fecha_fin_especial']]);
                break; 
            case 'edad_usuarios':
                DB::update('UPDATE datos_generales SET edad_min_auxiliar = ?, edad_max_auxiliar = ?, edad_min_docente = ?, edad_max_docente = ?, edad_min_alumno = ?, edad_max_alumno = ? WHERE id = 1', [$input['edad_min_auxiliar'], $input['edad_max_auxiliar'], $input['edad_min_docente'], $input['edad_max_docente'], $input['edad_min_alumno'], $input['edad_max_alumno']]);
                break;
        }
        /*
        DB::update('UPDATE datos_generales SET coordinador_general = ?, fecha_inicio_eva1 = ?, fecha_fin_eva1 = ?, fecha_inicio_eva2 = ?, fecha_fin_eva2 = ?, fecha_inicio_evaf = ?, fecha_fin_evaf = ?, edad_min_auxiliar = ?, edad_max_auxiliar = ?, edad_min_docente = ?, edad_max_docente = ?, edad_min_alumno = ?, edad_max_alumno = ?)', [$input['id_docente']]);
        */
    }

    public function getDatosValidacionesGenerales($usuario){
        $tabla;
        switch ($usuario) {
            case 'auxiliar':
                $tabla = DB::select('SELECT datos_generales.edad_min_auxiliar, datos_generales.edad_max_auxiliar FROM datos_generales WHERE datos_generales.id = 1', []);
                break;
            case 'docente':
                $tabla = DB::select('SELECT datos_generales.edad_min_docente, datos_generales.edad_max_docente FROM datos_generales WHERE datos_generales.id = 1', []);
                break;
            case 'alumno':
                $tabla = DB::select('SELECT datos_generales.edad_min_alumno, datos_generales.edad_max_alumno FROM datos_generales WHERE datos_generales.id = 1', []);
                break;
            case 'calificaciones':
                $tabla = DB::select('SELECT datos_generales.fecha_inicio_eva1, datos_generales.fecha_fin_eva1, datos_generales.fecha_inicio_eva2, datos_generales.fecha_fin_eva2, datos_generales.fecha_inicio_evaf, datos_generales.fecha_fin_evaf, datos_generales.fecha_inicio_extra1, datos_generales.fecha_fin_extra1, datos_generales.fecha_inicio_extra2, datos_generales.fecha_fin_extra2, datos_generales.fecha_inicio_especial, datos_generales.fecha_fin_especial FROM datos_generales WHERE datos_generales.id = 1', []);
                break;
        }
        return $tabla;
    }
   
    public function getHistorialUsuario(){
        $tabla = DB::select('SELECT historial_usuario.id_usuario, historial_usuario.tipo_usuario,
                    (CASE
                        WHEN historial_usuario.tipo_usuario = "ADMIN" THEN (SELECT CONCAT(users.nombre, " ", users.apellido_paterno, " ", users.apellido_materno) FROM users WHERE users.id_rol = historial_usuario.id_usuario LIMIT 1)
                        WHEN historial_usuario.tipo_usuario = "AUXILIAR" THEN (SELECT CONCAT(auxiliar.nombre, " ", auxiliar.apellido_paterno, " ", auxiliar.apellido_materno) FROM auxiliar WHERE auxiliar.id_auxiliar = historial_usuario.id_usuario AND auxiliar.id_rol = 1 LIMIT 1)
                        WHEN historial_usuario.tipo_usuario = "RESPONSABLE" THEN (SELECT CONCAT(docente.nombre, " ", docente.apellido_paterno, " ", docente.apellido_materno) FROM docente WHERE docente.id_docente = historial_usuario.id_usuario AND docente.id_rol = 2 LIMIT 1)
                        WHEN historial_usuario.tipo_usuario = "DOCENTE" THEN (SELECT CONCAT(docente.nombre, " ", docente.apellido_paterno, " ", docente.apellido_materno) FROM docente WHERE docente.id_docente = historial_usuario.id_usuario AND docente.id_rol = 3 LIMIT 1)
                        WHEN historial_usuario.tipo_usuario = "ALUMNO" THEN (SELECT CONCAT(alumno.nombre, " ", alumno.apellido_paterno, " ", alumno.apellido_materno) FROM alumno WHERE alumno.id_alumno = historial_usuario.id_usuario AND alumno.id_rol = 4 LIMIT 1)
                    ELSE "-----"
                    END) AS nombre_usuario, historial_usuario.accion, historial_usuario.id_objetivo ,historial_usuario.tipo_objetivo,
                    (CASE
                        WHEN historial_usuario.tipo_objetivo = "AUXILIAR" THEN (SELECT CONCAT(auxiliar.nombre, " ", auxiliar.apellido_paterno, " ", auxiliar.apellido_materno) FROM auxiliar WHERE auxiliar.id_auxiliar = historial_usuario.id_objetivo AND auxiliar.id_rol = 1 LIMIT 1)
                        WHEN historial_usuario.tipo_objetivo = "RESPONSABLE" THEN (SELECT CONCAT(docente.nombre, " ", docente.apellido_paterno, " ", docente.apellido_materno) FROM docente WHERE docente.id_docente = historial_usuario.id_objetivo AND docente.id_rol = 2 LIMIT 1)
                        WHEN historial_usuario.tipo_objetivo = "DOCENTE" THEN (SELECT CONCAT(docente.nombre, " ", docente.apellido_paterno, " ", docente.apellido_materno) FROM docente WHERE docente.id_docente = historial_usuario.id_objetivo AND docente.id_rol = 3 LIMIT 1)
                        WHEN historial_usuario.tipo_objetivo = "ALUMNO" THEN (SELECT CONCAT(alumno.nombre, " ", alumno.apellido_paterno, " ", alumno.apellido_materno) FROM alumno WHERE alumno.id_alumno = historial_usuario.id_objetivo AND alumno.id_rol = 4 LIMIT 1)
                    ELSE "-----"
                    END) AS nombre_objetivo, historial_usuario.campos_cambiados, historial_usuario.valores_antiguos, historial_usuario.valores_nuevos, historial_usuario.fecha
                    FROM historial_usuario ORDER BY historial_usuario.fecha DESC', []);
        return $tabla;
    }

    public function getCarrerasAceptadas(){
        $tabla=DB::select('SELECT carreras_aceptadas.* FROM carreras_aceptadas', []);
        return $tabla;
    }

    public function getCarreraById($id_carrera){
        $tabla=DB::select('SELECT carreras_aceptadas.* FROM carreras_aceptadas WHERE carreras_aceptadas.id_carrera = ?', [$id_carrera]);
        return $tabla;
    }

    public function updateOrAddCarrera($tipo){
        $input = Input::all();
        //print_r($input);
        //print($tipo);
        $salida = "";
        switch ($tipo) {
            case 'update':
                DB::update('UPDATE carreras_aceptadas SET carreras_aceptadas.nombre = ? WHERE carreras_aceptadas.id_carrera = ?' , [$input['nombre'],$input['id_carrera']]);
                $salida = "CARREA ACTUALIZADA";
                break;
            case 'insert':
                $existe = DB::select('SELECT carreras_aceptadas.* FROM carreras_aceptadas WHERE carreras_aceptadas.nombre = ? LIMIT 1', [$input['nombre']]);
                //print_r($existe);
                if(!$existe){
                    //echo "no existe";
                    DB::insert('INSERT INTO carreras_aceptadas (nombre) VALUES (?)', [$input['nombre']]);
                    $salida = "CARRERA AGREGADA";
                }else{
                    //echo "existe";
                    $salida = "CARRERA YA EXISTE";
                }
                break;
        }
        return $salida;
    }

    public function uploadImgs(){
        DB::statement('TRUNCATE TABLE imagen',[]);
        $input = Input::all();
        foreach ($input as $key => $element) {
            $descriptionImagen = $element->getClientOriginalName();
            $imgContent = base64_encode(file_get_contents($element));
            $mimeImagen = $element->getMimeType();
            $sizeImagen = $element->getClientSize();
            DB::insert('INSERT INTO imagen (nameImg, img, mimeImg, sizeImg) values (?, ?, ?, ?)', [$descriptionImagen, $imgContent, $mimeImagen, $sizeImagen]);
         } 
    } 

    public function getUploadImgs(){
        $salida = DB::select('SELECT * FROM imagen',[]);
        //foreach ($salida as $key => $element) {
            //$img = $element->img;
            //$mime = $element->mime;
            //echo "<img src='data:" .$mime .";base64,".$img."' />";
        //}
        //header("Content-type: " .$salida[0]->mime);
        //echo base64_decode($salida[0]->img);
        //var_dump(imagecreatefromstring(base64_decode($salida[0]->img)));
        return $salida;
    }

    public function createEventEvalucionExtraEspecial($evento, $nombre, $fecha, $hora, $minuto){
        //nombre = actualizar_estatusCali_Alumno
        DB::unprepared('DROP EVENT IF EXISTS ' .$nombre); 
        if($evento == 'extras'){
            DB::unprepared('CREATE EVENT ' .$nombre .' ON SCHEDULE AT "'.$fecha.' '.$hora.':'.$minuto.':00.000000" DO CALL EstatusAlumnoCaliToExtra()');
            //DB::unprepared('CREATE EVENT ' .$nombre .' ON SCHEDULE AT "'.$fecha_completa.' '.$hora.':'.$minuto.':00.000000" DO CALL UpNoLista()');
        }
        if($evento == 'especial'){
            DB::unprepared('CREATE EVENT ' .$nombre .' ON SCHEDULE AT "'.$fecha.' '.$hora.':'.$minuto.':00.000000" DO CALL EstatusAlumnoCaliToEspe()');
        }
    }

    public function getAlumntosEgresadosToCertificadoPDF($id_plantel){
        //$tabla = DB::select('SELECT alumno.id_alumno FROM alumno WHERE alumno.estado = 1 AND SUBSTRING(alumno.estatusSemestreCal, 1, 6) = "EGRESO" AND alumno.id_plantel = ?', [$id_plantel]);
        $tabla = DB::select('SELECT alumno.id_alumno, periodo.id_periodo, alumno.estatusSemestreCal FROM alumno INNER JOIN grupo_alumno ON grupo_alumno.id_alumno=alumno.id_alumno INNER JOIN grupo ON grupo.id_grupo = grupo_alumno.id_grupo INNER JOIN periodo ON periodo.id_periodo = grupo.id_periodo WHERE alumno.estado = 1 AND SUBSTRING(alumno.estatusSemestreCal, 1, 6) = "EGRESO" AND alumno.id_plantel = ? AND periodo.id_periodo = ((SELECT periodo.id_periodo FROM periodo WHERE periodo.estatus = 1)-1)',[$id_plantel]);
        return $tabla;
    }
}  
