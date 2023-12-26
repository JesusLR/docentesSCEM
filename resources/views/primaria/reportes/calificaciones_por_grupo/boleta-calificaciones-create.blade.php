@extends('layouts.dashboard')

@section('template_title')
    Reporte calificaciones por grupo
@endsection

@section('breadcrumbs')
  <a href="{{url('primaria_reporte/calificaciones_grupo')}}" class="breadcrumb">Inicio</a>
  <a href="" class="breadcrumb">Resumen de calificaciones por grupo</a>
@endsection

@section('content')

  @php
      $ubicacion_id = Auth::user()->primaria_empleado->escuela->departamento->ubicacion->id;
  @endphp

<div class="row">
  <div class="col s12 ">
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)', 'route' => 'primaria_reporte.boleta_calificaciones.imprimir', 'method' => 'POST', 'target' => '_blank']) !!}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">Resumen de calificaciones por grupo</span>
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

            <div class="row" style="display: none;">
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
                      {{--  <option value="">SELECCIONE UNA OPCIÓN</option>  --}}
                  </select>
              </div>
              <div class="col s12 m6 l4">
                <label for="escuela_id">Escuela*</label>
                <select name="escuela_id" id="escuela_id" data-escuela-id="{{old('escuela_id')}}" class="browser-default validate select2" style="width:100%;" required>
                    {{--  <option value="">SELECCIONE UNA OPCIÓN</option>  --}}
                </select>
              </div>
            </div>

            <div class="row">

              <div class="col s12 m6 l4">
                  <label for="programa_id">Programa*</label>
                  <select name="programa_id" id="programa_id" data-programa-id="{{old('programa_id')}}" class="browser-default validate select2" style="width:100%;" required>
                      <option value="">SELECCIONE UNA OPCIÓN</option>
                  </select>
              </div>
              <div class="col s12 m6 l4">
                  <label for="plan_id">Plan</label>
                  <select name="plan_id" id="plan_id" data-programa-id="{{old('plan_id')}}" class="browser-default validate select2" style="width:100%;" required>
                      <option value="">SELECCIONE UNA OPCIÓN</option>
                  </select>
              </div>
              <div class="col s12 m6 l4" style="display: none;">
                <label for="periodo_id">Periodo*</label>
                <select name="periodo_id" id="periodo_id" data-periodo-id="{{old('periodo_id')}}" class="browser-default validate select2" style="width:100%;">
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field col s12 m6 l6">
                  {!! Form::number('gpoGrado', NULL, array('id' => 'gpoGrado', 'class' => 'validate','min'=>'0', "required")) !!}
                  {!! Form::label('gpoGrado', 'Grado o Semestre*', array('class' => '')); !!}
                </div>
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('gpoClave', NULL, array('id' => 'gpoClave', 'class' => 'validate', "required")) !!}
                  {!! Form::label('gpoClave', 'Grupo*', array('class' => '')); !!}
                </div>
              </div>
              <div class="col s12 m6 l4" style="display: none;">
                <label for="conceptos">Estado del curso *</label>
                <select required name="conceptos" id="conceptos" data-conceptos-id="{{old('conceptos')}}" class="browser-default validate select2" style="width:100%;">
                    {{--  <option value="">SELECCIONE UNA OPCIÓN</option>  --}}
                    <option value="R">REGULAR</option>
                    {{--  @foreach ($conceptos as $concepto)
                        <option value="{{$concepto->concClave}}">{{$concepto->concNombre}}</option>
                    @endforeach  --}}
                </select>
              </div>

              <div class="col s12 m6 l4" style="display: none;">
                <label for="tipoReporte">Tipo de vista *</label>
                <select name="tipoReporte" id="tipoReporte" data-tipoReporte-id="{{old('tipoReporte')}}" class="browser-default validate select2" style="width:100%;">
                    {{-- <option value="">SELECCIONE UNA OPCIÓN</option> --}}
                    <option value="porMes">POR MES</option>
                    <option value="porBimestre">POR BIMESTRE</option>
                    <option value="porTrimestre">POR TRIMESTRE</option>

                </select>
              </div>


            </div>

            <div class="row">
              <div id="vistaPorMes" class="col s12 m6 l4">
                <label for="mesEvaluar">Mes a consultar *</label>
                <select required name="mesEvaluar" id="mesEvaluar" data-mesEvaluar-id="{{old('mesEvaluar')}}" class="browser-default validate select2" style="width:100%;">
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                    <option value="Septiembre">SEPTIEMBRE</option>
                    <option value="Octubre">OCTUBRE</option>
                    <option value="Noviembre">NOVIEMBRE</option>
                    <option value="Diciembre">DICIEMBRE</option>
                    <option value="Enero">ENERO</option>
                    <option value="Febrero">FEBRERO</option>
                    <option value="Marzo">MARZO</option>
                    <option value="Abril">ABRIL</option>
                    <option value="Mayo">MAYO</option>
                    <option value="Junio">JUNIO</option>
                  </select>
              </div>

              <div id="vistaPorBimestre" class="col s12 m6 l4" style="display: none;">
                <label for="bimestreEvaluar">Bimestre a consultar *</label>
                <select name="bimestreEvaluar" id="bimestreEvaluar" data-bimestreEvaluar-id="{{old('bimestreEvaluar')}}" class="browser-default validate select2" style="width:100%;">
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                    <option value="BIMESTRE1">BIMESTRE 1</option>
                    <option value="BIMESTRE2">BIMESTRE 2</option>
                    <option value="BIMESTRE3">BIMESTRE 3</option>
                    <option value="BIMESTRE4">BIMESTRE 4</option>
                    <option value="BIMESTRE5">BIMESTRE 5</option>
                  </select>
              </div>

              <div id="vistaPorTrimestre" class="col s12 m6 l4" style="display: none;">
                <label for="trimestreEvaluar">Trimestre a consultar *</label>
                <select name="trimestreEvaluar" id="trimestreEvaluar" data-trimestreEvaluar-id="{{old('trimestreEvaluar')}}" class="browser-default validate select2" style="width:100%;">
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                    <option value="TRIMESTRE1">TRIMESTRE 1</option>
                    <option value="TRIMESTRE2">TRIMESTRE 2</option>
                    <option value="TRIMESTRE3">TRIMESTRE 3</option>
                  </select>
              </div>

              <div class="col s12 m6 l4">
                <label for="tipoCalificacionVista">Tipo de Calificaciones *</label>
                <select required name="tipoCalificacionVista" id="tipoCalificacionVista" data-tipoCalificacionVista-id="{{old('tipoCalificacionVista')}}" class="browser-default validate select2" style="width:100%;">
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                    <option value="matOficiales">MATERIAS OFICIALES SEP</option>
                    <option value="todosGrupos">TODOS LOS GRUPOS MATERIAS</option>
                  </select>
              </div>
            </div>
            {{--  <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field col s12 m6 l6">
                  {!! Form::number('aluClave', NULL, array('id' => 'aluClave', 'class' => 'validate','min'=>'0')) !!}
                  {!! Form::label('aluClave', 'Clave alumno', array('class' => '')); !!}
                </div>
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('aluMatricula', NULL, array('id' => 'aluMatricula', 'class' => 'validate')) !!}
                  {!! Form::label('aluMatricula', 'Matricula alumno', array('class' => '')); !!}
                </div>
              </div>
            </div>  --}}

            {{--  <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('perApellido1', NULL, array('id' => 'perApellido1', 'class' => 'validate','min'=>'0')) !!}
                  {!! Form::label('perApellido1', 'Primer Apellido', array('class' => '')); !!}
                </div>
                <div class="input-field col s12 m6 l6">
                  {!! Form::text('perApellido2', NULL, array('id' => 'perApellido2', 'class' => 'validate','min'=>'0')) !!}
                  {!! Form::label('perApellido2', 'Segundo Apellido', array('class' => '')); !!}
                </div>
              </div>
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::text('perNombre', NULL, array('id' => 'perNombre', 'class' => 'validate','min'=>'0')) !!}
                  {!! Form::label('perNombre', 'Nombre(s)', array('class' => '')); !!}
                </div>
              </div>
            </div>  --}}

          </div>
        </div>
        <div class="card-action">
          {!! Form::button('<i class="material-icons left">picture_as_pdf</i> GENERAR REPORTE', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
        </div>
      </div>
    {!! Form::close() !!}
  </div>
</div>



@endsection

@section('footer_scripts')

@include('primaria.scripts.funcionesAuxiliares')
@include('primaria.reportes.calificaciones_por_grupo.ComboBoxJs')

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
            if(this.value) {
                getPeriodos2(this.value);
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

    });
</script>

@endsection
