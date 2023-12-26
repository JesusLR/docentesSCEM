@extends('layouts.dashboard')

@section('template_title')
Primaria categoría contenido
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{route('primaria.primaria_categoria_contenido.index')}}" class="breadcrumb">Lista de contenidos fundamentales</a>
<a href="{{url('primaria_categoria_contenido/'.$primaria_contenidos_categorias->id)}}" class="breadcrumb">Ver categoría contenido</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">CATEGORÍA CONTENIDO #{{$primaria_contenidos_categorias->id}}</span>

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
                                <input type="text" id="observacion_contenido" name="categoria" readonly class="validate" value="{{$primaria_contenidos_categorias->categoria}}">
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
