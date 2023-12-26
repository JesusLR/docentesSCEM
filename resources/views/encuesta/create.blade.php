@extends('layouts.dashboard')

@section('template_title')
    Encuesta
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('/')}}" class="breadcrumb">Inicio</a>
    <label class="breadcrumb">Encuesta</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12">
        <div class="card ">
          <div class="card-content ">
                @if($encuesta)
                  <div class="row">
                    <div class="col s12 m6 l12">
                      <p><b>Click en el botón para ir a la página de la encuesta. Al concluir la misma, da click en "Encuesta realizada".</b></p>
                      <a href="{{ $encuesta->encUrl }}" class="btn-large waves-effect  darken-3" type="button" target="_blank">Encuesta
                          <i class="material-icons left">library_books</i>
                      </a>
                    </div>
                    <div class="col s12 m6 l4">
                      <br><br>
                      <form method="POST" action="{{url('encuesta/verificar_codigo')}}" target="_blank">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="empleado_id" id="empleado_id" value="{{auth()->user()->empleado->id}}">
                        <button type="submit" class="btn-large waves-effect  darken-3">Encuesta realizada</button>
                      </form>
                    </div>
                  </div>
                @else
                  <div class="row">
                    <div class="col s12 m6 l12">
                      <p><b>No se encuentra tu encuesta. Favor de vrificar con tu coordinador</b></p>
                    </div>
                  </div>
                @endif

          </div>

        </div>
    </div>
  </div>

@endsection



