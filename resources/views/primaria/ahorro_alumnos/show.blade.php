@extends('layouts.dashboard')

@section('template_title')
    Primaria ahorro escolar
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_ahorro_escolar')}}" class="breadcrumb">Lista de ahorro escolar</a>
    <a href="{{url('primaria_ahorro_escolar/'.$primaria_inscritos_ahorro->id)}}" class="breadcrumb">Ver ahorro escolar</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AHORRO ESCOLAR #{{$primaria_inscritos_ahorro->id}}</span>

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
                        <label for="aplica_mes_nombre">Campus</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->periodo->departamento->ubicacion->ubiClave.'-'.$primaria_inscritos_ahorro->curso->periodo->departamento->ubicacion->ubiNombre}}">                    

                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Departamento</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->periodo->departamento->depClave.'-'.$primaria_inscritos_ahorro->curso->periodo->departamento->depNombre}}">
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Escuela</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->cgt->plan->programa->escuela->escClave.'-'.$primaria_inscritos_ahorro->curso->cgt->plan->programa->escuela->escNombre}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Período</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->periodo->perNumero.'-'.$primaria_inscritos_ahorro->curso->periodo->perAnioPago}}">
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Fecha inicial</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->periodo->perFechaInicial}}">
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Fecha final</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->periodo->perFechaFinal}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Programa</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->cgt->plan->programa->progNombre}}">
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Plan</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->cgt->plan->planClave}}">
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Grado y Grupo</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->cgt->cgtGradoSemestre.''.$primaria_inscritos_ahorro->curso->cgt->cgtGrupo}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">                        
                        {!! Form::label('curso_id', 'Alumno', array('class' => '')); !!}
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->curso->alumno->persona->perApellido1.' '.$primaria_inscritos_ahorro->curso->alumno->persona->perApellido2.' '.$primaria_inscritos_ahorro->curso->alumno->persona->perNombre}}">                    
                    </div>

                    <div class="col s12 m6 l4">
                        {!! Form::label('fecha', 'Fecha de movimiento', ['class' => '']); !!}
                        {!! Form::date('fecha', $primaria_inscritos_ahorro->fecha, array('id' => 'fecha', 'readonly')) !!}
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="aplica_mes_nombre">Mes de movimiento</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->aplica_mes_nombre}}">                    

                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        <input type="number" readonly name="importe" id="importe" value="{{$primaria_inscritos_ahorro->importe}}" class="validate Can_Produc" min="0" step="0.01">
                        {!! Form::label('importe', 'Importe', ['class' => '']); !!}
                        </div>
                    </div>

                    <div class="col s12 m6 l4">
                        <label for="movimiento">Tipo de Moviento</label>
                        <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->movimiento}}">
                    </div>

                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            <label for="observacion">Observación</label>
                            <textarea name="observacion" style="font-size: 13px; resize: none;" class="validate"
                                    id="observacion" cols="30" rows="10">{{$primaria_inscritos_ahorro->observacion}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <label for="primaria_empleado_id">Docente</label>
                        <input readonly type="text" name="" id="" 
                        value="{{$primaria_inscritos_ahorro->primaria_empleado->empApellido1.' '.$primaria_inscritos_ahorro->primaria_empleado->empApellido2.' '.$primaria_inscritos_ahorro->primaria_empleado->empNombre}}">                    

                    </div>

                 
                    <div class="col s12 m6 l4">
                        <label for="saldo_inicial">Saldo inicial</label>
                        <strong>                        
                            <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->saldo_inicial}}">
                        </strong>
                    </div>

                    <div class="col s12 m6 l4">
                        <label style="color: red" for="saldo_final">Saldo Final</label>
                        <strong>                        
                            <input readonly type="text" name="" id="" value="{{$primaria_inscritos_ahorro->saldo_final}}">
                        </strong>
                    </div>

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
        </div>
    </div>
  </div>




@endsection

@section('footer_scripts')

@endsection