@extends('layouts.dashboard')

@section('template_title')
    Primaria programa
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_programa')}}" class="breadcrumb">Lista de programas</a>
    <label class="breadcrumb">Editar programa</label>
@endsection

@section('content')

@php
    $escuela = $programa->escuela;
    $departamento = $escuela->departamento;
    $ubicacion = $departamento->ubicacion;
@endphp

<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_programa.update', $programa->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR PROGRAMA #{{$programa->id}}</span>

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
                        <select id="ubicacion_id" class="browser-default validate select2" data-ubicacion-id="{{old('ubicacion_id') ?: $ubicacion->id}}" required name="ubicacion_id" style="width: 100%;">
                            <option value="{{$ubicacion->id}}">{{$ubicacion->ubiClave}} {{$ubicacion->ubiNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id" class="browser-default validate select2" data-departamento-id="{{old('departamento_id') ?: $departamento->id}}" required name="departamento_id" style="width: 100%;">
                            <option value="{{$departamento->id}}"> {{$departamento->depClave}}-{{$departamento->depNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id" class="browser-default validate select2" data-escuela-id="{{old('escuela_id') ?: $escuela->id}}" required name="escuela_id" style="width: 100%;">
                            <option value="{{$escuela->id}}">{{$escuela->escClave}}-{{$escuela->escNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('empleado_id', 'Coordinador *', array('class' => '')); !!}
                        <select id="empleado_id" class="browser-default validate select2" required name="empleado_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($empleados as $empleado)
                                <option value="{{$empleado->id}}" @if($programa->empleado->id == $empleado->id) {{ 'selected' }} @endif>{{$empleado->empNombre ." ". $empleado->empApellido1." ". $empleado->empApellido2}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progClave', $programa->progClave, array('id' => 'progClave', 'class' => 'validate','required','readonly','maxlength'=>'3')) !!}
                            {!! Form::label('progClave', 'Clave programa *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progNombre', $programa->progNombre, array('id' => 'progNombre', 'class' => 'validate','required','maxlength'=>'45')) !!}
                            {!! Form::label('progNombre', 'Nombre programa *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progNombreCorto', $programa->progNombreCorto, array('id' => 'progNombreCorto', 'class' => 'validate','required','maxlength'=>'15')) !!}
                            {!! Form::label('progNombreCorto', 'Nombre corto * (15 carateres)', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m8">
                        <div class="input-field">
                            {!! Form::text('progTituloOficial', $programa->progTituloOficial, array('id' => 'progTituloOficial', 'class' => 'validate','maxlength'=>'78')) !!}
                            {!! Form::label('progTituloOficial', 'Título oficial de la carrera como debe aparecer en el certificado', array('class' => '')); !!}
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
@endsection
