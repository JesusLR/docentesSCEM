@extends('layouts.dashboard')

@section('template_title')
    Primaria materia
@endsection


@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_materia')}}" class="breadcrumb">Lista de materias</a>
    <label class="breadcrumb">Ver materias</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">MATERIA #{{$materia->id}}</span>

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
                            {!! Form::text('ubiClave', $materia->plan->programa->escuela->departamento->ubicacion->ubiNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('ubiClave', 'Campus', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('departamento_id', $materia->plan->programa->escuela->departamento->depNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('escuela_id', $materia->plan->programa->escuela->escNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('escuela_id', 'Escuela', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('programa_id', $materia->plan->programa->progNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('programa_id', 'Programa', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('plan_id', $materia->plan->planClave, array('readonly' => 'true')) !!}
                            {!! Form::label('plan_id', 'Plan', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matClave', $materia->matClave, array('readonly' => 'true')) !!}
                            {!! Form::label('matClave', 'Clave materia', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matNombre', $materia->matNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('matNombre', 'Nombre materia', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matNombreCorto', $materia->matNombreCorto, array('readonly' => 'true')) !!}
                            {!! Form::label('matNombreCorto', 'Nombre corto (15 carateres)', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matSemestre', $materia->matSemestre, array('readonly' => 'true')) !!}
                            {!! Form::label('matSemestre', 'Grado', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matCreditos', $materia->matCreditos, array('readonly' => 'true')) !!}
                            {!! Form::label('matCreditos', 'Créditos', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matClasificacion', clasificacion($materia->matClasificacion), array('readonly' => 'true')) !!}
                            {!! Form::label('matClasificacion', 'Clasificación', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matEspecialidad', $materia->matEspecialidad, array('readonly' => 'true')) !!}
                            {!! Form::label('matEspecialidad', 'Especialidad', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matTipoAcreditacion', acreditacion($materia->matTipoAcreditacion), array('readonly' => 'true')) !!}
                            {!! Form::label('matTipoAcreditacion', 'Tipo de acreditación', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matPorcentajeParcial', $materia->matPorcentajeParcial, array('readonly' => 'true')) !!}
                            {!! Form::label('matPorcentajeParcial', '% Examen parcial', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matPorcentajeOrdinario', $materia->matPorcentajeOrdinario, array('readonly' => 'true')) !!}
                            {!! Form::label('matPorcentajeOrdinario', '% Examen ordinario', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matNombreOficial', $materia->matNombreOficial, array('readonly' => 'true')) !!}
                            {!! Form::label('matNombreOficial', 'Nombre oficial', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('matOrdenVisual', $materia->matOrdenVisual, array('readonly' => 'true')) !!}
                            {!! Form::label('matOrdenVisual', 'Orden visual', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
          </div>
        </div>
    </div>
  </div>

@endsection

@php
    function clasificacion($valor){
        switch ($valor) {
            case "B":
                return "BÁSICA";
                break;
            case "O":
                return "OPTATIVA";
                break;
            case "U":
                return "OCUPA";
                break;
            case "X":
                return "EXTRAOFICIAL";
                break;
            case "C":
                return "COMPLEMENTARIA";
                break;
            default:
                return "";
        }
    }
    function acreditacion($valor){
        switch ($valor) {
            case "N":
                return "NUMÉRICO";
                break;
            case "A":
                return "ALFABÉTICO";
                break;
            case "M":
                return "MIXTO";
                break;
            default:
                return "";
        }
    }
@endphp
