@extends('layouts.dashboard')

@section('template_title')
    Primaria período
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_periodo')}}" class="breadcrumb">Lista de periodos</a>
    <label class="breadcrumb">Editar periodo</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_periodo.update', $periodo->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR PERIODO #{{$periodo->id}}</span>

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
                            <option value="{{$periodo->departamento->ubicacion_id}}" selected >{{$periodo->departamento->ubicacion->ubiClave}}-{{$periodo->departamento->ubicacion->ubiNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                            <option value="{{$periodo->departamento_id}}" selected >{{$periodo->departamento->depClave}}-{{$periodo->departamento->depNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('perNumero', $periodo->perNumero, array('id' => 'perNumero', 'class' => 'validate','required','readonly','min'=>'0','max'=>'9','onKeyPress="if(this.value.length==1) return false;"')) !!}
                            {!! Form::label('perNumero', 'Número de periodo *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('perAnio', $periodo->perAnio, array('id' => 'perAnio', 'class' => 'validate','required','readonly','min'=>'0','max'=>'9999','onKeyPress="if(this.value.length==4) return false;"')) !!}
                            {!! Form::label('perAnio', 'Año de periodo *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('perAnioPago', $periodo->perAnioPago, array('id' => 'perAnioPago', 'class' => 'validate','required','onKeyPress="if(this.value.length==4) return false;"')) !!}
                            {!! Form::label('perAnioPago', 'Año de inicio *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('perEstado', $periodo->perEstado, array('id' => 'perEstado', 'class' => 'validate','required','min'=>'0','max'=>'9999')) !!}
                            {!! Form::label('perEstado', 'Estado período *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('', 'Fecha inicial *', array('class' => '')); !!}
                        {!! Form::date('perFechaInicial', $periodo->perFechaInicial, array('class' => 'validate','required')) !!}
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('', 'Fecha final *', array('class' => '')); !!}
                        {!! Form::date('perFechaFinal', $periodo->perFechaFinal, array('class' => 'validate','required')) !!}
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

@endsection
