@extends('layouts.dashboard')

@section('template_title')
    Primaria alumno
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_alumno')}}" class="breadcrumb">Lista de alumnos</a>
    <a href="{{url('primaria_alumno/'.$alumno->id)}}" class="breadcrumb">Ver alumno</a>
@endsection

@section('content')

@php
    $progNombre = $ultimoCurso ? $ultimoCurso->cgt->plan->programa->progNombre : 'No encontró último curso';
    $planClave = $ultimoCurso ? $ultimoCurso->cgt->plan->planClave : 'No encontró último curso';
    $curEstado = $ultimoCurso ? $ultimoCurso->curEstado : 'No encontró último curso';
@endphp
<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">Clave del Alumno #{{$alumno->aluClave}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  <li class="tab"><a href="#personal">Personal</a></li>
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perNombre', $alumno->persona->perNombre, array('readonly' => 'true')) !!}
                        {!! Form::label('perNombre', 'Nombre(s) ', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perApellido1', $alumno->persona->perApellido1, array('readonly' => 'true')) !!}
                        {!! Form::label('perApellido1', 'Primer apellido ', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perApellido2', $alumno->persona->perApellido2, array('readonly' => 'true')) !!}
                        {!! Form::label('perApellido2', 'Segundo apellido', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perCurp', $alumno->persona->perCurp, array('readonly' => 'true')) !!}
                        {!! Form::label('perCurp', 'Curp', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            @foreach($departamentos as $departamento)
                              @php
                                  if($departamento->depNivel == $alumno->aluNivelIngr){
                                    $aluNivelIngr = $departamento->depNombre;
                                  }
                              @endphp
                            @endforeach
                        {!! Form::text('aluNivelIngr', $aluNivelIngr, array('readonly' => 'true')) !!}
                        {!! Form::label('aluNivelIngr', 'Nivel de ingreso', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('aluGradoIngr', $alumno->aluGradoIngr, array('readonly' => 'true')) !!}
                        {!! Form::label('aluGradoIngr', 'Grado Ingreso ', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('progNombre', $progNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('progNombre', 'Programa', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('planClave', $planClave, array('readonly' => 'true')) !!}
                            {!! Form::label('planClave', 'plan', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('curEstado', $curEstado, array('readonly' => 'true')) !!}
                            {!! Form::label('curEstado', 'Estado', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

            </div>

            {{-- PERSONAL BAR--}}
            <div id="personal">

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirCalle', $alumno->persona->perDirCalle, array('readonly' => 'true')) !!}
                        {!! Form::label('perDirCalle', 'Calle ', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirNumExt', $alumno->persona->perDirNumExt, array('readonly' => 'true')) !!}
                        {!! Form::label('perDirNumExt', 'Número exterior ', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirNumInt', $alumno->persona->perDirNumInt, array('readonly' => 'true')) !!}
                        {!! Form::label('perDirNumInt', 'Número interior', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                            <div class="input-field">
                            {!! Form::text('paisId', $alumno->persona->municipio->estado->pais->paisNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('paisId', 'País', array('class' => '')); !!}
                            </div>
                    </div>
                    <div class="col s12 m6 l4">
                            <div class="input-field">
                            {!! Form::text('estado_id', $alumno->persona->municipio->estado->edoNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('estado_id', 'Estado', array('class' => '')); !!}
                            </div>
                    </div>
                    <div class="col s12 m6 l4">
                            <div class="input-field">
                            {!! Form::text('municipio_id', $alumno->persona->municipio->munNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('municipio_id', 'Municipio', array('class' => '')); !!}
                            </div>
                    </div>
                </div>



                {{--  <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('paisId', $preparatoriaProcedencia->municipio->estado->pais->paisNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('paisId', 'Preparatoria país', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('estado_id', $preparatoriaProcedencia->municipio->estado->edoNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('estado_id', 'Preparatoria estado', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('municipio_id', $preparatoriaProcedencia->municipio->munNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('municipio_id', 'Preparatoria municipio', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('preparatoria_id', $preparatoriaProcedencia->prepNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('preparatoria_id', 'Preparatoria procedencia', array('class' => '')); !!}
                        </div>
                    </div>
                </div>  --}}


                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirColonia', $alumno->persona->perDirColonia, array('readonly' => 'true')) !!}
                        {!! Form::label('perDirColonia', 'Colonia ', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perDirCP', $alumno->persona->perDirCP, array('readonly' => 'true')) !!}
                        {!! Form::label('perDirCP', 'Código Postal ', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perSexo', $alumno->persona->perSexo, array('readonly' => 'true')); !!}
                        {!! Form::label('perSexo', 'Sexo ', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('perFechaNac', 'Fecha de nacimiento', array('class' => '')); !!}
                        {!! Form::date('perFechaNac', $alumno->persona->perFechaNac, array('readonly' => 'true')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perTelefono1', $alumno->persona->perTelefono1, array('readonly' => 'true')) !!}
                        {!! Form::label('perTelefono1', 'Teléfono fijo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perTelefono2', $alumno->persona->perTelefono2, array('readonly' => 'true')) !!}
                        {!! Form::label('perTelefono2', 'Teléfono móvil', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perCorreo1', $alumno->persona->perCorreo1, array('readonly' => 'true')) !!}
                        {!! Form::label('perCorreo1', 'Correo', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>

@endsection
