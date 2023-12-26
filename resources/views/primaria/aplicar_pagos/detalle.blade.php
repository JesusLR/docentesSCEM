@extends('layouts.dashboard')

@section('template_title')
  Aplicar pagos
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria/pagos/aplicar_pagos')}}" class="breadcrumb">Lista de pagos</a>
    <a href="{{url()->current()}}" class="breadcrumb">Detalle de pago</a>
@endsection

@section('content')
<div class="row">
  <div class="col s12 ">
    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primariaAplicarPagos.store', 'method' => 'POST']) !!}
      <div class="card ">
        <div class="card-content ">
          <span class="card-title">DETALLE DE PAGO</span>

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
              <div class="col s12 m4 l4">
                {!! Form::label('', 'Nombre del alumno', ['class' => '']); !!}
                <p>{{$pago->pagClaveAlu}} {{$alumno->persona->perNombre}} {{$alumno->persona->perApellido1}} {{$alumno->persona->perApellido2}}</p>



                {!! Form::label('', 'Año de inicio del curso', ['class' => '']); !!}
                <p>{{$pago->pagAnioPer}}</p>


                {!! Form::label('', 'Concepto del pago', ['class' => '']); !!}
                <p>{{$pago->pagConcPago}}</p>


                {!! Form::label('', 'Fecha del pago', ['class' => '']); !!}
                <p>{{Carbon\Carbon::parse($pago->pagFechaPago)->format("d-m-Y")}}</p>


                {!! Form::label('', 'Importe del pago', ['class' => '']); !!}
                <p>${{$pago->pagImpPago}}</p>

                {!! Form::label('', 'Incluye inscripción enero', ['class' => '']); !!}
                <p>${{$incluyeInscripcionEnero}}</p>


                {!! Form::label('', 'Referencia del pago', ['class' => '']); !!}
                <p>{{$pago->pagRefPago}}</p>


                {!! Form::label('', 'Usuario registra el pago', ['class' => '']); !!}
                <p>{{$usuario->perNombre}} {{$usuario->perApellido1}} {{$usuario->perApellido2}}</p>

                {!! Form::label('', 'Fecha de aplicación pago', ['class' => '']); !!}
                <p>{{Carbon\Carbon::parse($pago->created_at)->format("d-m-Y")}}</p>


                {!! Form::label('', 'Estado del pago', ['class' => '']); !!}
                <p>{{$pago->pagEstado}}</p>

                {!! Form::label('', 'Observaciones del pago', ['class' => '']); !!}
                <p>
                  {{$pago->pagObservacion == "P" ? "PAGO NORMAL": ""}}
                  {{$pago->pagObservacion == "B" ? "BECA": ""}}
                  {{$pago->pagObservacion == "C" ? "CREDITO": ""}}
                  {{$pago->pagObservacion == "D" ? "DESCUENTO": ""}}
                </p>



                {!! Form::label('', 'Aplicación', ['class' => '']); !!}
                <p>
                  {{$pago->pagFormaAplico == "M" ? "MANUAL": ""}}
                  {{$pago->pagFormaAplico == "A" ? "AUTOMATICO": ""}}
                </p>


                {!! Form::label('', 'Comentarios', ['class' => '']); !!}
                <p>
                  {{$pago->pagComentario}}
                </p>
              </div>

              <div class="col s12 m6 l6">
                <table  class="responsive-table display" cellspacing="0" width="100%">
                  <thead>
                  <tr>
                      <th>Curso</th>
                      <th>Beca</th>
                      <th>Tipo</th>
                      <th>Observaciones</th>
                      <th>Semestre</th>
                      <th>Prog clave</th>
                  </tr>
                  </thead>
                  <tfoot>
                    @foreach ($cursos as $curso)
                      <tr>
                        <th>{{$curso->periodo->perNumero}}-{{$curso->periodo->perAnio}}</th>
                        <th>{{$curso->curTipoBeca ? "%".$curso->curPorcentajeBeca: ""}}</th>
                        <th>{{$curso->curTipoBeca ? $curso->curTipoBeca: ""}}</th>
                        <th>{{ $curso->curObservacionesBeca}}</th>
                        <th>{{ $curso->cgt->cgtGradoSemestre}}</th>
                        <th>{{$curso->cgt->plan->programa->progClave}}</th>
                      </tr>
                    @endforeach
                  </tfoot>
                </table>
              </div>
            </div>

          </div>
        </div>


      </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection

@section('footer_scripts')

@endsection
