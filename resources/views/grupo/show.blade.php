@extends('layouts.dashboard')

@section('template_title')
    Grupo
@endsection

@section('breadcrumbs')
    <a href="{{url('/')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('grupo')}}" class="breadcrumb">Lista de Grupo</a>
    <a href="{{url('grupo/'.$grupo->id)}}" class="breadcrumb">Ver grupo</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">GRUPO #{{$grupo->id}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  <li class="tab"><a href="#equivalente">Equivalente</a></li>
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ubiClave', $grupo->plan->programa->escuela->departamento->ubicacion->ubiNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('ubiClave', 'Campus', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('departamento_id', $grupo->plan->programa->escuela->departamento->depNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('escuela_id', $grupo->plan->programa->escuela->escNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('escuela_id', 'Escuela', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('periodo_id', $grupo->periodo->perNumero.'-'.$grupo->periodo->perAnio, array('readonly' => 'true')) !!}
                            {!! Form::label('periodo_id', 'Periodo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', $grupo->periodo->perFechaInicial, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', $grupo->periodo->perFechaFinal, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('programa_id', $grupo->plan->programa->progNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('programa_id', 'Programa', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('plan_id', $grupo->plan->planClave, array('readonly' => 'true')) !!}
                            {!! Form::label('plan_id', 'Plan', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('gpoSemestre', $grupo->gpoSemestre, array('readonly' => 'true')) !!}
                            {!! Form::label('gpoSemestre', 'Semestre', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoClave', $grupo->gpoClave, array('readonly' => 'true')) !!}
                        {!! Form::label('gpoClave', 'Clave grupo', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('gpoTurno', $grupo->gpoTurno, array('readonly' => 'true')) !!}
                            {!! Form::label('gpoTurno', 'Turno', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col s12 ">
                        <div class="input-field">
                            {!! Form::text('materia_id', $grupo->materia->matClave.'-'.$grupo->materia->matNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('materia_id', 'Materia', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                @if($grupo->optativa)
                    <div id="seccion_optativa" class="row">
                        <div class="col s12 ">
                            <div class="input-field">
                                {!! Form::text('optativa_id', $grupo->optativa->materia->matNombre.'-'.$grupo->optativa->optNombre, array('readonly' => 'true')) !!}
                                {!! Form::label('optativa_id', 'Optativa', array('class' => '')); !!}
                            </div>
                        </div>
                    </div>
                @endif
                <br>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('gpoCupo', $grupo->gpoCupo, array('readonly' => 'true')) !!}
                        {!! Form::label('gpoCupo', 'Cupo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('gpoFechaExamenOrdinario', 'Fecha examen ordinario', array('class' => '')); !!}
                        {!! Form::date('gpoFechaExamenOrdinario', $grupo->gpoFechaExamenOrdinario, array('readonly' => 'true')) !!}
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('gpoHoraExamenOrdinario', 'Hora examen ordinario', array('class' => '')); !!}
                        {!! Form::time('gpoHoraExamenOrdinario', $grupo->gpoHoraExamenOrdinario, array('readonly' => 'true')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6">
                        <div class="input-field">
                            {!! Form::text('empleado_id', $grupo->empleado->persona->perNombre.' '.$grupo->empleado->persona->perApellido1.' '.$grupo->empleado->persona->perApellido2, array('readonly' => 'true')) !!}
                            {!! Form::label('empleado_id', 'Maestro', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6">
                        <div class="input-field">
                            {!! Form::text('empleado_sinodal_id', ($sinodal) ? $sinodal->persona->perNombre.' '.$sinodal->persona->perApellido1.' '.$sinodal->persona->perApellido2 : NULL, array('readonly' => 'true')) !!}
                            {!! Form::label('empleado_sinodal_id', 'Sinodal', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroFolio', $grupo->gpoNumeroFolio, array('readonly' => 'true')) !!}
                        {!! Form::label('gpoNumeroFolio', 'Folio', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroActa', $grupo->gpoNumeroActa, array('readonly' => 'true')) !!}
                        {!! Form::label('gpoNumeroActa', 'Acta', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroLibro', $grupo->gpoNumeroLibro, array('readonly' => 'true')) !!}
                        {!! Form::label('gpoNumeroLibro', 'Libro', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

            </div>

            {{-- EQUIVALENTE BAR--}}
            <div id="equivalente">
                <div class="row">
                    <div class="col s12 m6">
                        <div class="input-field">
                        {!! Form::text('programa_equivalente', ($grupo_equivalente) ? $grupo_equivalente->plan->programa->progNombre : NULL, array('readonly' => 'true')) !!}
                        {!! Form::label('programa_equivalente', 'Programa', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6">
                        <div class="input-field">
                            {!! Form::text('materia_equivalente',($grupo_equivalente) ? $grupo_equivalente->materia->matClave.'-'.$grupo->materia->matNombre : NULL, array('readonly' => 'true')) !!}
                            {!! Form::label('materia_equivalente', 'Materia', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('plan_equivalente', ($grupo_equivalente) ? $grupo_equivalente->plan->planClave : NULL, array('readonly' => 'true')) !!}
                        {!! Form::label('plan_equivalente', 'Plan', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('cgt_equivalente', ($grupo_equivalente) ? $grupo_equivalente->gpoSemestre.'-'.$grupo_equivalente->gpoClave.'-'.$grupo_equivalente->gpoTurno : NULL, array('readonly' => 'true')) !!}
                        {!! Form::label('cgt_equivalente', 'Grado-Grupo-Turno', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>

@endsection
