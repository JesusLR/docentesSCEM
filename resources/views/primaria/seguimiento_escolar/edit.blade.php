@extends('layouts.dashboard')

@section('template_title')
    Primaria seguimiento escolar
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_seguimiento_escolar')}}" class="breadcrumb">Lista de seguimiento escolar</a>
    <label class="breadcrumb">Editar seguimiento escolar</label>
@endsection

@section('content')

<style type="text/css">
    input[type="radio"] {
        margin-left: 10px;
    }
</style>
<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_seguimiento_escolar.update', $primaria_expediente_seguimiento_escolar->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR SEGUIMIENTO ESCOLAR #{{$primaria_expediente_seguimiento_escolar->id}}</span>

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
                        {!! Form::label('curso_id', 'Alumno *', array('class' => '')); !!}
                        <select id="curso_id" class="browser-default validate select2" name="curso_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach ($alumnoCurso as $alumno)
                                <option value="{{$alumno->curso_id}}" {{ $primaria_expediente_seguimiento_escolar->curso_id == $alumno->curso_id ? 'selected' : '' }}>{{$alumno->perApellido1}} {{$alumno->perApellido2}} {{$alumno->perNombre}} - Grupo: {{$alumno->cgtGradoSemestre}}{{$alumno->cgtGrupo}} - Año: {{$alumno->perAnioPago}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('fechaDelaEntrevista', 'Fecha de la entrevista *', array('class' => '')); !!}
                        {!! Form::date('fechaDelaEntrevista', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->fechaEntrevista)->format('Y-m-d'), array('id' => 'fechaDelaEntrevista', 'class' => '','maxlength'=>'255')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('horaDeLaEntrevista', 'Hora de la entrevista *', array('class' => '')); !!}
                        {!! Form::time('horaDeLaEntrevista', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->fechaEntrevista)->format('H:i'), array('id' => 'horaDeLaEntrevista', 'class' => '','maxlength'=>'255')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('entrevistaPeticion', $primaria_expediente_seguimiento_escolar->entrevistaPeticion, array('id' => 'entrevistaPeticion', 'class' => '','maxlength'=>'255')) !!}
                            {!! Form::label('entrevistaPeticion', 'Entrevista a petición de *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('perAsistieronEntrevista', 'Personas que asistieron a la entrevista *', array('class' => '')); !!}
                        <div style="position:relative;">
                            <input type="radio" name="perAsistieronEntrevista" id="papa" value="PAPÁ" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "PAPÁ" ? "checked" : ""}}>
                            <label for="papa">Papá</label>
                            <input type="radio" name="perAsistieronEntrevista" id="mama" value="MAMÁ" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "MAMÁ" ? "checked" : ""}}>
                            <label for="mama">Mamá</label>
                            <input type="radio" name="perAsistieronEntrevista" id="ambos" value="AMBOS" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "AMBOS" ? "checked" : ""}}>
                            <label for="ambos">Ambos</label>
                            <input type="radio" name="perAsistieronEntrevista" id="otro" value="OTRO" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "OTRO" ? "checked" : ""}}>
                            <label for="otro">Otro</label>
                        </div>
                    </div>



                    <div class="col s12 m6 l4" style="display: none;" id="divUno">
                        <div class="input-field">
                            {!! Form::text('perAsistieron1NombreCompleto', $primaria_expediente_seguimiento_escolar->perAsistieron1NombreCompleto, array('id' => 'perAsistieron1NombreCompleto', 'class' => '','maxlength'=>'150')) !!}
                            {!! Form::label('perAsistieron1NombreCompleto', 'Nombre del que asistio 1', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4" style="display: none;" id="divDos">
                        <div class="input-field">
                            {!! Form::text('perAsistieron2NombreCompleto', $primaria_expediente_seguimiento_escolar->perAsistieron2NombreCompleto, array('id' => 'perAsistieron2NombreCompleto', 'class' => '','maxlength'=>'150')) !!}
                            {!! Form::label('perAsistieron2NombreCompleto', 'Nombre del que asistio 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">

                <div class="row">
                    <div class="col s12 m6 l6">
                        <label for="motivoEntrevista">Planteamiento (motivo de la entrevista) *</label>
                        {!! Form::textarea('motivoEntrevista', $primaria_expediente_seguimiento_escolar->motivoEntrevista, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none')) !!}
                    </div>

                    <div class="col s12 m6 l6">
                        <label for="comentarioPadres">Comentarios de los padres *</label>
                        {!! Form::textarea('comentarioPadres', $primaria_expediente_seguimiento_escolar->comentarioPadres, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <label for="acuerdosCompromisos">Acuerdos y compromisos *</label>
                        {!! Form::textarea('acuerdosCompromisos', $primaria_expediente_seguimiento_escolar->acuerdosCompromisos, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none')) !!}
                    </div>

                    <div class="col s12 m6 l6">
                        <label for="observacionesEntrevista">Observaciones *</label>
                        {!! Form::textarea('observacionesEntrevista', $primaria_expediente_seguimiento_escolar->observacionesEntrevista, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none')) !!}
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('proximaEntrevistaFecha', 'Fecha de próxima entrevista', array('class' => '')); !!}
                        {!! Form::date('proximaEntrevistaFecha', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->proximaEntrevista)->format('Y-m-d'), array('id' => 'proximaEntrevistaFecha', 'class' => '','maxlength'=>'255')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('proximaEntrevistaHora', 'Hora de próxima entrevista', array('class' => '')); !!}
                        {!! Form::time('proximaEntrevistaHora', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->proximaEntrevista)->format('h:i'), array('id' => 'proximaEntrevistaHora', 'class' => '','maxlength'=>'255')) !!}
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('primaria_empleado_id_docente', 'Docente presente en la entrevista *', array('class' => '')); !!}
                        <select id="primaria_empleado_id_docente" class="browser-default validate select2" name="primaria_empleado_id_docente" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach ($primaria_empleados as $empleado)
                                <option value="{{$empleado->id}}" {{ $primaria_expediente_seguimiento_escolar->primaria_empleado_id_docente == $empleado->id ? 'selected' : '' }}>{{$empleado->empNombre}} {{$empleado->empApellido1}} {{$empleado->empApellido2}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('primaria_empleado_id_psicologa', 'Psicólogo(a) presente en la entrevista *', array('class' => '')); !!}
                        <select id="primaria_empleado_id_psicologa" class="browser-default validate select2" name="primaria_empleado_id_psicologa" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach ($psicologos as $psicologo)
                                <option value="{{$psicologo->id}}" {{ $primaria_expediente_seguimiento_escolar->primaria_empleado_id_psicologa == $psicologo->id ? 'selected' : '' }}>{{$psicologo->empNombre}} {{$psicologo->empApellido1}} {{$psicologo->empApellido2}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('primaria_empleado_directora', 'Director(a) presente en la entrevista', array('class' => '')); !!}
                        <select id="primaria_empleado_directora" class="browser-default validate select2" name="primaria_empleado_directora" style="width: 100%;">
                            <option value="" >SELECCIONE UNA OPCIÓN</option>
                            @foreach ($director_docente as $director)
                                <option value="{{$director->id}}" {{ $primaria_expediente_seguimiento_escolar->primaria_empleado_directora == $director->id ? 'selected' : '' }}>{{$director->empNombre}} {{$director->empApellido1}} {{$director->empApellido2}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('perNombreExtra', $primaria_expediente_seguimiento_escolar->perNombreExtra, array('id' => 'perNombreExtra', 'class' => '','maxlength'=>'255')) !!}
                            {!! Form::label('perNombreExtra', 'Nombre adicional de presente en la entrevista ', array('class' => '')); !!}
                        </div>
                    </div>
                </div>




          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3 submit-button','type' => 'submit']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>

@endsection

@section('footer_scripts')
<script>

    if ("{{$primaria_expediente_seguimiento_escolar->perAsistieronEntrevista}}" == "PAPÁ"){
        $("#divUno").show();
        $("#divDos").hide();

        $("#perAsistieron1NombreCompleto").prop('required', true);
        $("#perAsistieron2NombreCompleto").prop('required', false);
    }


    if ("{{$primaria_expediente_seguimiento_escolar->perAsistieronEntrevista}}" == "MAMÁ"){
        $("#divUno").show();
        $("#divDos").hide();

        $("#perAsistieron1NombreCompleto").prop('required', true);
        $("#perAsistieron2NombreCompleto").prop('required', false);
    }


    if ("{{$primaria_expediente_seguimiento_escolar->perAsistieronEntrevista}}" == "AMBOS"){
        $("#divUno").show();
        $("#divDos").show();
        $("#perAsistieron1NombreCompleto").prop('required', true);
        $("#perAsistieron2NombreCompleto").prop('required', true);
    }


    if ("{{$primaria_expediente_seguimiento_escolar->perAsistieronEntrevista}}" == "OTRO"){
        $("#divUno").show();
        $("#divDos").show();
        $("#perAsistieron1NombreCompleto").prop('required', true);
        $("#perAsistieron2NombreCompleto").prop('required', false);
    }





    $('input:radio[name="perAsistieronEntrevista"]').change(
        function(){
            if (this.checked ) {
                if(this.value == "PAPÁ"){
                    $("#divUno").show();
                    $("#divDos").hide();

                    $("#perAsistieron1NombreCompleto").prop('required', true);
                    $("#perAsistieron2NombreCompleto").prop('required', false);
                }

                if(this.value == "MAMÁ"){
                    $("#divUno").show();
                    $("#divDos").hide();

                    $("#perAsistieron1NombreCompleto").prop('required', true);
                    $("#perAsistieron2NombreCompleto").prop('required', false);
                }

                if(this.value == "AMBOS"){
                    $("#divUno").show();
                    $("#divDos").show();
                    $("#perAsistieron1NombreCompleto").prop('required', true);
                    $("#perAsistieron2NombreCompleto").prop('required', true);
                }

                if(this.value == "OTRO"){
                    $("#divUno").show();
                    $("#divDos").show();
                    $("#perAsistieron1NombreCompleto").prop('required', true);
                    $("#perAsistieron2NombreCompleto").prop('required', false);

                }
            }
    });
</script>
@endsection
