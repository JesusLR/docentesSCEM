<?php

/* -------------------------------------------------------------------------- */
/*                           rutas nivel bachiller                           */
/* -------------------------------------------------------------------------- */

// Programas
Route::get('bachiller_programa', 'Bachiller\BachillerProgramasController@index')->name('bachiller.bachiller_programa.index');
Route::get('bachiller_programa/list', 'Bachiller\BachillerProgramasController@list')->name('bachiller.bachiller_programa.list');
Route::get('bachiller_programa/api/programas/{escuela_id}','Bachiller\BachillerProgramasController@getProgramas');
Route::get('bachiller_programa/api/programa/{programa_id}','Bachiller\BachillerProgramasController@getPrograma');
Route::get('bachiller_programa/create', 'Bachiller\BachillerProgramasController@create')->name('bachiller.bachiller_programa.create');
Route::get('bachiller_programa/{id}/edit', 'Bachiller\BachillerProgramasController@edit')->name('bachiller.bachiller_programa.edit');
Route::get('bachiller_programa/{id}', 'Bachiller\BachillerProgramasController@show')->name('bachiller.bachiller_programa.show');
Route::post('bachiller_programa', 'Bachiller\BachillerProgramasController@store')->name('bachiller.bachiller_programa.store');
Route::put('bachiller_programa/{id}', 'Bachiller\BachillerProgramasController@update')->name('bachiller.bachiller_programa.update');
Route::delete('bachiller_programa/{id}', 'Bachiller\BachillerProgramasController@destroy')->name('bachiller.bachiller_programa.destroy');

// planes
Route::get('bachiller_plan', 'Bachiller\BachillerPlanesController@index')->name('bachiller.bachiller_plan.index');
Route::get('bachiller_plan/list', 'Bachiller\BachillerPlanesController@list')->name('bachiller.bachiller_plan.list');
Route::get('bachiller_plan/api/planes/{id}','Bachiller\BachillerPlanesController@getPlanes');
Route::get('bachiller_plan/create', 'Bachiller\BachillerPlanesController@create')->name('bachiller.bachiller_plan.create');
Route::get('bachiller_plan/{id}/edit', 'Bachiller\BachillerPlanesController@edit')->name('bachiller.bachiller_plan.edit');
Route::get('bachiller_plan/{id}', 'Bachiller\BachillerPlanesController@show')->name('bachiller.bachiller_plan.show');
Route::get('bachiller_plan/get_plan/{plan_id}', 'Bachiller\BachillerPlanesController@getPlan');
Route::get('bachiller_plan/plan/semestre/{id}','Bachiller\BachillerPlanesController@getSemestre');
Route::post('bachiller_plan', 'Bachiller\BachillerPlanesController@store')->name('bachiller.bachiller_plan.store');
Route::post('bachiller_plan/cambiarPlanEstado', 'Bachiller\BachillerPlanesController@cambiarPlanEstado');
Route::put('bachiller_plan/{id}', 'Bachiller\BachillerPlanesController@update')->name('bachiller.bachiller_plan.update');
Route::delete('bachiller_plan/{id}', 'Bachiller\BachillerPlanesController@destroy')->name('bachiller.bachiller_plan.destroy');

// Periodos
Route::get('bachiller_periodo', 'Bachiller\BachillerPeriodosController@index')->name('bachiller.bachiller_periodo.index');
Route::get('bachiller_periodo/list', 'Bachiller\BachillerPeriodosController@list')->name('bachiller.bachiller_periodo.list');
Route::get('bachiller_periodo/api/periodos/{departamento_id}','Bachiller\BachillerPeriodosController@getPeriodos');
Route::get('bachiller_periodo/api/periodo/{id}','Bachiller\BachillerPeriodosController@getPeriodo');
Route::get('bachiller_periodo/api/periodoPerAnioPago/{id}','Bachiller\BachillerPeriodosController@getPeriodoPerAnioPago');
Route::get('bachiller_periodo/api/periodo/{departamento_id}/posteriores', 'Bachiller\BachillerPeriodosController@getPeriodos_afterDate');
Route::get('bachiller_periodo/create', 'Bachiller\BachillerPeriodosController@create')->name('bachiller.bachiller_periodo.create');
Route::get('bachiller_periodo/{id}/edit', 'Bachiller\BachillerPeriodosController@edit')->name('bachiller.bachiller_periodo.edit');
Route::get('bachiller_periodo/{id}', 'Bachiller\BachillerPeriodosController@show')->name('bachiller.bachiller_periodo.show');
Route::get('bachiller_periodo/api/periodoByDepartamento/{departamentoId}','Bachiller\BachillerPeriodosController@getPeriodosByDepartamento');
Route::post('bachiller_periodo', 'Bachiller\BachillerPeriodosController@store')->name('bachiller.bachiller_periodo.store');
Route::put('bachiller_periodo/{id}', 'Bachiller\BachillerPeriodosController@update')->name('bachiller.bachiller_periodo.update');
Route::delete('bachiller_periodo/{id}', 'Bachiller\BachillerPeriodosController@destroy')->name('bachiller.bachiller_periodo.destroy');

// materias
Route::get('bachiller_materia','Bachiller\BachillerMateriasController@index')->name('bachiller.bachiller_materia.index');
Route::get('bachiller_materia/list','Bachiller\BachillerMateriasController@list');
Route::get('bachiller_materia/create','Bachiller\BachillerMateriasController@create')->name('bachiller.bachiller_materia.create');
Route::get('bachiller_materia/{id}/edit','Bachiller\BachillerMateriasController@edit')->name('bachiller.bachiller_materia.edit');
Route::get('bachiller_materia/{id}','Bachiller\BachillerMateriasController@show')->name('bachiller.bachiller_materia.show');
Route::get('bachiller_materia/prerequisitos/{id}','Bachiller\BachillerMateriasController@prerequisitos');
Route::get('bachiller_materia/materia/prerequisitos/{id}','Bachiller\BachillerMateriasController@listPreRequisitos');
Route::get('bachiller_materia/eliminarPrerequisito/{id}/{materia_id}','Bachiller\BachillerMateriasController@eliminarPrerequisito');
Route::get('bachiller_materia/materias/{semestre}/{planId}','Bachiller\BachillerMateriasController@getMaterias');
Route::get('bachiller_materia/getMateriasByPlan/{plan}/','Bachiller\BachillerMateriasController@getMateriasByPlan')->name('bachiller.bachiller_materia.getMateriasByPlan');
Route::post('bachiller_materia','Bachiller\BachillerMateriasController@store')->name('bachiller.bachiller_materia.store');
Route::post('bachiller_materia/agregarPreRequisitos','Bachiller\BachillerMateriasController@agregarPreRequisitos')->name('bachiller.bachiller_materia.agregarPreRequisitos');
Route::put('bachiller_materia/{id}','Bachiller\BachillerMateriasController@update')->name('bachiller.bachiller_materia.update');
Route::delete('bachiller_materia/{id}','Bachiller\BachillerMateriasController@destroy');

// CGT
Route::get('bachiller_cgt','Bachiller\BachillerCGTController@index')->name('bachiller.bachiller_cgt.index');
Route::get('bachiller_cgt/list','Bachiller\BachillerCGTController@list');
Route::get('bachiller_cgt/create','Bachiller\BachillerCGTController@create')->name('bachiller.bachiller_cgt.create');
Route::get('bachiller_cgt/{id}/edit','Bachiller\BachillerCGTController@edit')->name('bachiller.bachiller_cgt.edit');
Route::get('bachiller_cgt/{id}','Bachiller\BachillerCGTController@show')->name('bachiller.bachiller_cgt.show');
Route::get('bachiller_cgt/apiss/cgts/{plan_id}/{periodo_id}/{semestre}','Bachiller\BachillerCGTController@getCgtsSemestre');
Route::get('bachiller_cgt/api/cgts/{plan_id}/{periodo_id}','Bachiller\BachillerCGTController@getCgts');
Route::get('bachiller_cgt/api/cgts_sin_N/{plan_id}/{periodo_id}','Bachiller\BachillerCGTController@getCgtsSinN');
Route::post('bachiller_cgt','Bachiller\BachillerCGTController@store')->name('bachiller.bachiller_cgt.store');
Route::put('bachiller_cgt/{id}','Bachiller\BachillerCGTController@update')->name('bachiller.bachiller_cgt.update');
Route::delete('bachiller_cgt/{id}','Bachiller\BachillerCGTController@destroy')->name('bachiller.bachiller_cgt.destroy');

// Cambiar matrículas de alumnos (de un cgt).
Route::get('bachiller_cambiar_matriculas_cgt/{cgt_id}', 'Bachiller\BachillerCambiarMatriculasController@lista_alumnos');
Route::get('bachiller_cambiar_matriculas_cgt/{cgt_id}/buscar_alumno/{alumno_id}', 'Bachiller\BachillerCambiarMatriculasController@buscarAlumnoEnCgt');
Route::post('bachiller_cambiar_matriculas_cgt/{cgt_id}/actualizar/{alumno_id}', 'Bachiller\BachillerCambiarMatriculasController@cambiarMatricula');
Route::post('bachiller_cambiar_matriculas_cgt/{cgt_id}/actualizar_lista', 'Bachiller\BachillerCambiarMatriculasController@cambiarMultiplesMatriculas');

// Porcentaje
Route::get('bachiller_porcentaje','Bachiller\BachillerPorcentajeController@index')->name('bachiller.bachiller_porcentaje.index');
Route::get('bachiller_porcentaje/list','Bachiller\BachillerPorcentajeController@list');
Route::get('bachiller_porcentaje/create','Bachiller\BachillerPorcentajeController@create')->name('bachiller.bachiller_porcentaje.create');
Route::get('bachiller_porcentaje/{id}/edit','Bachiller\BachillerPorcentajeController@edit')->name('bachiller.bachiller_porcentaje.edit');
Route::get('bachiller_porcentaje/{id}','Bachiller\BachillerPorcentajeController@show')->name('bachiller.bachiller_porcentaje.show');
Route::post('bachiller_porcentaje','Bachiller\BachillerPorcentajeController@store')->name('bachiller.bachiller_porcentaje.store');
Route::put('bachiller_porcentaje/{id}','Bachiller\BachillerPorcentajeController@update')->name('bachiller.bachiller_porcentaje.update');
Route::delete('bachiller_porcentaje/{id}','Bachiller\BachillerPorcentajeController@destroy')->name('bachiller.bachiller_porcentaje.destroy');



/* ---------------------------- Módulo de Alumnos --------------------------- */
Route::get('/bachiller_alumno', 'Bachiller\BachillerAlumnosController@index')->name('bachiller.bachiller_alumno.index');
Route::get('bachiller_alumno/list','Bachiller\BachillerAlumnosController@list')->name('bachiller.bachiller_alumno.list');
Route::get('bachiller_alumno/create','Bachiller\BachillerAlumnosController@create')->name('bachiller.bachiller_alumno.create');
Route::post('bachiller_alumno','Bachiller\BachillerAlumnosController@store')->name('bachiller.bachiller_alumno.store');
Route::get('bachiller_alumno/verificar_persona', 'Bachiller\BachillerAlumnosController@verificarExistenciaPersona')->name('bachiller.bachiller_alumno.verificar_persona');
Route::get('bachiller_alumno/{id}/edit','Bachiller\BachillerAlumnosController@edit')->name('bachiller.bachiller_alumno.edit');
Route::get('bachiller_alumno/{id}','Bachiller\BachillerAlumnosController@show')->name('bachiller.bachiller_alumno.show');
Route::get('bachiller_alumno/ultimo_curso/{alumno_id}', 'Bachiller\BachillerAlumnosController@ultimoCurso')->name('bachiller/bachiller_alumno/ultimo_curso/{alumno_id}');
Route::post('bachiller_alumno/api/getMultipleAlumnosByFilter','Bachiller\BachillerAlumnosController@getMultipleAlumnosByFilter');
Route::get('bachiller_alumno/listHistorialPagosAluclave/{aluClave}','Bachiller\BachillerAlumnosController@listHistorialPagosAluclave')->name('bachiller.bachiller_alumno.listHistorialPagosAluclave');
Route::get('bachiller_alumno/conceptosBaja','Bachiller\BachillerAlumnosController@conceptosBaja')->name('bachiller.bachiller_alumno.conceptosBaja');
Route::get('bachiller_alumno/cambiar_matricula/{alumnoId}','Bachiller\BachillerAlumnosController@cambiarMatricula')->name("preescolar_alumnos.cambiarMatricula");
Route::get('bachiller_alumno/api/secundariaProcedencia/{municipio_id}','Bachiller\BachillerAlumnosController@secundariaProcedencia')->name('bachiller_alumno/api/secundariaProcedencia');

Route::get('bachiller_alumno/alumnoById/{alumnoId}','Bachiller\BachillerAlumnosController@getAlumnoById');
Route::post('bachiller_alumno/cambiarEstatusAlumno','Bachiller\BachillerAlumnosController@cambiarEstatusAlumno')->name("bachiller.bachiller_alumno.cambiarEstatusAlumno");
Route::post('bachiller_alumno/cambiar_matricula/edit','Bachiller\BachillerAlumnosController@postCambiarMatricula')->name("bachiller.bachiller_alumno.cambiarMatricula");
Route::post('bachiller_alumno/rehabilitar_alumno/{alumno_id}','Bachiller\BachillerAlumnosController@rehabilitarAlumno')->name('Bachiller\BachillerAlumnosController/rehabilitar_alumno/{alumno_id}');
Route::post('bachiller_alumno/registrar_empleado/{empleado_id}', 'Bachiller\BachillerAlumnosController@empleado_crearAlumno')->name('Bachiller\BachillerAlumnosController/registrar_empleado/{empleado_id}');
Route::post('bachiller_alumno/tutores/nuevo_tutor','Bachiller\BachillerAlumnosController@crearTutor')->name('bachiller.bachiller_alumno.tutores.nuevo_tutor');
Route::put('bachiller_alumno/{id}','Bachiller\BachillerAlumnosController@update')->name('bachiller.bachiller_alumno.update');
Route::delete('bachiller_alumno/{id}','Bachiller\BachillerAlumnosController@destroy')->name('bachiller.bachiller_alumno.destroy');

/* ------------------------------ Módulo cursos ----------------------------- */
//Route::get('/home', 'Bachiller\BachillerCursoController@index')->name('Bachiller_curso.index');
Route::get('/bachiller_curso', 'Bachiller\BachillerCursoController@index')->name('bachiller.bachiller_curso.index');
Route::get('bachiller_curso/{curso_id}/constancia_beca/','Bachiller\BachillerCursoController@constanciaBeca')->name('bachiller.bachiller_curso.constanciaBeca');
Route::get('bachiller_curso/listGruposAlumno/{aluClave}','Bachiller\BachillerCursoController@listGruposAlumno');
Route::get('bachiller_curso/grupos_alumno/{id}','Bachiller\BachillerCursoController@viewCalificaciones');
Route::get('/bachiller_curso/create', 'Bachiller\BachillerCursoController@create')->name('bachiller.bachiller_curso.create');
Route::get('bachiller_curso/list','Bachiller\BachillerCursoController@list')->name('bachiller.bachiller_curso.list');
Route::get('bachiller_curso/conceptosBaja','Bachiller\BachillerCursoController@conceptosBaja')->name('bachiller.bachiller_curso.conceptosBaja');
Route::get('bachiller_curso/{id}','Bachiller\BachillerCursoController@show')->name('bachiller.bachiller_curso.show');
Route::get('bachiller_curso/{id}/edit','Bachiller\BachillerCursoController@edit')->name('bachiller.bachiller_curso.edit');
Route::get('bachiller_curso/api/cursos/{cgt_id}','Bachiller\BachillerCursoController@getCursos');
Route::get('bachiller_curso/api/curso/alumno/{aluClave}/{cuoAnio}','Bachiller\BachillerCursoController@getCursoAlumno');
Route::put('bachiller_curso/{id}','Bachiller\BachillerCursoController@update')->name('bachiller.bachiller_curso.update');
Route::post('/bachiller_curso', 'Bachiller\BachillerCursoController@store')->name('bachiller.bachiller_curso.store');
Route::get('bachiller_curso/listHistorialPagos/{curso_id}','Bachiller\BachillerCursoController@listHistorialPagos')->name('bachiller.bachiller_curso.listHistorialPagos');
Route::get('bachiller_curso/api/curso/{curso_id}','Bachiller\BachillerCursoController@listPreinscritoDetalle')->name('bachiller_curso/api/listPreinscritoDetalle');
Route::get('bachiller_curso/{curso_id}/historial_calificaciones_alumno/','Bachiller\BachillerCursoController@historialCalificacionesAlumno')->name('bachiller.bachiller_curso.historialCalificacionesAlumno');
Route::get('bachiller_curso/api/curso/{curso_id}/listHistorialCalifAlumnos/','Bachiller\BachillerCursoController@listHistorialCalifAlumnos')->name('bachiller.bachiller_curso.listHistorialCalifAlumnos');
Route::get('bachiller_curso/api/curso/{curso}/verificar_materias_cargadas', 'Bachiller\BachillerCursoController@verificar_materias_cargadas');
Route::get('bachiller_curso/api/curso/infoBaja/{curso_id}','Bachiller\BachillerCursoController@infoBaja')->name('bachiller.bachiller_curso.api.infoBaja');
Route::get('bachiller_curso/listPosiblesHermanos/{curso_id}','Bachiller\BachillerCursoController@listPosiblesHermanos')->name('bachiller.bachiller_curso.listPosiblesHermanos');
Route::post('bachiller_curso/bajaCurso','Bachiller\BachillerCursoController@bajaCurso')->name('bachiller.bachiller_curso.bajaCurso');
Route::get('bachiller_curso/observaciones/{curso_id}/', 'Bachiller\BachillerCursoController@observaciones')->name('bachiller.bachiller_curso.observaciones');
Route::post('bachiller_curso/storeObservaciones','Bachiller\BachillerCursoController@storeObservaciones')->name('bachiller.bachiller_curso.storeObservacionesCurso');
Route::post('bachiller_curso/curso/altaCurso','Bachiller\BachillerCursoController@altaCurso')->name('bachiller.bachiller_curso.altaCurso');
Route::get('bachiller_curso/curso_archivo_observaciones/{curso_id}','Bachiller\BachillerCursoController@cursoArchivoObservaciones')->name('bachiller.bachiller_curso.curso_archivo_observaciones');
Route::get('bachiller_curso/crearReferencia/{curso_id}/{tienePagoCeneval}','Bachiller\BachillerCursoController@crearReferenciaBBVA')->name('bachiller.bachiller_curso.crearReferencia');
Route::get('bachiller_curso/crearReferenciaHSBC/{curso_id}/{tienePagoCeneval}','Bachiller\BachillerCursoController@crearReferenciaHSBC')->name('bachiller.bachiller_curso.crearReferenciaHSBC');
Route::get('bachiller_curso/listMateriasFaltantes/{curso_id}/','Bachiller\BachillerCursoController@listMateriasFaltantes')->name('bachiller.bachiller_curso.listMateriasFaltantes');
Route::get('bachiller_curso/getDepartamentosListaCompleta/{ubicacion_id}/','Bachiller\BachillerCursoController@getDepartamentosListaCompleta')->name('bachiller.bachiller_curso.getDepartamentosListaCompleta');
Route::get('bachiller_curso/grupos_alumno/ajustar_calificacion/{id}/{aluClave}/{curso_id}','Bachiller\BachillerCursoController@ajustar_calificacion');
Route::get('bachiller_curso/getCalificacionUnicoAlumno/{id}/{grupoId}/{aluClave}','Bachiller\BachillerCursoController@getCalificacionUnicoAlumno');
Route::patch('bachiller_curso/getCalificacionUnicoAlumno/{id}','Bachiller\BachillerCursoController@ajustar_calificacion_update')->name('bachiller.bachiller_curso.ajustar_calificacion_update');

Route::get('bachiller_curso_images/{filename}/{folder}', function ($filename, $folder)
{
    //$path = app_path('upload') . '/' . $filename;

    $path = storage_path(env("Bachiller_IMAGEN_CURSO_PATH") . $folder ."/".$filename);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Route::delete('bachiller_curso/delete/{id}','Bachiller\BachillerCursoController@destroy');


/* ----------------------- Módulo de historia clinica ----------------------- */
Route::get('bachiller_historia_clinica', 'Bachiller\BachillerAlumnosHistoriaClinicaController@index')->name('bachiller.bachiller_historia_clinica.index');
Route::get('bachiller_historia_clinica/list', 'Bachiller\BachillerAlumnosHistoriaClinicaController@list')->name('bachiller.bachiller_historia_clinica.list');
Route::get('bachiller_historia_clinica/api/estados/{id}','Bachiller\BachillerAlumnosHistoriaClinicaController@getEstados');
Route::get('bachiller_historia_clinica/api/municipios/{id}','Bachiller\BachillerAlumnosHistoriaClinicaController@getMunicipios');
Route::get('bachiller_historia_clinica/create', 'Bachiller\BachillerAlumnosHistoriaClinicaController@create')->name('bachiller.bachiller_historia_clinica.create');
Route::get('bachiller_historia_clinica/{id}', 'Bachiller\BachillerAlumnosHistoriaClinicaController@show')->name('bachiller.bachiller_historia_clinica.show');
Route::get('bachiller_historia_clinica/{id}/edit', 'Bachiller\BachillerAlumnosHistoriaClinicaController@edit')->name('bachiller.bachiller_historia_clinica.edit');
Route::post('bachiller_historia_clinica/', 'Bachiller\BachillerAlumnosHistoriaClinicaController@store')->name('bachiller.bachiller_historia_clinica.store');
Route::put('bachiller_historia_clinica/{historia}', 'Bachiller\BachillerAlumnosHistoriaClinicaController@update')->name('bachiller.bachiller_historia_clinica.update');



/* --------------------------- Modulo asignar CGT --------------------------- */
Route::get('bachiller_asignar_cgt/create', 'Bachiller\BachillerAsignarCGTController@edit')->name('bachiller.bachiller_asignar_cgt.edit');
Route::get('bachiller_asignar_cgt/getGradoGrupo/{periodo_id}/{programa_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerAsignarCGTController@getGradoGrupo');
Route::get('bachiller_asignar_cgt/getAlumnosGrado/{periodo_id}/{programa_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerAsignarCGTController@getAlumnosGrado');
Route::get('bachiller_asignar_cgt/getBachillerInscritoCursos/{periodo_id}/{programa_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerAsignarCGTController@getBachillerInscritoCursos');
Route::post('bachiller_asignar_cgt/create', 'Bachiller\BachillerAsignarCGTController@update')->name('bachiller.bachiller_asignar_cgt.update');

/* --------------------------- Modulo Cambiar CGT --------------------------- */
Route::get('bachiller_cambiar_cgt/create', 'Bachiller\BachillerCambiarCGTController@edit')->name('bachiller.bachiller_cambiar_cgt.edit');
Route::get('bachiller_cambiar_cgt/getGradoGrupo/{periodo_id}/{programa_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerCambiarCGTController@getGradoGrupo');
Route::get('bachiller_cambiar_cgt/getAlumnosGrado/{periodo_id}/{programa_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerCambiarCGTController@getAlumnosGrado');
Route::get('bachiller_cambiar_cgt/getBachillerInscritoCursos/{periodo_id}/{programa_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerCambiarCGTController@getBachillerInscritoCursos');
Route::post('bachiller_cambiar_cgt/create', 'Bachiller\BachillerCambiarCGTController@update')->name('bachiller.bachiller_cambiar_cgt.update');



/* ---------------------------- Módulo de grupos Mérida - Valladolid---------------------------- */
Route::get('bachiller_grupo_yucatan', 'Bachiller\BachillerGrupoYucatanController@index')->name('bachiller.bachiller_grupo_yucatan.index');
Route::get('bachiller_grupo_yucatan/list', 'Bachiller\BachillerGrupoYucatanController@list')->name('bachiller.bachiller_grupo_yucatan.list');
Route::get('bachiller_grupo_yucatan/create', 'Bachiller\BachillerGrupoYucatanController@create')->name('bachiller.bachiller_grupo_yucatan.create');
Route::post('bachiller_grupo_yucatan', 'Bachiller\BachillerGrupoYucatanController@store')->name('bachiller.bachiller_grupo_yucatan.store');
Route::get('bachiller_grupo_yucatan/{id}/edit', 'Bachiller\BachillerGrupoYucatanController@edit')->name('bachiller.bachiller_grupo_yucatan.edit');
Route::put('bachiller_grupo_yucatan/{id}', 'Bachiller\BachillerGrupoYucatanController@update')->name('bachiller.bachiller_grupo_yucatan.update');
Route::get('bachiller_grupo_yucatan/{id}', 'Bachiller\BachillerGrupoYucatanController@show')->name('bachiller.bachiller_grupo_yucatan.show');
Route::get('bachiller_grupo_yucatan/api/grupoEquivalente/{periodo_id}','Bachiller\BachillerGrupoYucatanController@listEquivalente')->name('bachiller_grupo_yucatan/api/grupoEquivalente');
Route::get('bachiller_grupo_yucatan/materias/{semestre}/{planId}','Bachiller\BachillerMateriasController@getBachillerMaterias');
Route::get('bachiller_grupo_yucatan/materiaComplementaria/{bachiller_materia_id}/{plan_id}/{periodo_id}/{grado}','Bachiller\BachillerGrupoYucatanController@materiaComplementaria');
Route::get('bachiller_grupo_yucatan/api/departamentos/{id}','Bachiller\BachillerGrupoYucatanController@getDepartamentos');
Route::get('bachiller_grupo_yucatan/api/escuelas/{id}/{otro?}','Bachiller\BachillerGrupoYucatanController@getEscuelas');
Route::get('bachiller_grupo_yucatan/{id}/evidencia','Bachiller\BachillerGrupoYucatanController@evidenciaTable')->name('bachiller.bachiller_grupo_yucatan.evidenciaTable');
Route::get('bachiller_grupo_yucatan/getGrupos/{id}','Bachiller\BachillerGrupoYucatanController@getGrupos');
Route::get('bachiller_grupo_yucatan/getMaterias/{id}','Bachiller\BachillerGrupoYucatanController@getMaterias');
Route::get('bachiller_grupo_yucatan/getMesEvidencias/{id}','Bachiller\BachillerGrupoYucatanController@getMesEvidencias'); //Get evidencias mes
Route::get('bachiller_grupo_yucatan/horario/{id}','Bachiller\BachillerGrupoYucatanController@horario')->name('bachiller_grupo_yucatan.horario');
Route::get('bachiller_grupo_yucatan/eliminarHorario/{id}/{idGrupo}','Bachiller\BachillerGrupoYucatanController@eliminarHorario');
Route::get('api/bachiller_grupo_yucatan/horario/{id}','Bachiller\BachillerGrupoYucatanController@listHorario');
Route::get('api/bachiller_grupo_yucatan/horario_admin/{empleado_id}/{periodo_id}','Bachiller\BachillerGrupoYucatanController@listHorarioAdmin');
Route::post('bachiller_grupo_yucatan/agregarHorario','Bachiller\BachillerGrupoYucatanController@agregarHorario')->name('bachiller_grupo_yucatan.agregarHorario');
Route::post('bachiller_grupo_yucatan/verificarHorasRepetidas','Bachiller\BachillerGrupoYucatanController@verificarHorasRepetidas');
Route::post('bachiller_grupo_yucatan/evidencias','Bachiller\BachillerGrupoYucatanController@guardar_actualizar_evidencia')->name('bachiller.bachiller_grupo_yucatan.guardar_actualizar_evidencia');
Route::get('bachiller_calificacion/getMeses/{mes}','Bachiller\BachillerGrupoYucatanController@getMeses');
Route::get('bachiller_calificacion/getNumeroEvaluacion/{mes}','Bachiller\BachillerGrupoYucatanController@getNumeroEvaluacion');
Route::get('bachiller_calificacion/api/getEvidencias/{id_grupo}/{id}','Bachiller\BachillerGrupoYucatanController@getEvidencias');
Route::delete('bachiller_grupo_yucatan/{id}', 'Bachiller\BachillerGrupoYucatanController@destroy')->name('bachiller.bachiller_grupo_yucatan.destroy');

/* ---------------------------- Módulo de grupos Chetumal---------------------------- */
Route::get('bachiller_grupo_chetumal', 'Bachiller\BachillerGrupoChetumalController@index')->name('bachiller.bachiller_grupo_chetumal.index');
Route::get('bachiller_grupo_chetumal/list', 'Bachiller\BachillerGrupoChetumalController@list')->name('bachiller.bachiller_grupo_chetumal.list');
Route::get('bachiller_grupo_chetumal/create', 'Bachiller\BachillerGrupoChetumalController@create')->name('bachiller.bachiller_grupo_chetumal.create');
Route::post('bachiller_grupo_chetumal', 'Bachiller\BachillerGrupoChetumalController@store')->name('bachiller.bachiller_grupo_chetumal.store');
Route::get('bachiller_grupo_chetumal/{id}/edit', 'Bachiller\BachillerGrupoChetumalController@edit')->name('bachiller.bachiller_grupo_chetumal.edit');
Route::put('bachiller_grupo_chetumal/{id}', 'Bachiller\BachillerGrupoChetumalController@update')->name('bachiller.bachiller_grupo_chetumal.update');
Route::get('bachiller_grupo_chetumal/{id}', 'Bachiller\BachillerGrupoChetumalController@show')->name('bachiller.bachiller_grupo_chetumal.show');
Route::get('bachiller_grupo_chetumal/api/grupoEquivalente/{periodo_id}','Bachiller\BachillerGrupoChetumalController@listEquivalente')->name('bachiller_grupo_chetumal/api/grupoEquivalente');
Route::get('bachiller_grupo_chetumal/materias/{semestre}/{planId}','Bachiller\BachillerMateriasController@getBachillerMaterias');
Route::get('bachiller_grupo_chetumal/materiaComplementaria/{bachiller_materia_id}/{plan_id}/{periodo_id}/{grado}','Bachiller\BachillerGrupoChetumalController@materiaComplementaria');
Route::get('bachiller_grupo_chetumal/api/departamentos/{id}','Bachiller\BachillerGrupoChetumalController@getDepartamentos');
Route::get('bachiller_grupo_chetumal/api/escuelas/{id}/{otro?}','Bachiller\BachillerGrupoChetumalController@getEscuelas');
Route::get('bachiller_grupo_chetumal/{id}/evidencia','Bachiller\BachillerGrupoChetumalController@evidenciaTable')->name('bachiller.bachiller_grupo_chetumal.evidenciaTable');
Route::get('bachiller_grupo_chetumal/getGrupos/{id}','Bachiller\BachillerGrupoChetumalController@getGrupos');
Route::get('bachiller_grupo_chetumal/getMaterias/{id}','Bachiller\BachillerGrupoChetumalController@getMaterias');
Route::get('bachiller_grupo_chetumal/getMesEvidencias/{id}','Bachiller\BachillerGrupoChetumalController@getMesEvidencias'); //Get evidencias mes
Route::get('bachiller_grupo_chetumal/horario/{id}','Bachiller\BachillerGrupoChetumalController@horario')->name('bachiller_grupo_chetumal.horario');
Route::get('bachiller_grupo_chetumal/eliminarHorario/{id}/{idGrupo}','Bachiller\BachillerGrupoChetumalController@eliminarHorario');
Route::get('api/bachiller_grupo_chetumal/horario/{id}','Bachiller\BachillerGrupoChetumalController@listHorario');
Route::get('api/bachiller_grupo_chetumal/horario_admin/{empleado_id}/{periodo_id}','Bachiller\BachillerGrupoChetumalController@listHorarioAdmin');
Route::post('bachiller_grupo_chetumal/agregarHorario','Bachiller\BachillerGrupoChetumalController@agregarHorario')->name('bachiller_grupo_chetumal.agregarHorario');
Route::post('bachiller_grupo_chetumal/verificarHorasRepetidas','Bachiller\BachillerGrupoChetumalController@verificarHorasRepetidas');
Route::post('bachiller_grupo_chetumal/evidencias','Bachiller\BachillerGrupoChetumalController@guardar_actualizar_evidencia')->name('bachiller.bachiller_grupo_chetumal.guardar_actualizar_evidencia');
Route::get('bachiller_calificacion_chetumal/getMeses/{mes}','Bachiller\BachillerGrupoChetumalController@getMeses');
Route::get('bachiller_calificacion_chetumal/getNumeroEvaluacion/{mes}','Bachiller\BachillerGrupoChetumalController@getNumeroEvaluacion');
Route::get('bachiller_calificacion_chetumal/api/getEvidencias/{id_grupo}/{id}','Bachiller\BachillerGrupoChetumalController@getEvidencias');
Route::delete('bachiller_grupo_chetumal/{id}', 'Bachiller\BachillerGrupoChetumalController@destroy')->name('bachiller.bachiller_grupo_chetumal.destroy');

/* ------------------------ Módulo de asignar grupos Mérida - Valladolid------------------------ */
Route::get('/bachiller_asignar_grupo', 'Bachiller\BachillerAsignarGrupoController@index')->name('bachiller.bachiller_asignar_grupo.index');
Route::get('/bachiller_asignar_grupo/list', 'Bachiller\BachillerAsignarGrupoController@list')->name('bachiller.bachiller_asignar_grupo.list');
Route::get('/bachiller_asignar_grupo/create', 'Bachiller\BachillerAsignarGrupoController@create')->name('bachiller.bachiller_asignar_grupo.create');
Route::post('/bachiller_asignar_grupo', 'Bachiller\BachillerAsignarGrupoController@store')->name('bachiller.bachiller_asignar_grupo.store');
Route::get('bachiller_asignar_grupo/{id}/edit', 'Bachiller\BachillerAsignarGrupoController@edit')->name('bachiller.bachiller_asignar_grupo.edit');
Route::put('bachiller_asignar_grupo/{id}', 'Bachiller\BachillerAsignarGrupoController@update')->name('bachiller.bachiller_asignar_grupo.update');
Route::get('bachiller_asignar_grupo/{id}', 'Bachiller\BachillerAsignarGrupoController@show')->name('bachiller.bachiller_asignar_grupo.show');
Route::delete('bachiller_asignar_grupo/{id}', 'Bachiller\BachillerAsignarGrupoController@destroy')->name('bachiller.bachiller_asignar_grupo.destroy');
Route::get('bachiller_asignar_grupo/cambiar_grupo/{inscritoId}', 'Bachiller\BachillerAsignarGrupoController@cambiarGrupo')->name('bachiller.bachiller_asignar_grupo.cambiar_grupo');
Route::post('bachiller_asignar_grupo/postCambiarGrupo', 'Bachiller\BachillerAsignarGrupoController@postCambiarGrupo')->name('bachiller.bachiller_asignar_grupo.postCambiarGrupo');
// Route::get('api/grupos/{curso_id}','Bachiller\BachillerAsignarGrupoController@getGrupos');
Route::get('bachiller_asignar_grupo/obtener_grupos/{curso_id}','Bachiller\BachillerAsignarGrupoController@ObtenerGrupos');
Route::get('bachiller_asignar_grupo/getDepartamentos/{id}','Bachiller\BachillerAsignarGrupoController@getDepartamentos');
Route::get('bachiller_asignar_grupo/getEscuelas/{id}/{otro?}','Bachiller\BachillerAsignarGrupoController@getEscuelas');

//APLICAR PAGOS MANUALES
Route::get('bachiller/pagos/aplicar_pagos','Bachiller\BachillerAplicarPagosController@index');
Route::get('bachiller/api/pagos/listadopagos','Bachiller\BachillerAplicarPagosController@list');
Route::get('bachiller/pagos/aplicar_pagos/create','Bachiller\BachillerAplicarPagosController@create');
Route::get('bachiller/pagos/aplicar_pagos/edit/{id}','Bachiller\BachillerAplicarPagosController@edit');
Route::post('bachiller/pagos/aplicar_pagos/update','Bachiller\BachillerAplicarPagosController@update')->name("bachillerAplicarPagos.update");
Route::post('bachiller/pagos/aplicar_pagos/existeAlumnoByClavePago','Bachiller\BachillerAplicarPagosController@existeAlumnoByClavePago')->name("bachillerAplicarPagos.existeAlumnoByClavePago");
Route::post('bachiller/pagos/aplicar_pagos/store','Bachiller\BachillerAplicarPagosController@store')->name("bachillerAplicarPagos.store");
Route::delete('bachiller/pagos/aplicar_pagos/delete/{id}','Bachiller\BachillerAplicarPagosController@destroy')->name("bachillerAplicarPagos.destroy");
Route::get('bachiller/pagos/aplicar_pagos/detalle/{pagoId}','Bachiller\BachillerAplicarPagosController@detalle')->name("bachillerAplicarPagos.detalle");
Route::post('bachiller/api/pagos/verificarExistePago/','Bachiller\BachillerAplicarPagosController@verificarExistePago')->name("bachillerAplicarPagos.verificarExistePago");
Route::get('bachiller/api/aplicar_pagos/buscar_inscripciones_educacion_continua/{pagClaveAlu}', 'Bachiller\BachillerAplicarPagosController@getInscripcionesEducacionContinua');

/* ------------------------ Módulo de asignar grupos Chetumal------------------------ */
Route::get('/bachiller_asignar_grupo_chetumal', 'Bachiller\BachillerAsignarGrupoChetumalController@index')->name('bachiller.bachiller_asignar_grupo_chetumal.index');
Route::get('/bachiller_asignar_grupo_chetumal/list', 'Bachiller\BachillerAsignarGrupoChetumalController@list')->name('bachiller.bachiller_asignar_grupo_chetumal.list');
Route::get('/bachiller_asignar_grupo_chetumal/create', 'Bachiller\BachillerAsignarGrupoChetumalController@create')->name('bachiller.bachiller_asignar_grupo_chetumal.create');
Route::post('/bachiller_asignar_grupo_chetumal', 'Bachiller\BachillerAsignarGrupoChetumalController@store')->name('bachiller.bachiller_asignar_grupo_chetumal.store');
Route::get('bachiller_asignar_grupo_chetumal/{id}/edit', 'Bachiller\BachillerAsignarGrupoChetumalController@edit')->name('bachiller.bachiller_asignar_grupo_chetumal.edit');
Route::put('bachiller_asignar_grupo_chetumal/{id}', 'Bachiller\BachillerAsignarGrupoChetumalController@update')->name('bachiller.bachiller_asignar_grupo_chetumal.update');
Route::get('bachiller_asignar_grupo_chetumal/{id}', 'Bachiller\BachillerAsignarGrupoChetumalController@show')->name('bachiller.bachiller_asignar_grupo_chetumal.show');
Route::delete('bachiller_asignar_grupo_chetumal/{id}', 'Bachiller\BachillerAsignarGrupoChetumalController@destroy')->name('bachiller.bachiller_asignar_grupo_chetumal.destroy');
Route::get('bachiller_asignar_grupo_chetumal/cambiar_grupo/{inscritoId}', 'Bachiller\BachillerAsignarGrupoChetumalController@cambiarGrupo')->name('bachiller.bachiller_asignar_grupo_chetumal.cambiar_grupo');
Route::post('bachiller_asignar_grupo_chetumal/postCambiarGrupo', 'Bachiller\BachillerAsignarGrupoChetumalController@postCambiarGrupo')->name('bachiller.bachiller_asignar_grupo_chetumal.postCambiarGrupo');
// Route::get('api/grupos/{curso_id}','Bachiller\BachillerAsignarGrupoChetumalController@getGrupos');
Route::get('bachiller_asignar_grupo_chetumal/obtener_grupos/{curso_id}','Bachiller\BachillerAsignarGrupoChetumalController@ObtenerGrupos');
Route::get('bachiller_asignar_grupo_chetumal/getDepartamentos/{id}','Bachiller\BachillerAsignarGrupoChetumalController@getDepartamentos');
Route::get('bachiller_asignar_grupo_chetumal/getEscuelas/{id}/{otro?}','Bachiller\BachillerAsignarGrupoChetumalController@getEscuelas');


/* --------------------------- Módulo de inscritos -------------------------- */
Route::get('bachiller_inscritos/list/{grupo_id}','Bachiller\BachillerInscritosYucatanController@list')->name('api/bachiller_inscritos/{grupo_id}');
Route::get('bachiller_inscritos/{grupo_id}', 'Bachiller\BachillerInscritosYucatanController@index')->name('bachiller.bachiller_inscritos/{grupo_id}');
// Route::get('bachiller_inscritos/calificaciones/primerreporte/{inscrito_id}/{persona_id}/{grado}/{grupo}', 'Bachiller\BachillerCalificacionesController@reporteTrimestre');
Route::get('bachiller_inscritos/pase_lista/{grupo_id}', 'Bachiller\BachillerInscritosYucatanController@pase_de_lista')->name('bachiller.bachiller_inscritos/pase_lista/{grupo_id}');
Route::get('bachiller_inscritos/obtenerAlumnosPaseLista/{grupo_id}/{fecha}', 'Bachiller\BachillerInscritosYucatanController@obtenerAlumnosPaseLista');
Route::post('bachiller_inscritos/asistencia_alumnos/', 'Bachiller\BachillerInscritosYucatanController@asistencia_alumnos')->name('bachiller.bachiller_inscritos.asistencia_alumnos');
Route::post('bachiller_inscritos/pase_lista/', 'Bachiller\BachillerInscritosYucatanController@guardarPaseLista')->name('bachiller.bachiller_inscritos.guardarPaseLista');

/* --------------------------- Módulo de inscritos Chetumal-------------------------- */
Route::get('bachiller_inscritos_chetumal/list/{grupo_id}','Bachiller\BachillerInscritosChetumalController@list')->name('api/bachiller_inscritos_chetumal/{grupo_id}');
Route::get('bachiller_inscritos_chetumal/{grupo_id}', 'Bachiller\BachillerInscritosChetumalController@index')->name('bachiller.bachiller_inscritos_chetumal/{grupo_id}');
Route::get('bachiller_inscritos_chetumal/calificaciones/primerreporte/{inscrito_id}/{persona_id}/{grado}/{grupo}', 'Bachiller\BachillerCalificacionesChetumalController@reporteTrimestre');
Route::get('bachiller_inscritos_chetumal/pase_lista/{grupo_id}', 'Bachiller\BachillerInscritosChetumalController@pase_de_lista')->name('bachiller.bachiller_inscritos_chetumal/pase_lista/{grupo_id}');
Route::get('bachiller_inscritos_chetumal/obtenerAlumnosPaseLista/{grupo_id}/{fecha}', 'Bachiller\BachillerInscritosChetumalController@obtenerAlumnosPaseLista');
Route::post('bachiller_inscritos_chetumal/asistencia_alumnos/', 'Bachiller\BachillerInscritosChetumalController@asistencia_alumnos')->name('bachiller.bachiller_inscritos_chetumal.asistencia_alumnos');
Route::post('bachiller_inscritos_chetumal/pase_lista/', 'Bachiller\BachillerInscritosChetumalController@guardarPaseLista')->name('bachiller.bachiller_inscritos_chetumal.guardarPaseLista');

/* ------------------------ Módulo de calificaciones ------------------------ */
Route::resource('bachiller_calificacion_chetumal','Bachiller\BachillerCalificacionesChetumalController');
Route::get('bachiller_calificacion_chetumal/{inscrito_id}/{grupo_id}', 'Bachiller\BachillerCalificacionesChetumalController@index');
Route::get('bachiller_calificacion_chetumal/create', 'Bachiller\BachillerCalificacionesChetumalController@create')->name('bachiller.bachiller_calificacion_chetumal.create');
Route::get('bachiller_calificacion_chetumal/api/getAlumnos/{id}','Bachiller\BachillerCalificacionesChetumalController@getAlumnos');
Route::get('bachiller_calificacion_chetumal/api/getGrupos/{id}','Bachiller\BachillerCalificacionesChetumalController@getGrupos');
Route::get('bachiller_calificacion_chetumal/api/getMaterias2/{id}','Bachiller\BachillerCalificacionesChetumalController@getMaterias2');
Route::get('bachiller_calificacion_chetumal/grupo/{id}/edit','Bachiller\BachillerCalificacionesChetumalController@edit_calificacion')->name('bachiller.bachiller_grupo.calificaciones.edit_calificacion');
Route::get('bachiller_calificacion_chetumal/getCalificacionesAlumnos/{id}/{grupoId}','Bachiller\BachillerCalificacionesChetumalController@getCalificacionesAlumnos');
Route::post('bachiller_calificacion_chetumal/guardarCalificacion', 'Bachiller\BachillerCalificacionesChetumalController@guardarCalificacion')->name('bachiller.bachiller_calificacion_chetumal.guardarCalificacion');
Route::put('bachiller_calificacion_chetumal/calificaciones/{id}','Bachiller\BachillerCalificacionesChetumalController@update_calificacion')->name('bachiller.bachiller_calificacion_chetumal.calificaciones.update_calificacion');
Route::get('bachiller/boletaAlumnoCurso/{curso_id}','Bachiller\BachillerCalificacionesChetumalController@boletadesdecurso')->name('bachiller.boletadesdecurso');


//Cargar Materias a inscrito
Route::get('bachiller_materias_inscrito', 'Bachiller\BachillerMateriasInscritoDController@index')->name('bachiller.bachiller_materias_inscrito.index');
Route::get('bachiller_materias_inscrito/ultimo_curso/{alumno_id}', 'Bachiller\BachillerMateriasInscritoDController@ultimoCurso');
Route::post('bachiller_materias_inscrito/api/getMultipleAlumnosByFilter','Bachiller\BachillerMateriasInscritoDController@getMultipleAlumnosByFilter');
Route::post('bachiller_materias_inscrito', 'Bachiller\BachillerMateriasInscritoDController@store')->name('bachiller.bachiller_materias_inscrito.store');

// CGT Materias
Route::get('bachiller_cgt_materias','Bachiller\BachillerCGTMateriasController@index')->name('bachiller.bachiller_cgt_materias.index');
Route::get('bachiller_cgt_materias/obtenerMaterias/{periodo_id}/{programa_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerCGTMateriasController@obtenerMaterias');
Route::post('bachiller_cgt_materias','Bachiller\BachillerCGTMateriasController@store')->name('bachiller.bachiller_cgt_materias.store');


//Asignar docente CGT
Route::get('bachiller_asignar_docente','Bachiller\BachillerAsignarDocenteCGTController@index')->name('bachiller.bachiller_asignar_docente.index');
Route::get('bachiller_asignar_docente/obtenerGrupos/get/{ubicacion}/{periodo_id}/{plan_id}/{cgt_id}', 'Bachiller\BachillerAsignarDocenteCGTController@obtenerGrupos');
Route::post('bachiller_asignar_docente','Bachiller\BachillerAsignarDocenteCGTController@store')->name('bachiller.bachiller_asignar_docente.store');

// Empleados
Route::get('/bachiller_empleado', 'Bachiller\BachillerEmpleadoController@index')->name('bachiller.bachiller_empleado.index');
Route::get('/bachiller_empleado/create', 'Bachiller\BachillerEmpleadoController@create')->name('bachiller.bachiller_empleado.create');
Route::get('/bachiller_empleado/list', 'Bachiller\BachillerEmpleadoController@list')->name('bachiller.bachiller_empleado.list');
Route::get('bachiller_empleado/verificar_persona', 'Bachiller\BachillerEmpleadoController@verificarExistenciaPersona');
Route::get('bachiller_empleado/{id}','Bachiller\BachillerEmpleadoController@show')->name('bachiller.bachiller_empleado.show');
Route::get('bachiller_empleado/{id}/edit','Bachiller\BachillerEmpleadoController@edit')->name('bachiller.bachiller_empleado.edit');
Route::get('bachiller_empleado/verificar_delete/{empleado_id}', 'Bachiller\BachillerEmpleadoController@puedeSerEliminado')->name('bachiller.bachiller_empleado/verificar_delete/{empleado_id}');

Route::put('bachiller_empleado/{id}','Bachiller\BachillerEmpleadoController@update')->name('bachiller.bachiller_empleado.update');
Route::post('bachiller_empleado/reactivar_empleado/{empleado_id}','Bachiller\BachillerEmpleadoController@reactivarEmpleado')->name('bachiller.bachiller_empleado/reactivar_empleado/{empleado_id}');
Route::post('bachiller_empleado/registrar_alumno/{alumno_id}', 'Bachiller\BachillerEmpleadoController@alumno_crearEmpleado')->name('bachiller.bachiller_empleado/registrar_alumno/{alumno_id}');
Route::post('bachiller_empleado','Bachiller\BachillerEmpleadoController@store')->name('bachiller.bachiller_empleado.store');
Route::post('bachiller_empleado/darBaja/{empleado_id}', 'Bachiller\BachillerEmpleadoController@darDeBaja')->name('bachiller.bachiller_empleado/darBaja/{empleado_id}');
Route::delete('bachiller_empleado/{id}','Bachiller\BachillerEmpleadoController@destroy')->name('bachiller.bachiller_empleado.destroy');


// Cambiar contraseña docente
Route::get('bachiller_cambiar_contrasenia', 'Bachiller\BachillerCambiarContraseniaController@index')->name('bachiller.bachiller_cambiar_contrasenia.index');
Route::get('bachiller_cambiar_contrasenia/list', 'Bachiller\BachillerCambiarContraseniaController@list');
Route::get('bachiller_cambiar_contrasenia/getEmpleadoCorreo/{id}', 'Bachiller\BachillerCambiarContraseniaController@getEmpleadoCorreo');
Route::get('bachiller_cambiar_contrasenia/create', 'Bachiller\BachillerCambiarContraseniaController@create')->name('bachiller.bachiller_cambiar_contrasenia.create');
Route::get('bachiller_cambiar_contrasenia/{id}/edit', 'Bachiller\BachillerCambiarContraseniaController@edit');
Route::get('bachiller_cambiar_contrasenia/{id}', 'Bachiller\BachillerCambiarContraseniaController@show');
Route::post('bachiller_cambiar_contrasenia', 'Bachiller\BachillerCambiarContraseniaController@store')->name('bachiller.bachiller_cambiar_contrasenia.store');
Route::put('bachiller_cambiar_contrasenia/{id}', 'Bachiller\BachillerCambiarContraseniaController@update')->name('bachiller.bachiller_cambiar_contrasenia.update');


/* -------------------------- Módulo de calendario -------------------------- */
Route::resource('bachiller_calendario', 'Bachiller\BachillerAgendaController');
Route::get('/bachiller_calendario', 'Bachiller\BachillerAgendaController@index')->name('bachiller.bachiller_calendario.index');
Route::get('/bachiller_calendario/show', 'Bachiller\BachillerAgendaController@show')->name('bachiller.bachiller_calendario.show');


//Fecha publicacion Docente
Route::get('bachiller_fecha_publicacion_calificacion_docente','Bachiller\BachillerFechaPublicacionControllerDocente@index')->name('bachiller.bachiller_fecha_publicacion_calificacion_docente.index');
Route::get('bachiller_fecha_publicacion_calificacion_docente/list','Bachiller\BachillerFechaPublicacionControllerDocente@list');
Route::get('bachiller_fecha_publicacion_calificacion_docente/create','Bachiller\BachillerFechaPublicacionControllerDocente@create')->name('bachiller.bachiller_fecha_publicacion_calificacion_docente.create');
Route::get('bachiller_fecha_publicacion_calificacion_docente/{id}/edit','Bachiller\BachillerFechaPublicacionControllerDocente@edit')->name('bachiller.bachiller_fecha_publicacion_calificacion_docente.edit');
Route::get('bachiller_fecha_publicacion_calificacion_docente/getMesEvaluaciones/{departamento_id}','Bachiller\BachillerFechaPublicacionControllerDocente@getMesEvaluaciones');
Route::post('bachiller_fecha_publicacion_calificacion_docente','Bachiller\BachillerFechaPublicacionControllerDocente@store')->name('bachiller.bachiller_fecha_publicacion_calificacion_docente.store');
Route::put('bachiller_fecha_publicacion_calificacion_docente/{id}','Bachiller\BachillerFechaPublicacionControllerDocente@update')->name('bachiller.bachiller_fecha_publicacion_calificacion_docente.update');

// Fecha publicacion Alumno
Route::get('bachiller_fecha_publicacion_calificacion_alumno','Bachiller\BachillerFechaPublicacionControllerAlumno@index')->name('bachiller.bachiller_fecha_publicacion_calificacion_alumno.index');
Route::get('bachiller_fecha_publicacion_calificacion_alumno/list','Bachiller\BachillerFechaPublicacionControllerAlumno@list');
Route::get('bachiller_fecha_publicacion_calificacion_alumno/create','Bachiller\BachillerFechaPublicacionControllerAlumno@create')->name('bachiller.bachiller_fecha_publicacion_calificacion_alumno.create');
Route::get('bachiller_fecha_publicacion_calificacion_alumno/{id}/edit','Bachiller\BachillerFechaPublicacionControllerAlumno@edit')->name('bachiller.bachiller_fecha_publicacion_calificacion_alumno.edit');
Route::post('bachiller_fecha_publicacion_calificacion_alumno','Bachiller\BachillerFechaPublicacionControllerAlumno@store')->name('bachiller.bachiller_fecha_publicacion_calificacion_alumno.store');
Route::put('bachiller_fecha_publicacion_calificacion_alumno/{id}','Bachiller\BachillerFechaPublicacionControllerAlumno@update')->name('bachiller.bachiller_fecha_publicacion_calificacion_alumno.update');

// observaciones boleta
Route::get('bachiller_obs_boleta','Bachiller\BachillerObservacionesBoletaController@index')->name('bachiller.bachiller_obs_boleta.index');
Route::get('bachiller_obs_boleta/obtenerObsBoleta/{plan_id}/{periodo_id}/{cgt_id}/{mes}','Bachiller\BachillerObservacionesBoletaController@obtenerObsBoleta');
Route::post('bachiller_obs_boleta/','Bachiller\BachillerObservacionesBoletaController@guardar')->name('bachiller.bachiller_obs_boleta.guardar');


// Horarios administrativos
Route::get('bachiller_horarios_administrativos','Bachiller\BachillerHorariosAdministrativosController@index')->name('bachiller.bachiller_horarios_administrativos');
Route::get('api/bachiller_horarios_administrativos','Bachiller\BachillerHorariosAdministrativosController@list')->name('api/bachiller_horarios_administrativos');
Route::get('bachiller_horarios_administrativos/{claveMaestro}/{periodoId}/calendario','Bachiller\BachillerHorariosAdministrativosController@horariosAdministrativos');
Route::get('api/bachiller_horarios_administrativos/horario/{claveMaestro}/{periodoId}','Bachiller\BachillerHorariosAdministrativosController@listHorario');
Route::get('api/bachiller_horarios_administrativos/horario_gpo/{claveMaestro}/{periodoId}','Bachiller\BachillerHorariosAdministrativosController@listHorarioGpo');
Route::get('bachiller_horarios_administrativos/eliminarHorario/{id}','Bachiller\BachillerHorariosAdministrativosController@eliminarHorario');
Route::post('bachiller_horarios_administrativos/agregarHorarios','Bachiller\BachillerHorariosAdministrativosController@agregarHorarios');


// Horarios administrativos
Route::get('bachiller_horarios_administrativos_chetumal','Bachiller\BachillerHorariosAdministrativosChetumalController@index')->name('bachiller.bachiller_horarios_administrativos_chetumal');
Route::get('api/bachiller_horarios_administrativos_chetumal','Bachiller\BachillerHorariosAdministrativosChetumalController@list')->name('api/bachiller_horarios_administrativos_chetumal');
Route::get('bachiller_horarios_administrativos_chetumal/{claveMaestro}/{periodoId}/calendario','Bachiller\BachillerHorariosAdministrativosChetumalController@horariosAdministrativos');
Route::get('api/bachiller_horarios_administrativos_chetumal/horario/{claveMaestro}/{periodoId}','Bachiller\BachillerHorariosAdministrativosChetumalController@listHorario');
Route::get('api/bachiller_horarios_administrativos_chetumal/horario_gpo/{claveMaestro}/{periodoId}','Bachiller\BachillerHorariosAdministrativosChetumalController@listHorarioGpo');
Route::get('bachiller_horarios_administrativos_chetumal/eliminarHorario/{id}','Bachiller\BachillerHorariosAdministrativosChetumalController@eliminarHorario');
Route::post('bachiller_horarios_administrativos_chetumal/agregarHorarios','Bachiller\BachillerHorariosAdministrativosChetumalController@agregarHorarios');

// Preinscripcion automatica
Route::get('bachiller_preinscripcion_automatica','Bachiller\BachillerPreinscripcionAutomaticaController@create')->name('bachiller.bachiller_preinscripcion_automatica.create');

//Solicitud de ExtraOrdinarios
Route::get('recibo/bachiller_solicitud/{id}','Bachiller\BachillerExtraordinarioController@solicitudRecibo')->name('recibo.bachiller_solicitud');
// Extraordinario Route
Route::get('bachiller_recuperativos/crearReporte', 'Bachiller\BachillerExtraordinarioController@crearReporte');
Route::post('bachiller_recuperativos/generarReporte', 'Bachiller\BachillerExtraordinarioController@generarReporte')->name('bachiller.bachiller_recuperativos.generarReporte');
Route::resource('bachiller_recuperativos','Bachiller\BachillerExtraordinarioController');
Route::get('api/bachiller_recuperativos','Bachiller\BachillerExtraordinarioController@list')->name('api/bachiller_recuperativos');
Route::get('api/bachiller_recuperativos/{extraordinario_id}','Bachiller\BachillerExtraordinarioController@getExtraordinario');
Route::get('api/solicitud/bachiller_recuperativos','Bachiller\BachillerExtraordinarioController@list_solicitudes')->name('api.solicitud.bachiller_recuperativos');
Route::get('api/bachiller_recuperativos/getAlumnosByFolioExtraordinario/{extraordinario_id}',
  'Bachiller\BachillerExtraordinarioController@getAlumnosByFolioExtraordinario')->name('api/bachiller_recuperativos/getAlumnosByFolioExtraordinario/{extraordinario_id}');
Route::get('api/bachiller_recuperativos/validarAlumnoPresentaExtra/{folioExt}/{alumno}',
  'Bachiller\BachillerExtraordinarioController@validarAlumnoPresentaExtra')->name('api/bachiller_recuperativos/validarAlumnoPresentaExtra');
Route::get('bachiller_calificacion/agregarextra/{extraordinario_id}','Bachiller\BachillerExtraordinarioController@agregarExtra');
Route::get('bachiller_recuperativos/{id}/edit_docente', 'Bachiller\BachillerExtraordinarioController@editar_docente');
Route::get('solicitudes/bachiller_recuperativos','Bachiller\BachillerExtraordinarioController@solicitudes');
Route::get('create/bachiller_solicitud','Bachiller\BachillerExtraordinarioController@solicitudCreate');
Route::post('store/bachiller_solicitud','Bachiller\BachillerExtraordinarioController@solicitudStore')->name('store.bachiller_solicitud');
Route::get('edit/bachiller_solicitud/{id}','Bachiller\BachillerExtraordinarioController@solicitudEdit')->name('edit.bachiller_solicitud');
Route::get('bachiller_solicitud/pagos/ficha_general','Bachiller\BachillerExtraordinarioController@fecha_general_index')->name('bachiller.bachiller_recuperativos.fecha_general_index');
Route::put('update/bachiller_solicitud/{id}','Bachiller\BachillerExtraordinarioController@solicitudUpdate')->name('update.bachiller_solicitud');
Route::put('bachiller_recuperativos/update_docente/{id}','Bachiller\BachillerExtraordinarioController@update_docente')->name('bachiller.bachiller_recuperativos.update_docente');
Route::get('show/bachiller_solicitud/{id}','Bachiller\BachillerExtraordinarioController@solicitudShow')->name('show.bachiller_solicitud');
Route::get('cancelar/bachiller_solicitud/{id}','Bachiller\BachillerExtraordinarioController@solicitudCancelar')->name('cancelar.bachiller_solicitud');
Route::post('bachiller_recuperativos/actaexamen/{extraordinario_id}','Bachiller\BachillerExtraordinarioController@actaExamen');
Route::get('api/bachiller_recuperativos/validarAlumno/{aluClave}','Bachiller\BachillerExtraordinarioController@validarAlumno')->name('api/bachiller_recuperativos/validarAlumno');
Route::get('bachiller_recuperativos/getDebeRecuperativos/{aluClave}/{perAnioPago}/{perNumero}','Bachiller\BachillerExtraordinarioController@getDebeRecuperativos');
Route::post('bachiller_recuperativos/pagos/ficha_general/store','Bachiller\BachillerFichaGeneralExtraordinarioController@store')->name('bachiller.bachiller_recuperativos.storePagoExtra');

//Ruta para imprimir acta de examen extraordinario en el datatable
// Route::post('extraordinario/actaexamen/{extraordinario_id}','Bachiller\BachillerExtraordinarioController@actaExamen');
Route::post('bachiller_recuperativos/extraStore','Bachiller\BachillerExtraordinarioController@extraStore')->name('bachiller.bachiller_recuperativos.extraStore');



//Solicitud de curso recuperativo
Route::get('recibo/bachiller_curso_recuperativo/{id}','Bachiller\BachillerCursoRecuperativoController@solicitudRecibo')->name('recibo.bachiller_curso_recuperativo');
// curso recuperativo Route
Route::resource('bachiller_curso_recuperativo','Bachiller\BachillerCursoRecuperativoController');
Route::get('api/bachiller_curso_recuperativo','Bachiller\BachillerCursoRecuperativoController@list')->name('api/bachiller_curso_recuperativo');
Route::get('api/bachiller_curso_recuperativo/{extraordinario_id}','Bachiller\BachillerCursoRecuperativoController@getExtraordinario');
Route::get('api/solicitud/bachiller_curso_recuperativo','Bachiller\BachillerCursoRecuperativoController@list_solicitudes')->name('api.solicitud.bachiller_curso_recuperativo');
Route::get('api/bachiller_curso_recuperativo/getAlumnosByFolioExtraordinario/{extraordinario_id}',
  'Bachiller\BachillerCursoRecuperativoController@getAlumnosByFolioExtraordinario')->name('api/bachiller_curso_recuperativo/getAlumnosByFolioExtraordinario/{extraordinario_id}');
Route::get('api/bachiller_curso_recuperativo/validarAlumnoPresentaExtra/{folioExt}/{alumno}',
  'Bachiller\BachillerCursoRecuperativoController@validarAlumnoPresentaExtra')->name('api/bachiller_curso_recuperativo/validarAlumnoPresentaExtra');
// Route::get('bachiller_calificacion/agregarextra/{extraordinario_id}','Bachiller\BachillerCursoRecuperativoController@agregarExtra');
Route::get('solicitudes/bachiller_curso_recuperativo','Bachiller\BachillerCursoRecuperativoController@solicitudes');
Route::get('create/bachiller_curso_recuperativo','Bachiller\BachillerCursoRecuperativoController@solicitudCreate');
Route::post('store/bachiller_curso_recuperativo','Bachiller\BachillerCursoRecuperativoController@solicitudStore')->name('store.bachiller_curso_recuperativo');
Route::get('edit/bachiller_curso_recuperativo/{id}','Bachiller\BachillerCursoRecuperativoController@solicitudEdit')->name('edit.bachiller_curso_recuperativo');
// Route::put('update/bachiller_solicitud/{id}','Bachiller\BachillerCursoRecuperativoController@solicitudUpdate')->name('update.bachiller_curso_recuperativo');
Route::get('show/bachiller_curso_recuperativo/{id}','Bachiller\BachillerCursoRecuperativoController@solicitudShow')->name('show.bachiller_curso_recuperativo');
Route::get('cancelar/bachiller_curso_recuperativo/{id}','Bachiller\BachillerCursoRecuperativoController@solicitudCancelar')->name('cancelar.bachiller_curso_recuperativo');
Route::post('bachiller_curso_recuperativo/actaexamen/{extraordinario_id}','Bachiller\BachillerCursoRecuperativoController@actaExamen');
Route::get('api/bachiller_curso_recuperativo/validarAlumno/{aluClave}','Bachiller\BachillerCursoRecuperativoController@validarAlumno')->name('api/bachiller_curso_recuperativo/validarAlumno');
Route::post('bachiller_curso_recuperativo/extraStore','Bachiller\BachillerCursoRecuperativoController@extraStore')->name('bachiller.bachiller_curso_recuperativo.extraStore');

// Evidencias
Route::get('bachiller_evidencias/{bachiller_grupo_id}','Bachiller\BachillerEvidenciasController@index')->name('bachiller.bachiller_evidencias.index');
Route::get('bachiller_evidencias/list/{periodo_id}/{bachiller_materia_id}/{bachiller_materia_acd_id}','Bachiller\BachillerEvidenciasController@list');
Route::get('bachiller_evidencias/create/{bachiller_grupo_id}','Bachiller\BachillerEvidenciasController@create')->name('bachiller.bachiller_evidencias.create');
Route::get('bachiller_evidencias/getMateriasEvidencias/{plan_id}/{programa_id}/{matSemestre}','Bachiller\BachillerEvidenciasController@getMateriasEvidencias');
Route::get('bachiller_evidencias/sinACDgetMateriasEvidenciasPeriodo/{periodo_id}/{bachiller_materia_id}/{matSemestre}','Bachiller\BachillerEvidenciasController@getMateriasEvidenciasPeriodo');

Route::get('bachiller_evidencias/getMateriasEvidenciasPeriodoACD/{periodo_id}/{bachiller_materia_id}/{matSemestre}/{bachiller_materia_acd_id}','Bachiller\BachillerEvidenciasController@getMateriasEvidenciasPeriodoACD');
Route::get('bachiller_evidencias/{id}/{grupo_id}/edit','Bachiller\BachillerEvidenciasController@edit')->name('bachiller.bachiller_evidencias.edit');
Route::get('bachiller_evidencias/ver/{id}/{grupo_id}','Bachiller\BachillerEvidenciasController@show')->name('bachiller.bachiller_evidencias.show');
Route::post('bachiller_evidencias','Bachiller\BachillerEvidenciasController@store')->name('bachiller.bachiller_evidencias.store');
Route::put('bachiller_evidencias/{id}','Bachiller\BachillerEvidenciasController@update')->name('bachiller.bachiller_evidencias.update');
Route::delete('bachiller_evidencias/{id}','Bachiller\BachillerEvidenciasController@destroy')->name('bachiller.bachiller_evidencias.destroy');

// Evidencias inscritos
Route::get('bachiller_evidencias_inscritos/{grupo_id}/{periodo_id}/{materia_id}/{materia_acd_id}/captura_materia_complementaria','Bachiller\BachillerEvidenciasInscritosController@capturaEvidencia')->name('bachiller.captura_materia_complementaria');
Route::get('bachiller_evidencias_inscritos/{grupo_id}/{periodo_id}/{materia_id}/captura','Bachiller\BachillerEvidenciasInscritosController@capturaEvidencia')->name('bachiller.captura_materia');
Route::get('bachiller_evidencias_inscritos/capturas_realizadas/{grupo_id}/{evidencia_id}','Bachiller\BachillerEvidenciasInscritosController@getMateriasEvidencias');
Route::post('bachiller_evidencias_inscritos','Bachiller\BachillerEvidenciasInscritosController@store')->name('bachiller.bachiller_evidencias_inscritos.store');


//Fechas de regularización
Route::get('bachiller_fechas_regularizacion','Bachiller\BachillerFechasRegularizacionController@index')->name('bachiller.bachiller_fechas_regularizacion.index');
Route::get('bachiller_fechas_regularizacion/list','Bachiller\BachillerFechasRegularizacionController@list')->name('bachiller.bachiller_fechas_regularizacion.list');
Route::get('bachiller_fechas_regularizacion/create','Bachiller\BachillerFechasRegularizacionController@create')->name('bachiller.bachiller_fechas_regularizacion.create');
Route::get('bachiller_fechas_regularizacion/{id}/edit','Bachiller\BachillerFechasRegularizacionController@edit')->name('bachiller.bachiller_fechas_regularizacion.edit');
Route::get('bachiller_fechas_regularizacion/{id}','Bachiller\BachillerFechasRegularizacionController@show')->name('bachiller.bachiller_fechas_regularizacion.show');
Route::post('bachiller_fechas_regularizacion','Bachiller\BachillerFechasRegularizacionController@store')->name('bachiller.bachiller_fechas_regularizacion.store');
Route::put('bachiller_fechas_regularizacion/{id}','Bachiller\BachillerFechasRegularizacionController@update')->name('bachiller.bachiller_fechas_regularizacion.update');
Route::delete('bachiller_fechas_regularizacion/{id}','Bachiller\BachillerFechasRegularizacionController@destroy')->name('bachiller.bachiller_fechas_regularizacion.destroy');

//Fechas de calendario de axamen
Route::get('bachiller_calendario_examen','Bachiller\BachillerFechasCalendariaExamenController@index')->name('bachiller.bachiller_calendario_examen.index');
Route::get('bachiller_calendario_examen/list','Bachiller\BachillerFechasCalendariaExamenController@list')->name('bachiller.bachiller_calendario_examen.list');
Route::get('bachiller_calendario_examen/create','Bachiller\BachillerFechasCalendariaExamenController@create')->name('bachiller.bachiller_calendario_examen.create');
Route::get('bachiller_calendario_examen/{id}/edit','Bachiller\BachillerFechasCalendariaExamenController@edit')->name('bachiller.bachiller_calendario_examen.edit');
Route::get('bachiller_calendario_examen/{id}','Bachiller\BachillerFechasCalendariaExamenController@show')->name('bachiller.bachiller_calendario_examen.show');
Route::post('bachiller_calendario_examen','Bachiller\BachillerFechasCalendariaExamenController@store')->name('bachiller.bachiller_calendario_examen.store');
Route::put('bachiller_calendario_examen/{id}','Bachiller\BachillerFechasCalendariaExamenController@update')->name('bachiller.bachiller_calendario_examen.update');
Route::delete('bachiller_calendario_examen/{id}','Bachiller\BachillerFechasCalendariaExamenController@destroy')->name('bachiller.bachiller_calendario_examen.destroy');



/* -------------------------------------------------------------------------- */
/*                              Rutas de Reportes                             */
/* -------------------------------------------------------------------------- */

Route::get('bachiller_calificacion_evidencias/{grupo_id}','Bachiller\Reportes\BachillerCalificacionEvidenciaController@imprimir_reporte')->name('bachiller.bachiller_calificacion_evidencias.imprimir_reporte');
Route::get('bachiller_faltas_evidencias/{grupo_id}','Bachiller\Reportes\BachillerFaltasEvidenciaController@imprimir_reporte')->name('bachiller.bachiller_faltas_evidencias.imprimir_reporte');

//Reporte de Inscritos y Preinscritos
Route::get('bachiller_reporte/bachiller_inscrito_preinscrito', 'Bachiller\Reportes\BachillerInscritosPreinscritosController@reporte')->name('bachiller_inscrito_preinscrito.reporte');
Route::post('bachiller_reporte/bachiller_preinscrito/imprimir', 'Bachiller\Reportes\BachillerInscritosPreinscritosController@imprimir')->name('bachiller_inscrito_preinscrito.imprimir');

// Relacion de deudores
Route::get('reporte/bachiller_relacion_deudores', 'Bachiller\Reportes\BachillerRelDeudoresController@reporte')->name('bachiller_relacion_deudores.reporte');
Route::post('reporte/bachiller_relacion_deudores/imprimir', 'Bachiller\Reportes\BachillerRelDeudoresController@imprimir')->name('bachiller_relacion_deudores.imprimir');

// Relacion de deuda individual de un alumno
Route::get('reporte/bachiller_relacion_deudas', 'Bachiller\Reportes\BachillerRelDeudasController@reporte')->name('bachiller_relacion_deudas.reporte');
Route::post('reporte/bachiller_relacion_deudas/imprimir', 'Bachiller\Reportes\BachillerRelDeudasController@imprimir')->name('bachiller_relacion_deudas.imprimir');

// Resumen de inscritos
Route::get('reporte/bachiller_resumen_inscritos', 'Bachiller\Reportes\BachillerResumenInscritosController@reporte')->name('bachiller.bachiller_resumen_inscritos.reporte');
Route::get('reporte/bachiller_resumen_inscritos/imprimir', 'Bachiller\Reportes\BachillerResumenInscritosController@imprimir')->name('bachiller.bachiller_resumen_inscritos.imprimir');
Route::get('reporte/bachiller_resumen_inscritos/exportarExcel', 'Bachiller\Reportes\BachillerResumenInscritosController@exportarExcel');


// crear lista de asistencia de alumnos desde grupos
Route::get('bachiller_inscritos_yuc/lista_de_asistencia/grupo/{grupo_id}', 'Bachiller\Reportes\BachillerListaDeAsistenciaController@imprimirListaAsistenciaYuc');
Route::get('bachiller_inscritos_che/lista_de_asistencia/grupo/{grupo_id}', 'Bachiller\Reportes\BachillerListaDeAsistenciaController@imprimirListaAsistenciaChe');

// // Controller para generar reporte de expediente de alumnos
// Route::get('bachiller_reporte/expediente_alumnos', 'Bachiller\Reportes\BachillerExpedienteAlumnosController@index')->name('bachiller_reporte.expediente_alumnos.index');
// Route::post('bachiller_reporte/expediente_alumnos/imprimir', 'Bachiller\Reportes\BachillerExpedienteAlumnosController@imprimirExpediente')->name('bachiller_reporte.expediente_alumnos.imprimir');

//Controller para generar reporte de alumnos becados
Route::get('bachiller_reporte/alumnos_becados', 'Bachiller\Reportes\BachillerAlumnosBecadosController@reporte')->name('bachiller_reporte.bachiller_alumnos_becados.reporte');
Route::post('bachiller_reporte/alumnos_becados/imprimir', 'Bachiller\Reportes\BachillerAlumnosBecadosController@imprimir')->name('bachiller_reporte.bachiller_alumnos_becados.imprimir');

//Relación de bajas por periodo.
Route::get('reporte/bachiller_relacion_bajas_periodo','Bachiller\Reportes\BachillerRelacionBajasPeriodoController@reporte')->name('bachiller.bachiller_relacion_bajas_periodo.reporte');
Route::post('reporte/bachiller_relacion_bajas_periodo/imprimir', 'Bachiller\Reportes\BachillerRelacionBajasPeriodoController@imprimir')->name('bachiller.bachiller_relacion_bajas_periodo.imprimir');


//horario de clases
Route::get('reporte/bachiller_horario_por_grupo','Bachiller\Reportes\BachillerHorarioPorGrupoController@reporte')->name('bachiller.bachiller_horario_por_grupo.reporte');
Route::post('reporte/bachiller_horario_por_grupo/imprimir','Bachiller\Reportes\BachillerHorarioPorGrupoController@imprimir')->name('bachiller.bachiller_horario_por_grupo.imprimir');

//Grupos por Semestre
Route::get('reporte/bachiller_grupo_semestre', 'Bachiller\Reportes\BachillerGrupoSemestreController@reporte')->name('bachiller.bachiller_grupo_semestre.reporte');
Route::post('reporte/bachiller_grupo_semestre/imprimir', 'Bachiller\Reportes\BachillerGrupoSemestreController@imprimir')->name('bachiller.bachiller_grupo_semestre.imprimir');

// Controller para generar constancias
Route::get('bachiller_reporte/carta_conducta/imprimir/{id_curso}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirCartaConducta');
Route::get('bachiller_reporte/constancia_estudio/imprimir/{id_curso}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirConstanciaEstudio');
Route::get('bachiller_reporte/constancia_no_adeudo/imprimir/{id_curso}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirConstanciaNoAdeudo');
Route::get('bachiller_reporte/constancia_de_cupo/imprimir/{id_curso}/{tipoContancia}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirConstanciaCupo');
Route::get('bachiller_reporte/constancia_de_promedio_final/imprimir/{id_curso}/{tipoContancia}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirConstanciaPromedioFinal');
Route::get('bachiller_reporte/constancia_de_artes_talleres/imprimir/{id_curso}/{tipoContancia}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirConstanciaArtesTalleres');
Route::get('bachiller_reporte/constancia_de_inscripcion_anual/imprimir/{id_curso}/{tipoContancia}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirConstanciaInscripcion');
Route::get('bachiller_reporte/constancia_de_escolaridad/imprimir/{id_curso}/{tipoContancia}', 'Bachiller\Reportes\BachillerConstanciasController@imprimirConstanciaEscolaridad');

//Relación Maestros Escuela
Route::get('bachiller_reporte/relacion_grupo_maestro', 'Bachiller\Reportes\BachillerRelacionMaestrosEscuelaController@reporte')->name('bachiller_relacion_maestros_escuela.reporte');
Route::post('bachiller_reporte/relacion_grupo_maestro/imprimir', 'Bachiller\Reportes\BachillerRelacionMaestrosEscuelaController@imprimir')->name('bachiller_relacion_maestros_escuela.imprimir');


// carga grupos maestro
Route::get('reporte/bachiller_carga_grupos_maestro', 'Bachiller\Reportes\BachillerCargaGruposMaestroController@reporte')->name('bachiller.bachiller_carga_grupos_maestro.reporte');
Route::post('reporte/bachiller_carga_grupos_maestro/imprimir', 'Bachiller\Reportes\BachillerCargaGruposMaestroController@imprimir')->name('bachiller.bachiller_carga_grupos_maestro.imprimir');



// Avance de calificaciones
Route::get('reporte/bachiller_avance_calificaciones', 'Bachiller\Reportes\BachillerAvanceCalificacionesController@reporte')->name('bachiller.bachiller_avance_calificaciones.reporte');
Route::post('reporte/bachiller_avance_calificaciones/imprimir', 'Bachiller\Reportes\BachillerAvanceCalificacionesController@imprimir')->name('bachiller.bachiller_avance_calificaciones.imprimir');

// Calificacion final
Route::get('reporte/bachiller_calificacion_final', 'Bachiller\Reportes\BachillerCalificacionFinalController@reporte')->name('bachiller.bachiller_calificacion_final.reporte');
Route::post('reporte/bachiller_calificacion_final/imprimir', 'Bachiller\Reportes\BachillerCalificacionFinalController@imprimir')->name('bachiller.bachiller_calificacion_final.imprimir');

// Controller para generar reporte de calificaciones
Route::get('bachiller_reporte/calificaciones_grupo', 'Bachiller\Reportes\BachillerCalificacionPorGrupoController@Reporte')->name('bachiller_reporte.calificaciones_grupo.reporte');
Route::post('bachiller_reporte/boleta_calificaciones/imprimir', 'Bachiller\Reportes\BachillerCalificacionPorGrupoController@imprimirCalificaciones')->name('bachiller_reporte.boleta_calificaciones.imprimir');

// Resumen de evidencias
Route::get('reporte/bachiller_resumen_evidencias', 'Bachiller\Reportes\BachillerResumenEvidenciasController@reporte')->name('bachiller.bachiller_resumen_evidencias.reporte');
Route::post('reporte/bachiller_resumen_evidencias/imprimir', 'Bachiller\Reportes\BachillerResumenEvidenciasController@imprimir')->name('bachiller.bachiller_resumen_evidencias.imprimir');

// acta de examen extraordinario
Route::get('reporte/bachiller_acta_extraordinario', 'Bachiller\Reportes\BachillerActaExtraordinarioController@reporte')->name('bachiller.bachiller_acta_extraordinario.reporte');
Route::post('reporte/bachiller_acta_extraordinario/imprimir', 'Bachiller\Reportes\BachillerActaExtraordinarioController@imprimir')->name('bachiller.bachiller_acta_extraordinario.imprimir');

// Extraordinarios - Resumen de Inscritos
Route::get('reporte/bachiller_resumen_inscritos_extraordinario', 'Bachiller\Reportes\BachillerResumenInscritosExtraordinarioController@reporte')->name('bachiller.bachiller_resumen_inscritos_extraordinario.reporte');
Route::post('reporte/bachiller_resumen_inscritos_extraordinario/imprimir', 'Bachiller\Reportes\BachillerResumenInscritosExtraordinarioController@imprimir')->name('bachiller.bachiller_resumen_inscritos_extraordinario.imprimir');

// Resumen de evidencias
Route::get('reporte/bachiller_resumen_evidencias', 'Bachiller\Reportes\BachillerResumenEvidenciasController@reporte')->name('bachiller.bachiller_resumen_evidencias.reporte');
Route::get('reporte/bachiller_detalle_evidencia/{plan_id}/{grado}', 'Bachiller\Reportes\BachillerResumenEvidenciasController@getMateriaGradoPlan');
Route::post('reporte/bachiller_resumen_evidencias/imprimir', 'Bachiller\Reportes\BachillerResumenEvidenciasController@imprimir')->name('bachiller.bachiller_resumen_evidencias.imprimir');


Route::get('bachiller_reporte/programacion_examenes', 'Bachiller\Reportes\BachillerProgramacionExamenesController@reporte')->name('bachiller.programacion_examenes.reporte');
Route::post('bachiller_reporte/programacion_examenes/imprimir', 'Bachiller\Reportes\BachillerProgramacionExamenesController@imprimir')->name('bachiller.programacion_examenes.imprimir');


Route::get('reporte/bachiller_avance_por_grupo', 'Bachiller\Reportes\BachillerAvancePorGrupoController@reporte')->name('bachiller.bachiller_avance_por_grupo.reporte');
Route::post('reporte/bachiller_avance_por_grupo/imprimir', 'Bachiller\Reportes\BachillerAvancePorGrupoController@imprimir')->name('bachiller.bachiller_avance_por_grupo.imprimir');


Route::get('bachiller_lista_de_asistencia/{grupo_id}', 'Bachiller\Reportes\BachillerListaDeAsistenciaController@imprimirListaAsistenciaYuc')->name('bachiller.bachiller_lista_de_asistencia.imprimirListaAsistenciaYuc');
Route::get('bachiller_lista_de_asistencia/excel/{grupo_id}', 'Bachiller\Reportes\BachillerListaDeAsistenciaController@imprimirListaAsistenciaYucExcel')->name('bachiller.bachiller_lista_de_asistencia.imprimirListaAsistenciaYucExcel');
