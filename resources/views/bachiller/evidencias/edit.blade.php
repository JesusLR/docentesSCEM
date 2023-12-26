@extends('layouts.dashboard')

@section('template_title')
    Bachiller evidencia
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_yucatan')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('bachiller_evidencias', [$grupo_id])}}" class="breadcrumb">Lista de evidencias</a>
    <label class="breadcrumb">Editar evidencia</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['bachiller.bachiller_evidencias.update', $bachiller_evidencias->id])) }}
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR EVIDENCIA #{{$bachiller_evidencias->id}}</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">General</a></li>
                        </ul>
                    </div>
                </nav>

                <input type="hidden" value="{{$grupo_id}}" name="bachiller_grupo_id">
                {{-- GENERAL BAR--}}
                <div id="general">
                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('ubicacion_id', 'Campus *', array('class' => '')); !!}
                            <select id="ubicacion_id" class="browser-default validate select2" required
                                name="ubicacion_id" style="width: 100%;">
                                <option value="{{$bachiller_evidencias->ubicacion_id}}">
                                    {{$bachiller_evidencias->ubiClave.'-'.$bachiller_evidencias->ubiNombre}}</option>

                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                            <select id="departamento_id" data-departamento-idold="{{old('departamento_id')}}"
                                class="browser-default validate select2" required name="departamento_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_evidencias->departamento_id}}">
                                    {{$bachiller_evidencias->depClave.'-'.$bachiller_evidencias->depNombre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                            <select id="escuela_id" data-escuela-idold="{{old('escuela_id')}}"
                                class="browser-default validate select2" required name="escuela_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_evidencias->escuela_id}}">
                                    {{$bachiller_evidencias->escClave.'-'.$bachiller_evidencias->escNombre}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('periodo_id', 'Período *', array('class' => '')); !!}
                            <select id="periodo_id" data-plan-idold="{{old('periodo_id')}}"
                                class="browser-default validate select2" required name="periodo_id" style="width: 100%;">
                                <option value="{{$bachiller_evidencias->periodo_id}}">
                                    {{$bachiller_evidencias->perNumero.'-'.$bachiller_evidencias->perAnio}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                            <select id="programa_id" data-programa-idold="{{old('programa_id')}}"
                                class="browser-default validate select2" required name="programa_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_evidencias->programa_id}}">
                                    {{$bachiller_evidencias->progClave.'-'.$bachiller_evidencias->progNombre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                            <select id="plan_id" data-plan-idold="{{old('plan_id')}}"
                                class="browser-default validate select2" required name="plan_id" style="width: 100%;">
                                <option value="{{$bachiller_evidencias->plan_id}}">{{$bachiller_evidencias->planClave}}
                                </option>
                            </select>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('matSemestre', 'Grado *', array('class' => '')); !!}
                            <input class="gpoSemestreOld" type="hidden" data-gpoSemestre-idold="{{old('matSemestre')}}">
                            <select id="matSemestre"
                                data-gposemestre-idold="{{old('matSemestre')}}"
                                class="browser-default validate select2" required name="matSemestre" style="width: 100%;">
                                <option value="{{$bachiller_evidencias->matSemestre}}">{{$bachiller_evidencias->matSemestre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('materia_id', 'Materia *', array('class' => '')); !!}
                            <select id="materia_id" name="materia_id" data-plan-idold="{{old('materia_id')}}"
                                class="browser-default validate select2" required name="materia_id" style="width: 100%;">
                                <option value="{{$bachiller_evidencias->bachiller_materia_id}}">
                                    {{$bachiller_evidencias->matClave.'-'.$bachiller_evidencias->matNombre}}</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="materia_acd_id" id="materia_acd_id_label">Materia ACD</label>
                            <select id="materia_acd_id" @if ($bachiller_evidencias->bachiller_materia_acd_id != "") @else disabled @endif data-plan-idold="{{old('materia_acd_id')}}"
                                class="browser-default validate select2" name="materia_acd_id" style="width: 100%;">
                                @if ($bachiller_evidencias->bachiller_materia_acd_id != "")
                                <option value="{{$bachiller_evidencias->bachiller_materia_acd_id}}">{{$bachiller_evidencias->gpoMatComplementaria}}</option>
                                @else
                                    <option value="NULL" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @endif
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('eviNumero', $bachiller_evidencias->eviNumero, array('id' =>
                                'eviNumero', 'class' => '','min'=>'0','max'=>'100')) !!}
                                {!! Form::label('eviNumero', 'Número evidencia *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('eviDescripcion', $bachiller_evidencias->eviDescripcion, array('id' =>
                                'eviDescripcion', 'class' => '','maxlength'=>'255')) !!}
                                {!! Form::label('eviDescripcion', 'Descripción evidencia *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('eviFechaEntrega', 'Fecha entrega *', array('class' => '')); !!}
                            {!! Form::date('eviFechaEntrega', $bachiller_evidencias->eviFechaEntrega, array('id' =>
                            'eviFechaEntrega', 'class' => '','maxlength'=>'15')) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('eviPuntos', $bachiller_evidencias->eviPuntos, array('id' =>
                                'eviPuntos', 'class' => '','min'=>'0','max'=>'100')) !!}
                                {!! Form::label('eviPuntos', 'Puntos evidencia *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('eviTipo', 'Tipo evidencia *', array('class' => '')); !!}
                            <select id="eviTipo" data-plan-idold="{{old('eviTipo')}}"
                                class="browser-default validate select2" name="eviTipo" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                <option value="A" {{$bachiller_evidencias->eviTipo == 'A' ? 'selected' : ''}}>A - DE PROCESO</option>
                                <option value="P" {{$bachiller_evidencias->eviTipo == "P" ? 'selected' : ''}}>P - DE PRODUCTO</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('eviFaltas', 'Faltas evidencia *', array('class' => '')); !!}
                            <select id="eviFaltas"
                                data-plan-idold="{{old('eviFaltas')}}"
                                class="browser-default validate select2" name="eviFaltas" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                <option value="S" {{$bachiller_evidencias->eviFaltas == 'S' ? 'selected' : ''}}>S - SE REGISTRA FALTAS</option>
                                <option value="N" {{$bachiller_evidencias->eviFaltas == 'N' ? 'selected' : ''}}>N - NO SE REGISTRA FALTAS</option>
                            </select>

                        </div>
                    </div>


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

{{--  para llenar automaticamente los combos   --}}
<script>

    $(document).ready(function(){

        var select = "{{$bachiller_evidencias->eviFaltas}}";
        //Cuando hay change
        //Merida
        $("#ubicacion_id").change(function(){
            $("#eviFaltas").empty();


            if($('select[id=ubicacion_id]').val() == "1"){
                $("#eviTipo").change(function(){

                    if($('select[id=eviTipo]').val() == "A"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                        $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                    }

                    if($('select[id=eviTipo]').val() == "P"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                    }
                });

                //Si no hay change de eviTipo
                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                }
            }

        });


        if($('select[id=ubicacion_id]').val() == "1"){
            $("#eviFaltas").empty();
            $("#eviTipo").change(function(){

                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                }
            });


            //Si no hay change de eviTipo
            if($('select[id=eviTipo]').val() == "A"){
                $("#eviFaltas").empty();



                $("#eviFaltas").append(`<option select='${select}' value='N'>N - NO SE REGISTRA FALTAS</option>`);
                $("#eviFaltas").append(`<option select='${select}' value='S'>S - SE REGISTRA FALTAS</option>`);

                $("#eviFaltas").val(select);
            }

            if($('select[id=eviTipo]').val() == "P"){
                $("#eviFaltas").empty();
                $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                $("#eviFaltas").val(select);
            }
        }

        //Valladolid change
        $("#ubicacion_id").change(function(){
            $("#eviFaltas").empty();


            if($('select[id=ubicacion_id]').val() == "2"){
                $("#eviTipo").change(function(){

                    if($('select[id=eviTipo]').val() == "A"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

                    }

                    if($('select[id=eviTipo]').val() == "P"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);


                    }
                });

                //Si no hay change de eviTipo
                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

                    $("#eviFaltas").val(select);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                    $("#eviFaltas").val(select);


                }
            }


        });

        //valladolid
        if($('select[id=ubicacion_id]').val() == "2"){
            $("#eviFaltas").empty();

            $("#eviTipo").change(function(){

                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);


                }
            });


            //Si no hay change de eviTipo

            if($('select[id=eviTipo]').val() == "A"){
                $("#eviFaltas").empty();
                $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

                $("#eviFaltas").val(select);
            }

            if($('select[id=eviTipo]').val() == "P"){
                $("#eviFaltas").empty();
                $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                $("#eviFaltas").val(select);

            }
        }



    });
    </script>
    @endsection
