@extends('layouts.dashboard')

@section('template_title')
  Aplicar pagos
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria/pagos/aplicar_pagos')}}" class="breadcrumb">Lista de pagos</a>
    <a href="{{url('primaria/pagos/aplicar_pagos/create')}}" class="breadcrumb">Agregar pagos</a>
@endsection

@section('content')


<div class="row">
  <input type="hidden" value="" class="pagoVerificado">
  <div class="col s12 ">
    {!! Form::open(['class' => 'formAplicarPago', 'onKeypress' => 'return disableEnterKey(event)','route' => 'primariaAplicarPagos.store', 'method' => 'POST']) !!}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">AGREGAR PAGO</span>

          {{-- NAVIGATION BAR--}}
          <nav class="nav-extended">
            <div class="nav-content">
              <ul class="tabs tabs-transparent">
                <li class="tab"><a class="active" href="#general">General</a></li>
              </ul>
            </div>
          </nav>

          {{-- GENERAL BAR--}}
          <div id="general">
            <div class="row">
              <div class="col s12 m6 l4">
                  <div class="input-field col s12 m6 l6">
                  {!! Form::text('pagClaveAlu', NULL, array('id' => 'pagClaveAlu', 'class' => 'validate','required','maxlength'=>'40')) !!}
                  {!! Form::label('pagClaveAlu', 'Clave de pago del alumno *', array('class' => '')); !!}
                  </div>
                  <div class="input-field col s12 m6 l6">
                  {!! Form::number('pagAnioPer', NULL, array('id' => 'pagAnioPer', 'class' => 'validate','required','min'=>'0','max'=>'9999','onKeyPress="if(this.value.length==4) return false;"')) !!}
                  {!! Form::label('pagAnioPer', 'Año de inicio del curso *', array('id' => 'pagAnioPer', 'class' => '')); !!}
                </div>
              </div>
              <div class="col s12 m6 l4">
                {!! Form::label('pagConcPago', 'Concepto del pago *', ['class' => '']); !!}
                <select id="pagConcPago" class="browser-default validate select2" data-concepto-pago="{{old('pagConcPago')}}" required name="pagConcPago" style="width: 100%;">
                    <option value="">SELECCIONE UNA OPCIÓN</option>
                    @foreach($conceptosPago as $concepto)
                        <option value="{{$concepto->conpClave}}">{{$concepto->conpClave}} {{$concepto->conpNombre}}</option>
                    @endforeach
                </select>
              </div>
              <div id="campo_opcional_periodo" class="col s12 m6 l4">
                {!! Form::label('educacioncontinua_id', 'Edu. Continua *', ['class' => '']); !!}
                <select id="educacioncontinua_id" name="educacioncontinua_id" data-educacioncontinua-id="{{old('educacioncontinua_id')}}" class="browser-default validate select2" style="width: 100%;">
                  <option value="">SELECCIONE UNA OPCIÓN</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::number('pagImpPago', 0, array('id' => 'pagImpPago', 'class' => 'validate','required', 'min' => 0)) !!}
                  {!! Form::label('pagImpPago', 'Importe *', ['class' => '']); !!}
                </div>
              </div>
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::text('pagRefPago', NULL, array('id' => 'pagRefPago', 'class' => 'validate', 'min' => 0)) !!}
                  {!! Form::label('pagRefPago', 'Referencia del pago', ['class' => '']); !!}
                </div>
              </div>

              <div class="col s12 m6 l4">
                {!! Form::label('pagObservacion', 'Observaciones del pago *', ['class' => '']); !!}
                <select name="pagObservacion" id="pagObservacion" class="browser-default validate select2 required" style="width: 100%;">
                    <option value="P">PAGO NORMAL</option>
                    <option value="B">BECA</option>
                    <option value="C">CRÉDITO</option>
                    <option value="D">DESCUENTO</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m6 l4">
                {!! Form::label('pagFechaPago', 'Fecha de pago', array('class' => '')); !!}
                {!! Form::date('pagFechaPago', NULL, []) !!}
              </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::text('pagComentario', NULL, array('id' => 'pagComentario', 'class' => 'validate')) !!}
                  {!! Form::label('pagComentario', 'Comentarios', ['class' => '']); !!}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col s12">
                <table id="tbl-verificar-pagos" class="responsive-table display" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Clave Alumno</th>
                      <th>Nombre Alumno</th>
                      <th>Año Periodo</th>
                      <th>Concepto Pago</th>
                      <th>Fecha Pago</th>
                      <th>Importe Pago</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

          </div>
        </div>


        <div class="card-action">
          {!! Form::button('<i class="material-icons left">verified_user</i> Verificar Pago', ['class' => 'btnVerificarPago btn-large waves-effect  darken-3','type' => 'submit']) !!}

          {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btnAplicarPago btn-large waves-effect  darken-3','type' => 'submit']) !!}
        </div>
      </div>
    {!! Form::close() !!}
  </div>
</div>


@endsection

@section('footer_scripts')

@include('primaria.scripts.funcionesAuxiliares')

  {!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
  {!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
  <script type="text/javascript">
    $(document).ready(function() {
      let pagConcPago = $('#pagConcPago');
      // let perNumero = $('#perNumero');
      let educacion_continua = $('#educacioncontinua_id');
      let campo_opcional_periodo = $('#campo_opcional_periodo');
      let pagClaveAlu = $('#pagClaveAlu');

      apply_data_to_select('perNumero', 'per-numero');
      actualizar_formulario();
      pagConcPago.on('change', function() {
        resetSelect('educacioncontinua_id');
        actualizar_formulario();
      });

      $(pagClaveAlu).on('keypress', function() {
        console.log('asdfaf');
        resetSelect('educacioncontinua_id');
      });

      pagClaveAlu.on('change', function() {
        resetSelect('educacioncontinua_id');
        actualizar_formulario();
      });

      function actualizar_formulario() {
        if(pagConcPago.val() == "90")  {
          campo_opcional_periodo.show();
          pagClaveAlu.val() ? buscar_inscripciones_educacion_continua(educacion_continua, pagClaveAlu.val()) : resetSelect('educacioncontinua_id');
        } else {
          resetSelect('educacioncontinua_id');
          campo_opcional_periodo.hide();
        }
      }

    }); // document.ready

    function buscar_inscripciones_educacion_continua(selectObject, pagClaveAlu) {
      let current_value = $(selectObject).data('educacioncontinua-id');
      $.ajax({
        type: 'GET',
        url: `${base_url}/primaria/api/aplicar_pagos/buscar_inscripciones_educacion_continua/${pagClaveAlu}`,
        dataType: 'json',
        data: {'pagClaveAlu': pagClaveAlu},
        success: function(programas) {
          if(programas) {
            $.each(programas, function(key, programa) {
              selectObject.append(new Option(`${programa.ecClave}-${programa.ecNombre}`, programa.id));
              (programa.id == current_value) && selectObject.val(programa.id);
            });

            selectObject.trigger('change');
          }
        },
        error: function(Xhr, textStatus, errorMessage) {
          swal({
            type: 'error',
            title: 'Error',
            text: errorMessage,
          })
        }
      });
    }


    $('.btnVerificarPago').on("click", function (e) {
      e.preventDefault()

      if (!$("#pagClaveAlu").val() || !$("#pagAnioPer").val()) {
        swal({
          title: "Aplicar Pagos",
          text: "Para verifica pago se requiere clave de pago del alumno, y año de inicio del curso",
          type: "warning",
          confirmButtonColor: '#0277bd',
        });

        return
      }


      if ($.fn.DataTable.isDataTable("#tbl-verificar-pagos")) {
        $('#tbl-verificar-pagos').DataTable().clear().destroy();
      }

      $('#tbl-verificar-pagos').dataTable({
        "language": {"url": base_url + "/api/lang/javascript/datatables"},
        "dom": '"top"i',
        "pageLength": 200,
        "ajax": {
          "type" : "POST",
          "data" : {
            pagClaveAlu: $("#pagClaveAlu").val(),
            pagAnioPer: $("#pagAnioPer").val(),
            _token: $("meta[name=csrf-token]").attr("content")
          },
          'url': base_url + "/primaria/api/pagos/verificarExistePago",
          beforeSend: function () {
            $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');});
          },
          complete: function (e) {
            $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
          },
        },
        "columns":[
          {data: "pagClaveAlu",name: "pagClaveAlu"},
          {data: "nombreCompleto"},
          {data: "pagAnioPer", name: "pagAnioPer"},
          {data: "pagConcPago",name: "pagConcPago"},
          {data: "pagFechaPago",name: "pagFechaPago"},
          {data: "pagImpPago",name: "pagImpPago"},
        ],
        //Apply the search
        initComplete: function () {
          $('.pagoVerificado').val("SI")
        },


      });

    });
  </script>


  <script type="text/javascript">
    $('.btnAplicarPago').on("click", function (e) {
      e.preventDefault()

        if (!$("#pagImpPago").val() ) {
            swal({
                title: "Aplicar Pagos",
                text: "Para registrar un concepto de pago, se requiere importe minimo en 0",
                type: "warning",
                confirmButtonColor: '#0277bd',
            });

            return
        }

      if ($('.pagoVerificado').val() !== "SI") {
        swal({
          title: "Aplicar Pagos",
          text: "Favor de verificar si existe el pago",
          type: "warning",
          confirmButtonColor: '#0277bd',
      }, function(isConfirm) {

      });

      return
    }


      //console.log("no entra")
      $.ajax({
          data: {
              aluClave: $("#pagClaveAlu").val(),
              _token: $("meta[name=csrf-token]").attr("content")
          },
          type: "POST",
          dataType: "JSON",
          url: base_url + "/primaria/pagos/aplicar_pagos/existeAlumnoByClavePago",
      })
      .done(function( data, textStatus, jqXHR ) {


          if (data.existe) {
              $('.formAplicarPago').submit();
          }
          if (!data.existe) {
              swal({
                  title: "Aplicar Pagos",
                  text: "La clave de pago no existe en la tabla alumnos. ¿Desea realizar este pago?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: '#0277bd',
                  confirmButtonText: 'SI',
                  cancelButtonText: "NO",
                  closeOnConfirm: false,
                  closeOnCancel: false
              }, function(isConfirm) {
                  if (isConfirm) {
                      $('.formAplicarPago').submit();
                  } else {
                      swal.close()
                  }
              });
          }

      })
      .fail(function( jqXHR, textStatus, errorThrown ) {
          console.log(textStatus)
          console.log(jqXHR)
      });
    });
  </script>
@endsection
