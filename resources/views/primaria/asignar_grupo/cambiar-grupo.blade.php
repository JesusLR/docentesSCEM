@extends('layouts.dashboard')

@section('template_title')
  Primaria inscrito materia
@endsection

@section('breadcrumbs')
  <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
  <a href="{{route('primaria_asignar_grupo.index')}}" class="breadcrumb">Lista de Inscritos</a>
  <a href="" class="breadcrumb">Cambiar alumno de grupo</a>
@endsection

@section('content')
<div class="row">
  <div class="col s12 ">
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_asignar_grupo.postCambiarGrupo', 'method' => 'POST']) !!}
      <input type="hidden" value="{{$inscrito->id}}" name="inscritoId">
      <div class="card ">
        <div class="card-content">
          <span class="card-title">CAMBIAR ALUMNO DE GRUPO. INSCRITO #{{$inscrito->id}}</span>
          {{-- NAVIGATION BAR--}}
          <nav class="nav-extended">
            <div class="nav-content">
              <ul class="tabs tabs-transparent">
                <li class="tab"><a class="active" href="#filtros">Filtros de búsqueda</a></li>
              </ul>
            </div>
          </nav>

          <br>
          <p><b>Año: </b>{{$inscrito->curso->periodo->perAnioPago}}</p>
          <p><b>Programa: </b>{{$inscrito->primaria_grupo->plan->programa->progNombre}}</p>
          <p><b>Grado-Grupo: </b>{{$inscrito->primaria_grupo->gpoGrado}}-{{$inscrito->primaria_grupo->gpoClave}}</p>
          <p><b>Alumno: </b>{{$inscrito->curso->alumno->persona->perNombre}} {{$inscrito->curso->alumno->persona->perApellido1}} {{$inscrito->curso->alumno->persona->perApellido2}} </p>


          {{-- GENERAL BAR--}}
          <div id="filtros">
            <div class="row">
              <div class="col s12 m6 l4">
                {!! Form::label('gpoId', 'Cambiar alumno a grupo', ['class' => '']); !!}
                <select name="gpoId" id="gpoId" class="browser-default validate select2" style="width: 100%;">
                    @foreach($grupos as $key => $value)
                      <option value="{{$value->id}}" {{$inscrito->primaria_grupo->id == $value->id ? "selected": ""}}>
                          {{$value->primaria_materia->matNombre}} - {{$value->gpoGrado}}{{$value->gpoClave}}{{$value->gpoTurno}}
                      </option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="card-action">
          {!! Form::button('<i class="material-icons left">save</i> GUARDAR', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
        </div>
      </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection


@section('footer_scripts')
@endsection
