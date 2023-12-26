@extends('layouts.dashboard')

@section('template_title')
    Primaria plan
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_plan')}}" class="breadcrumb">Lista de planes</a>
    <label class="breadcrumb">Editar plan</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_plan.update', $plan->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR PLAN #{{$plan->id}}</span>

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

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('ubicacion_id', 'Campus *', array('class' => '')); !!}
                        <select id="ubicacion_id" class="browser-default validate select2" required name="ubicacion_id" style="width: 100%;">
                            <option value="{{$plan->programa->escuela->departamento->ubicacion_id}}" selected >{{$plan->programa->escuela->departamento->ubicacion->ubiClave}}-{{$plan->programa->escuela->departamento->ubicacion->ubiNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                            <option value="{{$plan->programa->escuela->departamento_id}}" selected >{{$plan->programa->escuela->departamento->depClave}}-{{$plan->programa->escuela->departamento->depNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id" class="browser-default validate select2" required name="escuela_id" style="width: 100%;">
                            <option value="{{$plan->programa->escuela_id}}" selected >{{$plan->programa->escuela->escClave}}-{{$plan->programa->escuela->escNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                        <select id="programa_id" class="browser-default validate select2" required name="programa_id" style="width: 100%;">
                            <option value="{{$plan->programa_id}}" selected >{{$plan->programa->progClave}}-{{$plan->programa->progNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::hidden('planClaveAnterior', $plan->planClave, array('id' => 'planClaveAnterior', 'class' => 'validate','required','maxlength'=>'4')) !!}
                            {!! Form::text('planClave', $plan->planClave, array('id' => 'planClave', 'class' => 'validate','required','maxlength'=>'4')) !!}
                            {!! Form::label('planClave', 'Clave plan *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('planPeriodos', $plan->planPeriodos, array('id' => 'planPeriodos', 'class' => 'validate','required','min'=>'0','max'=>'99','onKeyPress="if(this.value.length==2) return false;"')) !!}
                            {!! Form::label('planPeriodos', 'Número de períodos *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('planNumCreditos', $plan->planNumCreditos, array('id' => 'planNumCreditos', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                            {!! Form::label('planNumCreditos', 'Número de creditos', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>

@endsection

@section('footer_scripts')


@include('primaria.scripts.departamentos')
@include('primaria.scripts.escuelas')
@include('primaria.scripts.programas')

@endsection
