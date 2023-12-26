@extends('layouts.dashboard')

@section('template_title')
    Primaria ahorro escolar
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{route('primaria_grupo.index')}}" class="breadcrumb">Inicio</a>
    <label class="breadcrumb">Lista de ahorro escolar</label>
@endsection

@section('content')

<div id="table-datatables">
    <h4 class="header">LISTA DE AHORRO ESCOLAR</h4>
    <a href="{{ route('primaria.primaria_ahorro_escolar.create') }}" class="btn-large waves-effect  darken-3" type="button">Agregar
        <i class="material-icons left">add</i>
    </a>
    <a href="{{ route('primaria.reporte.ahorro_escolar.index') }}" class="btn-large waves-effect  darken-3" type="button">Consultar ahorros<i class="material-icons left">insert_drive_file</i>
    </a>
    <br>
    <br>
    <div class="row">
        <div class="col s12">
            <table id="tbl-ahorro-alumnos" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Año</th>
                    <th>Clave Alumno</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Nombre(s)</th>
                    <th>Edo</th>
                    <th>Grado</th>
                    <th>Grupo</th>
                    <th>Fecha Movimiento</th>
                    <th>Importe</th>
                    <th>Movimiento</th>
                    <th>Saldo Final</th>
                    <th>Beca</th>
                    <th>Ubic</th>
                    <th>Dep</th>
                    <th>Esc</th>
                    <th>Prog</th>
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


@endsection

@section('footer_scripts')

{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script type="text/javascript">
    $(document).ready(function() {
        $('#tbl-ahorro-alumnos').dataTable({
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
                'url': base_url+"/primaria_ahorro_escolar/list",
                beforeSend: function () {
                    $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');;});
                },
                complete: function () {
                    $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                },
            },
            "columns":[
                {data: "anio_pago"},
                {data: "clave_pago"},
                {data: "apellido_paterno"},
                {data: "apellido_materno"},
                {data: "nombres_alumno"},
                {data: "estado_curso"},
                {data: "grado_alumno"},
                {data: "grupo_alumno"},
                {data: "fecha"},
                {data: "importe"},
                {data: "movimiento"},
                {data: "saldo_final"},
                {data: "tipo_beca"},
                {data: "ubicacion"},
                {data: "departamento"},
                {data: "escuela"},
                {data: "programa"},
                {data: "plan"},
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