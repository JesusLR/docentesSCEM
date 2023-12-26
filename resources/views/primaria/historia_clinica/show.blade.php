@extends('layouts.dashboard')

@section('template_title')
Primaria historial clinica
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{url('primaria_historia_clinica')}}" class="breadcrumb">Lista de historia clinica</a>
<a href="{{url('primaria_historia_clinica/'.$historia->id)}}" class="breadcrumb">Ver historia clínica</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">DETALLE HISTORIAL CLINICA</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">General</a></li>
                            <li class="tab"><a href="#familiares">Familiares</a></li>
                            <li class="tab"><a href="#escolares">Escolar</a></li>
                        </ul>
                    </div>
                </nav>

                @php
                use Carbon\Carbon;
                $fechaActual = Carbon::now('CDT')->format('Y-m-d');
                @endphp

                {{-- GENERAL BAR--}}
                <div id="general">
                    <br>
                        <div class="row" style="background-color:#ECECEC;">
                            <p style="text-align: center;font-size:1.2em;">DATOS GENERALES DEL ALUMNO (A)</p>
                        </div>
                        <div class="row">
                            {{--  /* --------------------------- Seleccionar alumno --------------------------- */  --}}
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::label('nombreAlumno', 'Nombre(s)*', array('class' =>
                                    '')); !!}
                                    {!! Form::text('nombreAlumno', $historia->perNombre, array('readonly' => 'true')) !!}
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::label('perApellido1', 'Apellido paterno*', array('class' =>
                                    '')); !!}
                                    {!! Form::text('perApellido1', $historia->perApellido1, array('readonly' => 'true')) !!}
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::label('perApellido2', 'Apellido materno*', array('class' =>
                                    '')); !!}
                                    {!! Form::text('perApellido2', $historia->perApellido2, array('readonly' => 'true')) !!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col s12 m6 l4">
                                    {!! Form::label('perFechaNac', 'Fecha de nacimiento*', array('class' =>
                                    '')); !!}
                                    {!! Form::date('perFechaNac', $historia->perFechaNac, array('readonly' => 'true')) !!}

                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::label('gradoInscrito', 'Grado al que se inscribe *', array('class' => '')); !!}
                                    {!! Form::text('gradoInscrito', $historia->gradoInscrito, array('readonly' => 'true')) !!}
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::label('edadAlumno', 'Edad *', array('class' => '')); !!}
                                    {!! Form::number('edadAlumno', $historia->edadAlumno, array('readonly' => 'true')) !!}
                                </div>
                            </div>

                        </div>


                </div>


                <div id="familiares">
                    <br>
                    <div class="row">
                        <div class="col s12 m6 l12">
                            <div class="input-field">
                                {!! Form::text('tiempoResidencia', $familia->tiempoResidencia, array('readonly' => 'true')) !!}
                                {!! Form::label('tiempoResidencia', 'Si proviene de otra ciudad ¿Cuánto tiempo tiene de residir en Mérida?', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row" style="background-color:#ECECEC;">
                        <p style="text-align: center;font-size:1.2em;">Datos del padre</p>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('nombresPadre', $familia->nombresPadre, array('readonly' => 'true')) !!}
                                {!! Form::label('nombresPadre', 'Nombre(s) *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('apellido1Padre', $familia->apellido1Padre, array('readonly' => 'true')) !!}
                                {!! Form::label('apellido1Padre', 'Apellido paterno *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('apellido2Padre', $familia->apellido2Padre, array('readonly' => 'true')) !!}
                                {!! Form::label('apellido2Padre', 'Apellido materno *', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('celularPadre', $familia->celularPadre, array('readonly' => 'true')) !!}
                                {!! Form::label('celularPadre', 'Celular *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('edadPadre', $familia->edadPadre, array('readonly' => 'true')) !!}
                                {!! Form::label('edadPadre', 'Edad *', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('ocupacioPadre', $familia->ocupacioPadre, array('readonly' => 'true')) !!}
                                {!! Form::label('ocupacioPadre', 'Ocuparacón *', array('class' => '')); !!}
                            </div>
                        </div>

                    </div>


                    <br>
                    <div class="row" style="background-color:#ECECEC;">
                        <p style="text-align: center;font-size:1.2em;">Datos de la madre</p>
                    </div>

                    <div class="row">
                        {{--  nombres de la madre   --}}
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('nombresMadre', $familia->nombresMadre, array('readonly' => 'true')) !!}
                                {!! Form::label('nombresMadre', 'Nombre(s) *', array('class' => '')); !!}
                            </div>
                        </div>

                        {{--  Apellido parterno madre   --}}
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('apellido1Madre', $familia->apellido1Madre, array('readonly' => 'true')) !!}
                                {!! Form::label('apellido1Madre', 'Apellido paterno *', array('class' => '')); !!}
                            </div>
                        </div>

                        {{--  apellido materno madre   --}}
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('apellido2Madre', $familia->apellido2Madre, array('readonly' => 'true')) !!}
                                {!! Form::label('apellido2Madre', 'Apellido materno *', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('celularMadre', $familia->celularMadre, array('readonly' => 'true')) !!}
                                {!! Form::label('celularMadre', 'Celular *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('edadMadre', $familia->edadMadre, array('readonly' => 'true')) !!}
                                {!! Form::label('edadMadre', 'Edad *', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('ocupacionMadre', $familia->ocupacionMadre, array('readonly' => 'true')) !!}
                                {!! Form::label('ocupacionMadre', 'Ocuparacón *', array('class' => '')); !!}
                            </div>
                        </div>

                    </div>

                    <div class="row" style="background-color:#ECECEC;">
                        <p style="text-align: center;font-size:1.2em;">Datos generales</p>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('estadoCilvilPadres', 'Estado civil de los padres*', ['class' => '', ]) !!}
                            <select id="estadoCilvilPadres" class="browser-default" name="estadoCilvilPadres" style="width: 100%; pointer-events: none">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                <option value="CASADOS" {{ $familia->estadoCilvilPadres == "CASADOS" ? 'selected="selected"' : '' }}>Casados</option>
                                <option value="UNIÓN LIBRE" {{ $familia->estadoCilvilPadres == "UNIÓN LIBRE" ? 'selected="selected"' : '' }}>Unión libre</option>
                                <option value="DIVORCIADOS" {{ $familia->estadoCilvilPadres == "DIVORCIADOS" ? 'selected="selected"' : '' }}>Divorciados</option>
                                <option value="VIUDO/A" {{ $familia->estadoCilvilPadres == "VIUDO/A" ? 'selected="selected"' : '' }}>Viudo/a</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l8">
                            <div class="input-field">
                                {!! Form::text('observaciones', $familia->observaciones, array('readonly' => 'true')) !!}
                                {!! Form::label('observaciones', 'Observaciones', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('religion', $familia->religion, array('readonly' => 'true')) !!}
                                {!! Form::label('religion', 'Religion', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <p><strong>Breve descripción de su familia (integrantes, relacion, edad, ocupacion)</strong></p>

                    <div class="row">
                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('integrante1', $familia->integrante1, array('readonly' => 'true')) !!}
                                {!! Form::label('integrante1', 'Integrante 1', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('relacionIntegrante1', $familia->relacionIntegrante1, array('readonly' => 'true')) !!}
                                {!! Form::label('relacionIntegrante1', 'Relacion integrante 1', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::number('edadIntegrante1', $familia->edadIntegrante1, array('readonly' => 'true')) !!}
                                {!! Form::label('edadIntegrante1', 'Edad integrante 1', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('ocupacionIntegrante1', $familia->ocupacionIntegrante1, array('readonly' => 'true')) !!}
                                {!! Form::label('ocupacionIntegrante1', 'Ocupación integrante 1', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('integrante2', $familia->integrante2, array('readonly' => 'true')) !!}
                                {!! Form::label('integrante2', 'Integrante 2', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('relacionIntegrante2', $familia->relacionIntegrante2, array('readonly' => 'true')) !!}
                                {!! Form::label('relacionIntegrante2', 'Relacion integrante 2', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::number('edadIntegrante2', $familia->edadIntegrante2, array('readonly' => 'true')) !!}
                                {!! Form::label('edadIntegrante2', 'Edad integrante 2', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('ocupacionIntegrante2', $familia->ocupacionIntegrante2, array('readonly' => 'true')) !!}
                                {!! Form::label('ocupacionIntegrante2', 'Ocupación integrante 2', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('integrante3', $familia->integrante3, array('readonly' => 'true')) !!}
                                {!! Form::label('integrante3', 'Integrante 2', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('relacionIntegrante3', $familia->relacionIntegrante3, array('readonly' => 'true')) !!}
                                {!! Form::label('relacionIntegrante3', 'Relacion integrante 3', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::number('edadIntegrante3', $familia->edadIntegrante3,array('readonly' => 'true')) !!}
                                {!! Form::label('edadIntegrante3', 'Edad integrante 3', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('ocupacionIntegrante4', $familia->ocupacionIntegrante4, array('readonly' => 'true')) !!}
                                {!! Form::label('ocupacionIntegrante4', 'Ocupación integrante 3', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('integrante4', $familia->integrante4, array('readonly' => 'true')) !!}
                                {!! Form::label('integrante4', 'Integrante 4', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('relacionIntegrante4', $familia->relacionIntegrante4,array('readonly' => 'true')) !!}
                                {!! Form::label('relacionIntegrante4', 'Relacion integrante 4', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::number('edadIntegrante4', $familia->edadIntegrante4, array('readonly' => 'true')) !!}
                                {!! Form::label('edadIntegrante4', 'Edad integrante 4', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l3">
                            <div class="input-field">
                                {!! Form::text('ocupacionIntegrante3', $familia->ocupacionIntegrante3, array('readonly' => 'true')) !!}
                                {!! Form::label('ocupacionIntegrante3', 'Ocupación integrante 4', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('apoyoTareas', $familia->apoyoTareas, array('readonly' => 'true')) !!}
                                {!! Form::label('apoyoTareas', '¿Quién apoya a su hijo(a) en las tareas en casa? *', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('deporteActividad', $familia->deporteActividad, array('readonly' => 'true')) !!}
                                {!! Form::label('deporteActividad', 'Deporte (s) o actividad cultural que practica *', array('class' => '')); !!}
                            </div>
                        </div>


                    </div>
                </div>


                {{--  escolares   --}}
                <div id="escolares">
                    <br>

                    <div class="row" style="background-color:#ECECEC;">
                        <p style="text-align: center;font-size:1.2em;">Datos escolares</p>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('escuelaProcedencia', $escolar->escuelaProcedencia,array('readonly' => 'true')) !!}
                                {!! Form::label('escuelaProcedencia', 'Nombre de la escuela donde cursó *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('aniosEstudios', $escolar->aniosEstudios, array('readonly' => 'true')) !!}
                                {!! Form::label('aniosEstudios', 'Años estudiados en la escuela anterior *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('motivosCambio', $escolar->motivosCambio, array('readonly' => 'true')) !!}
                                {!! Form::label('motivosCambio', 'Motivos del cambio de escuela *', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('kinder', $escolar->kinder, array('readonly' => 'true')) !!}
                                {!! Form::label('kinder', 'Kinder *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('observaciones', $escolar->observaciones, array('readonly' => 'true')) !!}
                                {!! Form::label('observaciones', 'Observaciones *', array('class' => '')); !!}
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col s12 m6 l12">
                            <div class="input-field">
                                {!! Form::text('primaria', $escolar->primaria, array('readonly' => 'true')) !!}
                                {!! Form::label('primaria', 'Primaria *', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('promedioGrado1', $escolar->promedioGrado1, array('readonly' => 'true')) !!}
                                {!! Form::label('promedioGrado1', 'Promedio en 1º', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('promedioGrado2', $escolar->promedioGrado2, array('readonly' => 'true')) !!}
                                {!! Form::label('promedioGrado2', 'Promedio en 2º', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('promedioGrado3', $escolar->promedioGrado3, array('readonly' => 'true')) !!}
                                {!! Form::label('promedioGrado3', 'Promedio en 3º', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('promedioGrado4', $escolar->promedioGrado4, array('readonly' => 'true')) !!}
                                {!! Form::label('promedioGrado4', 'Promedio en 4º', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('promedioGrado5', $escolar->promedioGrado5, array('readonly' => 'true')) !!}
                                {!! Form::label('promedioGrado5', 'Promedio en 5º', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('promedioGrado6', $escolar->promedioGrado6, array('readonly' => 'true')) !!}
                                {!! Form::label('promedioGrado6', 'Promedio en 6º', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('gradoRepetido', $escolar->gradoRepetido,array('readonly' => 'true')) !!}
                                {!! Form::label('gradoRepetido', 'Repetición de algún grado *', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('promedioRepetido', $escolar->promedioRepetido, array('readonly' => 'true')) !!}
                                {!! Form::label('promedioRepetido', 'Promedio', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            {!! Form::label('apoyoPedagogico', '¿Ha recibido su hijo(a) apoyo pedagógico en algún grado escolar? *', array('class' => '')); !!}
                            <select id="apoyoPedagogico" required class="browser-default" name="apoyoPedagogico"
                                style="width: 100%; pointer-events: none">
                                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                                <option value="SI" {{ $escolar->apoyoPedagogico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                                <option value="NO" {{ $escolar->apoyoPedagogico == "NO" ? 'selected="selected"' : '' }}>NO</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('observacionesApoyo', $escolar->observacionesApoyo, array('readonly' => 'true')) !!}
                                {!! Form::label('observacionesApoyo', 'Observaciones', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>
                    <br>
                    <p><strong>¿Ha recibido su hijo(a) algún tratamiento?</strong></p>
                    <br>
                    <div class="row">
                        <div class="col s12 m6 l6">
                            {!! Form::label('medico', 'Medico *', array('class' => '')); !!}
                            <select id="medico" required class="browser-default" name="medico"
                                style="width: 100%; pointer-events: none">
                                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                                <option value="SI" {{ $escolar->medico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                                <option value="NO" {{ $escolar->medico == "NO" ? 'selected="selected"' : '' }}>NO</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('observacionesMedico', $escolar->observacionesMedico, array('readonly' => 'true')) !!}
                                {!! Form::label('observacionesMedico', 'Observaciones', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            {!! Form::label('neurologico', 'Neurológico *', array('class' => '')); !!}
                            <select id="neurologico" required class="browser-default" name="neurologico"
                                style="width: 100%; pointer-events: none">
                                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                                <option value="SI" {{ $escolar->neurologico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                                <option value="NO" {{ $escolar->neurologico == "NO" ? 'selected="selected"' : '' }}>NO</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('observacionesNerologico', $escolar->observacionesNerologico, array('readonly' => 'true')) !!}
                                {!! Form::label('observacionesNerologico', 'Observaciones', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            {!! Form::label('psicologico', 'Psicologico *', array('class' => '')); !!}
                            <select id="psicologico" required class="browser-default" name="psicologico"
                                style="width: 100%; pointer-events: none">
                                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                                <option value="SI" {{ $escolar->psicologico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                                <option value="NO" {{ $escolar->psicologico == "NO" ? 'selected="selected"' : '' }}>NO</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('observacionesPsicologico', $escolar->observacionesPsicologico, array('readonly' => 'true')) !!}
                                {!! Form::label('observacionesPsicologico', 'Observaciones', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l12">
                            <div class="input-field">
                                {!! Form::text('motivoInscripcion', $escolar->motivoInscripcion, array('readonly' => 'true')) !!}
                                {!! Form::label('motivoInscripcion', 'Motivo por el que se solicita la inscripción en la Escuela Modelo', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <p><strong>Nombre de familiares o conocidos que estudien o trabajen en esta escuela</strong></p>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('familiar1', $escolar->familiar1, array('readonly' => 'true')) !!}
                                {!! Form::label('familiar1', 'Familiar 1', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('familiar2', $escolar->familiar2, array('readonly' => 'true')) !!}
                                {!! Form::label('familiar2', 'Familiar 2', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('familiar3', $escolar->familiar3,array('readonly' => 'true')) !!}
                                {!! Form::label('familiar3', 'Familiar 3', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <p><strong>Nombre de familiares o conocidos a quien se le pueda pedir referencia</strong></p>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('referencia1', $escolar->referencia1,array('readonly' => 'true')) !!}
                                {!! Form::label('referencia1', 'Referencia 1 *', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('celularReferencia1', $escolar->celularReferencia1, array('readonly' => 'true')) !!}
                                {!! Form::label('celularReferencia1', 'Celular referencia 1 *', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('referencia2', $escolar->referencia2, array('readonly' => 'true')) !!}
                                {!! Form::label('referencia2', 'Referencia 2 *', array('class' => '')); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('celularReferencia2', $escolar->celularReferencia2, array('readonly' => 'true')) !!}
                                {!! Form::label('celularReferencia2', 'Celular referencia 2 *', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l12">
                            <div class="input-field">
                                {!! Form::text('observacionesGenerales', $escolar->observacionesGenerales, array('readonly' => 'true')) !!}
                                {!! Form::label('observacionesGenerales', 'Observaciones generales', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('entrevisto', $escolar->entrevisto, array('readonly' => 'true')) !!}
                                {!! Form::label('entrevisto', 'Entrevisto *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l6">
                            <div class="input-field">
                                {!! Form::text('ubicacion', $escolar->ubicacion, array('readonly' => 'true')) !!}
                                {!! Form::label('ubicacion', 'Ubicacion *', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>

                </div>





            </div>

        </div>
    </div>
</div>

{{-- Script de funciones auxiliares  --}}
{!! HTML::script(asset('js/funcionesAuxiliares.js'), array('type' => 'text/javascript')) !!}

@endsection

@section('footer_scripts')


@endsection

<style>
    input[type="checkbox"][readonly] {
        pointer-events: none !important;
      }

</style>
