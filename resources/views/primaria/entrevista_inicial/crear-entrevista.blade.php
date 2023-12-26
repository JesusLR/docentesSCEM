@extends('layouts.dashboard')

@section('template_title')
    Primaria entrevista inicial
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_curso')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_entrevista_inicial')}}" class="breadcrumb">Listado de entrevista inicial</a>
    <a href="{{url('primaria_entrevista_inicial')}}" class="breadcrumb">Agregar entrevista inicial</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_entrevista_inicial.guardarEntrevista', 'method' => 'POST']) !!}
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
                            <select id="alumno_id" class="browser-default validate select2" name="alumno_id" style="width: 100%;" required {{old('alumno_id')}}>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach ($alumnos as $alumno)
                                    <option value="{{ $alumno->alumno_id }}" {{ old('alumno_id') == $alumno->alumno_id ? 'selected' : '' }}>Clave pago: {{$alumno->aluClave}}, Nombre: {{$alumno->perNombre}} {{$alumno->perApellido1}} {{$alumno->perApellido2}}</option>
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
                            {!! Form::number('gradoInscrito', NULL, array('id' => 'gradoInscrito', 'class' => 'validate', 'min'=>'0', 'max'=>'6')) !!}
                            {!! Form::label('gradoInscrito', 'Grado al que se inscribe', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tiempoResidencia', NULL, array('id' => 'tiempoResidencia', 'class' => 'validate', 'maxlength'=>'25')) !!}
                            {!! Form::label('tiempoResidencia', 'Si proviene de otra ciudad ¿Cuánto tiempo tiene de residir en Mérida?', array('class' => '')); !!}
                        </div>
                    </div>

                     
                </div>

                {{-- <div class="row">

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expAlergias', NULL, array('id' => 'expAlergias', 'class' => 'validate')) !!}
                            {!! Form::label('expAlergias', 'Alergias', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expEscuelaProcedencia', NULL, array('id' => 'expEscuelaProcedencia', 'class' => 'validate','required','maxlength'=>'250')) !!}
                            {!! Form::label('expEscuelaProcedencia', 'Escuela de procedencia *', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expGradosCursados', NULL, array('id' => 'expGradosCursados', 'class' => 'validate','required','maxlength'=>'250')) !!}
                            {!! Form::label('expGradosCursados', 'Grados ya cursados *', array('class' => '')); !!}
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="row">

                    <div class="col s12 m6 l4">
                        {!! Form::label('expAnioRecursado', '¿Ha recursado algún año o le han sugerido? *', array('class' => '')); !!}
                        <select id="expAnioRecursado" class="browser-default validate" required name="expAnioRecursado" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="SI" {{ old('expAnioRecursado') == "SI" ? 'selected' : '' }}>SI</option>
                            <option value="NO" {{ old('expAnioRecursado') == "NO" ? 'selected' : '' }}>NO</option>
                        </select>
                    </div>
                </div> --}}

                <br>

                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos del padre</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('nombrePadre', NULL, array('id' => 'nombrePadre', 'class' => 'validate','required','maxlength'=>'80')) !!}
                            {!! Form::label('nombrePadre', 'Nombre(s)', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido1Padre', NULL, array('id' => 'apellido1Padre', 'class' => 'validate','required','maxlength'=>'40')) !!}
                            {!! Form::label('apellido1Padre', 'Apellido 1 *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido2Padre', NULL, array('id' => 'apellido2Padre', 'class' => 'validate','maxlength'=>'40')) !!}
                            {!! Form::label('apellido2Padre', 'Apellido 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('edadPadre', NULL, array('id' => 'edadPadre', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadPadre', 'Edad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularPadre', NULL, array('id' => 'celularPadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('celularPadre', 'Celular', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ocupacionPadre', NULL, array('id' => 'ocupacionPadre', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionPadre', 'Ocupación', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empresaPadre', NULL, array('id' => 'empresaPadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('empresaPadre', 'Empresa', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('correoPadre', NULL, array('id' => 'correoPadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
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
                            {!! Form::text('nombreMadre', NULL, array('id' => 'nombreMadre', 'class' => 'validate','required','maxlength'=>'80')) !!}
                            {!! Form::label('nombreMadre', 'Nombre(s)', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido1Madre', NULL, array('id' => 'apellido1Madre', 'class' => 'validate','required','maxlength'=>'40')) !!}
                            {!! Form::label('apellido1Madre', 'Apellido 1 *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('apellido2Madre', NULL, array('id' => 'apellido2Madre', 'class' => 'validate','maxlength'=>'40')) !!}
                            {!! Form::label('apellido2Madre', 'Apellido 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('edadMadre', NULL, array('id' => 'edadMadre', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadMadre', 'Edad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularMadre', NULL, array('id' => 'celularMadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('celularMadre', 'Celular', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ocupacionMadre', NULL, array('id' => 'ocupacionMadre', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionMadre', 'Ocupación', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empresaMadre', NULL, array('id' => 'empresaMadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
                            {!! Form::label('empresaMadre', 'Empresa', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('correoMadre', NULL, array('id' => 'correoMadre', 'class' => 'validate', 'maxlength'=>'80')) !!}
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
                            <option value="CASADOS" {{ old('estadoCivilPadres') == "CASADOS" ? 'selected' : '' }}>Casados</option>
                            <option value="DIVORCIADOS" {{ old('estadoCivilPadres') == "DIVORCIADOS" ? 'selected' : '' }}>Divorciados</option>
                            <option value="SEPARADOS" {{ old('estadoCivilPadres') == "SEPARADOS" ? 'selected' : '' }}>Separados</option>
                        </select>
                    </div>
                   
                    {{-- ¿Tienen alguna religión? ¿Cuál? * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('religion', NULL, array('id' => 'religion', 'class' => 'validate','maxlength'=>'50')) !!}
                            {!! Form::label('religion', 'Religión', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <textarea id="observaciones" name="observaciones" class="materialize-textarea"></textarea>
                            {!! Form::label('observaciones', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div>                    
                </div>

                
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="input-field">
                            <textarea id="condicionFamiliar" name="condicionFamiliar" class="materialize-textarea"></textarea>
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
                            {!! Form::text('tutorResponsable', NULL, array('id' => 'tutorResponsable', 'required', 'class' => 'validate','maxlength'=>'80')) !!}
                            {!! Form::label('tutorResponsable', 'Padre o tutor responsable financiero *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularTutor', NULL, array('id' => 'celularTutor', 'class' => 'validate', 'required', 'maxlength'=>'10')) !!}
                            {!! Form::label('celularTutor', 'Celular *', array('class' => '')); !!}
                        </div>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('accidenteLlamar', NULL, array('id' => 'accidenteLlamar', 'class' => 'validate', 'required', 'maxlength'=>'200')) !!}
                            {!! Form::label('accidenteLlamar', 'En caso de algún accidente se deberá llamar a *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('celularAccidente', NULL, array('id' => 'celularAccidente', 'class' => 'validate', 'required', 'maxlength'=>'10')) !!}
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
                            {!! Form::text('integrante1', NULL, array('id' => 'integrante1', 'class' => 'validate','maxlength'=>'255')) !!}
                            {!! Form::label('integrante1', 'Integrante 1', array('class' => '')); !!}
                        </div>
                    </div>        
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('relacionIntegrante1', NULL, array('id' => 'relacionIntegrante1', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('relacionIntegrante1', 'Relación integrante 1', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::number('edadintegrante1', NULL, array('id' => 'edadintegrante1', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadintegrante1', 'Edad integrante 1', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('ocupacionIntegrante1', NULL, array('id' => 'ocupacionIntegrante1', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionIntegrante1', 'Ocupación integrante 1', array('class' => '')); !!}
                        </div>
                    </div>            
                </div>

                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('integrante2', NULL, array('id' => 'integrante2', 'class' => 'validate','maxlength'=>'255')) !!}
                            {!! Form::label('integrante2', 'Integrante 2', array('class' => '')); !!}
                        </div>
                    </div>        
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('relacionIntegrante2', NULL, array('id' => 'relacionIntegrante2', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('relacionIntegrante2', 'Relación integrante 2', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::number('edadintegrante2', NULL, array('id' => 'edadintegrante2', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('edadintegrante2', 'Edad integrante 2', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('ocupacionIntegrante2', NULL, array('id' => 'ocupacionIntegrante2', 'class' => 'validate', 'maxlength'=>'40')) !!}
                            {!! Form::label('ocupacionIntegrante2', 'Ocupación integrante 2', array('class' => '')); !!}
                        </div>
                    </div>            
                </div>

                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('conQuienViveAlumno', old('conQuienViveAlumno'), array('id' => 'conQuienViveAlumno', 'class' => 'validate', 'maxlength'=>'100', 'required')) !!}
                            {!! Form::label('conQuienViveAlumno', '¿Con quien vivi el niño(a)? *', array('class' => '')); !!}
                        </div>
                    </div>  
                    <div class="col s12 m6 l3">
                        <div class="input-field">
                            {!! Form::text('direccionViviendaAlumno', old('direccionViviendaAlumno'), array('id' => 'direccionViviendaAlumno', 'class' => 'validate', 'maxlength'=>'100', 'required')) !!}
                            {!! Form::label('direccionViviendaAlumno', 'Dirección donde vivie el alumno *', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 m12">
                        <div class="input-field">
                            <label for="situcionLegal">Situación legal: <b>*Entregar copia simple que avale el proceso en todos los casos de Guarda y
                                Custodia que ya haya tenido una sentencia definitiva o se encuentren en un proceso legal.</b></label>
                            <textarea id="situcionLegal" name="situcionLegal" class="materialize-textarea validate"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 m6">
                        <div class="input-field">
                            <label for="descripcionNinio">¿Cómo describen los padres al niño/a?</label>
                            <textarea id="descripcionNinio" name="descripcionNinio" class="materialize-textarea validate"></textarea>
                        </div>
                    </div>

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('apoyoTarea', NULL, array('id' => 'apoyoTarea', 'class' => 'validate', 'required', 'maxlength'=>'50')) !!}
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
                            {!! Form::text('escuelaAnterior', NULL, array('id' => 'escuelaAnterior', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('escuelaAnterior', 'Nombre de la escuela anterior', array('class' => '')); !!}
                        </div>
                    </div>  

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('aniosEstudiados', NULL, array('id' => 'aniosEstudiados', 'class' => 'validate', 'maxlength'=>'3')) !!}
                            {!! Form::label('aniosEstudiados', 'Años estudiados en la escuela anterior', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('motivosCambioEscuela', NULL, array('id' => 'motivosCambioEscuela', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('motivosCambioEscuela', 'Motivos del cambio de escuela', array('class' => '')); !!}
                        </div>
                    </div>  
                </div>


                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('kinder', NULL, array('id' => 'kinder', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('kinder', 'Kínder', array('class' => '')); !!}
                        </div>
                    </div>  

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('observacionEscolar', NULL, array('id' => 'observacionEscolar', 'class' => 'validate', 'maxlength'=>'9000')) !!}
                            {!! Form::label('observacionEscolar', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>Primaria</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio1">Promedio en 1º</label>
                            <input type="number" name="promedio1" id="promedio1" max="10" min="0" step="0.0">
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio2">Promedio en 2º</label>
                            <input type="number" name="promedio2" id="promedio2" max="10" min="0" step="0.0">
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio3">Promedio en 3º</label>
                            <input type="number" name="promedio3" id="promedio3" max="10" min="0" step="0.0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio4">Promedio en 4º</label>
                            <input type="number" name="promedio4" id="promedio4" max="10" min="0" step="0.0">
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio5">Promedio en 5º</label>
                            <input type="number" name="promedio5" id="promedio5" max="10" min="0" step="0.0">
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="promedio6">Promedio en 6º</label>
                            <input type="number" name="promedio6" id="promedio6" max="10" min="0" step="0.0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('recursamientoGrado', NULL, array('id' => 'recursamientoGrado', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('recursamientoGrado', 'Recursamiento de algún grado', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('deportes', NULL, array('id' => 'deportes', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('deportes', 'Deporte (s) o actividad cultural que practica', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('apoyoPedagogico', '¿Ha recibido su hijo(a) apoyo pedagógico en algún grado escolar? *', ['class' => '']); !!}
                        <select name="apoyoPedagogico" id="apoyoPedagogico" class="browser-default validate select2" style="width: 100%;" required>
                            {{-- <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option> --}}
                            <option value="NO" {{ old('apoyoPedagogico') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('apoyoPedagogico') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsPedagogico', NULL, array('id' => 'obsPedagogico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('obsPedagogico', 'Observaciones', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('terapiaLenguaje', '¿Ha recibido su hijo(a) terapia de lenguaje en algún grado escolar? *', ['class' => '']); !!}
                        <select name="terapiaLenguaje" id="terapiaLenguaje" class="browser-default validate select2" style="width: 100%;" required>
                            {{-- <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option> --}}
                            <option value="NO" {{ old('terapiaLenguaje') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('terapiaLenguaje') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTerapiaLenguaje', NULL, array('id' => 'obsTerapiaLenguaje', 'class' => 'validate', 'maxlength'=>'255')) !!}
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
                        <select name="tratamientoMedico" id="tratamientoMedico" class="browser-default validate select2" style="width: 100%;" required>
                            {{-- <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option> --}}
                            <option value="NO" {{ old('tratamientoMedico') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('tratamientoMedico') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratamientoMedico', NULL, array('id' => 'obsTratamientoMedico', 'class' => 'validate', 'maxlength'=>'255')) !!}
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
                            <option value="NO" {{ old('hemofilia') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('hemofilia') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('epilepsia', 'Epilepsia *', ['class' => '']); !!}
                        <select name="epilepsia" id="epilepsia" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('epilepsia') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('epilepsia') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('kawasaqui', 'Kawasaqui *', ['class' => '']); !!}
                        <select name="kawasaqui" id="kawasaqui" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('kawasaqui') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('kawasaqui') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('asma', 'Asma *', ['class' => '']); !!}
                        <select name="asma" id="asma" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('asma') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('asma') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l3">
                        {!! Form::label('diabetes', 'Diabetes *', ['class' => '']); !!}
                        <select name="diabetes" id="diabetes" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('diabetes') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('diabetes') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('cardiaco', 'Cardiaco *', ['class' => '']); !!}
                        <select name="cardiaco" id="cardiaco" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('cardiaco') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('cardiaco') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('dermatologico', 'Dermatológico *', ['class' => '']); !!}
                        <select name="dermatologico" id="dermatologico" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('dermatologico') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('dermatologico') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        {!! Form::label('alergias', 'Alergias *', ['class' => '']); !!}
                        <select name="alergias" id="alergias" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('alergias') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('alergias') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('otroTratamiento', NULL, array('id' => 'otroTratamiento', 'class' => 'validate', 'maxlength'=>'50')) !!}
                            {!! Form::label('otroTratamiento', 'Otro tratamiento', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('tomaMedicamento', NULL, array('id' => 'tomaMedicamento', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('tomaMedicamento', '¿Toma algún medicamento?', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('cuidadoEspecifico', NULL, array('id' => 'cuidadoEspecifico', 'class' => 'validate', 'maxlength'=>'255')) !!}
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
                            <option value="NO" {{ old('tratimientoNeurologico') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('tratimientoNeurologico') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratimientoNeurologico', NULL, array('id' => 'obsTratimientoNeurologico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('obsTratimientoNeurologico', 'Observaciones ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('tratamientoPsicologico', 'Psicológico *', ['class' => '']); !!}
                        <select name="tratamientoPsicologico" id="tratamientoPsicologico" class="browser-default validate select2" style="width: 100%;" required>
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="NO" {{ old('tratamientoPsicologico') == "NO" ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('tratamientoPsicologico') == "SI" ? 'selected' : '' }}>SI</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('obsTratimientoPsicologico', NULL, array('id' => 'obsTratimientoPsicologico', 'class' => 'validate', 'maxlength'=>'255')) !!}
                            {!! Form::label('obsTratimientoPsicologico', 'Observaciones ', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('medicoTratante', old('medicoTratante'), array('id' => 'medicoTratante', 'class' => 'validate', 'maxlength'=>'100')) !!}
                            {!! Form::label('medicoTratante', 'Médico tratante', array('class' => '')); !!}
                        </div>
                    </div> 

                    <div class="col s12 m6 l4">
                        {{--  <div class="input-field">  --}}
                            {!! Form::label('llevarAlNinio', 'En caso de no encontrar al padre la escuela llevara al niño', array('class' => '')); !!}
                            <select name="llevarAlNinio" id="llevarAlNinio" class="browser-default validate select2" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                <option value="NO" {{ old('llevarAlNinio') == "NO" ? 'selected' : '' }}>NO</option>
                                <option value="SI" {{ old('llevarAlNinio') == "SI" ? 'selected' : '' }}>SI</option>
                            </select>
                        {{--  </div>  --}}
                    </div> 
                </div>

                <p><b>*Entregar una copia simple del último diagnóstico y/o tratamiento de todo aquel niño que presente algún tipo de enfermedad, padecimiento o condición de salud. </b></p>

                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('motivoInscripcionEscuela', NULL, array('id' => 'motivoInscripcionEscuela', 'class' => 'validate', 'maxlength'=>'255')) !!}
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
                            {!! Form::text('conocidoEscuela1', NULL, array('id' => 'conocidoEscuela1', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('conocidoEscuela1', 'Familiar o conocido 1', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('conocidoEscuela2', NULL, array('id' => 'conocidoEscuela2', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('conocidoEscuela2', 'Familiar o conocido 2', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('conocidoEscuela3', NULL, array('id' => 'conocidoEscuela3', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('conocidoEscuela3', 'Familiar o conocido 3', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <p>Nombre y teléfono de familiares o conocidos a quien se le pueda pedir referencia</p>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia1', NULL, array('id' => 'referencia1', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('referencia1', 'Nombre completo referencia 1', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('cecularReferencia1', NULL, array('id' => 'cecularReferencia1', 'class' => 'validate', 'maxlength'=>'10')) !!}
                            {!! Form::label('cecularReferencia1', 'Celular referencia 1', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia2', NULL, array('id' => 'referencia2', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('referencia2', 'Nombre completo referencia 2', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('cecularReferencia2', NULL, array('id' => 'cecularReferencia2', 'class' => 'validate', 'maxlength'=>'10')) !!}
                            {!! Form::label('cecularReferencia2', 'Celular referencia 2', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('referencia3', NULL, array('id' => 'referencia3', 'class' => 'validate', 'maxlength'=>'200')) !!}
                            {!! Form::label('referencia3', 'Nombre completo referencia 3', array('class' => '')); !!}
                        </div>
                    </div> 
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('cecularReferencia3', NULL, array('id' => 'cecularReferencia3', 'class' => 'validate', 'maxlength'=>'10')) !!}
                            {!! Form::label('cecularReferencia3', 'Celular referencia 3', array('class' => '')); !!}
                        </div>
                    </div>                     
                </div>

                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('obsGenerales', NULL, array('id' => 'obsGenerales', 'class' => 'validate', 'maxlength'=>'600')) !!}
                            {!! Form::label('obsGenerales', 'OBSERVACIONES GENERALES', array('class' => '')); !!}
                        </div>
                    </div> 
                </div>

                <br>
                <br>
                <div class="row">
                    <div class="col s12 m6 l12">
                        <div class="input-field">
                            {!! Form::text('entrevistador', $empleado, array('id' => 'entrevistador', 'class' => 'validate', 'maxlength'=>'200', 'readonly')) !!}
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
