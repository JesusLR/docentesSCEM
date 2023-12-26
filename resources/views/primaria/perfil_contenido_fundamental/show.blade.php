@extends('layouts.dashboard')

@section('template_title')
Primaria contenido fundamental
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{route('primaria.primaria_contenido_fundamental.index')}}" class="breadcrumb">Lista de perfiles contenido</a>
<a href="{{url('primaria_contenido_fundamental/'.$contenido_fundamental->id)}}" class="breadcrumb">Ver contenido fundamental</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">CONTENIDO FUNDAMENTAL #{{$contenido_fundamental->id}}</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">GENERAL</a></li>
                        </ul>
                    </div>
                </nav>



                <div id="general">
                    <div class="row">

                        <div class="col s12 m6 l6">
                            {{--  <div class="input-field">  --}}
                                <label for="contenido">Contenido *</label>
                                {!! Form::textarea('contenido', $contenido_fundamental->contenido, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                                'style' => 'resize: none', 'readonly' => 'true')) !!}
                            {{--  </div>  --}}
                        </div>


                        <div class="col s12 m6 l6">
                            <label for="categoria">Categoría</label>
                            <input type="text" value="{{$contenido_fundamental->categoria}}" class="validate" readonly>
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
