@extends('layouts.dashboard')

@section('template_title')
    Primaria inscrito materia
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_asignar_grupo')}}" class="breadcrumb">Lista de Inscritos Materia</a>
    <a href="{{url('primaria_asignar_grupo/'.$inscrito->id)}}" class="breadcrumb">Ver inscrito materia</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">INSCRITO MATERIA #{{$inscrito->id}}</span>

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
                            {!! Form::text('ubiClave', $inscrito->curso->cgt->plan->programa->escuela->departamento->ubicacion->ubiNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('ubiClave', 'Campus', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('departamento_id', $inscrito->curso->cgt->plan->programa->escuela->departamento->depNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('escuela_id', $inscrito->curso->cgt->plan->programa->escuela->escNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('escuela_id', 'Escuela', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('periodo_id', $inscrito->curso->cgt->periodo->perNumero.'-'.$inscrito->curso->cgt->periodo->perAnio, array('readonly' => 'true')) !!}
                            {!! Form::label('periodo_id', 'Periodo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', $inscrito->curso->cgt->periodo->perFechaInicial, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', $inscrito->curso->cgt->periodo->perFechaFinal, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('programa_id', $inscrito->curso->cgt->plan->programa->progNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('programa_id', 'Programa', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('plan_id', $inscrito->curso->cgt->plan->planClave, array('readonly' => 'true')) !!}
                            {!! Form::label('plan_id', 'Plan', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('cgt_id', $inscrito->curso->cgt->cgtGradoSemestre.'-'.$inscrito->curso->cgt->cgtGrupo.'-'.$inscrito->curso->cgt->cgtTurno, array('readonly' => 'true')) !!}
                            {!! Form::label('cgt_id', 'CGT', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 ">
                        <div class="input-field">
                            {!! Form::text('curso_id', $inscrito->curso->alumno->persona->perNombre.' '.$inscrito->curso->alumno->persona->perApellido1.' '.$inscrito->curso->alumno->persona->perApellido2, array('readonly' => 'true')) !!}
                            {!! Form::label('curso_id', 'Alumno preinscrito', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 ">
                        <div class="input-field">
                            {!! Form::text('grupo_id', $inscrito->primaria_grupo->gpoGrado.'-'.$inscrito->primaria_grupo->gpoClave.'-'.$inscrito->primaria_grupo->gpoTurno.' '.$inscrito->primaria_grupo->primaria_materia->matNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('grupo_id', 'Grupo-Materia', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
            </div>

          </div>
        </div>
    </div>
  </div>

@endsection
