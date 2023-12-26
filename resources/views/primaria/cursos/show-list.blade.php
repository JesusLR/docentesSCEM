@extends('layouts.dashboard')

@section('template_title')
    Primaria preinscritos
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{route('primaria_curso.index')}}" class="breadcrumb">Lista de preinscritos</a>
@endsection

@section('content')


<div id="table-datatables">
    <h4 class="header">PREINSCRITOS</h4>
    @php use App\Models\User; @endphp
    @if (User::permiso("curso") != "D" && User::permiso("curso") != "P")
        <a href="{{ route('primaria_curso.create') }}" class="btn-large waves-effect  darken-3" type="button">Agregar
            <i class="material-icons left">add</i>
        </a>
        <br><br>
    @endif
    <div class="row">
        <div class="col s12">
            <table id="tbl-curso-primaria" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Clave Alumno</th>
                        <th>Matrícula</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Nombre(s) Alumno</th>
                        <th>Edo</th>
                        <th>Fecha de baja</th>
                        <th>TI</th>
                        <th>Gdo</th>
                        <th>Gpo</th>
                        <th>Beca</th>
                        <th>Ubic</th>
                        <th>Dep</th>
                        <th>Esc</th>
                        <th>Prog</th>
                        <th>Plan</th>
                        <th>Acciones/Registro</th>
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
                        <th></th>
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

@include('primaria.modales.modalPreinscritoDetalle')
@include('primaria.cursos.modales.modaHistorialPagos-primaria')
@include('primaria.cursos.modales.modaBajaCurso-primaria')
@include('primaria.modales.modalBajaARegular')
@include('primaria.cursos.modales.modalAlumnoDetalle-primaria')

@endsection

@section('footer_scripts')

{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}


<script type="text/javascript">
    $(document).on("click", ".btn-modal-ficha-pago", function(e) {
        e.preventDefault()

        var curso_id = $(this).data("curso-id");
        var pedirConfirmacion = $(this).data("pedir-confirmacion");
        if(pedirConfirmacion == 'SI') {
            swal({
                title: "Validar Pago Ceneval",
                text: "¿El alumno ya pagó su examen Ceneval?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#0277bd',
                confirmButtonText: 'SI',
                cancelButtonText: "NO",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.open("primaria_curso/crearReferencia/" + curso_id + "/" + "si", "_blank");
                } else {
                    window.open("primaria_curso/crearReferencia/" + curso_id + "/" + "no", "_blank");
                }
                swal.close()
            });
        } else {
            window.open("primaria_curso/crearReferencia/" + curso_id + "/" + "no", "_blank");
        }


    });

    $(document).on("click", ".confirm-delete-curso", function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        swal({
            title: "¿Estás seguro?",
            text: "Al eliminar el registro, puede afectar el resumen académico y el histórico de este alumno. ¿Desea continuar con la eliminación?",
            type: "warning",
            confirmButtonText: "Si",
            confirmButtonColor: '#3085d6',
            cancelButtonText: "No",
            showCancelButton: true
        },
        function(isConfirm) {
            if(isConfirm) {
                $('#delete_'+id).submit();
            }
        });
    });
</script>


<script type="text/javascript">
    $(document).on("click", ".btn-modal-ficha-pago-hsbc", function(e) {
        e.preventDefault()

        var curso_id = $(this).data("curso-id");
        var pedirConfirmacion = $(this).data("pedir-confirmacion");
        if(pedirConfirmacion == 'SI') {
            swal({
                title: "Validar Pago Ceneval",
                text: "¿El alumno ya pagó su examen Ceneval?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#0277bd',
                confirmButtonText: 'SI',
                cancelButtonText: "NO",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.open("primaria_curso/crearReferenciaHSBC/" + curso_id + "/" + "si", "_blank");
                } else {
                    window.open("primaria_curso/crearReferenciaHSBC/" + curso_id + "/" + "no", "_blank");
                }
                swal.close()
            });
        } else {
            window.open("primaria_curso/crearReferenciaHSBC/" + curso_id + "/" + "no", "_blank");
        }


    })
</script>


<!-- crearReferencia/'.$query->curso_id.' -->
<script type="text/javascript">
    function modalHistorialPagos(curso_id) {
        //MOSTRAR MODAL
        $('.modal').modal();
        //MOSTRAR GRUPOS
        if ($.fn.DataTable.isDataTable("#tbl-historial-pagos-primaria")) {
            $('#tbl-historial-pagos-primaria').DataTable().clear().destroy();
        }

        $.fn.dataTable.ext.errMode = 'throw';
        $('#tbl-historial-pagos-primaria').dataTable({
            "destroy": true,
            "language":{"url":"api/lang/javascript/datatables"},
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "stateSave": true,
            "bSort": false,
            "ajax": {
                "type" : "GET",
                'url': base_url + "/primaria_curso/listHistorialPagos/" + curso_id,
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
                                window.location.href = 'login';
                        });
                    }
                }
            },
            "columns": [
                {data: "concepto.conpNombre"},
                {data: "pagImpPago"},
                {data: "pagRefPago"},
                {data: "pagFechaPago"},
                {data: "pagAnioPer"},
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

    function modalHistorialPagosAluClave(aluClave) {
        //MOSTRAR MODAL
        $('.modal').modal();
        //MOSTRAR GRUPOS
        if ($.fn.DataTable.isDataTable("#tbl-historial-pagos-alu-primaria")) {
            $('#tbl-historial-pagos-alu-primaria').DataTable().clear().destroy();
        }

        $.fn.dataTable.ext.errMode = 'throw';
        $('#tbl-historial-pagos-alu-primaria').dataTable({
            "destroy": true,
            "language":{"url":"api/lang/javascript/datatables"},
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "stateSave": true,
            "bSort": false,
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

                        console.log(data.responseJSON);

                        // $(".modal-titulo-periodo").html(obj.periodo.perNumero)
                        // $(".modal-periodo-anio").html(obj.periodo.perAnio)
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
                {data: "conpNombre"},
                {data: "pagImpPago"},
                {data: "pagRefPago"},
                {data: "pagFechaPago"},
                {data: "pagAnioPer"},
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

        var curso_id = $(this).data("curso-id")
        var nombres = $(this).data("nombres")
        var aluclave = $(this).data("aluclave")
        console.log("aluclave")
        console.log(aluclave)

        console.log(nombres)
        $('.modalNombres').html(nombres)

        modalHistorialPagos(curso_id)
        modalHistorialPagosAluClave(aluclave)

    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.modal').modal();

        $(document).on("click", ".btn-modal-preinscrito-detalle", function (e) {
            e.preventDefault()
            var curso_id = $(this).data("curso-id");

            $.get(base_url+`/primaria_curso/api/curso/${curso_id}`, function(res,sta) {
                $(".modalCursoId").html(res.curso.id);

                if (res.curso.curPrimariaFoto && !res.curso.curPrimariaFoto.includes(".pdf")) {
                    $('.curexaniimg').attr("src", base_url + "/exani_images/" + res.curso.curPrimariaFoto)
                    $(".curexaniimg").show()
                    $(".curexanipdf").hide()
                } else {
                    $('.curexanipdf').attr("src", base_url + "/exani_images/" + res.curso.curPrimariaFoto)
                    $(".curexaniimg").hide()
                    $(".curexanipdf").show()
                }

                $('#curExani').val( res.curso.curExani)
                $(".modalUbiClave").val(res.curso.cgt.plan.programa.escuela.departamento.ubicacion.ubiNombre)
                $(".modalDepartamentoId").val(res.curso.cgt.plan.programa.escuela.departamento.depNombre)
                $(".modalEscuelaId").val(res.curso.cgt.plan.programa.escuela.escNombre)
                $(".modalPeriodo").val(res.curso.cgt.periodo.perNumero + "-" + res.curso.cgt.periodo.perAnio)
                $(".modalPerFechaInicial").val(res.curso.cgt.periodo.perFechaInicial)
                $(".modalPerFechaFinal").val(res.curso.cgt.periodo.perFechaFinal)
                $(".modalProgNombre").val(res.curso.cgt.plan.programa.progNombre)
                $(".modalPlanClave").val(res.curso.cgt.plan.planClave)
                $(".modalCgtGradoSemestre").val(res.curso.cgt.cgtGradoSemestre + "-" + res.curso.cgt.cgtGrupo + "-"+ res.curso.cgt.cgtTurno)
                $(".modalPerNombre").val(res.curso.alumno.persona.perNombre + " " + res.curso.alumno.persona.perApellido1 + " " + res.curso.alumno.persona.perApellido2)

                $(".modalCurEstado").val(res.curEstado)
                $(".modalCurTipoIngreso").val(res.curTipoIngreso)
                $(".modalCurOpcionTitulo").val(res.curOpcionTitulo)

                $(".modalCurAnioCuotas").val(res.curso.curAnioCuotas)
                $(".modalCurImporteInscripcion").val(res.curso.curImporteInscripcion)
                $(".modalCurImporteMensualidad").val(res.curso.curImporteMensualidad)
                $(".modalCurImporteVencimiento").val(res.curso.curImporteVencimiento)
                $(".modalCurImporteDescuento").val(res.curso.curImporteDescuento)
                $(".modalCurDiasProntoPago").val(res.curso.curDiasProntoPago)

                $(".modalCurPlanPago").val(res.curPlanPago)
                $(".modalCurTipoBeca").val(res.curTipoBeca)

                $(".modalCurPorcentajeBeca").val(res.curso.curPorcentajeBeca)
                $(".modalCurObservacionesBeca").val(res.curso.curObservacionesBeca)

                $("#modalPreinscritoDetalle label").addClass("active")
            });
            $('.modal').modal();
        })

    })
</script>

<script>
$(document).on('click', '.confirmar-baja-alumno', function (e) {
    e.preventDefault();
    let curso_id = $('.modalCursoId').val();
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: `${base_url}/primaria_curso/api/curso/${curso_id}/verificar_materias_cargadas`,
        data: {'curso_id': curso_id},
        success: function(data) {
            if(data.tiene_materias_cargadas) {
                let mensajeAdicional = "El alumno tiene materias cargadas a este curso, si le da de baja, puede afectar el registro ante el SCENSY.";
                mostrarAlertConfirmacionBaja(mensajeAdicional);
            } else {
                mostrarAlertConfirmacionBaja();
            }
        },
        error: function(Xhr, textStatus, errorMessage) {
            swal({
                type: 'error',
                title: 'Error',
                text: errorMessage
            });
        }
    });
});

    function mostrarAlertConfirmacionBaja(mensajeAdicional = '') {
        swal({
            title: "¿Estás seguro?",
            text: `${mensajeAdicional} \n ¿Estas seguro que deseas dar de baja a este alumno?`,
            type: "warning",
            confirmButtonText: "Si",
            confirmButtonColor: '#3085d6',
            cancelButtonText: "No",
            showCancelButton: true
        },
        function(isConfirm) {
            if(isConfirm) {
                $(".form-baja-alumno").submit();
            }
        });
    }
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('.modal').modal();

        $(document).on("click", ".btn-modal-baja-curso", function (e) {
            e.preventDefault()
            var curso_id = $(this).data("curso-id");
            $(".modalCursoId").val(curso_id)

            $.get(base_url+`/primaria_curso/api/curso/infoBaja/` + curso_id, function(res, sta) {
                console.log(res);
                $(".modalAlumnoClave").html(res.aluClave)
                $(".modalAlumnoNombre").html(res.alumno)

                console.log(res)
                if (res.cantidadInscritos > 0) {
                    $(".modalCursosInfo").html("Esta inscrito a grupos y tiene " + res.cantidadInscritos + " materias.")
                }
                if (res.cantidadInscritos == 0) {
                    $(".modalCursosInfo").html("Este alumno no esta en grupos." )
                }
            })

            $.get(base_url+`/primaria_curso/conceptosBaja`, function(res,sta) {
                res.forEach(element => {
                    $("#conceptosBaja").append(`<option value=${element.conbClave}>${element.conbNombre}</option>`);
                });
            })

            if ($.fn.DataTable.isDataTable("#tbl-posibles-hermanos")) {
                $('#tbl-posibles-hermanos').DataTable().clear().destroy();
            }
            $.fn.dataTable.ext.errMode = 'throw';
            $('#tbl-posibles-hermanos').dataTable({
                "language":{"url":"api/lang/javascript/datatables"},
                "serverSide": true,
                "dom": '"top"i',
                "ajax": {
                    "type" : "GET",
                    'url': "primaria_curso/listPosiblesHermanos/" + curso_id,
                    beforeSend: function () {
                        $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');});
                    },
                    complete: function () {
                        $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
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
                    {data:"aluClave"},
                    {data: "nombreCompleto"},
                ],
            });

            // $.get(base_url+`/api/curso/${curso_id}`, function(res,sta) {
            // });
            $('.modal').modal();
        })
    })
</script>



<script>
    $(document).on('click', '.confirmar-alta-alumno', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
            title: "¿Estás seguro?",
            text: "¿Estas seguro que deseas dar de alta a este alumno?",
            type: "warning",
            confirmButtonText: "Si",
            confirmButtonColor: '#3085d6',
            cancelButtonText: "No",
            showCancelButton: true
        },
        function() {
            $(".form-alta-alumno").submit();
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.modal').modal();

        $(document).on("click", ".btn-modal-baja-a-regular", function (e) {
            e.preventDefault()
            var curso_id = $(this).data("curso-id");
            $(".modalCursoId").val(curso_id)

            $.get(base_url + `/primaria_curso/api/curso/infoBaja/` + curso_id, function(res, sta) {
                $(".modalAlumnoClave").html(res.aluClave)
                $(".modalAlumnoNombre").html(res.alumno)
                $(".modalProgClave").html(res.progClave)
                $(".modalProgNombre").html(res.progNombre)
                $(".modalPerNumero").html(res.perNumero)
                $(".modalPerAnio").html(res.perAnio)

                var html = "";
                $.each(res.inscritosEliminados, function( index, value ) {
                    console.log("value")
                    console.log(value)

                    html += '<div><input type="checkbox" name="inscritosEliminados[]" id="inscritosEliminados'+value.id+'"  value="'+value.id+'" checked>' +
                        '<label for="inscritosEliminados'+value.id+'">'+value.grupo.materia.matClave + ' - ' + value.grupo.materia.matNombre +'</label></div>'
                });

                $(".inscritos-eliminados").empty().append(html)
            })

            $('.modal').modal();
        })
    })
</script>





<script type="text/javascript">
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'throw';
        var tableCurso = $('#tbl-curso-primaria').dataTable({
            "language":{"url":"api/lang/javascript/datatables"},
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "stateSave": true,
            "ajax": {
                "type" : "GET",
                'url': base_url +"/primaria_curso/list",
                beforeSend: function () {
                    $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');});
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
                {data: "perAnio",name:"periodos.perAnio"},
                {data: "aluClave",name:"alumnos.aluClave"},
                {data: "aluMatricula",name:"alumnos.aluMatricula"},
                {data:'perApellido1',name: "personas.perApellido1"},
                {data:'perApellido2',name: "personas.perApellido2"},
                {data:'perNombre',name: "personas.perNombre"},
                {data: "curEstado"},
                {data: "curFechaBaja"},
                {data: "curTipoIngreso"},
                {data: "cgtGradoSemestre",name:"cgt.cgtGradoSemestre"},
                {data: "cgtGrupo",name:"cgt.cgtGrupo"},
                {data: "beca"},
                {data: "ubiClave",name:"ubicacion.ubiClave"},
                {data: "depClave",name:"departamentos.depClave"},
                {data: "escClave",name:"escuelas.escClave"},
                {data: "progClave",name:"programas.progClave"},
                {data: "planClave",name:"planes.planClave"},
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
@endsection
