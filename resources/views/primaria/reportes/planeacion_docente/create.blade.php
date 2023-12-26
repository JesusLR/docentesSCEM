@extends('layouts.dashboard')

@section('template_title')
    Reporte planeación
@endsection

@section('breadcrumbs')
  <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
  <a href="" class="breadcrumb">Planeación docente</a>
@endsection

@section('content')

  @php
      $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
  @endphp

<div class="row">
  <div class="col s12 ">
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)', 'route' => 'primaria.reporte.planeacion_docente.imprimir', 'method' => 'POST', 'target' => '_blank']) !!}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">PLANEACIÓN DOCENTE</span>
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
              <div class="col s12 m6 l4">
                <label for="periodo_id">Periodo*</label>
                <select name="periodo_id" id="periodo_id" data-periodo-id="{{old('periodo_id')}}" class="browser-default validate select2" style="width:100%;" required>
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div id="vistaPorMes" class="col s12 m6 l4">
                <label for="mesAConsultar">Mes a consultar *</label>
                <select required name="mesAConsultar" id="mesAConsultar" class="browser-default validate select2" style="width:100%;">
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
             <div class="col s12 m6 l4">
                <div class="col s12 m6 l6">
                  {!! Form::label('gpoGrado', 'Grado *', array('class' => '')); !!}
                  <select id="gpoGrado" class="browser-default validate select2" required name="gpoGrado" style="width: 100%;">
                    <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                    @for ($i = 0; $i < 7; $i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="input-field col s12 m6 l6" style="display: none;">
                  {!! Form::text('gpoClave', NULL, array('id' => 'gpoClave', 'class' => 'validate')) !!}
                  {!! Form::label('gpoClave', 'Grupo ', array('class' => '')); !!}
                </div>
              </div>

              <div class="col s12 m6 l4" style="display: none;">
                {!! Form::label('primaria_grupo_id', 'Grupo materia', array('class' => '')); !!}
                <select id="primaria_grupo_id" class="browser-default validate select2" name="primaria_grupo_id"
                    style="width: 100%;">
                    {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                </select>
              </div>
            </div>

            <div class="row" style="display: none;">
              <div class="col s12 m6 l4">
                {!! Form::label('empleado_id', 'Docente', array('class' => '')); !!}
                <select id="empleado_id" class="browser-default validate select2" name="empleado_id"
                    style="width: 100%;">
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                    @foreach ($primaria_empleado as $empleado)
                        <option value="{{$empleado->id}}">{{$empleado->empApellido1.' '.$empleado->empApellido2 .' '.$empleado->empNombre}}</option>
                    @endforeach
                </select>
              </div>
            </div>






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
@include('primaria.reportes.planeacion_docente.grupos')


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

    });
</script>

@endsection
