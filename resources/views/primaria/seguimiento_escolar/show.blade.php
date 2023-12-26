@extends('layouts.dashboard')

@section('template_title')
    Primaria seguimiento escolar
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_seguimiento_escolar')}}" class="breadcrumb">Lista de seguimiento escolar</a>
    <label class="breadcrumb">Ver seguimiento escolar</label>
@endsection

@section('content')

<style type="text/css">
    input[type="radio"] {
        margin-left: 10px;
    }
</style>
<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">SEGUIMIENTO ESCOLAR #{{$primaria_expediente_seguimiento_escolar->id}}</span>

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
                        <input type="text" readonly value="{{$primaria_expediente_seguimiento_escolar->perApellido1}} {{$primaria_expediente_seguimiento_escolar->perApellido2}} {{$primaria_expediente_seguimiento_escolar->perNombre}} - Grupo: {{$primaria_expediente_seguimiento_escolar->cgtGradoSemestre}}{{$primaria_expediente_seguimiento_escolar->cgtGrupo}} - Año: {{$primaria_expediente_seguimiento_escolar->perAnioPago}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('fechaDelaEntrevista', 'Fecha de la entrevista *', array('class' => '')); !!}
                        {!! Form::date('fechaDelaEntrevista', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->fechaEntrevista)->format('Y-m-d'), array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('horaDeLaEntrevista', 'Hora de la entrevista *', array('class' => '')); !!}
                        {!! Form::time('horaDeLaEntrevista', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->fechaEntrevista)->format('H:i'), array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('entrevistaPeticion', $primaria_expediente_seguimiento_escolar->entrevistaPeticion, array('readonly' => 'true')) !!}
                            {!! Form::label('entrevistaPeticion', 'Entrevista a petición de *', array('class' => '')); !!}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('perAsistieronEntrevista', 'Personas que asistieron a la entrevista *', array('class' => '')); !!}
                        <div style="position:relative;">
                            <input type="radio" name="perAsistieronEntrevista" id="papa" value="PAPÁ" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "PAPÁ" ? "checked" : ""}}>
                            <label for="">Papá</label>
                            <input type="radio" name="perAsistieronEntrevista" id="mama" value="MAMÁ" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "MAMÁ" ? "checked" : ""}}>
                            <label for="">Mamá</label>
                            <input type="radio" name="perAsistieronEntrevista" id="ambos" value="AMBOS" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "AMBOS" ? "checked" : ""}}>
                            <label for="">Ambos</label>
                            <input type="radio" name="perAsistieronEntrevista" id="otro" value="OTRO" {{ $primaria_expediente_seguimiento_escolar->perAsistieronEntrevista == "OTRO" ? "checked" : ""}}>
                            <label for="">Otro</label>
                        </div>
                    </div>



                    <div class="col s12 m6 l4" style="display: none;" id="divUno">
                        <div class="input-field">
                            {!! Form::text('perAsistieron1NombreCompleto', $primaria_expediente_seguimiento_escolar->perAsistieron1NombreCompleto, array('readonly' => 'true')) !!}
                            {!! Form::label('perAsistieron1NombreCompleto', 'Nombre del que asistio 1', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4" style="display: none;" id="divDos">
                        <div class="input-field">
                            {!! Form::text('perAsistieron2NombreCompleto', $primaria_expediente_seguimiento_escolar->perAsistieron2NombreCompleto, array('readonly' => 'true')) !!}
                            {!! Form::label('perAsistieron2NombreCompleto', 'Nombre del que asistio 2', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">

                <div class="row">
                    <div class="col s12 m6 l6">
                        <label for="motivoEntrevista">Planteamiento (motivo de la entrevista) *</label>
                        {!! Form::textarea('motivoEntrevista', $primaria_expediente_seguimiento_escolar->motivoEntrevista, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none', 'readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l6">
                        <label for="comentarioPadres">Comentarios de los padres *</label>
                        {!! Form::textarea('comentarioPadres', $primaria_expediente_seguimiento_escolar->comentarioPadres, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none', 'readonly' => 'true')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l6">
                        <label for="acuerdosCompromisos">Acuerdos y compromisos *</label>
                        {!! Form::textarea('acuerdosCompromisos', $primaria_expediente_seguimiento_escolar->acuerdosCompromisos, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none', 'readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l6">
                        <label for="observacionesEntrevista">Observaciones *</label>
                        {!! Form::textarea('observacionesEntrevista', $primaria_expediente_seguimiento_escolar->observacionesEntrevista, array('id' => 'observacion_contenido', 'class' => 'validate',
                        'style' => 'resize: none', 'readonly' => 'true')) !!}
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('proximaEntrevistaFecha', 'Fecha de próxima entrevista', array('class' => '')); !!}
                        {!! Form::date('proximaEntrevistaFecha', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->proximaEntrevista)->format('Y-m-d'), array('readonly' => 'true')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('proximaEntrevistaHora', 'Hora de próxima entrevista', array('class' => '')); !!}
                        {!! Form::time('proximaEntrevistaHora', \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->proximaEntrevista)->format('h:i'), array('readonly' => 'true')) !!}
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('primaria_empleado_id_docente', 'Docente presente en la entrevista *', array('class' => '')); !!}
                        <input type="text" readonly value="{{$primaria_expediente_seguimiento_escolar->empNombre}} {{$primaria_expediente_seguimiento_escolar->empApellido1}} {{$primaria_expediente_seguimiento_escolar->empApellido2}}">
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('primaria_empleado_id_psicologa', 'Psicólogo(a) presente en la entrevista *', array('class' => '')); !!}
                        <input type="text" readonly value="{{$primaria_expediente_seguimiento_escolar->nombrePsi}} {{$primaria_expediente_seguimiento_escolar->apellido1Psi}} {{$primaria_expediente_seguimiento_escolar->apellido2Psi}}">

                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('primaria_empleado_directora', 'Director(a) presente en la entrevista', array('class' => '')); !!}
                        <input type="text" readonly value="{{$primaria_expediente_seguimiento_escolar->nombreDirec}} {{$primaria_expediente_seguimiento_escolar->apellido1Direc}} {{$primaria_expediente_seguimiento_escolar->apellido2Direc}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('perNombreExtra', $primaria_expediente_seguimiento_escolar->perNombreExtra, array('id' => 'perNombreExtra', 'class' => '','maxlength'=>'255', 'readonly')) !!}
                            {!! Form::label('perNombreExtra', 'Nombre adicional de presente en la entrevista ', array('class' => '')); !!}
                        </div>
                    </div>
                </div>



          </div>

        </div>
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
                alert('Si hay chec')
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
