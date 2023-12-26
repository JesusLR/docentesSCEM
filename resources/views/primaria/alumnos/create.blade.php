@extends('layouts.dashboard')

@section('template_title')
    Primaria alumno
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_curso')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria_alumno.index')}}" class="breadcrumb">Lista de alumnos</a>
    <a href="{{route('primaria_alumno.create')}}" class="breadcrumb">Agregar alumno</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_alumno.store', 'method' => 'POST']) !!}

        @if (isset($candidato))
            <input type="hidden" name="candidato_id" value="{{$candidato->id}}" />
        @endif
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR ALUMNO</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  {{--  <li class="tab"><a href="#tutores">Tutor</a></li>  --}}
                </ul>
              </div>
            </nav>

            @php
             use Carbon\Carbon;
             $fechaActual = Carbon::now('CDT')->format('Y-m-d');
            @endphp

            {{-- GENERAL BAR--}}
            <div id="general">
                <input type="hidden" name="campus" value={{isset($candidato) ? $campus: null}} />
                <input type="hidden" name="departamento" value={{isset($candidato) ? $departamento: null}} />
                <input type="hidden" name="programa" value={{isset($candidato) ? $programa: null}} />

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perNombre', isset($candidato) ? $candidato->perNombre: null,
                            array('id' => 'perNombre', 'class' => 'validate','required','maxlength'=>'40', isset($candidato) ? "readonly": "")) !!}
                        {!! Form::label('perNombre', 'Nombre(s) *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perApellido1', isset($candidato) ? $candidato->perApellido1: null,
                        array('id' => 'perApellido1', 'class' => 'validate','required','maxlength'=>'30',isset($candidato) ? "readonly": "")) !!}
                        {!! Form::label('perApellido1', 'Primer apellido *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perApellido2', isset($candidato) ? $candidato->perApellido2: null,
                        array('id' => 'perApellido2', 'class' => 'validate','maxlength'=>'30', isset($candidato) ? "readonly": ""))!!}
                        {!! Form::label('perApellido2', 'Segundo apellido', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('perCurp', isset($candidato) ? $candidato->perCurp: null,
                                array('id' => 'perCurp', 'class' => 'validate', 'required', 'maxlength'=>'18', isset($candidato) ? "readonly": "")) !!}
                            {!! Form::hidden('esCurpValida', NULL, ['id' => 'esCurpValida']) !!}
                            {!! Form::label('perCurp', 'Curp *', array('class' => '')); !!}
                        </div>
                        <div class="row">
                            <div class="col s12 m6 l6">
                                <a class="waves-effect waves-light btn" href="https://www.gob.mx/curp/" target="_blank">
                                    Verificar Curp
                                </a>
                            </div>
                            <div class="col s12 m6 l6" style="margin-top:5px;">
                                <div style="position:relative;">
                                    <input type="checkbox" name="esExtranjero" id="esExtranjero" value="" {{(isset($candidato) && $candidato->esExtranjero) ? "checked": ""}} {{isset($candidato) ? "readonly": ""}}>
                                    <label for="esExtranjero"> No soy Mexicano y aún no tengo el CURP</label>
                                    @if (isset($candidato))
                                        <div style="width: 100%; height: 100%; position: absolute; top: 0;"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="col s12 m6 l6">
                                {!! Form::label('aluNivelIngr', 'Nivel de ingreso *', array('class' => '')); !!}
                                <div style="position:relative;">
                                    <select id="aluNivelIngr" class="browser-default validate select2" required name="aluNivelIngr" style="width: 100%;" {{isset($candidato) ? "readonly": ""}}>
                                        <option value="" disabled>SELECCIONE UNA OPCIÓN</option>
                                        @foreach($departamentos as $departamento)
                                            <option value="{{$departamento->depNivel}}"
                                                {{ (isset($candidato) && $departamento->depClave == "SUP") ? "selected": ""}}
                                                @if(old('aluNivelIngr') == $departamento->depNivel) {{ 'selected' }} @endif>

                                                {{$departamento->depClave}} -
                                                @if ($departamento->depClave == "SUP") Superior @endif
                                                @if ($departamento->depClave == "POS") Posgrado @endif
                                                @if ($departamento->depClave == "DIP") Educacion Continua @endif
                                                @if ($departamento->depClave == "PRE") Prescolar @endif
                                                @if ($departamento->depClave == "PRI") Primaria @endif

                                            </option>
                                        @endforeach
                                    </select>
                                    @if (isset($candidato))
                                        <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                                    @endif
                                </div>
                        </div>
                        <div class="input-field col s12 m6 l6">
                            {!! Form::number('aluGradoIngr', isset($candidato) ? "1" : null, array('id' => 'aluGradoIngr', isset($candidato) ? "readonly": "", 'class' => 'validate','required','min'=>'1','max'=>'6','onKeyPress="if(this.value.length>1) return false;"')) !!}
                            {!! Form::label('aluGradoIngr', 'Grado Ingreso *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {{-- COLUMNA --}}
                        <div class="col s12 m6 l6">
                            {!! Form::label('perSexo', 'Sexo *', array('class' => '')); !!}
                            <div style="position:relative;">
                                <select id="perSexo" class="browser-default validate select2" required name="perSexo" style="width: 100%;" {{isset($candidato) ? "readonly": ""}}>
                                    <option
                                        value="M"
                                        {{ (old("perSexo") == "M") ? "selected": ""}}
                                        {{ (isset($candidato) && $candidato->perSexo == "M") ? "selected": ""}}>
                                        HOMBRE
                                    </option>
                                    <option
                                        value="F"
                                        {{ (old("perSexo") == "F") ? "selected": ""}}
                                        {{ (isset($candidato) && $candidato->perSexo == "F") ? "selected": ""}}>
                                        MUJER
                                    </option>
                                </select>
                                @if (isset($candidato))
                                    <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                                @endif
                            </div>
                        </div>
                        <div class="col s12 m6 l6">
                            {!! Form::label('perFechaNac', 'Fecha de nacimiento *', array('class' => '')); !!}
                            {!! Form::date('perFechaNac',  isset($candidato) ? $candidato->perFechaNac: NULL,
                            array('id' => 'perFechaNac', 'class' => ' validate','required', 'max'=>$fechaActual, isset($candidato) ? "readonly": "")) !!}
                        </div>
                    </div>
                </div>

                <br>
                <div class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Lugar de Nacimiento</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('paisId', 'País *', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="paisId"
                                data-pais-id="{{old('paisId')}}"
                                class="browser-default validate select2" required name="paisId" style="width: 100%;" {{isset($candidato) ? "readonly": ""}}>
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach($paises as $pais)
                                    @php
                                        $selected = '';
                                        if (isset($candidato)) {
                                            if ($municipio->estado->pais->id == $pais->id) {
                                                $selected = 'selected';
                                            }
                                        }

                                        if ($pais->id == old("paisId")) {
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value="{{$pais->id}}" {{$selected}}>{{$pais->paisNombre}}</option>
                                @endforeach
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                            {!! Form::label('estado_id', 'Estado *', array('class' => '')); !!}
                            <div style="position:relative">
                                <select id="estado_id"
                                    {{isset($candidato) ? "readonly": ""}}
                                    data-estado-id="{{old('estado_id')}}"
                                    class="browser-default validate select2" required name="estado_id" style="width: 100%;">
                                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                </select>
                                @if (isset($candidato))
                                    <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                                @endif
                            </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('municipio_id', 'Municipio *', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="municipio_id"
                                {{isset($candidato) ? "readonly": ""}}
                                data-municipio-id="{{old('municipio_id')}}"
                                class="browser-default validate select2" required name="municipio_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <br>
                <div class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Datos de Contacto</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('perTelefono2', isset($candidato) ? $candidato->perTelefono1 : null,
                            array('id' => 'perTelefono2', isset($candidato) ? "readonly": "", 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"', 'required')) !!}
                        {!! Form::label('perTelefono2', 'Teléfono móvil *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::label('perCorreo1', 'Correo', ['class' => '', ]) !!}
                        {!! Form::email('perCorreo1', isset($candidato) ? $candidato->perCorreo1 : null,
                            ['id' => 'perCorreo1', isset($candidato) ? "readonly": "", 'class' => 'validate', 'maxlength' => '60']) !!}
                        </div>
                    </div>
                </div>

                {{-- <br>
                <div class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Domicilio</p>
                </div> --}}

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirCalle', NULL, array('id' => 'perDirCalle', 'class' => 'validate','maxlength'=>'25')) !!}
                        {!! Form::label('perDirCalle', 'Calle', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirNumExt', NULL, array('id' => 'perDirNumExt', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('perDirNumExt', 'Número exterior', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirNumInt', NULL, array('id' => 'perDirNumInt', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('perDirNumInt', 'Número interior', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('perDirColonia', NULL, array('id' => 'perDirColonia', 'class' => 'validate','maxlength'=>'60')) !!}
                            {!! Form::label('perDirColonia', 'Colonia', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('perDirCP', NULL, array('id' => 'perDirCP', 'class' => 'validate','min'=>'0','max'=>'99999','onKeyPress="if(this.value.length==5) return false;"')) !!}
                            {!! Form::label('perDirCP', 'Código Postal', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('perTelefono1', NULL, array('id' => 'perTelefono1', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                        {!! Form::label('perTelefono1', 'Teléfono fijo', array('class' => '')); !!}
                        </div>
                    </div>
                </div>


                <br>
                <div class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Tutores</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('tutNombre', NULL, array('id' => 'tutNombre', 'class' => 'validate')) !!}
                            {!! Form::label('tutNombre', 'Nombre(s) de Tutor', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('tutTelefono', NULL, array('id' => 'tutTelefono', 'class' => 'validate','min'=>'0','max'=>'9999999999')) !!}
                            {!! Form::label('tutTelefono', 'Teléfono de tutor', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                      <div class="input-field col s6 m6 l3">
                          <a name="buscarTutor" id="buscarTutor" class="waves-effect btn-large tooltipped" data-position="right" data-tooltip="Buscar tutor por nombre y teléfono">
                          <i class=material-icons>search</i>
                        </a>
                      </div>
                      <div class="input-field col s6 m6 l3">
                          <a name="vincularTutor" id="vincularTutor" class="waves-effect btn-large tooltipped" 
                              data-position="right" data-tooltip="Vincular tutor a este alumno" disabled>
                              <i class=material-icons>sync</i>
                          </a>
                      </div>
                    </div>
                </div>
            
                <br><br>
                <p>(Los siguientes datos son opcionales)</p>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('tutCalle', NULL, array('id' => 'tutCalle', 'class' => 'validate')) !!}
                            {!! Form::label('tutCalle', 'Calle', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('tutColonia', NULL, array('id' => 'tutColonia', 'class' => 'validate')) !!}
                            {!! Form::label('tutColonia', 'Colonia', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field col s12 m6 l4">
                            {!! Form::number('tutCodigoPostal', NULL, array('id' => 'tutCodigoPostal', 'class' => 'validate','min'=>'0','max'=>'99999')) !!}
                            {!! Form::label('tutCodigoPostal', 'Código Postal', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('tutPoblacion', NULL, array('id' => 'tutPoblacion', 'class' => 'validate')) !!}
                            {!! Form::label('tutPoblacion', 'Población', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('tutEstado', NULL, array('id' => 'tutEstado', 'class' => 'validate'))!!}
                        {!! Form::label('tutEstado', 'Estado', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::label('tutCorreo', 'Correo Electrónico', array('class' => 'validate')); !!}
                            {!! Form::email('tutCorreo', NULL, array('id' => 'tutCorreo')) !!}
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col s12 m6 l4">
                      <div class="input-field col s6 m6 l3">
                          <a name="crearTutor" id="crearTutor" class="waves-effect btn-large tooltipped #2e7d32 green darken-3" 
                              data-position="right" data-tooltip="Crear nuevo tutor">
                              <i class=material-icons>person_add</i>
                          </a>
                      </div>
                    </div>
                </div>
                
                <br>
                <!-- TABLA DE TUTORES DEL ALUMNO. -->
                <div class="row">
                    <div class="col s12">
                        <table id="tbl-tutores" class="responsive-table display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Nombre(s)</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{--  <br>
                <div id="prepDefinir" class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Preparatoria de procedencia</p>
                </div>

                <div id="prepDefinir2" class="row">
                    <div class="col s12 m6 l4">
                        <div style="position:relative;">
                            <input type="checkbox" name="prepaPorDefinir" id="prepaPorDefinir" value="" {{isset($candidato) ? "readonly": ""}}>
                            <label for="prepaPorDefinir">Definir preparatoria después</label>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 100%; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                </div>  --}}
{{--  
                <div id="prepDefinir3" class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('paisPrepaId', 'País preparatoria', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="paisPrepaId"
                                {{isset($candidato) ? "readonly": ""}}
                                data-pais-id="{{old('paisPrepaId')}}"
                                class="browser-default validate select2" name="paisPrepaId" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach($paises as $pais)
                                    @php
                                        $selected = '';

                                        if (isset($candidato)) {
                                            if ($preparatoriaProcedencia->municipio->estado->pais->id == $pais->id) {
                                                $selected = 'selected';
                                            }
                                        }

                                        if ($pais->id == old("paisPrepaId")) {
                                            $selected = 'selected';
                                        }
                                    @endphp

                                    <option value="{{$pais->id}}" {{$selected}}>{{$pais->paisNombre}}</option>
                                @endforeach
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('estado_prepa_id', 'Estado preparatoria', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="estado_prepa_id"
                                {{isset($candidato) ? "readonly": ""}}
                                data-estado-id="{{old('estado_prepa_id')}}"
                                class="browser-default validate select2" name="estado_prepa_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('municipio_prepa_id', 'Municipio preparatoria', array('class' => '')); !!}
                        <div style="position:relative;">
                            <select id="municipio_prepa_id"
                                {{isset($candidato) ? "readonly": ""}}
                                data-municipio-id="{{old('municipio_prepa_id')}}"
                                class="browser-default validate select2" name="municipio_prepa_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                </div>  --}}
                {{--  <div class="row" id="escuelaProdecenciadiv">
                    <div class="col s12 m6 l4">
                        {!! Form::label('preparatoria_id', 'Preparatoria de procedencia', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="preparatoria_id"
                                {{isset($candidato) ? "readonly": ""}}
                                data-preparatoria-id="{{old('preparatoria_id')}}"
                                class="browser-default validate select2" required name="preparatoria_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                </div>  --}}
            </div>

            {{-- TUTORES BAR --}}
            {{--  @include('primaria.alumnos.tutores')  --}}

          </div>
          <input type="hidden" name="empleado_id" id="empleado_id" value="">
          <div class="card-action">
            {!! Form::button('<i class=" material-icons left validar-campos">save</i> Guardar y continuar preinscripción', ['class' => 'btn-guardar-alumno btn-large waves-effect  darken-3','id'=>'btn-guardar-alumno']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>

  
  {{-- funciones de módulos CRUD --}}
  {!! HTML::script(asset('js/alumnos/crud-alumnos.js'), array('type' => 'text/javascript')) !!}
  {{-- Funciones para Modelo Persona --}}
  {!! HTML::script(asset('js/personas/personas.js'), array('type' => 'text/javascript'))!!}

@endsection

@section('footer_scripts')

{{-- Script de funciones auxiliares  --}}
@include('primaria.scripts.funcionesAuxiliares')

    <script>
        // var instance = M.Tabs.getInstance($(".tabs"));
        // instance.select('personal');

        $(document).on("click", ".btn-guardar-alumno", function(e) {


            if ((!$("#perDirCalle").val()    || !$("#perDirNumExt").val()
                || !$("#paisId").val()       || !$("#estado_id").val()
                || !$("#municipio_id").val() || !$("#perDirColonia").val()
                || !$("#perDirCP").val()     || !$("#perSexo").val()
                || !$("#perFechaNac").val()  || !$("#perTelefono2").val()
                || !$("#perCorreo1").val())
                && $("#general").hasClass("active")
                && $("#perNombre").val()
                && $("#perApellido1").val()
                && $("#perCurp").val()
                && $("#aluNivelIngr").val()
                && $("#aluGradoIngr").val()) {

                $('ul.tabs').tabs("select_tab", "personal");

                return;
            }



            $(this).submit()
        })



        var curp = $("#perCurp").val()
        var esCurpValida = curpValida(curp);
        $("#esCurpValida").val(esCurpValida);

        $("#perCurp").on('change', function(e) {
            var curp = e.target.value
            var esCurpValida = curpValida(curp);
            $("#esCurpValida").val(esCurpValida);
        });


    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            avoidSpecialCharacters('perNombre');
            avoidSpecialCharacters('perApellido1');
            avoidSpecialCharacters('perApellido2');

            // PERSONA - LUGAR DE NACIMIENTO - SELECTS

            var pais_id = $('#paisId').val();
            pais_id ? getEstados(pais_id, 'estado_id',
            {{ (isset($candidato) && $municipio) ? $municipio->estado->id : null}}) : resetSelect('estado_id');
            $('#paisId').on('change', function() {
                var pais_id = $(this).val();
                pais_id ? getEstados(pais_id, 'estado_id',
                {{ (isset($candidato) && $municipio)? $municipio->estado->id : null}}) : resetSelect('estado_id');
            });

            var estado_id = $('#estado_id').val();
            estado_id ? getMunicipios(estado_id, 'municipio_id',
            {{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('municipio_id');
            $('#estado_id').on('change', function() {
                var estado_id = $(this).val();
                estado_id ? getMunicipios(estado_id, 'municipio_id',
                {{ (isset($candidato) && $municipio)? $municipio->id : null}}) : resetSelect('municipio_id');
            });



            // PREPARATORIA DE PROCEDENCIA - SELECTS
            var prepa_pais_id = $('#paisPrepaId').val();
            prepa_pais_id
                ? getEstados(prepa_pais_id, 'estado_prepa_id',
                    {{ (isset($candidato) && $preparatoriaProcedencia) ? $preparatoriaProcedencia->municipio->estado->id : null}})
                : resetSelect('estado_prepa_id');

            $('#paisPrepaId').on('change', function() {
                var prepa_pais_id = $(this).val();
                prepa_pais_id
                    ? getEstados(prepa_pais_id, 'estado_prepa_id',
                        {{ (isset($candidato) && $preparatoriaProcedencia) ? $preparatoriaProcedencia->municipio->estado->id : null}})
                    : resetSelect('estado_prepa_id');
            });

            var prepa_estado_id = $('#estado_prepa_id').val();
            prepa_estado_id
                ? getMunicipios(prepa_estado_id, 'municipio_prepa_id',
                    {{ (isset($candidato) && $preparatoriaProcedencia) ? $preparatoriaProcedencia->municipio->id : null}})
                : resetSelect('municipio_prepa_id');
            $('#estado_prepa_id').on('change', function() {
                var prepa_estado_id = $(this).val();
                prepa_estado_id
                    ? getMunicipios(prepa_estado_id, 'municipio_prepa_id',
                        {{ (isset($candidato) && $preparatoriaProcedencia) ? $preparatoriaProcedencia->municipio->id : null}})
                    : resetSelect('municipio_prepa_id');
            });

            var prepa_municipio_id = $('#municipio_prepa_id').val();
            prepa_municipio_id
                ? getPreparatorias(prepa_municipio_id, 'preparatoria_id',
                    {{ (isset($candidato) && $preparatoriaProcedencia) ? $preparatoriaProcedencia->id : null}})
                : resetSelect('preparatoria_id');
            $('#municipio_prepa_id').on('change', function() {
                var prepa_municipio_id = $(this).val();
                prepa_municipio_id
                    ? getPreparatorias(prepa_municipio_id, 'preparatoria_id',
                        {{ (isset($candidato) && $preparatoriaProcedencia) ? $preparatoriaProcedencia->id : null}})
                    : resetSelect('preparatoria_id');
            });

            function esExtranjero (inputEsExtranjero) {
                if(inputEsExtranjero.is(':checked')) {
                    $("#perCurp").removeAttr('required');
                    $("#perCurp").attr('disabled', true);
                    $("#perCurp").removeClass('invalid').val('');
                    if ($('#paisId').val() == 1) {
                        $('#paisId').val(0).select2();
                        resetSelect('estado_id');
                        resetSelect('municipio_id');
                    }
                    $('#paisId option[value="1"]').attr('disabled', true).select2();

                    Materialize.updateTextFields();
                } else {
                    $("#perCurp").attr('required', true);
                    $("#perCurp").removeAttr('disabled');
                    $('#paisId option[value="1"]').removeAttr('disabled').select2();
                }
            }
            // CHECKBOX  "Soy Extranjero".
            esExtranjero($('#esExtranjero'));
            $('#esExtranjero').on('click', function() {
                var inputEsExtranjero = $(this)
                esExtranjero(inputEsExtranjero);
            });

            //CHECKBOX "Definir preparatoria después"
            $('#prepaPorDefinir').on('click', function() {
                var prepaPorDefinir = $(this);
                if(prepaPorDefinir.is(':checked')) {
                    $("#paisPrepaId").attr('disabled', true).val(0).select2();
                    $("#estado_prepa_id").empty().append(new Option('SELECCIONE UNA OPCIÓN', '')).attr('disabled', true).select2();
                    $("#municipio_prepa_id").empty().append(new Option('SELECCIONE UNA OPCIÓN', '')).attr('disabled', true).select2();
                    $('#preparatoria_id').empty()
                    .append(new Option('* POR DEFINIR', 0));
                } else {
                    $('#paisPrepaId').removeAttr('disabled').select2();
                    $('#estado_prepa_id').removeAttr('disabled').select2();
                    $('#municipio_prepa_id').removeAttr('disabled').select2();
                    $('#preparatoria_id').empty()
                    .append(new Option('SELECCIONE UNA OPCIÓN', ''));
                }
            });



        });
    </script>

    <script type="text/javascript">

        /*
        * El siguiente código solo interviene en el apartado tutores.
        */
        $(document).ready(function(){

            var elementos = [
                'tutNombre',
                'tutCalle',
                'tutColonia',
                'tutCodigoPostal',
                'tutPoblacion',
                'tutEstado',
                'tutTelefono',
                'tutCorreo',
                'tutCorreo'
            ];

            var elemRequeridos = [
                'tutNombre',
                'tutTelefono'
            ];

            $.each(elemRequeridos, function(key, value) {
                $('#' + value).on('change', function() {
                    $('#vincularTutor').attr('disabled', true);
                })
            });

            $.each(elementos, function (key, value) {
                $('#' + value).change(function () {
                    if_haveValue_setRequired(elementos, elemRequeridos);
                });
            });

            //Acciones del botón buscar tutor. -------------------------------
            $('#buscarTutor').on('click', function () {
                var tutNombre = $('#tutNombre').val();
                var tutTelefono = $('#tutTelefono').val();
                if(tutNombre && tutTelefono){
                    buscarTutor(tutNombre, tutTelefono);
                }else{
                    swal({
                        title: 'Requiere llenar estos campos:',
                        text: '- Nombre del tutor \n - Teléfono de tutor',
                    });
                }
            });


            //acciones del botón vincular tutor. -----------------------------
            $('#vincularTutor').on('click', function () {
                var tutNombre = $('#tutNombre').val();
                var tutTelefono = $('#tutTelefono').val();
                if(tutNombre && tutTelefono){
                    addRow_tutor(tutNombre, tutTelefono);
                    emptyElements(elementos);
                    unsetRequired(elemRequeridos);
                }else{
                    swal({
                        title: 'Requiere llenar estos campos:',
                        text: '- Nombre del tutor \n - Teléfono de tutor \n'+
                            '\n Así como verificar si el tutor existe',
                    });
                }
            });

            //Acción de botón crear tutor. ---------------------------------
            $('#crearTutor').on('click', function () {
                var datos = objectBy(elementos);
                console.log(datos);
                $.ajax({
                    type: 'POST',
                    url: base_url + '/primaria_alumno/tutores/nuevo_tutor',
                    data: {datos: datos, '_token':'{{csrf_token()}}'},
                    dataType: 'json',
                    success: function (data) {
                        if(data){
                            var tutor = data;
                            addRow_tutor(tutor.tutNombre, tutor.tutTelefono);
                            emptyElements(elementos);
                            unsetRequired(elemRequeridos);
                        }else{
                            swal({
                                title: 'Ya existe registro.',
                                text: 'Ya existe un tutor con estos datos, ' +
                                'Puede obtener sus datos presionando el botón de búsqueda.'
                            });
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        console.log(errorMessage);
                    }
                });
            });

            $('#tbl-tutores').on('click','.desvincular', function () {
                $(this).closest('tr').remove();
            });

            $('#btn-guardar-alumno').on('click', function () {
                var requeridosIdentidad = {
                    perNombre: 'Nombre',
                    perApellido1: 'Primer Apellido',
                    perCurp: 'CURP'
                };
                if($('#esExtranjero').is(':checked')) {
                    delete requeridosIdentidad.perCurp;
                }

                var camposFaltantes = validate_formFields(requeridosIdentidad);
                if(jQuery.isEmptyObject(camposFaltantes)) {
                    verificarPersona();
                }else{
                    showRequiredFields(camposFaltantes);
                }
            });


        });

        function verificarPersona() {
            console.log("verificar persona")

            $.ajax({
                type:'GET',
                url: base_url + '/primaria_alumno/verificar_persona',
                data: $('form').serialize(),
                dataType: 'json',
                success: function(data) {

                    if(data.alumno){
                        var alumno = data.alumno;
                        var persona = alumno.persona;
                        swal({
                            title: 'Ya existe el Alumno',
                            text: 'Se encontró un alumno con los siguientes datos: \n' +
                                  '\n Clave de Alumno: '+alumno.aluClave+' \n'+
                                  'Nombre: '+persona.perNombre+' '+persona.perApellido1+' '+persona.perApellido2+' \n'+
                                  'CURP: '+persona.perCurp+' \n'+
                                  '\n No se puede duplicar el alumno. ¿Desea utilizar este registro?',
                            showCancelButton: true,
                            cancelButtonText: 'No, cancelar',
                            confirmButtonText: 'Habilitar'
                        },function() {
                            rehabilitarAlumno(alumno.id);
                        });
                    }else if(data.empleado) {
                        var empleado = data.empleado;
                        var persona = empleado.persona;
                        swal({
                            title: 'Ya existe la persona',
                            text: 'Se encontró un empleado con los siguientes datos: \n' +
                                  '\n Clave: '+empleado.id+' \n'+
                                  'Nombre: '+persona.perNombre+' '+persona.perApellido1+' '+persona.perApellido2+' \n'+
                                  'CURP: '+persona.perCurp+' \n'+
                                  '\n No se pueden duplicar estos datos. ¿Desea registrar este empleado como alumno?',
                            showCancelButton: true,
                            cancelButtonText: 'No, cancelar',
                            confirmButtonText: 'Sí, registrar como alumno'
                        }, function() {
                            empleado_crearAlumno(empleado.id);
                        });
                    }else{
                        $('form').submit();
                    }
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    disabled.attr('disabled','disabled');

                    console.log(errorMessage);
                },
            });
        }//verificarPersona.

        function rehabilitarAlumno(alumno_id) {
            $.ajax({
                type:'POST',
                url: base_url+'/primaria_alumno/rehabilitar_alumno/'+alumno_id,
                data:{alumno_id: alumno_id, '_token':'{{csrf_token()}}'},
                dataType:'json',
                success: function(alumno) {
                    window.location = base_url+'/primaria_alumno/'+alumno.id+'/edit';
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    console.log(errorMessage);
                }
            });
        }//rehabilitarAlumno.

        function empleado_crearAlumno(empleado_id) {


            $.ajax({
                type:'POST',
                url: base_url+'/primaria_alumno/registrar_empleado/'+empleado_id,
                data: $('form').serialize(),
                dataType:'json',
                success: function (alumno) {
                    window.location = base_url+'/primaria_alumno/'+alumno.id+'/edit';
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    console.log(errorMessage);
                }
            });
        }//empleado_crearAlumno.

    </script>


    <script>


            $("select[name=aluNivelIngr]").change(function(){
                if($('select[name=aluNivelIngr]').val() == 1){

                    $("#paisPrepaId").attr('disabled', true).val(0).select2();
                    $("#estado_prepa_id").empty().append(new Option('SELECCIONE UNA OPCIÓN', '')).attr('disabled', true).select2();
                    $("#municipio_prepa_id").empty().append(new Option('SELECCIONE UNA OPCIÓN', '')).attr('disabled', true).select2();
                    $('#preparatoria_id').empty()
                    .append(new Option('* POR DEFINIR', 0));

                    $("#prepaPorDefinir").prop("checked", true);
                    $('#prepaPorDefinir').prop('disabled', true);

                    {{--  ocultar los div de los datos de la Prepa cuando se selecciona Prescolar   --}}
                    $("#prepDefinir").hide();
                    $("#prepDefinir2").hide();
                    $("#prepDefinir3").hide();
                    $("#escuelaProdecenciadiv").hide();



                }else{

                    if($('select[name=aluNivelIngr]').val() == 2){
                        $("#paisPrepaId").attr('disabled', true).val(0).select2();
                        $("#estado_prepa_id").empty().append(new Option('SELECCIONE UNA OPCIÓN', '')).attr('disabled', true).select2();
                        $("#municipio_prepa_id").empty().append(new Option('SELECCIONE UNA OPCIÓN', '')).attr('disabled', true).select2();
                        $('#preparatoria_id').empty()
                        .append(new Option('* POR DEFINIR', 0));

                        $("#prepaPorDefinir").prop("checked", true);
                        $('#prepaPorDefinir').prop('disabled', true);

                        {{--  ocultar los div de los datos de la Prepa cuando se selecciona Prescolar   --}}
                        $("#prepDefinir").hide();
                        $("#prepDefinir2").hide();
                        $("#prepDefinir3").hide();
                        $("#escuelaProdecenciadiv").hide();
                    }else{
                        $("#prepaPorDefinir").prop("checked", false);
                        $('#prepaPorDefinir').prop('disabled', false);
                        $('#paisPrepaId').removeAttr('disabled').select2();
                        $('#estado_prepa_id').removeAttr('disabled').select2();
                        $('#municipio_prepa_id').removeAttr('disabled').select2();
                        $('#preparatoria_id').empty()
                        .append(new Option('SELECCIONE UNA OPCIÓN', ''));

                        $("#prepDefinir").show();
                        $("#prepDefinir2").show();
                        $("#prepDefinir3").show();
                        $("#escuelaProdecenciadiv").show();
                    }
                }
            });


 
    </script>

@endsection
