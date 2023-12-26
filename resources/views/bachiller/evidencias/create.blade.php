@extends('layouts.dashboard')

@section('template_title')
Bachiller evidencia
@endsection

@section('head')

@endsection

@section('breadcrumbs')
<a href="{{url('bachiller_grupo_yucatan')}}" class="breadcrumb">Inicio</a>
<a href="{{url('bachiller_evidencias', [$bachiller_grupo->id])}}" class="breadcrumb">Lista de evidencias</a>
<label class="breadcrumb">Agregar evidencia</label>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' =>
        'bachiller.bachiller_evidencias.store', 'method' => 'POST']) !!}
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">AGREGAR EVIDENCIA</span>

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
                            {!! Form::label('ubicacion_id', 'Campus *', array('class' => '')); !!}
                            <select id="ubicacion_id" class="browser-default validate select2" required
                                name="ubicacion_id" style="width: 100%;">
                                <option value="{{$bachiller_grupo->ubicacion_id}}">
                                    {{$bachiller_grupo->ubiClave.'-'.$bachiller_grupo->ubiNombre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('departamento_id', 'Departamento *', array('class' => '')); !!}
                            <select id="departamento_id" data-departamento-idold="{{old('departamento_id')}}"
                                class="browser-default validate select2" required name="departamento_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_grupo->departamento_id}}">
                                    {{$bachiller_grupo->depClave.'-'.$bachiller_grupo->depNombre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('escuela_id', 'Escuela *', array('class' => '')); !!}
                            <select id="escuela_id" data-escuela-idold="{{old('escuela_id')}}"
                                class="browser-default validate select2" required name="escuela_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_grupo->escuela_id}}">
                                    {{$bachiller_grupo->escClave.'-'.$bachiller_grupo->escNombre}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('periodo_id', 'Período *', array('class' => '')); !!}
                            <select id="periodo_id" data-plan-idold="{{old('periodo_id')}}"
                                class="browser-default validate select2" required name="periodo_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_grupo->periodo_id}}">
                                    {{$bachiller_grupo->perNumero.'-'.$bachiller_grupo->perAnio}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('programa_id', 'Programa *', array('class' => '')); !!}
                            <select id="programa_id" data-programa-idold="{{old('programa_id')}}"
                                class="browser-default validate select2" required name="programa_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_grupo->programa_id}}">
                                    {{$bachiller_grupo->progClave.'-'.$bachiller_grupo->progNombre}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('plan_id', 'Plan *', array('class' => '')); !!}
                            <select id="plan_id" data-plan-idold="{{old('plan_id')}}"
                                class="browser-default validate select2" required name="plan_id" style="width: 100%;">
                                <option value="{{$bachiller_grupo->plan_id}}">{{$bachiller_grupo->planClave}}</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            {!! Form::label('matSemestre', 'Grado *', array('class' => '')); !!}
                            <input class="gpoSemestreOld" type="hidden" data-gpoSemestre-idold="{{old('matSemestre')}}">
                            <select id="matSemestre" data-gposemestre-idold="{{old('matSemestre')}}"
                                class="browser-default validate select2" required name="matSemestre"
                                style="width: 100%;">
                                <option value="{{$bachiller_grupo->gpoGrado}}">{{$bachiller_grupo->gpoGrado}}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l4">
                            {!! Form::label('materia_id', 'Materia *', array('class' => '')); !!}
                            <select id="materia_id" data-plan-idold="{{old('materia_id')}}"
                                class="browser-default validate select2" required name="materia_id"
                                style="width: 100%;">
                                <option value="{{$bachiller_grupo->bachiller_materia_id}}">
                                    {{$bachiller_grupo->matClave.'-'.$bachiller_grupo->matNombre}}</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            <label for="materia_acd_id" id="materia_acd_id_label">Materia ACD</label>
                            <select id="materia_acd_id" @if ($bachiller_grupo->gpoMatComplementaria != "") @else disabled @endif data-plan-idold="{{old('materia_acd_id')}}"
                                class="browser-default validate select2" name="materia_acd_id" style="width: 100%;">
                                @if ($bachiller_grupo->bachiller_materia_acd_id != "")
                                <option value="{{$bachiller_grupo->bachiller_materia_acd_id}}">{{$bachiller_grupo->gpoMatComplementaria}}</option>
                                @else
                                <option value="NULL">SELECCIONE UNA OPCIÓN</option>
                                @endif

                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('eviNumero', NULL, array('id' => 'eviNumero', 'class' =>
                                '','maxlength'=>'15')) !!}
                                {!! Form::label('eviNumero', 'Número evidencia *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::text('eviDescripcion', NULL, array('id' => 'eviDescripcion', 'class' =>
                                '','maxlength'=>'255')) !!}
                                {!! Form::label('eviDescripcion', 'Descripción evidencia *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('eviFechaEntrega', 'Fecha entrega *', array('class' => '')); !!}
                            {!! Form::date('eviFechaEntrega', NULL, array('id' => 'eviFechaEntrega', 'class' =>
                            '','maxlength'=>'15')) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                {!! Form::number('eviPuntos', NULL, array('id' => 'eviPuntos', 'class' =>
                                '','maxlength'=>'15')) !!}
                                {!! Form::label('eviPuntos', 'Puntos evidencia *', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('eviTipo', 'Tipo evidencia *', array('class' => '')); !!}
                            <select id="eviTipo" data-plan-idold="{{old('eviTipo')}}"
                                class="browser-default validate select2" name="eviTipo" style="width: 100%;">
                                {{--  <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>  --}}
                                <option value="A">A - DE PROCESO</option>
                                <option value="P">P - DE PRODUCTO</option>
                            </select>
                        </div>

                        <div class="col s12 m6 l4">
                            {!! Form::label('eviFaltas', 'Faltas evidencia *', array('class' => '')); !!}
                            <select id="eviFaltas" data-plan-idold="{{old('eviFaltas')}}"
                                class="browser-default validate select2" name="eviFaltas" style="width: 100%;">
                                {{-- <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option> --}}
                                {{-- <option value="S">S - SE REGISTRA FALTAS</option>
                                <option value="N">N - NO SE REGISTRA FALTAS</option> --}}
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s6 m6 l3">
                            <a name="agregarMateriaEvidencias" id="agregarMateriaEvidencias"
                                class="waves-effect btn-large tooltipped #2e7d32 green darken-3" data-position="right"
                                data-tooltip="Agregar evidencia">
                                <i class=material-icons>library_add</i>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l3">
                            <label for="contador"><b style="color: #000">Puntos actuales</b></label>
                            <b><input style="color: red" type="text" name="" id="contador" name="contador" value="0"
                                    readonly></b>
                        </div>
                        <div class="col s12 m6 l3">
                            <label for="contadorRestantes"><b style="color: #000">Puntos restantes</b></label>
                            <b><input style="color: red" type="text" name="" id="contadorRestantes"
                                    name="contadorRestantes" value="100" readonly></b>
                        </div>

                        <div class="col s12 m6 l3">
                            <label for="puntosProceso"><b style="color: #000">Puntos proceso</b></label>
                            <b><input style="color: red" type="text" name="" id="puntosProceso" name="puntosProceso"
                                    value="0" readonly></b>
                        </div>
                        <div class="col s12 m6 l3">
                            <label for="puntosProducto"><b style="color: #000">Puntos producto</b></label>
                            <b><input style="color: red" type="text" name="" id="puntosProducto" name="puntosProducto"
                                    value="0" readonly></b>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12">
                            <table id="tbl_materias_bachiller" class="responsive-table display" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Período-Año</th>
                                        <th>Clave-Nombre</th>
                                        <th>Materia ACD</th>
                                        <th>Número evidencia</th>
                                        <th>Descripción</th>
                                        <th>Fecha entrega</th>
                                        <th>Puntos Evidencia</th>
                                        <th>Tipo Evidencia</th>
                                        <th>Faltas Evidencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


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

    @include('bachiller.evidencias.getMaterias')

    <script>
        $(document).ready(function(){
            var contadorRestantes = $("#contadorRestantes").val();
            $('.submit-button').prop('disabled',true);
            function tablaMateria(datos,periodo_id,materia_id, materia_acd_id){
                var table_row = `<tr>`+
                    '<td>'+datos[0]+'</td>'+
                    '<td>'+datos[1]+'</td>'+
                    '<td>'+datos[8]+'</td>'+
                    '<td>'+datos[2]+'</td>'+
                    '<td>'+datos[3]+'</td>'+
                    '<td>'+datos[4]+'</td>'+
                    '<td>'+datos[5]+'</td>'+
                    '<td>'+datos[6]+'</td>'+
                    '<td>'+datos[7]+'</td>'+
                    '<td><a class="quitar" style="cursor:pointer;" title="Eliminar del listado">'+
                        '<i class=material-icons>delete</i>'+
                    '</a></td>'+

                    `<td><textarea style="display:none;" name="materiasEvidencias[]">${periodo_id}~${materia_id}~${datos[0]}~${datos[1]}~${datos[2]}~${datos[3]}~${datos[4]}~${datos[5]}~${datos[6]}~${datos[7]}~${datos[8]}~${materia_acd_id}</textarea></td>`
                    '</tr>';

                $('#tbl_materias_bachiller tbody').append(table_row);

                var suma = $("#contador").val();
                $("#tbl_materias_bachiller tbody tr").find('td:eq(6)').each(function () {

                    //obtenemos el codigo de la celda
                    codigo = parseInt($(this).text());
                    var total = codigo+parseInt(suma);

                    $("#contador").val(total)
                    $("#contadorRestantes").val(parseInt(contadorRestantes) - total)


                });

            }


            $('#agregarMateriaEvidencias').on('click',function(e){
                e.preventDefault();

                var periodo_id = $('#periodo_id option:selected').val();
                var periodPeriodoAnio = $('#periodo_id option:selected').html();

                var materia_id = $('#materia_id option:selected').val();
                var materiaClaveNombre = $('#materia_id option:selected').html();


                var materia_acd_id = $('#materia_acd_id option:selected').val();
                var nombreACD = $('#materia_acd_id option:selected').html();

                var eviNumero = $('#eviNumero').val();
                var eviDescripcion = $('#eviDescripcion').val();
                var eviFechaEntrega = $('#eviFechaEntrega').val();
                var eviPuntos = $("#eviPuntos").val();
                var eviTipo = $('#eviTipo').val();
                var eviFaltas = $('#eviFaltas').val();

                var contadorRestantes2 = $("#contadorRestantes").val();


                if(parseInt(eviPuntos) <= parseInt(contadorRestantes2)){

                    if(materia_id && eviNumero && eviDescripcion && eviFechaEntrega && eviPuntos && eviTipo && eviFaltas){
                        $('#ubicacion_id').prop('disabled',true);
                        $('#departamento_id').prop('disabled',true);
                        $('#escuela_id').prop('disabled',true);
                        $('#programa_id').prop('disabled',true);
                        $('#plan_id').prop('disabled',true);
                        $('#materia_id').prop('disabled',true);
                        $('#periodo_id').prop('disabled',true);
                        $("#matSemestre").prop('disabled',true);
                        $('#materia_acd_id').prop('disabled',true);



                        $('.submit-button').prop('disabled',false);


                        var datos = [
                            periodPeriodoAnio,
                            materiaClaveNombre,
                            eviNumero,
                            eviDescripcion,
                            eviFechaEntrega,
                            eviPuntos,
                            eviTipo,
                            eviFaltas,
                            nombreACD
                        ];

                            tablaMateria(datos,periodo_id,materia_id, materia_acd_id);
                            $('#eviNumero').val("");
                            $('#eviDescripcion').val("");
                            $('#eviFechaEntrega').val("");
                            $("#eviPuntos").val("");
                            //$("#eviTipo").val("").trigger( "change" );
                            //$('#eviFaltas').val("");


                    }else{
                        swal('Ingrese todos los datos de la evidencia para poder agregarla a lista \n'+
                        'Datos necesarios: \n'+
                        'Materia, Número evidencia, Descripción evidencia, Puntos evidencia, Fecha entrega, Tipo evidencia, y Faltas evidencia');
                    }

                }else{
                    swal("Upss...", "No se puede agregar la evidencia debido que sobrepasa los puntos de evidencias");
                }


            });

            $('#tbl_materias_bachiller').on('click','.quitar', function (event) {
                var totales2 = $("#contador").val();
                var contadorRestantes2 = $("#contadorRestantes").val();


                var restaValores = parseInt(totales2) - parseInt($(this).parents('tbody tr').find('td:eq(6)').text());
                //Primera fila
               $("#contador").val(restaValores);

               //Regresamos el valor al input de puntos restantes
               $("#contadorRestantes").val(parseInt($(this).parents('tbody tr').find('td:eq(6)').text()) + parseInt(contadorRestantes2));

                $(this).closest('tr').remove();
                if($('#tbl_materias_bachiller tbody tr').length == 0){
                    $('#ubicacion_id').prop('disabled',false);
                    $('#departamento_id').prop('disabled',false);
                    $('#escuela_id').prop('disabled',false);
                    $('#programa_id').prop('disabled',false);
                    $('#plan_id').prop('disabled',false);
                    $('#periodo_id').prop('disabled',false);
                    $("#matSemestre").prop('disabled',false);
                    $('.submit-button').prop('disabled',true);
                    $('#materia_id').prop('disabled',false);
                    $('#materia_acd_id').prop('disabled',false);


                }
            });






                function mensaje() {

                    var totales = $("#contador").val();

                    if(parseInt(totales) >= 100){
                        $("#agregarMateriaEvidencias").attr('disabled', true);


                    }else{
                        $("#agregarMateriaEvidencias").attr('disabled', false);

                    }

                }

                 setInterval(mensaje,1000);


        });
        </script>

    {{-- para llenar automaticamente los combos --}}
    <script>
        $(document).ready(function(){

        //Cuando hay change
        //Merida
        $("#ubicacion_id").change(function(){
            $("#eviFaltas").empty();


            if($('select[id=ubicacion_id]').val() == "1"){
                $("#eviTipo").change(function(){

                    if($('select[id=eviTipo]').val() == "A"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                        $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                    }

                    if($('select[id=eviTipo]').val() == "P"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                    }
                });

                //Si no hay change de eviTipo
                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option select value='N'>N - NO SE REGISTRA FALTAS</option>`);
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option select value='N'>N - NO SE REGISTRA FALTAS</option>`);
                }
            }

        });


        if($('select[id=ubicacion_id]').val() == "1"){
            $("#eviFaltas").empty();
            $("#eviTipo").change(function(){

                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option select value='N'>N - NO SE REGISTRA FALTAS</option>`);
                }
            });


            //Si no hay change de eviTipo
            if($('select[id=eviTipo]').val() == "A"){
                //$("#eviFaltas").empty();
                $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);
                $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);

            }

            if($('select[id=eviTipo]').val() == "P"){
                $("#eviFaltas").empty();
                $("#eviFaltas").append(`<option select value='N'>N - NO SE REGISTRA FALTAS</option>`);
            }
        }

        //Valladolid change
        $("#ubicacion_id").change(function(){
            $("#eviFaltas").empty();


            if($('select[id=ubicacion_id]').val() == "2"){
                $("#eviTipo").change(function(){

                    if($('select[id=eviTipo]').val() == "A"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

                    }

                    if($('select[id=eviTipo]').val() == "P"){
                        $("#eviFaltas").empty();
                        $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);


                    }
                });

                //Si no hay change de eviTipo
                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);


                }
            }


        });

        //valladolid
        if($('select[id=ubicacion_id]').val() == "2"){
            $("#eviFaltas").empty();

            $("#eviTipo").change(function(){

                if($('select[id=eviTipo]').val() == "A"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

                }

                if($('select[id=eviTipo]').val() == "P"){
                    $("#eviFaltas").empty();
                    $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);


                }
            });


            //Si no hay change de eviTipo

            if($('select[id=eviTipo]').val() == "A"){
                $("#eviFaltas").empty();
                $("#eviFaltas").append(`<option value='N'>N - NO SE REGISTRA FALTAS</option>`);

            }

            if($('select[id=eviTipo]').val() == "P"){
                $("#eviFaltas").empty();
                $("#eviFaltas").append(`<option value='S'>S - SE REGISTRA FALTAS</option>`);


            }
        }



    });
    </script>
    @endsection
