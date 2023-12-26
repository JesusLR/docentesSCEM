<?php

/*
|--------------------------------------------------------------------------
| RUTAS DE ADMINISTRACIÓN
|--------------------------------------------------------------------------
|
*/

// Usuario Route
Route::resource('usuario','UserController');
Route::get('api/users','UserController@list')->name('api/users');

// Permisos Route
Route::get('permiso','PermisoController@index');
Route::get('permiso/modulo','PermisoController@modulos');

// Modulos Route
Route::get('modulo','ModuloController@index');
