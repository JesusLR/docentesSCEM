@extends('layouts.dashboard')

@section('template_title')
    Primaria materia
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_materia')}}" class="breadcrumb">Lista de Materias</a>
    <label class="breadcrumb">Editar materia</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_materia.update', $materia->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR MATERIA #{{$materia->id}}</span>

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
                            <option value="{{$materia->plan->programa->escuela->departamento->ubicacion_id}}" selected >{{$materia->plan->programa->escuela->departamento->ubicacion->ubiClave}}-{{$materia->plan->programa->escuela->departamento->ubicacion->ubiNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                            <option value="{{$materia->plan->programa->escuela->departamento_id}}" selected >{{$materia->plan->programa->escuela->departamento->depClave}}-{{$materia->plan->programa->escuela->departamento->depNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id" class="browser-default validate select2" required name="escuela_id" style="width: 100%;">
                            <option value="{{$materia->plan->programa->escuela_id}}" selected >{{$materia->plan->programa->escuela->escClave}}-{{$materia->plan->programa->escuela->escNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                        <select id="programa_id" class="browser-default validate select2" required name="programa_id" style="width: 100%;">
                            <option value="{{$materia->plan->programa_id}}" selected >{{$materia->plan->programa->progClave}}-{{$materia->plan->programa->progNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                        <select id="plan_id" class="browser-default validate select2" required name="plan_id" style="width: 100%;">
                            <option value="{{$materia->plan_id}}" selected >{{$materia->plan->planClave}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matClave', $materia->matClave, array('id' => 'matClave', 'class' => 'validate', 'required', 'maxlength'=>'15')) !!}
                            {!! Form::hidden('matClaveAnterior', $materia->matClave, array('id' => 'matClaveAnterior', 'class' => 'validate', 'required', 'maxlength'=>'15')) !!}
                            {!! Form::label('matClave', 'Clave materia *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matNombre', $materia->matNombre, array('id' => 'matNombre', 'class' => 'validate','required','maxlength'=>'60')) !!}
                            {!! Form::label('matNombre', 'Nombre materia *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matNombreCorto', $materia->matNombreCorto, array('id' => 'matNombreCorto', 'class' => 'validate','required','maxlength'=>'15')) !!}
                            {!! Form::label('matNombreCorto', 'Nombre corto * (15 carateres)', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('matSemestre', 'Grado *', array('class' => '')); !!}
                        <select id="matSemestre" class="browser-default validate select2" required name="matSemestre" style="width: 100%;">
                            @for ($i = 1; $i <= $plan->planPeriodos; $i++)
                                <option value="{{$i}}" @if($materia->matSemestre == $i) {{ 'selected' }} @endif>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('matCreditos', $materia->matCreditos, array('id' => 'matCreditos', 'class' => 'validate','min'=>'0','max'=>'999','onKeyPress="if(this.value.length==3) return false;"')) !!}
                            {!! Form::label('matCreditos', 'Créditos', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('matClasificacion', 'Clasificación', array('class' => '')); !!}
                        <select id="matClasificacion" class="browser-default validate select2" name="matClasificacion" style="width: 100%;">
                            <option value="B" @if($materia->matClasificacion == "B") {{ 'selected' }} @endif>BÁSICA</option>
                            <option value="O" @if($materia->matClasificacion == "O") {{ 'selected' }} @endif>OPTATIVA</option>
                            <option value="U" @if($materia->matClasificacion == "U") {{ 'selected' }} @endif>OCUPA</option>
                            <option value="X" @if($materia->matClasificacion == "X") {{ 'selected' }} @endif>EXTRAOFICIAL</option>
                            <option value="C" @if($materia->matClasificacion == "C") {{ 'selected' }} @endif>COMPLEMENTARIA</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matEspecialidad', $materia->matEspecialidad, array('id' => 'matEspecialidad', 'class' => 'validate','maxlength'=>'3')) !!}
                            {!! Form::label('matEspecialidad', 'Especialidad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('matTipoAcreditacion', 'Tipo de acreditación', array('class' => '')); !!}
                        <select id="matTipoAcreditacion" class="browser-default validate select2" name="matTipoAcreditacion" style="width: 100%;">
                            <option value="N" @if($materia->matTipoAcreditacion == "N") {{ 'selected' }} @endif>NUMÉRICO</option>
                            <option value="A" @if($materia->matTipoAcreditacion == "A") {{ 'selected' }} @endif>ALFABÉTICO</option>
                            <option value="M" @if($materia->matTipoAcreditacion == "M") {{ 'selected' }} @endif>MIXTO</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('matPorcentajeParcial', $materia->matPorcentajeParcial, array('id' => 'matPorcentajeParcial', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                            {!! Form::label('matPorcentajeParcial', '% Examen parcial', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('matPorcentajeOrdinario', $materia->matPorcentajeOrdinario, array('id' => 'matPorcentajeOrdinario', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                            {!! Form::label('matPorcentajeOrdinario', '% Examen ordinario', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matNombreOficial', $materia->matNombreOficial, array('id' => 'matNombreOficial', 'class' => 'validate','maxlength'=>'78')) !!}
                            {!! Form::label('matNombreOficial', 'Nombre oficial', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('matOrdenVisual', $materia->matOrdenVisual, array('id' => 'matOrdenVisual', 'class' => 'validate','min'=>'0','max'=>'999','onKeyPress="if(this.value.length==3) return false;"')) !!}
                            {!! Form::label('matOrdenVisual', 'Orden visual', array('class' => '')); !!}
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
            $.get(base_url+`/api/planes/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#plan_id").append(`<option value=${element.id}>${element.planClave}</option>`);
                });
            });
        });

     });
</script>

<script type="text/javascript">

    $(document).ready(function() {
        // OBTENER MATERIA SEMESTRE Y SEMESTRE CGT
        $("#plan_id").change( event => {
            $("#matSemestre").empty();
            $("#matSemestre").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgtGradoSemestre").empty();
            $("#cgtGradoSemestre").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#gpoSemestre").empty();
            $("#gpoSemestre").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_plan/plan/semestre/${event.target.value}`,function(res,sta){
                //PARA PRIMARIA SON 6 GRADOS
                //for (i = 1; i <= res.planPeriodos; i++) {
                for (i = 1; i <= 6; i++) {
                    $("#matSemestre").append(`<option value="${i}">${i}</option>`);
                    $("#cgtGradoSemestre").append(`<option value="${i}">${i}</option>`);
                    $("#gpoSemestre").append(`<option value="${i}">${i}</option>`);
                }
            });
        });
     });
</script>

@endsection
