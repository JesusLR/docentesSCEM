@extends('layouts.dashboard')

@section('template_title')
  Reportes
@endsection

@section('breadcrumbs')
  <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
  <a href="" class="breadcrumb">Relación de alumnos becados</a>
@endsection

@section('content')

  @php
      $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
  @endphp

<div class="row">
  <div class="col s12 ">
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_reporte.primaria_alumnos_becados.imprimir', 'method' => 'POST', 'target' => '_blank']) !!}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">Relación de alumnos becados</span>
          {{-- NAVIGATION BAR--}}
          <nav class="nav-extended">
            <div class="nav-content">
              <ul class="tabs tabs-transparent">
                <li class="tab"><a class="active" href="#filtros">Filtros de búsqueda</a></li>
              </ul>
            </div>
          </nav>

          {{-- GENERAL BAR--}}
          <div id="filtros">

            <div class="row">
              <div class="col s12 m6 l4" style="margin-top:10px;">
                {!! Form::label('validar_hermanos', '¿Imprimir para validar hermanos?', ['class' => '']); !!}
                <select name="validar_hermanos" id="validar_hermanos" class="browser-default validate select2" style="width: 100%;">
                  <option value="">NO</option>
                  <option value="SI">SI</option>
                </select>
              </div>


              <div class="col s12 m6 l4" style="margin-top:10px;">
                {!! Form::label('tipoReporte', 'Tipo de reporte', ['class' => '']); !!}
                <select name="tipoReporte" id="tipoReporte" class="browser-default validate select2" style="width: 100%;">
                  <option value="N">Normal</option>
                  <option value="F">Solo firmas</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m12 l12">
                <hr />
              </div>
            </div>

            <div class="row">
                <div class="col s12 m6 l4">
                    <label for="ubicacion_id">Ubicación*</label>
                    <select name="ubicacion_id" id="ubicacion_id" data-ubicacion-id="{{old('ubicacion_id')}}" class="browser-default validate select2" style="width:100%;" required>
                        <option value="">SELECCIONE UNA OPCIÓN</option>
                        @foreach($ubicaciones as $ubicacion)
                            <option value="{{$ubicacion->id}}">{{$ubicacion->ubiClave}} - {{$ubicacion->ubiNombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col s12 m6 l4">
                    <label for="departamento_id">Departamento*</label>
                    <select name="departamento_id" id="departamento_id" data-departamento-id="{{old('departamento_id')}}" class="browser-default validate select2" style="width:100%;" required>
                    </select>
                </div>
                <div class="col s12 m6 l4">
                  <div class="col s12 m6 l6">
                    <label for="perAnioPago">Año Inicio Curso*</label>
                    <select name="perAnioPago" id="perAnioPago" data-peraniopago="{{old('perAnioPago')}}" class="browser-default validate select2" style="width:100%;" required>
                      <option value="">SELECCIONE UNA OPCIÓN</option>
                      @for($i = $anioActual; $i > 1996; $i--)
                        <option value="{{$i}}">{{$i}}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col s12 m6 l6">
                    <label for="promedio_de_curso">Mostrar promedios de:</label>
                    <select name="promedio_de_curso" id="promedio_de_curso" data-promedio-curso="{{old('promedio_de_curso')}}" class="browser-default validate select2" style="width:100%;" required>
                      <option value="SI">Este curso</option>
                      <option value="">Curso anterior</option>
                    </select>
                  </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m6 l4">
                    <label for="escuela_id">Escuela</label>
                    <select name="escuela_id" id="escuela_id" data-escuela-id="{{old('escuela_id')}}" class="browser-default validate select2" style="width:100%;">
                    </select>
                </div>
                <div class="col s12 m6 l4">
                    <label for="programa_id">Programa</label>
                    <select name="programa_id" id="programa_id" data-programa-id="{{old('programa_id')}}" class="browser-default validate select2" style="width:100%;">
                        <option value="">SELECCIONE UNA OPCIÓN</option>
                    </select>
                </div>
                <div class="col s12 m6 l4">
                  <div class="input-field col s12 m6 l6">
                    {!! Form::number('cgtGradoSemestre', NULL, array('id' => 'cgtGradoSemestre', 'class' => 'validate','min'=>'0')) !!}
                    {!! Form::label('cgtGradoSemestre', 'Grado', array('class' => '')); !!}
                  </div>
                  <div class="input-field col s12 m6 l6">
                    {!! Form::text('cgtGrupo', NULL, array('id' => 'cgtGrupo', 'class' => 'validate')) !!}
                    {!! Form::label('cgtGrupo', 'Grupo', array('class' => '')); !!}
                  </div>
                </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4" style="margin-top:10px;">
                {!! Form::label('curTipoBeca', 'Tipo beca', ['class' => '']); !!}
                <select name="curTipoBeca" id="curTipoBeca" class="browser-default validate select2" style="width: 100%;">
                  <option value="">Seleccionar</option>
                  @foreach ($tiposBeca as $beca)
                    <option value="{{$beca->bcaClave}}">{{$beca->bcaNombre}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::number('curPorcentajeBeca', NULL, array('id' => 'curPorcentajeBeca', 'class' => 'validate','min'=>'0')) !!}
                  {!! Form::label('curPorcentajeBeca', 'Porcentaje beca', array('class' => '')); !!}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4" style="margin-top:10px;">
                <div class="col s12 m6 l6">
                  {!! Form::label('curEstado', 'Curso estado *', ['class' => '']); !!}
                  <select name="curEstado" id="curEstado" class="browser-default validate select2 required" style="width: 100%;">
                    <option value="T">TODOS MENOS BAJAS</option>
                    <option value="">TODOS</option>
                    <option value="RCA">INSCRITOS</option>
                    @foreach ($estadosCurso as $key => $estadoCurso)
                      <option value="{{$key}}">{{$estadoCurso}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="input-field col s12 m6 l6">
                  {!! Form::label('curFechaRegistro', 'Fecha registro', ['class' => '', 'style' => 'margin-top: -16px;']); !!}
                  <input id="curFechaRegistro" name="curFechaRegistro" class="validate" type="date" value="">
                </div>
              </div>
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::text('curObservacionesBeca', NULL, array('id' => 'curObservacionesBeca', 'class' => 'validate')) !!}
                  {!! Form::label('curObservacionesBeca', 'Observaciones', array('class' => '')); !!}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('aluClave', NULL, array('id' => 'aluClave', 'class' => 'validate', "")) !!}
                  {!! Form::label('aluClave', 'Clave de pago', array('class' => '')); !!}
                </div>
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('aluMatricula', NULL, array('id' => 'aluMatricula', 'class' => 'validate')) !!}
                  {!! Form::label('aluMatricula', 'Matrícula', array('class' => '')); !!}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('perApellido1', NULL, array('id' => 'perApellido1', 'class' => 'validate')) !!}
                  {!! Form::label('perApellido1', 'Apellido paterno', array('class' => '')); !!}
                </div>
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('perApellido2', NULL, array('id' => 'perApellido2', 'class' => 'validate')) !!}
                  {!! Form::label('perApellido2', 'Apellido materno', array('class' => '')); !!}
                </div>
              </div>
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::text('perNombre', NULL, array('id' => 'perNombre', 'class' => 'validate')) !!}
                  {!! Form::label('perNombre', 'Nombre(s)', array('class' => '')); !!}
                </div>
              </div>
            </div>


          </div>
        </div>
        <div class="card-action">
          {!! Form::button('<i class="material-icons left">picture_as_pdf</i> GENERAR REPORTE', ['class' => 'btn-large waves-effect darken-3', 'type' => 'submit']) !!}
        </div>
      </div>
    {!! Form::close() !!}
  </div>
</div>

  {{-- Script de funciones auxiliares  --}}
  {{--  {!! HTML::script(asset('js/funcionesAuxiliares.js'), array('type' => 'text/javascript')) !!}  --}}

@endsection


@section('footer_scripts')

@include('primaria.scripts.funcionesAuxiliares')

<script type="text/javascript">
    $(document).ready(function() {
        var ubicacion = $('#ubicacion_id');
        var departamento = $('#departamento_id');
        var escuela = $('#escuela_id');
        var programa = $('#programa_id');

        var ubicacion_id = {!! json_encode(old('ubicacion_id')) !!} || {!! json_encode($ubicacion_id) !!};
        if(ubicacion_id) {
            ubicacion.val(ubicacion_id).select2();
            getDepartamentos(ubicacion_id);
        }

        ubicacion.on('change', function() {
            this.value ? getDepartamentos(this.value) : resetSelect('departamento_id');
        });

        departamento.on('change', function() {
          this.value ? getEscuelas(this.value) : resetSelect('escuela_id');
        });

        escuela.on('change', function() {
            this.value ? getProgramas(this.value) : resetSelect('programa_id');
        });

        var validar_hermanos = {!! json_encode(old('validar_hermanos')) !!};
        validar_hermanos && $('#validar_hermanos').val(validar_hermanos).select2();

        var tipoReporte = {!! json_encode(old('tipoReporte')) !!};
        tipoReporte && $('#tipoReporte').val(tipoReporte).select2();

        var curTipoBeca = {!! json_encode(old('curTipoBeca')) !!};
        curTipoBeca && $('#curTipoBeca').val(curTipoBeca).select2();

        var curEstado = {!! json_encode(old('curEstado')) !!};
        curEstado && $('#curEstado').val(curEstado).select2();

    });

</script>

@endsection
