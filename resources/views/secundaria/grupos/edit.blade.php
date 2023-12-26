@extends('layouts.dashboard')

@section('template_title')
    Secundaria grupo
@endsection

@section('head')
    {!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('secundaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('secundaria.secundaria_grupo.index')}}" class="breadcrumb">Lista de Grupo</a>
    <a href="{{url('secundaria_grupo/'.$grupo->id.'/edit')}}" class="breadcrumb">Editar grupo</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['secundaria.secundaria_grupo.update', $grupo->id])) }}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">EDITAR GRUPO #{{$grupo->id}}</span>

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
                    <div class="col s12 m6 l4">
                        {!! Form::label('ubicacion_id', 'Campus *', array('class' => '')); !!}
                        <select id="ubicacion_id" class="browser-default validate select2" required name="ubicacion_id" style="width: 100%;">
                            <option value="{{$grupo->plan->programa->escuela->departamento->ubicacion_id}}" selected >{{$grupo->plan->programa->escuela->departamento->ubicacion->ubiClave}}-{{$grupo->plan->programa->escuela->departamento->ubicacion->ubiNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                        <select id="departamento_id" class="browser-default validate select2" required name="departamento_id" style="width: 100%;">
                            <option value="{{$grupo->plan->programa->escuela->departamento_id}}" selected >{{$grupo->plan->programa->escuela->departamento->depClave}}-{{$grupo->plan->programa->escuela->departamento->depNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                        <select id="escuela_id" class="browser-default validate select2" required name="escuela_id" style="width: 100%;">
                            <option value="{{$grupo->plan->programa->escuela_id}}" selected >{{$grupo->plan->programa->escuela->escClave}}-{{$grupo->plan->programa->escuela->escNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('periodo_id', 'Periodo *', array('class' => '')); !!}
                        <select id="periodo_id" class="browser-default validate select2" required name="periodo_id" style="width: 100%;">
                            <option value="{{$grupo->periodo_id}}" >{{$grupo->periodo->perNumero ." - ".$grupo->periodo->perAnio}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaInicial', $grupo->periodo->perFechaInicial, array('id' => 'perFechaInicial', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('perFechaFinal', $grupo->periodo->perFechaFinal, array('id' => 'perFechaFinal', 'class' => 'validate','readonly')) !!}
                        {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                        <select id="programa_id" class="browser-default validate select2" required name="programa_id" style="width: 100%;">
                            <option value="{{$grupo->plan->programa->id}}">{{$grupo->plan->programa->progClave}}-{{$grupo->plan->programa->progNombre}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4">
                        {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                        <select id="plan_id" class="browser-default validate select2" required name="plan_id" style="width: 100%;">
                            <option value="{{$grupo->plan->id}}" >{{$grupo->plan->planClave}}</option>
                        </select>
                    </div>
                    <div class="col s12 m6 l4" style="display: none">
                        {!! Form::label('gpoExtraCurr', 'Es Materia Extracurricular *', array('class' => '')); !!}
                        <select id="gpoExtraCurr"
                            class="browser-default validate select2"
                            required name="gpoExtraCurr" style="width: 100%;">
                            <option value="N" {{$grupo->gpoExtraCurr == "N" ? "selected": ""}}>NO</option>
                            <option value="S" {{$grupo->gpoExtraCurr == "S" ? "selected": ""}}>SI</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoSemestre', $grupo->gpoGrado, array('id' => 'gpoSemestre', 'class' => 'validate','required','readonly')) !!}
                        {!! Form::label('gpoSemestre', 'Grado *', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoClave', $grupo->gpoClave, array('id' => 'gpoClave', 'class' => 'validate','required','readonly')) !!}
                        {!! Form::label('gpoClave', 'Clave grupo *', ['class' => '']); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoTurno', $grupo->gpoTurno, array('id' => 'gpoTurno', 'class' => 'validate','required','readonly')) !!}
                        {!! Form::label('gpoTurno', 'Turno *', ['class' => '']); !!}
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col s12 ">
                        {!! Form::label('materia_id', 'Materia *', array('class' => '')); !!}
                        <select id="materia_id" class="browser-default validate select2" required name="materia_id" style="width: 100%;">
                            <option value="{{$grupo->secundaria_materia->id}}">{{$grupo->secundaria_materia->matNombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('nombreAlternativo',$grupo->nombreAlternativo, array('id' => 'nombreAlternativo','onKeyPress="if(this.value.length==75) return false;"')) !!}
                        {!! Form::label('nombreAlternativo', 'Nombre Alternativo', array('class' => '')); !!}
                        </div>
                    </div>
                </div>
                @if($grupo->optativa_id)
                    <div id="seccion_optativa" class="row">
                        <div class="col s12 ">
                            {!! Form::label('optativa_id', 'Optativa', array('class' => '')); !!}
                            <select id="optativa_id" class="browser-default validate select2" name="optativa_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach($optativas as $optativa)
                                    <option value="{{$optativa->id}}" @if($grupo->optativa_id == $optativa->id) {{ 'selected' }} @endif>{{$optativa->optNumero.'-'.$optativa->optNombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <br>
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::number('gpoCupo', $grupo->gpoCupo, array('id' => 'gpoCupo', 'class' => 'validate','max'=>'999999','onKeyPress="if(this.value.length==6) return false;"')) !!}
                        {!! Form::label('gpoCupo', 'Cupo', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="empleadoinput" style="position:relative">
                            {!! Form::label('empleado_id', 'Docente titular *', array('class' => '')); !!}
                            <select id="empleado_id"  class="browser-default validate select2" required name="empleado_id" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach($empleados as $empleado)
                                    @php
                                        $empleadoId = $grupo->empleado_id_docente;
                                    @endphp
                                    <option value="{{$empleado->id}}" {{($empleadoId == $empleado->id) ? 'selected': ''}}>
                                        {{$empleado->id ." - ".$empleado->empNombre
                                            ." ". $empleado->empApellido1
                                            ." ". $empleado->empApellido2}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="block" style="width: 100%; height: 65px; position: absolute; top:0; left:0; display:none;"></div>
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="sinodalinput" style="position:relative">
                            {!! Form::label('empleado_id_auxiliar', 'Docente auxiliar', array('class' => '')); !!}
                            <select id="empleado_id_auxiliar"  class="browser-default validate select2" id="empleado_id_auxiliar" name="empleado_id_auxiliar" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                @foreach($empleados as $empleado)
                                    @php
                                        //$sinodalId = $grupo_equivalente->empleado_id_auxiliar;
                                        if (!$grupo_equivalente)
                                            {
                                            $sinodalId = $grupo->empleado_id_auxiliar;
                                            }
                                        else
                                            {
                                            $sinodalId = $grupo_equivalente->empleado_id_auxiliar;
                                            }
                                    @endphp

                                    <option value="{{$empleado->id}}" @if($sinodalId == $empleado->id) {{ 'selected' }} @endif>
                                        {{$empleado->id ." - ".$empleado->empNombre
                                            ." ". $empleado->empApellido1
                                            ." ". $empleado->empApellido2}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="block" style="width: 100%; height: 65px; position: absolute; top:0; left:0; display:none;"></div>
                        </div>
                    </div>
                </div>

                <div class="row" style="display: none">
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroFolio', $grupo->gpoNumeroFolio, array('id' => 'gpoNumeroFolio', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('gpoNumeroFolio', 'Folio', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroActa', $grupo->gpoNumeroActa, array('id' => 'gpoNumeroActa', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('gpoNumeroActa', 'Acta', array('class' => '')); !!}
                        </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                        {!! Form::text('gpoNumeroLibro', $grupo->gpoNumeroLibro, array('id' => 'gpoNumeroLibro', 'class' => 'validate','maxlength'=>'6')) !!}
                        {!! Form::label('gpoNumeroLibro', 'Libro', array('class' => '')); !!}
                        </div>
                    </div>
                </div>

            </div>


          </div>
          <div class="row">
            <div class="col s12 m12 l12">
                <div class="card-action">
                    {!! Form::button('<i class="material-icons left">save</i> Guardar', ['display' => 'inline-block', 'class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}

                </div>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>

    {{-- @include('modales.equivalentes') --}}

    @endsection

    @section('footer_scripts')

        <script type="text/javascript">
            $(document).ready(function() {

                $("#ubicacion_id").change( event => {
                    $("#departamento_id").empty();
                    $("#escuela_id").empty();
                    $("#periodo_id").empty();
                    $("#programa_id").empty();
                    $("#plan_id").empty();
                    $("#cgt_id").empty();
                    $("#materia_id").empty();
                    $("#departamento_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                    $("#escuela_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                    $("#periodo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                    $("#programa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                    $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                    $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                    $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

                    $("#perFechaInicial").val('');
                    $("#perFechaFinal").val('');
                    $.get(base_url+`/api/departamentos/${event.target.value}`,function(res,sta){
                        res.forEach(element => {
                            $("#departamento_id").append(`<option value=${element.id}>${element.depClave}-${element.depNombre}</option>`);
                        });
                    });
                });
            });
        </script>


        @include('secundaria.scripts.escuelas')
        @include('secundaria.scripts.programas')
        @include('secundaria.scripts.planes')
        @include('secundaria.scripts.periodos')
        @include('secundaria.scripts.cgts')
        @include('secundaria.scripts.grados')
        @include('secundaria.scripts.materias')
        @include('secundaria.scripts.grupos')

    @endsection
