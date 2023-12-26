@extends('layouts.dashboard')

@php use App\Http\Helpers\Utils; @endphp

@section('template_title')
    Horario Grupo
@endsection

@section('head')
    {!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}      
@endsection

@section('breadcrumbs')
    <a href="{{url('/')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('grupo')}}" class="breadcrumb">Lista de Grupo</a>
    <a href="{{url('grupo/horario')}}" class="breadcrumb">Horario</a>
@endsection

@section('content')



<div class="row">
    <div class="col s12 ">
      {!! Form::open(['url' => 'grupo/agregarHorario', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR HORARIO AL GRUPO #{{$grupo->id}}</span>

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
                <br>
                <input id="grupo_id" name="grupo_id" type="hidden" value="{{$grupo->id}}">
                <input id="empleado_id" name="empleado_id" type="hidden" value="{{$grupo->empleado->id}}">
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
                        <span>Materia: <b>{{$grupo->materia->matClave}}-{{$grupo->materia->matNombre}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Curso-Grado-Turno: <b>{{$grupo->gpoSemestre}}-{{$grupo->gpoClave}}-{{$grupo->gpoTurno}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Docente: <b>{{$grupo->empleado->persona->perNombre}} {{$grupo->empleado->persona->perApellido1}} {{$grupo->empleado->persona->perApellido2}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6">
                        {!! Form::label('aula_id', 'Aula', array('class' => '')); !!}
                        <select id="aula_id" class="browser-default validate select2" required name="aula_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($aulas as $aula)
                                <option value="{{$aula->id}}" @if(old('aula_id') == $aula->id) {{ 'selected' }} @endif>{{$aula->aulaClave}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6">
                        {!! Form::label('ghDia', 'Día', array('class' => '')); !!}
                        <select id="ghDia" class="browser-default validate select2" required name="ghDia" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @for($i=1;$i<=6;$i++)
                                <option value="{{$i}}" @if(old('ghDia') == $i) {{ 'selected' }} @endif>{{Utils::diaSemana($i)}}</option>
                            @endFor
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6">
                        {!! Form::label('ghInicio', 'Hora Inicio', array('class' => '')); !!}
                        <select id="ghInicio" class="browser-default validate select2" required name="ghInicio" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @for($i=7;$i<=22;$i++)
                                <option value="{{$i}}" @if(old('ghInicio') == $i) {{ 'selected' }} @endif>{{$i}}Hrs</option>
                            @endFor
                        </select>
                    </div>
                    <div class="col s12 m6">
                        {!! Form::label('ghFinal', 'Hora Final', array('class' => '')); !!}
                        <select id="ghFinal" class="browser-default validate select2" required name="ghFinal" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @for($i=7;$i<=22;$i++)
                                <option value="{{$i}}" @if(old('ghFinal') == $i) {{ 'selected' }} @endif>{{$i}}Hrs</option>
                            @endFor
                        </select>
                    </div>
                </div>
                <div class="card-action">
                {!! Form::button('<i class="material-icons left">add</i> Agregar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="col s12">
                        <table id="tbl-horario" class="responsive-table display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Día</th>
                                <th>Aula</th>
                                <th>Hora Inicio</th>
                                <th>Hora Final</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>

          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>


@endsection

@section('footer_scripts')
{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script type="text/javascript">
    $(document).ready(function() {
        var grupo_id = $('#grupo_id').val();
        if(grupo_id != "" && grupo_id != null){
            $('#tbl-horario').dataTable({
                "language":{"url":base_url+"/api/lang/javascript/datatables"},
                "serverSide": true,
                "ajax": {
                    "type" : "GET",
                    'url': base_url+"/api/grupo/horario/"+grupo_id,
                    beforeSend: function () {
                        $('.preloader').fadeIn('slow',function(){$(this).append('<div id="preloader"></div>');;});
                    },
                    complete: function () {
                        $('.preloader').fadeOut('slow',function(){$('#preloader').remove();});
                    },
                },
                "columns":[
                    {data: "dia"},
                    {data: "aula.aulaClave"},
                    {data: "ghInicio"},
                    {data: "ghFinal"},
                    {data: "action"}
                ]
            });
        }
    });
</script>
@endsection