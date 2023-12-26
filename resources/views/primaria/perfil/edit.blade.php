@extends('layouts.dashboard')

@section('template_title')
Primaria perfil
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
<a href="{{route('primaria.primaria_perfil.index')}}" class="breadcrumb">Lista de perfiles</a>
<a href="#" class="breadcrumb">Editar perfil</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_perfil.update', 'method' => 'POST']) !!}
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR PERFIL #{{$expediente_perfil->id}}</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">Matematicas</a></li>
                            <li class="tab"><a href="#espanol">Español</a></li>
                            <li class="tab"><a href="#participacion">Participación</a></li>
                            <li class="tab"><a href="#tareas">Tareas</a></li>
                            <li class="tab"><a href="#asistencia">Asistencia</a></li>
                            <li class="tab"><a href="#Convivencia">Convivencia</a></li>
                            <li class="tab"><a href="#Limpieza">Limpieza </a></li>
                            <li class="tab"><a href="#obsGeneral">Obs. general</a></li>

                        </ul>
                    </div>
                </nav>

                <p style="font-size: 18px">PRIMARIA ESCUELA MODELO</p>
                <p style="font-size: 18px">Perfil Final {{$gradoAlumno}}º Grado “{{$grupoAlumno}}”</p>
                <p style="font-size: 18px">Curso escolar {{$ciclo_escolar}}</p>
                <p style="font-size: 18px">{{$alumno}}</p>
                <p style="font-size: 18px">Edad: {{$edadCalculada}} años</p>
                <input type="hidden" name="perfil_id" id="perfil_id" value="{{$expediente_perfil->id}}">

                @php
                $num_mat = 1;
                $num_ESP = 1;
                $num_parti_clase = 1;
                $num_tareas = 1;
                $num_asis = 1;
                $num_convi = 1;
                $num_lim = 1;
                @endphp
                {{-- GENERAL BAR--}}
                <div id="general">
                    @if ($parametro == "true")
                    <div class="row">
                        {{-- MATEMATICAS  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">CONTENIDOS FUNDAMENTALES DE
                                        MATEMÁTICAS</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_contenidos_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "1")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_mat++}}
                                        <input type="hidden" name="contenido_fundamental_id[]"
                                            id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}
                                    </td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}"
                                            class="browser-default validate" name="calificador_id[]"
                                            style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                            <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;"
                                            class="validate" id="observacion_contenido" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        {{-- MATEMATICAS  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px; text-alig:center;">No.</th>
                                    <th style="font-size: 13px;">CONTENIDOS FUNDAMENTALES DE  MATEMÁTICAS</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                    @if ($contenido_fundamental->primaria_contenidos_categoria_id == "1")
                                    <tr>
                                        <td style="font-size: 13px;">
                                            {{$num_mat++}}
                                            <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                            <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                        </td>
                                        <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                        <td style="width: 200px; font-size: 13px;">
                                            <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                                <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                                @foreach ($calificadores as $calificador)
                                                    <option value="{{$calificador->id}}" {{ $calificador->id == $contenido_fundamental->primaria_contenidos_calificadores_id ? 'selected' : '' }}>{{$calificador->calificador}}</option>
                                                @endforeach
                                            </select>                                            
                                        </td>
                                        <td style="font-size: 13px;">
                                            {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                            <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion_contenido" cols="30" rows="10">{{$contenido_fundamental->observacion_contenido}}</textarea>
                                        </td>
                                    </tr>                                       
                                    @endif
                                @endforeach                                                    
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <div id="espanol">
                    @if ($parametro == "true")
                    <div class="row">
                        {{-- Español  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">CONTENIDOS FUNDAMENTALES DE ESPAÑOL
                                    </th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_contenidos_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "2")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_ESP++}}
                                        <input type="hidden" name="contenido_fundamental_id[]"
                                            id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}
                                    </td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}"
                                            class="browser-default validate" name="calificador_id[]" 
                                            style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                            <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;"
                                            class="validate" id="observacion_contenido" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">     
                        {{-- Español  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">CONTENIDOS FUNDAMENTALES DE ESPAÑOL
                                    </th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "2")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_ESP++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}" {{ $calificador->id == $contenido_fundamental->primaria_contenidos_calificadores_id ? 'selected' : '' }}>{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion_contenido" cols="30" rows="10">{{$contenido_fundamental->observacion_contenido}}</textarea>
                                    </td>
                                </tr>      
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <div id="participacion">
                    @if ($parametro == "true")
                    <div class="row">
                        {{-- Participacion en clase  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">PARTICIPACIÓN EN CLASES</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_contenidos_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "3")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_parti_clase++}}
                                        <input type="hidden" name="contenido_fundamental_id[]"
                                            id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}
                                    </td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}"
                                            class="browser-default validate" name="calificador_id[]"
                                            style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                            <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;"
                                            class="validate" id="observacion_contenido" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">                     
                        {{-- Participacion en clase  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">PARTICIPACIÓN EN CLASES</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                    @if ($contenido_fundamental->primaria_contenidos_categoria_id == "3")
                                    <tr>
                                        <td style="font-size: 13px;">
                                            {{$num_parti_clase++}}
                                            <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                            <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                        </td>
                                        <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                        <td style="width: 200px; font-size: 13px;">
                                            <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                                <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                                @foreach ($calificadores as $calificador)
                                                    <option value="{{$calificador->id}}" {{ $calificador->id == $contenido_fundamental->primaria_contenidos_calificadores_id ? 'selected' : '' }}>{{$calificador->calificador}}</option>
                                                @endforeach
                                            </select>                                            
                                        </td>
                                        <td style="font-size: 13px;">
                                            {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                            <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion_contenido" cols="30" rows="10">{{$contenido_fundamental->observacion_contenido}}</textarea>
                                        </td>
                                    </tr>      
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    
                </div>

                <div id="tareas">
                    @if ($parametro == "true")
                    <div class="row">
                        {{-- TAREAS  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">TAREAS</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_contenidos_fundamentales as $contenido_fundamental)
                                    @if ($contenido_fundamental->primaria_contenidos_categoria_id == "4")
                                    <tr>
                                        <td style="font-size: 13px;">
                                            {{$num_tareas++}}
                                            <input type="hidden" name="contenido_fundamental_id[]"
                                                id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                        </td>
                                        <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}
                                        </td>
                                        <td style="width: 200px; font-size: 13px;">
                                            <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}"
                                                class="browser-default validate" name="calificador_id[]" 
                                                style="width: 100%;">
                                                <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                                @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="font-size: 13px;">
                                            {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                            <textarea name="observacion[]" style="font-size: 13px; resize: none;"
                                                class="validate" id="observacion_contenido" cols="30" rows="10"></textarea>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>                       
                    </div>
                    @else
                    <div class="row">                   
                        {{-- TAREAS  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">TAREAS</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                    @if ($contenido_fundamental->primaria_contenidos_categoria_id == "4")
                                    <tr>
                                        <td style="font-size: 13px;">
                                            {{$num_tareas++}}
                                            <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                            <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                        </td>
                                        <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                        <td style="width: 200px; font-size: 13px;">
                                            <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                                <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                                @foreach ($calificadores as $calificador)
                                                    <option value="{{$calificador->id}}" {{ $calificador->id == $contenido_fundamental->primaria_contenidos_calificadores_id ? 'selected' : '' }}>{{$calificador->calificador}}</option>
                                                @endforeach
                                            </select>                                            
                                        </td>
                                        <td style="font-size: 13px;">
                                            {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                            <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion_contenido" cols="30" rows="10">{{$contenido_fundamental->observacion_contenido}}</textarea>
                                        </td>
                                    </tr>      
                                    @endif
                                @endforeach
                            </tbody>
                        </table>                  
                        
                    </div>
                    @endif
                </div>

                <div id="asistencia">
                    @if ($parametro == "true")
                    <div class="row">
                        {{-- Asistencia y puntualidad --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">ASISTENCIA Y PUNTUALIDAD</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_contenidos_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "5")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_asis++}}
                                        <input type="hidden" name="contenido_fundamental_id[]"
                                            id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}
                                    </td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}"
                                            class="browser-default validate" name="calificador_id[]" 
                                            style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                            <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;"
                                            class="validate" id="observacion_contenido" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        {{-- Asistencia y puntualidad --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">ASISTENCIA Y PUNTUALIDAD</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "5")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_asis++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}" {{ $calificador->id == $contenido_fundamental->primaria_contenidos_calificadores_id ? 'selected' : '' }}>{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion_contenido" cols="30" rows="10">{{$contenido_fundamental->observacion_contenido}}</textarea>
                                    </td>
                                </tr>      
                                @endif
                                @endforeach
                            </tbody>
                        </table>                      
                    </div>
                    @endif
                </div>

                <div id="Convivencia">
                    @if ($parametro == "true")
                    <div class="row">
                        {{-- Convivencia escolar --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">CONVIVENCIA ESCOLAR</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_contenidos_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "6")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_convi++}}
                                        <input type="hidden" name="contenido_fundamental_id[]"
                                            id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}
                                    </td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}"
                                            class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                            <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;"
                                            class="validate" id="observacion_contenido" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        {{-- CONVIVENCIA ESCOLAR --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">CONVIVENCIA ESCOLAR</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "6")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_convi++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}" {{ $calificador->id == $contenido_fundamental->primaria_contenidos_calificadores_id ? 'selected' : '' }}>{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion_contenido" cols="30" rows="10">{{$contenido_fundamental->observacion_contenido}}</textarea>
                                    </td>
                                </tr>      
                                @endif
                                @endforeach
                            </tbody>
                        </table>                       
                    </div>
                    @endif
                </div>

                <div id="Limpieza">
                    @if ($parametro == "true")
                    <div class="row">
                        {{-- Limpieza y orden --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">LIMPIEZA Y ORDEN</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_contenidos_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "7")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_lim++}}
                                        <input type="hidden" name="contenido_fundamental_id[]"
                                            id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}
                                    </td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}"
                                            class="browser-default validate" name="calificador_id[]"
                                            style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                            <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;"
                                            class="validate" id="observacion_contenido" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        {{-- Limpieza y orden --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">LIMPIEZA Y ORDEN</th>
                                    <th style="font-size: 13px;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "7")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_lim++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}" {{ $calificador->id == $contenido_fundamental->primaria_contenidos_calificadores_id ? 'selected' : '' }}>{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion_contenido"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion_contenido" cols="30" rows="10">{{$contenido_fundamental->observacion_contenido}}</textarea>
                                    </td>
                                </tr>      
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <div id="obsGeneral">
                    <div class="row">
                        <div class="col s12 m6 l7">
                            <div class="input-field">
                                <label for="observacionAlumno">Observación general</label>
                                <textarea name="observacionAlumno" style="font-size: 13px; resize: none;" class="validate"
                                    id="observacionAlumno" cols="30" rows="10">{{ $expediente_perfil->observaciones}}</textarea>
                            </div>
                        </div>


                        <div class="col s12 m6 l5">
                            <label for="usaLentes">Utiliza lentes</label>
                            <select id="usaLentes" data-departamento-idold="{{old('usaLentes')}}" class="browser-default validate" name="usaLentes" required style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                <option value="NO" {{ $expediente_perfil->utiliza_lentes == "NO" ? 'selected' : '' }}>NO</option>
                                <option value="SI" {{ $expediente_perfil->utiliza_lentes == "SI" ? 'selected' : '' }}>SI</option>
                            </select>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-action">
                <button class="btn-guardar btn-large waves-effect  darken-3" type="submit"><i class="material-icons left">save</i>Guardar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>



@endsection

@section('footer_scripts')


@endsection