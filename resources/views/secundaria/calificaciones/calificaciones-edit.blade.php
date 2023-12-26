@extends('layouts.dashboard')

@section('template_title')
Secundaria calificaciones
@endsection

@section('head')
{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' =>
'stylesheet')) !!}
@endsection

@section('breadcrumbs')
<a href="{{url('secundaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{route('secundaria.secundaria_grupo.index')}}" class="breadcrumb">Lista de Grupo</a>
<a href="{{url('secundaria_calificacion/grupo/'.$calificaciones[0]->grupo_id.'/edit')}}" class="breadcrumb">Editar calificación</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {{ Form::open(['enctype' => 'multipart/form-data', 'method'=>'PATCH','route' => ['secundaria.secundaria_calificacion.calificaciones.update_calificacion', $calificaciones[0]->id]]) }}
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR CALIFICACIONES GRUPO #{{$calificaciones[0]->grupo_id}}</span>

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
                            {!! Form::label('periodo_id2', 'Ciclo escolar *', ['class' => '']); !!}
                            <select name="periodo_id2" id="periodo_id2" class="browser-default validate select2"
                                style="width: 100%;">
                                <option value="{{$calificaciones[0]->periodo_id}}">
                                    {{\Carbon\Carbon::parse($calificaciones[0]->perFechaInicial)->format('Y')}}-{{\Carbon\Carbon::parse($calificaciones[0]->perFechaFinal)->format('Y')}}
                                </option>

                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('grupo_id2', 'Grado-Grupo *', ['class' => '']); !!}
                            <select name="grupo_id2" id="grupo_id2"
                                class="browser-default validate select2" style="width: 100%;">
                                <option value="{{$calificaciones[0]->grupo_id}}">
                                    {{$calificaciones[0]->gpoGrado}}-{{$calificaciones[0]->gpoClave}}
                                </option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('materia_id2', 'Materia *', ['class' => '']); !!}
                            <select name="materia_id2" id="materia_id2" class="browser-default validate select2"
                                style="width: 100%;">
                                @if ($calificaciones[0]->gpoMatComplementaria == "")
                                <option value="{{$calificaciones[0]->id_materia}}">{{$calificaciones[0]->matNombre}}
                                @else
                                <option value="{{$calificaciones[0]->id_materia}}">{{$calificaciones[0]->gpoMatComplementaria}}
                                @endif
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('secundaria_grupo_evidencia_id', 'Mes de evaluación *', array('class' => '')); !!}
                            <select id="secundaria_grupo_evidencia_id" class="browser-default validate select2" required
                                name="secundaria_grupo_evidencia_id" style="width: 100%;"
                                data-mes-idold="secundaria_grupo_evidencia_id">
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

                        <div class="col s12 m6 l4" style="display: none;">
                            {!! Form::label('numero_evaluacion', 'Número de evaluación *', array('class' => '')); !!}
                            <select id="numero_evaluacion" class="browser-default validate select2" required
                                name="numero_evaluacion" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4" style="margin-top: -9px; display: none;">
                            <div class="input-field" id="input-field">
                                {!! Form::label('numero_evidencias', 'Total de evidencias a registrar *', array('class' => '')); !!}
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
                                    <th style="display:none;">ffffffffffff</th>
                                    <th style="display:none;">ffffffff</th>
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
                                        <th class="classPromedioMes" scope="col">PROMEDIO<p>DEL MES</p></th>
                                        <th class="classtotalFaltasSep"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id="totalFaltasSep1"></label><label style="color:#fff">SEPTIEMBRE</label></p></th>
                                        <th class="classtotalFaltasOct"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">OCTUBRE</label></p></th>
                                        <th class="classtotalFaltasNov"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">NOVIEMBRE</label></p></th>
                                        <th class="classtotalFaltasDic"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">DICIEMBRE</label></p></th>
                                        <th class="classtotalFaltasEne"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">DIEMBRE-ENERO</label></p></th>
                                        <th class="classtotalFaltasFeb"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">FEBREO</label></p></th>
                                        <th class="classtotalFaltasMar"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">MARZO</label></p></th>
                                        <th class="classtotalFaltasAbr"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">ABRIL</label></p></th>
                                        <th class="classtotalFaltasMay"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">MAYO</label></p></th>
                                        <th class="classtotalFaltasJun"><p id=""> FALTAS EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">JUNIO</label></p></th>

                                        {{--  //Conducta  --}}

                                        <th class="classConductaSep"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id="totalFaltasSep1"></label><label style="color:#fff">SEPTIEMBRE</label></p></th>
                                        <th class="classConductaOct"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">OCTUBRE</label></p></th>
                                        <th class="classConductaNov"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">NOVIEMBRE</label></p></th>
                                        <th class="classConductaDic"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">DICIEMBRE</label></p></th>
                                        <th class="classConductaEne"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">DIEMBRE-ENERO</label></p></th>
                                        <th class="classConductaFeb"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">FEBREO</label></p></th>
                                        <th class="classConductaMar"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">MARZO</label></p></th>
                                        <th class="classConductaAbr"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">ABRIL</label></p></th>
                                        <th class="classConductaMay"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">MAYO</label></p></th>
                                        <th class="classConductaJun"><p id=""> CONDUCTA EN  </p> <p> <label style="color:#fff" id=""></label><label style="color:#fff">JUNIO</label></p></th>

                                </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row" id="Tabla">
                    <div class="col s12">
                        <div class="responsive-table display" cellspacing="0" width="100%" id="tablePrint">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-action">
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
    table th {
      background: #01579B;
      color: #fff;

    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
</style>

@endsection

@section('footer_scripts')

{{--  <script src="{{ asset('js/moment.min.js') }}"></script>  --}}

@include('secundaria.calificaciones.funcionesSecJsEdit')

@endsection
