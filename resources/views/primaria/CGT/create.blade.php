@extends('layouts.dashboard')

@section('template_title')
    Primaria CGT
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_cgt')}}" class="breadcrumb">Lista de Cgt</a>
    <a href="{{url('primaria_cgt/create')}}" class="breadcrumb">Agregar Cgt</a>
@endsection

@section('content')

@php
    $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
@endphp


<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_cgt.store', 'method' => 'POST']) !!}
      <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR CGT</span>

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
                        {!! Form::label('ubicacion_id', 'Ubicación *', array('class' => '')); !!}
                        <select id="ubicacion_id" class="browser-default validate select2" data-ubicacion-id="{{old('ubicacion_id') ?: $ubicacion_id}}" required name="ubicacion_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($ubicaciones as $ubicacion)
                                {{-- @php
                                $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
                                if($ubicacion->id == $ubicacion_id){
                                    echo '<option value="'.$ubicacion->id.'" selected>'.$ubicacion->ubiClave.'-'.$ubicacion->ubiNombre.'</option>';
                                }else{
                                    echo '<option value="'.$ubicacion->id.'">'.$ubicacion->ubiClave.'-'.$ubicacion->ubiNombre.'</option>';
                                }
                                @endphp --}}
                                <option value="{{$ubicacion->id}}">{{$ubicacion->ubiClave}}-{{$ubicacion->ubiNombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id" class="browser-default validate select2" data-departamento-id="{{old('departamento_id')}}" required name="departamento_id" style="width: 100%;">
                            {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id" class="browser-default validate select2" data-escuela-id="{{old('escuela_id')}}" required name="escuela_id" style="width: 100%;">
                            {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('periodo_id', 'Periodo *', array('class' => '')); !!}
                        <select id="periodo_id" class="browser-default validate select2" data-periodo-id="{{old('periodo_id')}}" required name="periodo_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', NULL, array('id' => 'perFechaInicial', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', NULL, array('id' => 'perFechaFinal', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                        <select id="programa_id" class="browser-default validate select2" data-programa-id="{{old('programa_id')}}" required name="programa_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                        <select id="plan_id" class="browser-default validate select2" data-plan-id="{{old('plan_id')}}" required name="plan_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('cgtGradoSemestre', 'Grado *', array('class' => '')); !!}
                        <select id="cgtGradoSemestre" class="browser-default validate select2" data-cgt-grado="{{old('cgtGradoSemestre')}}" required name="cgtGradoSemestre" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('cgtGrupo', NULL, array('id' => 'cgtGrupo', 'class' => 'validate','required','maxlength'=>'3')) !!}
                        {!! Form::label('cgtGrupo', 'Grupo *', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('cgtTurno', 'Turno *', array('class' => '')); !!}
                        <select id="cgtTurno" class="browser-default validate select2" required name="cgtTurno" style="width: 100%;">
                            <option value="M">MATUTINO</option>
                            <option value="V">VESPERTINO</option>
                            <option value="X">MIXTO</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('cgtCupo', NULL, array('id' => 'cgtCupo', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                        {!! Form::label('cgtCupo', 'Cupo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m8">
                        {!! Form::label('empleado_id', 'Maestro titular *', array('class' => '')); !!}
                        <select id="empleado_id" class="browser-default validate select2" required name="empleado_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($empleados as $empleado)
                                <option value="{{$empleado->id}}" @if(old('empleado_id') == $empleado->id) {{ 'selected' }} @endif>{{$empleado->id ." - ".$empleado->persona->perNombre ." ". $empleado->persona->perApellido1." ". $empleado->persona->perApellido2}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                        {!! Form::textarea('cgtDescripcion', NULL, ['id' => 'cgtDescripcion', 'class' => 'materialize-textarea','rows' => 2, 'cols' => 40,'data-length' => "30"]) !!}
                        {!! Form::label('cgtDescripcion', 'Descripción', ['class' => '']); !!}
                        </div>
                    </div>
                </div> --}}
            </div>
          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', [ 'id'=>'btn-guardar','class' => 'btn-large waves-effect  darken-3']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>




@endsection

@section('footer_scripts')
@include('primaria.scripts.funcionesAuxiliares')


<script type="text/javascript">
    $(document).ready(function() {
        let ubicacion = $('#ubicacion_id');
        let departamento = $('#departamento_id');
        let periodo = $('#periodo_id');
        let escuela = $('#escuela_id');
        let programa = $('#programa_id');
        let plan = $('#plan_id');
        let ubicacion_id = $(ubicacion).data('ubicacion-id');

        apply_data_to_select('ubicacion_id', 'ubicacion-id');
        apply_data_to_select('departamento_id', 'departamento-id');
        apply_data_to_select('periodo_id', 'periodo-id');
        apply_data_to_select('escuela_id', 'periodo-id');
        apply_data_to_select('programa_id', 'programa-id');
        apply_data_to_select('plan_id', 'plan-id');

        ubicacion_id ? getDepartamentos(ubicacion_id) : resetSelect('departamento_id');
        ubicacion.on('change', function() {
            this.value ? getDepartamentos(this.value) : resetSelect('departamento_id');
        });

        departamento.on('change', function() {
            if(this.value) {
                getPeriodos(this.value);
                getEscuelas(this.value);
            } else {
                resetSelect('periodo_id');
                resetSelect('escuela_id');
            }
        });

        escuela.on('change', function() {
            this.value ? getProgramas(this.value) : resetSelect('programa_id');
        });

        programa.on('change', function() {
            this.value ? getPlanes(this.value) : resetSelect('plan_id');
        });

        plan.on('change', function() {
            this.value ? getSemestres(this.value) : resetSelect('cgtGradoSemestre');
        });

        periodo.on('change', function() {
            this.value ? periodo_fechasInicioFin(this.value) : emptyElements(['perFechaInicial', 'perFechaFinal']);
        });

        $('#btn-guardar').on('click', function() {
            crear_cgt_primaria();
        });


    });

    function crear_cgt_primaria() {

        var empty_after_create = [
            'cgtGradoSemestre',
            'cgtGrupo'
        ];
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '{{route('primaria.primaria_cgt.store')}}',
            dataType: 'json',
            data: $('form').serialize(),
            success: function(data) {
                if (data.error) {
                    emptyElements(empty_after_create);
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: data.errorMsg
                    });
                } else {
                    if(data) {
                        console.log(data);
                        emptyElements(empty_after_create);
                        swal({
                            type: 'success',
                            title: 'Realizado',
                            text: 'Cgt Creado con éxito.'
                        });
                        location.reload();
                    }

                }
            },
            error: function(jqXhr, textStatus, errorMessage) {
                console.log(errorMessage);
                var resJSON = jqXhr.responseJSON;
                if(resJSON) {
                    showValidatorErrorsJSON(resJSON);
                }
            }
        });
    }//crear_cgt_primaria.
</script>

@endsection
