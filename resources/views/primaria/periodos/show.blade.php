@extends('layouts.dashboard')

@section('template_title')
    Primaria período
@endsection


@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_periodo')}}" class="breadcrumb">Lista de periodos</a>
    <label class="breadcrumb">Ver periodo</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">PERIODO #{{$periodo->id}}</span>

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
                        <div class="input-field">
                            {!! Form::text('ubiClave', $periodo->departamento->ubicacion->ubiNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('ubiClave', 'Campus', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('departamento_id', $periodo->departamento->depNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('perNumero', $periodo->perNumero, array('readonly' => 'true')) !!}
                            {!! Form::label('perNumero', 'Número de periodo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('perAnio', $periodo->perAnio, array('readonly' => 'true')) !!}
                            {!! Form::label('perAnio', 'Año de periodo', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('perAnioPago', $periodo->perAnioPago, array('id' => 'perAnioPago', 'class' => 'validate','required','min'=>'0','max'=>'9','onKeyPress="if(this.value.length==4) return false;"')) !!}
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
                        {!! Form::label('', 'Fecha inicial', array('class' => '')); !!}
                        {!! Form::date('perFechaInicial', $periodo->perFechaInicial, array('readonly' => 'true')) !!}
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('', 'Fecha final', array('class' => '')); !!}
                        {!! Form::date('perFechaFinal', $periodo->perFechaFinal, array('readonly' => 'true')) !!}
                    </div>
                </div>
          </div>
        </div>
    </div>
  </div>

@endsection
