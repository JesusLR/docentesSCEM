@extends('layouts.dashboard')

@section('template_title')
    Reportes lista de asistencia
@endsection

@section('breadcrumbs')
  <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
  <a href="" class="breadcrumb">Lista de asistencia</a>
@endsection

@section('content')

  @php
      $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
  @endphp

<div class="row">
  <div class="col s12 ">
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)', 'route' => 'primaria_reporte.lista_de_asistencia.imprimir', 'method' => 'POST', 'target' => '_blank']) !!}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">Lista de asistencia</span>
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
                  <select name="ubicacion_id" id="ubicacion_id" data-ubicacion-id="{{old('ubicacion_id')}}" class="browser-default validate select2" disabled style="width:100%;" required>
                      <option value="">SELECCIONE UNA OPCIÓN</option>
                      @foreach($ubicaciones as $ubicacion)
                          <option value="{{$ubicacion->id}}">{{$ubicacion->ubiClave}} - {{$ubicacion->ubiNombre}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col s12 m6 l4">
                  <label for="departamento_id">Departamento*</label>
                  <select name="departamento_id" id="departamento_id" disabled data-departamento-id="{{old('departamento_id')}}" class="browser-default validate select2" style="width:100%; pointer-events: none" required>
                      {{--  <option value="">SELECCIONE UNA OPCIÓN</option>  --}}
                  </select>
              </div>

              <div class="col s12 m6 l4">
                <label for="escuela_id">Escuela*</label>
                <select name="escuela_id" id="escuela_id" disabled data-escuela-id="{{old('escuela_id')}}" class="browser-default validate select2" style="width:100%;" required>
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
                  <label for="plan_id">Plan *</label>
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
                <div class="col s12 m6 l4">
                    <div class="input-field">
                        {!! Form::number('gpoGrado', NULL, array('id' => 'gpoGrado', 'class' => 'validate','required', 'min' => '0', 'max'=>'6')) !!}
                        {!! Form::label('gpoGrado', 'Grado *', array('class' => '')); !!}
                    </div>
                </div>

                <div class="col s12 m6 l4">
                    <div class="input-field">
                        {!! Form::text('gpoGrupo', NULL, array('id' => 'gpoGrupo', 'class' => 'validate','required','maxlength'=>'5')) !!}
                        {!! Form::label('gpoGrupo', 'Grupo *', array('class' => '')); !!}
                    </div>
                </div>

                <div class="col s12 m6 l4">
                  <label for="tipoVista">Tipo de vista *</label>
                  <select name="tipoVista" id="tipoVista" data-periodo-id="{{old('tipoVista')}}" class="browser-default validate select2" style="width:100%;" required>
                      <option value="listaVacia">LISTA VACÍA</option>
                      <option value="listaLlena">LISTA CON FECHAS</option>
                  </select>
                </div>
            </div>

            <div class="row" style="display: none;" id="fechasBusqueda">
              <div class="col s12 m6 l4">
                {!! Form::label('fechaInicio', 'Fecha de inicio *', array('class' => '')); !!}
                {!! Form::date('fechaInicio', NULL, array('id' => 'fechaInicio', 'class' => 'validate')) !!}
              </div>

              <div class="col s12 m6 l4">
                {!! Form::label('fechaFin', 'Fecha final *', array('class' => '')); !!}
                {!! Form::date('fechaFin', NULL, array('id' => 'fechaFin', 'class' => 'validate')) !!}
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
@include('primaria.reportes.calificacion_por_materia.funcionJs')

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

<script>
  $("#tipoVista").change(function(){
    if($('select[id=tipoVista]').val() == "listaLlena"){
      $("#fechasBusqueda").show();
      $('#fechaInicio').attr('required', 'required');
      $('#fechaFin').attr('required', 'required');

    }else{
      $("#fechasBusqueda").hide();
      $('#fechaInicio').removeAttr('required');
      $('#fechaFin').removeAttr('required');
    }
});
</script>

@endsection
