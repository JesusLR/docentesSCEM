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
<a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Lista de Grupo</a>
<a href="{{ url('primaria_grupo/calificaciones/'.$calificaciones[0]->primaria_grupo_id.'/edit') }}"
    class="breadcrumb">Editar calificacion</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {{ Form::open(['enctype' => 'multipart/form-data', 'method'=>'PATCH','route' => ['primaria_calificacion.calificaciones.update_calificacion', $calificaciones[0]->id]]) }}
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR CALIFICACIONES GRUPO #{{$calificaciones[0]->primaria_grupo_id}}</span>

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
                            {!! Form::label('periodo_id2', 'Periodo *', ['class' => '']); !!}
                            <select name="periodo_id2" id="periodo_id2" class="browser-default validate select2"
                                style="width: 100%;">
                                <option value="{{$calificaciones[0]->periodo_id}}">
                                    {{$calificaciones[0]->perFechaInicial}} al {{$calificaciones[0]->perFechaFinal}}
                                </option>

                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('primaria_grupo_id2', 'Grupo *', ['class' => '']); !!}
                            <select name="primaria_grupo_id2" id="primaria_grupo_id2"
                                class="browser-default validate select2" style="width: 100%;">
                                <option value="{{$calificaciones[0]->primaria_grupo_id}}">
                                    #{{$calificaciones[0]->primaria_grupo_id}}, Grado: {{$calificaciones[0]->gpoGrado}},
                                    Grupo: {{$calificaciones[0]->gpoClave}}, Programa:
                                    {{$calificaciones[0]->progClave}}-{{$calificaciones[0]->progNombre}}</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('materia_id2', 'Materia *', ['class' => '']); !!}
                            <select name="materia_id2" id="materia_id2" class="browser-default validate select2"
                                style="width: 100%;">
                                <option value="{{$calificaciones[0]->id_materia}}">{{$calificaciones[0]->matNombre}}
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
                    <div class="col s12">
                        <table class="responsive-table display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Evidencia 1</th>
                                    <th scope="col">Evidencia 2</th>
                                    <th scope="col">Evidencia 3</th>
                                    <th scope="col">Evidencia 4</th>
                                    <th scope="col">Evidencia 5</th>
                                    <th scope="col">Evidencia 6</th>
                                    <th scope="col">Evidencia 7</th>
                                    <th scope="col">Evidencia 8</th>
                                    <th scope="col">Evidencia 9</th>
                                    <th scope="col">Evidencia 10</th>
                                    <th scope="col">Total</th>
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

        function obtenerAlumnos(alumnoId, grupoId) {
            $.get(base_url+`/api/getCalificacionesAlumnos/${alumnoId}/${grupoId}`, function(res,sta) {

                if(res == ""){
                    $(".btn-guardar").hide();
                }else{
                    $(".btn-guardar").show();
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
                                promedio = promedio / parciales;

                                promedio = promedio + 0.5

                                promedio = Math.trunc(promedio);


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
                                    <td><input name='id[]' type='hidden' value='${element.id}'></td>
                                    <td><input name='primaria_inscrito_id[]' type='hidden' value='${element.primaria_inscrito_id}'></td>
                                    <td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>
                                    <td><input id='evidencia1' value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia2' value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia3' value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia4' value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia5' value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia6' value='${element.calificacion_evidencia6}' name='evidencia6[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia7' value='${element.calificacion_evidencia7}' name='evidencia7[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia8' value='${element.calificacion_evidencia8}' name='evidencia8[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia9' value='${element.calificacion_evidencia9}' name='evidencia9[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input id='evidencia10' value='${element.calificacion_evidencia10}' name='evidencia10[]' step="0.0" type='number' min="0" max="100" class='calif evidencia_${element.primaria_inscrito_id}' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input onmouseover="this.value = parseFloat(this.value).toFixed(1)" readonly='true' id='promedioTotal${element.primaria_inscrito_id}' name='promedioTotal[]' step="0.0" type='number' min="0" max="100" value='${element.promedio_mes}'></td>
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

        obtenerAlumnos($("#primaria_grupo_evidencia_id").val(), $("#primaria_grupo_id2").val())
        $("#primaria_grupo_evidencia_id").change( event => {
            obtenerAlumnos(event.target.value,$("#primaria_grupo_id2").val())

        });
     });
  </script>

@endsection
