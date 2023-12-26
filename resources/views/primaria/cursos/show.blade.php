@extends('layouts.dashboard')

@section('template_title')
    Primaria curso
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria_curso.index')}}" class="breadcrumb">Lista de preinscritos</a>
    <a href="{{url('primaria_curso/'.$curso->id)}}" class="breadcrumb">Ver curso</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">PREINSCRIPCIÓN #{{$curso->id}} - CLAVE DE ALUMNO(A): {{$curso->alumno->aluClave}}</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  <li class="tab"><a href="#cuotas">Cuotas</a></li>
                  <li class="tab"><a href="#becas">Becas</a></li>
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('ubiClave', $curso->cgt->plan->programa->escuela->departamento->ubicacion->ubiNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('ubiClave', 'Campus', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('departamento_id', $curso->cgt->plan->programa->escuela->departamento->depNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('escuela_id', $curso->cgt->plan->programa->escuela->escNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('escuela_id', 'Escuela', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('periodo_id', $curso->cgt->periodo->perNumero.'-'.$curso->cgt->periodo->perAnio, array('readonly' => 'true')) !!}
                            {!! Form::label('periodo_id', 'Periodo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', $curso->cgt->periodo->perFechaInicial, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', $curso->cgt->periodo->perFechaFinal, array('readonly' => 'true')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('programa_id', $curso->cgt->plan->programa->progNombre, array('readonly' => 'true')) !!}
                            {!! Form::label('programa_id', 'Programa', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('plan_id', $curso->cgt->plan->planClave, array('readonly' => 'true')) !!}
                            {!! Form::label('plan_id', 'Plan', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('cgt_id', $curso->cgt->cgtGradoSemestre.'-'.$curso->cgt->cgtGrupo.'-'.$curso->cgt->cgtTurno, array('readonly' => 'true')) !!}
                            {!! Form::label('cgt_id', 'CGT', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m8">
                        <div class="input-field">
                            {!! Form::text('alumno_id', $curso->alumno->persona->perNombre.' '.$curso->alumno->persona->perApellido1.' '.$curso->alumno->persona->perApellido2, array('readonly' => 'true')) !!}
                            {!! Form::label('alumno_id', 'Alumno', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m4">
                        <a href="#modalAlumnoDetalle-primaria" data-alumno-id="{{ $curso->alumno_id  }}" class="btn modal-trigger btn-modal-alumno-detalle-primaria button button--icon js-button js-ripple-effect ">Ver alumno</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('curEstado', 'Estado del curso', ['class' => '']); !!}
                        <select name="curEstado" id="curEstado" required class="browser-default validate select2" style="width: 100%;" disabled>
                            @foreach($estadoCurso as $key => $value)
                                <option value="{{$key}}" @if($curso->curEstado == $key) {{ 'selected' }} @else {{'disabled'}} @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('curTipoIngreso', 'Tipo de ingreso', ['class' => '']); !!}
                        <select name="curTipoIngreso" id="curTipoIngreso" required class="browser-default validate select2" style="width: 100%;" disabled>
                            @foreach($tiposIngreso as $key => $value)
                                <option value="{{$key}}" @if($curso->curTipoIngreso == $key) {{ 'selected' }} @else {{'disabled'}} @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4" style="visibility: hidden;">
                        {!! Form::label('curOpcionTitulo', 'Opción de titulo', ['class' => '']); !!}
                        <select name="curOpcionTitulo" id="curOpcionTitulo" required class="browser-default validate select2" style="width: 100%;">
                            @foreach($opcionTitulo as $key => $value)
                                <option value="{{$key}}" @if($curso->curOpcionTitulo == $key) {{ 'selected' }} @else {{'disabled'}} @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('curPrimariaFoto', 'Foto del Curso', ['class' => '']); !!}
                        <div class="input-field">
                            @if ($curso->curPrimariaFoto && !strpos($curso->curPrimariaFoto, '.pdf'))
                                <img style="width:200px;" src="{{url('/primaria_curso_images/' . $curso->curPrimariaFoto . '/' . $periodo->perAnioPago) }}" alt="">
                            @endif

                            @if($curso->curPrimariaFoto && strpos($curso->curPrimariaFoto, '.pdf'))
                                <label>Imagen</label>
                                <embed src="/primaria_curso_images/{{$curso->curPrimariaFoto}}/{{$periodo->perAnioPago}}"
                                    type="application/pdf"
                                    width="100%"
                                    height="600px" />
                            @endif
                        </div>
                    </div>
                    <div class="col s12 m6 l4" style="visibility: hidden;">
                        <div class="input-field">
                            {!! Form::number('curExani', $curso->curExani, ['id' => 'curExani', 'class' => 'validate','readonly', 'disable' => false,'min' => '900', 'max' => '1300']) !!}
                            {!! Form::label('curExani', 'Resultado Calificación Exani', ['class' => '']); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('usuario_at', $usuario_at->empleado->persona->perNombre.' '.$usuario_at->empleado->persona->perApellido1.' '.$usuario_at->empleado->persona->perApellido2, array('readonly' => 'true')) !!}
                        {!! Form::label('usuario_at', 'Usuario creador', ['class' => '']); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('usuario_at', \Carbon\Carbon::parse($lafechabuena)->format('d/m/Y'), array('readonly' => 'true')) !!}
                        <label for="usuario_at">{{$quemostrar}}</label>
                        </div>
                    </div>
                </div>

            </div>

            {{-- CUOTAS BAR--}}
            <div id="cuotas">
                <div class="row">
                    <div class="col s4">
                        <div class="input-field">
                        {!! Form::text('curAnioCuotas', $curso->curAnioCuotas, array('readonly' => 'true')) !!}
                        {!! Form::label('curAnioCuotas', 'Año cuota', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('curImporteInscripcion', $curso->curImporteInscripcion, array('readonly' => 'true')) !!}
                        {!! Form::label('curImporteInscripcion', 'Importe inscripción', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('curImporteMensualidad', $curso->curImporteMensualidad, array('readonly' => 'true')) !!}
                        {!! Form::label('curImporteMensualidad', 'Importe mensual', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('curImporteVencimiento', $curso->curImporteVencimiento, array('readonly' => 'true')) !!}
                            {!! Form::label('curImporteVencimiento', 'Importe vencido', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('curImporteDescuento', $curso->curImporteDescuento, array('readonly' => 'true')) !!}
                        {!! Form::label('curImporteDescuento', 'Descuento pronto pago', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('curDiasProntoPago', $curso->curDiasProntoPago, array('readonly' => 'true')) !!}
                        {!! Form::label('curDiasProntoPago', 'Días pronto pago', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('curPlanPago', 'Plan de pago', ['class' => '']); !!}
                        <select name="curPlanPago" id="curPlanPago" required class="browser-default validate select2" style="width: 100%;">
                            @foreach($planesPago as $key => $value)
                                <option value="{{$key}}" @if($curso->curPlanPago == $key) {{ 'selected' }} @else {{'disabled'}} @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{-- BECAS BAR--}}
            <div id="becas">
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('curTipoBeca', 'Tipo de beca', ['class' => '']); !!}
                        <select name="curTipoBeca" id="curTipoBeca" required class="browser-default validate select2" style="width: 100%;">
                            @foreach($tiposBeca as $value)
                                <option value="{{$value->bcaClave}}" @if($curso->curTipoBeca == $value->bcaClave) {{ 'selected' }} @else {{'disabled'}} @endif>
                                    {{$value->bcaNombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('curPorcentajeBeca', $curso->curPorcentajeBeca, array('readonly' => 'true')) !!}
                        {!! Form::label('curPorcentajeBeca', '% Beca', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m8">
                        <div class="input-field">
                        {!! Form::textarea('curObservacionesBeca', $curso->curObservacionesBeca, ['readonly' => 'true']) !!}
                        {!! Form::label('curObservacionesBeca', 'Observaciones', ['class' => '']); !!}
                        </div>
                    </div>
                </div>


            </div>
          </div>
        </div>
    </div>
  </div>
  @include('primaria.cursos.modales.modalAlumnoDetalle-primaria')

@endsection
