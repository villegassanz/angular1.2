<?php

/* 
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
| 
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/   
Route::group(['prefix' => 'api'], function () { 
    //http://localhost/proyecto/backend/public/api 
    Route::post('autenticar','AuthenticateController@authenticate');
    Route::get('autenticar/info', 'AuthenticateController@getAuthenticatedUser');
    //------------------------
    Route::group(['prefix' => 'global'], function () {
        //http://localhost/proyecto/backend/public/api/global
        Route::get('tama/{nomTabla}', ['uses' => 'GlobalesController@getTamaTabla']);
        //http://localhost/proyecto/backend/public/api/global/addHistorialUsuario/id_usuario/0/tipo_usuario/ADMIN/accion/INSERT/id_objetivo/5/tipo_objetivo/DOCENTE/campos_cambiados/nombre|apellido_paterno/valores_antiguos/Emmanuel|Rodriguez/valores_nuevos/Esteban|Sanchez
        Route::post('addHistorialUsuario/id_usuario/{id_usuario}/tipo_usuario/{tipo_usuario}/accion/{accion}/id_objetivo/{id_objetivo}/tipo_objetivo/{tipo_objetivo}/campos_cambiados/{campos_cambiados}/valores_antiguos/{valores_antiguos}/valores_nuevos/{valores_nuevos}', ['uses' => 'GlobalesController@insertHistorialUsuario']);
        //http://localhost/proyecto/backend/public/api/global/ultimosRegistrosAgregados/1/docente/1/INSERT
        Route::get('ultimosRegistrosAgregados/{rol}/{usuario}/{id_plantel}/{accion}/{horas}', ['uses' => 'GlobalesController@getUltimosRegistrosIngresados']);
        Route::get('IDSyTIPOconCURPS/usuario/{curp_usuario}/rol_usuario/{rol_usuario}/objetivo/{curp_objetivo}/rol_objetivo/{rol_objetivo}', ['uses' => 'GlobalesController@getIDSyTIPOconCURPS']);
        //http://localhost/proyecto/backend/public/api/global/IDSyTIPOconCURPS/usuario/ROVE930928HOCDZM05/rol_usuario/0/objetivo/SAVE920509HOCNLS09/rol_objetivo/3
        Route::get('IDSyTIPOconCURPStoNoUsers/usuario/{curp_usuario}/rol_usuario/{rol_usuario}', ['uses' => 'GlobalesController@getIDSyTIPOconCURPStoNoUsers']);
        //http://localhost/proyecto/backend/public/api/global/IDSyTIPOconCURPStoNoUsers/usuario/ROVE930928HOCDZM05/rol_usuario/0
        //
        //http://localhost/proyecto/backend/public/api/global/DatosToCertificadoPDF/50
        Route::get('DatosToCertificadoPDF/{id_alumno}', ['uses' => 'GlobalesController@getDatosToCertificadoPDF']);
        //http://localhost/proyecto/backend/public/api/global/DatosToKardexPDF/50
        Route::get('DatosToKardexPDF/{id_alumno}', ['uses' => 'GlobalesController@getDatosToKardexPDF']);
        Route::get('DatosToListaPDF/{id_docente}/{id_materia}', ['uses' => 'GlobalesController@getDatosToLista']); 
        Route::get('DatosGenerales', ['uses' => 'GlobalesController@getDatosGenerales']);
        //http://localhost/proyecto/backend/public/api/global/DatosGenerales/cambio_coordinador
        Route::post('DatosGenerales/{tipo}', ['uses' => 'GlobalesController@setDatosGenerales']);
        Route::get('DatosValidacionesGenerales/{usuario}', ['uses' => 'GlobalesController@getDatosValidacionesGenerales']);
        //http://localhost/proyecto/backend/public/api/global/HistorialUsuario
        Route::get('HistorialUsuario', ['uses' => 'GlobalesController@getHistorialUsuario']);
        //http://localhost/proyecto/backend/public/api/global/carrerasAceptadas
        Route::get('carrerasAceptadas', ['uses' => 'GlobalesController@getCarrerasAceptadas']);
        //http://localhost/proyecto/backend/public/api/global/carrera/2
        Route::get('carrera/{id_carrera}', ['uses' => 'GlobalesController@getCarreraById']);
        //http://localhost/proyecto/backend/public/api/global/CambiarCarrera
        Route::post('CambiarOAgregarCarrera/{tipo}', ['uses' => 'GlobalesController@updateOrAddCarrera']);
        //http://localhost/proyecto/backend/public/api/global/uploadImgs
        Route::post('uploadImgs', ['uses' => 'GlobalesController@uploadImgs']);
        //http://localhost/proyecto/backend/public/api/global/CreateEventExtra/extra1EstatusAlum/2018-01-09/20/00
        Route::get('ImgsIntoBD', ['uses' => 'GlobalesController@getUploadImgs']);
        Route::get('{evento}/CreateEventExtra/{nombre}/{fecha}/{hora}/{minuto}', ['uses' => 'GlobalesController@createEventEvalucionExtraEspecial']);
        //http://localhost/proyecto/backend/public/api/global/AlumntosEgresadosToCertificadoPDF/1
        Route::get('AlumntosEgresadosToCertificadoPDF/{id_plantel}', ['uses' => 'GlobalesController@getAlumntosEgresadosToCertificadoPDF']);
    });
    //------------------------
    Route::group(['prefix' => 'estadisticas'], function () {
        //http://localhost/proyecto/backend/public/api/estadisticas/totalHombresMujeres
        Route::get('TotalAlumnoxGenero', ['uses' => 'EstadisticasController@getTotalAlumnoxGenero']);
        //http://localhost/proyecto/backend/public/api/estadisticas/AlumnoxGeneroxPlantel
        Route::get('AlumnoxGeneroxPlantel/{id_plantel}', ['uses' => 'EstadisticasController@getAlumnoxGeneroxPlantel']);
        //http://localhost/proyecto/backend/public/api/estadisticas/DatosOfAproOrReproXPeriodo/8/22
        Route::get('DatosOfAproOrReproXPeriodoxPlantel/{id_periodo}/{id_grupo}/{id_plantel}', ['uses' => 'EstadisticasController@getDatosOfAproOrReproXPeriodoxPlantel']);
        //http://localhost/proyecto/backend/public/api/estadisticas/DatosOfEgresadosXPeriodoXPlantel/8/1
        Route::get('DatosOfEgresadosXPeriodoXPlantel/{id_periodo}/{id_plantel}', ['uses' => 'EstadisticasController@getDatosOfEgresadosXPeriodoXPlantel']);
        
    });
    //------------------------
    Route::group(['prefix' => 'correo'], function () { 
        //http://localhost/proyecto/backend/public/api/correo/restorePassword/
        Route::get('checkEmail/{correo}/{user}', ['uses' => 'MailController@checkEmail']);
        Route::get('/comprobarEstadoEmailUsuario/{identificador}/{pass}', 'MailController@comprobarEstadoEmailUsuario');
        //http://localhost/proyecto/backend/public/api/correo/email/villegas09sanz@gmail.com/1
        Route::get('email/{email}/{user}', ['uses' => 'MailController@getTokenToChangePassword']);
        //http://localhost/proyecto/backend/public/api/correo/token/6e331ec0742dba983ed2964b2e07e566e48960c6dbae135bf93ea12844af1fc90574b62970ec8b870d371ed64e9995f4cfc84809ab6b6f183fb69f208b49ac8b
        Route::get('token/{token}', ['uses' => 'MailController@comprobarTokenPassword']); 
        Route::get('{user}/usuario/{correo}/newpassword/{newpassword}', ['uses' => 'MailController@changePassword']);
        Route::get('nombre/{name}/usuario/{correo}/tipo/{tipo}/mensaje/{mensaje}', ['uses' => 'MailController@sendEmail']);
    }); 
    //------------------------
    Route::group(['prefix' => 'user'], function () {
        //http://localhost/proyecto/backend/public/api/user
        //Route::get('{id}', ['uses' => 'UserController@getUsuario']);
        Route::post('', ['uses' => 'UserController@saveUsuario']);
        Route::get('', ['uses' => 'UserController@allUsuarios']);
        //Route::put('/delete/{id}', ['uses' => 'UserController@deleteUsuario']);
    });
    //------------------------ 
    Route::group(['prefix' => 'alumnos'], function () { 
        //http://localhost/proyecto/backend/public/api/alumnos
        Route::get('', ['uses' => 'AlumnoController@allAlumnos']);
        Route::get('{id}', ['uses' => 'AlumnoController@getAlumno']);
        Route::post('', ['uses' => 'AlumnoController@saveAlumno']);
        Route::put('/update/{id}', ['uses' => 'AlumnoController@updateAlumno']);
        //http://localhost/proyecto/backend/public/api/alumnos/plantel/1
        Route::get('/plantel/{idPlantel}', ['uses' => 'AlumnoController@alumnosPorPlantel']);
        Route::get('/busquedaPersonalizada/{miClave}/{miValor}', ['uses' => 'AlumnoController@getBusqedaPersonalizadaAlumno']);
        Route::get('/all/nlista/{id}', ['uses' => 'AlumnoController@alumnosSegunPlantelConNrolista']);
        Route::put('/delete/{id}', ['uses' => 'AlumnoController@deleteUsuario']);
        //url de secundarias
        Route::get('/clave/allSecundarias', ['uses' => 'AlumnoController@getAllSecundarias']);
        //----------------
        Route::get('/Inhabilitados/all', ['uses' => 'AlumnoController@getAlumnoInhabilitados']);
        //ALTA ALUMNO-----------
        Route::put('/Alta/id_alumno/{id}', ['uses' => 'AlumnoController@AltaAlumno']);
        //GET ALUMNO INHABILITADO----------
        Route::get('/InhabilitadobyId/id_alumno/{id_alumno}', ['uses' => 'AlumnoController@getAlumnobyIdInhabilitado']);
        //get Alumnos por materia y docente
        //http://localhost/proyecto/backend/public/api/alumnos/AlumnosPorDocenteYMateria/71/3
        Route::get('/AlumnosPorDocenteYMateria/{id_docente}/{id_materia}', ['uses' => 'AlumnoController@getAlumnosPorDocenteYMateria']);
        Route::get('/allAlumnos/ParaPasarDeSemestre', ['uses' => 'AlumnoController@getAllAlumnosParaPasarSemestre']);
        Route::get('/llamarProce/cambiarEstatus', ['uses' => 'AlumnoController@llamarProceEstatusCali']);
        Route::get('/login/{user}/password/{password}', ['uses' => 'AlumnoController@login']);
 
    });
    Route::group(['prefix' => 'docentes'], function () {
        //http://localhost/proyecto/backend/public/api/docentes
        Route::get('', ['uses' => 'DocenteController@allDocentes']);
        Route::get('listar/Responsables', ['uses' => 'DocenteController@allResponsables']);
        Route::get('id/{id}', ['uses' => 'DocenteController@getDocenteBy']); 
        Route::post('', ['uses' => 'DocenteController@saveDocente']);
        Route::put('{id}', ['uses' => 'DocenteController@updateDocente']);
        Route::put('/habilitar/{id}', ['uses' => 'DocenteController@habilitarDocente']);
        Route::get('/all/docente/plantel/{idPlantel}', ['uses' => 'DocenteController@docentesSegunPlantel']);
       // Route::delete('{id}', ['uses' => 'AlumnoController@deleteAlumno']);
       Route::get('/all/planteles/permiso', ['uses' => 'DocenteController@getPlantelesconPermiso']); 
       Route::get('/all/planteles/permiso/{numInicio}/{numTama}', ['uses' => 'DocenteController@getDirectoresconPermisoRango']);
       Route::get('/only/director/permiso/{id}', ['uses' => 'DocenteController@getPermisoDirector']);
       Route::put('/permisos/{id_rol}/actualiza', ['uses' => 'DocenteController@updateDocentesPermisos']); 
       Route::post('/fecha/{fecha_completa}/hora/{hora}/minuto/{minuto}/rol/{rol}/{estado_Ins}', ['uses' => 'DocenteController@inhabilitar']);
       //http://localhost/proyecto/backend/public/api/docentes/tipo/addAlumno
       Route::get('/tipo/addAlumno', ['uses' => 'DocenteController@getEstadoDeInscripcion']);
       Route::put('/delete/{id}', ['uses' => 'DocenteController@deleteDocente']);
       Route::get('/cargaAcademica/{id_plantel}', ['uses' => 'DocenteController@getCargaAcademica']);
       Route::get('/getcargaAcademica/id_docente/{id_docente}', ['uses' => 'DocenteController@getCargaAcademicaDocente']);
       //http://localhost/proyecto/backend/public/api/docentes/DatosporDocenteGrupoMateria/36/8/69
       Route::get('/DatosporDocenteGrupoMateria/{id_docente}/{id_grupo}/{id_materia}', ['uses' => 'DocenteController@getDatosporDocenteGrupoMateria']);
       Route::get('/idDocenteUnico/curp/{curp}', ['uses' => 'DocenteController@getIdDocenteApartirDeCurp']); 
       Route::get('/getDocentesInhabiliatos', ['uses' => 'DocenteController@getDocenteInhabilitados']);
       Route::get('/getDocenteInhabiliatoById/{idDocente}', ['uses' => 'DocenteController@getDocenteInhabilitadoById']);
       Route::put('/docenteCambioDePlantel/{id}', ['uses' => 'DocenteController@updateCambiarDePlantelDocente']); 
       Route::get('/carreras', ['uses' => 'DocenteController@getCarrerasAceptadas']);
       //http://localhost/proyecto/backend/public/api/docentes/getTotalDocenteOrResponsableByPlantel/RESPONSABLE/2
       Route::get('/getTotalDocenteOrResponsableByPlantel/{tipo}/{id_plantel}', ['uses' => 'DocenteController@getTotalDocenteOrResponsableByPlantel']);
       Route::get('/misMaterias/{id_docente}', ['uses' => 'DocenteController@getMateriasSimple']);
       
       Route::get('/Materias/yaEnCargaAca/{id_docente}/{id_grupo}', ['uses' => 'DocenteController@getMateriasYaAgregadasEnCargaAca']);
       
    });

    Route::group(['prefix' => 'auxiliares'], function () {
        //http://localhost/proyecto/backend/public/api/auxiliares
        Route::get('', ['uses' => 'AuxiliarController@allAuxiliares']);
        Route::get('{id}', ['uses' => 'AuxiliarController@getAuxiliar']);
        Route::put('{id}', ['uses' => 'AuxiliarController@updateAuxiliar']);
        Route::post('', ['uses' => 'AuxiliarController@saveAuxiliar']);
       // Route::delete('{id}', ['uses' => 'AlumnoController@deleteAlumno']);
    });
    
    Route::group(['prefix' => 'planteles'], function () {
        //http://localhost/proyecto/backend/public/api/planteles
        Route::get('', ['uses' => 'PlantelController@allPlanteles']);
        Route::get('{id}', ['uses' => 'PlantelController@getPlantel']);
        Route::put('{id}', ['uses' => 'PlantelController@updatePlantel']);
        Route::post('', ['uses' => 'PlantelController@savePlantel']);
        //GET http://localhost/proyecto/backend/public/api/planteles/rango/0/10
        Route::get('/rango/{numInicio}/{numTama}', ['uses' => 'PlantelController@getPlantelesporRango']);
        Route::get('/municipios/all', ['uses' => 'PlantelController@getMunicipios']);
        Route::get('/distritos/all/id_region/{id_region}', ['uses' => 'PlantelController@getDistritos']);
        Route::get('/localidades/codigoPostal/{codigoPostal}', ['uses' => 'PlantelController@getLocalidadesSegunCodigoPostal']);
       
        Route::get('/regiones/all/', ['uses' => 'PlantelController@getRegiones']);
        Route::get('/localidades/all/id_municipio/{id_municipio}', ['uses' => 'PlantelController@getLocalidadesSegunIdmunicipio']);
        
    }); 

    Route::group(['prefix' => 'materias'], function () {
        //http://localhost/proyecto/backend/public/api/materias
        Route::get('', ['uses' => 'MateriaController@allMaterias']);
        Route::get('{id}', ['uses' => 'MateriaController@getMateria']);
        Route::put('{id}', ['uses' => 'MateriaController@updateMateria']);
        Route::post('', ['uses' => 'MateriaController@saveMateria']);
        Route::get('/semestre/{id}', ['uses' => 'MateriaController@getMateriasSegunSemestre']);
        Route::get('/campoDisc/{capo_disc}/idDocente/{idDocente}/semestre/{semestre}', ['uses' => 'MateriaController@getDocenteCamDisAndSemestre']);
       // Route::delete('{id}', ['uses' => 'AlumnoController@deleteAlumno']);
    });

    Route::group(['prefix' => 'periodos'], function () {
        //http://localhost/proyecto/backend/public/api/periodos
        Route::get('', ['uses' => 'PeriodoController@allPeriodos']);
        Route::get('{id}', ['uses' => 'PeriodoController@getPeriodoById']);
        Route::put('{id}', ['uses' => 'PeriodoController@updatePeriodo']);
        Route::post('', ['uses' => 'PeriodoController@savePeriodo']);
        Route::get('/getPerido/enEspera', ['uses' => 'PeriodoController@periodoEnEspera']);
        Route::get('/getPerido/Activo', ['uses' => 'PeriodoController@getPeriodoActivo']);
        Route::get('/getGrupos/periodoEnEspera', ['uses' => 'PeriodoController@getgruposPeriodoEnEspera']);
        Route::post('/id_periodo/{id_periodo}', ['uses' => 'PeriodoController@eliminarPeriodoNuevo']);
        //http://localhost/proyecto/backend/public/api/periodos/GruposXPeriodo/1
        Route::get('/GruposXPeriodo/{id_periodo}', ['uses' => 'PeriodoController@getGruposXPeriodo']);
        
       // Route::delete('{id}', ['uses' => 'AlumnoController@deleteAlumno']);
    }); 

     Route::group(['prefix' => 'grupos'], function () {
        //http://localhost/proyecto/backend/public/api/grupos
        Route::get('', ['uses' => 'GrupoController@allGrupos']);
        Route::get('{id}', ['uses' => 'GrupoController@getGrupoById']);
        Route::put('{id}', ['uses' => 'GrupoController@updateGrupo']);
        Route::post('', ['uses' => 'GrupoController@saveGrupo']);
        Route::get('/all/activos', ['uses' => 'GrupoController@mostrarGrupoSegunPeriodo']);
        Route::get('/horario/nombreGrupo/{id_grupo}/{id_plantel}', ['uses' => 'GrupoController@getHorarioPorGrupo']);
        Route::get('/horario/id_alumno/{id_alumno}', ['uses' => 'GrupoController@getHorarioPorGrupoIdAlumno']);
       
    });
    Route::group(['prefix' => 'grupoAlumnos'], function () {
        //http://localhost/proyecto/backend/public/api/grupoAlumnos
        Route::post('', ['uses' => 'Grupo_AlumnoController@saveGrupoAlumno']);
        Route::post('/cargaAcademica ', ['uses' => 'Grupo_AlumnoController@savegrupoMateriaDocente']);
        //http://localhost/proyecto/backend/public/api/grupoAlumnos/calificacionesxMateria/docente/36/grupo/1/materia/3
        Route::get('/calificacionesxMateria/docente/{id_docente}/grupo/{id_grupo}/materia/{id_materia}', ['uses' => 'Grupo_AlumnoController@getCalificacionesxMateria']);
        Route::post('/calificaciones ', ['uses' => 'Grupo_AlumnoController@guardarCalificaciones']);
        Route::post('/calificacionesPendientes ', ['uses' => 'Grupo_AlumnoController@guardarCalificacionesPendientes']);
        //SE UTILIZA PARA OBTENER LAS MATERIAS A SUBIR CALIFICACIONES DE LOS ALUMNOS
        //http://localhost/proyecto/backend/public/api/grupoAlumnos/materias/id_docente/{id_docente}/grupo/{grupo} 
        Route::get('/materias/id_docente/{id_docente}/grupo/{id_grupo}', ['uses' => 'Grupo_AlumnoController@getMateriasAsignarCalificaciones']);
        Route::get('/getalumnos/id_plantel/{id_plantel}/grupo/{id_grupo}', ['uses' => 'Grupo_AlumnoController@getAlumnosPorGrupoAsignarCalif']);
        //http://localhost/proyecto/backend/public/api/grupoAlumnos/getCalificacionesxGrupo/id_grupo/9
        Route::get('/MateriasYaCursadas/{grupo}/{plantel}', ['uses' => 'Grupo_AlumnoController@getMateriasYaCursadas']);
        Route::get('/periodosYaCursados/periodo/{periodo}', ['uses' => 'Grupo_AlumnoController@getPeridosYaCursados']);

        Route::get('/gruposPeriodosYaCursados/periodo/{semestre}/{Nombreperiodo}', ['uses' => 'Grupo_AlumnoController@getGruposDelPeridoCursado']);
        
        Route::get('/getCalificacionesxGrupo/id_grupo/{id_grupo}/id_alumno/{id_alumno}', ['uses' => 'Grupo_AlumnoController@getCalificacionesxGrupo']);
        //http://localhost/proyecto/backend/public/api/grupoAlumnos/calificacionesxMateriaVista/docente/71/grupo/1/materia/3
        Route::get('/calificacionesxMateriaVista/docente/{id_docente}/grupo/{id_grupo}/materia/{id_materia}', ['uses' => 'Grupo_AlumnoController@getCalificacionesxMateriaVista']);
        Route::post('/allAlumnos/PasarSigSemestre', ['uses' => 'Grupo_AlumnoController@SaveAlumnosAlSigSemestre']);
        //http://localhost/proyecto/backend/public/api/grupoAlumnos/AlumnosPendientes/71
        Route::get('AlumnosPendientes/{id_docente}', ['uses' => 'Grupo_AlumnoController@getAlumnosPendientes']);
        Route::get('getCalificaciones/semestreActual/{id_alumno}', ['uses' => 'Grupo_AlumnoController@getCalificacionesSemestreActual']);
        //http://localhost/proyecto/backend/public/api/grupoAlumnos/IDsToCalificacion/1
        Route::get('IDsToCalificacion/{id_grupo}/{id_plantel}', ['uses' => 'Grupo_AlumnoController@getIDsToCalificacion']);
    });
});
 
Route::get('/', function () {
    return view('welcome');
});