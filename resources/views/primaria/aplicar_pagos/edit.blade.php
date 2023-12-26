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
  <div class="col s12 ">
    {!! Form::open(['class' => 'formAplicarPago', 'onKeypress' => 'return disableEnterKey(event)','route' => 'primariaAplicarPagos.update', 'method' => 'POST']) !!}
      <input type="hidden"  value={{$pago->id}} name="id" />
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">MODIFICAR PAGO</span>

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
                <div class="input-field">
                  {!! Form::text('pagClaveAlu', $pago->pagClaveAlu, array('id' => 'pagClaveAlu', 'class' => 'validate','required','maxlength'=>'40', 'readonly')) !!}
                  {!! Form::label('pagClaveAlu', 'Clave de pago del alumno *', array('class' => '')); !!}
                </div>
              </div>
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::number('pagAnioPer', $pago->pagAnioPer, [
                    'id' => 'pagAnioPer', 'class' => 'validate','required', 'readonly' ,
                    'min'=>'0','max'=>'9999', 'onKeyPress="if(this.value.length==4) return false;"'
                  ]) !!}
                  {!! Form::label('pagAnioPer', 'Año de inicio del curso *', array('class' => '')); !!}
                </div>
              </div>

              <div class="col s12 m6 l4">
                {!! Form::label('pagConcPago', 'Concepto del pago *', ['class' => '']); !!}
                <select id="pagConcPago" class="browser-default validate select2" data-concepto-pago="{{old('pagConcPago') ?: $pago->pagConcPago}}" required name="pagConcPago" style="width: 100%;">
                  @foreach($conceptosPago as $concepto)
                    <option value="{{$concepto->conpClave}}">{{$concepto->conpClave}} {{$concepto->conpNombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::number('pagImpPago', $pago->pagImpPago, array('id' => 'pagImpPago', 'class' => 'validate','required', 'min' => 0)) !!}
                  {!! Form::label('pagImpPago', 'Importe *', ['class' => '']); !!}
                </div>
              </div>
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::text('pagRefPago', $pago->pagRefPago, array('id' => 'pagRefPago', 'class' => 'validate', 'min' => 0)) !!}
                  {!! Form::label('pagRefPago', 'Referencia del pago', ['class' => '']); !!}
                </div>
              </div>

              <div class="col s12 m6 l4">
                {!! Form::label('pagObservacion', 'Observaciones del pago *', ['class' => '']); !!}
                <select name="pagObservacion" id="pagObservacion" class="browser-default validate select2 required" style="width: 100%;">
                    <option {{$pago->pagObservacion == "P" ? "selected": ""}} value="P">PAGO NORMAL</option>
                    <option {{$pago->pagObservacion == "B" ? "selected": ""}} value="B">BECA</option>
                    <option {{$pago->pagObservacion == "C" ? "selected": ""}} value="C">CRÉDITO</option>
                    <option {{$pago->pagObservacion == "D" ? "selected": ""}} value="D">DESCUENTO</option>
                </select>
              </div>
            </div>

            <div class="col s12 m6 l4">
                {!! Form::label('pagFechaPago', 'Fecha de pago', array('class' => '')); !!}
                {!! Form::date('pagFechaPago', $pago->pagFechaPago, []) !!}
            </div>


            <div class="row">
              <div class="col s12 m6 l4">
                <div class="input-field">
                  {!! Form::text('pagComentario', $pago->pagComentariopagImpPago, array('id' => 'pagComentario', 'class' => 'validate')) !!}
                  {!! Form::label('pagComentario', 'Comentarios', ['class' => '']); !!}
                </div>
              </div>
            </div>


          </div>
        </div>


        <div class="card-action">
          {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btnAplicarPago btn-large waves-effect  darken-3','type' => 'submit']) !!}
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
    apply_data_to_select('pagConcPago', 'concepto-pago');
  });


  $('.btnAplicarPago').on("click", function (e) {
    e.preventDefault();


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
