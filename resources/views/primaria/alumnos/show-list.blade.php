@extends('layouts.dashboard')

@section('template_title')
    Primaria alumnos
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_alumno')}}" class="breadcrumb">Lista de alumnos</a>
@endsection

@section('content')
    <div id="table-datatables">
        <h4 class="header">ALUMNOS</h4>
        @php use App\Models\User; @endphp
        @if (User::permiso("alumno") != "D" && User::permiso("alumno") != "P")
        <a href="{{ route('primaria_alumno.create') }}" class="btn-large waves-effect  darken-3" type="button">Agregar
            <i class="material-icons left">add</i>
        </a>
        <br>
        <br>
        @endif
        <div class="row">
            <div class="col s12">
                <table id="tbl-alumno-primaria" class="responsive-table display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Clave Alumno</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Nombre(s)</th>
                        <th>Curp</th>
                        <th>Estado Alumno</th>
                        <th>Fecha ingreso</th>
                        <th>Fecha de baja</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="non_searchable"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="preloader">
        <div id="preloader"></div>
    </div>

    <div id="modalEstatusAlumno-primaria" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col s12">
                    <input type="hidden" value="" class="alumnoId">
                    <h4>Modificar Estatus Del Alumno</h4>
                    <select name="aluEstado" class="aluEstado browser-default validate select2" id="" style="width: 100%;">
                        <option value="">Seleccionar</option>
                        <option value="R">Regular</option>
                        <option value="E">Egresado</option>
                        <option value="N">Nuevo ingreso</option>
                        <option value="B">Baja</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l6">
                    <label for="resFechaBaja" class="resFechaBaja">Fecha de baja</label>
                    <input type="date" id="resFechaBaja" name="resFechaBaja" class="resFechaBaja validate" style="width: 100%;" />
                </div>
                <div class="col s12 m6 l6">
                    <label for="conceptosBaja" class="conceptosBaja">Motivo de baja</label>
                    <select id="conceptosBaja" class="browser-default validate conceptosBaja" required name="conceptosBaja" style="width: 100%;" required>
                        <option value="" selected disabled>Seleccionar</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 l8">
                    <label for="resObservaciones" class="resObservaciones">Observaciones</label>
                    <input type="text" id="resObservaciones" name="resObservaciones" class="resObservaciones validate" style="width: 100%;" />
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button type="button" class="guardar-estatus-alumno btn-large waves-effect  darken-3 btn-flat" style="color: #fff;">
                        <i class="material-icons left">add</i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-close waves-effect waves-green btn-flat">Cerrar</button>
        </div>
    </div>

    {{-- MODAL EQUIVALENTES --}}
    <div id="modalpro" class="modal">
        <div class="modal-content">
            <h4>Historial de pagos</h4>

        </div>
        <div class="modal-footer">
            <button type="button" class="modal-close waves-effect waves-green btn-flat">Cerrar</button>
        </div>
    </div>



    {{-- MODAL EQUIVALENTES --}}
    <div id="modalHistorialPagosAluPrimaria" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h4>Historial de pagos</h4>
                    <span class="modalAluClave" style="font-weight: bold;"></span>
                    <span class="modalNombres"></span>
                    <p>
                        Pagos recibidos hasta: {{$registroUltimoPago}}
                    </p>
                </div>
                <div class="col s12 m6 l6">
                    <p><b>Generar reporte:</b></p>
                    {{-- ambos forms apuntan a HistorialPagosAlumnoController --}}
                    <form action="{{url('primaria_reporte/historial_pagos_alumno/imprimir')}}" method="POST" style="display:inline;" target="_blank">
                        @csrf
                        <input type="hidden" name="aluClave" value="" class="modal_aluClave" required>
                        <input type="hidden" name="formatoImpresion" value="PDF">
                        <button type="submit" class="btn waves-effect red darken-3" style="width:100px">PDF</button>
                    </form>

                    {{-- <form action="{{url('primaria_reporte/historial_pagos_alumno/imprimir')}}" method="POST" style="display:inline;" target="_blank">
                        @csrf
                        <input type="hidden" name="aluClave" value="" class="modal_aluClave" required>
                        <input type="hidden" name="formatoImpresion" value="EXCEL">
                        <button type="submit" class="btn waves-effect green darken-4" style="width:100px">Excel</button>
                    </form>  --}}

                </div>
            </div>
            <table id="tbl-historial-pagos" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Curso</th>
                    <th>Concepto de pago</th>
                    <th>Importe</th>
                    <th>Referencia de pago</th>
                    <th>Fecha de pago</th>
                    <th>Comentario del pago</th>
                </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <div class="preloader-modal">
                <div id="preloader-modal"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-close waves-effect waves-green btn-flat">Cerrar</button>
        </div>
    </div>
@endsection

@section('footer_scripts')
    {!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
    {!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}


    <!-- crearReferencia/'.$query->aluClave.' -->
    <script type="text/javascript">
        function modalHistorialPagos(aluClave) {
            //MOSTRAR MODAL
            $('.modal').modal();
            if ($.fn.DataTable.isDataTable("#tbl-historial-pagos")) {
                $('#tbl-historial-pagos').DataTable().clear().destroy();
            }

            $.fn.dataTable.ext.errMode = 'throw';
            $('#tbl-historial-pagos').dataTable({
                "destroy": true,
                "language":{"url":"api/lang/javascript/datatables"},
                "serverSide": true,
                "dom": '"top"i',
                "pageLength": 12,
                "bSort": false,
                "stateSave": false,
                "ajax": {
                    "type" : "GET",
                    'url': base_url + "/primaria_alumno/listHistorialPagosAluclave/" + aluClave,
                    beforeSend: function () {
                        $('.preloader-modal').fadeIn(200, function() {
                            $(this).append('<div id="preloader-modal"></div>');
                        });
                    },
                    complete: function (data) {
                        if (data.responseJSON.data) {
                            var obj = data.responseJSON.data[0];
                        }

                        $('.preloader-modal').fadeOut(200, function() {
                            $('#preloader-modal').remove();
                        });
                    }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                        if (errorThrown === "Unauthorized") {
                            swal({
                                title: "Ups...",
                                text: "La sesion ha expirado",
                                type: "warning",
                                confirmButtonText: "Ok",
                                confirmButtonColor: '#3085d6',
                                showCancelButton: false
                                }, function(isConfirm) {
                                if (isConfirm) {
                                    window.location.href = 'login';
                                } else {
                                    window.location.href = 'login';
                                }
                            });
                        }
                    }
                },
                "columns": [
                    {data: "pagAnioPer"},
                    {data: "conpNombre", name:"conpNombre"},
                    {data: "pagImpPago", name:"pagImpPago"},
                    {data: "pagRefPago"},
                    {data: "pagFechaPago", name:"pagFechaPago"},
                    {data: "pagComentario"},
                ],
                //Apply the search
                initComplete: function () {
                    var searchFill = JSON.parse(localStorage.getItem( 'DataTables_' + this.api().context[0].sInstance ))

                    var index = 0
                    this.api().columns().every(function () {
                        var column = this;
                        var columnClass = column.footer().className;
                        if (columnClass != 'non_searchable') {
                            var input = document.createElement("input");


                            var columnDataOld = searchFill.columns[index].search.search
                            $(input).attr("placeholder", "Buscar").addClass("busquedas").val(columnDataOld);



                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }

                        index ++
                    });
                },
                stateSaveCallback: function(settings,data) {
                    localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
                },
                stateLoadCallback: function(settings) {
                    return JSON.parse(localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                }
            });
        }
        $(document).on("click", ".btn-modal-historial-pagos-primaria", function (e) {
            e.preventDefault()
            var aluClave = $(this).data("aluclave");
            var nombres = $(this).data("nombres");
            var alumno_id = $(this).data('alumno-id');

            $('.modalNombres').html(nombres);
            $('.modalAluClave').html(aluClave);
            $('.modal_aluClave').val(aluClave);
            modalHistorialPagos(aluClave)
        })
    </script>


    <script>
        $(document).on("click", ".btn-modal-estatus-alumno", function (e) {
            e.preventDefault()
            $('.modal').modal();
            var alumnoId = $(this).data("alumno-id");
            $("#modalEstatusAlumno-primaria").find(".alumnoId").val(alumnoId)



        })

        $(document).on('change','.aluEstado',function(e){
            e.preventDefault();
            var aluEstado = $(this).val();
            if(aluEstado == 'B'){
                $('.resFechaBaja').show().prop('required',true);
                $('.resObservaciones').show().prop('required',true);
                $('.conceptosBaja').show().prop('required',true);
                $('.guardar-estatus-alumno').prop('disabled',true);
            }else{
                $('.resFechaBaja').hide().removeAttr('required');
                $('.resObservaciones').hide().removeAttr('required');
                $('.conceptosBaja').hide().removeAttr('required');
                $('.guardar-estatus-alumno').prop('disabled',false);
            }

        });

        $('#resFechaBaja').on('change',function(e){
            e.preventDefault();
            var resFechaBaja = $(this).val();
            var resObservaciones = $('#resObservaciones').val();
            var conceptosBaja = $('#conceptosBaja').val();

            if(!resFechaBaja == '' && !resObservaciones == '' && !conceptosBaja == ''){
                $('.guardar-estatus-alumno').prop('disabled',false);
            }else{
                $('.guardar-estatus-alumno').prop('disabled',true);
            }
        });

        $('#resObservaciones').on('change',function(e){
            e.preventDefault();
            var resFechaBaja = $('#resFechaBaja').val();
            var conceptosBaja = $('#conceptosBaja').val();
            var resObservaciones = $(this).val();

            if(!resFechaBaja == '' && !resObservaciones == '' && !conceptosBaja == ''){
                $('.guardar-estatus-alumno').prop('disabled',false);
            }else{
                $('.guardar-estatus-alumno').prop('disabled',true);
            }
        });

        $('#conceptosBaja').on('change',function(e){
            e.preventDefault();
            var resFechaBaja = $('#resFechaBaja').val();
            var resObservaciones = $('#resObservaciones').val();
            var conceptosBaja = $(this).val();

            if(!resFechaBaja == '' && !resObservaciones == '' && !conceptosBaja == ''){
                $('.guardar-estatus-alumno').prop('disabled',false);
            }else{
                $('.guardar-estatus-alumno').prop('disabled',true);
            }
        });

        $(document).on("click", ".guardar-estatus-alumno", function (e) {
            e.preventDefault()

            var aluEstado = $(".aluEstado").val()
            var alumnoId = $("#modalEstatusAlumno-primaria").find(".alumnoId").val()
            var resFechaBaja = $('#resFechaBaja').val();
            var resObservaciones = $('#resObservaciones').val();
            var conceptosBaja = $('#conceptosBaja').val();

            if (aluEstado !== "") {
                $.ajax({
                    data: {
                        "alumnoId": alumnoId,
                        "aluEstado" : aluEstado,
                        "resFechaBaja" : resFechaBaja,
                        "resObservaciones" : resObservaciones,
                        "conceptosBaja" : conceptosBaja,
                        "_token": $("meta[name=csrf-token]").attr("content")
                    },
                    type: "POST",
                    dataType: "JSON",
                    url: base_url + "/primaria_alumno/cambiarEstatusAlumno",
                })
                .done(function( data, textStatus, jqXHR ) {
                    if (data.res) {
                        swal({
                            title: "Escuela modelo",
                            text: "Estatus de alumno guardado correctamente",
                            type: "success",
                        });
                        location.reload();
                    }
                    if (!data.res) {
                        swal({
                            title: "Escuela modelo",
                            text: data.msg,
                            type: "warning",
                        });
                    }

                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    console.log(textStatus)
                    console.log(jqXHR)
                });
            }

        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.resFechaBaja').hide();
            $('.resObservaciones').hide();
            $('.conceptosBaja').hide();

            $.get(base_url+`/primaria_alumno/conceptosBaja`, function(data) {

                $.each(data,function(key,value){
                    $("#conceptosBaja").append(`<option value=${value.conbClave}>${value.conbNombre}</option>`);
                });

            });

            $.fn.dataTable.ext.errMode = 'throw';
            $('#tbl-alumno-primaria').dataTable({
                "language":{"url":base_url+"/api/lang/javascript/datatables"},
                "serverSide": true,
                "dom": '"top"i',
                "pageLength": 5,
                "stateSave": true,
                "ajax": {
                    "type" : "GET",
                    'url': base_url+"/primaria_alumno/list",
                    beforeSend: function () {
                        $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');;});
                    },
                    complete: function () {
                        $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                    }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                        if (errorThrown === "Unauthorized") {
                            swal({
                                title: "Ups...",
                                text: "La sesion ha expirado",
                                type: "warning",
                                confirmButtonText: "Ok",
                                confirmButtonColor: '#3085d6',
                                showCancelButton: false
                                }, function(isConfirm) {
                                if (isConfirm) {
                                    window.location.href = 'login';
                                } else {
                                    window.location.href = 'login';
                                }
                            });
                        }
                    }
                },
                "columns":[
                    {data: "aluClave"},
                    {data:'perApellido1',name: "personas.perApellido1"},
                    {data:'perApellido2',name: "personas.perApellido2"},
                    {data:'perNombre',name: "personas.perNombre"},
                    {data:'perCurp',name: "personas.perCurp"},
                    {data: "aluEstado"},
                    {data: "aluFechaIngr"},
                    {data: "resFechaBaja"},
                    {data: "action"}
                ],
                //Apply the search
                initComplete: function () {
                    var searchFill = JSON.parse(localStorage.getItem( 'DataTables_' + this.api().context[0].sInstance ))


                    var index = 0
                    this.api().columns().every(function () {
                        var column = this;
                        var columnClass = column.footer().className;
                        if(columnClass != 'non_searchable'){
                            var input = document.createElement("input");


                            var columnDataOld = searchFill.columns[index].search.search
                            $(input).attr("placeholder", "Buscar").addClass("busquedas").val(columnDataOld);



                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }

                        index ++
                    });
                },
                stateSaveCallback: function(settings,data) {
                    localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
                },
                stateLoadCallback: function(settings) {
                    return JSON.parse(localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.confirm-buena-conducta', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "¿Estás seguro?",
                    text: "¿Estás seguro que deseas imprimir la constancia de buena conducta de este alumno?",
                    type: "info",
                    confirmButtonText: "Si",
                    confirmButtonColor: '#3085d6',
                    cancelButtonText: "No",
                    showCancelButton: true
                },
                function() {
                    $(".form-buena-conducta").submit();
                });
            });
    </script>
@endsection
