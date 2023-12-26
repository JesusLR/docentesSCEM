@extends('layouts.dashboard')

@section('template_title')
    Primaria alumno
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_alumno')}}" class="breadcrumb">Lista de alumnos</a>
    <a href="{{url('primaria_alumno/'.$alumno->id.'/edit')}}" class="breadcrumb">Editar alumno</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria_alumno.update', $alumno->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR. Clave del Alumno #{{$alumno->aluClave}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  {{--  <li class="tab"><a href="#tutores">Tutor</a></li>  --}}
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perNombre', $alumno->persona->perNombre, array('id' => 'perNombre', 'class' => 'validate','required','maxlength'=>'40')) !!}
                        {!! Form::label('perNombre', 'Nombre(s) *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perApellido1', $alumno->persona->perApellido1, array('id' => 'perApellido1', 'class' => 'validate','required','maxlength'=>'30')) !!}
                        {!! Form::label('perApellido1', 'Primer apellido *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perApellido2', $alumno->persona->perApellido2, array('id' => 'perApellido2', 'class' => 'validate','maxlength'=>'30')) !!}
                        {!! Form::label('perApellido2', 'Segundo apellido', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perCurp', $alumno->persona->perCurp, array('id' => 'perCurp', 'class' => 'validate','required')) !!}
                        {!! Form::hidden('perCurpOld', $alumno->persona->perCurp, ['id' => 'perCurpOld']) !!}
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
                                <input type="checkbox" name="esExtranjero" id="esExtranjero" value="">
                                <label for="esExtranjero"> No soy Mexicano y aún no tengo el CURP</label>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="col s12 m6 l6">
                            {!! Form::label('aluNivelIngr', 'Nivel de ingreso *', array('class' => '')); !!}
                            <select id="aluNivelIngr" class="browser-default validate select2" required name="aluNivelIngr" style="width: 100%;">
                                @foreach($departamentos as $departamento)
                                    @if($alumno->aluNivelIngr == $departamento->depNivel)
                                        <option value="{{$departamento->depNivel}}" >
                                            {{$departamento->depClave}} -
                                            @if ($departamento->depClave == "SUP") Superior @endif
                                            @if ($departamento->depClave == "POS") Posgrado @endif
                                            @if ($departamento->depClave == "BAC") Bachillerato @endif
                                            @if ($departamento->depClave == "PRE") Prescolar @endif
                                            @if ($departamento->depClave == "PRI") Primaria @endif
                                            @if ($departamento->depClave == "SEC") Secundaria @endif
                                            @if ($departamento->depClave == "DIP") Educación continua @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="input-field col s12 m6 l6">
                            {!! Form::number('aluGradoIngr', $alumno->aluGradoIngr, array('id' => 'aluGradoIngr', 'class' => 'validate','required','min'=>'1','max'=>'6','onKeyPress="if(this.value.length>1) return false;"')) !!}
                            {!! Form::label('aluGradoIngr', 'Grado Ingreso *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="col s12 m6 l6">
                            {!! Form::label('perSexo', 'Sexo *', array('class' => '')); !!}
                            <select id="perSexo" class="browser-default validate select2" required name="perSexo" style="width: 100%;">
                                <option value="M" @if($alumno->persona->perSexo == "M") {{ 'selected' }} @endif>HOMBRE</option>
                                <option value="F" @if($alumno->persona->perSexo == "F") {{ 'selected' }} @endif>MUJER</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6">
                            {!! Form::label('perFechaNac', 'Fecha de nacimiento *', array('class' => '')); !!}
                            {!! Form::date('perFechaNac', $alumno->persona->perFechaNac, array('id' => 'perFechaNac', 'class' => ' validate','required')) !!}
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
                        <select id="paisId" class="browser-default validate select2" required name="paisId" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($paises as $pais)
                                <option value="{{$pais->id}}"
                                    @if($alumno->persona->municipio->estado->pais->id == $pais->id) {{ 'selected' }} @endif
                                    @if ($pais->id == old("paisId"))
                                        {{ 'selected' }}
                                    @endif
                                    >
                                    {{$pais->paisNombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('estado_id', 'Estado *', array('class' => '')); !!}
                        <select id="estado_id" data-estado-id="{{$estado_id}}"
                            class="browser-default validate select2"
                            value="{{$estado_id}}"
                            required name="estado_id" style="width: 100%;">
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('municipio_id', 'Municipio *', ['class' => '']); !!}
                        <select id="municipio_id" data-municipio-id="{{$alumno->persona->municipio->id}}"
                            class="browser-default validate select2"
                            value="{{$alumno->persona->municipio->id}}"
                            required name="municipio_id" style="width: 100%;">
                        </select>
                    </div>
                </div>

                <br>
                <div class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Datos de Contacto</p>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('perTelefono2', $alumno->persona->perTelefono2, array('id' => 'perTelefono2', 'class' => 'validate',
                            'min'=>'0','max'=>'9999999999', 'onKeyPress="if(this.value.length==10) return false;"')) !!}
                        {!! Form::label('perTelefono2', 'Teléfono móvil *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perCorreo1', $alumno->persona->perCorreo1, array('id' => 'perCorreo1', 'class' => 'validate','maxlength'=>'60')) !!}
                        {!! Form::label('perCorreo1', 'Correo', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
{{--
                <br>
                <div class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Domicilio</p>
                </div> --}}

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirCalle', $alumno->persona->perDirCalle, array('id' => 'perDirCalle', 'class' => 'validate','maxlength'=>'25')) !!}
                        {!! Form::label('perDirCalle', 'Calle', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirNumExt', $alumno->persona->perDirNumExt, array('id' => 'perDirNumExt', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('perDirNumExt', 'Número exterior', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirNumInt', $alumno->persona->perDirNumInt, array('id' => 'perDirNumInt', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('perDirNumInt', 'Número interior', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirColonia', $alumno->persona->perDirColonia, array('id' => 'perDirColonia', 'class' => 'validate','maxlength'=>'60')) !!}
                        {!! Form::label('perDirColonia', 'Colonia', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('perDirCP', $alumno->persona->perDirCP, array('id' => 'perDirCP', 'class' => 'validate','min'=>'0','max'=>'99999','onKeyPress="if(this.value.length==5) return false;"')) !!}
                        {!! Form::label('perDirCP', 'Código Postal', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('perTelefono1', $alumno->persona->perTelefono1, array('id' => 'perTelefono1', 'class' => 'validate',
                            'min'=>'0','max'=>'9999999999', 'onKeyPress="if(this.value.length==10) return false;"')) !!}
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
                {{--  <div class="row" style="background-color:#ECECEC;">
                  <p style="text-align: center;font-size:1.2em;">Preparatoria de procedencia</p>
                </div>  --}}

                {{--  <div class="row">
                    <div class="col s12 m6 l4">
                        <input type="checkbox" name="prepaPorDefinir" id="prepaPorDefinir" value="">
                        <label for="prepaPorDefinir">Definir preparatoria después</label>
                    </div>
                </div>  --}}

                {{--  <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('paisPrepaId', 'País preparatoria', array('class' => '')); !!}
                        <select id="paisPrepaId" class="browser-default validate select2"  name="paisPrepaId" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($paises as $pais)
                                <option value="{{$pais->id}}" @if($preparatoria_pais_id == $pais->id) {{ 'selected' }} @endif>{{$pais->paisNombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('estado_prepa_id', 'Estado preparatoria', array('class' => '')); !!}
                        <select id="estado_prepa_id" data-estado-id="{{$preparatoria_estado_id}}"
                            class="browser-default validate select2"
                             name="estado_prepa_id" style="width: 100%;">
                        </select>
                        <input type="hidden" class="fix-estado-prepa-id" value="{{$preparatoria_estado_id}}">
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('municipio_prepa_id', 'Municipio preparatoria', ['class' => '']); !!}
                        <select id="municipio_prepa_id" data-municipio-id="{{$preparatoria_municipio_id}}"
                            class="browser-default validate select2"
                            required name="municipio_prepa_id" style="width: 100%;">
                        </select>
                    </div>
                </div>  --}}


                {{--  <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('preparatoria_id', 'Preparatoria de procedencia *', array('class' => '')); !!}
                        <select id="preparatoria_id" data-preparatoria-id="{{$alumno->preparatoria_id}}"
                            class="browser-default validate select2"  name="preparatoria_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <input type="hidden" id="preparatoria_id_alumno" value="{{$alumno->preparatoria_id}}" />
                </div>  --}}


            </div>


            {{-- TUTORES BAR --}}
            {{--  @include('primaria.alumnos.tutores')  --}}


          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>


  {{-- funciones de módulo CRUD --}}
  {!! HTML::script(asset('js/alumnos/crud-alumnos.js'), array('type' => 'text/javascript')) !!}
  {{-- Funciones para Modelo Persona --}}
  {!! HTML::script(asset('js/personas/personas.js'), array('type' => 'text/javascript'))!!}

@endsection

@section('footer_scripts')
  {{-- Script de funciones auxiliares  --}}
  @include('primaria.scripts.funcionesAuxiliares')
    <script>
        /*
        * VALIDACIÓN DE CURP
        */
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

            /*
            * PERSONA LUGAR DE NACIMIENTO.
            */

            var pais_id = $('#paisId').val();
            pais_id && getEstados(pais_id, 'estado_id');
            $("#paisId").on('change', function() {
                pais_id = $('#paisId').val();
                pais_id && getEstados(pais_id, 'estado_id');
            });


            var estado_id = $('#estado_id').val();
            estado_id && getMunicipios(estado_id, 'municipio_id');
            $("#estado_id").on('change', function() {
                estado_id = $('#estado_id').val();
                estado_id && getMunicipios(estado_id, 'municipio_id');
            });

            /*
            * PREPARATORIA DE PROCEDENCIA.
            */

            var prepa_pais_id = $('#paisPrepaId').val();
            prepa_pais_id && getEstados(prepa_pais_id, 'estado_prepa_id');
            $("#paisPrepaId").on('change', function() {
                prepa_pais_id = $('#paisPrepaId').val();
                prepa_pais_id && getEstados(prepa_pais_id, 'estado_prepa_id');
            });

            var prepa_estado_id = $('#estado_prepa_id').val();
            prepa_estado_id && getMunicipios(prepa_estado_id, 'municipio_prepa_id');
            $("#estado_prepa_id").on('change', function() {
                prepa_estado_id = $('#estado_prepa_id').val();
                prepa_estado_id && getMunicipios(prepa_estado_id, 'municipio_prepa_id');
            });

            var prepa_municipio_id = $('#municipio_prepa_id').val();
            prepa_municipio_id && getPreparatorias(prepa_municipio_id, 'preparatoria_id');
            $("#municipio_prepa_id").on('change', function() {
                prepa_municipio_id = $('#municipio_prepa_id').val();
                prepa_municipio_id && getPreparatorias(prepa_municipio_id, 'preparatoria_id');
            });


            // CHECKBOX  "Soy Extranjero".
            $('#esExtranjero').on('click', function() {
                var esExtranjero = $(this);
                if(esExtranjero.is(':checked')) {
                    $("#perCurp").removeAttr('required');
                    $("#perCurp").attr('disabled', true);
                    $("#perCurp").removeClass('invalid');
                    $('#paisId').val(0).select2();
                    $('#paisId option[value="1"]').attr('disabled', true).select2()
                    resetSelect('estado_id');
                    resetSelect('municipio_id');
                    Materialize.updateTextFields();
                } else {
                    $("#perCurp").attr('required', true);
                    $("#perCurp").removeAttr('disabled');
                    $('#paisId option[value="1"]').removeAttr('disabled').select2();
                }
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

    {{-- El siguiente Script solo afecta al apartado de TUTORES --}}
    <script type="text/javascript">

        $(document).ready(function () {

            var alumno = {!!json_encode($alumno)!!};
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

            llenar_tabla_tutores(alumno.id);

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
        });


    </script>

@endsection
