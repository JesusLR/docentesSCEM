@extends('layouts.dashboard')

@section('template_title')
    Primaria planes
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <label class="breadcrumb">Lista de planes</label>
@endsection

@section('content')

<div id="table-datatables">
    <h4 class="header">PLANES</h4>
    @php use App\Models\User; @endphp
    @if (User::permiso("plan") != "D" && User::permiso("plan") != "P")
    <a href="{{ route('primaria.primaria_plan.create') }}" class="btn-large waves-effect  darken-3" type="button">Agregar
        <i class="material-icons left">add</i>
    </a>
    <br>
    <br>
    @endif
    <div class="row">
        <div class="col s12">
            <table id="tbl-plan" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Campus</th>
                    <th>Escuela</th>
                    <th>Programa</th>
                    <th>Plan</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
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

    {{-- Modal Cambiar planEstado --}}
    <div id="modalCambiarEstadoPrimaria" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col s12">
                    <input type="hidden" value="" class="planId">
                    <h4>Modificar Estatus Del Plan</h4>
                    <select name="planEstado" id="planEstado" class="browser-default validate select2" id="" style="width: 100%;">
                        <option value="">SELECCIONE UN ESTADO</option>
                        <option value="L">Liquidación</option>
                        <option value="N">Nuevo</option>
                        <option value="C">Cerrado</option>
                        <option value="X">Extraoficial</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <button type="button" class="guardar-estatus-plan-primaria btn-large waves-effect  darken-3 btn-flat" style="color: #fff;">
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


@endsection

@section('footer_scripts')

{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script type="text/javascript">
    $(document).ready(function() {
        $('#tbl-plan').dataTable({
            "language":{"url":base_url+"/api/lang/javascript/datatables"},
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "stateSave": true,
            "order": [
                [2, 'asc']
            ],
            "ajax": {
                "type" : "GET",
                'url': base_url+"/primaria_plan/list",
                beforeSend: function () {
                    $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');;});
                },
                complete: function () {
                    $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                },
            },
            "columns":[
                {data: "ubiNombre",name:"ubicacion.ubiNombre"},
                {data: "escNombre",name:"escuelas.escNombre"},
                {data: "progNombre",name:"programas.progNombre"},
                {data: "planClave"},
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

    $(document).on("click", ".btn-modal-estatus-plan-primaria", function (e) {
        e.preventDefault()
        $('.modal').modal();
        var planId = $(this).data("plan-id");
        $("#modalCambiarEstadoPrimaria").find(".planId").val(planId);
        getPlan_putPlanEstado(planId, 'planEstado');
    });

    $(document).on("click", ".guardar-estatus-plan-primaria", function (e) {
        e.preventDefault()

        var planEstado = $("#planEstado").val()
        var planId = $("#modalCambiarEstadoPrimaria").find(".planId").val()

        if (planEstado && planId) {
            $.ajax({
                data: {
                    "planId": planId,
                    "planEstado" : planEstado,
                    "_token": $("meta[name=csrf-token]").attr("content")
                },
                type: "POST",
                dataType: "JSON",
                url: base_url + "/primaria_plan/cambiarPlanEstado",
            })
            .done(function( data, textStatus, jqXHR ) {
                if (data.res) {
                    swal({
                        title: "Escuela modelo",
                        text: data.msg,
                        type: "success",
                    });
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

    });

    function getPlan_putPlanEstado(planId, targetSelect) {
        var select = $('#' + targetSelect);
        $.ajax({
            type: 'GET',
            url: base_url + '/primaria_plan/get_plan/' + planId,
            dataType: 'json',
            data: {'plan_id': planId},
            success: function(plan) {
                if(plan) {
                    select.val(plan.planEstado).select2();
                }
            },
            error: function(Xhr, textStatus, errorMessage) {
                console.log(errorMessage);
            }
        });
    }//getPLan_putPlanEstado
</script>


@endsection
