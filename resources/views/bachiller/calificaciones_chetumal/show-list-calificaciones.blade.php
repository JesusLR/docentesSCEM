@extends('layouts.dashboard')

@section('template_title')
    Secundaria calificaciones
@endsection

@section('head')
    {!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_seq')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('bachiller_grupo_seq')}}" class="breadcrumb">Lista de grupos</a>
    <a href="{{url('bachiller_inscritos_seq/'.$grupo_id)}}" class="breadcrumb">Alumnos</a>
    <a href="" class="breadcrumb">Calificaciones</a>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 ">

            {!! Form::open(['route' => 'preescolarcalificaciones.store', 'method' => 'POST', 'id' => 'form-guardar']) !!}
                <input type="hidden" name="trimestre1_edicion" value="{{$trimestre1_edicion}}">
                <input type="hidden" name="inscrito_id" value="{{$inscrito_id}}">
                <input type="hidden" name="trimestre_a_evaluar" value="{{$trimestre_a_evaluar}}">

                <div class="card ">

                    <div class="card-content ">
                        <span class="card-title">CALIFICACIONES DEL ALUMNO - CLAVE {{$curso->alumno->aluClave}}</span>

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
                                    <span>Materia: <b>{{$grupo->preescolar_materia->matClave}}-{{$grupo->preescolar_materia->matNombre}}</b></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <span>Grado-Grupo-Turno: <b>{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}-{{$grupo->gpoTurno}}</b></span>
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
                                                <span>
                                                    Alumno: <b>{{$curso->alumno->persona->perNombre}}
                                                        {{$curso->alumno->persona->perApellido1}}
                                                        {{$curso->alumno->persona->perApellido2}}</b>
                                                </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12" >
                                                    <span>
                                                        Número de Trimestre: <b>{{$trimestre_a_evaluar}}</b>
                                                    </span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12 m6 l4">
                                    @if (is_null($inscrito_faltas))
                                        @if ( ($trimestre1_edicion == 'SI') )
                                            <div class="input-field">
                                                {!! Form::number('trimestreFaltas', 0,
                                                                ['id' => 'trimestreFaltas', 'class' => 'validate','min'=>'0','max'=>'90',
                                                                'onKeyPress="if(this.value.length>2) return false;"','required']) !!}
                                                {!! Form::label('trimestreFaltas', 'Número de faltas del trimestre', ['class' => '']); !!}
                                            </div>
                                        @else
                                            <span>
                                               Número de faltas del trimestre: <b>0</b>
                                            </span>
                                        @endif
                                    @else
                                        @if ( ($trimestre1_edicion == 'SI') )
                                            <div class="input-field">
                                                {!! Form::number('trimestreFaltas', $inscrito_faltas,
                                                                ['id' => 'trimestreFaltas', 'class' => 'validate','min'=>'0','max'=>'90',
                                                                'onKeyPress="if(this.value.length>2) return false;"','required']) !!}
                                                {!! Form::label('trimestreFaltas', 'Número de faltas del trimestre', ['class' => '']); !!}
                                            </div>
                                        @else
                                            <span>
                                               Número de faltas del trimestre: <b>{{$inscrito_faltas}}</b>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12 m8">
                                    @if (is_null($inscrito_observaciones))
                                        @if ( ($trimestre1_edicion == 'SI') )
                                            <div class="input-field">
                                                {!! Form::textarea('trimestreObservaciones', NULL,
                                                    ['id' => 'trimestreObservaciones', 'class' => 'materialize-textarea','rows' => 2, 'cols' => 40,'data-length' => "255"]) !!}
                                                {!! Form::label('trimestreObservaciones', 'Observaciones del alumno durante el trimestre:', ['class' => '']); !!}
                                            </div>
                                        @else
                                            <span>
                                               Observaciones del alumno durante el trimestre: <b> </b>
                                            </span>
                                        @endif
                                    @else
                                        @if ( ($trimestre1_edicion == 'SI') )
                                            <div class="input-field">
                                                {!! Form::textarea('trimestreObservaciones', $inscrito_observaciones,
                                                    ['id' => 'trimestreObservaciones', 'class' => 'materialize-textarea','rows' => 2, 'cols' => 40,'data-length' => "255"]) !!}
                                                {!! Form::label('trimestreObservaciones', 'Observaciones del alumno durante el trimestre:', ['class' => '']); !!}
                                            </div>
                                        @else
                                            <span>
                                               Observaciones del alumno durante el trimestre: <b>{{$inscrito_observaciones}}</b>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <br>

                            <div id="primertrimestre">
                                <div class="row">
                                    <div class="col s12">
                                        <table id="" class="responsive-table display tbl-calificaciones-bachiller" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Categoría</th>
                                                <th>Aprendizaje</th>
                                                <th>Aprovechamiento</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($calificaciones as $row)
                                                <tr>
                                                    <td>{{ $row->tipo }}</td>
                                                    <td>{{ $row->rubrica }}</td>

                                                    <td>

                                                        @if (is_null($row->trimestre1_nivel))

                                                            <input
                                                                   name="calificaciones[trimestre1][{{$row->id}}]"
                                                                   type="number" class="permitido calif parcial{{$row->id}}" min="1" max="4"
                                                                   <?= ($trimestre1_edicion == 'NO') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                                   @if ( ($trimestre1_edicion == 'SI') )
                                                                        value="4" required
                                                                   @else
                                                                        value="{{$row->trimestre1_nivel}}" readonly onfocus="this.blur()"
                                                                   @endif
                                                                   data-inscritoid="{{$row->id}}">

                                                        @else

                                                            <input
                                                                    name="calificaciones[trimestre1][{{$row->id}}]"
                                                                    type="number" class="permitido calif parcial{{$row->id}}" min="1" max="4"
                                                                    <?= ($trimestre1_edicion == 'NO') ? 'readonly onfocus="this.blur()"' : '' ?>
                                                                    @if ( ($trimestre1_edicion == 'SI') )
                                                                    value="{{$row->trimestre1_nivel}}" required
                                                                    @else
                                                                    value="{{$row->trimestre1_nivel}}" readonly onfocus="this.blur()"
                                                                    @endif
                                                                    data-inscritoid="{{$row->id}}">

                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                    </div>

                    <div class="card-action">
                        @if($grupo_abierto == 'SI')
                            {!! Form::button('<i class="material-icons left">save</i> Guardar Calificaciones', ['class' => 'btn-guardar btn-large waves-effect  darken-3','type' => 'submit']) !!}
                        @endif
                            <a href="{{ url('bachiller_inscritos_seq/'.$grupo->id) }}" class="btn-guardar btn-large">Regresar al listado de alumnos</a>
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
            $('.tbl-calificaciones-bachiller').dataTable({
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


    </script>


@endsection
