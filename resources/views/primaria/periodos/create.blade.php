@extends('layouts.dashboard')

@section('template_title')
   Primaria período
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_periodo')}}" class="breadcrumb">Lista de periodos</a>
    <label class="breadcrumb">Agregar periodo</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_periodo.store', 'method' => 'POST']) !!}
            <div class="card ">
                <div class="card-content ">
                    <span class="card-title">AGREGAR PERIODO</span>

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
                                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                    @foreach($ubicaciones as $ubicacion)
                                        @php
                                        $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
                                        if ($ubicacion->id == $ubicacion_id){
                                            echo '<option value="'.$ubicacion->id.'" selected>'.$ubicacion->ubiClave.'-'.$ubicacion->ubiNombre.'</option>';
                                        } else {
                                            echo '<option value="'.$ubicacion->id.'">'.$ubicacion->ubiClave.'-'.$ubicacion->ubiNombre.'</option>';
                                        }
                                        @endphp
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s12 m6 l4">
                                {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                                <select id="departamento_id" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::number('perNumero', NULL, array('id' => 'perNumero', 'class' => 'validate','required','min'=>'0','max'=>'9','onKeyPress="if(this.value.length==1) return false;"')) !!}
                                    {!! Form::label('perNumero', 'Número de periodo *', array('class' => '')); !!}
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::number('perAnio', NULL, array('id' => 'perAnio', 'class' => 'validate','required','min'=>'0','max'=>'9999','onKeyPress="if(this.value.length==4) return false;"')) !!}
                                    {!! Form::label('perAnio', 'Año de periodo *', array('class' => '')); !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::number('perAnioPago', NULL, array('id' => 'perAnioPago', 'class' => 'validate','required','onKeyPress="if(this.value.length==4) return false;"')) !!}
                                    {!! Form::label('perAnioPago', 'Año de inicio *', array('class' => '')); !!}
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input-field">
                                    {!! Form::text('perEstado', NULL, array('id' => 'perEstado', 'class' => 'validate','required','min'=>'0','max'=>'9999')) !!}
                                    {!! Form::label('perEstado', 'Estado período *', array('class' => '')); !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m6 l4">
                                {!! Form::label('', 'Fecha inicial *', array('class' => '')); !!}
                                {!! Form::date('perFechaInicial', NULL, array('class' => 'validate','required')) !!}
                            </div>
                            <div class="col s12 m6 l4">
                                {!! Form::label('', 'Fecha final *', array('class' => '')); !!}
                                {!! Form::date('perFechaFinal', NULL, array('class' => 'validate','required')) !!}
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
@include('primaria.scripts.preferencias')
@include('primaria.scripts.departamentos')

@endsection
