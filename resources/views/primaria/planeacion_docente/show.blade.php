@extends('layouts.dashboard')

@section('template_title')
Primaria planeacion docente
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
<a href="{{url('primaria_planeacion_docente')}}" class="breadcrumb">Lista de planeación docente</a>
<a href="{{url('primaria_planeacion_docente/'.$primaria_grupos_planeaciones->id)}}" class="breadcrumb">Ver planeación docente</a>

@endsection

@section('content')

<style type="text/css">
    input[type="radio"] {
        margin-left: 10px;
    }
</style>
<div class="row">
    <div class="col s12 ">
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">PLANEACIÓN DOCENTE #{{$primaria_grupos_planeaciones->id}}</span>

                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">General</a></li>
                            <li class="tab"><a href="#temas">Temas</a></li>

                        </ul>
                    </div>
                </nav>

                {{-- GENERAL BAR--}}
                <div id="general">

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('ubicacion_id', 'Ubicación *', array('class' => '')); !!}
                            <input type="text" value="{{$ubicacion_grupo->ubiNombre}}" readonly>                         
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                            <input type="text" value="{{$departamento_grupo->depNombre}}" readonly>                           
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                            <input type="text" value="{{$escuela_grupo->escNombre}}" readonly> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('periodo_id', 'Periodo *', array('class' => '')); !!}
                            <input type="text" value="{{$periodo_grupo->perNumero}}-{{$periodo_grupo->perAnioPago}}" readonly> 
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('perFechaInicial', $periodo_grupo->perFechaInicial, array('id' => 'perFechaInicial', 'class' =>
                                'validate','readonly')) !!}
                                {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('perFechaFinal', $periodo_grupo->perFechaFinal, array('id' => 'perFechaFinal', 'class' =>
                                'validate','readonly')) !!}
                                {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                            <input type="text" value="{{$programa_grupo->progNombre}}" readonly>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                            <input type="text" value="{{$plan_grupo->planClave}}" readonly>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('gpoGrado', 'Grado *', array('class' => '')); !!}
                            <input type="text" value="{{$grupo->gpoGrado}}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('primaria_grupo_id', 'Grupo *', array('class' => '')); !!}
                            <input type="text" value="{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}, Materia: {{$primaria_materia->matClave}}-{{$primaria_materia->matNombre}}" readonly>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('semana_inicio', 'Semana inicio *', array('class' => '')); !!}
                            <input type="date" name="semana_inicio" id="semana_inicio" value="{{\Carbon\Carbon::parse($primaria_grupos_planeaciones->semana_inicio)->format('Y-m-d')}}" readonly>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('semana_fin', 'Semana fin *', array('class' => '')); !!}
                            <input type="date" name="semana_fin" id="semana_fin" value="{{\Carbon\Carbon::parse($primaria_grupos_planeaciones->semana_fin)->format('Y-m-d')}}" readonly>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 l4">
                        {!! Form::label('mes', 'Mes *', array('class' => '')); !!}
                           <input type="text" value="{{$primaria_grupos_planeaciones->mes}}" readonly>
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="frase_mes">Frase del mes *</label>
                            {!! Form::textarea('frase_mes', $primaria_grupos_planeaciones->frase_mes, array('id' => 'observacion_contenido', 'readonly' => 'true', 'style' => 'resize: none')) !!} 
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="valor_mes">Valor del mes *</label>
                            {!! Form::textarea('valor_mes', $primaria_grupos_planeaciones->valor_mes, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!} 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <label for="norma_urbanidad">Norma de urbanidad *</label>
                            {!! Form::textarea('norma_urbanidad', $primaria_grupos_planeaciones->norma_urbanidad, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!} 
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="objetivo_general">Objetivo general *</label>
                            {!! Form::textarea('objetivo_general', $primaria_grupos_planeaciones->objetivo_general, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!} 
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="objetivo_particular">Objetivo particular *</label>
                            {!! Form::textarea('objetivo_particular', $primaria_grupos_planeaciones->objetivo_particular, array('id' => 'observacion_contenido', 'class' => 'validate', 'notas_observaciones',
                            'style' => 'resize: none', 'readonly' => 'true')) !!} 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('bloque', $primaria_grupos_planeaciones->bloque, array('id' => 'bloque', 'required', 'readonly' => 'true')) !!}
                                {!! Form::label('bloque', 'Bloque *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="notas_observaciones">Notas/Observaciones *</label>
                            {!! Form::textarea('notas_observaciones', $primaria_grupos_planeaciones->notas_observaciones, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!} 
                        </div>
                    </div>
                   

                </div>

                <div id="temas">               
                  
                    <br>
                    @foreach ($primaria_grupos_planeaciones_temas as $item)
                    <div class="row" style="background-color:#ECECEC;">
                        <p style="text-align: center;font-size:1.2em;">TEMA</p>
                    </div>
                    <div class="row">
                        <input type="hidden" name="planeacion_id[]" value="{{$item->id}}">
                        <div class="col s12 m6 l4">
                            <label for="tema">Conocimientos (Tema) *</label>
                            {!! Form::textarea('tema[]', $item->tema, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!}
                        </div>
                    
                        <div class="col s12 m6 l4">
                            <label for="objetivo">AprendizajAprendizaje esperado e esperado *</label>
                            {!! Form::textarea('objetivo[]', $item->objetivo, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!}
                        </div>
                    
                        <div class="col s12 m6 l4">
                            <label for="estrategias">Estrategias y secuencia didáctica *</label>
                            {!! Form::textarea('estrategias[]', $item->estrategias, array('id' => 'observacion_contenido', 'class' => 'validate', 'required', 'style' => 'resize: none', 'readonly' => 'true')) !!}
                        </div>

                        
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <label for="libro">Libro *</label>
                            {!! Form::textarea('libro2[]', $item->libros, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!}
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="habilidad">Habilidad o conocimiento aplicado *</label>
                            {!! Form::textarea('habilidad[]', $item->habilidad, array('id' => 'observacion_contenido', 'class' => 'validate',
                            'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!}
                        </div>
                    
                        <div class="col s12 m6 l4">
                            <label for="evaluacion">Evaluacion *</label>
                            {!! Form::textarea('evaluacion[]', $item->evaluacion, array('id' => 'observacion_contenido', 'class' => 'validate',
                            'required',
                            'style' => 'resize: none', 'readonly' => 'true')) !!}
                        </div>
                    </div>
                    @endforeach                    
                </div>

            </div>
        </div>
    </div>

    
    @endsection

    @section('footer_scripts')
   
 


    @endsection