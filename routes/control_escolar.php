<?php

/*
|--------------------------------------------------------------------------
| RUTAS DE CONTROL ESCOLAR
|--------------------------------------------------------------------------
|
*/

// Grupo Route
Route::resource('grupo','GrupoController');
Route::get('api/grupo','GrupoController@list')->name('api/grupo');
Route::get('api/grupoEquivalente','GrupoController@listEquivalente')->name('api/grupoEquivalente');
Route::get('api/grupo/{id}','GrupoController@getGrupo');
Route::get('api/grupos/{curso_id}','GrupoController@getGrupos');
Route::get('grupo/horario/{id}','GrupoController@horario');
Route::post('grupo/agregarHorario','GrupoController@agregarHorario')->name('grupo/agregarHorario');
Route::get('grupo/eliminarHorario/{id}/{idGrupo}','GrupoController@eliminarHorario');
Route::get('api/grupo/horario/{id}','GrupoController@listHorario');
Route::get('grupo/cambiarEstado/{id}/{estado_act}','GrupoController@cambiarEstado');

// Calificación Route
Route::resource('calificacion','CalificacionController');
Route::get('calificacion/agregar/{nivel}/{grupo_id}','CalificacionController@agregar');
Route::post('calificacion/store','CalificacionController@store')->name('guarda_calificacion.store');

Route::post('extraordinario/store','ExtraordinarioController@extraStore');

//Ruta para imprimir acta de examen extraordinario en el datatable
Route::post('extraordinario/actaexamen/{extraordinario_id}','ExtraordinarioController@actaExamen');






