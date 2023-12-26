@extends('layouts.dashboard')

@section('template_title')
    Primaria calificador
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria.primaria_calificador.index')}}" class="breadcrumb">Lista de calificadores</a>
    <a href="{{route('primaria.primaria_calificador.create')}}" class="breadcrumb">Agregar calificador</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_calificador.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR CALIFICADOR</span>

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
                            <input type="text" name="calificador" id="calificador" class="validate" maxlength="100" value="{{old('calificador')}}">
                            {!! Form::label('calificador', 'Nombre calificador *', array('class' => '')); !!}
                        </div>
                    </div>

                </div>
            </div>



          </div>
          <div class="card-action">
            <button class="btn-guardar btn-large waves-effect  darken-3" type="submit">Guardar<i class="material-icons left">save</i></button>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
</div>



@endsection

@section('footer_scripts')


@endsection
