@extends('layouts.dashboard')

@section('template_title')
    Primaria Cgt
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_cgt')}}" class="breadcrumb">Lista de Cgt</a>
    <a href="{{url('primaria_cgt/'.$cgt->id.'/edit')}}" class="breadcrumb">Editar cgt</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_cgt.update', $cgt->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR CGT #{{$cgt->id}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('ubicacion_id', 'Campus *', array('class' => '')); !!}
                        <select id="ubicacion_id" class="browser-default validate select2" required name="ubicacion_id" style="width: 100%;">
                            <option value="{{$cgt->plan->programa->escuela->departamento->ubicacion_id}}" selected>
                                {{$cgt->plan->programa->escuela->departamento->ubicacion->ubiClave}}-{{$cgt->plan->programa->escuela->departamento->ubicacion->ubiNombre}}
                            </option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id" data-departamento-idold="{{$cgt->plan->programa->escuela->departamento_id}}" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                            <option value="{{$cgt->plan->programa->escuela->departamento_id}}" selected>
                                {{$cgt->plan->programa->escuela->departamento->depClave}}-{{$cgt->plan->programa->escuela->departamento->depNombre}}
                            </option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id" data-escuela-idold="{{$cgt->plan->programa->escuela_id}}" class="browser-default validate select2" required name="escuela_id" style="width: 100%;">
                            <option value="{{$cgt->plan->programa->escuela_id}}" selected>{{$cgt->plan->programa->escuela->escClave}}-{{$cgt->plan->programa->escuela->escNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('periodo_id', 'Periodo *', array('class' => '')); !!}
                        <select id="periodo_id" data-periodo-idold="{{$cgt->periodo_id}}" class="browser-default validate select2" required name="periodo_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($periodos as $periodo)
                                <option value="{{$periodo->id}}" @if($cgt->periodo_id == $periodo->id) {{ 'selected' }} @endif>
                                    {{$periodo->perNumero ." - ".$periodo->perAnio}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', $cgt->periodo->perFechaInicial, array('id' => 'perFechaInicial', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', $cgt->periodo->perFechaFinal, array('id' => 'perFechaFinal', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                        <select id="programa_id" data-programa-idold={{$cgt->plan->programa->id}} class="browser-default validate select2" required name="programa_id" style="width: 100%;">
                            <option value="{{$cgt->plan->programa->id}}">{{$cgt->plan->programa->progClave}}-{{$cgt->plan->programa->progNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                        <select id="plan_id" data-plan-idold="{{$cgt->plan->id}}" class="browser-default validate select2" required name="plan_id" style="width: 100%;">
                            <option value="{{$cgt->plan->id}}">{{$cgt->plan->planClave}}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('cgtGradoSemestre', 'Grado/Semestre *', array('class' => '')); !!}
                        <select id="cgtGradoSemestre" data-gposemestre-idold={{$cgt->cgtGradoSemestre}} class="browser-default validate select2" required name="cgtGradoSemestre" style="width: 100%;">
                            <option value="{{$cgt->cgtGradoSemestre}}">{{$cgt->cgtGradoSemestre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('cgtGrupo', $cgt->cgtGrupo, array('id' => 'cgtGrupo', 'class' => 'validate','required','readonly','maxlength'=>'3')) !!}
                        {!! Form::label('cgtGrupo', 'Grupo *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('cgtTurno', 'Turno *', array('class' => '')); !!}
                        <select id="cgtTurno" class="browser-default validate select2" required name="cgtTurno" style="width: 100%;">
                            <option value="M" {{$cgt->cgtTurno == "M" ? "selected":""}}>Matutino</option>
                            <option value="V" {{$cgt->cgtTurno == "V" ? "selected":""}}>Vespertino</option>
                            <option value="M" {{$cgt->cgtTurno == "X" ? "selected":""}}>Mixto</option>

                        </select>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('cgtCupo', $cgt->cgtCupo, array('id' => 'cgtCupo', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                        {!! Form::label('cgtCupo', 'Cupo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m8">
                        {!! Form::label('empleado_id', 'Maestro titular *', array('class' => '')); !!}
                        <select id="empleado_id" class="browser-default validate select2" required name="empleado_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($empleados as $empleado)
                                <option value="{{$empleado->id}}"  {{ ($cgt->empleado_id == $empleado->id) ? 'selected': '' }} >
                                    {{$empleado->id ." - ".$empleado->persona->perNombre ." ". $empleado->persona->perApellido1." ". $empleado->persona->perApellido2}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                        {!! Form::textarea('cgtDescripcion', $cgt->cgtDescripcion, ['id' => 'cgtDescripcion', 'class' => 'materialize-textarea','rows' => 2, 'cols' => 40,'data-length' => "30", 'readonly'=>'true']) !!}
                        {!! Form::label('cgtDescripcion', 'Descripción', ['class' => '']); !!}
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




@endsection

@section('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {

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
                var departamentoSeleccionadoOld = $("#departamento_id").data("departamento-idold")
                $("#departamento_id").empty()
                res.forEach(element => {
                    var selected = "";
                    if (element.id === departamentoSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                        $("#departamento_id").append(`<option value=${element.id} ${selected}>${element.depClave}-${element.depNombre}</option>`);
                    }
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
                var escuelaSeleccionadoOld = $("#escuela_id").data("escuela-idold")
                $("#escuela_id").empty()

                res.forEach(element => {
                    var selected = "";
                    if (element.id === escuelaSeleccionadoOld) {
                        console.log('escuelaSeleccionada '+element.id);
                        selected = "selected";
                        $("#escuela_id").append(`<option value=${element.id} ${selected}>${element.escClave}-${element.escNombre}</option>`);
                    }
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
                        $("#periodo_id").append(`<option value=${element.id} ${selected}>${element.perNumero}-${element.perAnio}</option>`);
                    }

                });
                //OBTENER FECHA INICIAL Y FINAL DEL PERIODO SELECCIONADO
                $.get(base_url+`/primaria_periodo/periodo/${perSeleccionado}`,function(res3,sta){
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
     });
</script>
@include('primaria.scripts.programas')
@include('primaria.scripts.planes')
@include('primaria.scripts.periodos')
@include('primaria.scripts.grados')

<script>
 // OBTENER MATERIA SEMESTRE Y SEMESTRE CGT
    $("#plan_id").change( event => {
        $("#cgtGradoSemestre").empty();
        $("#cgtGradoSemestre").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);




        $.get(base_url + `/primaria_plan/plan/semestre/${event.target.value}`, function(res,sta) {
            //seleccionar el post preservado
            var gpoSemestreSeleccionadoOld = $("#cgtGradoSemestre").data("gposemestre-idold")
            console.log(gpoSemestreSeleccionadoOld)

            $("#cgtGradoSemestre").empty()
            for (i = 1; i <= res.planPeriodos; i++) {
                var selected = "";
                if (i === gpoSemestreSeleccionadoOld) {
                    selected = "selected";
                }


                $("#cgtGradoSemestre").append(`<option value="${i}" ${selected}>${i}</option>`);
            }

            $('#cgtGradoSemestre').trigger('change'); // Notify only Select2 of changes
        });
    });
</script>
@endsection
