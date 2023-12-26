@extends('layouts.dashboard')

@section('template_title')
    Bachiller evidencia
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_yucatan')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('bachiller_evidencias')}}" class="breadcrumb">Lista de evidencias</a>
    <label class="breadcrumb">Ver evidencia</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EVIDENCIA #{{$bachiller_evidencias->id}}</span>

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
                            <input type="text" name="" id="" value="{{$bachiller_evidencias->ubiClave.'-'.$bachiller_evidencias->ubiNombre}}" readonly>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                            <input type="text" name="" id="" value="{{$bachiller_evidencias->depClave.'-'.$bachiller_evidencias->depNombre}}" readonly>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                            <input type="text" name="" id="" value="{{$bachiller_evidencias->escClave.'-'.$bachiller_evidencias->escNombre}}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('periodo_id', 'Período *', array('class' => '')); !!}
                            <input type="text" name="" id="" value="{{$bachiller_evidencias->perNumero.'-'.$bachiller_evidencias->perAnioPago}}" readonly>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                            <input type="text" name="" id="" value="{{$bachiller_evidencias->progClave.'-'.$bachiller_evidencias->progNombre}}" readonly>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                            <input type="text" name="" id="" value="{{$bachiller_evidencias->planClave}}" readonly>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('materia_id', 'Materia *', array('class' => '')); !!}
                            <input type="text" name="" id="" value="{{$bachiller_evidencias->matClave.'-'.$bachiller_evidencias->matNombre}}" readonly>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('eviNumero', 'Número evidencia *', array('class' => '')); !!}
                                <input type="text" name="" id="" value="{{$bachiller_evidencias->eviNumero}}" readonly>
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('eviDescripcion', 'Descripción evidencia *', array('class' => '')); !!}
                                <input type="text" name="" id="" value="{{$bachiller_evidencias->eviDescripcion}}" readonly>
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('eviFechaEntrega', 'Fecha entrega *', array('class' => '')); !!}
                                <input type="text" name="" id="" value="{{$bachiller_evidencias->eviFechaEntrega}}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('eviPuntos', 'Puntos evidencia *', array('class' => '')); !!}
                                <input type="text" name="" id="" value="{{$bachiller_evidencias->eviPuntos}}" readonly>
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('eviTipo', 'Tipo evidencia *', array('class' => '')); !!}
                                <input type="text" name="" id="" value="{{$bachiller_evidencias->eviTipo}}" readonly>
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('eviFaltas', 'Faltas evidencia *', array('class' => '')); !!}
                                <input type="text" name="" id="" value="{{$bachiller_evidencias->eviFaltas}}" readonly>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('footer_scripts')


    @endsection
