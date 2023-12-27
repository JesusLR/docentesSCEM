<?php

use App\Models\User;
use App\Models\Grupo;
use App\Models\User_docente;



// Home Route
Route::get('preescolar_grupo', 'Preescolar\PreescolarGrupoController@index')->name('preescolar_grupo.index');
Route::get('api/preescolar_grupo','Preescolar\PreescolarGrupoController@list')->name('api/preescolar_grupo');
//PREESCOLAR
//Route::resource('preescolarinscritos','PreescolarInscritosController');
Route::get('api/preescolarinscritos/{grupo_id}','Preescolar\PreescolarInscritosController@list')->name('api/preescolarinscritos/{grupo_id}');
Route::get('preescolarinscritos/{grupo_id}', 'Preescolar\PreescolarInscritosController@index')->name('preescolarinscritos/{grupo_id}');
//Route::get('alumno_pagos/{alumno_id}', 'AlumnoPagosController@index');
//Route::get('api/alumno_pagos/{alumno_id}', 'AlumnoPagosController@list')->name('api/alumno_pagos/{alumno_id}');
Route::resource('preescolarcalificaciones','Preescolar\PreescolarCalificacionesController');
Route::get('preescolarinscritos/preescolarcalificaciones/{inscrito_id}/{grupo_id}/{trimestre}', 'Preescolar\PreescolarCalificacionesController@index');
Route::get('preescolarinscritos/calificaciones/primerreporte/{inscrito_id}/{persona_id}/{grado}/{grupo}/{peraniopago}', 'Preescolar\PreescolarCalificacionesController@primerreportetrimestre');
Route::get('preescolarinscritos/calificaciones/segundoreporte/{inscrito_id}/{persona_id}/{grado}/{grupo}/{peraniopago}', 'Preescolar\PreescolarCalificacionesController@segundoreportetrimestre');
Route::get('preescolarinscritos/calificaciones/tercerreporte/{inscrito_id}/{persona_id}/{grado}/{grupo}/{peraniopago}', 'Preescolar\PreescolarCalificacionesController@tercerreportetrimestre');



