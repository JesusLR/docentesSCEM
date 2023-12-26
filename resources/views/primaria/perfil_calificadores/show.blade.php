@extends('layouts.dashboard')

@section('template_title')
    Primaria calificador
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria.primaria_calificador.index')}}" class="breadcrumb">Lista de calificadores</a>
    <a href="{{url('primaria_calificador/'.$primaria_contenidos_calificadores->id)}}" class="breadcrumb">Ver calificador</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">CALIFICADOR #{{$primaria_contenidos_calificadores->id}}</span>

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
                            <input type="text" name="calificador" id="calificador" readonly class="validate" maxlength="100" value="{{ $primaria_contenidos_calificadores->calificador}}">
                            {!! Form::label('calificador', 'Nombre calificador *', array('class' => '')); !!}
                        </div>
                    </div>

                </div>
            </div>



          </div>
        </div>
    </div>
</div>



@endsection

@section('footer_scripts')


@endsection
