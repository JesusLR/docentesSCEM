@extends('layouts.dashboard')

@php
    use App\Http\Helpers\Utils;
@endphp

@section('template_title')
    Calificación
@endsection

@section('head')
    {!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('/')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('extracurricular')}}" class="breadcrumb">Lista de Grupos extracurriculares</a>
    <a href="" class="breadcrumb">Calificaciones</a>
@endsection

@section('content')
<div class="row">
    <div class="col s12 ">
        {!! Form::open(['route' => 'extracurricularcalif.store', 'method' => 'POST', 'id' => 'form-guardar']) !!}
            <input type="hidden" name="puedeParcial1" value="{{$puedeParcial1}}">
            <input type="hidden" name="puedeParcial2" value="{{$puedeParcial2}}">
            <input type="hidden" name="puedeParcial3" value="{{$puedeParcial3}}">
            <input type="hidden" name="puedeOrdinario" value="{{$puedeOrdinario}}">
            <div class="card ">
            <div class="card-content ">
            <span class="card-title">CALIFICACIONES EXTRACURRICULAR DEL GRUPO #{{$grupo->id}}</span>
                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                        <li class="tab"><a class="active" href="#primerparcial">Primer parcial</a></li>
                        <li class="tab"><a class="active" href="#segundoparcial">Segundo parcial</a></li>
                        <li class="tab"><a class="active" href="#tercerparcial">Tercer parcial</a></li>
                        <li class="tab"><a class="active" href="#promediosparciales">Promedios parciales</a></li>
                        <li class="tab"><a href="#ordinarios">Ordinarios</a></li>
                        </ul>
                    </div>
                </nav>

                <br>
                <input id="grupo_id" name="grupo_id" type="hidden" value="{{$grupo->id}}">
                <div class="row">
                    <div class="col s12">
                        <span>Programa: <b>{{$grupo->plan->programa->progNombre}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Plan: <b>{{$grupo->plan->planClave}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Materia: <b>{{$grupo->materia->matClave}}-{{$grupo->materia->matNombre}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>Curso-Grado-Turno: <b>{{$grupo->gpoSemestre}}-{{$grupo->gpoClave}}-{{$grupo->gpoTurno}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <span>
                            Docente: <b>{{$grupo->empleado->persona->perNombre}}
                            {{$grupo->empleado->persona->perApellido1}}
                            {{$grupo->empleado->persona->perApellido2}}</b>
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <p>{{$calificacionPermitida}}</p>
                    </div>
                </div>
                <br>
                <input id="matPorcentajeParcial" type="hidden" value="{{$matPorcentajeParcial}}">
                <input id="matPorcentajeOrdinario" type="hidden" value="{{$matPorcentajeOrdinario}}">
                



                <div id="primerparcial">
                    <div class="row">
                        <div class="col s12">
                            <table id="" class="responsive-table display tbl-calificaciones" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width:60px;">Clave alumno</th>
                                        <th>Nombre alumno</th>
                                        <th>Pa1</th>
                                        <th>Fa1</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $consecutivo=1;
                                        $tabindexP1=100;
                                        $tabindexP2=200;
                                        $tabindexP3=300;
                                        $tabindexPR=400;
                                        $tabindexOR=500;

                                        // $grupo->materia->matTipoAcreditacion = "as"
                                    @endphp

                                    
                  
                                    @foreach ($inscritos as $inscrito)
                                        <tr>
                                            <td>{{$consecutivo}}</td>
                                            <td>{{$inscrito->curso->alumno->aluClave}}</td>
                                            <td>
                                                {{$inscrito->curso->alumno->persona->perApellido1 . ' ' .
                                                $inscrito->curso->alumno->persona->perApellido2 . ' ' .
                                                $inscrito->curso->alumno->persona->perNombre}}
                                            </td>
                                            @if($grupo->materia->matTipoAcreditacion == 'N' || $grupo->materia->matTipoAcreditacion == 'A')
                                                <td>
                                                    @if ($puedeParcial1)
                                                        @if (is_null($inscrito->calificacion->inscCalificacionParcial1))
                                                            <input tabindex='{{$tabindexP1}}'
                                                                name="calificaciones[inscCalificacionParcial1][{{$inscrito->id}}]"
                                                                type="number" class="permitido calif parcial{{$inscrito->id}}" min="0" max="100"
                                                                <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0" readonly onfocus="this.blur()"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscCalificacionParcial1}}"
                                                                @endif
                                                                data-inscritoid="{{$inscrito->id}}">
                                                        @else 
                                                            <input tabindex='{{$tabindexP1}}'
                                                                name="calificaciones[inscCalificacionParcial1][{{$inscrito->id}}]"
                                                                type="number" class="calif parcial{{$inscrito->id}}" min="0" max="100"
                                                                readonly onfocus="this.blur()"
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscCalificacionParcial1}}"
                                                                @endif

                                                                data-inscritoid="{{$inscrito->id}}">
                                                        @endif
                                                    @else
                                                        <input tabindex='{{$tabindexP1}}'
                                                            name="calificaciones[inscCalificacionParcial1][{{$inscrito->id}}]"
                                                            type="number" class="calif parcial{{$inscrito->id}}" min="0" max="100"
                                                            readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->inscCalificacionParcial1}}"
                                                            data-inscritoid="{{$inscrito->id}}">
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if ($puedeParcial1)
                                                        @if (is_null($inscrito->calificacion->inscFaltasParcial1))
                                                            <input tabindex='{{$tabindexP1}}'
                                                                name="calificaciones[inscFaltasParcial1][{{$inscrito->id}}]"
                                                                type="number" class="calif" min="0" max="100"
                                                                <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0" readonly onfocus="this.blur()"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscFaltasParcial1}}">
                                                                @endif
                                                        @else
                                                            <input tabindex='{{$tabindexP1}}'
                                                                name="calificaciones[inscFaltasParcial1][{{$inscrito->id}}]"
                                                                type="number" class="calif" min="0" max="100"
                                                                readonly onfocus="this.blur()"
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscFaltasParcial1}}">
                                                                @endif
                                                        @endif
                                                    @else 
                                                        <input tabindex='{{$tabindexP1}}'
                                                            name="calificaciones[inscFaltasParcial1][{{$inscrito->id}}]"
                                                            type="number" class="calif" min="0" max="100"
                                                            readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->inscFaltasParcial1}}">
                                                    @endif
                                                </td>
                                                @php
                                                    $tabindexP1++;
                                                    $tabindexP2++;
                                                    $tabindexP3++;
                                                    $tabindexPR++;
                                                    $tabindexOR++;
                                                @endphp
                                            @else
                                                <td>
                                                    <select name="calificaciones[inscCalificacionParcial1][{{$inscrito->id}}]" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>>
                                                        <option value="" selected>SELECCIONA</option>
                                                        <option value="0" @if($inscrito->calificacion->inscCalificacionParcial1 == "0") {{ 'selected' }} @endif>APROBADO</option>
                                                        <option value="1" @if($inscrito->calificacion->inscCalificacionParcial1 == "1") {{ 'selected' }} @endif>REPROBADO</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input
                                                        name="calificaciones[inscFaltasParcial1][{{$inscrito->id}}]"
                                                        type="number" class="calif" min="0" max="100" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                        value="{{$inscrito->calificacion->inscFaltasParcial1}}">
                                                </td>
                                                
                                                <td></td>
                                            @endif
                                        </tr>
                                        @php
                                            $consecutivo++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="segundoparcial">
                    <div class="row">
                        <div class="col s12">
                            <table id="" class="responsive-table display tbl-calificaciones" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th  style="width:60px;">Clave alumno</th>
                                        <th>Nombre alumno</th>
                                        <th>Pa2</th>
                                        <th>Fa2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $consecutivo=1;
                                        $tabindexP1=100;
                                        $tabindexP2=200;
                                        $tabindexP3=300;
                                        $tabindexPR=400;
                                        $tabindexOR=500;

                                        // $grupo->materia->matTipoAcreditacion = "as"
                                    @endphp

                                    
                  
                                    @foreach ($inscritos as $inscrito)
                                        <tr>
                                            <td>{{$consecutivo}}</td>
                                            <td>{{$inscrito->curso->alumno->aluClave}}</td>
                                            <td>
                                                {{$inscrito->curso->alumno->persona->perApellido1 . ' ' .
                                                $inscrito->curso->alumno->persona->perApellido2 . ' ' .
                                                $inscrito->curso->alumno->persona->perNombre}}
                                            </td>
                                            @if($grupo->materia->matTipoAcreditacion == 'N' || $grupo->materia->matTipoAcreditacion == 'A')
                                                <td>
                                                    @if ($puedeParcial2)
                                                        @if (is_null($inscrito->calificacion->inscCalificacionParcial2))
                                                            <input tabindex='{{$tabindexP2}}'
                                                                name="calificaciones[inscCalificacionParcial2][{{$inscrito->id}}]"
                                                                type="number" class="permitido calif parcial{{$inscrito->id}}" min="0" max="100" 
                                                                @php
			                                                       if($grupo->estado_act == 'C'){
			                                                           if(is_null($inscrito->calificacion->inscCalificacionParcial1)){
			                                                               echo 'hidden';
			                                                           }else{
			                                                               echo 'readonly onfocus="this.blur()"';
			                                                           }
			                                                       }
                                                                @endphp
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0" readonly onfocus="this.blur()"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscCalificacionParcial2}}"
                                                                @endif
                                                                data-inscritoid="{{$inscrito->id}}">
                                                        @else 
                                                            <input tabindex='{{$tabindexP2}}'
                                                                name="calificaciones[inscCalificacionParcial2][{{$inscrito->id}}]"
                                                                type="number" class="calif parcial{{$inscrito->id}}" min="0" max="100"
                                                                readonly onfocus="this.blur()"
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscCalificacionParcial2}}"
                                                                @endif
                                                                data-inscritoid="{{$inscrito->id}}">
                                                        @endif
                                                    @else 
                                                        <input tabindex='{{$tabindexP2}}'
                                                            name="calificaciones[inscCalificacionParcial2][{{$inscrito->id}}]"
                                                            type="number" class="calif parcial{{$inscrito->id}}" min="0" max="100"
                                                            readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->inscCalificacionParcial2}}"
                                                            data-inscritoid="{{$inscrito->id}}">
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if ($puedeParcial2)
                                                        @if (is_null($inscrito->calificacion->inscFaltasParcial2))
                                                            <input tabindex='{{$tabindexP2}}'
                                                                name="calificaciones[inscFaltasParcial2][{{$inscrito->id}}]"
                                                                type="number" class="calif" min="0" max="100" 
                                                                @php
			                                                       if($grupo->estado_act == 'C'){
			                                                           if(is_null($inscrito->calificacion->inscCalificacionParcial1)){
			                                                               echo 'hidden';
			                                                           }else{
			                                                               echo 'readonly onfocus="this.blur()"';
			                                                           }
			                                                       }
                                                                @endphp
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0" readonly onfocus="this.blur()"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscFaltasParcial2}}">
                                                                @endif

                                                        @else
                                                            <input tabindex='{{$tabindexP2}}'
                                                                name="calificaciones[inscFaltasParcial2][{{$inscrito->id}}]"
                                                                type="number" class="calif" min="0" max="100"
                                                                readonly onfocus="this.blur()"
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscFaltasParcial2}}">
                                                                @endif
                                                        @endif
                                                    @else
                                                        <input tabindex='{{$tabindexP2}}'
                                                            name="calificaciones[inscFaltasParcial2][{{$inscrito->id}}]"
                                                            type="number" class="calif" min="0" max="100"
                                                            readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->inscFaltasParcial2}}">
                                                    @endif
                                                </td>
                                                @php
                                                    $tabindexP1++;
                                                    $tabindexP2++;
                                                    $tabindexP3++;
                                                    $tabindexPR++;
                                                    $tabindexOR++;
                                                @endphp
                                            @else
                                                <td>
                                                    <select name="calificaciones[inscCalificacionParcial2][{{$inscrito->id}}]" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>>
                                                        <option value="" selected>SELECCIONA</option>
                                                        <option value="0" @if($inscrito->calificacion->inscCalificacionParcial2 == "0") {{ 'selected' }} @endif>APROBADO</option>
                                                        <option value="1" @if($inscrito->calificacion->inscCalificacionParcial2 == "1") {{ 'selected' }} @endif>REPROBADO</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input
                                                        name="calificaciones[inscFaltasParcial2][{{$inscrito->id}}]"
                                                        type="number" class="calif" min="0" max="100" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                        value="{{$inscrito->calificacion->inscFaltasParcial2}}">
                                                </td>
                                                
                                                <td></td>
                                            @endif
                                        </tr>
                                        @php
                                            $consecutivo++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div id="tercerparcial">
                    <div class="row">
                        <div class="col s12">
                            <table id="" class="responsive-table display tbl-calificaciones" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th  style="width:60px;">Clave alumno</th>
                                        <th>Nombre alumno</th>
                                        <th>Pa3</th>
                                        <th>Fa3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $consecutivo=1;
                                        $tabindexP1=100;
                                        $tabindexP2=200;
                                        $tabindexP3=300;
                                        $tabindexPR=400;
                                        $tabindexOR=500;

                                        // $grupo->materia->matTipoAcreditacion = "as"
                                    @endphp

                                    
                  
                                    @foreach ($inscritos as $inscrito)
                                        <tr>
                                            <td>{{$consecutivo}}</td>
                                            <td>{{$inscrito->curso->alumno->aluClave}}</td>
                                            <td>
                                                {{$inscrito->curso->alumno->persona->perApellido1 . ' ' .
                                                $inscrito->curso->alumno->persona->perApellido2 . ' ' .
                                                $inscrito->curso->alumno->persona->perNombre}}
                                            </td>
                                            @if($grupo->materia->matTipoAcreditacion == 'N' || $grupo->materia->matTipoAcreditacion == 'A')
                                                <td>
                                                    @if ($puedeParcial3)
                                                        @if (is_null($inscrito->calificacion->inscCalificacionParcial3))
                                                            <input tabindex='{{$tabindexP3}}'
                                                                name="calificaciones[inscCalificacionParcial3][{{$inscrito->id}}]"
                                                                type="number" class="permitido calif parcial{{$inscrito->id}}" min="0" max="100" 
                                                                @php
			                                                       if($grupo->estado_act == 'C'){
			                                                           if(is_null($inscrito->calificacion->inscCalificacionParcial2)){
			                                                               echo 'hidden';
			                                                           }else{
			                                                               echo 'readonly onfocus="this.blur()"';
			                                                           }
			                                                       }
                                                                @endphp
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0" readonly onfocus="this.blur()"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscCalificacionParcial3}}"
                                                                @endif
                                                                data-inscritoid="{{$inscrito->id}}">
                                                        @else
                                                            <input tabindex='{{$tabindexP3}}'
                                                                name="calificaciones[inscCalificacionParcial3][{{$inscrito->id}}]"
                                                                type="number" class="calif parcial{{$inscrito->id}}" min="0" max="100"
                                                                readonly onfocus="this.blur()"
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscCalificacionParcial3}}"
                                                                @endif
                                                                data-inscritoid="{{$inscrito->id}}">
                                                        @endif
                                                    @else
                                                        <input tabindex='{{$tabindexP3}}'
                                                            name="calificaciones[inscCalificacionParcial3][{{$inscrito->id}}]"
                                                            type="number" class="calif parcial{{$inscrito->id}}" min="0" max="100"
                                                            readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->inscCalificacionParcial3}}"
                                                            data-inscritoid="{{$inscrito->id}}">
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if ($puedeParcial3)
                                                        @if (is_null($inscrito->calificacion->inscFaltasParcial3))
                                                            <input tabindex='{{$tabindexP3}}'
                                                                name="calificaciones[inscFaltasParcial3][{{$inscrito->id}}]"
                                                                type="number" class="calif" min="0" max="100" 
                                                                @php
			                                                       if($grupo->estado_act == 'C'){
			                                                           if (is_null($inscrito->calificacion->inscCalificacionParcial2)) {
			                                                               echo 'hidden';
			                                                            } else {
			                                                               echo 'readonly onfocus="this.blur()"';
			                                                            }
			                                                       }
                                                                @endphp
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0" readonly onfocus="this.blur()"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscFaltasParcial3}}">
                                                                @endif

                                                        @else 
                                                            <input tabindex='{{$tabindexP3}}'
                                                                name="calificaciones[inscFaltasParcial3][{{$inscrito->id}}]"
                                                                type="number" class="calif" min="0" max="100"
                                                                readonly onfocus="this.blur()"
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    value="0"
                                                                @else
                                                                    value="{{$inscrito->calificacion->inscFaltasParcial3}}">
                                                                @endif

                                                        @endif

                                                    @else
                                                        <input tabindex='{{$tabindexP3}}'
                                                            name="calificaciones[inscFaltasParcial3][{{$inscrito->id}}]"
                                                            type="number" class="calif" min="0" max="100"
                                                            readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->inscFaltasParcial3}}">
                                                    @endif
                                                </td>
                                                @php
                                                    $tabindexP1++;
                                                    $tabindexP2++;
                                                    $tabindexP3++;
                                                    $tabindexPR++;
                                                    $tabindexOR++;
                                                @endphp
                                            @else
                                                <td>
                                                    <select name="calificaciones[inscCalificacionParcial3][{{$inscrito->id}}]" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>>
                                                        <option value="" selected>SELECCIONA</option>
                                                        <option value="0" @if($inscrito->calificacion->inscCalificacionParcial3 == "0") {{ 'selected' }} @endif>APROBADO</option>
                                                        <option value="1" @if($inscrito->calificacion->inscCalificacionParcial3 == "1") {{ 'selected' }} @endif>REPROBADO</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="calificaciones[inscFaltasParcial3][{{$inscrito->id}}]"
                                                        type="number" class="calif" min="0" max="100" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                        value="{{$inscrito->calificacion->inscFaltasParcial3}}">
                                                </td>
                                                <td></td>
                                            @endif
                                        </tr>
                                        @php
                                            $consecutivo++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="promediosparciales">
                    <div class="row">
                        <div class="col s12">
                            <table id="" class="responsive-table display tbl-calificaciones" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th  style="width:60px;">Clave alumno</th>
                                        <th>Nombre alumno</th>
                                        <th style="width: 50px;">ProPar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $consecutivo=1;
                                        $tabindexP1=100;
                                        $tabindexP2=200;
                                        $tabindexP3=300;
                                        $tabindexPR=400;
                                        $tabindexOR=500;

                                        // $grupo->materia->matTipoAcreditacion = "as"
                                    @endphp

                                    
                  
                                    @foreach ($inscritos as $inscrito)
                                        <tr>
                                            <td>{{$consecutivo}}</td>
                                            <td>{{$inscrito->curso->alumno->aluClave}}</td>
                                            <td>
                                                {{$inscrito->curso->alumno->persona->perApellido1 . ' ' .
                                                $inscrito->curso->alumno->persona->perApellido2 . ' ' .
                                                $inscrito->curso->alumno->persona->perNombre}}
                                            </td>
                                            @if($grupo->materia->matTipoAcreditacion == 'N' || $grupo->materia->matTipoAcreditacion == 'A')
                                                


                                                <td>
                                                    <input tabindex='{{$tabindexPR}}'
                                                        name="calificaciones[inscPromedioParciales][{{$inscrito->id}}]"
                                                        id="inscPromedioParciales{{$inscrito->id}}"
                                                        type="text" min="0" max="100" readonly  onfocus="this.blur()"
                                                        @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                            value="0" readonly onfocus="this.blur()"
                                                        @else
                                                            value="{{$inscrito->calificacion->inscPromedioParciales}}">
                                                        @endif
                                                </td>
                                                @php
                                                    $tabindexP1++;
                                                    $tabindexP2++;
                                                    $tabindexP3++;
                                                    $tabindexPR++;
                                                    $tabindexOR++;
                                                @endphp
                                            @else
                                                <td>
                                                    <select name="calificaciones[inscCalificacionParcial3][{{$inscrito->id}}]" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>>
                                                        <option value="" selected>SELECCIONA</option>
                                                        <option value="0" @if($inscrito->calificacion->inscCalificacionParcial3 == "0") {{ 'selected' }} @endif>APROBADO</option>
                                                        <option value="1" @if($inscrito->calificacion->inscCalificacionParcial3 == "1") {{ 'selected' }} @endif>REPROBADO</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="calificaciones[inscFaltasParcial3][{{$inscrito->id}}]"
                                                        type="number" class="calif" min="0" max="100" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                        value="{{$inscrito->calificacion->inscFaltasParcial3}}">
                                                </td>
                                                <td></td>
                                            @endif
                                        </tr>
                                        @php
                                            $consecutivo++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



                {{-- GENERAL BAR--}}
                <div id="ordinarios">
                        <div class="row">
                            <div class="col s12">
                                <table id="" class="responsive-table display tbl-calificaciones" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Clave alumno</th>
                                            <th>Nombre alumno</th>
                                            <th>Promedio parciales ({{$matPorcentajeParcial}}%)</th>
                                            <th>Ordinario ({{$matPorcentajeOrdinario}}%)</th>
                                            <th>Final</th>
                                            <th>Inasistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $consecutivo = 1;
                                        @endphp
                                        @foreach ($inscritos as $inscrito)
                                            <tr>
                                                <td>{{$consecutivo}}</td>
                                                <td>{{$inscrito->curso->alumno->aluClave}}</td>
                                                <td>
                                                    {{$inscrito->curso->alumno->persona->perApellido1 . ' ' .
                                                    $inscrito->curso->alumno->persona->perApellido2 . ' ' .
                                                    $inscrito->curso->alumno->persona->perNombre}}
                                                </td>
                                                @if($grupo->materia->matTipoAcreditacion == 'N' || $grupo->materia->matTipoAcreditacion == 'A')
                                                    <td>
                                                        <input id="inscPromedioParciales2{{$inscrito->id}}" type="text"
                                                            readonly onfocus="this.blur()"
                                                            @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                value="0"
                                                            @else
                                                                value="{{$inscrito->calificacion->inscPromedioParciales}}">
                                                            @endif

                                                    </td>
                                                    <td>
                                                        @if ($puedeOrdinario)
                                                            @if (is_null($inscrito->calificacion->inscCalificacionOrdinario) || ($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                <input tabindex='{{$tabindexOR}}' id="inscCalificacionOrdinario{{$inscrito->id}}"
                                                                @if (($matPorcentajeOrdinario == 100 && $matPorcentajeParcial == 0) || $grupo->materia->matTipoAcreditacion == 'A')
                                                                    <?=($grupo->estado_act == 'C') ? 'hidden' : '' ?>
                                                                @else 
                                                                    <?=($grupo->estado_act == 'C' || is_null($inscrito->calificacion->inscCalificacionParcial3)) ? 'hidden' : '' ?>
                                                                @endif
                                                                    name="calificaciones[inscCalificacionOrdinario][{{$inscrito->id}}]"
                                                                    type="number" min="0" max="100"
                                                                    class="permitido calif"
                                                                    value="{{$inscrito->calificacion->inscCalificacionOrdinario}}"
                                                                    data-inscritoid="{{$inscrito->id}}">
                                                                @else
                                                                    <input tabindex='{{$tabindexOR}}' id="inscCalificacionOrdinario{{$inscrito->id}}"
                                                                        readonly onfocus="this.blur()"
                                                                        name="calificaciones[inscCalificacionOrdinario][{{$inscrito->id}}]"
                                                                        type="number" min="0" max="100"
                                                                        class="calif"
                                                                        value="{{$inscrito->calificacion->inscCalificacionOrdinario}}"
                                                                        data-inscritoid="{{$inscrito->id}}">
                                                            @endif
                                                        @else
                                                            <input tabindex='{{$tabindexOR}}' id="inscCalificacionOrdinario{{$inscrito->id}}"
                                                                readonly onfocus="this.blur()"
                                                                name="calificaciones[inscCalificacionOrdinario][{{$inscrito->id}}]"
                                                                type="number" min="0" max="100"
                                                                class="calif"
                                                                value="{{$inscrito->calificacion->inscCalificacionOrdinario}}"
                                                                data-inscritoid="{{$inscrito->id}}">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ((!is_null($inscrito->calificacion->inscCalificacionOrdinario)))
                                                            <input id="incsCalificacionFinal{{$inscrito->id}}"
                                                            name="calificaciones[incsCalificacionFinal][{{$inscrito->id}}]"
                                                            type="text" min="0" max="100" readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->incsCalificacionFinal}}">
                                                        @else
                                                            <input id="incsCalificacionFinal{{$inscrito->id}}"
                                                                name="calificaciones[incsCalificacionFinal][{{$inscrito->id}}]"
                                                                type="text" min="0" max="100" readonly onfocus="this.blur()"
                                                                value="">
                                                        @endif
                                                        
                                                    </td>
                                                @else
                                                    <td>
                                                        <input type="hidden" name="calificaciones[inscPromedioParciales][{{$inscrito->id}}]" readonly onfocus="this.blur()" value="0">
                                                    </td>
                                                    <td>
                                                        <input name="calificaciones[inscCalificacionOrdinario][{{$inscrito->id}}]"
                                                            id="inscCalificacionOrdinario{{$inscrito->id}}"
                                                            type="number" min="0" max="1" onfocusout="calcularPromedioFinalApr({{$inscrito->id}})"
                                                            value="{{$inscrito->calificacion->inscCalificacionOrdinario}}">
                                                    </td>
                                                    <td>
                                                        <input name="calificaciones[incsCalificacionFinal][{{$inscrito->id}}]"
                                                            id="incsCalificacionFinal{{$inscrito->id}}"
                                                            type="number" min="0" max="1"
                                                            readonly onfocus="this.blur()"
                                                            value="{{$inscrito->calificacion->incsCalificacionFinal}}">
                                                    </td>
                                                @endif
                                                <td>
                                                    @if($grupo->materia->matTipoAcreditacion == 'N')
                                                        @if ($puedeOrdinario)
                                                            @if (!is_null($inscrito->calificacion->inscPromedioParciales))
                                                                <select name="calificaciones[inscMotivoFalta][{{$inscrito->id}}]" <?= ($grupo->estado_act == 'C') ? 'readonly onfocus="this.blur()"' : '' ?>>
                                                                    @foreach ($motivosFalta as $item)
                                                                        <option value="{{$item->id}}" {{$inscrito->calificacion->motivofalta_id == $item->id ? "selected": ""}}>{{$item->mfDescripcion}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            @php
                                                $consecutivo++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    @if($grupo->estado_act != 'C')
                        {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-guardar btn-large waves-effect  darken-3','type' => 'submit']) !!}
                    @endif
                </div>
            </div>
        {!! Form::close() !!}
    </div>
  </div>

@endsection

@section('footer_scripts')

@include('scripts.calificacion')

{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script type="text/javascript">
    $(document).ready(function() {
        $('.tbl-calificaciones').dataTable({
            "bPaginate": false,
            "language": {"url":base_url+"/api/lang/javascript/datatables"}
        });
    });

            // disable mousewheel on a input number field when in focus
    // (to prevent Cromium browsers change the value when scrolling)
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('wheel.disableScroll', function (e) {
            e.preventDefault()
        })
    })
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('wheel.disableScroll')
    })


    $(document).on('click', '.btn-guardar', function (e) {
        e.preventDefault();

        var calificacionIncompleta = false;
        $(".permitido.calif").each(function( index ) {
            
            if ($(this).val() === "") {
                calificacionIncompleta = true;
            }
        });

        if (calificacionIncompleta) {
            swal({
                title: "No completado",
                text: "Las calificaciones del parcial u ordinario no estan completadas. ¿Esta seguro que desea guardar los cambios?",
                type: "warning",
                confirmButtonText: "Si",
                confirmButtonColor: '#3085d6',
                cancelButtonText: "No",
                showCancelButton: true
            },
            function() {
                $('#form-guardar').submit();
            });
        } else {
            $('#form-guardar').submit();
        }

        
    });



</script>

@endsection