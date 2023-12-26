@extends('layouts.dashboard')

@section('template_title')
    Extracurricular
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('/')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('extracurricular')}}" class="breadcrumb">Lista de grupos extracurricular</a>
@endsection

@section('content')

<div id="table-datatables">
    <h4 class="header">GRUPOS EXTRACURRICULAR</h4>
    <div class="row">
        <div class="col s12">
            <table id="tbl-grupo" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Ubicación</th>
                    <th>Periodo</th>
                    <th>Año</th>
                    <th>Plan</th>
                    <th>Programa</th>
                    <th>Clave-Materia</th>
                    <th>Materia</th>
                    <th>Grado</th>
                    <th>Grupo</th>
                    <th>Turno</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Ubicación</th>
                    <th>Periodo</th>
                    <th>Año</th>
                    <th>Plan</th>
                    <th>Programa</th>
                    <th>Clave-Materia</th>
                    <th>Materia</th>
                    <th>Grado-Semestre</th>
                    <th>Grupo</th>
                    <th>Turno</th>
                    <th>Acciones</th>
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
            "ajax": {
                "type" : "GET",
                'url': base_url+"/api/extracurricular",
                beforeSend: function () {
                    $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');;});
                },
                complete: function () {
                    $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                },
            },
            "columns":[
                {data: "plan.programa.escuela.departamento.ubicacion.ubiNombre"},
                {data: "periodo.perNumero"},
                {data: "periodo.perAnio"},
                {data: "plan.planClave"},
                {data: "plan.programa.progClave"},
                {data: "materia.matClave"},
                {data: "materia.matNombre"},
                {data: "gpoSemestre"},
                {data: "gpoClave"},
                {data: "gpoTurno"},
                {data: "action"}
            ]
        });
    });
</script>


@endsection