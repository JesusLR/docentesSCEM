@extends('layouts.dashboard')

@section('template_title')
    Primaria empleado
@endsection

@section('head')
    {!! HTML::style(asset('/vendors/data-tables/css/jquery.dataTables.min.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_empleado')}}" class="breadcrumb">Lista de empleados</a>
@endsection

@section('content')

<div id="table-datatables">
    <h4 class="header">EMPLEADOS</h4>
    @php use App\Models\User; @endphp
    @if (User::permiso("empleado") != "A" || User::permiso("empleado") != "B")
    <a href="{{ route('primaria_empleado.create') }}" class="btn-large waves-effect  darken-3" type="button">Agregar
        <i class="material-icons left">add</i>
    </a>
    <br>
    <br>
    @endif
    <div class="row">
        <div class="col s12">
            <table id="table-empleados-primaria" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>Credencial</th>
                    <th>Nomina</th>
                    <th>Teléfono</th>
                    <th>Estatus</th>
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
        $.fn.dataTable.ext.errMode = 'throw';
        $('#table-empleados-primaria').dataTable({
            "language":{"url":base_url + "/api/lang/javascript/datatables"},
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "stateSave": true,
            "ajax": {
                "type" : "GET",
                'url': base_url+"/primaria_empleado/list",
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
                {data: "empleado_id",name:"empleado_id"},
                {data: "nombreCompleto"},
                {data: "empCredencial"},
                {data: "empNomina"},
                {data: "empTelefono",name:"empTelefono"},
                {data: "empEstado"},
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


        $('#table-empleados-primaria').on('click', '.btn-darBaja', function () {

            var empleado_id = $(this).data('id');
            darBaja(empleado_id);
        });

        $('#table-empleados-primaria').on('click', '.btn-borrar', function () {
            var empleado_id = $(this).data('id');
            borrar_empleado(empleado_id);
        });


    });


    function darBaja(empleado_id) {

        swal({
            title: 'Dar de baja?',
            text: 'Estás seguro que deseas continuar?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonText: 'Si, dar de baja al empleado',
            closeOnConfirm: false },
            function () {

                $.ajax({
                    type: 'POST',
                    url: base_url + '/primaria_empleado/darBaja/' + empleado_id,
                    dataType: 'json',
                    data: {empleado_id: empleado_id, '_token':'{!!csrf_token()!!}'},
                    success: function (data) {
                        if(data) {
                            var periodo = data.periodo;
                            swal({
                                title: 'No es posible.',
                                text: 'Este empleado tiene cursos asignados. \n'+
                                      'Ultimo periodo de docencia: \n' + periodo.perNumero +' / '+periodo.perAnio,
                                type: 'warning'
                            });
                        }else{
                            swal({
                                title: 'Hecho!',
                                text: 'Empleado dado de baja con éxito.',
                                type: 'success'
                            });
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        console.log(errorMessage);
                    }
                }); //ajax.

            });

    }//darBaja.

    function borrar_empleado(empleado_id) {
        $.ajax({
            type: 'GET',
            url: base_url + '/primaria_empleado/verificar_delete/'+empleado_id,
            //dataType: 'json',
            data: {empleado_id: empleado_id},
            success: function(data) {
                if(data) {
                    swal({
                        title: 'Borrar empleado '+empleado_id,
                        text: 'Seguro que deseas eliminar este empleado?',
                        showCancelButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Sí',
                        closeOnConfirm: false
                    }, function(){
                        $('#delete_'+empleado_id).submit();
                    });
                }else{
                    swal({
                        title: 'No se puede realizar la acción.',
                        text: 'Este empleado no puede ser eliminado \n'+
                               'Las razones pueden ser que se encuentra registrado como usuario en el sistema, '+
                               'o que ya ha impartido cursos o tiene cursos asignados.'
                    });
                }
            },
            error: function(jqXhr, textStatus, errorMessage) {
                console.log(errorMessage);
            }
        });
    }

</script>

@endsection

