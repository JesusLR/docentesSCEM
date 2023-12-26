@extends('layouts.dashboard')

@section('template_title')
    Entrevista
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_curso')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_entrevista_inicial')}}" class="breadcrumb">Agregar entrevista</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_entrevista_inicial.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">ENTREVISTA INICIAL</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">Datos básicos para la entrevista inicial - Ingreso a Primaria de la Escuela Modelo</a></li>
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">
                <br>
                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos generales del alumno(a)</p>
                </div>

                <div class="row">
                    {{-- Nombre alumno  --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expNombreAlumno', NULL, array('id' => 'expNombreAlumno', 'class' => 'validate', 'required', 'maxlength'=>'80', 'old("expNombreAlumno")')) !!}
                            {!! Form::label('expNombreAlumno', 'Nombre(s)*', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Apellido paterno  --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expApellidoAlumno1', NULL, array('id' => 'expApellidoAlumno1', 'class' => 'validate', 'required', 'maxlength'=>'40')) !!}
                            {!! Form::label('expApellidoAlumno1', 'Apellido paterno*', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Apellido materno  --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expApellidoAlumno2', NULL, array('id' => 'expApellidoAlumno2', 'class' => 'validate', 'required', 'maxlength'=>'40')) !!}
                            {!! Form::label('expApellidoAlumno2', 'Apellido materno*', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Fecha de nacimiento  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('expFechaNacAlumno', 'Fecha de nacimiento *', array('class' => '')); !!}
                        {!! Form::date('expFechaNacAlumno', NULL, array('id' => 'expFechaNacAlumno', 'class' => 'validate','required','maxlength'=>'250')) !!}
                    </div>
                </div>

                <div class="row">
                    {{-- Pais  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('paisAlumnoId', 'País*', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="paisAlumnoId" class="browser-default validate select2" name="paisAlumnoId" style="width: 100%;" required {{old('paisAlumnoId')}}>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach ($paises as $itemPais)
                                    <option value="{{ $itemPais->id }}" {{ old('paisAlumnoId') == $itemPais->id ? 'selected' : '' }}>{{ $itemPais->paisNombre }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    {{-- Estado   --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('estadoAlumno_id', 'Estado*', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="estadoAlumno_id" class="browser-default validate select2" name="estadoAlumno_id" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>
                    </div>
                    {{-- Municipio  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('expMunicioAlumno_id', 'Municipio *', array('class' => '')); !!}
                        <div style="position:relative;">
                            <select id="expMunicioAlumno_id"
                                class="browser-default validate select2" name="expMunicioAlumno_id" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- curp  --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expCurpAlumno', NULL, array('id' => 'expCurpAlumno', 'class' => 'validate', 'required', 'maxlength'=>'18')) !!}
                            {!! Form::label('expCurpAlumno', 'CURP *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- edad del alumno año y meses --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expEdadAlumno', NULL, array('id' => 'expEdadAlumno', 'class' => 'validate', 'required', 'maxlength'=>'25')) !!}
                            {!! Form::label('expEdadAlumno', 'Edad actual (Años y meses) *', array('class' => '')); !!}
                        </div>
                    </div>

                     {{-- tipo de sangre --}}
                     <div class="col s12 m6 l4">
                        {!! Form::label('expTipoSangre', 'Tipo de sangre *', array('class' => '')); !!}
                        <select id="expTipoSangre" class="browser-default validate" required name="expTipoSangre" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="O NEGATIVO" {{ old('expTipoSangre') == 'O NEGATIVO' ? 'selected' : '' }}>O negativo</option>
                            <option value="O POSITIVO" {{ old('expTipoSangre') == "O POSITIVO" ? 'selected' : '' }}>O positivo</option>
                            <option value="A NEGATIVO" {{ old('expTipoSangre') == "A NEGATIVO" ? 'selected' : '' }}>A negativo</option>
                            <option value="A POSITIVO" {{ old('expTipoSangre') == "A POSITIVO" ? 'selected' : '' }}>A positivo</option>
                            <option value="B NEGATIVO" {{ old('expTipoSangre') == "B NEGATIVO" ? 'selected' : '' }}>B negativo</option>
                            <option value="B POSITIVO" {{ old('expTipoSangre') == "B POSITIVO" ? 'selected' : '' }}>B positivo</option>
                            <option value="AB NEGATIVO" {{ old('expTipoSangre') == "AB NEGATIVO" ? 'selected' : '' }}>AB negativo</option>
                            <option value="AB POSITIVO" {{ old('expTipoSangre') == "AB POSITIVO" ? 'selected' : '' }}>AB positivo</option>
                        </select>
                    </div>
                </div>

                <div class="row">

                    {{-- Alergias --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expAlergias', NULL, array('id' => 'expAlergias', 'class' => 'validate')) !!}
                            {!! Form::label('expAlergias', 'Alergias', array('class' => '')); !!}
                        </div>
                    </div>

                    {{-- Escuela de procedencia --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expEscuelaProcedencia', NULL, array('id' => 'expEscuelaProcedencia', 'class' => 'validate','required','maxlength'=>'250')) !!}
                            {!! Form::label('expEscuelaProcedencia', 'Escuela de procedencia *', array('class' => '')); !!}
                        </div>
                    </div>

                    {{-- Grados ya cursados --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expGradosCursados', NULL, array('id' => 'expGradosCursados', 'class' => 'validate','required','maxlength'=>'250')) !!}
                            {!! Form::label('expGradosCursados', 'Grados ya cursados *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col s12 m6 l4">
                        {!! Form::label('expAnioRecursado', '¿Ha recursado algún año o le han sugerido? *', array('class' => '')); !!}
                        <select id="expAnioRecursado" class="browser-default validate" required name="expAnioRecursado" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="SI" {{ old('expAnioRecursado') == "SI" ? 'selected' : '' }}>SI</option>
                            <option value="NO" {{ old('expAnioRecursado') == "NO" ? 'selected' : '' }}>NO</option>
                        </select>
                    </div>
                </div>

                <br>

                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos de la Madre o tutor(a)</p>
                </div>

                <div class="row">
                    {{-- Nombre completo de Madre o tutor(a) 1 * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expTutorMadre', NULL, array('id' => 'expTutorMadre', 'class' => 'validate','required','maxlength'=>'250')) !!}
                            {!! Form::label('expTutorMadre', 'Nombre completo de Madre o tutor(a) 1 *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Edad --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('expEdadTutorMadre', NULL, array('id' => 'expEdadTutorMadre', 'required', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                            {!! Form::label('expEdadTutorMadre', 'Edad *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Fecha de nacimiento tutor  --}}
                    <div class="col s12 m6 l4">
                            {!! Form::label('expFechaNacimientoTutorMadre', 'Fecha de nacimiento *', array('class' => '')); !!}
                            {!! Form::date('expFechaNacimientoTutorMadre', NULL, array('id' => 'expFechaNacimientoTutorMadre', 'class' => 'validate','required','maxlength'=>'250')) !!}
                    </div>
                </div>

                <div class="row">
                    {{-- Pais  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('paisId', 'País*', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="paisId" class="browser-default validate select2" name="paisId" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach ($paises as $itemPais)
                                    <option value="{{ $itemPais->id }}" {{ old('paisId') == $itemPais->id ? 'selected' : '' }}>{{ $itemPais->paisNombre }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    {{-- Estado   --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('estado_id', 'Estado*', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="estado_id" class="browser-default validate select2" name="estado_id" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>
                    </div>
                    {{-- Municipio  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('exMunicipioMadre_id', 'Municipio *', array('class' => '')); !!}
                        <div style="position:relative;">
                            <select id="exMunicipioMadre_id"
                                class="browser-default validate select2" name="exMunicipioMadre_id" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Ocupación * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expOcupacionTutorMadre', NULL, array('id' => 'expOcupacionTutorMadre','class' => 'validate','required','maxlength'=>'50')) !!}
                            {!! Form::label('expOcupacionTutorMadre', 'Ocupación *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Empresa donde labora --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expEmpresaLaboralTutorMadre', NULL, array('id' => 'expEmpresaLaboralTutorMadre','class' => 'validate','maxlength'=>'50')) !!}
                            {!! Form::label('expEmpresaLaboralTutorMadre', 'Empresa donde labora', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Celular * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('expCelularTutorMadre', NULL, array('id' => 'expCelularTutorMadre', 'required', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                            {!! Form::label('expCelularTutorMadre', 'Celular *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Teléfono de casa --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('expTelefonoCasaTutorMadre', NULL, array('id' => 'expTelefonoCasaTutorMadre', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                            {!! Form::label('expTelefonoCasaTutorMadre', 'Teléfono de casa', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Email  --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('expEmailTutorMadre', NULL, array('id' => 'expEmailTutorMadre', 'class' => 'validate','required','maxlength'=>'80')) !!}
                            {!! Form::label('expEmailTutorMadre', 'Email *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <br>
                <div class="row" style="background-color:#ECECEC;">
                    <p style="text-align: center;font-size:1.2em;">Datos del Padre o tutor(a)</p>
                </div>

                <div class="row">
                    {{-- Nombre completo de Padre o tutor(a) 2 * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expTutorPadre', NULL, array('id' => 'expTutorPadre', 'class' => 'validate','required','maxlength'=>'250')) !!}
                            {!! Form::label('expTutorPadre', 'Nombre completo de Padre o tutor(a) 2 *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Edad --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('expEdadTutorPadre', NULL, array('id' => 'expEdadTutorPadre', 'required', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                            {!! Form::label('expEdadTutorPadre', 'Edad *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Fecha de nacimiento tutor  --}}
                    <div class="col s12 m6 l4">
                            {!! Form::label('expFechaNacimientoTutorPadre', 'Fecha de nacimiento *', array('class' => '')); !!}
                            {!! Form::date('expFechaNacimientoTutorPadre', NULL, array('id' => 'expFechaNacimientoTutorPadre', 'class' => 'validate','required','maxlength'=>'250')) !!}
                    </div>
                </div>

                <div class="row">
                    {{-- Pais  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('paisPadreId', 'País*', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="paisPadreId" class="browser-default validate select2" name="paisPadreId" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach ($paises as $itemPais)
                                    <option value="{{ $itemPais->id }}" {{ old('paisPadreId') == $itemPais->id ? 'selected' : '' }}>{{ $itemPais->paisNombre }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    {{-- Estado   --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('estadoPadre_id', 'Estado*', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="estadoPadre_id" class="browser-default validate select2" name="estadoPadre_id" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>
                    </div>
                    {{-- Municipio  --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('expMunicipioPadre_id', 'Municipio *', array('class' => '')); !!}
                        <div style="position:relative;">
                            <select id="expMunicipioPadre_id"
                                class="browser-default validate select2" name="expMunicipioPadre_id" style="width: 100%;" required>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Ocupación * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expOcupacionTutorPadre', NULL, array('id' => 'expOcupacionTutorPadre','class' => 'validate','required','maxlength'=>'50')) !!}
                            {!! Form::label('expOcupacionTutorPadre', 'Ocupación *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Empresa donde labora --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expEmpresaLaboralTutorPadre', NULL, array('id' => 'expEmpresaLaboralTutorPadre','class' => 'validate','maxlength'=>'50')) !!}
                            {!! Form::label('expEmpresaLaboralTutorPadre', 'Empresa donde labora', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Celular * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('expCelularTutorPadre', NULL, array('id' => 'expCelularTutorPadre', 'required', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                            {!! Form::label('expCelularTutorPadre', 'Celular *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Teléfono de casa --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('expTelefonoCasaTutorPadre', NULL, array('id' => 'expTelefonoCasaTutorPadre', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                            {!! Form::label('expTelefonoCasaTutorPadre', 'Teléfono de casa', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Email  --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::email('expEmailTutorPadre', NULL, array('id' => 'expEmailTutorPadre','class' => 'validate','required','maxlength'=>'80')) !!}
                            {!! Form::label('expEmailTutorPadre', 'Email *', array('class' => '')); !!}
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
                        {!! Form::label('expEstadoCivilPadres', 'Estado civil de los padres *', array('class' => '')); !!}
                        <select id="expEstadoCivilPadres" class="browser-default validate" required name="expEstadoCivilPadres" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="CASADOS" {{ old('expEstadoCivilPadres') == "CASADOS" ? 'selected' : '' }}>Casados</option>
                            <option value="DIVORCIADOS" {{ old('expEstadoCivilPadres') == "DIVORCIADOS" ? 'selected' : '' }}>Divorciados</option>
                            <option value="SEPARADOS" {{ old('expEstadoCivilPadres') == "SEPARADOS" ? 'selected' : '' }}>Separados</option>
                            <option value="OTRO" {{ old('expEstadoCivilPadres') == "OTRO" ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    {{-- otro  --}}
                    <div class="col s12 m6 l4" id="divOtro" style="display: none">
                        <div class="input-field">
                            {!! Form::text('expEstadoCivilOtro', NULL, array('id' => 'expEstadoCivilOtro', 'class' => 'validate','maxlength'=>'40')) !!}
                            {!! Form::label('expEstadoCivilOtro', 'Espesifica el estado civil *', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- ¿Tienen alguna religión? ¿Cuál? * --}}
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('expReligonPadres', NULL, array('id' => 'expReligonPadres', 'class' => 'validate','maxlength'=>'40', 'required')) !!}
                            {!! Form::label('expReligonPadres', '¿Tienen alguna religión? ¿Cuál? *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Nombre de algún familiar o conocido, en caso de no localizar a los padres * --}}
                    <div class="col s12 m6 l8">
                        <div class="input-field">
                            {!! Form::text('expNombreFamiliar', NULL, array('id' => 'expNombreFamiliar', 'required', 'class' => 'validate','maxlength'=>'80')) !!}
                            {!! Form::label('expNombreFamiliar', 'Nombre de algún familiar o conocido, en caso de no localizar a los padres *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('expTelefonoFamiliar', NULL, array('id' => 'expTelefonoFamiliar', 'required', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                            {!! Form::label('expTelefonoFamiliar', 'Teléfono del familiar o conocido *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <p>Nombre completo de personas autorizadas para recoger al alumno en la escuela *</p>

                <div class="row">
                    {{-- Persona autorizada 1* --}}
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('expPersona1Autorizada', NULL, array('id' => 'expPersona1Autorizada', 'required', 'class' => 'validate','maxlength'=>'80')) !!}
                            {!! Form::label('expPersona1Autorizada', 'Persona autorizada 1*', array('class' => '')); !!}
                        </div>
                    </div>
                    {{-- Persona autorizada 2* --}}
                    <div class="col s12 m6 l6">
                        <div class="input-field">
                            {!! Form::text('expPersona2Autorizada', NULL, array('id' => 'expPersona2Autorizada', 'class' => 'validate','maxlength'=>'80')) !!}
                            {!! Form::label('expPersona2Autorizada', 'Persona autorizada 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- ¿Alguno de los padres es egresado de la Universidad Modelo? * --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('expPadresEgresados', '¿Alguno de los padres es egresado de la Universidad Modelo? *', array('class' => '')); !!}
                        <select id="expPadresEgresados" class="browser-default validate" required name="expPadresEgresados" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="SI" {{ old('expPadresEgresados') == "SI" ? 'selected' : '' }}>SI</option>
                            <option value="NO" {{ old('expPadresEgresados') == "NO" ? 'selected' : '' }}>NO</option>
                        </select>
                    </div>
                    {{-- ¿Cuentan con algún familiar estudiando o trabajando en esta Institución? * --}}
                    <div class="col s12 m6 l4">
                        {!! Form::label('expFamiliarModelo', '¿Cuentan con algún familiar estudiando o trabajando en la Institución?*', array('class' => '')); !!}
                        <select id="expFamiliarModelo" class="browser-default validate" required name="expFamiliarModelo" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            <option value="SI" {{ old('expFamiliarModelo') == "SI" ? 'selected' : '' }}>SI</option>
                            <option value="NO" {{ old('expFamiliarModelo') == "NO" ? 'selected' : '' }}>NO</option>
                        </select>
                    </div>

                    {{-- Si la respuesta es sí, especifique: --}}
                    <div class="col s12 m6 l4" id="divEspecifica" style="display: none; margin-top:5px">
                        <div class="input-field">
                            {!! Form::text('expNombreFamiliarModelo', NULL, array('id' => 'expNombreFamiliarModelo', 'class' => 'validate','maxlength'=>'80')) !!}
                            {!! Form::label('expNombreFamiliarModelo', 'Nombre del familiar que está laborando * ', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <br><br>

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

@include('primaria.scripts.funcionesAuxiliares')

<script>

{{-- ingresa los datos en el select de estados del alumno --}}
var paisAlumno_id = $('#paisAlumnoId').val();
paisAlumno_id ? getEstados(paisAlumno_id, 'estadoAlumno_id',
{{ (isset($candidato) && $municipio) ? $municipio->estado->id : null}}) : resetSelect('estadoAlumno_id');
$('#paisAlumnoId').on('change', function() {
    var paisAlumno_id = $(this).val();
    paisAlumno_id ? getEstados(paisAlumno_id, 'estadoAlumno_id',
    {{ (isset($candidato) && $municipio)? $municipio->estado->id : null}}) : resetSelect('estadoAlumno_id');
});

{{-- ingresa los datos en el select de municipios del alumno --}}
var estadoAlumno_id = $('#estadoAlumno_id').val();
estadoAlumno_id ? getMunicipios(estadoAlumno_id, 'expMunicioAlumno_id',
{{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('expMunicioAlumno_id');
$('#estadoAlumno_id').on('change', function() {
var estadoAlumno_id = $(this).val();
estadoAlumno_id ? getMunicipios(estadoAlumno_id, 'expMunicioAlumno_id',
{{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('expMunicioAlumno_id');
});


{{-- ingresa los datos en el select de estados de la madre --}}
var pais_id = $('#paisId').val();
pais_id ? getEstados(pais_id, 'estado_id',
{{ (isset($candidato) && $municipio) ? $municipio->estado->id : null}}) : resetSelect('estado_id');
$('#paisId').on('change', function() {
    var pais_id = $(this).val();
    pais_id ? getEstados(pais_id, 'estado_id',
    {{ (isset($candidato) && $municipio)? $municipio->estado->id : null}}) : resetSelect('estado_id');
});

{{-- ingresa los datos en el select de municipios de la madre --}}
var estado_id = $('#estado_id').val();
estado_id ? getMunicipios(estado_id, 'exMunicipioMadre_id',
{{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('exMunicipioMadre_id');
$('#estado_id').on('change', function() {
var estado_id = $(this).val();
estado_id ? getMunicipios(estado_id, 'exMunicipioMadre_id',
{{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('exMunicipioMadre_id');
});

{{-- ingresa los datos en el select de estados de la madre --}}
var paisPadre_id = $('#paisPadreId').val();
paisPadre_id ? getEstados(paisPadre_id, 'estadoPadre_id',
{{ (isset($candidato) && $municipio) ? $municipio->estado->id : null}}) : resetSelect('estadoPadre_id');
$('#paisPadreId').on('change', function() {
    var paisPadre_id = $(this).val();
    paisPadre_id ? getEstados(paisPadre_id, 'estadoPadre_id',
    {{ (isset($candidato) && $municipio)? $municipio->estado->id : null}}) : resetSelect('estadoPadre_id');
});

{{-- ingresa los datos en el select de municipios de la madre --}}
var estadoPadre_id = $('#estadoPadre_id').val();
estadoPadre_id ? getMunicipios(estadoPadre_id, 'expMunicipioPadre_id',
{{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('expMunicipioPadre_id');
$('#estadoPadre_id').on('change', function() {
var estadoPadre_id = $(this).val();
estadoPadre_id ? getMunicipios(estadoPadre_id, 'expMunicipioPadre_id',
{{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('expMunicipioPadre_id');
});


$("select[name=expEstadoCivilPadres]").change(function(){
    if($('select[name=expEstadoCivilPadres]').val() == "OTRO"){

        $("#divOtro").show();
        $("#expEstadoCivilOtro").attr('required', '');

    }else{
        $("#expEstadoCivilOtro").removeAttr('required');
        $("#divOtro").hide();
        $("#expEstadoCivilOtro").val("");

    }
});

$("select[name=expFamiliarModelo]").change(function(){
    if($('select[name=expFamiliarModelo]').val() == "SI"){

        $("#divEspecifica").show();
        $("#expNombreFamiliarModelo").attr('required', '');

    }else{
        $("#expNombreFamiliarModelo").removeAttr('required');
        $("#divEspecifica").hide();
        $("#expNombreFamiliarModelo").val("");

    }
});


</script>
@endsection
