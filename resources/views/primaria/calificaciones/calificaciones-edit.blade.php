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
<a href="#"
    class="breadcrumb">Editar calificación</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {!! Form::open(['enctype' => 'multipart/form-data', 'onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_calificacion.calificaciones.update_calificacion', 'method' => 'POST']) !!}

        {{--
        <div class="row">
            <input type="number" id="nuevo" lang="en" value="3.1" data-decimals="1" placeholder="1.0" step="0.1" min="0.0" max="10.0">
        </div>
        --}}

        <div class="card ">
            <div class="card-content ">
                <span class="card-title">CAPTURA DE CALIFICACIONES DEL GRUPO #{{$calificaciones[0]->primaria_grupo_id}}</span>

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
                                   {{$calificaciones[0]->perNumero}}-{{\Carbon\Carbon::parse($calificaciones[0]->perFechaInicial)->format('Y')}}
                                </option>

                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('primaria_grupo_id2', 'Grupo *', ['class' => '']); !!}
                            <select name="primaria_grupo_id2" id="primaria_grupo_id2"
                                class="browser-default validate select2" style="width: 100%;">
                                @if ($calificaciones[0]->matClaveAsignatura != "")
                                <option value="{{$calificaciones[0]->primaria_grupo_id}}">
                                    {{$calificaciones[0]->gpoGrado}}{{$calificaciones[0]->gpoClave}}, Prog:
                                    {{$calificaciones[0]->progClave}}, Asignatura: {{$calificaciones[0]->matClaveAsignatura}}-{{$calificaciones[0]->matNombreAsignatura}}</option>
                                @else
                                <option value="{{$calificaciones[0]->primaria_grupo_id}}">
                                    {{$calificaciones[0]->gpoGrado}}{{$calificaciones[0]->gpoClave}}, Prog:
                                    {{$calificaciones[0]->progClave}}, Materia: {{$calificaciones[0]->matClave}}-{{$calificaciones[0]->matNombre}}</option>
                                @endif

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
                    <h5 id="info"></h5>
                </div>

                <div class="row" style="display: none;" id="alerta-menos-de-ciente">
                    <div class="col s12 m6 l12">
                        <h6 style="color: red">
                            "Aún NO SE HAN DEFINIDO TODAS LAS EVIDENCIAS DE APRENDIZAJE para este mes (porcentaje menor al 100%). Favor de regresar al módulo de GRUPOS, EVIDENCIAS DE APRENDIZAJE, seleccione el mes y termine de ingresar las evidencias faltantes para llegar al 100%."

                        </h6>
                    </div>
                </div>
                <div class="row" style="display: none;" id="alerta-min-max-calif">
                    <div class="col s12 m6 l12">
                        <h6 style="color: red">
                            Nota:
                            <p>Calificación de captura mínima permitida es 5</p>
                            <p>Calificación de captura máxima permitida es 10</p>

                        </h6>                      
                    </div>
                </div>
                <div class="row" id="Tabla" style="display: none">
                    <div class="col s12">
                        <table class="responsive-table display hoverTable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="title1" style="display: none"><p>id cal</p></th>
                                    <th class="title2" style="display: none;" ><p>inscrito id</p></th>
                                    <th scope="col"> <p>#</p></th>
                                    <th scope="col">CLAVE <p>PAGO</p></th>
                                    <th scope="col">NOMBRE <p>COMPLETO</p></th>
                                        <th class="classEvi1" scope="col"><p id="nombreEvidencia1"></p> <p> <label style="color:#fff" id="evi1"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi2" scope="col"><p id="nombreEvidencia2"></p> <p> <label style="color:#fff" id="evi2"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi3" scope="col"><p id="nombreEvidencia3"></p> <p> <label style="color:#fff" id="evi3"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi4" scope="col"><p id="nombreEvidencia4"></p> <p> <label style="color:#fff" id="evi4"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi5" scope="col"><p id="nombreEvidencia5"></p> <p> <label style="color:#fff" id="evi5"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi6" scope="col"><p id="nombreEvidencia6"></p> <p> <label style="color:#fff" id="evi6"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi7" scope="col"><p id="nombreEvidencia7"></p> <p> <label style="color:#fff" id="evi7"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi8" scope="col"><p id="nombreEvidencia8"></p> <p> <label style="color:#fff" id="evi8"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi9" scope="col"><p id="nombreEvidencia9"></p> <p> <label style="color:#fff" id="evi9"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classEvi10" scope="col"><p id="nombreEvidencia10"></p> <p> <label style="color:#fff" id="evi10"></label> <label style="color:#fff">%</label></p></th>
                                        <th class="classPromedioMes" scope="col">PROMEDIO<p id="queMes">DEL MES</p></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

            <div class="card-action" id="btn-ocultar-si-es-menor-a-cien" style="display: none">
                <button type="submit" onclick="this.disabled=true;this.form.submit();this.innerText='Guardando datos...';" class="btn-guardar btn-large waves-effect darken-3"><i
                        class="material-icons left">save</i>Guardar</button>
            </div>
        </div>

    </div>
    {!! Form::close() !!}
</div>



<style>
    table tbody tr:nth-child(odd) {
        background: #F7F8F9;
    }
    table tbody tr:nth-child(even) {
        background: #F1F1F1;
    }
    table thead {
      background: #01579B;
      color: #fff;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }

    .checkbox-warning-filled [type="checkbox"][class*='filled-in']:checked+label:after {
        border-color: #FD8136;
        background-color: #FD8136;
      }      

      .hoverTable{
        width:100%; 
        border-collapse:collapse; 
    }
  
  
    /* Define the hover highlight color for the table row */
    .hoverTable tr:hover {
          background-color: #D2D7D8;
    }
</style>


@endsection

@section('footer_scripts')




@include('primaria.calificaciones.funcionesJSEdit')


@endsection
