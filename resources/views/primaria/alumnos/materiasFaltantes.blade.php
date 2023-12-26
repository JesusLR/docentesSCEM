@extends('layouts.dashboard')

@section('template_title')
  Historial de calificaciones
@endsection


@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Lista de Preinscripción</a>
    <a href="{{url('primaria_curso/' . $curso->id . '/materias_faltantes')}}" class="breadcrumb">Materias Faltantes</a>
@endsection

@section('content')

@php
use App\Http\Helpers\Utils;
@endphp
<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">MATERIAS FALTANTES POR APROBAR</span>
        <p>
         Período: {{Utils::fecha_string($curso->periodo->perFechaInicial,true)}} - {{Utils::fecha_string($curso->periodo->perFechaFinal,true)}}
        </p>
        <p>Carrera: {{$curso->cgt->plan->programa->progClave}} ({{$curso->cgt->plan->planClave}}) {{$curso->cgt->plan->programa->progNombre}}</p>
        <p>
          Alumno: ({{$curso->alumno->aluClave}})
          {{$curso->alumno->persona->perNombre}}
          {{$curso->alumno->persona->perApellido1}}
          {{$curso->alumno->persona->perApellido2}}
        </p>
      <p align="right">Grado: {{$curso->cgt->cgtGradoSemestre}} {{$curso->cgt->cgtGrupo}}</p>
        {{-- NAVIGATION BAR--}}
        <nav class="nav-extended" style="margin-top: 20px;">
          <div class="nav-content">
            <ul class="tabs tabs-transparent">
              <li class="tab"><a class="active" href="#general">General</a></li>
            </ul>
          </div>
        </nav>

        {{-- GENERAL BAR--}}
        <div id="general">
          <input type="hidden" id="cursoId" data-curso-id="{{ $curso->id }}" />
          <div class="row">
            <div class="col s12">
              <table id="tbl-materias" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Clave Materia</th>
                    <th>Nombre Materia</th>
                    <th>Semestre</th>
                    <th>O/B</th>
                    <th>Ult. Examen</th>
                    <th>Calificación</th>
                    <th>Tipo</th>
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
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

<style>
  .dataTables_filter{
      display:none;
  }
</style>

@section('footer_scripts')
{{-- {!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!} --}}

{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{{-- {!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!} --}}
{!! HTML::script(asset('/js/datatables1.10.20/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/datatables1.10.20/dataTables.buttons.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/datatables1.10.20/buttons.flash.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/datatables1.10.20/jszip.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/datatables1.10.20/pdfmake.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/datatables1.10.20/vfs_fonts.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/datatables1.10.20/buttons.html5.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/datatables1.10.20/buttons.print.min.js'), array('type' => 'text/javascript')) !!}


<script type="text/javascript">
  $(document).ready(function() {


    var cursoId = $("#cursoId").data("curso-id")


    var tableCurso = $('#tbl-materias').dataTable({
      "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
      },

      "serverSide": true,
      // "dom": '"top"i',
      "pageLength": -1,
      dom: 'Bfrtip',
      buttons: [
          {
              extend: 'excel',
              className: 'btn',
              text: 'Exportar a Excel',
              filename: function(){
                  var d = new Date();
                  var n = d.getTime();
                  return 'materias_faltantes_' + n;
              },
              title:'',
              messageTop: null
          }
      ],
      // "pageLength": -1,
      "stateSave": true,
      "ajax": {
        "type" : "GET",
        'url': base_url + '/primaria_curso/listMateriasFaltantes/' + cursoId+'/',
        beforeSend: function () {
          $('.preloader').fadeIn(200, function() { $(this).append('<div id="preloader"></div>'); });
        },
        complete: function () {
          $('.preloader').fadeOut(200, function() { $('#preloader').remove(); });
        }
      },
      "columns":[
        {data: "matClave",  name:"matClave"},
        {data: "matNombre", name:"matNombre"},
        {data: "matSemestre",   name:"matSemestre"},
        {data: "matClasificacion", name:"matClasificacion"},
        {data: "histFechaExamen",  name:"histFechaExamen"},
        {data: "histCalificacion",  name:"histCalificacion"},
        {data: "histTipoAcreditacion",  name:"histTipoAcreditacion"},

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
