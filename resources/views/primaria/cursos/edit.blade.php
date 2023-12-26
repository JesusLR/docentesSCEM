@extends('layouts.dashboard')

@section('template_title')
    Primaria curso
@endsection

@section('head')
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Lista de Preinscripción</a>
    <a href="{{url('primaria_curso/'.$curso->id.'/edit')}}" class="breadcrumb">Editar preinscripción</a>
@endsection

@section('content')
@php
    use App\Models\User;
@endphp

<div class="row">
    <div class="col s12 ">
        {{ Form::open(['enctype' => 'multipart/form-data', 'method'=>'PUT','route' => ['primaria_curso.update', $curso->id]]) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR PREINSCRIPCIÓN #{{$curso->id}} - CLAVE DE ALUMNO(A): {{$curso->alumno->aluClave}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                    @if (User::permiso("curso") != "P")
                    <li class="tab"><a class="active" href="#general">General</a></li>
                    @endif
                    @if (User::permiso("curso") == "A" || User::permiso("curso") == "E" || User::permiso("curso") == "P")
                    <li class="tab"><a href="#cuotas">Cuotas</a></li>
                    <li class="tab"><a href="#becas">Becas</a></li>
                    @endif
                </ul>
              </div>
            </nav>

            @if (User::permiso("curso") != "P")
            {{-- GENERAL BAR--}}
            <div id="general">
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('ubicacion_id', 'Campus *', ['class' => '']); !!}
                        <select id="ubicacion_id" class="browser-default validate select2" required name="ubicacion_id" style="width: 100%;">
                            <option value="{{$curso->cgt->plan->programa->escuela->departamento->ubicacion_id}}" selected >{{$curso->cgt->plan->programa->escuela->departamento->ubicacion->ubiClave}}-{{$curso->cgt->plan->programa->escuela->departamento->ubicacion->ubiNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', ['class' => '']); !!}
                        <select id="departamento_id" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                            <option value="{{$curso->cgt->plan->programa->escuela->departamento_id}}" selected >{{$curso->cgt->plan->programa->escuela->departamento->depClave}}-{{$curso->cgt->plan->programa->escuela->departamento->depNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', ['class' => '']); !!}
                        <select id="escuela_id" class="browser-default validate select2" required name="escuela_id" style="width: 100%;">
                            <option value="{{$curso->cgt->plan->programa->escuela_id}}" selected >{{$curso->cgt->plan->programa->escuela->escClave}}-{{$curso->cgt->plan->programa->escuela->escNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('periodo_id', 'Periodo *', ['class' => '']); !!}
                        <select id="periodo_id" class="browser-default validate select2" required name="periodo_id" style="width: 100%;">
                            <option value="{{$curso->periodo->id}}">{{$curso->periodo->perNumero ." - ".$curso->periodo->perAnio}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', $curso->cgt->periodo->perFechaInicial, ['id' => 'perFechaInicial', 'class' => 'validate','readonly']) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', $curso->cgt->periodo->perFechaFinal, ['id' => 'perFechaFinal', 'class' => 'validate','readonly']) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', ['class' => '']); !!}
                        <select id="programa_id" class="browser-default validate select2" required name="programa_id" style="width: 100%;">
                            <option value="{{$curso->cgt->plan->programa->id}}">{{$curso->cgt->plan->programa->progClave}}-{{$curso->cgt->plan->programa->progNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('plan_id', 'Plan *', ['class' => '']); !!}
                        <select id="plan_id" class="browser-default validate select2" required name="plan_id" style="width: 100%;">
                            <option value="{{$curso->cgt->plan->id}}">{{$curso->cgt->plan->planClave}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('cgt_id', 'CGT *', ['class' => '']); !!}
                        <select id="cgt_id" class="browser-default validate select2" required name="cgt_id" style="width: 100%;">
                            @foreach ($cgts as $cgt)
                                <option value="{{$cgt->id}}" {{$cgt->id == $curso->cgt->id ? "selected": ""}}>
                                    {{$cgt->cgtGradoSemestre . '-' . $cgt->cgtGrupo . '-' . $cgt->cgtTurno}}
                                </option>
                            @endforeach
                            <!-- <option value="{{$curso->cgt->id}}">{{$curso->cgt->cgtGradoSemestre.'-'.$curso->cgt->cgtGrupo.'-'.$curso->cgt->cgtTurno}}</option> -->
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m8">
                        {!! Form::label('alumno_id', 'Alumno *', ['class' => '']); !!}
                        <select id="alumno_id" class="browser-default validate select2" required name="alumno_id" style="width: 100%;">
                            <option value="{{$curso->alumno->id}}">
                                {{$curso->alumno->aluClave}}-{{$curso->alumno->persona->perNombre}}
                                {{$curso->alumno->persona->perApellido1}}
                                {{$curso->alumno->persona->perApellido2}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    @if($permiso == 'A' || $permiso == 'B')
                        <div class="col s12 m6 l4">
                            {!! Form::label('curEstado', 'Estado del curso *', ['class' => '']); !!}
                            <select name="curEstado" id="curEstado" required class="browser-default validate select2" style="width: 100%;">
                                @foreach($estadoCurso as $key => $value)
                                    <option value="{{$key}}" @if($curso->curEstado == $key) {{ 'selected' }} @endif>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col s12 m6 l4">
                        {!! Form::label('curTipoIngreso', 'Tipo de ingreso *', ['class' => '']); !!}
                        <select name="curTipoIngreso" id="curTipoIngreso" required class="browser-default validate select2" style="width: 100%;">
                            @foreach($tiposIngreso as $key => $value)
                                <option value="{{$key}}" @if($curso->curTipoIngreso == $key) {{ 'selected' }} @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4" style="visibility: hidden;">
                        {!! Form::label('curOpcionTitulo', 'Opción de titulo', ['class' => '']); !!}
                        <select name="curOpcionTitulo" id="curOpcionTitulo" class="browser-default validate select2" style="width: 100%;">
                            <option value="S" @if($curso->curOpcionTitulo == "S") {{ 'selected' }} @endif>SI</option>
                            <option value="N" @if($curso->curOpcionTitulo == "N") {{ 'selected' }} @endif>NO</option>
                        </select>
                    </div>

                </div>

                <div class="row">

                    <div class="col s12 m6 l8">
                        <div class="file-field input-field">
                            <div class="btn">
                            <span>Foto del Curso (.jpg)</span>
                            <input value="" type="file" name="curPrimariaFoto">
                            </div>
                            <div class="file-path-wrapper">
                            <input class="file-path validate"  type="text">
                            </div>
                        </div>
                        @if ($curso->curPrimariaFoto)
                            <img style="width:200px;" src="{{url('/primaria_curso_images/' . $curso->curPrimariaFoto) }}" alt="">
                        @endif
                    </div>
                    <div class="col s12 m6 l4" style="visibility: hidden;">
                        <div class="input-field">
                            {!! Form::number('curExani', $curso->curExani, ['id' => 'curExani','', 'min' => '0', 'max' => '1300']) !!}
                            {!! Form::label('curExani', 'Resultado Calificación Exani', ['class' => '']); !!}
                        </div>
                    </div>

                </div>
            </div>



            @endif


            @if (User::permiso("curso") == "A" || User::permiso("curso") == "E" || User::permiso("curso") == "P")
            {{-- CUOTAS BAR--}}
            <div id="cuotas">
                <div class="row">
                    <div class="col s4">
                        <div class="input-field">
                        {!! Form::number('curAnioCuotas', $curso->curAnioCuotas, ['id' => 'curAnioCuotas', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"']) !!}
                        {!! Form::label('curAnioCuotas', 'Año cuota', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curImporteInscripcion', $curso->curImporteInscripcion, ['id' => 'curImporteInscripcion', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"']) !!}
                        {!! Form::label('curImporteInscripcion', 'Importe inscripción', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curImporteMensualidad', $curso->curImporteMensualidad, ['id' => 'curImporteMensualidad', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"']) !!}
                        {!! Form::label('curImporteMensualidad', 'Importe mensual', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('curImporteVencimiento', $curso->curImporteVencimiento, ['id' => 'curImporteVencimiento', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"']) !!}
                            {!! Form::label('curImporteVencimiento', 'Importe vencido', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curImporteDescuento', $curso->curImporteDescuento, ['id' => 'curImporteDescuento', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"']) !!}
                        {!! Form::label('curImporteDescuento', 'Descuento pronto pago', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curDiasProntoPago', $curso->curDiasProntoPago, ['id' => 'curDiasProntoPago', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"']) !!}
                        {!! Form::label('curDiasProntoPago', 'Días pronto pago', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('curPlanPago', 'Plan de pago', ['class' => '']); !!}
                        <select name="curPlanPago" id="curPlanPago" class="browser-default validate select2" style="width: 100%;">
                            @foreach($planesPago as $key => $value)
                                <option value="{{$key}}" @if($curso->curPlanPago == $key) {{ 'selected' }} @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{-- BECAS BAR--}}
            <div id="becas">
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('curTipoBeca', 'Tipo de beca', ['class' => '']); !!}
                        <select name="curTipoBeca" id="curTipoBeca" class="browser-default validate select2" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($tiposBeca as $value)
                                <option value="{{$value->bcaClave}}" @if($curso->curTipoBeca == $value->bcaClave) {{ 'selected' }} @endif>
                                    {{$value->bcaNombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curPorcentajeBeca', $curso->curPorcentajeBeca, ['id' => 'curPorcentajeBeca', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"']) !!}
                        {!! Form::label('curPorcentajeBeca', '% Beca', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m8">
                        <div class="input-field">
                        {!! Form::textarea('curObservacionesBeca', $curso->curObservacionesBeca, ['id' => 'curObservacionesBeca', 'class' => 'materialize-textarea','rows' => 2, 'cols' => 40,'data-length' => "255"]) !!}
                        {!! Form::label('curObservacionesBeca', 'Observaciones', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
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

        $("#ubicacion_id").change( event => {
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
            $.get(base_url+`/api/departamentos/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#departamento_id").append(`<option value=${element.id}>${element.depClave}-${element.depNombre}</option>`);
                });
            });
        });
     });
</script>


<script type="text/javascript">
    $(document).ready(function() {

        $("#departamento_id").change( event => {
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
            $.get(base_url+`/api/escuelas/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#escuela_id").append(`<option value=${element.id}>${element.escClave}-${element.escNombre}</option>`);
                });
            });
            $.get(base_url+`/primaria_periodo/api/periodos/${event.target.value}`,function(res2,sta){
                var perSeleccionado;
                res2.forEach(element => {
                    $("#periodo_id").append(`<option value=${element.id}>${element.perNumero}-${element.perAnio}</option>`);
                });
                //OBTENER FECHA INICIAL Y FINAL DEL PERIODO SELECCIONADO
                $.get(base_url+`/primaria_periodo/api/periodo/${perSeleccionado}`,function(res3,sta){
                    $("#perFechaInicial").val(res3.perFechaInicial);
                    $("#perFechaFinal").val(res3.perFechaFinal);
                    Materialize.updateTextFields();
                });
            });//TERMINA PERIODO
        });

     });
</script>

<script type="text/javascript">

    $(document).ready(function() {

        $("#escuela_id").change( event => {
            $("#programa_id").empty();
            $("#plan_id").empty();
            $("#cgt_id").empty();
            $("#materia_id").empty();
            $("#programa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_programa/api/programas/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#programa_id").append(`<option value=${element.id}>${element.progNombre}</option>`);
                });
            });
        });

     });
</script>

<script>
    $(document).on('click', '#agregarPrograma', function (e) {
        var programa_id = $("#programa_id").val();
        if(programa_id != "" && programa_id != null){
            if(recorrerProgramas(programa_id)){
                $.get(base_url+`/primaria_programa/api/programa/${programa_id}`,function(res,sta){
                $("#seccion-programas").show();
                $('#tbl-programas> tbody:last-child').append(`<tr id="programa${res.id}">
                        <td>${res.escuela.escNombre}</td>
                        <td>${res.progClave}</td>
                        <td>${res.progNombre}</td>
                        <td><input name="programas[${res.id}]" type="hidden" value="${res.id}" readonly="true"/>
                        <a href="javascript:;" onclick="eliminarPrograma(${res.id})" class="button button--icon js-button js-ripple-effect" title="Eliminar">
                            <i class="material-icons">delete</i>
                        </a>
                        </td>
                    </tr>`);
                });
            }else{
                swal({
                    title: "Ups...",
                    text: "El programa ya se encuentra agregado",
                    type: "warning",
                    confirmButtonText: "Ok",
                    confirmButtonColor: '#3085d6',
                    showCancelButton: false
                });
            }
        }else{
            swal({
                title: "Ups...",
                text: "Debes seleccionar al menos un programa",
                type: "warning",
                confirmButtonText: "Ok",
                confirmButtonColor: '#3085d6',
                showCancelButton: false
            });
        }
    });

    function recorrerProgramas(id){
        encontro = true;
        $('#tbl-programas tr').each(function() {
            if(this.id == 'programa'+id){
                encontro = false;
                return false;
            }
        });
        return encontro;
    }

    function eliminarPrograma(id){
        $('#programa'+id).remove();
    }
</script>

<script type="text/javascript">

    $(document).ready(function() {
        // OBTENER PLANES
        $("#programa_id").change( event => {
            $("#plan_id").empty();
            $("#cgt_id").empty();
            $("#materia_id").empty();
            $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_plan/api/planes/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#plan_id").append(`<option value=${element.id}>${element.planClave}</option>`);
                });
            });
        });

     });
</script>

<script type="text/javascript">

    $(document).ready(function() {

        //OBTENER FECHA PERIODO
        $("#periodo_id").change( event => {
            $("#perFechaInicial").val('');
            $("#perFechaFinal").val('');
            //INSCRITOS
            $("#curso_id").empty();
            $("#curso_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#grupo_id").empty();
            $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            Materialize.updateTextFields();
            $.get(base_url+`/primaria_periodo/api/periodo/${event.target.value}`,function(res,sta){
                $("#perFechaInicial").val(res.perFechaInicial);
                $("#perFechaFinal").val(res.perFechaFinal);
                Materialize.updateTextFields();
            });
        });

     });
</script>

<script type="text/javascript">

    $(document).ready(function() {

        // OBTENER CGTS POR PLAN
        $("#plan_id").change( event => {
            var periodo_id = $("#periodo_id").val();
            $("#cgt_id").empty();
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").empty();
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_cgt/api/cgts/${event.target.value}/${periodo_id}`,function(res,sta){
                res.forEach(element => {
                    $("#cgt_id").append(`<option value=${element.id}>${element.cgtGradoSemestre}-${element.cgtGrupo}-${element.cgtTurno}</option>`);
                });
            });
        });

        // OBTENER CGTS POR PERIODO
        $("#periodo_id").change( event => {
            var plan_id = $("#plan_id").val();
            $("#cgt_id").empty();
            $("#materia_id").empty();
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_cgt/api/cgts/${plan_id}/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#cgt_id").append(`<option value=${element.id}>${element.cgtGradoSemestre}-${element.cgtGrupo}-${element.cgtTurno}</option>`);
                });
            });
        });

     });
</script>

<script type="text/javascript">

    $(document).ready(function() {

        // OBTENER MATERIAS POR SEMESTRE SELECCIONADO
        $("#gpoSemestre").change( event => {
            var plan_id = $("#plan_id").val();
            $("#materia_id").empty();
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_materia/materias/${event.target.value}/${plan_id}`,function(res,sta){
                res.forEach(element => {
                    $("#materia_id").append(`<option value=${element.id}>${element.matClave}-${element.matNombre}</option>`);
                });
            });
        });

     });
</script>


@endsection
