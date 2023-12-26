@extends('layouts.dashboard')

@section('template_title')
Primaria contenido fundamental
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{route('primaria.primaria_contenido_fundamental.index')}}" class="breadcrumb">Lista de contenidos fundamensales</a>
<a href="#" class="breadcrumb">Crear contenido fundamental</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_contenido_fundamental.store', 'method' => 'POST']) !!}

        <div class="card ">
            <div class="card-content ">
                <span class="card-title">AGREGAR CONTENIDO FUNDAMENTAL</span>

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
                                <label for="contenido">Contenido *</label>
                                {!! Form::textarea('contenido', NULL, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                                'style' => 'resize: none')) !!}
                        </div>


                        <div class="col s12 m6 l6">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" data-departamento-idold="{{old('categoria')}}" class="browser-default validate select2" required name="categoria" style="width: 100%;">
                                <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                                @foreach ($primaria_contenidos_categorias as $item)
                                    <option value="{{$item->id}}">{{$item->categoria}}</option>
                                @endforeach
                            </select>
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
