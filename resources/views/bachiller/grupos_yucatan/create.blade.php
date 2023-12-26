@extends('layouts.dashboard')

@section('template_title')
    Bachiller grupo
@endsection

@section('head')

{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_yucatan')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('bachiller.bachiller_grupo_yucatan.index')}}" class="breadcrumb">Lista de Grupo</a>
    <a href="{{ route('bachiller.bachiller_grupo_yucatan.create') }}" class="breadcrumb">Agregar Grupo</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller.bachiller_grupo_yucatan.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR GRUPO</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  {{-- <li class="tab"><a href="#equivalente">Equivalente</a></li> --}}
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('ubicacion_id', 'Ubicación *', array('class' => '')); !!}
                        <select id="ubicacion_id" class="browser-default validate select2" required name="ubicacion_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($ubicaciones as $ubicacion)
                                @php
                                    $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;

                                    $selected = '';
                                    if ($ubicacion->id == $ubicacion_id) {
                                        $selected = 'selected';
                                    }

                                    if ($ubicacion->id == old("ubicacion_id")) {
                                        $selected = 'selected';
                                    }
                                @endphp
                                <option value="{{$ubicacion->id}}" {{$selected}}>{{$ubicacion->ubiClave ."-". $ubicacion->ubiNombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id"
                            data-departamento-idold="{{old('departamento_id')}}"
                            class="browser-default validate select2"
                            required name="departamento_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id"
                            data-escuela-idold="{{old('escuela_id')}}"
                            class="browser-default validate select2"
                            required name="escuela_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('periodo_id', 'Periodo *', array('class' => '')); !!}
                        <select id="periodo_id"
                            data-periodo-idold="{{old('periodo_id')}}"
                            class="browser-default validate select2"
                            required name="periodo_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', NULL, array('id' => 'perFechaInicial', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', NULL, array('id' => 'perFechaFinal', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                        <select id="programa_id"
                            data-programa-idold="{{old('programa_id')}}"
                            class="browser-default validate select2"
                            required name="programa_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                        <select id="plan_id"
                            data-plan-idold="{{old('plan_id')}}"
                            class="browser-default validate select2"
                            required name="plan_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div style="display: none;" class="col s12 m6 l4">
                        {!! Form::label('gpoExtraCurr', 'Es Materia Extracurricular *', array('class' => '')); !!}
                        <select id="gpoExtraCurr"
                            class="browser-default validate select2"
                            required name="gpoExtraCurr" style="width: 100%;">
                            <option value="N" selected>NO</option>
                            <option value="S">SI</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('gpoSemestre', 'Grado *', array('class' => '')); !!}
                        <input class="gpoSemestreOld" type="hidden" data-gpoSemestre-idold="{{old('gpoSemestre')}}">
                        <select id="gpoSemestre"
                            data-gpoSemestre-idold="{{old('gpoSemestre')}}"
                            class="browser-default validate select2"
                            required name="gpoSemestre" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                   
                    <div class="col s12 m6 l4">
                        {!! Form::label('gpoTurno', 'Turno *', array('class' => '')); !!}
                        <select id="gpoTurno" class="browser-default validate select2" required name="gpoTurno" style="width: 100%;">
                            <option value="M" {{old('gpoTurno') == "M" ? "selected": ""}}>MATUTINO</option>
                            <option value="V" {{old('gpoTurno') == "V" ? "selected": ""}}>VESPERTINO</option>
                            <option value="X" {{old('gpoTurno') == "X" ? "selected": ""}}>MIXTO</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col s12 l6">
                        {!! Form::label('materia_id', 'Materia *', array('class' => '')); !!}
                        <select id="materia_id" class="browser-default validate select2" name="materia_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>

                    <div class="col s12 m6 l3">
                        <div class="input-field">
                        {!! Form::text('gpoClave', NULL, array( 'id' => 'gpoClave',
                            'class' => 'validate','required','maxlength' => '3',
                            'value' => old("gpoClave") ? old("gpoClave"): "" )) !!}
                        {!! Form::label('gpoClave', 'Clave grupo *', ['class' => '']); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l3" style="margin-top:30px;">
                        <div style="position:relative;">
                            <input type="checkbox" {{ (! empty(old('gpoACD')) ? 'checked' : '') }} name="gpoACD" id="gpoACD" value="">
                            <label for="gpoACD"> ¿Es un grupo ACD?</label>
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col s12 l6">
                        {!! Form::label('gpoMatComplementaria', 'Materia complementaria *', array('class' => '')); !!}
                        <select id="gpoMatComplementaria" class="browser-default validate select2" name="gpoMatComplementaria" style="width: 100%;" disabled>
                            {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                        </select>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('gpoCupo', NULL, array('id' => 'gpoCupo', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                        {!! Form::label('gpoCupo', 'Cupo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('empleado_id', 'Docente titular *', array('class' => '')); !!}
                        <select id="empleado_id" class="browser-default validate select2" required name="empleado_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($empleados as $empleado)
                                <option value="{{$empleado->id}}" @if(old('empleado_id') == $empleado->id) {{ 'selected' }} @endif>{{$empleado->id ." - ".$empleado->empNombre ." ". $empleado->empApellido1." ".$empleado->empApellido2}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('empleado_id_auxiliar', 'Docente auxiliar ', array('class' => '')); !!}
                        <select id="empleado_id_auxiliar" class="browser-default validate select2" name="empleado_id_auxiliar" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($empleados as $empleado)
                                <option value="{{$empleado->id}}" @if(old('empleado_id_auxiliar') == $empleado->id) {{ 'selected' }} @endif>{{$empleado->id ." - ".$empleado->empNombre ." ". $empleado->empApellido1." ".$empleado->empApellido2}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--  <div style="display: none" class="col s12 m6 l4">
                        {!! Form::label('gpoFechaExamenOrdinario', 'Fecha examen ordinario', array('class' => '')); !!}
                        {!! Form::date('gpoFechaExamenOrdinario', NULL, array('id' => 'gpoFechaExamenOrdinario', 'class' => 'validate')) !!}
                    </div>
                    <div style="display: none" class="col s12 m6 l4">
                        {!! Form::label('gpoHoraExamenOrdinario', 'Hora examen ordinario', array('class' => '')); !!}
                        {!! Form::time('gpoHoraExamenOrdinario', NULL, array('id' => 'gpoHoraExamenOrdinario', 'class' => 'validate')) !!}
                    </div>  --}}
                </div>
                {{--  <div class="row">

                </div>  --}}
                <div style="display: none" class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroFolio', NULL, array('id' => 'gpoNumeroFolio', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('gpoNumeroFolio', 'Folio', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroActa', NULL, array('id' => 'gpoNumeroActa', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('gpoNumeroActa', 'Acta', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroLibro', NULL, array('id' => 'gpoNumeroLibro', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('gpoNumeroLibro', 'Libro', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
            </div>



          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-guardar btn-large waves-effect  darken-3']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
</div>



@endsection

@section('footer_scripts')
{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}

<script type="text/javascript">
    $(document).ready(function() {



        $(document).on("click", ".btn-guardar", function(e) {
            e.preventDefault();
            swal({
                title: "Captura de grupo",
                text: "¿Está seguro que desea guardar a este grupo?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#0277bd',
                confirmButtonText: 'SI',
                cancelButtonText: "NO",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    postGuardarGrupoBachiller()
                    swal.close()
                } else {
                    swal.close()
                }
            });
        });

        function postGuardarGrupoBachiller () {
            $.ajax({
                data: {
                    "_token": $("meta[name=csrf-token]").attr("content"),
                    "ubicacion_id": $("#ubicacion_id").val(),
                    "departamento_id": $("#departamento_id").val(),
                    "escuela_id": $("#escuela_id").val(),
                    "periodo_id": $("#periodo_id").val(),
                    "perFechaInicial": $("#perFechaInicial").val(),
                    "perFechaFinal": $("#perFechaFinal").val(),
                    "programa_id": $("#programa_id").val(),
                    "plan_id": $("#plan_id").val(),
                    "gpoSemestre": $("#gpoSemestre").val(),
                    "gpoClave": $("#gpoClave").val(),
                    "gpoTurno": $("#gpoTurno").val(),
                    "materia_id": $("#materia_id").val(),
                    "optativa_id": $("#optativa_id").val(),
                    "gpoCupo": $("#gpoCupo").val(),

                    "empleado_id": $("#empleado_id").val(),
                    "empleado_id_auxiliar": $("#empleado_id_auxiliar").val(),
                    "gpoNumeroFolio": $("#gpoNumeroFolio").val(),
                    "gpoNumeroActa": $("#gpoNumeroActa").val(),
                    "gpoNumeroLibro": $("#gpoNumeroLibro").val(),
                    "programa_equivalente": $("#programa_equivalente").val(),
                    "materia_equivalente": $("#materia_equivalente").val(),
                    "plan_equivalente": $("#plan_equivalente").val(),
                    "cgt_equivalente": $("#cgt_equivalente").val(),
                    "grupo_equivalente_id": $("#grupo_equivalente_id").val(),
                    "gpoExtraCurr": $('#gpoExtraCurr').val(),
                    "gpoACD": $('#gpoACD').val(),
                    "gpoMatComplementaria": $('#gpoMatComplementaria').val()



                },
                type: "POST",
                dataType: "JSON",
                url: base_url + "/bachiller_grupo_yucatan",
            })
            .done(function( data, textStatus, jqXHR ) {
                if (data.res) {
                    $('#materia_id').val("");
                    $('#materia_id').select2().trigger('change');

                    $('#gpoCupo').val("");

                    $('#empleado_id').val("");
                    $('#empleado_id').select2().trigger('change');

                    $('#empleado_id_auxiliar').val("");
                    $('#empleado_id_auxiliar').select2().trigger('change');

                    $('#gpoNumeroFolio').val("");
                    $('#gpoNumeroActa').val("");
                    $('#gpoNumeroLibro').val("");


                    $("#grupo_creado_id").val(data.data.id);
                    $("#empleado_creado_id").val(data.data.empleado_id);

                    /*cancelarSeleccionado()*/


                    /*$("#ubicacion_id").prop( "disabled", true  );
                    $("#departamento_id").prop( "disabled", true  );
                    $("#escuela_id").prop( "disabled", true  );
                    $("#periodo_id").prop( "disabled", true  );
                    $("#perFechaInicial").prop( "disabled", true  );
                    $("#perFechaFinal").prop( "disabled", true  );
                    $("#programa_id").prop( "disabled", true  );
                    $("#plan_id").prop( "disabled", true  );
                    $("#gpoSemestre").prop( "disabled", true  );
                    $("#gpoClave").prop( "disabled", true  );
                    $("#gpoTurno").prop( "disabled", true  );*/

                    //$(".btn-guardar").hide();

                    swal({
                        title: "Escuela Modelo",
                        text: "El grupo se ha creado con éxito",
                        icon: "success",
                    });
                }

                if (!data.res) {
                    console.log(data.msg)

                    if (data.existeGrupo) {
                        var html = "";
                        html += "<p style='text-align:left;'><b>GRUPO:</b> #" +  data.msg.id + "</p>"
                        html += "<p style='text-align:left;'><b>Grado-Grupo-Turno:</b> "
                            + data.msg.gpoGrado +"-" +data.msg.gpoClave + "-" + data.msg.gpoTurno
                        + "</p>"
                        html += "<p style='text-align:left;'><b>Materia:</b>"
                            + " " + data.msg.bachiller_materia.matNombre
                        + "</p>"
                        html += "<p style='text-align:left;'><b>Maestro:</b>"
                            + " " + data.msg.bachiller_empleado.empNombre
                            + " " + data.msg.bachiller_empleado.empApellido1
                            + " " + data.msg.bachiller_empleado.empApellido2
                        + "</p>"
                        html += "<p>" + "</p>"

                        swal({
                            html:true,
                            title: "El grupo ya existe",
                            text: html,
                            confirmButtonText: "Ok",
                        })

                    } else {
                        var htmlErr = ""
                        $.each(data.msg, function( index, value ) {
                            htmlErr += "<p>" + value[0] + "</p>";
                        });
                        $("body").append('<div class="error message dismissible">'
                            + '<i class="material-icons status">&#xE645;</i>'
                            + '<h4>Error</h4>'
                            + htmlErr
                        +'</div>')
                        $(document).on("click", ".dismissible", function () {
                            $(this).remove()
                        })
                    }


                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                console.log(textStatus)
                console.log(jqXHR)
            });
        }

        var grupo_creado_id = $('#grupo_creado_id').val();
        var claveMaestro = $('#empleado_creado_id').val();
        var periodoId = $('#periodo_id').val();


    });
</script>


@include('bachiller.scripts.preferencias')
@include('bachiller.scripts.departamentos')
@include('bachiller.scripts.escuelas')
@include('bachiller.scripts.programas')
@include('bachiller.scripts.planes')
@include('bachiller.scripts.periodos')
@include('bachiller.scripts.grados')
@include('bachiller.scripts.materias')
@include('bachiller.scripts.grupos')
{{-- @include('scripts.optativas') --}}

<script>
    $("input[name=gpoACD]").change(function(){
        if ($(this).is(':checked') ) {
     
            $("#gpoACD").val("1");
            $("#materia_id").prop('required', false);
            $("#gpoMatComplementaria").prop('required', true);
            $("#gpoMatComplementaria").prop('disabled', false);


            //por materia 
            $("#materia_id").change( event => {
                var plan_id = $("#plan_id").val();
                var periodo_id = $("#periodo_id").val();
                var gpoSemestre = $("#gpoSemestre").val();
                $("#gpoMatComplementaria").empty();
                $("#gpoMatComplementaria").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $.get(base_url+`/bachiller_grupo_yucatan/materiaComplementaria/${event.target.value}/${plan_id}/${periodo_id}/${gpoSemestre}`,function(resACD,sta){
                    resACD.forEach(materiaACD => {
                        $("#gpoMatComplementaria").append(`<option value="${materiaACD.gpoMatComplementaria}">${materiaACD.gpoMatComplementaria}</option>`);
                    });
                });
            });  
            
            //por periodo 
            $("#periodo_id").change( event => {
                var plan_id = $("#plan_id").val();
                var materia_id = $("#materia_id").val();
                var gpoSemestre = $("#gpoSemestre").val();
                console.log($("periodo_id").val())

                $("#gpoMatComplementaria").empty();
                $("#gpoMatComplementaria").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $.get(base_url+`/bachiller_grupo_yucatan/materiaComplementaria/${materia_id}/${plan_id}/${event.target.value}/${gpoSemestre}`,function(resACD,sta){
                    resACD.forEach(materiaACD => {
                        $("#gpoMatComplementaria").append(`<option value="${materiaACD.gpoMatComplementaria}">${materiaACD.gpoMatComplementaria}</option>`);
                    });
                });
            });    

            //por plan
            $("#plan_id").change( event => {
                var periodo_id = $("#periodo_id").val();
                var materia_id = $("#materia_id").val();
                var gpoSemestre = $("#gpoSemestre").val();
                $("#gpoMatComplementaria").empty();
                $("#gpoMatComplementaria").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $.get(base_url+`/bachiller_grupo_yucatan/materiaComplementaria/${materia_id}/${event.target.value}/${periodo_id}/${gpoSemestre}`,function(resACD,sta){
                    resACD.forEach(materiaACD => {
                        $("#gpoMatComplementaria").append(`<option value="${materiaACD.gpoMatComplementaria}">${materiaACD.gpoMatComplementaria}</option>`);
                    });
                });
            });    
 

            //Carga las materias complementarias
            var materia_id = $("#materia_id").val();
            var plan_id = $("#plan_id").val();
                var periodo_id = $("#periodo_id").val();
                var gpoSemestre = $("#gpoSemestre").val();
                $("#gpoMatComplementaria").empty();
                $("#gpoMatComplementaria").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $.get(base_url+`/bachiller_grupo_yucatan/materiaComplementaria/${materia_id}/${plan_id}/${periodo_id}/${gpoSemestre}`,function(resACD,sta){
                    resACD.forEach(materiaACD => {
                        $("#gpoMatComplementaria").append(`<option value="${materiaACD.gpoMatComplementaria}">${materiaACD.gpoMatComplementaria}</option>`);
                    });
                });
         


    
        } else {
            $("#gpoACD").val("0");
            $("#materia_id").prop('required', true);
            $("#gpoMatComplementaria").prop('required', false);
            $("#gpoMatComplementaria").prop('disabled', true);


            $("#gpoMatComplementaria").empty();
            $("#gpoMatComplementaria").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

        }
    });

</script>


@endsection
