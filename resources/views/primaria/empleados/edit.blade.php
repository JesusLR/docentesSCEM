@extends('layouts.dashboard')

@section('template_title')
    Primaria empleado
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_empleado')}}" class="breadcrumb">Lista de empleados</a>
    <a href="{{url('primaria_empleado/'.$empleado->id.'/edit')}}" class="breadcrumb">Editar empleado</a>
@endsection

@section('content')


<div class="row">
  <div class="col s12 ">
    {{ Form::open(array('method'=>'PUT','route' => ['primaria_empleado.update', $empleado->id])) }}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">EDITAR EMPLEADO #{{$empleado->id}}</span>

          {{-- NAVIGATION BAR--}}
          <nav class="nav-extended">
            <div class="nav-content">
              <ul class="tabs tabs-transparent">
                <li class="tab"><a class="active" href="#general">General</a></li>
                <li class="tab"><a href="#personal">Personal</a></li>
              </ul>
            </div>
          </nav>

          {{-- GENERAL BAR--}}
          <div id="general">

              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('perNombre', $empleado->empNombre, array('id' => 'perNombre', 'class' => 'validate','required','maxlength'=>'40')) !!}
                      {!! Form::label('perNombre', 'Nombre(s) *', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('perApellido1', $empleado->empApellido1, array('id' => 'perApellido1', 'class' => 'validate','required','maxlength'=>'30')) !!}
                      {!! Form::label('perApellido1', 'Primer apellido *', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('perApellido2', $empleado->empApellido2, array('id' => 'perApellido2', 'class' => 'validate','maxlength'=>'30')) !!}
                      {!! Form::label('perApellido2', 'Segundo apellido', array('class' => '')); !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('ubicacion_id', 'Campus *', array('class' => '')); !!}
                        <select id="ubicacion_id" class="browser-default validate select2" required name="ubicacion_id" style="width: 100%;">
                            @foreach($ubicaciones as $ubicacion)
                                @php
                                if($ubicacion->id == $empleado->escuela->departamento->ubicacion_id){
                                    echo '<option value="'.$ubicacion->id.'" selected>'.$ubicacion->ubiClave.'-'.$ubicacion->ubiNombre.'</option>';
                                }else{
                                    echo '<option value="'.$ubicacion->id.'">'.$ubicacion->ubiClave.' '.$ubicacion->ubiClave.'-'.$ubicacion->ubiNombre.'</option>';
                                }
                                @endphp
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select
                            id="departamento_id" data-departamento-id="{{$empleado->escuela->departamento_id}}"
                            class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                            <option value="{{$empleado->escuela->departamento_id}}" selected >
                                {{$empleado->escuela->departamento->depClave}}-{{$empleado->escuela->departamento->depNombre}}
                            </option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id" data-escuela-id="{{$empleado->escuela_id}}" class="browser-default validate select2" required name="escuela_id" style="width: 100%;">
                            <option value="{{$empleado->escuela_id}}">{{$empleado->escuela->escClave}}-{{$empleado->escuela->escNombre}}</option>
                        </select>
                    </div>
                </div>
              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('empCredencial', $empleado->empCredencial, array('id' => 'empCredencial', 'class' => 'validate','pattern' => '[A-Z,a-z,0-9]*','maxlength'=>'8')) !!}
                      {!! Form::hidden('empCredencialAnterior', $empleado->empCredencial, array('id' => 'empCredencialAnterior', 'class' => 'validate','pattern' => '[A-Z,a-z,0-9]*','maxlength'=>'8')) !!}
                      {!! Form::label('empCredencial', 'Clave credencial', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::number('empNomina', $empleado->empNomina, array('id' => 'empNomina', 'class' => 'validate','min'=>'0','max'=>'99999999999','onKeyPress="if(this.value.length==11) return false;"')) !!}
                      {!! Form::hidden('empNominaAnterior', $empleado->empNomina, array('id' => 'empNominaAnterior', 'class' => 'validate','min'=>'0','max'=>'99999999999','onKeyPress="if(this.value.length==11) return false;"')) !!}
                      {!! Form::label('empNomina', 'Clave nomina', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('empImss', $empleado->empNSS, array('id' => 'empImss', 'class' => 'validate','pattern' => '[A-Z,a-z,0-9]*','maxlength'=>'11')) !!}
                      {!! Form::label('empImss', 'Clave imss', array('class' => '')); !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('perCurp', $empleado->empCURP, array('id' => 'perCurp', 'class' => 'validate','required')) !!}
                      {!! Form::hidden('perCurpOld', $empleado->empCURP, ['id' => 'perCurpOld']) !!}
                      {!! Form::hidden('esCurpValida', NULL, ['id' => 'esCurpValida']) !!}
                      {!! Form::label('perCurp', 'Curp * (min 18 caracteres)', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('empRfc', $empleado->empRFC, array('id' => 'empRfc', 'class' => 'validate','pattern' => '[A-Z,a-z,0-9]*','maxlength'=>'13')) !!}
                      {!! Form::hidden('empRfcAnterior', $empleado->empRFC, array('id' => 'empRfcAnterior', 'class' => 'validate','pattern' => '[A-Z,a-z,0-9]*','maxlength'=>'13')) !!}
                      {!! Form::label('empRfc', 'RfC * (min 13 caracteres)', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::number('empHorasCon', $empleado->empHoras, array('id' => 'empHorasCon', 'class' => 'validate','required','min'=>'0','max'=>'99999999999','onKeyPress="if(this.value.length==11) return false;"')) !!}
                      {!! Form::label('empHorasCon', 'Horas *', array('class' => '')); !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::password('password', $empleado->password, array('id' => 'password', 'class' => 'validate','maxlength'=>'191')) !!}
                      {!! Form::label('password', 'Contraseña docente', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::password('password_confirmation', $empleado->password_confirmation, array('id' => 'password_confirmation', 'class' => 'validate','maxlength'=>'191')) !!}
                      {!! Form::label('password_confirmation', 'Confirmar contraseña', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                    {!! Form::label('empEstado', 'Estatus de Empleado *', array('class' => '')); !!}
                    <select id="empEstado" name="empEstado" class="browser-default validate select2" required style="width:100%;">
                      <option value="A">ALTA</option>
                      @if($puedeDarseDeBaja)
                        <option value="B">BAJA</option>
                      @endif
                    </select>
                  </div>
              </div>


              <div class="row">
                <div class="col s12 m6 l4">
                    {!! Form::label('empFechaIngreso', 'Fecha de ingreso *', array('class' => '')); !!}
                    {!! Form::date('empFechaIngreso', $empleado->empFechaIngreso, array('id' => 'empFechaIngreso', 'class' => 'validate','maxlength'=>'191', 'required')) !!}
                  </div>

                <div class="col s12 m6 l4">
                    {!! Form::label('puesto_id', 'Puesto del empleado *', array('class' => '')); !!}
                    <select id="puesto_id" class="browser-default validate select2" required name="puesto_id" style="width: 100%;">
                        <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        @foreach ($puestos as $puesto)
                            <option value="{{$puesto->id}}" {{ $empleado->puesto_id == $puesto->id ? 'selected="selected"' : '' }}>{{$puesto->puesNombre}}</option>
                        @endforeach
                    </select>
                </div>
              </div>
          </div>

          {{-- PERSONAL BAR--}}
          <div id="personal">

              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('perDirCalle', $empleado->empDireccionCalle, array('id' => 'perDirCalle', 'class' => 'validate','required','maxlength'=>'25')) !!}
                      {!! Form::label('perDirCalle', 'Calle *', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirNumExt', $empleado->empDireccionNumero, array('id' => 'perDirNumExt', 'class' => 'validate','required','maxlength'=>'6')) !!}
                        {!! Form::label('perDirNumExt', 'Número *', array('class' => '')); !!}
                        </div>
                    </div>
                  {{--  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('perDirNumInt', $empleado->persona->perDirNumInt, array('id' => 'perDirNumInt', 'class' => 'validate','maxlength'=>'6')) !!}
                      {!! Form::label('perDirNumInt', 'Número interior', array('class' => '')); !!}
                      </div>
                  </div>  --}}
              </div>

              <div class="row">
                  <div class="col s12 m6 l4">
                      {!! Form::label('paisId', 'País *', array('class' => '')); !!}
                      <select id="paisId" class="browser-default validate select2" required name="paisId" style="width: 100%;">
                          <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                          @foreach($paises as $pais)
                              <option value="{{$pais->id}}" @if($empleado->municipio->estado->pais->id == $pais->id) {{ 'selected' }} @endif>{{$pais->paisNombre}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="col s12 m6 l4">
                      {!! Form::label('estado_id', 'Estado *', array('class' => '')); !!}
                      <select id="estado_id" class="browser-default validate select2" required name="estado_id" style="width: 100%;">
                          <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                          @foreach($estados as $estado)
                              <option value="{{$estado->id}}" @if($empleado->municipio->estado->id == $estado->id) {{ 'selected' }} @endif>{{$estado->edoNombre}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="col s12 m6 l4">
                      {!! Form::label('municipio_id', 'Municipio *', array('class' => '')); !!}
                      <select id="municipio_id" class="browser-default validate select2" required name="municipio_id" style="width: 100%;">
                          <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                          @foreach($municipios as $municipio)
                              <option value="{{$municipio->id}}" @if($empleado->municipio->id == $municipio->id) {{ 'selected' }} @endif>{{$municipio->munNombre}}</option>
                          @endforeach
                      </select>
                  </div>
              </div>

              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::text('perDirColonia', $empleado->empDireccionColonia, array('id' => 'perDirColonia', 'class' => 'validate','required','maxlength'=>'60')) !!}
                      {!! Form::label('perDirColonia', 'Colonia *', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::number('perDirCP', $empleado->empDireccionCP, array('id' => 'perDirCP', 'class' => 'validate','required', 'pattern' => '[A-Z,a-z,0-9]*','min'=>'0','max'=>'99999','onKeyPress="if(this.value.length==5) return false;"')) !!}
                      {!! Form::label('perDirCP', 'Código Postal *', array('class' => '')); !!}
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col s12 m6 l4">
                    {!! Form::label('perSexo', 'Sexo *', array('class' => '')); !!}
                    <select id="perSexo" class="browser-default validate select2" required name="perSexo" style="width: 100%;">
                        <option value="M" @if($empleado->empSexo == "M") {{ 'selected' }} @endif>HOMBRE</option>
                        <option value="F" @if($empleado->empSexo == "F") {{ 'selected' }} @endif>MUJER</option>
                    </select>
                  </div>
                  <div class="col s12 m6 l4">
                      {!! Form::label('perFechaNac', 'Fecha de nacimiento *', array('class' => '')); !!}
                      {!! Form::date('perFechaNac', $empleado->empFechaNacimiento, array('id' => 'perFechaNac', 'class' => ' validate','required')) !!}
                  </div>
              </div>


              <div class="row">
                  {{--  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::number('perTelefono1', $empleado->empTelefono, array('id' => 'perTelefono1', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                      {!! Form::label('perTelefono1', 'Teléfono fijo', array('class' => '')); !!}
                      </div>
                  </div>  --}}
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::number('perTelefono2', $empleado->empTelefono, array('id' => 'perTelefono2', 'class' => 'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"')) !!}
                      {!! Form::label('perTelefono2', 'Teléfono móvil', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      {!! Form::email('perCorreo1', $empleado->empCorreo1, array('id' => 'perCorreo1', 'class' => 'validate','maxlength'=>'60')) !!}
                      {!! Form::label('perCorreo1', 'Correo', array('class' => '')); !!}
                      </div>
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

  {{-- Funciones para Modelo Persona --}}
  {!! HTML::script(asset('js/personas/personas.js'), array('type' => 'text/javascript'))!!}

@endsection

@section('footer_scripts')

    @include('primaria.scripts.funcionesAuxiliares')
    <script>


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

            function obtenerDepartamentos(ubicacionId) {
                console.log(ubicacionId);

                console.log("aqui")
                $("#departamento_id").empty();


                $("#escuela_id").empty();
                $("#periodo_id").empty();
                $("#programa_id").empty();
                $("#plan_id").empty();
                $("#cgt_id").empty();
                $("#materia_id").empty();
                $("#departamento_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#escuela_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#periodo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#programa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

                $("#perFechaInicial").val('');
                $("#perFechaFinal").val('');

                $.get(base_url+`/api/departamentos/${ubicacionId}`, function(res,sta) {

                    //seleccionar el post preservado
                    var departamentoSeleccionado = $("#departamento_id").data("departamento-id")
                    $("#departamento_id").empty()
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === departamentoSeleccionado) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }

                        $("#departamento_id").append(`<option value=${element.id} ${selected}>${element.depClave}-${element.depNombre}</option>`);
                    });
                    $('#departamento_id').trigger('change'); // Notify only Select2 of changes
                });
            }

            obtenerDepartamentos($("#ubicacion_id").val())
            $("#ubicacion_id").change( event => {
                obtenerDepartamentos(event.target.value)
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            function obtenerEscuelas (departamentoId) {

                console.log(departamentoId)
                $("#escuela_id").empty();

                $("#periodo_id").empty();
                $("#programa_id").empty();
                $("#plan_id").empty();
                $("#cgt_id").empty();
                $("#materia_id").empty();
                $("#escuela_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#periodo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#programa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

                $("#perFechaInicial").val('');
                $("#perFechaFinal").val('');



                $.get(base_url+`/api/escuelas/${departamentoId}`,function(res,sta){

                    //seleccionar el post preservado
                    var escuelaSeleccionada = $("#escuela_id").data("escuela-id")
                    $("#escuela_id").empty()

                    res.forEach(element => {
                        var selected = "";
                        if (element.id === escuelaSeleccionada) {
                            selected = "selected";
                        }

                        $("#escuela_id").append(`<option value=${element.id} ${selected}>${element.escClave}-${element.escNombre}</option>`);
                    });

                    $('#escuela_id').trigger('change'); // Notify only Select2 of changes

                });

                //OBTENER PERIODOS
                $.get(base_url+`/primaria_periodo/api/periodos/${departamentoId}`,function(res2,sta){
                    var perSeleccionado;


                    var periodoSeleccionadoOld = $("#periodo_id").data("periodo-idold")

                    console.log(periodoSeleccionadoOld)
                    $("#periodo_id").empty()
                    res2.forEach(element => {

                        var selected = "";
                        if (element.id === periodoSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }

                        $("#periodo_id").append(`<option value=${element.id} ${selected}>${element.perNumero}-${element.perAnio}</option>`);
                    });
                    //OBTENER FECHA INICIAL Y FINAL DEL PERIODO SELECCIONADO
                    $.get(base_url+`/primaria_periodo/api/periodo/${perSeleccionado}`,function(res3,sta){
                        $("#perFechaInicial").val(res3.perFechaInicial);
                        $("#perFechaFinal").val(res3.perFechaFinal);
                        Materialize.updateTextFields();
                    });

                    $('#periodo_id').trigger('change'); // Notify only Select2 of changes
                });//TERMINA PERIODO
            }


            $("#departamento_id").change( event => {
                obtenerEscuelas(event.target.value)
            });


            var empleado = {!! json_encode($empleado) !!};
            var puedeDarseDeBaja = {!! json_encode($puedeDarseDeBaja) !!};

            $('#empEstado').val(empleado.empEstado);





        });



    </script>


    @include('scripts.estados')
    @include('scripts.municipios')
@endsection
