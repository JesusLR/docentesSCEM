@extends('layouts.dashboard')

@section('template_title')
    Secundaria grupo evidencia
@endsection

@section('head')

{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('secundaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('secundaria.secundaria_grupo.index')}}" class="breadcrumb">Lista de Grupo</a>
    <a href="{{ url('secundaria_grupo/'.$grupo->id.'/evidencia') }}" class="breadcrumb">Agregar evidencia</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'secundaria.secundaria_grupo.guardar_actualizar_evidencia', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EVIDENCIA GRUPO #{{$grupo->id}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  {{-- <li class="tab"><a href="#equivalente">Equivalente</a></li> --}}
                </ul>
              </div>
            </nav>

            <br>
            {{-- GENERAL BAR--}}
            <div id="general">
                {{-- <input type="hidden" name="secundaria_grupo_id" value="{{$grupo->id}}"> --}}
                <div class="col s12 m6 l4" style="display: none">
                    {!! Form::label('secundaria_grupo_id', 'Grupo *', array('class' => '')); !!}
                    <select id="secundaria_grupo_id" class="browser-default validate select2" required name="secundaria_grupo_id" style="width: 100%;">
                        <option value="{{$grupo->id}}">{{$grupo->id}}</option>
                    </select>
                </div>
             
                <div class="row">
                    <div class="col s12 m6 l4">
                        <p><b>Período: </b>{{$grupo->periodo->perAnioPago}}</p>
                        <p><b>Materia: </b>{{$grupo->secundaria_materia->matNombre}}</p>
                        <p><b>Grado-Grupo: </b>{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}</p>
                        <p><b>Docente: </b>{{$grupo->secundaria_empleado->empNombre}} {{$grupo->secundaria_empleado->empApellido1}} {{$grupo->secundaria_empleado->empApellido2}}</p>


                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('secundaria_mes_evaluacion_id', 'Mes *', array('class' => '')); !!}
                        <select id="secundaria_mes_evaluacion_id" class="browser-default validate select2" required name="secundaria_mes_evaluacion_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @if ($Evidencias != "")
                                @foreach ($meses as $mes)
                                    <option value="{{$mes->id}}" {{ $Evidencias->secundaria_mes_evaluacion_id == $mes->id ? 'selected="selected"' : '' }}>{{$mes->mes}}</option>
                                @endforeach
                            @else
                                @foreach ($meses as $mes)
                                    <option value="{{$mes->id}}">{{$mes->mes}}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>

                    <div class="col s12 m6 l4" style="margin-top: -10px">
                        <div class="input-field">
                            {!! Form::label('numero_evidencias', 'Número de evidencias *', array('class' => '')); !!}
                            <input type="number" onclick="cuentaTotalEvidencia()" onKeyDown="cuentaTotalEvidencia()" onKeyUp="cuentaTotalEvidencia()" name="numero_evidencias" id="numero_evidencias" min="1" max="10" required>
                        </div>
                    </div>

                    <div class="col s12 m6 l4" style="margin-top: 15px">
                        <label for=""></label>
                        <div style="position:relative;">
                            <input type="checkbox" name="aplicar" id="aplicar" value="SOLO UNO">
                            <label for="aplicar"> Aplicar para todos los meses restantes</label>
                        </div>
                    </div>
                </div>

                <div class="row" id="div1" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia1', 'Concepto evidencia 1', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia1" name="concepto_evidencia1" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia1', 'Porcentaje evidencia 1', array('class' => '')); !!}
                            <input id='porcentaje_evidencia1' name='porcentaje_evidencia1' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>


                <div class="row" id="div2" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia2', 'Concepto evidencia 2', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia2" name="concepto_evidencia2" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia2', 'Porcentaje evidencia 2', array('class' => '')); !!}
                            <input id='porcentaje_evidencia2' name='porcentaje_evidencia2' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>


                <div class="row" id="div3" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia3', 'Concepto evidencia 3', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia3" name="concepto_evidencia3" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia3', 'Porcentaje evidencia 3', array('class' => '')); !!}
                            <input id='porcentaje_evidencia3' name='porcentaje_evidencia3' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>



                <div class="row" id="div4" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia4', 'Concepto evidencia 4', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia4" name="concepto_evidencia4" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia4', 'Porcentaje evidencia 4', array('class' => '')); !!}
                            <input id='porcentaje_evidencia4' name='porcentaje_evidencia4' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>


                {{-- evidencia 5 --}}
                <div class="row" id="div5" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia5', 'Concepto evidencia 5', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia5" name="concepto_evidencia5" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia5', 'Porcentaje evidencia 5', array('class' => '')); !!}
                            <input id='porcentaje_evidencia5' name='porcentaje_evidencia5' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>

                {{-- evidencia 6  --}}
                <div class="row" id="div6" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia6', 'Concepto evidencia 6', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia6" name="concepto_evidencia6" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia6', 'Porcentaje evidencia 6', array('class' => '')); !!}
                            <input id='porcentaje_evidencia6' name='porcentaje_evidencia6' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>


                {{-- evidencia 7  --}}
                <div class="row" id="div7" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia7', 'Concepto evidencia 7', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia7" name="concepto_evidencia7" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia7', 'Porcentaje evidencia 7', array('class' => '')); !!}
                            <input id='porcentaje_evidencia7' name='porcentaje_evidencia7' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>



                {{-- evidencia 8  --}}
                <div class="row" id="div8" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia8', 'Concepto evidencia 8', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia8" name="concepto_evidencia8" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia8', 'Porcentaje evidencia 8', array('class' => '')); !!}
                            <input id='porcentaje_evidencia8' name='porcentaje_evidencia8' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>



                {{-- evidencia 9  --}}
                <div class="row" id="div9" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia9', 'Concepto evidencia 9', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia9" name="concepto_evidencia9" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('porcentaje_evidencia9', 'Porcentaje evidencia 9', array('class' => '')); !!}
                            <input id='porcentaje_evidencia9' name='porcentaje_evidencia9' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>



                {{-- evidencia 10  --}}
                <div class="row" id="div10" style="display: none;">
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field">
                            {!! Form::label('concepto_evidencia10', 'Concepto evidencia 10', array('class' => '')); !!}
                            <input type="text" class="validate decimal" id="concepto_evidencia10" name="concepto_evidencia10" onKeyDown="cuentaLetras()" onKeyUp="cuentaLetras()" maxlength="25">
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="input-field clase-input-field" id="input-field10">
                            {!! Form::label('porcentaje_evidencia10', 'Porcentaje evidencia 10', array('class' => '')); !!}
                            <input id='porcentaje_evidencia10' name='porcentaje_evidencia10' step='0.0' type='number' min='0' max='100' class='validate porcentaje'>
                        </div>
                    </div>
                </div>

                <div class="row" style="display: none" id="divPorcentaje">
                    <div class="col s12 m6 l6">
                        <input type="number" readonly="true" step='0.0' id="porcentajeTotal" name="porcentajeTotal" maxlength="25" value="0">
                        {!! Form::label('porcentajeTotal', 'Porcentaje total', array('class' => '')); !!}
                    </div>
                </div>


            </div>

          </div>
          <div class="card-action">
            <button type="submit" class="btn-guardar btn-large waves-effect darken-3"><i class="material-icons left">save</i>Guardar</button>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
</div>



@endsection

@section('footer_scripts')


@include('secundaria.grupos.funcionesJS')

<script type="text/javascript">
    $(document).ready(function() {

        function obtenerDatos(grupo_id,mes_id) {



            $.get(base_url+`/secundaria_calificacion/api/getEvidencias/${grupo_id}/${mes_id}`, function(res,sta) {

                console.log(res)

                if(res != ""){
                    res.forEach(element => {
                        $("#numero_evidencias").val(element.numero_evidencias);
                        $(".input-field").removeClass("input-field");
                        if(element.numero_evidencias == 1){
                            $("#div1").show();
                            $("#div2").hide();
                            $("#div3").hide();
                            $("#div4").hide();
                            $("#div5").hide();
                            $("#div6").hide();
                            $("#div7").hide();
                            $("#div8").hide();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }

                        if(element.numero_evidencias == 2){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").hide();
                            $("#div4").hide();
                            $("#div5").hide();
                            $("#div6").hide();
                            $("#div7").hide();
                            $("#div8").hide();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }

                        if(element.numero_evidencias == 3){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").hide();
                            $("#div5").hide();
                            $("#div6").hide();
                            $("#div7").hide();
                            $("#div8").hide();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }
                        if(element.numero_evidencias == 4){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").show();
                            $("#div5").hide();
                            $("#div6").hide();
                            $("#div7").hide();
                            $("#div8").hide();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }

                        if(element.numero_evidencias == 5){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").show();
                            $("#div5").show();
                            $("#div6").hide();
                            $("#div7").hide();
                            $("#div8").hide();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }

                        if(element.numero_evidencias == 6){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").show();
                            $("#div5").show();
                            $("#div6").show();
                            $("#div7").hide();
                            $("#div8").hide();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }
                        if(element.numero_evidencias == 7){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").show();
                            $("#div5").show();
                            $("#div6").show();
                            $("#div7").show();
                            $("#div8").hide();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }

                        if(element.numero_evidencias == 8){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").show();
                            $("#div5").show();
                            $("#div6").show();
                            $("#div7").show();
                            $("#div8").show();
                            $("#div9").hide();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }

                        if(element.numero_evidencias == 9){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").show();
                            $("#div5").show();
                            $("#div6").show();
                            $("#div7").show();
                            $("#div8").show();
                            $("#div9").show();
                            $("#div10").hide();
                            $("#divPorcentaje").show();
                        }
                        if(element.numero_evidencias == 10){
                            $("#div1").show();
                            $("#div2").show();
                            $("#div3").show();
                            $("#div4").show();
                            $("#div5").show();
                            $("#div6").show();
                            $("#div7").show();
                            $("#div8").show();
                            $("#div9").show();
                            $("#div10").show();
                            $("#divPorcentaje").show();
                        }

                         $("#concepto_evidencia1").val(element.concepto_evidencia1);
                         $("#porcentaje_evidencia1").val(element.porcentaje_evidencia1);

                         $("#concepto_evidencia2").val(element.concepto_evidencia2);
                         $("#porcentaje_evidencia2").val(element.porcentaje_evidencia2);

                         $("#concepto_evidencia3").val(element.concepto_evidencia3);
                         $("#porcentaje_evidencia3").val(element.porcentaje_evidencia3);

                         $("#concepto_evidencia4").val(element.concepto_evidencia4);
                         $("#porcentaje_evidencia4").val(element.porcentaje_evidencia4);

                         $("#concepto_evidencia5").val(element.concepto_evidencia5);
                         $("#porcentaje_evidencia5").val(element.porcentaje_evidencia5);

                         $("#concepto_evidencia6").val(element.concepto_evidencia6);
                         $("#porcentaje_evidencia6").val(element.porcentaje_evidencia6);

                         $("#concepto_evidencia7").val(element.concepto_evidencia7);
                         $("#porcentaje_evidencia7").val(element.porcentaje_evidencia7);

                         $("#concepto_evidencia8").val(element.concepto_evidencia8);
                         $("#porcentaje_evidencia8").val(element.porcentaje_evidencia8);

                         $("#concepto_evidencia9").val(element.concepto_evidencia9);
                         $("#porcentaje_evidencia9").val(element.porcentaje_evidencia9);

                         $("#concepto_evidencia10").val(element.concepto_evidencia10);
                         $("#porcentaje_evidencia10").val(element.porcentaje_evidencia10);

                         $("#porcentajeTotal").val(element.porcentaje_total)

                    });
                }else{
                    $("#numero_evidencias").val("");
                    $("#concepto_evidencia1").val("");
                    $("#porcentaje_evidencia1").val("");

                    $("#concepto_evidencia2").val("");
                    $("#porcentaje_evidencia2").val("");

                    $("#concepto_evidencia3").val("");
                    $("#porcentaje_evidencia3").val("");

                    $("#concepto_evidencia4").val("");
                    $("#porcentaje_evidencia4").val("");

                    $("#concepto_evidencia5").val("");
                    $("#porcentaje_evidencia5").val("");

                    $("#concepto_evidencia6").val("");
                    $("#porcentaje_evidencia6").val("");

                    $("#concepto_evidencia7").val("");
                    $("#porcentaje_evidencia7").val("");

                    $("#concepto_evidencia8").val("");
                    $("#porcentaje_evidencia8").val("");

                    $("#concepto_evidencia9").val("");
                    $("#porcentaje_evidencia9").val("");

                    $("#concepto_evidencia10").val("");
                    $("#porcentaje_evidencia10").val("");

                    $("#porcentajeTotal").val(0);

                    $(".clase-input-field").addClass("input-field");

                    //ocultar
                    $("#div1").hide();

                    $("#div2").hide();

                    $("#div3").hide();

                    $("#div4").hide();

                    $("#div5").hide();

                    $("#div6").hide();

                    $("#div7").hide();

                    $("#div8").hide();

                    $("#div9").hide();

                    $("#div10").hide();

                    $("#divPorcentaje").hide();
                }

            });
        }

        obtenerDatos($("#secundaria_grupo_id").val(),$("#secundaria_mes_evaluacion_id").val())
        $("#secundaria_mes_evaluacion_id").change( event => {
            obtenerDatos($("#secundaria_grupo_id").val(),event.target.value)
        });
     });
  </script>

@endsection

