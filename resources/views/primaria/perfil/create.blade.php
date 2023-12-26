@extends('layouts.dashboard')

@section('template_title')
    Primaria perfil
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria.primaria_perfil.index')}}" class="breadcrumb">Lista de perfiles</a>
    <a href="{{route('primaria.primaria_perfil.create')}}" class="breadcrumb">Agregar perfil</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_grupo.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR PERFIL</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                  {{-- <li class="tab"><a href="#equivalente">Equivalente</a></li> --}}
                </ul>
              </div>
            </nav>

            @php
                $num_mat = 1;
                $num_ESP = 1;
                $num_parti_clase = 1;
                $num_tareas = 1;
                $num_asis = 1;
                $num_convi = 1;
                $num_lim = 1;
            @endphp
            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">

                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id" style="font-size: 13px;">No.</th>
                                <th data-field="name" style="font-size: 13px;">CONTENIDOS FUNDAMENTALES DE  MATEMÁTICAS</th>
                                <th data-field="price" style="font-size: 13px;">CALIFICADOR</th>
                                <th data-field="" style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contenido_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->categoria == "Competencias curriculares de Matemáticas")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_mat++}}
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate select2" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>                                       
                                @endif
                            @endforeach                                                    
                        </tbody>
                    </table>
                    

                    {{-- Español  --}}
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id" style="font-size: 13px;">No.</th>
                                <th data-field="name" style="font-size: 13px;">CONTENIDOS FUNDAMENTALES DE  ESPAÑOL</th>
                                <th data-field="price" style="font-size: 13px;">CALIFICADOR</th>
                                <th data-field="" style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contenido_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->categoria == "Competencias curriculares de Español")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_ESP++}}
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>                                       
                                @endif
                            @endforeach                                                    
                        </tbody>
                    </table>


                    {{-- Participacion en clase  --}}              
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id" style="font-size: 13px;">No.</th>
                                <th data-field="name" style="font-size: 13px;">PARTICIPACIÓN EN CLASES</th>
                                <th data-field="price" style="font-size: 13px;">CALIFICADOR</th>
                                <th data-field="" style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contenido_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->categoria == "Participación en clase")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_parti_clase++}}
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>                                       
                                @endif
                            @endforeach                                                    
                        </tbody>
                    </table>

                    {{-- TAREAS  --}}
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id" style="font-size: 13px;">No.</th>
                                <th data-field="name" style="font-size: 13px;">TAREAS</th>
                                <th data-field="price" style="font-size: 13px;">CALIFICADOR</th>
                                <th data-field="" style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contenido_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->categoria == "Tareas")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_tareas++}}
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>                                       
                                @endif
                            @endforeach                                                    
                        </tbody>
                    </table>


                    {{-- Asistencia y puntualidad --}}
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id" style="font-size: 13px;">No.</th>
                                <th data-field="name" style="font-size: 13px;">ASISTENCIA Y PUNTUALIDAD</th>
                                <th data-field="price" style="font-size: 13px;">CALIFICADOR</th>
                                <th data-field="" style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contenido_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->categoria == "Asistencia y puntualidad")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_asis++}}
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>                                       
                                @endif
                            @endforeach                                                    
                        </tbody>
                    </table>
                

                    {{-- Convivencia escolar --}}
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id" style="font-size: 13px;">No.</th>
                                <th data-field="name" style="font-size: 13px;">CONVIVENCIA ESCOLAR</th>
                                <th data-field="price" style="font-size: 13px;">CALIFICADOR</th>
                                <th data-field="" style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contenido_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->categoria == "Convivencia escolar")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_convi++}}
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>                                       
                                @endif
                            @endforeach                                                    
                        </tbody>
                    </table>

                    {{-- Limpieza y orden --}}
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id" style="font-size: 13px;">No.</th>
                                <th data-field="name" style="font-size: 13px;">LIMPIEZA Y ORDEN</th>
                                <th data-field="price" style="font-size: 13px;">CALIFICADOR</th>
                                <th data-field="" style="font-size: 13px;">OBSERVACION PARTICULAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contenido_fundamentales as $contenido_fundamental)
                                @if ($contenido_fundamental->categoria == "Limpieza y orden")
                                <tr>
                                    <td style="font-size: 13px;">
                                        {{$num_lim++}}
                                        <input type="hidden" name="contenido_fundamental_id[]" id="contenido_fundamental_id" value="{{$contenido_fundamental->id}}">
                                    </td>
                                    <td style="width: 350px; font-size: 13px;">{{$contenido_fundamental->contenido}}</td>
                                    <td style="width: 200px; font-size: 13px;">
                                        <select id="calificador_id" data-departamento-idold="{{old('calificador_id')}}" class="browser-default validate" name="calificador_id[]" style="width: 100%;">
                                            <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                                            @foreach ($calificadores as $calificador)
                                                <option value="{{$calificador->id}}">{{$calificador->calificador}}</option>
                                            @endforeach
                                        </select>                                            
                                    </td>
                                    <td style="font-size: 13px;">
                                        {{-- <input type="text" name="observacion[]" id="observacion"> --}}
                                        <textarea name="observacion[]" style="font-size: 13px; resize: none;" class="validate" id="observacion" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>                                       
                                @endif
                            @endforeach                                                    
                        </tbody>
                    </table>
                  
                </div>
               
                
            </div>



          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-guardar btn-large waves-effect  darken-3']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
</div>



@endsection

@section('footer_scripts')


@endsection
