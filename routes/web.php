<?php

use App\Models\User;
use App\Http\Models\Grupo;
use App\Models\User_docente;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
//Auth::routes();

// Home Route
Route::get('/','GrupoController@index')->name('home');
Route::get('/home','GrupoController@index')->name('home');
Route::get('/extraordinarios','ExtraordinarioController@index')->name('extraordinarios');
Route::get('api/extraordinario','ExtraordinarioController@list')->name('api/extraordinario');
Route::get('calificacion/agregarextra/{extraordinario_id}','ExtraordinarioController@agregarExtra');


Route::get('extracurricular','ExtracurricularController@index')->name('extracurricular');
Route::get('api/extracurricular','ExtracurricularController@list')->name('api/extracurricular');

Route::resource('extracurricularcalif','ExtracurricularCalificacionController');
Route::get('extracurricular/calificacion/agregar/{nivel}/{grupo_id}','ExtracurricularCalificacionController@agregar');
// Route::post('extraordinario/store','ExtraordinarioController@extraStore');


// Login Route
Route::get('login','LoginController@index')->name('login');
Route::post('auth','LoginController@auth')->name('auth');
Route::get('logout','LoginController@logout')->name('logout');


Route::get('cambiar_contraseña','CuentaController@cambiarPassword')->name('cambiar_contraseña');
Route::post('cambiar_contraseña','CuentaController@passwordUpdate')->name('password.update');


Route::get('/calificacion/reporte/{grupo_id}','ListasEvaluacionParcialController@imprimir')->name('home');
Route::get('/calificacion/reporte/listas_evaluacion_ordinaria/{grupo_id}', 'ListasEvaluacionOrdinariaController@imprimir');

Route::get('encuesta', 'EncuestaController@make');
Route::post('encuesta/verificar_codigo', 'EncuestaController@verificar_codigo');

Route::get('biblioteca','BibliotecaController@index');
Route::get('biblioteca_action','BibliotecaController@action');

// Route::get("/pruebaLog", function () {
//   $empleado_id = Auth::user()->empleado->id;
//   $perActual = Auth::user()->empleado->escuela->departamento->perActual;



//   $grupo = Grupo::with('materia','periodo','plan.programa.escuela.departamento.ubicacion')->select('grupos.*')
//   ->where('grupos.periodo_id',$perActual)
//   ->where('grupos.empleado_id',$empleado_id)->get();



//   dd(Auth::user()->empleado->escuela->departamento, $empleado_id, $perActual, $grupo);
// });


require (__DIR__ . '/control_escolar.php');

require (__DIR__ . '/language.php');

require (__DIR__ . '/preescolar.php');

require (__DIR__ . '/primaria.php');

require (__DIR__ . '/secundaria.php');

require (__DIR__ . '/bachiller.php');
