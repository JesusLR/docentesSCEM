@extends('layouts.dashboard')

@section('template_title')
    Primaria pase de lista
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Lista de grupos</a>
    <a href="" class="breadcrumb">Alumnos</a>
@endsection

@section('content')
<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_inscritos.guardarPaseLista', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
          <span class="card-title">PASE DE LISTA DEL GRUPO #{{$grupo->id}}</span>
            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#parciales">Lista</a></li>
                </ul>
              </div>
            </nav>

            <br>
            <input id="grupo_id" name="grupo_id" type="hidden" value="{{$grupo->id}}">
            <input id="primaria_materia_id" name="primaria_materia_id" type="hidden" value="{{$grupo->primaria_materia->id}}">

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
                    <span>Materia: <b>{{$grupo->primaria_materia->matClave}}-{{$grupo->primaria_materia->matNombre}}</b></span>
                </div>
            </div>

            @if ($grupo->primaria_materia_asignatura != null)
            <div class="row">
                <div class="col s12">
                    <span>Asignatura: <b>{{$grupo->primaria_materia_asignatura->matClaveAsignatura}}-{{$grupo->primaria_materia_asignatura->matNombreAsignatura}}</b></span>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col s12">
                    <span>Curso-Grado-Turno: <b>{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}
                        @if ($grupo->gpoTurno != "")
                        -{{$grupo->gpoTurno}}</b></span>
                        @else
                        - No registrado
                        @endif

                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>
                        Docente: <b>{{$grupo->primaria_empleado->empNombre}}
                            {{$grupo->primaria_empleado->empApellido1}}
                            {{$grupo->primaria_empleado->empApellido2}}</b>
                    </span>
                    <span style="float:right">
                        Fecha <input type="date" name="fecha_asistencia" id="fecha_asistencia" readonly="true">
                    </span>
                </div>
            </div>

            <br>

            @if ($Total > 0)
                <div></div>
            @else
            <div style="font-size: 20px; text-align: center; color: red;"> “Nuevo día de pase de lista, favor de ajustar la columna de estado para retardos o inasistencias”</div>
            @endif


            {{-- GENERAL BAR--}}
            <div id="parciales">
                <div class="row">
                    <div class="col s12">
                        <table id="tbl-parciales" class="responsive-table display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                {{--  <th>ID</th>  --}}
                                <th>Clave Pago</th>
                                <th>Alumno</th>
                                <th>Año Escolar</th>
                                <th>Modalidad</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>

                                @if ($Total > 0)
                                @foreach ($primaria_asistencia1 as $key => $asistenciaTable)
                                <tr>
                                    <th style="font-weight: normal">{{$key+1}}</th>
                                    {{--  <th name="id_alumno" style="font-weight: normal; display:none;">{{$asistenciaTable->alumno_id}}</th>  --}}
                                    <th style="font-weight: normal">{{$asistenciaTable->aluClave}}</th>
                                    <th style="font-weight: normal">{{$asistenciaTable->perApellido1}} {{$asistenciaTable->perApellido2}} {{$asistenciaTable->perNombre}}</th>
                                    <th style="font-weight: normal">{{$asistenciaTable->perAnio}}</th>
                                    <th style="font-weight: normal">{{$asistenciaTable->inscTipoAsistencia}}</th>
                                    <th style="font-weight: normal">
                                        <select id="estado" class="browser-default validate" required name="estado[]" style="width: 100%;">
                                            {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                                            <option value="A" {{ $asistenciaTable->estado == "A" ? 'selected="selected"' : '' }}>ASISTENCIA</option>
                                            <option value="R" {{ $asistenciaTable->estado == "R" ? 'selected="selected"' : '' }}>RETARDO</option>
                                            <option value="F" {{ $asistenciaTable->estado == "F" ? 'selected="selected"' : '' }}>FALTA</option>
                                            <option value="FJ" {{ $asistenciaTable->estado == "FJ" ? 'selected="selected"' : '' }}>FALTA JUSTIFICADA</option>
                                        </select>
                                        <input id="inscrito_id" name="inscrito_id[]" type="hidden" value="{{$asistenciaTable->asistencia_inscrito_id}}">
                                        <input id="id" name="id[]" type="hidden" value="{{$asistenciaTable->id}}">
                                    </th>

                                    @php



                                    $faltasExistentes =  DB::select("SELECT count(primaria_asistencia.estado) AS TotalFaltas FROM primaria_asistencia as primaria_asistencia
                                    INNER JOIN primaria_inscritos as primaria_inscritos ON primaria_inscritos.id = primaria_asistencia.asistencia_inscrito_id
                                    INNER JOIN primaria_grupos as primaria_grupos ON primaria_grupos.id = primaria_inscritos.primaria_grupo_id
                                    INNER JOIN periodos as periodos ON periodos.id = primaria_grupos.periodo_id
                                    WHERE asistencia_inscrito_id='".$asistenciaTable->asistencia_inscrito_id."'
                                    AND estado='F'
                                    AND periodos.id = '".$perActual."'");
                                    @endphp

                                    @if ($faltasExistentes[0]->TotalFaltas >= 25 && $faltasExistentes[0]->TotalFaltas < 39)
                                    <th>
                                        <label style="color: #F5C40C;">Total de faltas: {{$faltasExistentes[0]->TotalFaltas}}</label>
                                    </th>
                                    @endif

                                    @if ($faltasExistentes[0]->TotalFaltas >= 39)
                                    <th>
                                        <label style="color: #D31401;">Total de faltas: {{$faltasExistentes[0]->TotalFaltas}}</label>
                                    </th>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                @foreach ($paseAsistencia_collection as $key => $item)
                                <tr>
                                    <th style="font-weight: normal">{{$key+1}}</th>
                                    {{--  <th name="id_alumno" style="font-weight: normal;  display;none;">{{$item->alumno_id}}</th>  --}}
                                    <th style="font-weight: normal">{{$item->aluClave}}</th>
                                    <th style="font-weight: normal">{{$item->perApellido1}} {{$item->perApellido2}} {{$item->perNombre}}</th>
                                    <th style="font-weight: normal">{{$item->perAnio}}</th>
                                    <th style="font-weight: normal">{{$item->inscTipoAsistencia}}</th>

                                    <th style="font-weight: normal">
                                        <select id="estado" class="browser-default validate" required name="estado[]" style="width: 100%;">
                                            {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                                            <option value="A">ASISTENCIA</option>
                                            <option value="R">RETARDO</option>
                                            <option value="F">FALTA</option>
                                            <option value="FJ">FALTA JUSTIFICADA</option>
                                        </select>
                                        <input id="inscrito_id" name="inscrito_id[]" type="hidden" value="{{$item->inscrito_id}}">

                                    </th>

                                    @php

                                    //PRIMARIA PERIODO ACTUAL
                                    #$departamento = Departamento::with('ubicacion')->findOrFail(14);
                                    #$perActual = $departamento->perActual;

                                    $faltasExistentes =  DB::select("SELECT count(primaria_asistencia.estado) AS TotalFaltas FROM primaria_asistencia as primaria_asistencia
                                    INNER JOIN primaria_inscritos as primaria_inscritos ON primaria_inscritos.id = primaria_asistencia.asistencia_inscrito_id
                                    INNER JOIN primaria_grupos as primaria_grupos ON primaria_grupos.id = primaria_inscritos.primaria_grupo_id
                                    INNER JOIN periodos as periodos ON periodos.id = primaria_grupos.periodo_id
                                    WHERE asistencia_inscrito_id='".$item->inscrito_id."'
                                    AND estado='F'
                                    AND periodos.id = '".$perActual."'");
                                    @endphp

                                    @if ($faltasExistentes[0]->TotalFaltas >= 25 && $faltasExistentes[0]->TotalFaltas < 39)
                                    <th>
                                        <label style="color: #F5C40C;">Total de faltas: {{$faltasExistentes[0]->TotalFaltas}}</label>
                                    </th>
                                    @endif

                                    @if ($faltasExistentes[0]->TotalFaltas >= 39)
                                    <th>
                                        <label style="color: #D31401;">Total de faltas: {{$faltasExistentes[0]->TotalFaltas}}</label>
                                    </th>
                                    @endif
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

          <div class="card-action">
                {{--  @if (!Utils::validaPermiso('grupo', $grupo->plan->programa_id))
                    @if($grupo->estado_act != 'C')  --}}
                        {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
                {{--  @endif
                @endif  --}}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>

@endsection

@section('footer_scripts')
<script>

    let fecha = new Date();
    let dia = fecha.getDate();
    if(dia < 10){
        dia = '0' + dia;
    }
    let mes = (fecha.getMonth() +1);
    if(mes < 10){
        mes = '0' + mes;
    }
    let anio = fecha.getFullYear();

    let fechaHoy = anio + '-' + mes + '-' + dia;

    $("#fecha_asistencia").val(fechaHoy);


</script>

@endsection
