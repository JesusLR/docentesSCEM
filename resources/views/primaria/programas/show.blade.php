@extends('layouts.dashboard')

@section('template_title')
    Primaria programa
@endsection


@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_programa')}}" class="breadcrumb">Lista de programas</a>
    <label class="breadcrumb">Ver de programa</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">PROGRAMA #{{$programa->id}}</span>

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
                            {!! Form::text('ubicacion_id', $programa->ubiNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('ubicacion_id', 'Campus', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('departamento_id', $programa->depNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('escuela_id', $programa->escNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('escuela_id', 'Escuela', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empleado_id', $programa->empNombre.' '.$programa->empApellido1.' '.$programa->empApellido2, array('readonly' => 'true')) !!}
                            {!! Form::label('empleado_id', 'Coordinador', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progClave', $programa->progClave, array('readonly' => 'true')) !!}
                            {!! Form::label('progClave', 'Clave programa', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progNombre', $programa->progNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('progNombre', 'Nombre programa', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progNombreCorto', $programa->progNombreCorto, array('readonly' => 'true')) !!}
                            {!! Form::label('progNombreCorto', 'Nombre corto (15 carateres)', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('progClaveSegey', $programa->progClaveSegey, array('readonly' => 'true')) !!}
                            {!! Form::label('progClaveSegey', 'Clave de Programa SEGEY', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progClaveEgre', $programa->progClaveEgre, array('readonly' => 'true')) !!}
                            {!! Form::label('progClaveEgre', 'Clave de Egreso SEGEY', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m8">
                        <div class="input-field">
                            {!! Form::text('progTituloOficial', $programa->progTituloOficial, array('readonly' => 'true')) !!}
                            {!! Form::label('progTituloOficial', 'Título oficial de la carrera como debe aparecer en el certificado', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>

@endsection
