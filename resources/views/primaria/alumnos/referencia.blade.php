@extends('layouts.dashboard')

@section('template_title')
    Referencia
@endsection

@section('head')
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Lista de preinscritos</a>
@endsection

@section('content')
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_curso.crearReferencia', 'method' => 'POST']) !!}
    <div class="card ">
        <div class="card-content ">
        <span class="card-title">REFERENCIA</span>

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
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('aluClave', '16184623', array('id' => 'aluClave', 'class' => 'validate')) !!}
                    {!! Form::label('aluClave', 'Clave de pago', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                        <div class="input-field">
                        {!! Form::text('cuoConcepto', '03', array('id' => 'cuoConcepto', 'class' => 'validate')) !!}
                        {!! Form::label('cuoConcepto', 'Concepto', ['class' => '']); !!}
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::number('cuoAnio', '18', array('id' => 'cuoAnio', 'class' => 'validate')) !!}
                    {!! Form::label('cuoAnio', 'Año periodo', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    {!! Form::label('cuoFecha', 'Fecha de hoy', ['class' => '']); !!}
                    {!! Form::date('cuoFecha', '2018-10-02', array('id' => 'cuoFecha', 'class' => 'validate')) !!}
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('mesesAtraso', '0', array('id' => 'mesesAtraso', 'class' => 'validate')) !!}
                    {!! Form::label('mesesAtraso', 'Meses Atraso', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('importeNormal', '6500.00', array('id' => 'importeNormal', 'class' => 'validate')) !!}
                    {!! Form::label('importeNormal', 'Importe Normal', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('importeBeca', '2600.00', array('id' => 'importeBeca', 'class' => 'validate')) !!}
                    {!! Form::label('importeBeca', 'Importe Beca', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('prontoPago', '210.00', array('id' => 'prontoPago', 'class' => 'validate')) !!}
                    {!! Form::label('prontoPago', 'Pronto Pago', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('inscEnero', '74.00', array('id' => 'inscEnero', 'class' => 'validate')) !!}
                    {!! Form::label('inscEnero', 'Inscripción Enero', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('impRecargo', '0.00', array('id' => 'impRecargo', 'class' => 'validate')) !!}
                    {!! Form::label('impRecargo', 'Importe Recargo', ['class' => '']); !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="input-field">
                    {!! Form::text('impPagar', '4430.00', array('id' => 'impPagar', 'class' => 'validate')) !!}
                    {!! Form::label('impPagar', 'Importe a pagar', ['class' => '']); !!}
                    </div>
                </div>
            </div>

        </div>

        </div>
        <div class="card-action">
        {!! Form::button('<i class="material-icons left">save</i> Generar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('footer_scripts')

@endsection
