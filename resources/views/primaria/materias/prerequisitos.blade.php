@extends('layouts.dashboard')

@section('template_title')
    Primaria materia
@endsection

@section('head')
{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_materia')}}" class="breadcrumb">Lista de Materias</a>
    <label class="breadcrumb">Agregar pre-requisito</label>
@endsection

@section('content')
<div class="row">
    <div class="col s12 ">
        {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_materia.agregarPreRequisitos', 'method' => 'POST']) !!}
            <div class="card ">
            <div class="card-content ">
            <span class="card-title">PRE-REQUISITOS MATERIA {{$materia->matClave.'-'.$materia->matNombre}}</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                <div class="nav-content">
                    <ul class="tabs tabs-transparent">
                    <li class="tab"><a class="" href="#prerequisitos">Pre-requisitos</a></li>
                    </ul>
                </div>
                </nav>

                {{-- PRE-REQUISITOS BAR--}}
                <div id="prerequisitos">
                    <input id="materia_id" name="materia_id" type="hidden" value="{{$materia->id}}">
                    <div class="row">
                        <div class="col s8">
                            {!! Form::label('materia', 'Materia *', array('class' => '')); !!}
                            <select id="materia" class="browser-default validate select2" required name="materia" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach($materias as $materia)
                                    <option value="{{$materia->id}}">{{$materia->matClave.'-'.$materia->matNombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col s4">
                            {!! Form::button('<i class="material-icons left">add</i> Agregar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col s12">
                            <table id="tbl-prerequisitos" class="responsive-table display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Clave materia</th>
                                        <th>Nombre materia</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('footer_scripts')
    @include('primaria.materias.scripts-pre_requisitos')
@endsection
