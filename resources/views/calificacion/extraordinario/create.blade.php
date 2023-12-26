@extends('layouts.dashboard')

@php use App\Http\Helpers\Utils; @endphp

@section('template_title')
    Calificación
@endsection

@section('head')
{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('/')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('extraordinarios')}}" class="breadcrumb">Lista de extraordinarios</a>
    <a href="" class="breadcrumb">Calificaciones del extraordinario</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','url' => 'extraordinario/store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
          <span class="card-title">CALIFICACIONES DEL EXTRAORDINARIO #{{$extraordinario->id}}</span>
            {{-- NAVIGATION BAR--}}

            <input id="extraordinario_id" name="extraordinario_id" type="hidden" value="{{$extraordinario->id}}">
            <div class="row">
                <div class="col s12">
                    <span>Programa: <b>{{$extraordinario->materia->plan->programa->progNombre}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Plan: <b>{{$extraordinario->materia->plan->planClave}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Materia: <b>{{$extraordinario->materia->matClave}}-{{$extraordinario->materia->matNombre}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>Fecha y hora: <b>{{$extraordinario->extFecha}} - {{$extraordinario->extHora}}</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <span>
                        Docente: <b>{{$extraordinario->empleado->persona->perNombre}}
                        {{$extraordinario->empleado->persona->perApellido1}}
                        {{$extraordinario->empleado->persona->perApellido2}}</b>
                    </span>
                </div>
            </div>

            {{-- GENERAL BAR--}}

                <div class="row">
                    <div class="col s12">
                        <table id="tbl-alumnos" class="responsive-table display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Clave alumno</th>
                                <th>Nombre alumno</th>
                                <th>iex Estado</th>
                                <th>Calificación</th>
                                <th>Inasistencia</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $consecutivo=1;

                                @endphp

                                @foreach ($inscritos as $inscritoEx)

                                <tr>
                                    <td>{{$consecutivo}}</td>
                                    <td>{{$inscritoEx->alumno->aluClave}}</td>
                                    <td>
                                        {{$inscritoEx->alumno->persona->perApellido1 . ' ' .
                                        $inscritoEx->alumno->persona->perApellido2  . ' ' .
                                        $inscritoEx->alumno->persona->perNombre}}
                                    </td>
                                    <td>{{$inscritoEx->iexEstado}}</td>

                                    <td>
                                        @if ($extraordinario->materia->matTipoAcreditacion == "N")

                                            @if (is_null($inscritoEx->iexCalificacion))
                                                <input
                                                    name="calificacion[inscEx][{{$inscritoEx->id}}]"
                                                    type="number" class="calif" min="0" max="100"
                                                    value="{{$inscritoEx->iexCalificacion}}"
                                                    data-inscritoid="{{$inscritoEx->id}}">
                                            @else
                                                <input
                                                    name="calificacion[inscEx][{{$inscritoEx->id}}]"
                                                    type="number" class="calif" min="0" max="100"
                                                    readonly
                                                    value="{{$inscritoEx->iexCalificacion}}"
                                                    data-inscritoid="{{$inscritoEx->id}}">
                                            @endif
                                        @else
                                            @if (is_null($inscritoEx->iexCalificacion))
                                                <select data-inscritoid="{{$inscritoEx->id}}" name="calificacion[inscEx][{{$inscritoEx->id}}]" id="" class="calif">
                                                    <option value="0">APROBADO</option>
                                                    <option value="1">NO APROBADO</option>
                                                </select>
                                            @else
                                                <div style="position:relative;">
                                                    <select disabled data-inscritoid="{{$inscritoEx->id}}" name="calificacion[inscEx][{{$inscritoEx->id}}]" id="" class="calif">
                                                        <option value="0" {{$inscritoEx->iexCalificacion == "0" ? "selected": "" }}>APROBADO</option>
                                                        <option value="1" {{$inscritoEx->iexCalificacion == "1" ? "selected": "" }}>NO APROBADO</option>
                                                    </select>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if (is_null($inscritoEx->iexCalificacion))
                                            <select name="calificacion[asistencia][{{$inscritoEx->id}}]">
                                                @foreach ($motivosFalta as $item)
                                                    <option value="{{$item->id}}" {{$inscritoEx->motivofalta_id == $item->id ? "selected": ""}}>{{$item->mfDescripcion}}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="calificacion[asistencia][{{$inscritoEx->id}}]" >
                                                @foreach ($motivosFalta as $item)
                                                    <option value="{{$item->id}}" {{$inscritoEx->motivofalta_id == $item->id ? "selected": ""}}>{{$item->mfDescripcion}}</option>
                                                @endforeach
                                            </select>
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


            {{-- GENERAL BAR--}}


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

@include('scripts.calificacion')
{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script type="text/javascript">
    $(document).ready(function() {

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
        $('#tbl-alumnos').dataTable({
            "language":{"url":base_url+"/api/lang/javascript/datatables"},
            "dom": '"top"i',
            "ordering": false,
            "bPaginate": false,
            "order": [
                [2, 'asc']
            ]
        });
    });
</script>

@endsection
