@extends('layouts.dashboard')

@section('template_title')
    Primaria ahorro escolar
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_ahorro_escolar')}}" class="breadcrumb">Lista de ahorro escolar</a>
    <a href="{{url('primaria_ahorro_escolar/create')}}" class="breadcrumb">Agregar ahorro escolar</a>
@endsection

@section('content')

@php
    $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
@endphp


<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_ahorro_escolar.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR AHORRO ESCOLAR</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                </ul>
              </div>
            </nav>

            <input type="hidden" name="curso_id_desdepantalla" id="curso_id_desdepantalla" value="{{$curso_id}}">
            <input type="hidden" name="empleado_id_desdepantalla" id="empleado_id_desdepantalla" value="{{$empleado_id}}">

            {{-- GENERAL BAR--}}
            <div id="general">
                <div class="row">
                    <div class="col s12 m6 l4">                        
                        {!! Form::label('curso_id', 'Alumno *', array('class' => '')); !!}
                        <select id="curso_id" class="browser-default validate select2" name="curso_id" style="width: 100%;">
                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                            {{-- validamos si hay un curso_id desde ruta  --}}
                            @if ($curso_id == "")
                                @foreach ($alumnoCurso as $curso)
                                    <option value="{{$curso->curso_id}}" {{old('curso_id') == $curso->curso_id ? 'selected' : '' }}>Alumno: {{$curso->perApellido1.' '.$curso->perApellido2.' '.$curso->perNombre}}, 
                                        Año: {{$curso->perAnioPago}}, Grupo: {{$curso->cgtGradoSemestre.''.$curso->cgtGrupo}}, Programa: {{$curso->progNombre}} </option>
                                @endforeach
                            @else
                                @foreach ($alumnoCurso as $curso)
                                    <option value="{{$curso->curso_id}}" {{$curso_id == $curso->curso_id ? 'selected' : '' }}>Alumno: {{$curso->perApellido1.' '.$curso->perApellido2.' '.$curso->perNombre}}, 
                                        Año: {{$curso->perAnioPago}}, Grupo: {{$curso->cgtGradoSemestre.''.$curso->cgtGrupo}}, Programa: {{$curso->progNombre}} </option>
                                @endforeach
                            @endif
                            
                        </select>                        
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('fecha', 'Fecha de movimiento *', ['class' => '']); !!}
                        {!! Form::date('fecha', old('fecha'), array('id' => 'fecha', 'class' => 'validate', 'readonly')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Mes de moviento *</label>
                        <select name="aplica_mes_nombre" id="aplica_mes_nombre" class="browser-default validate select2" style="width:100%;">
                            <option value="">SELECCIONE UNA OPCIÓN</option>
                            <option value="SEPTIEMBRE" {{old('aplica_mes_nombre') == "SEPTIEMBRE" ? 'selected' : '' }}>SEPTIEMBRE</option>
                            <option value="OCTUBRE" {{old('aplica_mes_nombre') == "OCTUBRE" ? 'selected' : '' }}>OCTUBRE</option>
                            <option value="NOVIEMBRE" {{old('aplica_mes_nombre') == "NOVIEMBRE" ? 'selected' : '' }}>NOVIEMBRE</option>
                            <option value="DICIEMBRE" {{old('aplica_mes_nombre') == "DICIEMBRE" ? 'selected' : '' }}>DICIEMBRE</option>
                            <option value="ENERO" {{old('aplica_mes_nombre') == "ENERO" ? 'selected' : '' }}>ENERO</option>
                            <option value="FEBRERO" {{old('aplica_mes_nombre') == "FEBRERO" ? 'selected' : '' }}>FEBRERO</option>
                            <option value="MARZO" {{old('aplica_mes_nombre') == "MARZO" ? 'selected' : '' }}>MARZO</option>
                            <option value="ABRIL" {{old('aplica_mes_nombre') == "ABRIL" ? 'selected' : '' }}>ABRIL</option>
                            <option value="MAYO" {{old('aplica_mes_nombre') == "MAYO" ? 'selected' : '' }}>MAYO</option>
                            <option value="JUNIO" {{old('aplica_mes_nombre') == "JUNIO" ? 'selected' : '' }}>JUNIO</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        <input type="number" name="importe" id="importe" value="{{old('importe')}}" class="validate Can_Produc" min="0" step="0.01">
                        {!! Form::label('importe', 'Importe *', ['class' => '']); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4" style="display: none;">
                        <label for="movimiento">Moviento *</label>
                        <select name="movimiento" id="movimiento" class="browser-default validate select2" style="width:100%;">
                            {{--  <option value="">SELECCIONE UNA OPCIÓN</option>  --}}
                            <option value="DEPOSITO" {{old('movimiento') == "DEPOSITO" ? 'selected' : '' }}>DEPOSITO</option>
                            {{--  <option value="RETIRO" {{old('movimiento') == "RETIRO" ? 'selected' : '' }}>RETIRO</option>                              --}}
                        </select>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="observacion">Observación</label>
                            <textarea name="observacion" style="font-size: 13px; resize: none;" class="validate"
                                    id="observacion" cols="30" rows="10">{{old('observacion')}}</textarea>
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field" id="sal_fin">
                            <label style="color: red" for="saldo_final">Saldo total disponible</label>
                            <strong><input type="number" value="{{$saldo_inicial,old('saldo_final')}}" name="saldo_final" id="saldo_final" class="validate" min="0" step="0.01" readonly></strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{--  <div class="col s12 m6 l4">
                        <label for="primaria_empleado_id">Docente *</label>
                        <select name="primaria_empleado_id" id="primaria_empleado_id" class="browser-default validate select2" style="width:100%;">
                            <option value="">SELECCIONE UNA OPCIÓN</option>
                            @if ($empleado_id == "")
                                @foreach ($primaria_empleados as $primaria_empleado)
                                    <option value="{{$primaria_empleado->id}}" {{old('primaria_empleado_id') == $primaria_empleado->id ? 'selected' : '' }}>{{$primaria_empleado->empApellido1.' '.$primaria_empleado->empApellido2.' '.$primaria_empleado->empNombre}}</option>
                                @endforeach
                            @else
                                @foreach ($primaria_empleados as $primaria_empleado)
                                    <option value="{{$primaria_empleado->id}}" disabled {{$empleado_id == $primaria_empleado->id ? 'selected' : '' }}>{{$primaria_empleado->empApellido1.' '.$primaria_empleado->empApellido2.' '.$primaria_empleado->empNombre}}</option>
                                @endforeach     
                                <input type="hidden" name="primaria_empleado_id" value="{{$empleado_id}}" />
 
                            @endif
                                                                    
                        </select>

                    </div>  --}}

                    <input type="hidden" name="primaria_empleado_id" id="primaria_empleado_id" value="{{$usuario_logueado}}">

                </div>
                
                {{-- <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        <input type="number" name="saldo_final" id="saldo_final" class="validate" min="0" step="0.01">
                        {!! Form::label('saldo_final', 'Saldo final', ['class' => '']); !!}
                        </div>
                    </div>
                </div> --}}


            </div>
           
          </div>
          <div class="card-action">
              <button class="btn-large waves-effect darken-3" type="submit">Guardar<i class="material-icons left">save</i></button>            
            
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>




@endsection

@section('footer_scripts')
  @include('primaria.ahorro_alumnos.obtenerSaldo')

  <script type="text/javascript">
    $(document).ready(function() {
        
        var fecha = new Date();

        var year = fecha.getFullYear();
        var month =  fecha.getMonth() + 1;
        if(month < 10){
            month = "0" + month;
        }
        var date = fecha.getDate();
        if(date < 10){
            date = "0" + date;
        }
        var fecha_formateada = year  + '-' + month + '-' + date;
    
        //FECHA DEL SISTEMA EN INPUT
        $("#fecha").val(fecha_formateada);
    
    
        //SELECCIONAR EL VALOR POR MES DEL SISTEMA
        /*if(month == "01"){
            $("#aplica_mes_nombre option[value='ENERO']").attr("selected",true);
        }
        if(month == "02"){
            $("#aplica_mes_nombre option[value='FEBRERO']").attr("selected",true);
        }
        if(month == "03"){
            $("#aplica_mes_nombre option[value='MARZO']").attr("selected",true);
        }
        if(month == "04"){
            $("#aplica_mes_nombre option[value='ABRIL']").attr("selected",true);
        }
        if(month == "05"){
            $("#aplica_mes_nombre option[value='MAYO']").attr("selected",true);
        }
        if(month == "06"){
            $("#aplica_mes_nombre option[value='JUNIO']").attr("selected",true);
        }
        if(month == "07"){
            $("#aplica_mes_nombre option[value='JUNIO']").attr("selected",true);
        }
        if(month == "08"){
            $("#aplica_mes_nombre option[value='JUNIO']").attr("selected",true);
        }
        if(month == "09"){
            $("#aplica_mes_nombre option[value='SEPTIEMBRE']").attr("selected",true);
        }
        if(month == "10"){
            $("#aplica_mes_nombre option[value='OCTUBRE']").attr("selected",true);
        }
        if(month == "11"){
            $("#aplica_mes_nombre option[value='NOVIEMBRE']").attr("selected",true);
        }
        if(month == "12"){
            $("#aplica_mes_nombre option[value='DICIEMBRE']").attr("selected",true);
        }*/
    
    
        if($("#saldo_final").val() != ""){
            $("#sal_fin").removeClass('input-field');
        }else{
            $("#sal_fin").addClass('input-field');
    
        }
        
    });
</script>


@endsection