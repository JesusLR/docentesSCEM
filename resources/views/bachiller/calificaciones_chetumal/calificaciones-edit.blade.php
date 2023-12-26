@extends('layouts.dashboard')

@section('template_title')
Bachiller calificaciones
@endsection

@section('head')
{!! HTML::style(asset('vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' =>
'stylesheet')) !!}
@endsection

@section('breadcrumbs')
<a href="{{url('bachiller_curso')}}" class="breadcrumb">Inicio</a>
<a href="{{route('bachiller.bachiller_grupo_seq.index')}}" class="breadcrumb">Lista de Grupo</a>
<a href="{{url('bachiller_calificacion_seq/grupo/'.$bachiller_cch_inscritos2[0]->bachiller_grupo_id.'/edit')}}" class="breadcrumb">Editar calificación</a>
@endsection

@section('content')

<div class="row">
    <div class="col s12 ">
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">EDITAR CALIFICACIONES GRUPO #{{$bachiller_cch_inscritos2[0]->bachiller_grupo_id}}</span>
                
                {{-- NAVIGATION BAR--}}
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab"><a class="active" href="#general">Ordinarios</a></li>
                            <li class="tab"><a class="active" href="#recuperativos">Recuperativos</a></li>
                            <li class="tab"><a class="active" href="#extraRegular">Extra regular</a></li>
                            <li class="tab"><a class="active" href="#especialOglobal">Especial</a></li>

                        </ul>
                    </div>
                </nav>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <p style="font-size: 18px;"><b>Período:</b> {{$bachiller_cch_inscritos2[0]->perNumero.'-'.$bachiller_cch_inscritos2[0]->perAnio}}</p>
                        <p style="font-size: 18px;"><b>Semestre-Grupo:</b> {{$bachiller_cch_inscritos2[0]->gpoGrado}}-{{$bachiller_cch_inscritos2[0]->gpoClave}}</p>
                        <p style="font-size: 18px;"><b>Grupo materia:</b> {{$bachiller_cch_inscritos2[0]->matNombre}} @if ($bachiller_cch_inscritos2[0]->gpoMatComplementaria != "") - {{$bachiller_cch_inscritos2[0]->gpoMatComplementaria}} @endif</p>
                        <p style="font-size: 18px;">
                            @php
                                if($bachiller_cch_inscritos2[0]->empApellido1 == ""){
                                    $apellido1 = "";
                                }else{
                                    $apellido1 = $bachiller_cch_inscritos2[0]->empApellido1;
                                }

                                if($bachiller_cch_inscritos2[0]->empApellido2 == ""){
                                    $apellido2 = "";
                                }else{
                                    $apellido2 = $bachiller_cch_inscritos2[0]->empApellido2;
                                }

                                if($bachiller_cch_inscritos2[0]->empNombre == ""){
                                    $nombre = "";
                                }else{
                                    $nombre = $bachiller_cch_inscritos2[0]->empNombre;
                                }
                                
                            @endphp
                            <b>Docente:</b> {{$apellido1.' '.$apellido2.' '.$nombre}}
                        </p>
                    </div>
                </div>
                {{-- GENERAL BAR--}}
                <div id="general">
                    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller.bachiller_calificacion_seq.calificaciones.update_calificacion_ordinarios', 'method' => 'POST']) !!}


                        <input type="hidden" id="bachiller_cch_grupo_id" name="bachiller_cch_grupo_id" value="{{$bachiller_cch_inscritos2[0]->bachiller_grupo_id}}">

                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="1">NO</th>
                                    <th colspan="1">CLAVE PAGO</th>
                                    <th style="display: none;" colspan="1">CURP</th>
                                    <th colspan="1">ALUMNO</th>
                                    {{--  <th align="center" colspan="4">EVALUACIONES</th>  --}}
                                    <th>EVAL 1</th>
                                    <th>EVAL 2</th>
                                    <th>EVAL 3</th>
                                    <th>EVAL 4</th>
                                    <th style="display: none;" colspan="1">ID INSCRITO</th>
                                    <th style="display: none;" colspan="1">calificando</th>

                                </tr>
                                {{--  <tr>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th>EVAL 1</th>
                                    <th>EVAL 2</th>
                                    <th>EVAL 3</th>
                                    <th>EVAL 4</th>
                                </tr>  --}}
                            </thead>
                            <tbody>
                                @php
                                    $total = 1;
                                    $select2 = "select2";
                                @endphp

                                {{--  Si es numerico entra aqui   --}}
                                @if ($bachiller_cch_inscritos2[0]->matTipoAcreditacion == "N")
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                        <tr>
                                            <td>{{$total++}}</td>
                                            <td>{{$item->aluClave}}</td>
                                            <td style="display: none;">{{$item->perCurp}}</td>
                                            <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                            {{--  parcial 1  --}}
                                            @if ($item->insCalificacionOrdinarioParcial1 != "")
                                                @if ($item->insCalificacionOrdinarioParcial1 >= 6)
                                                    <td><input tabindex="1" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" value="{{$item->insCalificacionOrdinarioParcial1}}"></td>
                                                @else
                                                    <td><b><input tabindex="1" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" value="{{$item->insCalificacionOrdinarioParcial1}}"></b></td>
                                                @endif
                                            @else
                                                <td><input tabindex="1" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" value="{{$item->insCalificacionOrdinarioParcial1}}"></td>
                                            @endif
                                            

                                            {{--  parcial 2  --}}
                                            @if ($item->insCalificacionOrdinarioParcial2 != "")
                                                @if ($item->insCalificacionOrdinarioParcial2 >= 6)
                                                    <td><input tabindex="2" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial2" name="insCalificacionOrdinarioParcial2[]" value="{{$item->insCalificacionOrdinarioParcial2}}"></td>
                                                @else
                                                    <td><b><input tabindex="2" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial2" name="insCalificacionOrdinarioParcial2[]" value="{{$item->insCalificacionOrdinarioParcial2}}"></b></td>
                                                @endif
                                            @else
                                                <td><input tabindex="2" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial2" name="insCalificacionOrdinarioParcial2[]" value="{{$item->insCalificacionOrdinarioParcial2}}"></td>
                                            @endif


                                            {{--  parcial 3  --}}
                                            @if ($item->insCalificacionOrdinarioParcial3 != "")
                                                @if ($item->insCalificacionOrdinarioParcial3 >= 6)
                                                    <td><input tabindex="3" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial3" name="insCalificacionOrdinarioParcial3[]" value="{{$item->insCalificacionOrdinarioParcial3}}"></td>
                                                @else
                                                    <td><b><input tabindex="3" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial3" name="insCalificacionOrdinarioParcial3[]" value="{{$item->insCalificacionOrdinarioParcial3}}"></b></td>
                                                @endif
                                            @else
                                                <td><input tabindex="3" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial3" name="insCalificacionOrdinarioParcial3[]" value="{{$item->insCalificacionOrdinarioParcial3}}"></td>
                                            @endif


                                            {{--  parcial 4  --}}
                                            @if ($item->insCalificacionOrdinarioParcial4 != "")
                                                @if ($item->insCalificacionOrdinarioParcial4 >= 6)
                                                    <td><input tabindex="4" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial4" name="insCalificacionOrdinarioParcial4[]" value="{{$item->insCalificacionOrdinarioParcial4}}"></td>
                                                @else
                                                    <td><b><input tabindex="4" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial4" name="insCalificacionOrdinarioParcial4[]" value="{{$item->insCalificacionOrdinarioParcial4}}"></b></td>
                                                @endif
                                            @else
                                                <td><input tabindex="4" style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial4" name="insCalificacionOrdinarioParcial4[]" value="{{$item->insCalificacionOrdinarioParcial4}}"></td>
                                            @endif



                                            <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                            <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="parciales_ordinarios"></td>

                                        </tr>                                
                                    @endforeach
                                @else
                                    {{--  si es alfanumerico entra   --}}
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                        <tr>
                                            <td>{{$total++}}</td>
                                            <td>{{$item->aluClave}}</td>
                                            <td style="display: none;">{{$item->perCurp}}</td>
                                            <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                            {{--  parcial 1  --}}
                                            @if ($item->insCalificacionOrdinarioParcial1 != "")
                                                @if ($item->insCalificacionOrdinarioParcial1 == -1)
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial1[]" id="insCalificacionOrdinarioParcial1_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1" selected>A</option>
                                                            <option value="-2">NA</option>
                                                        </select>
                                                    </td>
                                                @else
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial1[]" id="insCalificacionOrdinarioParcial1_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1">A</option>
                                                            <option value="-2" selected>NA</option>
                                                        </select>
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial1[]" id="insCalificacionOrdinarioParcial1_{{$item->id}}">
                                                        <option value="" selected>SELECCIONE</option>
                                                        <option value="-1">A</option>
                                                        <option value="-2">NA</option>
                                                    </select>
                                                </td>
                                            @endif
                                            

                                            {{--  parcial 2  --}}
                                            @if ($item->insCalificacionOrdinarioParcial2 != "")
                                                @if ($item->insCalificacionOrdinarioParcial2 == -1)
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial2[]" id="insCalificacionOrdinarioParcial2_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1" selected>A</option>
                                                            <option value="-2">NA</option>
                                                        </select>
                                                    </td>
                                                @else
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial2[]" id="insCalificacionOrdinarioParcial2_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1">A</option>
                                                            <option value="-2" selected>NA</option>
                                                        </select>
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial2[]" id="insCalificacionOrdinarioParcial2_{{$item->id}}">
                                                        <option value="" selected>SELECCIONE</option>
                                                        <option value="-1">A</option>
                                                        <option value="-2">NA</option>
                                                    </select>
                                                </td>
                                            @endif


                                            {{--  parcial 3  --}}
                                            @if ($item->insCalificacionOrdinarioParcial3 != "")
                                                @if ($item->insCalificacionOrdinarioParcial3 == -1)
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial3[]" id="insCalificacionOrdinarioParcial3_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1" selected>A</option>
                                                            <option value="-2">NA</option>
                                                        </select>
                                                    </td>
                                                @else
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial3[]" id="insCalificacionOrdinarioParcial3_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1">A</option>
                                                            <option value="-2" selected>NA</option>
                                                        </select>
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial3[]" id="insCalificacionOrdinarioParcial3_{{$item->id}}">
                                                        <option value="" selected>SELECCIONE</option>
                                                        <option value="-1">A</option>
                                                        <option value="-2">NA</option>
                                                    </select>
                                                </td>
                                            @endif


                                            {{--  parcial 4  --}}
                                            @if ($item->insCalificacionOrdinarioParcial4 != "")
                                                @if ($item->insCalificacionOrdinarioParcial4 == -1)
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial4[]" id="insCalificacionOrdinarioParcial4_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1" selected>A</option>
                                                            <option value="-2">NA</option>
                                                        </select>
                                                    </td>
                                                @else
                                                    <td>
                                                        <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial4[]" id="insCalificacionOrdinarioParcial4_{{$item->id}}">
                                                            <option value="" selected>SELECCIONE</option>
                                                            <option value="-1">A</option>
                                                            <option value="-2" selected>NA</option>
                                                        </select>
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    <select style="width: 100%;" class="browser-default validate select2" name="insCalificacionOrdinarioParcial4[]" id="insCalificacionOrdinarioParcial4_{{$item->id}}">
                                                        <option value="" selected>SELECCIONE</option>
                                                        <option value="-1">A</option>
                                                        <option value="-2">NA</option>
                                                    </select>
                                                </td>
                                            @endif



                                            <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                            <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="parciales_ordinarios"></td>

                                        </tr>                                
                                    @endforeach
                                @endif
                                
                                
                            </tbody>
                        </table>

                        <div class="card-action">
                            {!! Form::button('<i class="material-icons left">save</i> Guardar',
                            ['onclick'=>'this=true;this.innerText="Actulizando datos...";this.form.submit(); alerta();','class' =>
                            'btn-large btn-save waves-effect darken-3 btn-guardar','type' => 'submit']) !!}
                        </div>
                   
                    {!! Form::close() !!}

                </div>

                <div id="recuperativos">
                    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller.bachiller_calificacion_seq.calificaciones.update_calificacion_recuperativos', 'method' => 'POST']) !!}


                        <input type="hidden" id="bachiller_cch_grupo_id" name="bachiller_cch_grupo_id" value="{{$bachiller_cch_inscritos2[0]->bachiller_grupo_id}}">

                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="1">NO</th>
                                    <th colspan="1">CLAVE PAGO</th>
                                    <th style="display: none;" colspan="1">CURP</th>
                                    <th colspan="1">ALUMNO</th>
                                    {{--  <th align="center" colspan="4">EVALUACIONES</th>  --}}
                                    <th>RECUPERATIVO EVAL 1</th>
                                    <th>RECUPERATIVO EVAL 2</th>
                                    <th>RECUPERATIVO EVAL 3</th>
                                    <th>RECUPERATIVO EVAL 4</th>
                                    <th style="display: none;" colspan="1">ID INSCRITO</th>
                                    <th style="display: none;" colspan="1">calificando</th>
                                    <th style="display: none;" colspan="1">tipo acreditacion</th>


                                </tr>
                                {{--  <tr>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th>EVAL 1</th>
                                    <th>EVAL 2</th>
                                    <th>EVAL 3</th>
                                    <th>EVAL 4</th>
                                </tr>  --}}
                            </thead>
                            <tbody>
                                @php
                                    $total = 1;
                                @endphp

                                {{--  Si es numerico entra aqui   --}}
                                @if ($bachiller_cch_inscritos2[0]->matTipoAcreditacion == "N")
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                        @if ($item->insCantidadReprobadasOrdinarioParciales == "1" || $item->insCantidadReprobadasOrdinarioParciales == "2" || $item->insEstaEnRecuperativo == "SI")
                                            <tr>
                                                <td>{{$total++}}</td>
                                                <td>{{$item->aluClave}}</td>
                                                <td style="display: none;">{{$item->perCurp}}</td>
                                                <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                                {{--  parcial 1  --}}                                               
                                                @if ($item->insAproboParcial1 == "SI")
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="{{$item->insCalificacionRecuperativoParcial1}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial1 >= 6)
                                                        <td><input tabindex="5" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="{{$item->insCalificacionRecuperativoParcial1}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                    @else
                                                        <td><input tabindex="5" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="{{$item->insCalificacionRecuperativoParcial1}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                    @endif

                                                @endif


                                                {{--  @if ($item->insCalificacionOrdinarioParcial1 != "")
                                                    @if ($item->insCalificacionOrdinarioParcial1 < 6)
                                                        <td><b><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="{{$item->insCalificacionRecuperativoParcial1}}"></b></td>
                                                    @else
                                                        <td><input readonly onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="{{$item->insCalificacionRecuperativoParcial1}}"></td>
                                                    @endif
                                                @else
                                                    <td><input style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="{{$item->insCalificacionRecuperativoParcial1}}"></td>
                                                @endif  --}}
                                                

                                                {{--  parcial 2  --}}
                                                @if ($item->insAproboParcial2 == "SI")
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="{{$item->insCalificacionRecuperativoParcial2}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial2 >= 6)
                                                        <td><input tabindex="6" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="{{$item->insCalificacionRecuperativoParcial2}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                    @else
                                                        <td><input tabindex="6" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="{{$item->insCalificacionRecuperativoParcial2}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                    @endif

                                                @endif
                                                {{--  @if ($item->insCalificacionOrdinarioParcial2 != "")
                                                    @if ($item->insCalificacionOrdinarioParcial2 < 6)
                                                        <td><b><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="{{$item->insCalificacionRecuperativoParcial2}}"></b></td>
                                                    @else
                                                        <td><input readonly onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="{{$item->insCalificacionRecuperativoParcial2}}"></td>
                                                    @endif
                                                @else
                                                    <td><input style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="{{$item->insCalificacionRecuperativoParcial2}}"></td>
                                                @endif  --}}


                                                {{--  parcial 3  --}}
                                                @if ($item->insAproboParcial3 == "SI")
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="{{$item->insCalificacionRecuperativoParcial3}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial3 >= 6)
                                                        <td><input tabindex="7" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="{{$item->insCalificacionRecuperativoParcial3}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                    @else
                                                        <td><input tabindex="7" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="{{$item->insCalificacionRecuperativoParcial3}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                    @endif

                                                @endif
                                                {{--  @if ($item->insCalificacionOrdinarioParcial3 != "")
                                                    @if ($item->insCalificacionOrdinarioParcial3 < 6)
                                                        <td><b><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="{{$item->insCalificacionRecuperativoParcial3}}"></b></td>
                                                    @else
                                                        <td><input readonly onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="{{$item->insCalificacionRecuperativoParcial3}}"></td>
                                                    @endif
                                                @else
                                                    <td><input style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="{{$item->insCalificacionRecuperativoParcial3}}"></td>
                                                @endif  --}}


                                                {{--  parcial 4  --}}
                                                @if ($item->insAproboParcial4 == "SI")
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="{{$item->insCalificacionRecuperativoParcial4}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial4 >= 6)
                                                        <td><input tabindex="8" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="{{$item->insCalificacionRecuperativoParcial4}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                    @else
                                                        <td><input tabindex="8" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="{{$item->insCalificacionRecuperativoParcial4}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                    @endif

                                                @endif
                                                {{--  @if ($item->insCalificacionOrdinarioParcial4 != "")
                                                    @if ($item->insCalificacionOrdinarioParcial4 < 6)
                                                        <td><b><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="{{$item->insCalificacionRecuperativoParcial4}}"></b></td>
                                                    @else
                                                        <td><input readonly onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="{{$item->insCalificacionRecuperativoParcial4}}"></td>
                                                    @endif
                                                @else
                                                    <td><input style="" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="{{$item->insCalificacionRecuperativoParcial4}}"></td>
                                                @endif  --}}


                                                <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                                <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="recuperativos"></td>

                                            </tr> 
                                        @endif                                                                      
                                    @endforeach
                                @else
                                    {{--  si es alfanumerico entra   --}}
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                        @if ($item->insCantidadReprobadasOrdinarioParciales == "1" || $item->insCantidadReprobadasOrdinarioParciales == "2" || $item->insEstaEnRecuperativo == "SI")
                                            <tr>
                                                <td>{{$total++}}</td>
                                                <td>{{$item->aluClave}}</td>
                                                <td style="display: none;">{{$item->perCurp}}</td>
                                                <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                                {{--  parcial 1  --}}                                               
                                                @if ($item->insAproboParcial1 == "SI")
                                                    @if ($item->insCalificacionRecuperativoParcial1 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial1 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial1[]" id="insCalificacionRecuperativoParcial1_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial1[]" id="insCalificacionRecuperativoParcial1_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial1[]" id="insCalificacionRecuperativoParcial1_{{$item->id}}_recuperativo">
                                                                <option value="" selected></option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>

                                                    @endif
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial1 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial1 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial1[]" id="insCalificacionRecuperativoParcial1_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial1[]" id="insCalificacionRecuperativoParcial1_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial1[]" id="insCalificacionRecuperativoParcial1_{{$item->id}}_recuperativo">
                                                                <option value="" selected>SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>

                                                    @endif
                                                    

                                                @endif
                                                

                                                {{--  parcial 2  --}}
                                                @if ($item->insAproboParcial2 == "SI")
                                                    @if ($item->insCalificacionRecuperativoParcial2 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial2 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial2[]" id="insCalificacionRecuperativoParcial2_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial2[]" id="insCalificacionRecuperativoParcial2_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial2[]" id="insCalificacionRecuperativoParcial2_{{$item->id}}_recuperativo">
                                                                <option value="" selected></option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>

                                                    @endif
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial2 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial2 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial2[]" id="insCalificacionRecuperativoParcial2_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial2[]" id="insCalificacionRecuperativoParcial2_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial2[]" id="insCalificacionRecuperativoParcial2_{{$item->id}}_recuperativo">
                                                                <option value="" selected>SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>

                                                    @endif                                                   

                                                @endif


                                                {{--  parcial 3  --}}
                                                @if ($item->insAproboParcial3 == "SI")
                                                    @if ($item->insCalificacionRecuperativoParcial3 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial3 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial3[]" id="insCalificacionRecuperativoParcial3_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial3[]" id="insCalificacionRecuperativoParcial3_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial3[]" id="insCalificacionRecuperativoParcial3_{{$item->id}}_recuperativo">
                                                                <option value="" selected></option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>

                                                    @endif
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial3 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial3 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial3[]" id="insCalificacionRecuperativoParcial3_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial3[]" id="insCalificacionRecuperativoParcial3_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial3[]" id="insCalificacionRecuperativoParcial3_{{$item->id}}_recuperativo">
                                                                <option value="" selected>SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>

                                                    @endif                                                   

                                                @endif


                                                {{--  parcial 4  --}}
                                                @if ($item->insAproboParcial4 == "SI")
                                                    @if ($item->insCalificacionRecuperativoParcial4 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial4 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial4[]" id="insCalificacionRecuperativoParcial4_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial4[]" id="insCalificacionRecuperativoParcial4_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial4[]" id="insCalificacionRecuperativoParcial4_{{$item->id}}_recuperativo">
                                                                <option value="" selected></option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>

                                                    @endif
                                                @else

                                                    @if ($item->insCalificacionRecuperativoParcial4 != "")
                                                        @if ($item->insCalificacionRecuperativoParcial4 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial4[]" id="insCalificacionRecuperativoParcial4_{{$item->id}}_recuperativo">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial4[]" id="insCalificacionRecuperativoParcial4_{{$item->id}}_recuperativo">
                                                                    <option value="" selected>SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionRecuperativoParcial4[]" id="insCalificacionRecuperativoParcial4_{{$item->id}}_recuperativo">
                                                                <option value="" selected>SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>

                                                    @endif                                                   

                                                @endif



                                                <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                                <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="recuperativos"></td>

                                                <td style="display: none;"><input class="noUpperCase" type="text" id="tipoacreditacion" name="tipoacreditacion" value="alfanumerico"></td>


                                            </tr>   
                                        @endif                                                                     
                                    @endforeach
                                @endif
                                
                                
                            </tbody>
                        </table>

                        <div class="card-action">
                            {!! Form::button('<i class="material-icons left">save</i> Guardar',
                            ['onclick'=>'this=true;this.innerText="Actulizando datos...";this.form.submit(); alerta();','class' =>
                            'btn-large btn-save waves-effect darken-3 btn-guardar','type' => 'submit']) !!}
                        </div>
                   
                    {!! Form::close() !!}

                </div>

                <div id="extraRegular">
                    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller.bachiller_calificacion_seq.calificaciones.update_calificacion_extraregular', 'method' => 'POST']) !!}


                        <input type="hidden" id="bachiller_cch_grupo_id" name="bachiller_cch_grupo_id" value="{{$bachiller_cch_inscritos2[0]->bachiller_grupo_id}}">

                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="1">NO</th>
                                    <th colspan="1">CLAVE PAGO</th>
                                    <th style="display: none;" colspan="1">CURP</th>
                                    <th colspan="1">ALUMNO</th>
                                    {{--  <th align="center" colspan="4">EVALUACIONES</th>  --}}
                                    <th>EXTRA EVAL 1</th>
                                    <th>EXTRA EVAL 2</th>
                                    <th>EXTRA EVAL 3</th>
                                    <th>EXTRA EVAL 4</th>
                                    <th style="display: none;" colspan="1">ID INSCRITO</th>
                                    <th style="display: none;" colspan="1">calificando</th>

                                </tr>
                                {{--  <tr>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th>EVAL 1</th>
                                    <th>EVAL 2</th>
                                    <th>EVAL 3</th>
                                    <th>EVAL 4</th>
                                </tr>  --}}
                            </thead>
                            <tbody>
                                @php
                                    $total = 1;
                                @endphp

                                {{--  Si es numerico entra aqui   --}}
                                @if ($bachiller_cch_inscritos2[0]->matTipoAcreditacion == "N")
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                        @if ($item->insCantidadReprobadasDespuesRecuperativo == "1"  || $item->insEstaEnExtraRegular == "SI")
                                            <tr>
                                                <td>{{$total++}}</td>
                                                <td>{{$item->aluClave}}</td>
                                                <td style="display: none;">{{$item->perCurp}}</td>
                                                <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                                {{--  parcial 1  --}}         
                                                @if ($item->insAproboRecuperativoParcial1 != "")
                                                    @if ($item->insAproboRecuperativoParcial1 == "SI")
                                                        <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="{{$item->insCalificacionExtraOrdinarioParcial1}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="SiDisabled"></td>
                                                    @else

                                                        @if ($item->insCalificacionExtraOrdinarioParcial1 >= 6)
                                                            <td><input tabindex="9" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="{{$item->insCalificacionExtraOrdinarioParcial1}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @else
                                                            <td><input tabindex="9" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="{{$item->insCalificacionExtraOrdinarioParcial1}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @endif

                                                    @endif
                                                @else
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="{{$item->insCalificacionExtraOrdinarioParcial1}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="SiDisabled"></td>
                                                @endif                                      
                                                
                                                

                                                {{--  parcial 2  --}}
                                                @if ($item->insAproboRecuperativoParcial2 != "")
                                                    @if ($item->insAproboRecuperativoParcial2 == "SI")
                                                        <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="{{$item->insCalificacionExtraOrdinarioParcial2}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="SiDisabled"></td>
                                                    @else

                                                        @if ($item->insCalificacionExtraOrdinarioParcial2 >= 6)
                                                            <td><input tabindex="10" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="{{$item->insCalificacionExtraOrdinarioParcial2}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @else
                                                            <td><input tabindex="10" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="{{$item->insCalificacionExtraOrdinarioParcial2}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @endif

                                                    @endif
                                                @else
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="{{$item->insCalificacionExtraOrdinarioParcial2}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="SiDisabled"></td>
                                                @endif    
                                                


                                                {{--  parcial 3  --}}
                                                @if ($item->insAproboRecuperativoParcial3 != "")
                                                    @if ($item->insAproboRecuperativoParcial3 == "SI")
                                                        <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="{{$item->insCalificacionExtraOrdinarioParcial3}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="SiDisabled"></td>
                                                    @else

                                                        @if ($item->insCalificacionExtraOrdinarioParcial3 >= 6)
                                                            <td><input tabindex="11" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="{{$item->insCalificacionExtraOrdinarioParcial3}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @else
                                                            <td><input tabindex="11" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="{{$item->insCalificacionExtraOrdinarioParcial3}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @endif

                                                    @endif
                                                @else
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="{{$item->insCalificacionExtraOrdinarioParcial3}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="SiDisabled"></td>
                                                @endif    
                                            
                                               

                                                {{--  parcial 4  --}}
                                                @if ($item->insAproboRecuperativoParcial4 != "")
                                                    @if ($item->insAproboRecuperativoParcial4 == "SI")
                                                        <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="{{$item->insCalificacionExtraOrdinarioParcial4}}"></td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="SiDisabled"></td>
                                                    @else

                                                        @if ($item->insCalificacionExtraOrdinarioParcial4 >= 6)
                                                            <td><input tabindex="12" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="{{$item->insCalificacionExtraOrdinarioParcial4}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                        @else
                                                            <td><input tabindex="12" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="{{$item->insCalificacionExtraOrdinarioParcial4}}"></td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                        @endif

                                                    @endif
                                                @else
                                                    <td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="{{$item->insCalificacionExtraOrdinarioParcial4}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="SiDisabled"></td>
                                                @endif    
                                                
                                                


                                                <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                                <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="extraregular"></td>

                                            </tr> 
                                        @endif                                                                      
                                    @endforeach
                                @else
                                    {{--  si es alfanumerico entra   --}}
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                    @if ($item->insCantidadReprobadasDespuesRecuperativo == "1"  || $item->insEstaEnExtraRegular == "SI")
                                        <tr>
                                            <td>{{$total++}}</td>
                                            <td>{{$item->aluClave}}</td>
                                            <td style="display: none;">{{$item->perCurp}}</td>
                                            <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                            {{--  parcial 1  --}}         
                                            @if ($item->insAproboRecuperativoParcial1 != "")
                                                @if ($item->insAproboRecuperativoParcial1 == "SI")
                                                    <td>
                                                        <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial1[]" id="insCalificacionExtraOrdinarioParcial1_{{$item->id}}_extraregulares">
                                                            <option value="" selected></option>
                                                        </select>
                                                    </td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionExtraOrdinarioParcial1 != "")
                                                        @if ($item->insCalificacionExtraOrdinarioParcial1 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial1[]" id="insCalificacionExtraOrdinarioParcial1_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial1[]" id="insCalificacionExtraOrdinarioParcial1_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial1[]" id="insCalificacionExtraOrdinarioParcial1_{{$item->id}}_extraregulares">
                                                                <option value="">SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                    @endif
                                                    

                                                @endif
                                            @else
                                                <td>
                                                    <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial1[]" id="insCalificacionExtraOrdinarioParcial1_{{$item->id}}_extraregulares">
                                                        <option value="" selected></option>
                                                    </select>
                                                </td>
                                                
                                                <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="SiDisabled"></td>
                                            @endif                                      
                                            
                                            

                                            {{--  parcial 2  --}}
                                            @if ($item->insAproboRecuperativoParcial2 != "")
                                                @if ($item->insAproboRecuperativoParcial2 == "SI")
                                                    <td>
                                                        <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial2[]" id="insCalificacionExtraOrdinarioParcial2_{{$item->id}}_extraregulares">
                                                            <option value="" selected></option>
                                                        </select>
                                                    </td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionExtraOrdinarioParcial2 != "")
                                                        @if ($item->insCalificacionExtraOrdinarioParcial2 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial2[]" id="insCalificacionExtraOrdinarioParcial2_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial2[]" id="insCalificacionExtraOrdinarioParcial2_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial2[]" id="insCalificacionExtraOrdinarioParcial2_{{$item->id}}_extraregulares">
                                                                <option value="">SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                    @endif
                                                    

                                                @endif
                                             @else
                                                <td>
                                                    <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial2[]" id="insCalificacionExtraOrdinarioParcial2_{{$item->id}}_extraregulares">
                                                        <option value="" selected></option>
                                                    </select>
                                                </td>
                                                
                                                <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="SiDisabled"></td>
                                            @endif  
                                            


                                            {{--  parcial 3  --}}
                                            @if ($item->insAproboRecuperativoParcial3 != "")
                                                @if ($item->insAproboRecuperativoParcial3 == "SI")
                                                    <td>
                                                        <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial3[]" id="insCalificacionExtraOrdinarioParcial3_{{$item->id}}_extraregulares">
                                                            <option value="" selected></option>
                                                        </select>
                                                    </td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionExtraOrdinarioParcial3 != "")
                                                        @if ($item->insCalificacionExtraOrdinarioParcial3 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial3[]" id="insCalificacionExtraOrdinarioParcial3_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial3[]" id="insCalificacionExtraOrdinarioParcial3_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial3[]" id="insCalificacionExtraOrdinarioParcial3_{{$item->id}}_extraregulares">
                                                                <option value="">SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>
                                                    @endif
                                                    

                                                @endif
                                             @else
                                                <td>
                                                    <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial3[]" id="insCalificacionExtraOrdinarioParcial3_{{$item->id}}_extraregulares">
                                                        <option value="" selected></option>
                                                    </select>
                                                </td>
                                                
                                                <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="SiDisabled"></td>
                                            @endif   
                                        
                                        

                                            {{--  parcial 4  --}}
                                            @if ($item->insAproboRecuperativoParcial4 != "")
                                                @if ($item->insAproboRecuperativoParcial4 == "SI")
                                                    <td>
                                                        <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial4[]" id="insCalificacionExtraOrdinarioParcial4_{{$item->id}}_extraregulares">
                                                            <option value="" selected></option>
                                                        </select>
                                                    </td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="SiDisabled"></td>
                                                @else

                                                    @if ($item->insCalificacionExtraOrdinarioParcial4 != "")
                                                        @if ($item->insCalificacionExtraOrdinarioParcial4 == -1)
                                                            <td>
                                                                <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial4[]" id="insCalificacionExtraOrdinarioParcial4_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1" selected>A</option>
                                                                    <option value="-2">NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                        @else
                                                            <td>
                                                                <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial4[]" id="insCalificacionExtraOrdinarioParcial4_{{$item->id}}_extraregulares">
                                                                    <option value="">SELECCIONE</option>
                                                                    <option value="-1">A</option>
                                                                    <option value="-2" selected>NA</option>
                                                                </select>
                                                            </td>
                                                            <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial4[]" id="insCalificacionExtraOrdinarioParcial4_{{$item->id}}_extraregulares">
                                                                <option value="">SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="NoDisabled"></td>
                                                    @endif
                                                    

                                                @endif
                                             @else
                                                <td>
                                                    <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionExtraOrdinarioParcial4[]" id="insCalificacionExtraOrdinarioParcial4_{{$item->id}}_extraregulares">
                                                        <option value="" selected></option>
                                                    </select>
                                                </td>
                                                
                                                <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="SiDisabled"></td>
                                            @endif  
                                            
                                            


                                            <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                            <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="extraregular"></td>

                                            <td style="display: none;"><input class="noUpperCase" type="text" id="tipoacreditacion" name="tipoacreditacion" value="alfanumerico"></td>


                                        </tr> 
                                    @endif                                                                   
                                    @endforeach
                                @endif
                                
                                
                            </tbody>
                        </table>

                        <div class="card-action">
                            {!! Form::button('<i class="material-icons left">save</i> Guardar',
                            ['onclick'=>'this=true;this.innerText="Actulizando datos...";this.form.submit(); alerta();','class' =>
                            'btn-large btn-save waves-effect darken-3 btn-guardar','type' => 'submit']) !!}
                        </div>
                   
                    {!! Form::close() !!}

                </div>
                
                <div id="especialOglobal">
                    {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'bachiller.bachiller_calificacion_seq.calificaciones.update_calificacion_especial', 'method' => 'POST']) !!}


                        <input type="hidden" id="bachiller_cch_grupo_id" name="bachiller_cch_grupo_id" value="{{$bachiller_cch_inscritos2[0]->bachiller_grupo_id}}">

                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="1">NO</th>
                                    <th colspan="1">CLAVE PAGO</th>
                                    <th style="display: none;" colspan="1">CURP</th>
                                    <th colspan="1">ALUMNO</th>
                                    {{--  <th align="center" colspan="4">EVALUACIONES</th>  --}}
                                    <th>CALIFICACIÓN</th>
                                    <th style="display: none;" colspan="1">ID INSCRITO</th>
                                    <th style="display: none;" colspan="1">calificando</th>

                                </tr>
                                {{--  <tr>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th colspan="1"></th>
                                    <th>EVAL 1</th>
                                    <th>EVAL 2</th>
                                    <th>EVAL 3</th>
                                    <th>EVAL 4</th>
                                </tr>  --}}
                            </thead>
                            <tbody>
                                @php
                                    $total = 1;
                                @endphp

                                {{--  Si es numerico entra aqui   --}}
                                @if ($bachiller_cch_inscritos2[0]->matTipoAcreditacion == "N")
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                        @if ($item->insCantidadReprobadasOrdinarioParciales == "3" || $item->insCantidadReprobadasRecuperativos == 2 || $item->insEstaEnEspecial == "SI")
                                            <tr>
                                                <td>{{$total++}}</td>
                                                <td>{{$item->aluClave}}</td>
                                                <td style="display: none;">{{$item->perCurp}}</td>
                                                <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                                {{--  parcial 1  --}}                                               
                                                @if ($item->insCalificacionEspecial >= 6)
                                                    <td><input tabindex="13" style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionEspecial" name="insCalificacionEspecial[]" value="{{$item->insCalificacionEspecial}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                @else
                                                    <td><input tabindex="13" style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionEspecial" name="insCalificacionEspecial[]" value="{{$item->insCalificacionEspecial}}"></td>
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                @endif


                                                <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                                <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="especial"></td>

                                            </tr> 
                                        @endif                                                                      
                                    @endforeach
                                @else
                                    {{--  si es alfanumerico entra   --}}
                                    @foreach ($bachiller_cch_inscritos2 as $item)
                                        @if ($item->insCantidadReprobadasOrdinarioParciales == "3" || $item->insCantidadReprobadasRecuperativos == 2 || $item->insEstaEnEspecial == "SI")
                                            <tr>
                                                <td>{{$total++}}</td>
                                                <td>{{$item->aluClave}}</td>
                                                <td style="display: none;">{{$item->perCurp}}</td>
                                                <td>{{$item->perApellido1.' '.$item->perApellido2.' '.$item->perNombre}}</td>

                                                {{--  parcial 1  --}}    
                                                
                                                @if ($item->insCalificacionEspecial != "")
                                                    @if ($item->insCalificacionEspecial == -1)
                                                        <td>
                                                            <select style="width: 100%; border-color: #01579B;" class="browser-default validate select2" name="insCalificacionEspecial[]" id="insCalificacionEspecial_{{$item->id}}_especial">
                                                                <option value="">SELECCIONE</option>
                                                                <option value="-1" selected>A</option>
                                                                <option value="-2">NA</option>
                                                            </select>
                                                        </td>
                                                        
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>
                                                    @else
                                                        <td>
                                                            <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionEspecial[]" id="insCalificacionEspecial_{{$item->id}}_especial">
                                                                <option value="">SELECCIONE</option>
                                                                <option value="-1">A</option>
                                                                <option value="-2" selected>NA</option>
                                                            </select>
                                                        </td>
                                                        
                                                        <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                    @endif
                                                @else
                                                    <td>
                                                        <select style="width: 100%; border-color: red;" class="browser-default validate select2" name="insCalificacionEspecial[]" id="insCalificacionEspecial_{{$item->id}}_especial">
                                                            <option value="">SELECCIONE</option>
                                                            <option value="-1">A</option>
                                                            <option value="-2">NA</option>
                                                        </select>
                                                    </td>
                                                    
                                                    <td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>
                                                @endif
                                                


                                                <td style="display: none;"><input class="noUpperCase" type="text" id="bachiller_cch_inscrito_id" name="bachiller_cch_inscrito_id[]" value="{{$item->id}}"></td>

                                                <td style="display: none;"><input class="noUpperCase" type="text" id="se_esta_calificando" name="se_esta_calificando" value="especial"></td>

                                                <td style="display: none;"><input class="noUpperCase" type="text" id="tipoacreditacion" name="tipoacreditacion" value="alfanumerico"></td>

                                            </tr> 
                                        @endif                                                                        
                                    @endforeach
                                @endif
                                
                                
                            </tbody>
                        </table>

                        <div class="card-action">
                            {!! Form::button('<i class="material-icons left">save</i> Guardar',
                            ['onclick'=>'this=true;this.innerText="Actulizando datos...";this.form.submit(); alerta();','class' =>
                            'btn-large btn-save waves-effect darken-3 btn-guardar','type' => 'submit']) !!}
                        </div>
                   
                    {!! Form::close() !!}

                </div>
                
              
            </div>
            
        </div>

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



@include('bachiller.calificaciones_chetumal.funcionesJs2')

<script>
    function alerta(){

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
{{--  @include('bachiller.calificaciones_chetumal.creamos_listado_alumno')  --}}


@endsection
