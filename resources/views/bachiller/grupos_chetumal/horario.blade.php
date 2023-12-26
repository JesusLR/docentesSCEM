@extends('layouts.dashboard')

@php use App\Http\Helpers\Utils; @endphp

@section('template_title')
    Bachiller Horario Grupo
@endsection

@section('head')
    {!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_seq')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('bachiller_grupo_seq')}}" class="breadcrumb">Lista de Grupo</a>
    <a href="{{url('bachiller_grupo_seq/horario/'.$bachiller_grupo->id)}}" class="breadcrumb">Horario</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {!! Form::open(['class' => 'formAgregarHorario', 'onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller_grupo_seq.agregarHorario', 'method' => 'POST']) !!}
            <div class="card ">
                <div class="card-content ">
                    <span class="card-title">AGREGAR HORARIO AL GRUPO #{{$bachiller_grupo->id}}</span>

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
                        <br>
                        <input id="grupo_id" name="grupo_id" type="hidden" value="{{$bachiller_grupo->id}}">
                        <input id="periodo_id" name="periodo_id" type="hidden" value="{{$bachiller_grupo->periodo_id}}">
                        <input id="empleado_id" name="empleado_id" type="hidden" value="{{$bachiller_grupo->bachiller_empleado->id}}">
                        <div class="row">
                            <div class="col s12">
                                <span>Programa: <b>{{$bachiller_grupo->plan->programa->progNombre}}</b></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <span>Periodo: <b>{{$bachiller_grupo->periodo->perNumero}} {{$bachiller_grupo->periodo->perAnio}}</b></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <span>Plan: <b>{{$bachiller_grupo->plan->planClave}}</b></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <span>Materia: <b>{{$bachiller_grupo->bachiller_materia->matClave}}-{{$bachiller_grupo->bachiller_materia->matNombre}}</b></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <span>Curso-Grado-Turno: <b>{{$bachiller_grupo->gpoSemestre}}-{{$bachiller_grupo->gpoClave}}-{{$bachiller_grupo->gpoTurno}}</b></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <span>Docente: <b>{{$bachiller_grupo->bachiller_empleado->empNombre}} {{$bachiller_grupo->bachiller_empleado->empApellido1}} {{$bachiller_grupo->bachiller_empleado->empApellido2}}</b></span>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col s12 m6">
                                {!! Form::label('aula_id', 'Aula', array('class' => '')); !!}
                                <select id="aula_id" class="browser-default validate select2" required name="aula_id" style="width: 100%;">
                                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                    @foreach($aulas as $aula)
                                        <option value="{{$aula->id}}" @if(old('aula_id') == $aula->id) {{ 'selected' }} @endif>{{$aula->aulaClave}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s12 m6">
                                {!! Form::label('ghDia', 'Día', array('class' => '')); !!}
                                <select id="ghDia" class="browser-default validate select2" required name="ghDia" style="width: 100%;">
                                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                    @for($i=1;$i<=6;$i++)
                                        <option value="{{$i}}" @if(old('ghDia') == $i) {{ 'selected' }} @endif>{{Utils::diaSemana($i)}}</option>
                                    @endFor
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col s12 m3">
                                {!! Form::label('ghInicio', 'Hora Inicio', array('class' => '')); !!}
                                <select id="ghInicio" class="browser-default validate select2" required name="ghInicio" style="width: 100%;">
                                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                    @for($i=7;$i<=22;$i++)
                                        <option value="{{$i}}" @if(old('ghInicio') == $i) {{ 'selected' }} @endif>{{$i}}Hrs</option>
                                    @endFor
                                </select>
                            </div>
                            <div class="col s12 m3">
                                {!! Form::label('gMinInicio', 'Minutos', array('class' => '')); !!}
                                <select id="gMinInicio" class="browser-default validate select2" required name="gMinInicio" style="width: 100%; margin-top: 10px;">
                                    <option value="00">0 Min</option>
                                    <option value="05">5 Min</option>
                                    <option value="10">10 Min</option>
                                    <option value="15">15 Min</option>
                                    <option value="20">20 Min</option>
                                    <option value="25">25 Min</option>
                                    <option value="30">30 Min</option>
                                    <option value="35">35 Min</option>
                                    <option value="40">40 Min</option>
                                    <option value="45">45 Min</option>
                                    <option value="50">50 Min</option>
                                    <option value="55">55 Min</option>
                                </select>
                            </div>
                            <div class="col s12 m3">
                                {!! Form::label('ghFinal', 'Hora Final', array('class' => '')); !!}
                                <select id="ghFinal" class="browser-default validate select2" required name="ghFinal" style="width: 100%;">
                                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                    @for($i=7;$i<=22;$i++)
                                        <option value="{{$i}}" @if(old('ghFinal') == $i) {{ 'selected' }} @endif>{{$i}}Hrs</option>
                                    @endFor
                                </select>
                            </div>
                            <div class="col s12 m3">
                                {!! Form::label('gMinFinal', 'Minutos', array('class' => '')); !!}
                                <select id="gMinFinal" class="browser-default validate select2" required name="gMinFinal" style="width: 100%; margin-top: 10px;">
                                    <option value="00" selected>0 Min</option>
                                    <option value="05">5 Min</option>
                                    <option value="10">10 Min</option>
                                    <option value="15">15 Min</option>
                                    <option value="20">20 Min</option>
                                    <option value="25">25 Min</option>
                                    <option value="30">30 Min</option>
                                    <option value="35">35 Min</option>
                                    <option value="40">40 Min</option>
                                    <option value="45">45 Min</option>
                                    <option value="50">50 Min</option>
                                    <option value="55">55 Min</option>
                                </select>
                            </div>
                        </div> --}}
                            {{-- @if (!$bachiller_grupo->grupo_equivalente_id)
                            {!! Form::button('<i class="material-icons left">add</i> Agregar', ['class' => 'btnAgregarHorario btn-large waves-effect  darken-3','type' => 'submit']) !!}
                            @endif
                            @if ($bachiller_grupo->grupo_equivalente_id)
                                <div class="card-panel red darken-1 lighten-2">
                                    <p style="color: #fff;">No se puede modificar horarios de este grupo, porque pertenece a un grupo equivalente.</p>
                                </div>
                            @endif --}}
                        <br><br>
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <p><b>HORARIOS POR GRUPO</b></p>
                                <table id="tbl-horario-bachiller_chetumal" class="responsive-table display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Aula</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Final</th>
                                            <th>Materia</th>
                                            {{--  <th>Acciones</th>  --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            {{--  <div class="col l6 m12 s12">
                                <p><b>HORARIOS ADMINISTRATIVOS</b></p>
                                <table id="tbl-horario-bachiller_chetumal-admivo" class="responsive-table display" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Final</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>  --}}
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>


@endsection

@section('footer_scripts')
{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script type="text/javascript">
    $(document).ready(function() {
        var grupo_id = $('#grupo_id').val();
        var claveMaestro = $('#empleado_id').val();
        var periodoId = $('#periodo_id').val();

        if (grupo_id != "" && grupo_id != null) {
            $('#tbl-horario-bachiller_chetumal').dataTable({
                "language":{"url":base_url+"/api/lang/javascript/datatables"},
                "serverSide": true,
            "dom": '"top"i',
            // "pageLength": 5,
                "bPaginate": false,

                "ajax": {
                    "type" : "GET",
                    'url': base_url+"/api/bachiller_grupo_seq/horario/"+grupo_id,
                    beforeSend: function () {
                        $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');;});
                    },
                    complete: function () {
                        $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                    },
                },
                "columns":[
                    {data: "dia"},
                    {data: "aula.aulaClave"},
                    {data: "horaInicio"},
                    {data: "horaFinal"},
                    {data: "materia"}
                    //{data: "action"}
                ]
            });
        }



        $('.btnAgregarHorario').on("click", function (e) {
            e.preventDefault()

            $.ajax({
                data: {
                    grupo_id: $("#grupo_id").val(),
                    empleado_id: $("#empleado_id").val(),
                    aula_id: $("#aula_id").val(),
                    ghDia: $("#ghDia").val(),
                    ghInicio: $("#ghInicio").val(),
                    gMinInicio: $("#gMinInicio").val(),
                    ghFinal: $("#ghFinal").val(),
                    gMinFinal: $("#gMinFinal").val(),
                    _token: $("meta[name=csrf-token]").attr("content")
                },
                type: "POST",
                dataType: "JSON",
                url: base_url + "/bachiller_grupo_seq/verificarHorasRepetidas",
            })
            .done(function( data, textStatus, jqXHR ) {
                if (data.res) {
                    $('.formAgregarHorario').submit();
                }
                if (!data.res) {
                    swal({
                        title: "Captura de horarios",
                        text: "El horario capturado ya existe",
                        type: "warning",
                        // showCancelButton: true,
                        confirmButtonColor: '#0277bd',
                        // confirmButtonText: 'SI',
                        // cancelButtonText: "NO",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            swal.close()
                            // $('.formAgregarHorario').submit();
                        } else {
                            swal.close()
                        }
                    });
                }

            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                console.log(textStatus)
                console.log(jqXHR)
            });

        })









        if (claveMaestro !== "" && claveMaestro !== null && periodoId !== "" && periodoId !== null) {
            $('#tbl-horario-bachiller_chetumal-admivo').dataTable({
                "language": {"url": base_url + "/api/lang/javascript/datatables"},
                "serverSide": true,
                "dom": '"top"i',
                "bPaginate": false,
                "ajax": {
                    "type" : "GET",
                    'url': base_url + "/api/bachiller_grupo_seq/horario_admin/" + claveMaestro + "/" + periodoId,
                    beforeSend: function () {
                        $('.preloader').fadeIn(200, function() {
                            $(this).append('<div id="preloader"></div>');
                        });
                    },
                    complete: function () {
                        $('.preloader').fadeOut(200, function() {
                            $('#preloader').remove();
                        });
                    },
                },
                "columns":[
                    {data: "dia"},
                    {data: "horaInicio"},
                    {data: "horaFinal"},
                ]
            });
        }
    });
</script>
@endsection