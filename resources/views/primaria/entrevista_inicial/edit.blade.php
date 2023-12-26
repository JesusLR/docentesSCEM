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
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_entrevista_inicial.update', $alumnoEntrevista->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">ENTREVISTA INICIAL #{{$alumnoEntrevista->id}}</span>

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
                            <select id="alumno_id" class="browser-default validate select2" name="alumno_id" style="width: 100%;"  required {{old('alumno_id')}}>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach ($alumnos as $alumno)
                                    <option selected disabled value="{{ $alumno->alumno_id }}" {{ $alumno->alumno_id == $alumnoEntrevista->alumno_id ? 'selected' : '' }}>Clave pago: {{$alumno->aluClave}}, Nombre: {{$alumno->perNombre}} {{$alumno->perApellido1}} {{$alumno->perApellido2}}</option>
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
                            {!! Form::number('gradoInscrito', $alumnoEntrevista->gradoInscrito, array('id' => 'gradoInscrito', 'class' => 'validate', 'min'=>'0', 'max'=>'6')) !!}
                            {!! Form::label('gradoInscrito', 'Grado al que se inscribe', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tiempoResidencia', $alumnoEntrevista->tiempoResidencia, array('id' => 'tiempoResidencia', 'class' => 'validate', 'maxlength'=>'25')) !!}
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
                            {!! Form::text('nombrePadre', $alumnoEntrevista->nombrePadre, array('id' => 'nombrePadre', 'class' => 'validate','required','maxlength'=>'80')) !!}
                            {!! Form::label('nombrePadre', 'Nombre(s)', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido1Padre', $alumnoEntrevista->apellido1Padre, array('id' => 'apellido1Padre', 'class' => 'validate','required','maxlength'=>'40')) !!}
                            {!! Form::label('apellido1Padre', 'Apellido 1 *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido2Padre', $alumnoEntrevista->apellido2Padre, array('id' => 'apellido2Padre', 'class' => 'validate','maxlength'=>'40')) !!}
                            {!! Form::label('apellido2Padre', 'Apellido 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('edadPadre', $alumnoEntrevista->edadPadre, array('id' => 'edadPadre', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadPadre', 'Edad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularPadre', $alumnoEntrevista->celularPadre, array('id' => 'celularPadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('celularPadre', 'Celular', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ocupacionPadre', $alumnoEntrevista->ocupacionPadre, array('id' => 'ocupacionPadre', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionPadre', 'Ocupación', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empresaPadre', $alumnoEntrevista->empresaPadre, array('id' => 'empresaPadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('empresaPadre', 'Empresa', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('correoPadre', $alumnoEntrevista->correoPadre, array('id' => 'correoPadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
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
                            {!! Form::text('nombreMadre', $alumnoEntrevista->nombreMadre, array('id' => 'nombreMadre', 'class' => 'validate','required','maxlength'=>'80')) !!}
                            {!! Form::label('nombreMadre', 'Nombre(s)', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido1Madre', $alumnoEntrevista->apellido1Madre, array('id' => 'apellido1Madre', 'class' => 'validate','required','maxlength'=>'40')) !!}
                            {!! Form::label('apellido1Madre', 'Apellido 1 *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido2Madre', $alumnoEntrevista->apellido2Madre, array('id' => 'apellido2Madre', 'class' => 'validate','maxlength'=>'40')) !!}
                            {!! Form::label('apellido2Madre', 'Apellido 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('edadMadre', $alumnoEntrevista->edadMadre, array('id' => 'edadMadre', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadMadre', 'Edad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularMadre', $alumnoEntrevista->celularMadre, array('id' => 'celularMadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('celularMadre', 'Celular', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ocupacionMadre', $alumnoEntrevista->ocupacionMadre, array('id' => 'ocupacionMadre', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionMadre', 'Ocupación', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empresaMadre', $alumnoEntrevista->empresaMadre, array('id' => 'empresaMadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('empresaMadre', 'Empresa', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('correoMadre', $alumnoEntrevista->correoMadre, array('id' => 'correoMadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
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
                        <select id="estadoCivilPadres" class="browser-default validate" required name="estadoCivilPadres" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="CASADOS" {{ $alumnoEntrevista->estadoCivilPadres == "CASADOS" ? 'selected' : '' }}>Casados</option>
                            <option value="DIVORCIADOS" {{ $alumnoEntrevista->estadoCivilPadres == "DIVORCIADOS" ? 'selected' : '' }}>Divorciados</option>
                            <option value="SEPARADOS" {{ $alumnoEntrevista->estadoCivilPadres == "SEPARADOS" ? 'selected' : '' }}>Separados</option>
                        </select>
                    </div>
                   
                    {{-- ¿Tienen alguna religión? ¿Cuál? * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('religion', $alumnoEntrevista->religion, array('id' => 'religion', 'class' => 'validate','maxlength'=>'100')) !!}
                            {!! Form::label('religion', 'Religión', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <textarea id="observaciones" name="observaciones" class="materialize-textarea">{{$alumnoEntrevista->observaciones}}</textarea>
                            {!! Form::label('observaciones', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div>                    
                </div>

                
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <textarea id="condicionFamiliar" name="condicionFamiliar" class="materialize-textarea">{{$alumnoEntrevista->condicionFamiliar}}</textarea>
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
                            {!! Form::text('tutorResponsable', $alumnoEntrevista->tutorResponsable, array('id' => 'tutorResponsable', 'required', 'class' => 'validate','maxlength'=>'80')) !!}
                            {!! Form::label('tutorResponsable', 'Padre o tutor responsable financiero *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularTutor', $alumnoEntrevista->celularTutor, array('id' => 'celularTutor', 'class' => 'validate', 'required', 'maxlength'=>'10')) !!}
                            {!! Form::label('celularTutor', 'Celular *', array('class' => '')); !!}
                        </div>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('accidenteLlamar', $alumnoEntrevista->accidenteLlamar, array('id' => 'accidenteLlamar', 'class' => 'validate', 'required', 'maxlength'=>'200')) !!}
                            {!! Form::label('accidenteLlamar', 'En caso de algún accidente se deberá llamar a *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularAccidente', $alumnoEntrevista->celularAccidente, array('id' => 'celularAccidente', 'class' => 'validate', 'required', 'maxlength'=>'10')) !!}
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
                            {!! Form::text('integrante1', $alumnoEntrevista->integrante1, array('id' => 'integrante1', 'class' => 'validate','maxlength'=>'255')) !!}
                            {!! Form::label('integrante1', 'Integrante 1', array('class' => '')); !!}
                        </div>
                    </div>        
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('relacionIntegrante1', $alumnoEntrevista->relacionIntegrante1, array('id' => 'relacionIntegrante1', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('relacionIntegrante1', 'Relación integrante 1', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::number('edadintegrante1', $alumnoEntrevista->edadintegrante1, array('id' => 'edadintegrante1', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadintegrante1', 'Edad integrante 1', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('ocupacionIntegrante1', $alumnoEntrevista->ocupacionIntegrante1, array('id' => 'ocupacionIntegrante1', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionIntegrante1', 'Ocupación integrante 1', array('class' => '')); !!}
                        </div>
                    </div>            
                </div>

                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('integrante2', $alumnoEntrevista->integrante2, array('id' => 'integrante2', 'class' => 'validate','maxlength'=>'255')) !!}
                            {!! Form::label('integrante2', 'Integrante 2', array('class' => '')); !!}
                        </div>
                    </div>        
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('relacionIntegrante2', $alumnoEntrevista->relacionIntegrante2, array('id' => 'relacionIntegrante2', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('relacionIntegrante2', 'Relación integrante 2', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::number('edadintegrante2', $alumnoEntrevista->edadintegrante2, array('id' => 'edadintegrante2', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadintegrante2', 'Edad integrante 2', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('ocupacionIntegrante2', $alumnoEntrevista->ocupacionIntegrante2, array('id' => 'ocupacionIntegrante2', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionIntegrante2', 'Ocupación integrante 2', array('class' => '')); !!}
                        </div>
                    </div>            
                </div>

                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('conQuienViveAlumno', $alumnoEntrevista->conQuienViveAlumno, array('id' => 'conQuienViveAlumno', 'class' => 'validate', 'maxlength'=>'100', 'required')) !!}
                            {!! Form::label('conQuienViveAlumno', '¿Con quien vivi el niño(a)? *', array('class' => '')); !!}
                        </div>
                    </div>  
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('direccionViviendaAlumno', $alumnoEntrevista->direccionViviendaAlumno, array('id' => 'direccionViviendaAlumno', 'class' => 'validate', 'maxlength'=>'100', 'required')) !!}
                            {!! Form::label('direccionViviendaAlumno', 'Dirección donde vivie el alumno *', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 m12">
                        <div class="input-field">
                            <label for="situcionLegal">Situación legal: <b>*Entregar copia simple que avale el proceso en todos los casos de Guarda y
                                Custodia que ya haya tenido una sentencia definitiva o se encuentren en un proceso legal.</b></label>
                            <textarea id="situcionLegal" name="situcionLegal" class="materialize-textarea validate">{{$alumnoEntrevista->situcionLegal}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 m6">
                        <div class="input-field">
                            <label for="descripcionNinio">¿Cómo describen los padres al niño/a?</label>
                            <textarea id="descripcionNinio" name="descripcionNinio" class="materialize-textarea validate">{{$alumnoEntrevista->descripcionNinio}}</textarea>
                        </div>
                    </div>

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('apoyoTarea', $alumnoEntrevista->apoyoTarea, array('id' => 'apoyoTarea', 'class' => 'validate', 'required', 'maxlength'=>'50')) !!}
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
                            {!! Form::text('escuelaAnterior', $alumnoEntrevista->escuelaAnterior, array('id' => 'escuelaAnterior', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('escuelaAnterior', 'Nombre de la escuela anterior', array('class' => '')); !!}
                        </div>
                    </div>  

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('aniosEstudiados', $alumnoEntrevista->aniosEstudiados, array('id' => 'aniosEstudiados', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('aniosEstudiados', 'Años estudiados en la escuela anterior', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('motivosCambioEscuela', $alumnoEntrevista->motivosCambioEscuela, array('id' => 'motivosCambioEscuela', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('motivosCambioEscuela', 'Motivos del cambio de escuela', array('class' => '')); !!}
                        </div>
                    </div>  
                </div>


                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('kinder', $alumnoEntrevista->kinder, array('id' => 'kinder', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('kinder', 'Kínder', array('class' => '')); !!}
                        </div>
                    </div>  

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('observacionEscolar', $alumnoEntrevista->observacionEscolar, array('id' => 'observacionEscolar', 'class' => 'validate', 'maxlength'=>'9000')) !!}
                            {!! Form::label('observacionEscolar', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>Primaria</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio1">Promedio en 1º</label>
                            <input type="number" name="promedio1" id="promedio1" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio1}}">
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio2">Promedio en 2º</label>
                            <input type="number" name="promedio2" id="promedio2" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio2}}">
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio3">Promedio en 3º</label>
                            <input type="number" name="promedio3" id="promedio3" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio3}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio4">Promedio en 4º</label>
                            <input type="number" name="promedio4" id="promedio4" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio4}}">
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio5">Promedio en 5º</label>
                            <input type="number" name="promedio5" id="promedio5" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio5}}">
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio6">Promedio en 6º</label>
                            <input type="number" name="promedio6" id="promedio6" max="10" min="0" step="0.0" value="{{$alumnoEntrevista->promedio6}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('recursamientoGrado', $alumnoEntrevista->recursamientoGrado, array('id' => 'recursamientoGrado', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('recursamientoGrado', 'Recursamiento de algún grado', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('deportes', $alumnoEntrevista->deportes, array('id' => 'deportes', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('deportes', 'Deporte (s) o actividad cultural que practica', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('apoyoPedagogico', '¿Ha recibido su hijo(a) apoyo pedagógico en algún grado escolar? *', ['class' => '']); !!}
                        <select name="apoyoPedagogico" id="apoyoPedagogico" class="browser-default validate select2" style="width: 100%;" required>
                            {{-- <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option> --}}
                            <option value="NO" {{ $alumnoEntrevista->apoyoPedagogico == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->apoyoPedagogico == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsPedagogico', $alumnoEntrevista->obsPedagogico, array('id' => 'obsPedagogico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('obsPedagogico', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('terapiaLenguaje', '¿Ha recibido su hijo(a) terapia de lenguaje en algún grado escolar? *', ['class' => '']); !!}
                        <select name="terapiaLenguaje" id="terapiaLenguaje" class="browser-default validate select2" style="width: 100%;" required>
                            {{-- <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option> --}}
                            <option value="NO" {{ $alumnoEntrevista->terapiaLenguaje == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->terapiaLenguaje == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTerapiaLenguaje', $alumnoEntrevista->obsTerapiaLenguaje, array('id' => 'obsTerapiaLenguaje', 'class' => 'validate', 'maxlength'=>'255')) !!}
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
                        <select name="" id="tratamientoMedico" class="browser-default validate select2" style="width: 100%;" required>
                            {{-- <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option> --}}
                            <option value="NO" {{ $alumnoEntrevista->tratamientoMedico == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->tratamientoMedico == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratamientoMedico', $alumnoEntrevista->obsTratamientoMedico, array('id' => 'obsTratamientoMedico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('obsTratamientoMedico', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>¿Actualmente presenta algún padecimiento?</p>

                <div class="row">
                    <div class="col s12 m6 l3">
                        {!! Form::label('hemofilia', 'Hemofilia *', ['class' => '']); !!}
                        <select name="hemofilia" id="hemofilia" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->hemofilia == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->hemofilia == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('epilepsia', 'Epilepsia *', ['class' => '']); !!}
                        <select name="epilepsia" id="epilepsia" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->epilepsia == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->epilepsia == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('kawasaqui', 'Kawasaqui *', ['class' => '']); !!}
                        <select name="kawasaqui" id="kawasaqui" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->kawasaqui == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->kawasaqui == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('asma', 'Asma *', ['class' => '']); !!}
                        <select name="asma" id="asma" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->asma == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->asma == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l3">
                        {!! Form::label('diabetes', 'Diabetes *', ['class' => '']); !!}
                        <select name="diabetes" id="diabetes" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->diabetes == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->diabetes == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('cardiaco', 'Cardiaco *', ['class' => '']); !!}
                        <select name="cardiaco" id="cardiaco" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->cardiaco == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->cardiaco == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('dermatologico', 'Dermatológico *', ['class' => '']); !!}
                        <select name="dermatologico" id="dermatologico" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->dermatologico == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->dermatologico == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('alergias', 'Alergias *', ['class' => '']); !!}
                        <select name="alergias" id="alergias" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ $alumnoEntrevista->alergias == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ $alumnoEntrevista->alergias == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('otroTratamiento',  $alumnoEntrevista->otroTratamiento, array('id' => 'otroTratamiento', 'class' => 'validate', 'maxlength'=>'50')) !!}
                            {!! Form::label('otroTratamiento', 'Otro tratamiento', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tomaMedicamento', $alumnoEntrevista->tomaMedicamento, array('id' => 'tomaMedicamento', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('tomaMedicamento', '¿Toma algún medicamento?', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tomaMedicamento', $alumnoEntrevista->tomaMedicamento, array('id' => 'cuidadoEspecifico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('cuidadoEspecifico', '¿Requiere algún cuidado específico? ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>¿Ha recibido su hijo(a) tratamiento?</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('tratimientoNeurologico', 'Neurológico *', ['class' => '']); !!}
                        <select name="tratimientoNeurologico" id="tratimientoNeurologico" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{  $alumnoEntrevista->tratimientoNeurologico == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{  $alumnoEntrevista->tratimientoNeurologico == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratimientoNeurologico', $alumnoEntrevista->obsTratimientoNeurologico, array('id' => 'obsTratimientoNeurologico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('obsTratimientoNeurologico', 'Observaciones ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('tratamientoPsicologico', 'Psicológico *', ['class' => '']); !!}
                        <select name="tratamientoPsicologico" id="tratamientoPsicologico" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{$alumnoEntrevista->tratamientoPsicologico == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{$alumnoEntrevista->tratamientoPsicologico == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratimientoPsicologico', $alumnoEntrevista->obsTratimientoPsicologico, array('id' => 'obsTratimientoPsicologico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('obsTratimientoPsicologico', 'Observaciones ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('medicoTratante', $alumnoEntrevista->medicoTratante, array('id' => 'medicoTratante', 'class' => 'validate', 'maxlength'=>'100')) !!}
                            {!! Form::label('medicoTratante', 'Médico tratante', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l4">
                        {!! Form::label('llevarAlNinio', 'En caso de no encontrar al padre la escuela llevara al niño *', array('class' => '')); !!}
                        <select name="llevarAlNinio" id="llevarAlNinio" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{$alumnoEntrevista->llevarAlNinio == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{$alumnoEntrevista->llevarAlNinio == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div> 
                </div>

                <p><b>*Entregar una copia simple del último diagnóstico y/o tratamiento de todo aquel niño que presente algún tipo de enfermedad, padecimiento o condición de salud. </b></p>

                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('motivoInscripcionEscuela', $alumnoEntrevista->motivoInscripcionEscuela, array('id' => 'motivoInscripcionEscuela', 'class' => 'validate', 'maxlength'=>'255')) !!}
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
                            {!! Form::text('conocidoEscuela1', $alumnoEntrevista->conocidoEscuela1, array('id' => 'conocidoEscuela1', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('conocidoEscuela1', 'Familiar o conocido 1', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('conocidoEscuela2', $alumnoEntrevista->conocidoEscuela2, array('id' => 'conocidoEscuela2', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('conocidoEscuela2', 'Familiar o conocido 2', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('conocidoEscuela3', $alumnoEntrevista->conocidoEscuela3, array('id' => 'conocidoEscuela3', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('conocidoEscuela3', 'Familiar o conocido 3', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>Nombre y teléfono de familiares o conocidos a quien se le pueda pedir referencia</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia1', $alumnoEntrevista->referencia1, array('id' => 'referencia1', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('referencia1', 'Nombre completo referencia 1', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularReferencia1', $alumnoEntrevista->celularReferencia1, array('id' => 'celularReferencia1', 'class' => 'validate', 'maxlength'=>'10')) !!}
                            {!! Form::label('celularReferencia1', 'Celular referencia 1', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia2', $alumnoEntrevista->referencia2, array('id' => 'referencia2', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('referencia2', 'Nombre completo referencia 2', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularReferencia2', $alumnoEntrevista->celularReferencia2, array('id' => 'celularReferencia2', 'class' => 'validate', 'maxlength'=>'10')) !!}
                            {!! Form::label('celularReferencia2', 'Celular referencia 2', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia3', $alumnoEntrevista->referencia3, array('id' => 'referencia3', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('referencia3', 'Nombre completo referencia 3', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularReferencia3',  $alumnoEntrevista->celularReferencia3, array('id' => 'celularReferencia3', 'class' => 'validate', 'maxlength'=>'10')) !!}
                            {!! Form::label('celularReferencia3', 'Celular referencia 3', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('obsGenerales', $alumnoEntrevista->obsGenerales, array('id' => 'obsGenerales', 'class' => 'validate', 'maxlength'=>'600')) !!}
                            {!! Form::label('obsGenerales', 'OBSERVACIONES GENERALES', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <br>
                <br>
                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('entrevistador', $alumnoEntrevista->entrevistador, array('id' => 'entrevistador', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('entrevistador', 'ENTREVISTADOR', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>



@endsection

@section('footer_scripts')

@include('primaria.entrevista_inicial.traerDatos')



@endsection
