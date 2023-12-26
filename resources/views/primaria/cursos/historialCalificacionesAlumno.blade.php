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
    <a href="{{url('primaria_curso/' . $curso->id . '/historial_calificaciones_alumno')}}" class="breadcrumb">Historial de calificaciones</a>
@endsection

@section('content')


<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">HISTORIAL DE CALIFICACIONES</span>
        <p>
          ({{$curso->alumno->aluClave}})
          {{$curso->alumno->persona->perNombre}}
          {{$curso->alumno->persona->perApellido1}}
          {{$curso->alumno->persona->perApellido2}}
        </p>

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
              <table id="tbl-calificaciones" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Clave Materia</th>
                    <th>Nombre Materia</th>
                    <th>Año</th>
                    <th>Período</th>
                    <th>Cal. Parcial 1</th>
                    <th>Cal. Parcial 2</th>
                    <th>Cal. Parcial 3</th>
                    <th>Promedio Parciales</th>
                    <th>Calificación Ordinario</th>
                    <th>Calificación Final</th>
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


@section('footer_scripts')
{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}


<script type="text/javascript">
  $(document).ready(function() {


    var cursoId = $("#cursoId").data("curso-id")


    var tableCurso = $('#tbl-calificaciones').dataTable({
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
      "dom": '"top"i',
      "pageLength": 5,
      "stateSave": true,
      "ajax": {
        "type" : "GET",
        'url': base_url + '/primaria_curso/api/curso/' + cursoId + '/listHistorialCalifAlumnos/',
        beforeSend: function () {
          $('.preloader').fadeIn(200, function() { $(this).append('<div id="preloader"></div>'); });
        },
        complete: function () {
          $('.preloader').fadeOut(200, function() { $('#preloader').remove(); });
        }
      },
      "columns":[
        {data: "matClave",  name:"materias.matClave"},
        {data: "matNombre", name:"materias.matNombre"},
        {data: "perAnio",   name:"periodos.perAnio"},
        {data: "perNumero", name:"periodos.perNumero"},
        {data: "inscCalificacionParcial1",  name:"calificaciones.inscCalificacionParcial1"},
        {data: "inscCalificacionParcial2",  name:"calificaciones.inscCalificacionParcial2"},
        {data: "inscCalificacionParcial3",  name:"calificaciones.inscCalificacionParcial3"},
        {data: "inscPromedioParciales",     name:"calificaciones.inscPromedioParciales"},
        {data: "inscCalificacionOrdinario", name:"calificaciones.inscCalificacionOrdinario"},
        {data: "incsCalificacionFinal",     name:"calificaciones.incsCalificacionFinal"},

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
