@extends('layouts.dashboard')

@section('template_title')
Primaria calificaciones
@endsection

@section('head')

{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' =>
'stylesheet')) !!}
@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Lista de Grupo</a>
<a href="#"
    class="breadcrumb">Editar calificación</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {!! Form::open(['enctype' => 'multipart/form-data', 'onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_calificacion.calificaciones.update_calificacion', 'method' => 'POST']) !!}

        {{--
        <div class="row">
            <input type="number" id="nuevo" lang="en" value="3.1" data-decimals="1" placeholder="1.0" step="0.1" min="0.0" max="10.0">
        </div>
        --}}

        <div class="card ">
            <div class="card-content ">
                <span class="card-title">CAPTURA DE CALIFICACIONES DEL GRUPO #{{$calificaciones[0]->primaria_grupo_id}}</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">General</a></li>
                            {{-- <li class="tab"><a href="#equivalente">Equivalente</a></li> --}}
                        </ul>
                    </div>
                </nav>

                {{-- GENERAL BAR--}}
                <div id="general">

                    <div class="row">
                        <div class="col s12">
                            <p><b>Periodo: </b>{{$calificaciones[0]->perNumero}}-{{$calificaciones[0]->perAnio}}</p>
                            <p><b>Programa: </b>{{$calificaciones[0]->progNombre}}</p>
                            <p><b>Grupo: </b>{{$calificaciones[0]->gpoGrado}}{{$calificaciones[0]->gpoClave}}</p>
                            <p><b>Materia: </b>{{$calificaciones[0]->matClave}}-{{$calificaciones[0]->matNombre}} @if($calificaciones[0]->gpoMatComplementaria != "")<b>-{{$calificaciones[0]->gpoMatComplementaria}}</b> @endif</p>
                            <input type="hidden" name="matNombre" id="matNombre">
                        </div>
                    </div>

                    <div class="row">
                     
                        <input type="hidden" name="primaria_grupo_id" id="primaria_grupo_id" value="{{$primaria_grupo->id}}">

                        

                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('primaria_grupo_evidencia_id', 'Mes de evaluación *', array('class' => '')); !!}
                            <select id="primaria_grupo_evidencia_id" class="browser-default validate select2" required
                                name="primaria_grupo_evidencia_id" style="width: 100%;" data-mes-idold="primaria_grupo_evidencia_id">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                {{--  @forelse ($primaria_grupos_evidencias as $item)
                                    @if ($item->mes == "ENERO")
                                    <option value="{{$item->id}}">DICIEMBRE-ENERO</option>
                                    @else
                                    <option value="{{$item->id}}">{{$item->mes}}</option>
                                    @endif
                                    
                                @empty
                                    
                                @endforelse  --}}
                            </select>
                        </div>
                       
                        <div class="col s12 m6 l4">
                            {!! Form::label('numero_evaluacion', 'Número de evaluación *', array('class' => '')); !!}
                            <select id="numero_evaluacion" class="browser-default validate select2" required
                                name="numero_evaluacion" style="width: 100%;">
                                {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            <div id="input-field">
                                {!! Form::label('numero_evidencias', 'Total de evidencias a registrar *', array('class'
                                => '')); !!}
                                <select id="numero_evidencias" class="browser-default validate select2" required
                                name="numero_evidencias" style="width: 100%;">
                                {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                            </select>
                            </div>
                        </div>
                    </div>



                </div>
                <br>
                <div class="row">
                    <h5 id="info"></h5>
                </div>

                <div class="row" style="display: none;" id="alerta-menos-de-ciente">
                    <div class="col s12 m6 l12">
                        <h6 style="color: red">
                            "Aún NO SE HAN DEFINIDO TODAS LAS EVIDENCIAS DE APRENDIZAJE para este mes (porcentaje menor al 100%). Favor de regresar al módulo de GRUPOS, EVIDENCIAS DE APRENDIZAJE, seleccione el mes y termine de ingresar las evidencias faltantes para llegar al 100%."

                        </h6>
                    </div>
                </div>
                <div class="row" style="display: none;" id="alerta-min-max-calif">
                    <div class="col s12 m6 l12">
                        <h6 style="color: red">
                            Nota:
                            <p>Calificación de captura mínima permitida es 5</p>
                            <p>Calificación de captura máxima permitida es 10</p>

                        </h6>                      
                    </div>
                </div>              

                <div class="row" id="Tabla">
                    <div class="col s12">
                        <div class="responsive-table display" cellspacing="0" width="100%" id="tablePrint">
                        </div>
                    </div>
                </div>


            </div>

            <div class="card-action" id="btn-ocultar-si-es-menor-a-cien" style="display: none">
                <button type="submit" onclick="this.disabled=true;this.form.submit();this.innerText='Guardando datos...';" class="btn-guardar btn-large waves-effect darken-3"><i
                        class="material-icons left">save</i>Guardar</button>
            </div>
        </div>

    </div>
    {!! Form::close() !!}
</div>



<style>
    table tbody tr:nth-child(odd) {
        background: #E5E3E3;
    }

    table tbody tr:nth-child(even) {
        background: #F0ECEB;
    }

    table th {
        background: #01579B;
        color: #fff;

    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    
      
      .checkbox-warning-filled [type="checkbox"][class*='filled-in']:checked+label:after {
        border-color: #FD8136;
        background-color: #FD8136;
      }      

      .hoverTable{
        width:100%; 
        border-collapse:collapse; 
    }
  
  
    /* Define the hover highlight color for the table row */
    .hoverTable tr:hover {
          background-color: #BFC2C3;
    }
</style>


@endsection

@section('footer_scripts')




@include('primaria.calificaciones.creacionDeTabla')


@endsection
