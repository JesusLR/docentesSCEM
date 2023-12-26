@extends('layouts.dashboard')

@section('template_title')
    Bachiller pase de lista
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_chetumal')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('bachiller_grupo_chetumal')}}" class="breadcrumb">Lista de grupos</a>
    <a href="" class="breadcrumb">Alumnos</a>
@endsection

@section('content')
<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller.bachiller_inscritos_seq.asistencia_alumnos', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
          <span class="card-title">PASE DE LISTA DEL GRUPO #{{$grupo->id}}</span>
            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#parciales">Lista</a></li>
                </ul>
              </div>
            </nav>

            <br>
            <input id="grupo_id" name="grupo_id" type="hidden" value="{{$grupo->id}}">
            <input id="bachiller_materia_id" name="bachiller_materia_id" type="hidden" value="{{$grupo->bachiller_materia->id}}">

            <div class="row">
                <div class="col s12">
                    <span>Programa: <b>{{$grupo->plan->programa->progNombre}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Plan: <b>{{$grupo->plan->planClave}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Período: <b>{{$grupo->periodo->perAnioPago}}-{{$grupo->periodo->perAnioPago+1}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Materia: <b>{{$grupo->bachiller_materia->matClave}}-{{$grupo->bachiller_materia->matNombre}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Curso-Grado-Turno: <b>{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}
                        @if ($grupo->gpoTurno != "")
                        -{{$grupo->gpoTurno}}</b></span>
                        @else
                        - No registrado
                        @endif

                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>
                        Docente: <b>{{$grupo->bachiller_empleado->empNombre}}
                            {{$grupo->bachiller_empleado->empApellido1}}
                            {{$grupo->bachiller_empleado->empApellido2}}</b>
                    </span>
                    <span style="float:right">
                        Fecha <input type="date" name="fecha_asistencia" id="fecha_asistencia">
                    </span>
                </div>
            </div>

            <br>

            <div id="alertaPaseLista" style="font-size: 20px; text-align: center; color: red; display:none;"> “Nuevo día de pase de lista, favor de ajustar la columna de estado para retardos o inasistencias”</div>

           
            <div class="row" id="Tabla">
                <div class="col s12">
                    <table class="responsive-table display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="display: none;">ID</th>
                                <th>Clave alumno</th>
                                <th>Alumno</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyLista">

                        </tbody>
                    </table>
                </div>
            </div>
         
            {{-- GENERAL BAR--}}
            <div id="parciales">
                <div class="row">
                    <div class="col s12">
                      
                    </div>
                </div>
            </div>

          <div class="card-action">
                {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect btn-cambia-nombre  darken-3','type' => 'submit']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
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
</style>

@endsection

@section('footer_scripts')
<script>

    let fecha = new Date();
    let dia = fecha.getDate();
    if(dia < 10){
        dia = '0' + dia;
    }
    let mes = (fecha.getMonth() +1);
    if(mes < 10){
        mes = '0' + mes;
    }
    let anio = fecha.getFullYear();

    let fechaHoy = anio + '-' + mes + '-' + dia;

    $("#fecha_asistencia").val(fechaHoy);



    document.getElementById("fecha_asistencia").setAttribute("max", fechaHoy);


</script>




@include('bachiller.inscritos_chetumal.jsPaseDelista')

@endsection
