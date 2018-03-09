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
  
    //------------------------ 
    Route::group(['prefix' => 'alumnos'], function () { 
        //http://localhost/proyecto/backend/public/api/alumnos
        Route::get('', ['uses' => 'AlumnoController@allAlumnos']);
        Route::get('{id}', ['uses' => 'AlumnoController@getAlumno']);
        Route::post('', ['uses' => 'AlumnoController@saveAlumno']);
        Route::put('/update/{id}', ['uses' => 'AlumnoController@updateAlumno']);
        Route::put('/delete/{id}', ['uses' => 'AlumnoController@deleteUsuario']);
        Route::put('/Alta/id_alumno/{id}', ['uses' => 'AlumnoController@AltaAlumno']);
        Route::get('/login/{correo}/password/{password}', ['uses' => 'AlumnoController@login']);
 
    });
 
    
  
});
 
Route::get('/', function () {
    return view('welcome');
});