@extends('layouts.dashboard')

@section('template_title')
    Biblioteca
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="" class="breadcrumb">Inicio</a>
    <label class="breadcrumb">Biblioteca</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12">
        <div class="card ">
          <div class="card-content">

            <div class="row">
              <div class="col s12 m12 l12">
                <span class="card-title">BIBLIOTECA</span>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m12 l12">
              @if(Auth::user()->empleado->escuela->departamento->depClave == 'SUP')
                  <a class="waves-effect  darken-3" href="{{ url('biblioteca_action') }}">
                    <img src="{{ asset('images/LOGO_TOLMEX.png') }}">
                  </a>
                @endif
              </div>
            </div>

          </div>

        </div>
    </div>
  </div>

@endsection



