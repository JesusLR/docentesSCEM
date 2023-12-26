@extends('layouts.dashboard')

@section('template_title')
    Bachiller inscrito evidencia
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('bachiller_grupo_yucatan')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('bachiller.bachiller_grupo_yucatan.index')}}" class="breadcrumb">Lista de grupos</a>
    <label class="breadcrumb">Agregar inscrito evidencia</label>
@endsection

@section('content')

@php
    
use App\Http\Models\Departamento;
use App\Http\Models\Bachiller\Bachiller_calendarioexamen;
use App\Http\Helpers\Utils;


$departamento_CME = Departamento::with('ubicacion')->findOrFail(7);
$perActual_CME = $departamento_CME->perActual;
$bachiller_calendarioexamen_cme = Bachiller_calendarioexamen::where('periodo_id', '=', $perActual_CME)->first();

$departamento_CVA = Departamento::with('ubicacion')->findOrFail(17);
$perActual_CVA = $departamento_CVA->perActual;
$bachiller_calendarioexamen_cva = Bachiller_calendarioexamen::where('periodo_id', '=', $perActual_CVA)->first();
@endphp

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller.bachiller_evidencias_inscritos.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">CAPTURA DE INSCRITO EVIDENCIA @if($bachiller_grupo->estado_act == "C")<span style="color: red;" id="estado_act"> - <b>GRUPO CERRADO</b></span>@endif</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                </ul>
              </div>
            </nav>
            <br>

            {{-- GENERAL BAR--}}
            <div id="general">
              
                
                <div class="row">
                    <div class="col s12">
                        <p><b>Grupo: </b>{{$bachiller_grupo->gpoGrado.'-'.$bachiller_grupo->gpoClave}}</p>
                        <p><b>Periodo: </b>{{$bachiller_grupo->perNumero.'-'.$bachiller_grupo->perAnio}}</p>         
                        <p><b>Materia: </b>
                          @if($bachiller_grupo->gpoMatComplementaria != "")
                            {{$bachiller_grupo->matClave.' - '.$bachiller_grupo->matNombre.' - '.$bachiller_grupo->gpoMatComplementaria}}
                          @else
                          {{$bachiller_grupo->matClave.' - '.$bachiller_grupo->matNombre}}
                          @endif
                        </p>             
                    </div>   
                    
                    <input type="hidden" id="bachiller_grupo_id" name="bachiller_grupo_id" value="{{$bachiller_grupo->id}}" readonly>                 
                   
                </div>

             
              @if ($calendario_examen->calexFinOrdinario >= $fechaHoy)
                <div class="row">
                  <div class="col s12 m6 l8">
                      {!! Form::label('bachiller_evidencia_id', 'Evidencia *', array('class' => '')); !!}
                      <select id="bachiller_evidencia_id" name="bachiller_evidencia_id" data-bachiller_evidencia_id-id="{{old('bachiller_evidencia_id')}}"
                          class="browser-default validate select2" required style="width: 100%;">
                          <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                          @if ($ubicacion == "CME")
                            @forelse ($bachiller_evidencias as $item)
                              @if ($item->eviFaltas == "N")
                                  @php
                                      $faltas = "NO SE REGISTRAN FALTAS"
                                  @endphp
                              @else
                                  @php
                                      $faltas = "SE REGISTRAN FALTAS"
                                  @endphp
                              @endif
                              <option value="{{$item->id}}">Fecha: {{Utils::fecha_string($item->fecha_entrega, $item->fecha_entrega)}}-Número: {{$item->eviNumero}} - Descripción: {{$item->eviDescripcion}} - {{$faltas}}</option>
                            @empty
                                <option value="">NO HAY EVIDENCIAS CREADAS PARA EL GRUPO MATERIA SELECCINADO</option>
                            @endforelse
                          @else

                              @forelse ($bachiller_evidencias as $item)
                                @if ($item->eviFaltas == "N")
                                  @php
                                      $faltas = "NO SE REGISTRAN FALTAS"
                                  @endphp
                                @else
                                  @php
                                      $faltas = "SE REGISTRAN FALTAS"
                                  @endphp
                                @endif

                                @if ($item->fecha_entrega >= $calendario_examen->calexInicioParcial1 && $item->fecha_entrega <= $calendario_examen->calexFinParcial1 && $calendario_examen->calexInicioParcial1 <= $fechaHoy && $calendario_examen->calexFinParcial1 >= $fechaHoy)
                                {{--  @if ($item->fecha_entrega >= $calendario_examen->calexInicioParcial1 && $item->fecha_entrega <= $calendario_examen->calexFinParcial1 && $item->fecha_entrega >=  $fechaHoy)  --}}
                                  
                                    <option value="{{$item->id}}">Número: {{$item->eviNumero}} - Descripción: {{$item->eviDescripcion}} - {{$faltas}}</option>
                                  
                                @endif

                                @if ($item->fecha_entrega >= $calendario_examen->calexInicioParcial2 && $item->fecha_entrega <= $calendario_examen->calexFinParcial2 && $calendario_examen->calexInicioParcial2 <= $fechaHoy && $calendario_examen->calexFinParcial2 >= $fechaHoy)

                                {{--  @if ($item->fecha_entrega >= $calendario_examen->calexInicioParcial2 && $item->fecha_entrega <= $calendario_examen->calexFinParcial2 && $item->fecha_entrega >=  $fechaHoy)  --}}
                                  
                                    <option value="{{$item->id}}">Número: {{$item->eviNumero}} - Descripción: {{$item->eviDescripcion}} - {{$faltas}}</option>
                                  
                                @endif

                                @if ($item->fecha_entrega >= $calendario_examen->calexInicioParcial3 && $item->fecha_entrega <= $calendario_examen->calexFinParcial3 && $calendario_examen->calexInicioParcial3 <= $fechaHoy && $calendario_examen->calexFinParcial3 >= $fechaHoy)

                                {{--  @if ($item->fecha_entrega >= $calendario_examen->calexInicioParcial3 && $item->fecha_entrega <= $calendario_examen->calexFinParcial3 && $item->fecha_entrega >=  $fechaHoy)  --}}
                                  
                                    <option value="{{$item->id}}">Número: {{$item->eviNumero}} - Descripción: {{$item->eviDescripcion}} - {{$faltas}}</option>
                                  
                                @endif

                                @if ($item->fecha_entrega >= $calendario_examen->calexInicioOrdinario && $item->fecha_entrega <= $calendario_examen->calexFinOrdinario && $calendario_examen->calexInicioOrdinario <= $fechaHoy && $calendario_examen->calexFinOrdinario >= $fechaHoy)

                                {{--  @if ($item->fecha_entrega >= $calendario_examen->calexInicioOrdinario && $item->fecha_entrega <= $calendario_examen->calexFinOrdinario && $item->fecha_entrega >=  $fechaHoy)  --}}
                                  
                                    <option value="{{$item->id}}">Número: {{$item->eviNumero}} - Descripción: {{$item->eviDescripcion}} - {{$faltas}}</option>
                                  
                                @endif
                              @empty
                                <option value="">NO HAY EVIDENCIAS CREADAS PARA EL GRUPO MATERIA SELECCINADO</option>
                              @endforelse                                  

                            
                          @endif
                          
                      </select>
                  </div>

                  <div class="col s12 m6 l4" id="puntos" style="display: none;">
                      <br>
                      <label style="color: #000">Puntos maximos de evidencia: </label><label id="puntosMaximos" style="color: red; font-size: 25px;"></label>
                  </div>
                </div>
              @endif

                

                <br>



                <div class="row" id="Tabla">
                    <div class="col s12">
                        <h5 style="display: none;" id="alumno"></h5>
                        
                        <div class="responsive-table display" cellspacing="0" width="100%" id="tablePrint">
                        </div>
                    </div>
                </div>

                         
                

          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar',
            ['style' => 'display:none;', 'onclick'=>'this.disabled=true;this.innerText="Cargando datos...";this.form.submit(); mostrarAlerta();','class' =>
            'btn-large btn-save waves-effect darken-3 submit-button','type' => 'submit']) !!}

        </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
  <style>
    table tbody tr:nth-child(odd) {
        background: #F7F8F9;
    }
    table tbody tr:nth-child(even) {
        background: #F1F1F1;
    }
    table th {
      background: #01579B;
      color: #fff;

    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
</style>

@endsection

@section('footer_scripts')

@include('bachiller.evidencias_inscritos.getEvidenciasCapturadas')


<script>
    function mostrarAlerta(){

        //$("#submit-button").prop('disabled', true);
        var html = "";
        html += "<div class='preloader-wrapper big active'>"+
            "<div class='spinner-layer spinner-blue-only'>"+
              "<div class='circle-clipper left'>"+
                "<div class='circle'></div>"+
              "</div><div class='gap-patch'>"+
                "<div class='circle'></div>"+
              "</div><div class='circle-clipper right'>"+
                "<div class='circle'></div>"+
              "</div>"+
            "</div>"+
          "</div>";

        html += "<p>" + "</p>"

        swal({
            html:true,
            title: "Guardando...",
            text: html,
            showConfirmButton: false
            //confirmButtonText: "Ok",
        })

       
    }


</script>

{{--  @include('bachiller.evidencias_inscritos.guardarEvidenciasJs')  --}}

@endsection





