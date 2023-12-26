@extends('layouts.dashboard')

@section('template_title')
Primaria calificaciones
@endsection

@section('head')

{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' =>
'stylesheet')) !!}
@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{url('primaria_curso/grupos_alumno/'.$curso_id)}}" class="breadcrumb">Lista de grupos alumno</a>
<a href="#" class="breadcrumb">Calificación alumno</a>
@endsection

@section('content')
<div class="row">
    <div class="col s12 "></div>
        {{ Form::open(['enctype' => 'multipart/form-data', 'method'=>'PATCH','route' => ['primaria_curso.ajustar_calificacion_update', $calificaciones->id]]) }}
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">AJUSTAR CALIFICACION</span>

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
                    <div class="col s12 m6 l4" style="display: none">
                        {!! Form::label('aluClave', 'Clave alumno *', ['class' => '']); !!}
                        <select name="aluClave" id="aluClave" class="browser-default validate select2"
                            style="width: 100%;">
                            <option value="{{$calificaciones->aluClave}}">
                                {{$calificaciones->aluClave}}
                            </option>

                        </select>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('periodo_id2', 'Periodo *', ['class' => '']); !!}
                            <select name="periodo_id2" id="periodo_id2" class="browser-default validate select2"
                                style="width: 100%;">
                                <option value="{{$calificaciones->periodo_id}}">
                                    {{$calificaciones->perFechaInicial}} al {{$calificaciones->perFechaFinal}}
                                </option>

                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('primaria_grupo_id2', 'Grupo *', ['class' => '']); !!}
                            <select name="primaria_grupo_id2" id="primaria_grupo_id2"
                                class="browser-default validate select2" style="width: 100%;">
                                <option value="{{$calificaciones->primaria_grupo_id}}">
                                    #{{$calificaciones->primaria_grupo_id}}, Grado: {{$calificaciones->gpoGrado}},
                                    Grupo: {{$calificaciones->gpoClave}}, Programa:
                                    {{$calificaciones->progClave}}-{{$calificaciones->progNombre}}
                                </option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('materia_id2', 'Materia *', ['class' => '']); !!}
                            <select name="materia_id2" id="materia_id2" class="browser-default validate select2"
                                style="width: 100%;">
                                <option value="{{$calificaciones->id_materia}}">{{$calificaciones->matNombre}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('primaria_grupo_evidencia_id', 'Mes de evaluación *', array('class' => ''));
                            !!}
                            <select id="primaria_grupo_evidencia_id" class="browser-default validate select2" required
                                name="primaria_grupo_evidencia_id" style="width: 100%;"
                                data-mes-idold="primaria_grupo_evidencia_id">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4" style="display: none">
                            {!! Form::label('mes', 'Mes de evaluación *', array('class' => '')); !!}
                            <select id="mes" class="browser-default validate select2" required name="mes"
                                style="width: 100%;" data-mes-idold="mes">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('numero_evaluacion', 'Número de evaluación *', array('class' => '')); !!}
                            <select id="numero_evaluacion" class="browser-default validate select2" required
                                name="numero_evaluacion" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4" style="margin-top: -9px">
                            <div class="input-field" id="input-field">
                                {!! Form::label('numero_evidencias', 'Total de evidencias a registrar *', array('class'
                                => '')); !!}
                                <input type="text" readonly="true" name="numero_evidencias" id="numero_evidencias"
                                    required>
                            </div>
                        </div>
                    </div>


                </div>
                <br>

                <div class="row">
                    <h5 id="info"></h5>
                </div>
                <div class="row" id="Tabla" style="display: none">
                    <div class="col s12">
                        <table class="responsive-table display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th scope="col">NOMBRE <p>COMPLETO</p></th>
                                    <th class="classEvi1" scope="col"><p id="nombreEvidencia1"></p> <p> <label style="color:black" id="evi1"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi2" scope="col"><p id="nombreEvidencia2"></p> <p> <label style="color:black" id="evi2"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi3" scope="col"><p id="nombreEvidencia3"></p> <p> <label style="color:black" id="evi3"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi4" scope="col"><p id="nombreEvidencia4"></p> <p> <label style="color:black" id="evi4"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi5" scope="col"><p id="nombreEvidencia5"></p> <p> <label style="color:black" id="evi5"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi6" scope="col"><p id="nombreEvidencia6"></p> <p> <label style="color:black" id="evi6"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi7" scope="col"><p id="nombreEvidencia7"></p> <p> <label style="color:black" id="evi7"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi8" scope="col"><p id="nombreEvidencia8"></p> <p> <label style="color:black" id="evi8"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi9" scope="col"><p id="nombreEvidencia9"></p> <p> <label style="color:black" id="evi9"></label> <label style="color:black">%</label></p></th>
                                    <th class="classEvi10" scope="col"><p id="nombreEvidencia10"></p> <p> <label style="color:black" id="evi10"></label> <label style="color:black">%</label></p></th>
                                    <th class="classPromedioMes" scope="col">PROMEDIO<p>DEL MES</p></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="card-action">
                <button type="submit" class="btn-guardar btn-large waves-effect darken-3"><i
                        class="material-icons left">save</i>Guardar</button>
            </div>
        </div>

    </div>
    {!! Form::close() !!}
</div>
</div>


@endsection

@section('footer_scripts')


{{--  obtener meses vigentes de evaluacion  --}}
<script type="text/javascript">
    $(document).ready(function() {

        function obtenerMesEvaluacion(mes_id) {

            $("#primaria_grupo_evidencia_id").empty();



            $.get(base_url+`/primaria_grupo/getMesEvidencias/${mes_id}`, function(res,sta) {

                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#primaria_grupo_evidencia_id").data("mes-idold")
                $("#primaria_grupo_evidencia_id").empty()

                if(res != ""){

                    res.forEach(element => {
                        var selected = "";
                        if (element.id === numeroEvaSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }

                        $("#primaria_grupo_evidencia_id").append(`<option value=${element.id} ${selected}>${element.mes}</option>`);

                    });
                    $('#primaria_grupo_evidencia_id').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#primaria_grupo_evidencia_id").append(`<option value="" selected disabled>NO HAY MES EVIDICENCIA PARA ESTE GRUPO</option>`);

                }

            });
        }

        obtenerMesEvaluacion($("#primaria_grupo_id2").val())
        $("#primaria_grupo_id2").change( event => {
            obtenerMesEvaluacion(event.target.value)
        });
     });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        function obtenerMeses(id_evidencia_grupo) {

            $("#mes").empty();



            $.get(base_url+`/primaria_calificacion/getMeses/${id_evidencia_grupo}`, function(res,sta) {

                //seleccionar el post preservado
                var mesesSeleccionadoOld = $("#mes").data("mes-idold")
                $("#mes").empty()

                if(res != ""){
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === mesesSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }

                        $("#mes").append(`<option value=${element.mes} ${selected}>${element.mes}</option>`);

                    });
                    $('#mes').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#mes").append(`<option value="" selected disabled>EL MES NO SE ENCUENTRA SELECCIONADO</option>`);
                }
            });
        }

        obtenerMeses($("#primaria_grupo_evidencia_id").val())
        $("#primaria_grupo_evidencia_id").change( event => {
            obtenerMeses(event.target.value)
        });
     });
</script>

  {{--  obtener numero de  evaluacion  --}}
  <script type="text/javascript">
    $(document).ready(function() {

        function obtenerNumEvaluacion(mes) {

            $("#numero_evaluacion").empty();



            $("#numero_evaluacion").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);


            $.get(base_url+`/primaria_calificacion/getNumeroEvaluacion/${mes}`, function(res,sta) {

                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#numero_evaluacion").data("numero-evaluacion-idold")
                $("#numero_evaluacion").empty()

                if(res != ""){
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === numeroEvaSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }

                        $("#numero_evaluacion").append(`<option value=${element.numero_evaluacion} ${selected}>${element.numero_evaluacion}</option>`);
                        $("#input-field").removeClass("input-field");
                        $("#numero_evidencias").val(element.numero_evidencias);

                        $("#numero_evaluacion").append(`<option value=${element.numero_evaluacion} ${selected}>${element.numero_evaluacion}</option>`);
                        $("#input-field").removeClass("input-field");
                        $("#numero_evidencias").val(element.numero_evidencias);

                        if(element.numero_evidencias == 1){


                            $(".classEvi2").hide();
                            $('td:nth-child(5)').hide();

                            $(".classEvi3").hide();
                            $('td:nth-child(6)').hide();

                            $(".classEvi4").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 2){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").hide();
                            $('td:nth-child(6)').hide();

                            $(".classEvi4").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 3){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 4){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 5){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 6){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 7){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 8){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(11)').show();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 9){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(11)').show();

                            $(".classEvi9").show();
                            $('td:nth-child(12)').show();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 10){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(11)').show();

                            $(".classEvi9").show();
                            $('td:nth-child(12)').show();
                            $(".classEvi10").show();
                            $('td:nth-child(13)').show();
                        }


                        //pintar los porcentajes de cada evidencia
                        if(element.porcentaje_evidencia1 != null){
                            $("#nombreEvidencia1").text(element.concepto_evidencia1);
                            $("#evi1").text(element.porcentaje_evidencia1);
                        }else{
                            $("#nombreEvidencia1").text("NA");
                            $("#evi1").text("NA");
                        }

                        if(element.porcentaje_evidencia2 != null){
                            $("#nombreEvidencia2").text(element.concepto_evidencia2);
                            $("#evi2").text(element.porcentaje_evidencia2);
                        }else{
                            $("#nombreEvidencia2").text("NA");
                            $("#evi2").text("NA");
                        }

                        if(element.porcentaje_evidencia3 != null){
                            $("#nombreEvidencia3").text(element.concepto_evidencia3);
                            $("#evi3").text(element.porcentaje_evidencia3);
                        }else{
                            $("#nombreEvidencia3").text("NA");
                            $("#evi3").text("NA");
                        }

                        if(element.porcentaje_evidencia4 != null){
                            $("#nombreEvidencia4").text(element.concepto_evidencia4);
                            $("#evi4").text(element.porcentaje_evidencia4);
                        }else{
                            $("#nombreEvidencia4").text("NA");
                            $("#evi4").text("NA");
                        }
                        if(element.porcentaje_evidencia5 != null){
                            $("#nombreEvidencia5").text(element.concepto_evidencia5);
                            $("#evi5").text(element.porcentaje_evidencia5);
                        }else{
                            $("#nombreEvidencia5").text("NA");
                            $("#evi5").text("NA");
                        }

                        if(element.porcentaje_evidencia6 != null){
                            $("#nombreEvidencia6").text(element.concepto_evidencia6);
                            $("#evi6").text(element.porcentaje_evidencia6);
                        }else{
                            $("#nombreEvidencia6").text("NA");
                            $("#evi6").text("NA");
                        }

                        if(element.porcentaje_evidencia7 != null){
                            $("#nombreEvidencia7").text(element.concepto_evidencia7);
                            $("#evi7").text(element.porcentaje_evidencia7);
                        }else{
                            $("#nombreEvidencia7").text("NA");
                            $("#evi7").text("NA");
                        }

                        if(element.porcentaje_evidencia8 != null){
                            $("#nombreEvidencia8").text(element.concepto_evidencia8);
                            $("#evi8").text(element.porcentaje_evidencia8);
                        }else{
                            $("#nombreEvidencia8").text("");
                            $("#evi8").text("NA");
                        }

                        if(element.porcentaje_evidencia9 != null){
                            $("#nombreEvidencia9").text(element.concepto_evidencia9);
                            $("#evi9").text(element.porcentaje_evidencia9);
                        }else{
                            $("#nombreEvidencia9").text("NA");
                            $("#evi9").text("NA");
                        }

                        if(element.porcentaje_evidencia10 != null){
                            $("#nombreEvidencia10").text(element.concepto_evidencia10);
                            $("#evi10").text(element.porcentaje_evidencia10);
                        }else{
                            $("#nombreEvidencia10").text("NA");
                            $("#evi10").text("NA");
                        }



                    });
                    $('#numero_evaluacion').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#numero_evaluacion").append(`<option value="" selected disabled>EL MES NO SE ENCUENTRA SELECCIONADO</option>`);
                }
            });
        }

        obtenerNumEvaluacion($("#mes").val())
        $("#mes").change( event => {
            obtenerNumEvaluacion(event.target.value)
        });
     });

  </script>

  <script type="text/javascript">
    $(document).ready(function() {

        function obtenerAlumnos(alumnoId, grupoId, aluClave) {
            $.get(base_url+`/primaria_curso/getCalificacionUnicoAlumno/${alumnoId}/${grupoId}/${aluClave}`, function(res,sta) {

                console.log(res)
                if(res == ""){
                    $(".btn-guardar").hide();
                    $("#Tabla").hide();
                    $("#info").html("No hay calificaciones registradas en el mes seleccionado");
                }else{
                    $(".btn-guardar").show();
                    $("#Tabla").show();
                    $("#info").html("");
                }
                    const data = res;

                        const tableData = data.map(function(element){

                            function calcularPromedio(id){
                                var parciales = 0;
                                var promedio  = 0;
                                $('.evidencia_' + element.primaria_inscrito_id).each(function(){
                                    if ($(this).val() != "") {
                                        parciales++;
                                        promedio = promedio + parseInt($(this).val());
                                    }
                                });
                                var parrafo1 = document.getElementById('evi1');
                                var porcentaje1 = parrafo1.innerHTML;

                                var parrafo2 = document.getElementById('evi2');
                                var porcentaje2 = parrafo2.innerHTML;

                                var parrafo3 = document.getElementById('evi3');
                                var porcentaje3 = parrafo3.innerHTML;

                                var parrafo4 = document.getElementById('evi4');
                                var porcentaje4 = parrafo4.innerHTML;

                                var parrafo5 = document.getElementById('evi5');
                                var porcentaje5 = parrafo5.innerHTML;

                                var parrafo6 = document.getElementById('evi6');
                                var porcentaje6 = parrafo6.innerHTML;

                                var parrafo7 = document.getElementById('evi7');
                                var porcentaje7 = parrafo7.innerHTML;

                                var parrafo8 = document.getElementById('evi8');
                                var porcentaje8 = parrafo8.innerHTML;

                                var parrafo9 = document.getElementById('evi9');
                                var porcentaje9 = parrafo9.innerHTML;

                                var parrafo10 = document.getElementById('evi10');
                                var porcentaje10 = parrafo10.innerHTML;

                                var numero_evidencias = $("#numero_evidencias").val();

                                if(numero_evidencias == 1){
                                    promedio = (promedio * (porcentaje1/100))
                                }

                                if(numero_evidencias == 2){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));

                                    promedio = res1 + res2;
                                }

                                if(numero_evidencias == 3){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));

                                    promedio = res1 + res2 + res3;
                                }

                                if(numero_evidencias == 4){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));

                                    promedio = res1 + res2 + res3 + res4;
                                }

                                if(numero_evidencias == 5){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));

                                    promedio = res1 + res2 + res3 + res4 + res5;
                                }

                                if(numero_evidencias == 6){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6;
                                }

                                if(numero_evidencias == 7){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7;
                                }

                                if(numero_evidencias == 8){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));
                                    var res8 = (promedio * (porcentaje8/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7 + res8;
                                }
                                if(numero_evidencias == 9){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));
                                    var res8 = (promedio * (porcentaje8/100));
                                    var res9 = (promedio * (porcentaje8/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7 + res8 + res9;
                                }

                                if(numero_evidencias == 10){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));
                                    var res8 = (promedio * (porcentaje8/100));
                                    var res9 = (promedio * (porcentaje8/100));
                                    var res10 = (promedio * (porcentaje8/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7 + res8 + res9 + res10;
                                }


                                promedio = promedio / parciales;


                                $('#promedioTotal' + element.primaria_inscrito_id).val(promedio);
                            }


                            $(function() {
                                $(".calif").on('change keyup', function(e) {
                                    var value = e.target.value
                                    console.log("entra")
                                    console.log(value)

                                    $(this).val(value || 0)


                                    if ($(this).data('inscritoid')) {

                                        var inscritoId = $(this).data('inscritoid')

                                        calcularPromedio(inscritoId)
                                    }
                                });


                            });
                            return (
                                `<tr>
                                    <td><input name='id' type='hidden' value='${element.id}'></td>
                                    <td><input name='primaria_inscrito_id' type='hidden' value='${element.primaria_inscrito_id}'></td>
                                    <td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>
                                    <td><input id='evidencia1' value='${element.calificacion_evidencia1}' name='evidencia1' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia2' value='${element.calificacion_evidencia2}' name='evidencia2' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia3' value='${element.calificacion_evidencia3}' name='evidencia3' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia4' value='${element.calificacion_evidencia4}' name='evidencia4' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia5' value='${element.calificacion_evidencia5}' name='evidencia5' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia6' value='${element.calificacion_evidencia6}' name='evidencia6' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia7' value='${element.calificacion_evidencia7}' name='evidencia7' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia8' value='${element.calificacion_evidencia8}' name='evidencia8' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia9' value='${element.calificacion_evidencia9}' name='evidencia9' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia10' value='${element.calificacion_evidencia10}' name='evidencia10' step="0.0" type='number' min="5" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input onmouseover="this.value = parseFloat(this.value).toFixed(1)" readonly='true' id='promedioTotal${element.primaria_inscrito_id}' name='promedioTotal' step="0.0" type='number' min="5" max="100" value='${element.promedio_mes}'></td>
                                </tr>`
                            );


                        }).join('');
                    const tabelBody = document.querySelector("#tableBody");
                        tableBody.innerHTML = tableData;

                        $("input[type='number']").blur(function() {
                            this.value = parseFloat(this.value).toFixed(1);
                        });



            });
        }

        obtenerAlumnos($("#primaria_grupo_evidencia_id").val(), $("#primaria_grupo_id2").val(), $("#aluClave").val())
        $("#primaria_grupo_evidencia_id").change( event => {
            obtenerAlumnos(event.target.value,$("#primaria_grupo_id2").val(),$("#aluClave").val())

        });
     });
  </script>

@endsection
