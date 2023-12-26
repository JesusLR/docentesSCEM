@extends('layouts.dashboard')

@section('template_title')
Primaria planeacion docente
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
<a href="{{url('primaria_planeacion_docente')}}" class="breadcrumb">Lista de planeación docente</a>
<a href="{{url('primaria_planeacion_docente/'.$primaria_grupos_planeaciones->id.'/edit')}}" class="breadcrumb">Editar planeación docente</a>

@endsection

@section('content')

<style type="text/css">
    input[type="radio"] {
        margin-left: 10px;
    }
</style>
<div class="row">
    <div class="col s12 ">
        {{ Form::open(array('method'=>'PUT','route' => ['primaria.primaria_planeacion_docente.update', $primaria_grupos_planeaciones->id])) }}        
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR PLANEACIÓN DOCENTE #{{$primaria_grupos_planeaciones->id}}</span>

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
                            <select id="ubicacion_id" class="browser-default validate select2" required
                                name="ubicacion_id" style="width: 100%;">
                                <option value="{{$ubicacion_grupo->id}}">{{$ubicacion_grupo->ubiNombre}}</option>
                               
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                            <select id="departamento_id" class="browser-default validate select2" required
                                name="departamento_id" style="width: 100%;">
                                <option value="{{$departamento_grupo->id}}">{{$departamento_grupo->depNombre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                            <select id="escuela_id" class="browser-default validate select2" required name="escuela_id"
                                style="width: 100%;">
                                <option value="{{$escuela_grupo->id}}">{{$escuela_grupo->escNombre}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('periodo_id', 'Periodo *', array('class' => '')); !!}
                            <select id="periodo_id" class="browser-default validate select2" required name="periodo_id"
                                style="width: 100%;">
                                    <option value="{{$periodo_grupo->id}}">{{$periodo_grupo->perNumero}}-{{$periodo_grupo->perAnioPago}}</option>
                            </select>
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
                            <select id="programa_id" class="browser-default validate select2" required
                                name="programa_id" style="width: 100%;">
                                <option value="{{$programa_grupo->id}}">{{$programa_grupo->progNombre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                            <select id="plan_id" class="browser-default validate select2" required name="plan_id"
                                style="width: 100%;">
                                    <option value="{{$plan_grupo->id}}">{{$plan_grupo->planClave}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('gpoGrado', 'Grado *', array('class' => '')); !!}
                            <select id="gpoGrado" class="browser-default validate select2" required name="gpoGrado"
                                style="width: 100%;">
                                @foreach ($grados as $grado)
                                    <option value="{{$grado->gpoGrado}}" {{ $grupo->gpoGrado == $grado->gpoGrado ? 'selected' : '' }}>{{$grado->gpoGrado}}</option>
                                @endforeach                                
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('primaria_grupo_id', 'Grupo *', array('class' => '')); !!}
                            <select id="primaria_grupo_id" class="browser-default validate select2" required name="primaria_grupo_id"
                                style="width: 100%;">
                                {{--  <option value="{{$primaria_grupos_planeaciones->primaria_grupo_id}}">{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}, Materia: {{$grupo->matClave}}-{{$grupo->matNombre}}</option>  --}}
                                @foreach ($grupos as $grupo)
                                    <option value="{{$grupo->id}}" {{ $primaria_grupos_planeaciones->primaria_grupo_id == $grupo->id ? 'selected' : '' }}>{{$grupo->gpoGrado}}-{{$grupo->gpoClave}}, Materia: {{$grupo->matClave}}-{{$grupo->matNombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('semana_inicio', 'Semana inicio *', array('class' => '')); !!}
                            <input type="date" name="semana_inicio" id="semana_inicio" value="{{\Carbon\Carbon::parse($primaria_grupos_planeaciones->semana_inicio)->format('Y-m-d')}}" required>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('semana_fin', 'Semana fin *', array('class' => '')); !!}
                            <input type="date" name="semana_fin" id="semana_fin" value="{{\Carbon\Carbon::parse($primaria_grupos_planeaciones->semana_fin)->format('Y-m-d')}}" required>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 l4">
                        {!! Form::label('mes', 'Mes *', array('class' => '')); !!}
                            <select id="mes" class="browser-default validate select2" required name="mes" style="width: 100%;">
                                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                <option value="Septiembre" {{ $primaria_grupos_planeaciones->mes == "Septiembre" ? 'selected' : '' }}>SEPTIEMBRE</option>
                                <option value="Octubre" {{ $primaria_grupos_planeaciones->mes == "Octubre" ? 'selected' : '' }}>OCTUBRE</option>
                                <option value="Noviembre" {{ $primaria_grupos_planeaciones->mes == "Noviembre" ? 'selected' : '' }}>NOVIEMBRE</option>
                                <option value="Diciembre" {{ $primaria_grupos_planeaciones->mes == "Diciembre" ? 'selected' : '' }}>DICIEMBRE</option>
                                <option value="Enero" {{ $primaria_grupos_planeaciones->mes == "Enero" ? 'selected' : '' }}>ENERO</option>
                                <option value="Febrero" {{ $primaria_grupos_planeaciones->mes == "Febrero" ? 'selected' : '' }}>FEBRERO</option>
                                <option value="Marzo" {{ $primaria_grupos_planeaciones->mes == "Marzo" ? 'selected' : '' }}>MARZO</option>
                                <option value="Abril" {{ $primaria_grupos_planeaciones->mes == "Abril" ? 'selected' : '' }}>ABRIL</option>
                                <option value="Mayo" {{ $primaria_grupos_planeaciones->mes == "Mayo" ? 'selected' : '' }}>MAYO</option>
                                <option value="Junio" {{ $primaria_grupos_planeaciones->mes == "Junio" ? 'selected' : '' }}>JUNIO</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="frase_mes">Frase del mes *</label>
                            {!! Form::textarea('frase_mes', $primaria_grupos_planeaciones->frase_mes, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none')) !!} 
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="valor_mes">Valor del mes *</label>
                            {!! Form::textarea('valor_mes', $primaria_grupos_planeaciones->valor_mes, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none')) !!} 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <label for="norma_urbanidad">Norma de urbanidad *</label>
                            {!! Form::textarea('norma_urbanidad', $primaria_grupos_planeaciones->norma_urbanidad, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none')) !!} 
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="objetivo_general">Objetivo general *</label>
                            {!! Form::textarea('objetivo_general', $primaria_grupos_planeaciones->objetivo_general, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none')) !!} 
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="objetivo_particular">Objetivo particular *</label>
                            {!! Form::textarea('objetivo_particular', $primaria_grupos_planeaciones->objetivo_particular, array('id' => 'observacion_contenido', 'class' => 'validate', 'notas_observaciones',
                            'style' => 'resize: none')) !!} 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('bloque', $primaria_grupos_planeaciones->bloque, array('id' => 'bloque', 'required')) !!}
                                {!! Form::label('bloque', 'Bloque *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="notas_observaciones">Notas/Observaciones *</label>
                            {!! Form::textarea('notas_observaciones', $primaria_grupos_planeaciones->notas_observaciones, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none')) !!} 
                        </div>
                    </div>
                   

                </div>

                <div id="temas">               
                    <br>
                    <div>
                        <a href="javascript:void(0);" class="agregar-pregunta btn-large waves-effect  darken-3"><i
                                class="material-icons left">add</i>Nuevo</a>
                    </div>
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
                            'style' => 'resize: none')) !!}
                        </div>
                    
                        <div class="col s12 m6 l4">
                            <label for="objetivo">AprendizajAprendizaje esperado e esperado *</label>
                            {!! Form::textarea('objetivo[]', $item->objetivo, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none')) !!}
                        </div>
                    
                        <div class="col s12 m6 l4">
                            <label for="estrategias">Estrategias y secuencia didáctica *</label>
                            {!! Form::textarea('estrategias[]', $item->estrategias, array('id' => 'observacion_contenido', 'class' => 'validate', 'required', 'style' => 'resize: none')) !!}
                        </div>

                        
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <label for="libro">Libro *</label>
                            {!! Form::textarea('libro[]', $item->libros, array('id' => 'observacion_contenido', 'class' => 'validate', 'required',
                            'style' => 'resize: none')) !!}
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="habilidad">Habilidad o conocimiento aplicado *</label>
                            {!! Form::textarea('habilidad[]', $item->habilidad, array('id' => 'observacion_contenido', 'class' => 'validate',
                            'required',
                            'style' => 'resize: none')) !!}
                        </div>
                    
                        <div class="col s12 m6 l4">
                            <label for="evaluacion">Evaluacion *</label>
                            {!! Form::textarea('evaluacion[]', $item->evaluacion, array('id' => 'observacion_contenido', 'class' => 'validate',
                            'required',
                            'style' => 'resize: none')) !!}
                        </div>
                    </div>
                    @endforeach                    
                </div>

                <div class="contenedor-temas">
                        
                </div>
                <div class="card-action">
                    {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large
                    waves-effect darken-3 submit-button','type' => 'submit']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    
    @endsection

    @section('footer_scripts')
   
    <script type="text/javascript">
        $(document).ready(function(){
            var addButton = $('.agregar-pregunta'); // Agregar selector de botón
            var wrapper = $('.contenedor-temas'); // Contenedor de campo de entrada
            var fieldHTML = ''+
            '<div>'+
                '<br>'+
                '<div class="row" style="background-color:#ECECEC;">'+
                    '<p style="text-align: center;font-size:1.2em;">TEMA</p>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col s12 m6 l4">'+
                        '<label for="tema2">Conocimientos (Tema) *</label>' +                    
                        '{!! Form::textarea("tema2[]", NULL, array("id" => "observacion_contenido", "class" => "validate", "required", "style" => "resize: none")) !!}'+                
                    '</div>'+

                    '<div class="col s12 m6 l4">'+
                        '<label for="objetivo2">Aprendizaje esperado (objetivo) *</label>'+
                        '{!! Form::textarea("objetivo2[]", NULL, array("id" => "observacion_contenido", "class" => "validate", "required", "style" => "resize: none")) !!} '+
                    '</div>'+

                    '<div class="col s12 m6 l4">'+
                        '<label for="estrategias">Estrategias y secuencia didáctica *</label>'+
                        '{!! Form::textarea("estrategias2[]", NULL, array("id" => "observacion_contenido", "class" => "validate", "required", "style" => "resize: none")) !!}'+
                    '</div>'+

                    
                '</div>'+
                '<div class="row">'+
                    '<div class="col s12 m6 l4">'+
                        '<label for="libro2">Libro *</label>'+
                        '{!! Form::textarea("libro2[]", NULL, array("id" => "observacion_contenido", "class" => "validate", "required", "style" => "resize: none")) !!}'+
                    '</div>'+
                    
                    '<div class="col s12 m6 l4">'+
                        '<label for="habilidad">Habilidad o conocimiento aplicado *</label>'+
                        '{!! Form::textarea("habilidad2[]", NULL, array("id" => "observacion_contenido", "class" => "validate", "required", "style" => "resize: none")) !!}' +
                    '</div>'+

                    '<div class="col s12 m6 l4">'+
                        '<label for="evaluacion">Evaluacion *</label>'+
                        '{!! Form::textarea("evaluacion2[]", NULL, array("id" => "observacion_contenido", "class" => "validate", "required", "style" => "resize: none")) !!}' +
                    '</div>'+
                '</div>'+
                '<a style="width:10px; height:60px; " href="javascript:void(0);" class="remove_button btn-large waves-effect  darken-3"><i class="material-icons center">delete</i></a>'+
                '<br>'+
            '</div>';
            var x = 1; // El contador de campo inicial es 1
            $(addButton).click(function(){  // Una vez que se hace clic en el botón Agregar
                    x++; // Incremento del contador de campo
                    $(wrapper).append(fieldHTML); // Agregar campo html      
       
            });
            $(wrapper).on('click', '.remove_button', function(e){// Una vez que se hace clic en el botón Eliminar
                e.preventDefault();
                $(this).parent('div').remove(); // Eliminar el campo html
                x--; // Disminuir el contador de campo
            });
        });
    
    
    </script>


    @endsection