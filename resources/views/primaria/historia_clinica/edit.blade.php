@extends('layouts.dashboard')

@section('template_title')
Primaria historial clinica
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{url('primaria_historia_clinica')}}" class="breadcrumb">Lista de historia clínica</a>
<a href="{{url('primaria_historia_clinica/'.$historia->id.'/edit')}}" class="breadcrumb">Editar historial clínica</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria_historia_clinica.update', $historia->id])) }}
        {{--  @if (isset($candidato))
            <input type="hidden" name="candidato_id" value="{{$candidato->id}}" />
        @endif --}}
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR HISTORIAL CLINICA</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">General</a></li>
                            <li class="tab"><a href="#familiares">Familiares</a></li>
                            <li class="tab"><a href="#escolares">Escolar</a></li>

                        </ul>
                    </div>
                </nav>

                @php
                use Carbon\Carbon;
                $fechaActual = Carbon::now('CDT')->format('Y-m-d');
                @endphp

                {{-- GENERAL BAR--}}
                <div id="general">
                <br>
                    <div class="row" style="background-color:#ECECEC;">
                        <p style="text-align: center;font-size:1.2em;">DATOS GENERALES DEL ALUMNO (A)</p>
                    </div>
                    <div class="row">
                        {{--  /* --------------------------- Seleccionar alumno --------------------------- */  --}}
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('nombreAlumno', 'Nombre(s)*', array('class' =>
                                '')); !!}
                                {!! Form::text('nombreAlumno', $historia->perNombre, array('readonly' => 'true')) !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('perApellido1', 'Apellido paterno*', array('class' =>
                                '')); !!}
                                {!! Form::text('perApellido1', $historia->perApellido1, array('readonly' => 'true')) !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('perApellido2', 'Apellido materno*', array('class' =>
                                '')); !!}
                                {!! Form::text('perApellido2', $historia->perApellido2, array('readonly' => 'true')) !!}
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                                {!! Form::label('perFechaNac', 'Fecha de nacimiento*', array('class' =>
                                '')); !!}
                                {!! Form::date('perFechaNac', $historia->perFechaNac, array('readonly' => 'true')) !!}

                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('gradoInscrito', 'Grado al que se inscribe *', array('class' => '')); !!}
                                {!! Form::text('gradoInscrito', $historia->gradoInscrito, array('id' => 'gradoInscrito',
                                'class' =>
                                'validate','required', 'maxlength'=>'18')) !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::label('edadAlumno', 'Edad *', array('class' => '')); !!}
                                {!! Form::number('edadAlumno', $historia->edadAlumno, array('id' => 'edadAlumno', 'class' =>
                                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==4) return false;"',
                                'required')) !!}
                            </div>
                        </div>

                    </div>




                </div>

                {{--  BAR FAMILIARES  --}}
                @include('primaria.historia_clinica.familiares')

                {{--  BAR ESCOLAR   --}}
                @include('primaria.historia_clinica.escolares')

            </div>
            <div class="card-action">
                {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}

            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

{{-- Script de funciones auxiliares  --}}

@endsection

@section('footer_scripts')



@endsection
