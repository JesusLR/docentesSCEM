@extends('layouts.dashboard')

@section('template_title')
    Reportes historial académico del alumno
@endsection

@section('breadcrumbs')
  <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
  <a href="" class="breadcrumb">Historial academico de alumnos</a>
@endsection

@section('content')
@php
use App\Models\User;
@endphp
<div class="row">
  <div class="col s12 ">
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)', 'route' => 'primaria.primaria_historial_alumno.imprimir', 'method' => 'POST', 'target' => '_blank']) !!}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">Historial academico de alumnos</span>
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
              @if(!empty($message))
              <div class="row">
                <div class="col s12 m12" align="center">
                    <div class="chip red darken-1">
                        <span class="white-text">{{ $message }}</span>
                        <i class=" close material-icons right white-text">close</i>
                    </div>
                </div>
              </div>
              @endif


              <div class="col s12 m6 l6">
                {!! Form::label('ubicacion_id', 'Campus', ['class' => '',]); !!}
                <select name="ubicacion_id" id="ubicacion_id" class="browser-default validate select2" required style="width: 100%;" required>
                  @foreach ($ubicaciones as $item)
                    @php
                    $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
                    $selected = '';
                    if($item->id == $ubicacion_id){
                        $selected = 'selected';
                    }
                    @endphp
                    <option value="{{$item->id}}" {{$selected}}>{{$item->ubiClave}} - {{$item->ubiNombre}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col s12 m6 l6">
                {!! Form::label('firmante', 'Firmante', ['class' => '']); !!}
                <select name="firmante" id="firmante" class="browser-default validate select2" required style="width: 100%;" required>
                    <option value="">Seleccionar Firmante</option>
                </select>
              </div>



            </div>

            <div class="row">
              <div class="col s12 m6 l4">
                {!! Form::label('tipoDato', 'Buscar por:', ['class' => '',]); !!}
                <select name="tipoDato" id="tipoDato" class="browser-default validate select2" style="width: 100%;" required>
                  <option value="1" selected>Clave del alumno</option>
                  <option value="2">Matrícula del alumno</option>
                </select>
              </div>
              <div class="col s10 m4 l3" id="aluClaveDiv">
                <div class="input-field">
                  {!! Form::text('aluClave', NULL, array('id' => 'aluClave', 'class' => 'validate','min'=>'0')) !!}
                  {!! Form::label('aluClave', 'Clave del alumno', array('class' => '')); !!}
                </div>
              </div>
              <div class="col s10 m4 l3" id="aluMatriculaDiv">
                <div class="input-field">
                  {!! Form::text('aluMatricula', NULL, array('id' => 'aluMatricula', 'class' => 'validate','min'=>'0')) !!}
                  {!! Form::label('aluMatricula', 'Matrícula del alumno', array('class' => '')); !!}
                </div>
              </div>
              <div class="col s2 m2 l1">
                <br>
                <a id="buscarProgramas" class="btn-floating waves-effect tooltipped blue darken-3" data-tooltip="Buscar programas" float="left"><i class="material-icons left">search</i></a>
              </div>

              <div class="col s12 m6 l4">
                {{-- <a id="buscarProgramas" class="btn-floating waves-effect tooltipped blue darken-3" data-tooltip="Buscar programas" float="left"><i class="material-icons left">search</i></a> --}}
                {{-- {!! Form::label('plan_id', 'Programa', ['class' => '',]); !!} --}}
                <label for="plan_id">Programa</label>
                <select name="plan_id" id="plan_id" class="browser-default validate select2" style="width:85%;" required>
                  <option value="">SELECCIONE UNA OPCIÓN</option>
                  {{-- @foreach ($programas as $item)
                      <option value="{{$item->plan_id}}">{{$item->plan_id}}</option>
                  @endforeach --}}
                </select>
              </div>
          </div>

          </div>
        </div>
        <div class="card-action">
          {!! Form::button('<i class="material-icons left">picture_as_pdf</i> GENERAR REPORTE', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit','id'=>'botonSubmit']) !!}
        </div>
      </div>
    {!! Form::close() !!}
  </div>
</div>


@endsection


@section('footer_scripts')

  {{-- Script de funciones auxiliares  --}}
@include('primaria.scripts.funcionesAuxiliares')

<script type="text/javascript">
  $(document).ready(function(){

    $('#aluClaveDiv').hide();
    $('#aluMatriculaDiv').hide();

    $('#plan_id').prop('disabled',true);
    $('#botonSubmit').prop('disabled',true);

    $('#ubicacion_id').change(function(){
      var ubicacion_id = $(this).val();
      obtenerFirmantes(ubicacion_id);
    });

    obtenerFirmantes($('#ubicacion_id').val());

    $('#tipoDato').change(function(){
      var alumnoTipo = $(this).val();
      seleccionarTipo(alumnoTipo);
    });

    seleccionarTipo($('#tipoDato').val());

    $('#aluClave').change(function(){
      resetSelect('plan_id');
      $('#plan_id').prop('disabled',true);
      $('#botonSubmit').prop('disabled',true);
    });

    $('#aluMatricula').change(function(){
      resetSelect('plan_id');
      $('#plan_id').prop('disabled',true);
      $('#botonSubmit').prop('disabled',true);
    });


    $('#buscarProgramas').click(function(){
      var aluClave = $('#aluClave').val();
      var aluMatricula = $('#aluMatricula').val();

      if(aluClave) {
        buscar_programas(`/primaria_historial_alumno/obtenerProgramasClave/${aluClave}`);
      }else if(aluMatricula) {
        buscar_programas(`/primaria_historial_alumno/obtenerProgramasMatricula/${aluMatricula}`);
      }else {
        resetSelect('plan_id');
        $('#botonSubmit').prop('disabled', true);
        swal({
          title: 'Sin criterio de búsqueda',
          text: 'Por favor escriba la clave o matrícula del alumno, para realizar la búsqueda'
        });
      }

    });


  });




  /**
  * Auxiliares para esta vista únicamente.
  * ----------------------------------------
  */

  function buscar_programas(url_buscar) {
    $.ajax({
      type:'GET',
      url: base_url + url_buscar,
      dataType:'json',
      data: {ubicacion_id: $('#ubicacion_id').val()},
      success: function(planes) {
        if($.isEmptyObject(planes) || !planes) {
          resetSelect('plan_id');
          $('#botonSubmit').prop('disabled', true);
          swal('Sin coincidencias', 'No se encontró alumno con la clave o matrícula proporcionada. Favor de verificar.');
        } else {
          $('#botonSubmit').prop('disabled', false);
          $('#plan_id').removeAttr('disabled');
          $.each(planes, function(key, value) {
            $('#plan_id').append(new Option(`${value.depClave} ${value.progClave} - ${value.planClave}`, value.plan_id));
          });
        }
      },
      error: function(jqXhr, textStatus, errorMessage) {
        console.log(errorMessage);
      }
    });
  } // buscar_programas.

  function obtenerFirmantes(ubicacion_id){
      resetSelect('firmante');
    $.get(base_url+`/obtenerFirmantes/${ubicacion_id}`,function(data){
      $.each(data,function(key,value){
        $('#firmante').append(new Option(value.firNombre, value.id));
      });
    });
  } // obtenerFirmantes.

  function seleccionarTipo(alumnoTipo){
    if(alumnoTipo == 1){
      $('#aluClaveDiv').show();
      $('#aluMatricula').val('');
      $('#aluMatriculaDiv').hide();
    }
    if(alumnoTipo == 2){
      $('#aluMatriculaDiv').show();
      $('#aluClave').val('');
      $('#aluClaveDiv').hide();
    }
  } // seleccionarTipo.


</script>

@endsection
