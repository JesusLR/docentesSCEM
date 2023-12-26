@extends('layouts.dashboard')

@section('template_title')
    Bachiller grupo
@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_seq')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('bachiller.bachiller_grupo_seq.index')}}" class="breadcrumb">Lista de Grupo</a>
    <a href="{{url('bachiller_grupo_seq/'.$bachiller_grupo->id)}}" class="breadcrumb">Ver grupo</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">GRUPO #{{$bachiller_grupo->id}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  {{-- <li class="tab"><a href="#equivalente">Equivalente</a></li> --}}
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ubiClave', $bachiller_grupo->plan->programa->escuela->departamento->ubicacion->ubiNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('ubiClave', 'Campus', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('departamento_id', $bachiller_grupo->plan->programa->escuela->departamento->depNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('escuela_id', $bachiller_grupo->plan->programa->escuela->escNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('escuela_id', 'Escuela', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('periodo_id', $bachiller_grupo->periodo->perNumero.'-'.$bachiller_grupo->periodo->perAnio, array('readonly' => 'true')) !!}
                            {!! Form::label('periodo_id', 'Periodo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', $bachiller_grupo->periodo->perFechaInicial, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', $bachiller_grupo->periodo->perFechaFinal, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('programa_id', $bachiller_grupo->plan->programa->progNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('programa_id', 'Programa', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('plan_id', $bachiller_grupo->plan->planClave, array('readonly' => 'true')) !!}
                            {!! Form::label('plan_id', 'Plan', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('gpoGrado', $bachiller_grupo->gpoGrado, array('readonly' => 'true')) !!}
                            {!! Form::label('gpoGrado', 'Grado', array('class' => '')); !!}
                        </div>
                    </div>
                    
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('gpoTurno', $bachiller_grupo->gpoTurno, array('readonly' => 'true')) !!}
                            {!! Form::label('gpoTurno', 'Turno', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col s12 l6">
                        <div class="input-field">
                            {!! Form::text('materia_id', $bachiller_grupo->bachiller_materia->matClave.'-'.$bachiller_grupo->bachiller_materia->matNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('materia_id', 'Materia', array('class' => '')); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l3">
                        <div class="input-field">
                        {!! Form::text('gpoClave', $bachiller_grupo->gpoClave, array('readonly' => 'true')) !!}
                        {!! Form::label('gpoClave', 'Clave grupo', ['class' => '']); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l3" style="margin-top:30px;">
                        <div style="position:relative;">
                            <input type="checkbox" {{ (! empty(old('gpoACD')) ? 'checked' : '') }} name="gpoACD" id="gpoACD" value="">
                            <label for=""> ¿Es un grupo ACD?</label>
                        </div>
                    </div>
                </div>

                <div class="row">                    
                    <div class="col s12 l6">
                        {!! Form::label('gpoMatComplementaria', 'Materia complementaria *', array('class' => '')); !!}
                        {!! Form::text('gpoMatComplementaria', $bachiller_grupo->gpoMatComplementaria, array('readonly' => 'true')) !!}
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('gpoCupo', $bachiller_grupo->gpoCupo, array('readonly' => 'true')) !!}
                        {!! Form::label('gpoCupo', 'Cupo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empleado_id', $bachiller_grupo->bachiller_empleado->empNombre.' '.$bachiller_grupo->bachiller_empleado->empApellido1.' '.$bachiller_grupo->bachiller_empleado->empApellido2, array('readonly' => 'true')) !!}
                            {!! Form::label('empleado_id', 'Docente titular', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('empleado_sinodal_id', ($docente_auxiliar) ? $docente_auxiliar->empNombre.' '.$docente_auxiliar->empApellido1.' '.$docente_auxiliar->empApellido2 : NULL, array('readonly' => 'true')) !!}
                            {!! Form::label('empleado_sinodal_id', 'Docente auxiliar', array('class' => '')); !!}
                        </div>
                    </div>
                </div>


            </div>


          </div>
        </div>
    </div>
  </div>

  {{--  <script>
    if("{{$bachiller_grupo->gpoACD}}" == 1){
        $("#gpoACD").val("1");
        $("#gpoACD").prop("checked", true);
        $("#gpoMatComplementaria").prop('disabled', false);      
              
    }else{
        $("#gpoACD").val("0");
        $("#gpoMatComplementaria").empty();
        $("#gpoMatComplementaria").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
    }
</script>  --}}
@endsection
