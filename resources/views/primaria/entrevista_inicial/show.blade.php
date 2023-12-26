@extends('layouts.dashboard')

@section('template_title')
    Primaria entrevista inicial
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_curso')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_entrevista_inicial')}}" class="breadcrumb">Listado de entrevista inicial</a>
    <a href="{{url('primaria_entrevista_inicial/'.$alumnoEntrevista->id.'/edit')}}" class="breadcrumb">Editar entrevista inicial</a>

@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">ENTREVISTA INICIAL</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">DEPARTAMENTO DE PSICOPEDAGOGÍA - ENTREVISTA INICIAL A PADRES DE FAMILIA</a></li>
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">
                <br>
                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">I. INFORMACIÓN PERSONAL Y FAMILIAR DEL ALUMNO</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('alumno_id', 'Alumno *', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="alumno_id" class="browser-default validate select2" name="alumno_id" disabled style="width: 100%;" required {{old('alumno_id')}}>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach ($alumnos as $alumno)
                                    <option value="{{ $alumno->alumno_id }}" {{ $alumno->alumno_id == $alumnoEntrevista->alumno_id ? 'selected' : '' }}>{{$alumno->perNombre}} {{$alumno->perApellido1}} {{$alumno->perApellido2}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('fechaNacimiento', 'Fecha de nacimiento *', array('class' => '')); !!}
                        <input type="date" name="fechaNacimiento" id="fechaNacimiento" readonly="true">
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('edadAlumo', 'Edad *', array('class' => '')); !!}
                        <input type="text" name="edadAlumo" id="edadAlumo" readonly="true">
                    </div>
                </div>



                <div class="row">
                    {{-- Pais  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('paisAlumno', 'Pais nacimiento *', array('class' => '')); !!}
                        <input type="text" name="paisAlumno" id="paisAlumno" readonly="true">
                    </div>

                    {{-- Estado   --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('estadoAlumno', 'Estado nacimiento *', array('class' => '')); !!}
                        <input type="text" name="estadoAlumno" id="estadoAlumno" readonly="true">
                    </div>

                    {{-- Municipio  --}}                    
                    <div class="col s12 m6 l4">
                        {!! Form::label('municipioAlumno', 'Municipio nacimiento *', array('class' => '')); !!}
                        <input type="text" name="municipioAlumno" id="municipioAlumno" readonly="true">
                    </div>
                    
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('gradoInscrito', $alumnoEntrevista->gradoInscrito, array('readonly' => 'true')) !!}
                            {!! Form::label('gradoInscrito', 'Grado al que se inscribe', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tiempoResidencia', $alumnoEntrevista->tiempoResidencia, array('readonly' => 'true')) !!}
                            {!! Form::label('tiempoResidencia', 'Si proviene de otra ciudad ¿Cuánto tiempo tiene de residir en Mérida?', array('class' => '')); !!}
                        </div>
                    </div>

                     
                </div>

                <br>

                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos del padre</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('nombrePadre', $alumnoEntrevista->nombrePadre, array('readonly' => 'true')) !!}
                            {!! Form::label('nombrePadre', 'Nombre(s)', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido1Padre', $alumnoEntrevista->apellido1Padre, array('readonly' => 'true')) !!}
                            {!! Form::label('apellido1Padre', 'Apellido 1 *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido2Padre', $alumnoEntrevista->apellido2Padre, array('readonly' => 'true')) !!}
                            {!! Form::label('apellido2Padre', 'Apellido 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('edadPadre', $alumnoEntrevista->edadPadre, array('readonly' => 'true')) !!}
                            {!! Form::label('edadPadre', 'Edad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularPadre', $alumnoEntrevista->celularPadre, array('readonly' => 'true')) !!}
                            {!! Form::label('celularPadre', 'Celular', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ocupacionPadre', $alumnoEntrevista->ocupacionPadre,array('readonly' => 'true')) !!}
                            {!! Form::label('ocupacionPadre', 'Ocupación', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empresaPadre', $alumnoEntrevista->empresaPadre, array('readonly' => 'true')) !!}
                            {!! Form::label('empresaPadre', 'Empresa', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('correoPadre', $alumnoEntrevista->correoPadre, array('readonly' => 'true')) !!}
                            {!! Form::label('correoPadre', 'Correo', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

       

                <br>
                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos de la madre</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('nombreMadre', $alumnoEntrevista->nombreMadre, array('readonly' => 'true')) !!}
                            {!! Form::label('nombreMadre', 'Nombre(s)', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido1Madre', $alumnoEntrevista->apellido1Madre, array('readonly' => 'true')) !!}
                            {!! Form::label('apellido1Madre', 'Apellido 1 *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido2Madre', $alumnoEntrevista->apellido2Madre, array('readonly' => 'true')) !!}
                            {!! Form::label('apellido2Madre', 'Apellido 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('edadMadre', $alumnoEntrevista->edadMadre, array('readonly' => 'true')) !!}
                            {!! Form::label('edadMadre', 'Edad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularMadre', $alumnoEntrevista->celularMadre, array('readonly' => 'true')) !!}
                            {!! Form::label('celularMadre', 'Celular', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ocupacionMadre', $alumnoEntrevista->ocupacionMadre, array('readonly' => 'true')) !!}
                            {!! Form::label('ocupacionMadre', 'Ocupación', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empresaMadre', $alumnoEntrevista->empresaMadre, array('readonly' => 'true')) !!}
                            {!! Form::label('empresaMadre', 'Empresa', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('correoMadre', $alumnoEntrevista->correoMadre, array('readonly' => 'true')) !!}
                            {!! Form::label('correoMadre', 'Correo', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <br>
                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos familiares</p>
                </div>

                <div class="row">
                    {{-- Estado civil de los padres * --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('estadoCivilPadres', 'Estado civil de los padres *', array('class' => '')); !!}
                        {!! Form::text('estadoCivilPadres', $alumnoEntrevista->estadoCivilPadres, array('readonly' => 'true')) !!}
                    </div>
                   
                    {{-- ¿Tienen alguna religión? ¿Cuál? * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('religion', $alumnoEntrevista->religion, array('readonly' => 'true')) !!}
                            {!! Form::label('religion', 'Religión', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <textarea id="observaciones" name="observaciones" class="materialize-textarea" readonly="true">{{$alumnoEntrevista->observaciones}}</textarea>
                            {!! Form::label('observaciones', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div>                    
                </div>

                
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <textarea id="condicionFamiliar" name="condicionFamiliar" class="materialize-textarea" readonly="true">{{$alumnoEntrevista->condicionFamiliar}}</textarea>
                            <label for="condicionFamiliar">Condición familiar: <b>*Comunicar por escrito la condición familiar especial, irregular o extraordinaria por 
                                la cual el niño, si así lo fuere, esté pasando.</b></label>
                        </div>
                    </div>  
                </div>
               

                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Tutor</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('tutorResponsable', $alumnoEntrevista->tutorResponsable, array('readonly' => 'true')) !!}
                            {!! Form::label('tutorResponsable', 'Padre o tutor responsable financiero *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularTutor', $alumnoEntrevista->celularTutor, array('readonly' => 'true')) !!}
                            {!! Form::label('celularTutor', 'Celular *', array('class' => '')); !!}
                        </div>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('accidenteLlamar', $alumnoEntrevista->accidenteLlamar, array('readonly' => 'true')) !!}
                            {!! Form::label('accidenteLlamar', 'En caso de algún accidente se deberá llamar a *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularAccidente', $alumnoEntrevista->celularAccidente, array('readonly' => 'true')) !!}
                            {!! Form::label('celularAccidente', 'Celular *', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>


                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos familiares generales</p>
                </div>
                <p>Breve descripción de su familia </p>

                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('integrante1', $alumnoEntrevista->integrante1, array('readonly' => 'true')) !!}
                            {!! Form::label('integrante1', 'Integrante 1', array('class' => '')); !!}
                        </div>
                    </div>        
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('relacionIntegrante1', $alumnoEntrevista->relacionIntegrante1, array('readonly' => 'true')) !!}
                            {!! Form::label('relacionIntegrante1', 'Relación integrante 1', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::number('edadintegrante1', $alumnoEntrevista->edadintegrante1, array('readonly' => 'true')) !!}
                            {!! Form::label('edadintegrante1', 'Edad integrante 1', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('ocupacionIntegrante1', $alumnoEntrevista->ocupacionIntegrante1, array('readonly' => 'true')) !!}
                            {!! Form::label('ocupacionIntegrante1', 'Ocupación integrante 1', array('class' => '')); !!}
                        </div>
                    </div>            
                </div>

                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('integrante2', $alumnoEntrevista->integrante2, array('readonly' => 'true')) !!}
                            {!! Form::label('integrante2', 'Integrante 2', array('class' => '')); !!}
                        </div>
                    </div>        
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('relacionIntegrante2', $alumnoEntrevista->relacionIntegrante2, array('readonly' => 'true')) !!}
                            {!! Form::label('relacionIntegrante2', 'Relación integrante 2', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::number('edadintegrante2', $alumnoEntrevista->edadintegrante2, array('readonly' => 'true')) !!}
                            {!! Form::label('edadintegrante2', 'Edad integrante 2', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('ocupacionIntegrante2', $alumnoEntrevista->ocupacionIntegrante2, array('readonly' => 'true')) !!}
                            {!! Form::label('ocupacionIntegrante2', 'Ocupación integrante 2', array('class' => '')); !!}
                        </div>
                    </div>            
                </div>

                <div class="row">
                    <div class="col s12 m6 m12">
                        <div class="input-field">
                            <label for="situcionLegal">Situación legal: <b>*Entregar copia simple que avale el proceso en todos los casos de Guarda y
                                Custodia que ya haya tenido una sentencia definitiva o se encuentren en un proceso legal.</b></label>
                            <textarea id="situcionLegal" name="situcionLegal" class="materialize-textarea validate" readonly="true">{{$alumnoEntrevista->situcionLegal}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 m6">
                        <div class="input-field">
                            <label for="descripcionNinio">¿Cómo describen los padres al niño/a?</label>
                            <textarea id="descripcionNinio" name="descripcionNinio" class="materialize-textarea validate" readonly="true">{{$alumnoEntrevista->descripcionNinio}}</textarea>
                        </div>
                    </div>

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('apoyoTarea', $alumnoEntrevista->apoyoTarea, array('readonly' => 'true')) !!}
                            {!! Form::label('apoyoTarea', '¿Quién apoya al niño(a) en las tareas para realizar en casa?: ', array('class' => '')); !!}
                        </div>
                    </div>  
                </div>

                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">II.	INFORMACIÓN ESCOLAR DEL ALUMNO </p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('escuelaAnterior', $alumnoEntrevista->escuelaAnterior, array('readonly' => 'true')) !!}
                            {!! Form::label('escuelaAnterior', 'Nombre de la escuela anterior', array('class' => '')); !!}
                        </div>
                    </div>  

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('aniosEstudiados', $alumnoEntrevista->aniosEstudiados, array('readonly' => 'true')) !!}
                            {!! Form::label('aniosEstudiados', 'Años estudiados en la escuela anterior', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('motivosCambioEscuela', $alumnoEntrevista->motivosCambioEscuela, array('readonly' => 'true')) !!}
                            {!! Form::label('motivosCambioEscuela', 'Motivos del cambio de escuela', array('class' => '')); !!}
                        </div>
                    </div>  
                </div>


                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('kinder', $alumnoEntrevista->kinder, array('readonly' => 'true')) !!}
                            {!! Form::label('kinder', 'Kínder', array('class' => '')); !!}
                        </div>
                    </div>  

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('observacionEscolar', $alumnoEntrevista->observacionEscolar, array('readonly' => 'true')) !!}
                            {!! Form::label('observacionEscolar', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>Primaria</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio1">Promedio en 1º</label>
                            <input type="number" name="promedio1" id="promedio1" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio1}}" readonly="true">
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio2">Promedio en 2º</label>
                            <input type="number" name="promedio2" id="promedio2" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio2}}" readonly="true">
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio3">Promedio en 3º</label>
                            <input type="number" name="promedio3" id="promedio3" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio3}}" readonly="true">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio4">Promedio en 4º</label>
                            <input type="number" name="promedio4" id="promedio4" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio4}}" readonly="true">
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio5">Promedio en 5º</label>
                            <input type="number" name="promedio5" id="promedio5" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio5}}" readonly="true">
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio6">Promedio en 6º</label>
                            <input type="number" name="promedio6" id="promedio6" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio6}}" readonly="true">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('recursamientoGrado', $alumnoEntrevista->recursamientoGrado, array('readonly' => 'true')) !!}
                            {!! Form::label('recursamientoGrado', 'Recursamiento de algún grado', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('deportes', $alumnoEntrevista->deportes, array('readonly' => 'true')) !!}
                            {!! Form::label('deportes', 'Deporte (s) o actividad cultural que practica', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('apoyoPedagogico', '¿Ha recibido su hijo(a) apoyo pedagógico en algún grado escolar? *', ['class' => '']); !!}
                        {!! Form::text('apoyoPedagogico', $alumnoEntrevista->apoyoPedagogico, array('readonly' => 'true')) !!}
                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsPedagogico', $alumnoEntrevista->obsPedagogico, array('readonly' => 'true')) !!}
                            {!! Form::label('obsPedagogico', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('terapiaLenguaje', '¿Ha recibido su hijo(a) terapia de lenguaje en algún grado escolar? *', ['class' => '']); !!}
                        {!! Form::text('terapiaLenguaje', $alumnoEntrevista->terapiaLenguaje, array('readonly' => 'true')) !!}

                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTerapiaLenguaje', $alumnoEntrevista->obsTerapiaLenguaje, array('readonly' => 'true')) !!}
                            {!! Form::label('obsTerapiaLenguaje', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>


                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">III.	INFORMACIÓN SOBRE LA CONDICIÓN DE SALUD O NECESIDADES ESPECÍFICAS DEL ALUMNO</p>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('tratamientoMedico', '¿Ha recibido su hijo(a)  tratamiento médico? *', ['class' => '']); !!}
                        {!! Form::text('tratamientoMedico', $alumnoEntrevista->tratamientoMedico, array('readonly' => 'true')) !!}

                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratamientoMedico', $alumnoEntrevista->obsTratamientoMedico, array('readonly' => 'true')) !!}
                            {!! Form::label('obsTratamientoMedico', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>¿Actualmente presenta algún padecimiento?</p>

                <div class="row">
                    <div class="col s12 m6 l3">
                        {!! Form::label('hemofilia', 'Hemofilia *', ['class' => '']); !!}
                        {!! Form::text('hemofilia', $alumnoEntrevista->hemofilia, array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('epilepsia', 'Epilepsia *', ['class' => '']); !!}
                        {!! Form::text('epilepsia', $alumnoEntrevista->epilepsia, array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('kawasaqui', 'Kawasaqui *', ['class' => '']); !!}
                        {!! Form::text('kawasaqui', $alumnoEntrevista->kawasaqui, array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('asma', 'Asma *', ['class' => '']); !!}
                        {!! Form::text('asma', $alumnoEntrevista->asma, array('readonly' => 'true')) !!}
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l3">
                        {!! Form::label('diabetes', 'Diabetes *', ['class' => '']); !!}
                        {!! Form::text('diabetes', $alumnoEntrevista->diabetes, array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('cardiaco', 'Cardiaco *', ['class' => '']); !!}
                        {!! Form::text('cardiaco', $alumnoEntrevista->cardiaco, array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('dermatologico', 'Dermatológico *', ['class' => '']); !!}
                        {!! Form::text('dermatologico', $alumnoEntrevista->dermatologico, array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('alergias', 'Alergias *', ['class' => '']); !!}
                        {!! Form::text('alergias', $alumnoEntrevista->alergias, array('readonly' => 'true')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('otroTratamiento',  $alumnoEntrevista->otroTratamiento,array('readonly' => 'true')) !!}
                            {!! Form::label('otroTratamiento', 'Otro tratamiento', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tomaMedicamento', $alumnoEntrevista->tomaMedicamento, array('readonly' => 'true')) !!}
                            {!! Form::label('tomaMedicamento', '¿Toma algún medicamento?', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tomaMedicamento', $alumnoEntrevista->tomaMedicamento, array('readonly' => 'true')) !!}
                            {!! Form::label('cuidadoEspecifico', '¿Requiere algún cuidado específico? ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>¿Ha recibido su hijo(a) tratamiento?</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('tratimientoNeurologico', 'Neurológico *', ['class' => '']); !!}
                        {!! Form::text('tratimientoNeurologico', $alumnoEntrevista->tratimientoNeurologico, array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratimientoNeurologico', $alumnoEntrevista->obsTratimientoNeurologico, array('readonly' => 'true')) !!}
                            {!! Form::label('obsTratimientoNeurologico', 'Observaciones ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('tratamientoPsicologico', 'Psicológico *', ['class' => '']); !!}
                        {!! Form::text('tratamientoPsicologico', $alumnoEntrevista->tratamientoPsicologico, array('readonly' => 'true')) !!}

                    </div>

                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratimientoPsicologico', $alumnoEntrevista->obsTratimientoPsicologico, array('readonly' => 'true')) !!}
                            {!! Form::label('obsTratimientoPsicologico', 'Observaciones ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                

                <p><b>*Entregar una copia simple del último diagnóstico y/o tratamiento de todo aquel niño que presente algún tipo de enfermedad, padecimiento o condición de salud. </b></p>

                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('motivoInscripcionEscuela', $alumnoEntrevista->motivoInscripcionEscuela, array('readonly' => 'true')) !!}
                            {!! Form::label('motivoInscripcionEscuela', 'Motivo por el que se solicita la inscripción en la Escuela Modelo ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">IV.	REFERENCIAS</p>
                </div>

                <p>Nombre de familiares o conocidos que estudien o trabajen en la Escuela Modelo</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('conocidoEscuela1', $alumnoEntrevista->conocidoEscuela1, array('readonly' => 'true')) !!}
                            {!! Form::label('conocidoEscuela1', 'Familiar o conocido 1', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('conocidoEscuela2', $alumnoEntrevista->conocidoEscuela2, array('readonly' => 'true')) !!}
                            {!! Form::label('conocidoEscuela2', 'Familiar o conocido 2', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('conocidoEscuela3', $alumnoEntrevista->conocidoEscuela3, array('readonly' => 'true')) !!}
                            {!! Form::label('conocidoEscuela3', 'Familiar o conocido 3', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>Nombre y teléfono de familiares o conocidos a quien se le pueda pedir referencia</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia1', $alumnoEntrevista->referencia1, array('readonly' => 'true')) !!}
                            {!! Form::label('referencia1', 'Nombre completo referencia 1', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularReferencia1', $alumnoEntrevista->celularReferencia1, array('readonly' => 'true')) !!}
                            {!! Form::label('celularReferencia1', 'Celular referencia 1', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia2', $alumnoEntrevista->referencia2, array('readonly' => 'true')) !!}
                            {!! Form::label('referencia2', 'Nombre completo referencia 2', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularReferencia2', $alumnoEntrevista->celularReferencia2, array('readonly' => 'true')) !!}
                            {!! Form::label('celularReferencia2', 'Celular referencia 2', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia3', $alumnoEntrevista->referencia3, array('readonly' => 'true')) !!}
                            {!! Form::label('referencia3', 'Nombre completo referencia 3', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularReferencia3',  $alumnoEntrevista->celularReferencia3, array('readonly' => 'true')) !!}
                            {!! Form::label('celularReferencia3', 'Celular referencia 3', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('obsGenerales', $alumnoEntrevista->obsGenerales, array('readonly' => 'true')) !!}
                            {!! Form::label('obsGenerales', 'OBSERVACIONES GENERALES', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <br>
                <br>
                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('entrevistador', $alumnoEntrevista->entrevistador,array('readonly' => 'true')) !!}
                            {!! Form::label('entrevistador', 'ENTREVISTADOR', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

          </div>
          
        </div>
    </div>
  </div>



@endsection

@section('footer_scripts')

@include('primaria.entrevista_inicial.traerDatos')

@endsection
