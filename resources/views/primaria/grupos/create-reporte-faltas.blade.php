@extends('layouts.dashboard')

@section('template_title')
    Primaria reporte de faltas
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Lista de grupos</a>
    <a href="" class="breadcrumb">Alumnos</a>
@endsection

@section('content')
<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)', 'route' => 'primaria.primaria_grupo.imprimirFaltas', 'method' => 'POST', 'target' => '_blank']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">REPORTE DE FALTAS DEL GRUPO-MATERIA #{{$primaria_inscritos[0]->primaria_grupo_id}}</span>

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
                <input id="grupo_id" name="grupo_id" type="hidden" value="{{$primaria_inscritos[0]->primaria_grupo_id}}">

                <div class="row">
                    <div class="col s12">
                        <span>Programa: <b>{{$primaria_inscritos[0]->progNombre}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Plan: <b>{{$primaria_inscritos[0]->planClave}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Período: <b>{{$primaria_inscritos[0]->perAnioPago}}-{{$primaria_inscritos[0]->perAnioPago+1}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Materia: <b>{{$primaria_inscritos[0]->matClave}}-{{$primaria_inscritos[0]->matNombre}}</b></span>
                    </div>
                </div>

                @if ($primaria_inscritos[0]->matNombreAsignatura != "")
                <div class="row">
                    <div class="col s12">
                        <span>Asignatura: <b>{{$primaria_inscritos[0]->matClaveAsignatura}}-{{$primaria_inscritos[0]->matNombreAsignatura}}</b></span>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col s12">
                        <span>Curso-Grado: <b>{{$primaria_inscritos[0]->gpoGrado}}-{{$primaria_inscritos[0]->gpoClave}}                           
                        </b></span>
                    </div>
                </div>
    
                {{--  <div class="row">
                    <div class="col s12">
                        <span>Docente: <b>{{$primaria_inscritos[0]->empNombre}} {{$primaria_inscritos[0]->empApellido1}} {{$primaria_inscritos[0]->empApellido2}}</b></span>
                    </div>
                </div>  --}}

                <br>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('mes_a_consultar', 'Faltas del mes a consultar *', array('class' => '')); !!}
                        <select id="mes_a_consultar" class="browser-default validate select2" required name="mes_a_consultar" style="width: 100%;">
                            {{--  @foreach ($mesEvidencia as $evidencia)
                                <option value="{{$evidencia->mes}}">{{$evidencia->mes}}</option>
                            @endforeach  --}}
                            <option value="SEPTIEMBRE">SEPTIEMBRE</option>
                            <option value="OCTUBRE">OCTUBRE</option>
                            <option value="NOVIEMBRE">NOVIEMBRE</option>
                            <option value="DICIEMBRE">DICIEMBRE</option>
                            <option value="ENERO">ENERO</option>
                            <option value="FEBRERO">FEBRERO</option>
                            <option value="MARZO">MARZO</option>
                            <option value="ABRIL">ABRIL</option>
                            <option value="MAYO">MAYO</option>
                            <option value="JUNIO">JUNIO</option>
                        </select>
                    </div
                </div>

            </div>



          </div>
          <div class="card-action">
              <button class="btn-guardar btn-large waves-effect  darken-3" type="submit"><i class="material-icons left">picture_as_pdf</i> IMPRIMIR</button>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
</div>
 

@endsection

@section('footer_scripts')


@endsection
