@extends('layouts.dashboard')

@section('template_title')
Primaria perfil
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
<a href="{{route('primaria.primaria_perfil.index')}}" class="breadcrumb">Lista de perfiles</a>
<a href="#" class="breadcrumb">Ver perfil</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">       
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR PERFIL</span>

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
                            <li class="tab"><a href="#Limpieza ">Limpieza </a></li>
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
                    <div class="row">
                        {{-- MATEMATICAS  --}}
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">No.</th>
                                    <th style="font-size: 13px;">CONTENIDOS FUNDAMENTALES DE  MATEMÁTICAS</th>
                                    <th style="font-size: 13px; text-aling:center;">CALIFICADOR</th>
                                    <th style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                    @if ($contenido_fundamental->primaria_contenidos_categoria_id == "1")
                                    <tr>
                                        <td style="width: 10px; font-size: 13px;">
                                            {{$num_mat++}}
                                        </td>
                                        <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                        <td style="width: 100px; font-size: 13px; text-aling:center;">
                                            {{$contenido_fundamental->calificador}}
                                        </td>
                                        <td style="width: 500px; font-size: 13px;">
                                            {{$contenido_fundamental->observacion_contenido}}
                                        </td>
                                    </tr>                                       
                                    @endif
                                @endforeach                                                    
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="espanol">
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
                                    <td style="width: 10px; font-size: 13px;">
                                        {{$num_ESP++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 100px; font-size: 13px; text-aling:center;">
                                        {{$contenido_fundamental->calificador}}
                                    </td>
                                    <td style="width: 500px; font-size: 13px;">
                                        {{$contenido_fundamental->observacion_contenido}}
                                    </td>
                                </tr>      
                                @endif
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>

                <div id="participacion">
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
                                    <td style="width: 10px; font-size: 13px;">
                                        {{$num_parti_clase++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 100px; font-size: 13px; text-aling:center;">
                                        {{$contenido_fundamental->calificador}}
                                    </td>
                                    <td style="width: 500px; font-size: 13px;">
                                        {{$contenido_fundamental->observacion_contenido}}
                                    </td>
                                </tr>      
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="tareas">
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
                                    <td style="width: 10px; font-size: 13px;">
                                        {{$num_tareas++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 100px; font-size: 13px; text-aling:center;">
                                        {{$contenido_fundamental->calificador}}
                                    </td>
                                    <td style="width: 500px; font-size: 13px;">
                                        {{$contenido_fundamental->observacion_contenido}}
                                    </td>
                                </tr>     
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                   
                        
                    </div>
                </div>

                <div id="asistencia">
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
                                    <td style="width: 10px; font-size: 13px;">
                                        {{$num_asis++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 100px; font-size: 13px; text-aling:center;">
                                        {{$contenido_fundamental->calificador}}
                                    </td>
                                    <td style="width: 500px; font-size: 13px;">
                                        {{$contenido_fundamental->observacion_contenido}}
                                    </td>
                                </tr>       
                                @endif
                                @endforeach
                            </tbody>
                        </table>


                      
                    </div>
                </div>

                <div id="Convivencia">
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
                                @foreach ($primaria_expediente_perfiles_contenidos as $contenido_fundamental)
                                @if ($contenido_fundamental->primaria_contenidos_categoria_id == "6")
                                <tr>
                                    <td style="width: 10px; font-size: 13px;">
                                        {{$num_convi++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 100px; font-size: 13px; text-aling:center;">
                                        {{$contenido_fundamental->calificador}}
                                    </td>
                                    <td style="width: 500px; font-size: 13px;">
                                        {{$contenido_fundamental->observacion_contenido}}
                                    </td>
                                </tr>      
                                @endif
                                @endforeach
                            </tbody>
                        </table>                       
                    </div>
                </div>

                <div id="Limpieza">
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
                                    <td style="width: 10px; font-size: 13px;">
                                        {{$num_lim++}}
                                        <input type="hidden" name="id[]" id="id" value="{{$contenido_fundamental->id}}">
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->primaria_contenidos_fundamentales_id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 100px; font-size: 13px; text-aling:center;">
                                        {{$contenido_fundamental->calificador}}
                                    </td>
                                    <td style="width: 500px; font-size: 13px;">
                                        {{$contenido_fundamental->observacion_contenido}}
                                    </td>
                                </tr>       
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>



            </div>           
        </div>
    </div>
</div>



@endsection

@section('footer_scripts')


@endsection