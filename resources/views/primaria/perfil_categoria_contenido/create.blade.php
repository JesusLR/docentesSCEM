@extends('layouts.dashboard')

@section('template_title')
Primaria categoría contenido
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{route('primaria.primaria_categoria_contenido.index')}}" class="breadcrumb">Lista de categorías contenido</a>
<a href="#" class="breadcrumb">Agregar categoría contenido</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_categoria_contenido.store', 'method' => 'POST']) !!}

        <div class="card ">
            <div class="card-content ">
                <span class="card-title">AGREGAR CATEGORÍA CONTENIDO</span>

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
                             <div class="input-field">
                                <label for="categoria">Nombre de la categoría *</label>
                                <input type="text" id="categoria" name="categoria" class="validate" value="{{old('categoria')}}">
                             </div>
                        </div>

                    </div>
                </div>


            </div>
            <div class="card-action">
                <button class="btn-guardar btn-large waves-effect  darken-3" type="submit"><i
                        class="material-icons left">save</i>Guardar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>



@endsection

@section('footer_scripts')


@endsection
