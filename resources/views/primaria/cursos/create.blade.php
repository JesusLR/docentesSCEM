@extends('layouts.dashboard')

@section('template_title')
    Primaria curso
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria_curso.index')}}" class="breadcrumb">Lista de preinscritos</a>
    <a href="{{route('primaria_curso.create')}}" class="breadcrumb">Agregar preinscripción</a>
@endsection

@section('content')
@php
use App\Models\User;
@endphp

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['enctype' => 'multipart/form-data', 'onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_curso.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">PREINSCRIBIR</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  @if (User::permiso("curso") != "P")
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  @endif
                  @if (User::permiso("curso") == "A" || User::permiso("curso") == "E" || User::permiso("curso") == "P")
                  <li class="tab"><a href="#cuotas">Cuotas</a></li>
                  <li class="tab"><a href="#becas">Becas</a></li>
                  @endif
                </ul>
              </div>
            </nav>
            @if (User::permiso("curso") != "P")
            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('curTipoIngreso', 'Tipo de ingreso *', ['class' => '']); !!}
                        <div style="position:relative;">
                            <select name="curTipoIngreso" id="curTipoIngreso" required class="browser-default validate select2" style="width: 100%;">
                                @foreach($tiposIngreso as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <input type="hidden" name="curso_id" id="curso_id" value={{old('curso_id')}}>
                    <div class="col s12 m4">
                        <input type="text" placeholder="Buscar por: Clave alumno" id="aluClave" value="{{old('aluClave')}}"  name="aluClave" style="width: 100%;" />
                    </div>
                    <div class="col s12 m4">
                        <input type="text" placeholder="Buscar por: Nombre(s)" id="nombreAlumno" value="{{old('nombreAlumno')}}" name="nombreAlumno" style="width: 100%;" />
                    </div>
                    <div class="col s12 m4">
                            <button class="btn-large waves-effect darken-3 btn-buscar-alumno" {{isset($candidato) ? "disabled": ""}}>
                                <i class="material-icons left">search</i>
                                Buscar
                            </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m4">
                        {!! Form::label('alumno_id', 'Alumno *', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="alumno_id" class="browser-default validate select2" required name="alumno_id" style="width: 100%;">
                                @if($alumno)
                                    @php
                                        $persona = $alumno->persona;
                                        $nombreCompleto = $persona->perNombre.' '.$persona->perApellido1.' '.$persona->perApellido2;
                                    @endphp
                                    <option value="{{$alumno->id}}" selected>{{$alumno->aluClave}}-{{$nombreCompleto}}</option>
                                @else
                                    <option value="" selected disabled>RESULTADOS DE BUSQUEDA</option>
                                @endif
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('ubicacion_id', 'Ubicación *', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="ubicacion_id" class="browser-default validate select2" required name="ubicacion_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach($ubicaciones as $ubicacion)
                                    @php
                                    $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
                                    $selected = '';
                                    if (!isset($campus)) {
                                        if($ubicacion->id == $ubicacion_id){
                                            $selected = 'selected';
                                        }
                                    }
                                    $selected = (isset($campus) && $campus == $ubicacion->id) ? "selected": "";

                                    @endphp
                                    <option value="{{$ubicacion->id}}" {{$selected}}>{{$ubicacion->ubiNombre}}</option>
                                @endforeach
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="departamento_id" data-departamento-id="{{(isset($departamento)) ? $departamento:""}}" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <div style="position: relative;">
                            <select id="escuela_id" data-escuela-id="{{(isset($escuela)) ? $escuela:""}}" class="browser-default validate select2" required name="escuela_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('periodo_id', 'Periodo *', array('class' => '')); !!}
                        <select id="periodo_id" data-periodo-id="" class="browser-default validate select2" required name="periodo_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="">
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        {!! Form::date('perFechaInicial', NULL, array('id' => 'perFechaInicial', 'class' => 'validate','readonly')) !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="">
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        {!! Form::date('perFechaFinal', NULL, array('id' => 'perFechaFinal', 'class' => 'validate','readonly')) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                        <div style="position:relative">
                            <select id="programa_id" data-programa-id="{{(isset($programa)) ? $programa:""}}" class="browser-default validate select2" required name="programa_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            </select>
                            @if (isset($candidato))
                                <div style="width: 100%; height: 35px; position: absolute; top: 0;"></div>
                            @endif
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                        <select id="plan_id" data-plan-id="" class="browser-default validate select2" required name="plan_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('cgt_id', 'CGT *', array('class' => '')); !!}
                        <select id="cgt_id" data-cgt-id="" class="browser-default validate select2" required name="cgt_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                        </select>
                    </div>
                </div>


               <div class="row" style="visibility: hidden;">
                   <div class="col s12 m6 l4">
                       {!! Form::label('curOpcionTitulo', 'Opción de titulo', ['class' => '', 'disable' => true]); !!}
                       <select name="curOpcionTitulo" id="curOpcionTitulo" class="browser-default validate select2"
                               style="width:100%;">
                           <option value="S">SI</option>
                           <option value="N" selected>NO</option>
                       </select>
                  </div>
               </div>

                <div class="row">
                    <div class="col s12 m6 l8">
                        {{-- si estoy en vista candidato --}}
                        @if (isset($candidato))
                            {{-- si candidato no tiene foto, mostrar input --}}
                            @if (!$candidato->perFoto)
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>Foto del Curso</span>
                                        <input value="" type="file" name="curPrimariaFoto">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate"  type="text">
                                    </div>
                                </div>
                            @endif
                            {{-- si candidato tiene foto, mostrar imagen--}}
                            {{-- @if ($candidato->perFoto && strpos($candidato->perFoto, '.pdf')) --}}
                            @if ($candidato->perFoto)
                                    <input type="hidden" value="true" name="es_candidato_tiene_foto">
                                    @if ($candidato->perFoto && !strpos($candidato->perFoto, '.pdf'))
                                        <img height="200px" src="{{url('/primaria_curso_images/' . $candidato->perFoto) }}" alt="">
                                    @endif
                                    @if($candidato->perFoto && strpos($candidato->perFoto, '.pdf'))
                                        <label>Imagen</label>
                                        <embed src="/primaria_curso_images/{{$candidato->perFoto}}"
                                               type="application/pdf"
                                               width="100%"
                                               height="200px" />
                                    @endif
                            @endif

                            {{-- si no estoy en vista candidato --}}
                        @else
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span>Foto del Curso (.jpg)</span>
                                    <input value="" type="file" name="curPrimariaFoto">
                                </div>
                                    <div class="file-path-wrapper">
                                    <input class="file-path validate"  type="text">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <div style="position:relative;">

                                <input id="curExani" class="validate"  min="900" max="1300" name="curExani" type="number"
                                       value="{{ isset($candidato) ? $candidato->curExani: null }}"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength = "4" style="display: none"/>
                                <label for="curExani" class="" style="display: none">Resultado Calificaci&oacute;n Exani</label>

                                {{--
                                {!! Form::number('curExani', isset($candidato) ? $candidato->curExani: null, array('id' => 'curExani', 'class' => 'validate','', 'min' => '900', 'max' => '1300')) !!}
                                --}}
                                @if (isset($candidato))
                                    <div style="width: 100%; height: 100%; position: absolute; top: 0;"></div>
                                @endif
                            </div>
                            {{--
                            {!! Form::label('curExani', 'Resultado Calificación Exani', ['class' => '']); !!}
                            --}}
                        </div>
                    </div>

                </div>

            </div>
            @endif
            @if (User::permiso("curso") == "A" || User::permiso("curso") == "E" || User::permiso("curso") == "P")
            {{-- CUOTAS BAR--}}
            <div id="cuotas">
                <div class="row">
                    <div class="col s4">
                        <div class="input-field">
                        {!! Form::number('curAnioCuotas', NULL, array('id' => 'curAnioCuotas', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                        {!! Form::label('curAnioCuotas', 'Año cuota', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curImporteInscripcion', NULL, array('id' => 'curImporteInscripcion', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"')) !!}
                        {!! Form::label('curImporteInscripcion', 'Importe inscripción', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curImporteMensualidad', NULL, array('id' => 'curImporteMensualidad', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"')) !!}
                        {!! Form::label('curImporteMensualidad', 'Importe mensual', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('curImporteVencimiento', NULL, array('id' => 'curImporteVencimiento', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"')) !!}
                            {!! Form::label('curImporteVencimiento', 'Importe vencido', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curImporteDescuento', NULL, array('id' => 'curImporteDescuento', 'class' => 'validate','min'=>'0','max'=>'99999999','onKeyPress="if(this.value.length==8) return false;"')) !!}
                        {!! Form::label('curImporteDescuento', 'Descuento pronto pago', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curDiasProntoPago', NULL, array('id' => 'curDiasProntoPago', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                        {!! Form::label('curDiasProntoPago', 'Días pronto pago', ['class' => '']); !!}
                        </div>
                    </div>
                    <!-- <div class="col s12 m6 l4">
                        {!! Form::label('curPlanPago', 'Plan de pago', ['class' => '']); !!}
                        <select name="curPlanPago" id="curPlanPago" class="browser-default validate select2" style="width: 100%;">
                            <option value="">Seleccionar</option>
                            @foreach($planesPago as $key => $value)
                                <option value="{{$key}}" @if(old('curPlanPago') == $key) {{ 'selected' }} @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div> -->
                </div>
            </div>
            {{-- BECAS BAR--}}
            <div id="becas">
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('curTipoBeca', 'Tipo de beca', ['class' => '']); !!}
                        <select name="curTipoBeca" id="curTipoBeca" class="browser-default validate select2" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            @foreach($tiposBeca as $value)
                                <option value="{{$value->bcaClave}}" @if(old('curTipoBeca') == $value->bcaClave) {{ 'selected' }} @endif>
                                    {{$value->bcaNombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('curPorcentajeBeca', NULL, array('id' => 'curPorcentajeBeca', 'class' => 'validate','min'=>'0','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                        {!! Form::label('curPorcentajeBeca', '% Beca', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m8">
                        <div class="input-field">
                        {!! Form::textarea('curObservacionesBeca', NULL, ['id' => 'curObservacionesBeca', 'class' => 'materialize-textarea','rows' => 2, 'cols' => 40,'data-length' => "255"]) !!}
                        {!! Form::label('curObservacionesBeca', 'Observaciones', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif

          </div>
          <div class="card-action">
                {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>

@endsection

@section('footer_scripts')


@include('primaria.scripts.funcionesAuxiliares')

<script type="text/javascript">
    $(document).ready(function() {

      var alumnoIdOld = "{{old("alumno_id")}}";
      if (alumnoIdOld) {
        buscarAlumno()
      }



      $(".btn-buscar-alumno").on("click", function (e) {
        e.preventDefault()

        var aluClave = $("#aluClave").val()
        var nombreAlumno = $("#nombreAlumno").val()
        if (aluClave === "" && nombreAlumno === "") {
            swal({
                title: "Busqueda de alumnos",
                text: "Debes de tener al menos un dato de alumnos capturados",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: '#0277bd',
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                swal.close()
            });

        } else {
            buscarAlumno()
        }

      });

      $('#alumno_id').on('change', function() {
        var alumno_id = $('#alumno_id').val();
        $.ajax({
            type: 'GET',
            url: base_url+'/primaria_alumno/ultimo_curso/'+alumno_id,
            dataType: 'json',
            data: {alumno_id: alumno_id},
            success: function(data) {
                data['curso'] && alumno_precargar_datos(data);
            },
            error: function(Xhr, textMessage, errorMessage){
                console.log(errorMessage);
            }
        });
      });

      var ubicacion_id = $('#ubicacion_id').val();
      var departamento_id = $('#departamento_id').val();
      var escuela_id = $('#escuela_id').val();
      var programa_id = $('#programa_id').val();
      var periodo_id = $('#periodo_id').val();
      var plan_id = $('#plan_id').val();


      ubicacion_id && getDepartamentos(ubicacion_id, 'departamento_id');
      $('#ubicacion_id').on('change', function() {
        ubicacion_id = $(this).val();
        ubicacion_id && getDepartamentos(ubicacion_id, 'departamento_id');
      });

      if(departamento_id) {
        getEscuelas(departamento_id, 'escuela_id');
        getPeriodos(departamento_id);
      }
      $('#departamento_id').on('change', function() {
        departamento_id = $(this).val();
        if(departamento_id) {
            getEscuelas(departamento_id, 'escuela_id');
            getPeriodos(departamento_id);
        }
      });

      escuela_id && getProgramas(escuela_id, 'programa_id');
      $('#escuela_id').on('change', function() {
        escuela_id = $(this).val();
        escuela_id && getProgramas(escuela_id, 'programa_id');
      });

      programa_id && getPlanes(programa_id, 'plan_id');
      $('#programa_id').on('change', function() {
        programa_id = $(this).val();
        programa_id && getPlanes(programa_id, 'plan_id');
      });

      periodo_id && periodo_fechasInicioFin(periodo_id);
      $('#periodo_id').on('change', function() {
        periodo_id = $(this).val();
        periodo_id && periodo_fechasInicioFin(periodo_id);
        (periodo_id && plan_id) && getCgts_plan_periodo(plan_id, periodo_id, 'cgt_id');
      });

      (periodo_id && plan_id) && getCgts_plan_periodo(plan_id, periodo_id, 'cgt_id');
      $('#plan_id').on('change', function() {
        plan_id = $(this).val();
        (periodo_id && plan_id) && getCgts_plan_periodo(plan_id, periodo_id, 'cgt_id');
      });




    }); //document.ready




    function buscarAlumno()
      {
        var nombreAlumno = $("#nombreAlumno").val()
        var aluClave = $("#aluClave").val()

        $.ajax({
            type: "POST",
            url: base_url + `/primaria_alumno/api/getMultipleAlumnosByFilter`,
            data: {
                nombreAlumno: nombreAlumno,
                aluClave: aluClave,
                _token: $("meta[name=csrf-token]").attr("content")
            },
            dataType: "json"
        })
        .done(function(res) {
            console.log("res")
            console.log(res)
            $("#alumno_id").empty()

            if (res.length > 0) {
                res.forEach(element => {
                    $("#alumno_id").append(`<option value=${element.id}>${element.aluClave}-${element.persona.perNombre} ${element.persona.perApellido1} ${element.persona.perApellido2}</option>`);
                });
                $('#alumno_id').trigger('click');
                $('#alumno_id').trigger('change');
            }

        });
      } //buscarAlumno.


      function alumno_precargar_datos(data) {
        data.cgtSiguiente && $('#cgt_id').data('cgt-id', data.cgtSiguiente.id);
        $('#plan_id').data('plan-id', data.plan.id);
        $('#programa_id').data('programa-id', data.programa.id);
        $('#escuela_id').data('escuela-id', data.escuela.id);
        $('#departamento_id').data('departamento-id', data.departamento.id);
        $('#periodo_id').data('periodo-id', data.periodoSiguiente.id);
        $('#ubicacion_id').val(data.ubicacion.id).trigger('change');
        $('#curTipoIngreso').val('RI').select2();
        // if(data.periodo.perNumero == 3) {
            $('#curso_id').val(data.curso.id);
            $('#curAnioCuotas').val(data.curso.curAnioCuotas);
            $('#curImporteInscripcion').val(data.curso.curImporteInscripcion);
            $('#curImporteMensualidad').val(data.curso.curImporteMensualidad);
            $('#curImporteVencimiento').val(data.curso.curImporteVencimiento);
            $('#curImporteDescuento').val(data.curso.curImporteDescuento);
            $('#curDiasProntoPago').val(data.curso.curDiasProntoPago);
            $('#curPorcentajeBeca').val(data.curso.curPorcentajeBeca);
            $('#curObservacionesBeca').val(data.curso.curObservacionesBeca);
            $('#curTipoBeca').val(data.curso.curTipoBeca).select2();
            Materialize.updateTextFields();
        // }
      }//alumno_precargar_datos.




  </script>

  <script type="text/javascript">

    $(document).ready(function() {

        // OBTENER MATERIAS POR SEMESTRE SELECCIONADO
        $("#gpoSemestre").change( event => {
            var plan_id = $("#plan_id").val();
            $("#materia_id").empty();
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_materia/materias/${event.target.value}/${plan_id}`,function(res,sta){
                res.forEach(element => {
                    $("#materia_id").append(`<option value=${element.id}>${element.matClave}-${element.matNombre}</option>`);
                });
            });
        });

     });
</script>

@endsection
