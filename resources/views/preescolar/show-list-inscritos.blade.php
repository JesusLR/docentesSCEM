@extends('layouts.dashboard')

@section('template_title')
    Alumnos
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('preescolar_grupo')}}" class="breadcrumb">Lista de grupos</a>
    <a href="" class="breadcrumb">Alumnos</a>
@endsection

@section('content')

<div id="table-datatables">
    <h4 class="header">Alumnos</h4>
    <div class="row">
        <div class="col s12">
            <table id="tbl-grupo" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Clave Alumno</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Nombre(s) Alumno</th>
                    <th>Año Escolar</th>
                    <th>Grado</th>
                    <th>Grupo</th>
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
        $('#tbl-grupo').dataTable({
            "language":{"url":base_url+"/api/lang/javascript/datatables"},
            "serverSide": true,
            "order": [
                [3, 'asc']
            ],
            "ajax": {
                "type" : "GET",
                'url': base_url+"/api/preescolarinscritos/" + {!! json_encode($grupo_id)!!},
                beforeSend: function () {
                    $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');});
                },
                complete: function () {
                    $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                },
            },
            "columns":[
                {data: "aluClave",name:"alumnos.aluClave"},
                {data:'perApellido1',name: "personas.perApellido1"},
                {data:'perApellido2',name: "personas.perApellido2"},
                {data:'perNombre',name: "personas.perNombre"},
                {data: "perAnio",name:"periodos.perAnio"},
                {data: "gpoGrado",name:"preescolar_grupos.gpoGrado"},
                {data: "gpoClave",name:"preescolar_grupos.gpoClave"},
                {data: "action"}
            ]
        });
    });
</script>


@endsection
