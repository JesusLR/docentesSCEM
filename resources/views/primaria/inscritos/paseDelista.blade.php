@extends('layouts.dashboard')

@section('template_title')
    Primaria pase de lista
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
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_inscritos.asistencia_alumnos', 'method' => 'POST']) !!}
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
            <input id="primaria_materia_id" name="primaria_materia_id" type="hidden" value="{{$grupo->primaria_materia->id}}">

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
                    <span>Materia: <b>{{$grupo->primaria_materia->matClave}}-{{$grupo->primaria_materia->matNombre}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Curso-Grado: <b>{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}                       

                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    {{--  <span>
                        Docente: <b>{{$grupo->primaria_empleado->empNombre}}
                            {{$grupo->primaria_empleado->empApellido1}}
                            {{$grupo->primaria_empleado->empApellido2}}</b>
                    </span>  --}}
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




@include('primaria.inscritos.jsPaseDelista')

@endsection
