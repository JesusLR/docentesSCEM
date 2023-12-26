{!! HTML::script(asset('vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script >

    function modalGruposEquivalentes() {
        var periodo_id = $("#periodo_id").val()

        //MOSTRAR MODAL
        $('.modal').modal();
        //MOSTRAR GRUPOS
        $('#tbl-grupo-equivalente').dataTable({
            "destroy": true,
            "language": {
                "url": "api/lang/javascript/datatables"
            },
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "ajax": {
                "type": "GET",
                'url': base_url + "/bachiller_grupo/api/grupoEquivalente/" + periodo_id,
                beforeSend: function() {
                    $('.preloader').fadeIn(200, function() {
                        $(this).append('<div id="preloader"></div>');
                    });
                },
                complete: function(data) {
                    if (data.responseJSON.data) {
                        var obj = data.responseJSON.data[0];


                        console.log(obj)
                        $(".modal-titulo-periodo").html(obj.perNumero)
                        $(".modal-periodo-anio").html(obj.perAnio)
                    }

                    $('.preloader').fadeOut(200, function() {
                        $('#preloader').remove();
                    });
                },
            },
            "columns": [{
                    data: "planClave",
                    name: "planes.planClave"
                },
                {
                    data: "progClave",
                    name: "programas.progClave"
                },
                {
                    data: "matClave",
                    name: "bachiller_materias.matClave"
                },
                {
                    data: "matNombre",
                    name: "bachiller_materias.matNombre"
                },
                {
                    data: "optNombre",
                    name: "optativas.optNombre"
                },
                {
                    data: "gpoSemestre"
                },
                {
                    data: "action"
                }
            ],
            //Apply the search
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var columnClass = column.footer().className;
                    if (columnClass != 'non_searchable') {
                        var input = document.createElement("input");
                        $(input).attr("placeholder", "Buscar");
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function() {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    }
                });
            }
        });
    }

$(".btn-modal-grupos-equivalentes").on("click", function(e) {
    e.preventDefault()
    modalGruposEquivalentes()
})


$(document).ready(function() {



    // OBTENER GRUPO POR PLAN
    $("#plan_id").change(event => {
        var periodo_id = $("#periodo_id").val();
        var cgt_id = $("#cgt_id").val();
        if (periodo_id != "" && cgt_id != "") {
            $("#grupo_id").empty();
            $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            document.getElementById('tablePrint').innerHTML = "";

            //carga el combo con grupos materias del mismo grado y difentes grupo clave
            $.get(base_url + `/bachiller_paquete/todos/getCgtsGruposTodos/${event.target.value}/${periodo_id}/${cgt_id}`, function(todos, sta) {
                todos.forEach(elementTodos => {
                    $("#grupo_id").append(`<option value=${elementTodos.id}><b>Materia:</b> ${elementTodos.bachiller_materia.matClave}-${elementTodos.bachiller_materia.matNombre} <b>Maestro:</b> ${elementTodos.bachiller_empleado.id}-${elementTodos.bachiller_empleado.empNombre} ${elementTodos.bachiller_empleado.empApellido1} ${elementTodos.bachiller_empleado.empApellido2} <b>CGT:</b> ${elementTodos.gpoGrado}-${elementTodos.gpoClave}-${elementTodos.gpoTurno} </option>`);  
                    
                });
            });


            //carga el combo con grupos materias del mismo grado y misma grupo clave
            $.get(base_url + `/bachiller_paquete/apiss/cgts/${event.target.value}/${periodo_id}/${cgt_id}`, function(res, sta) {
                
                //creamos la tabla
            var myTable = "<table id='tbl-paquetes' cellspacing='0' width='100%' id='tablePrint'><tr>";
                myTable += "<th><strong>Materia</strong></th>";
                myTable += "<th><strong>Docente</strong></th>";
                myTable += "<th><strong>Curso-Grupo-Turno</strong></th>";
                myTable += "<th><strong>Acciones</strong></th>";
    
                myTable += "</tr>";
                res.forEach(element => {
    
                    myTable += `<tr id="grupo${element.id}">`;
                    myTable += `<td>${element.bachiller_materia.matClave}-${element.bachiller_materia.matNombre}</td>`;
                    myTable += `<td>${element.bachiller_empleado.id}-${element.bachiller_empleado.empNombre} ${element.bachiller_empleado.empApellido1} ${element.bachiller_empleado.empApellido2}</td>`;
                    myTable += `<td>${element.gpoGrado}-${element.gpoClave}-${element.gpoTurno}</td>`;
                    myTable += `<td><input name="grupos[${element.id}]" type="hidden" value="${element.id}" readonly="true"/><a href="javascript:;" onclick="eliminarGrupo(${element.id})" class="button button--icon js-button js-ripple-effect" title="Eliminar grupo"><i class="material-icons">delete</i></a></td>`;
                    myTable += "</tr>";
                });
    
                myTable += "</table>";
                //pintamos la tabla 
                document.getElementById('tablePrint').innerHTML = myTable;
            });
        }
    });

    // OBTENER GRUPO POR PLAN
    $("#periodo_id").change(event => {
        var plan_id = $("#plan_id").val();
        var cgt_id = $("#cgt_id").val();
        if (plan_id != "" && cgt_id != "") {
            $("#grupo_id").empty();
            $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            document.getElementById('tablePrint').innerHTML = "";

            //carga el combo con grupos materias del mismo grado y difentes grupo clave
            $.get(base_url + `/bachiller_paquete/todos/getCgtsGruposTodos/${plan_id}/${event.target.value}/${cgt_id}`, function(todos, sta) {
                todos.forEach(elementTodos => {
                    $("#grupo_id").append(`<option value=${elementTodos.id}><b>Materia:</b> ${elementTodos.bachiller_materia.matClave}-${elementTodos.bachiller_materia.matNombre} <b>Maestro:</b> ${elementTodos.bachiller_empleado.id}-${elementTodos.bachiller_empleado.empNombre} ${elementTodos.bachiller_empleado.empApellido1} ${elementTodos.bachiller_empleado.empApellido2} <b>CGT:</b> ${elementTodos.gpoGrado}-${elementTodos.gpoClave}-${elementTodos.gpoTurno} </option>`);  
                    
                });
            });

            $.get(base_url + `/bachiller_paquete/apiss/cgts/${plan_id}/${event.target.value}/${cgt_id}`, function(res, sta) {
                //creamos la tabla
            var myTable = "<table id='tbl-paquetes' cellspacing='0' width='100%' id='tablePrint'><tr>";
                myTable += "<th><strong>Materia</strong></th>";
                myTable += "<th><strong>Docente</strong></th>";
                myTable += "<th><strong>Curso-Grupo-Turno</strong></th>";
                myTable += "<th><strong>Acciones</strong></th>";
    
                myTable += "</tr>";
                res.forEach(element => {
    
                    myTable += `<tr id="grupo${element.id}">`;
                    myTable += `<td>${element.bachiller_materia.matClave}-${element.bachiller_materia.matNombre}</td>`;
                    myTable += `<td>${element.bachiller_empleado.id}-${element.bachiller_empleado.empNombre} ${element.bachiller_empleado.empApellido1} ${element.bachiller_empleado.empApellido2}</td>`;
                    myTable += `<td>${element.gpoGrado}-${element.gpoClave}-${element.gpoTurno}</td>`;
                    myTable += `<td><input name="grupos[${element.id}]" type="hidden" value="${element.id}" readonly="true"/><a href="javascript:;" onclick="eliminarGrupo(${element.id})" class="button button--icon js-button js-ripple-effect" title="Eliminar grupo"><i class="material-icons">delete</i></a></td>`;
                    myTable += "</tr>";
                });
    
                myTable += "</table>";
                //pintamos la tabla 
                document.getElementById('tablePrint').innerHTML = myTable;
            });
        }
    });
    // OBTENER GRUPO POR SEMESTRE
    $("#cgt_id").change(event => {
        var plan_id = $("#plan_id").val();
        var periodo_id = $("#periodo_id").val();
        $("#grupo_id").empty();
        $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
        document.getElementById('tablePrint').innerHTML = "";

        //carga el combo con grupos materias del mismo grado y difentes grupo clave
        $.get(base_url + `/bachiller_paquete/todos/getCgtsGruposTodos/${plan_id}/${periodo_id}/${event.target.value}`, function(todos, sta) {
            todos.forEach(elementTodos => {
                $("#grupo_id").append(`<option value=${elementTodos.id}><b>Materia:</b> ${elementTodos.bachiller_materia.matClave}-${elementTodos.bachiller_materia.matNombre} <b>Maestro:</b> ${elementTodos.bachiller_empleado.id}-${elementTodos.bachiller_empleado.empNombre} ${elementTodos.bachiller_empleado.empApellido1} ${elementTodos.bachiller_empleado.empApellido2} <b>CGT:</b> ${elementTodos.gpoGrado}-${elementTodos.gpoClave}-${elementTodos.gpoTurno} </option>`);  
                
            });
        });


        $.get(base_url + `/bachiller_paquete/apiss/cgts/${plan_id}/${periodo_id}/${event.target.value}`, function(res, sta) {


            //creamos la tabla
            var myTable = "<table id='tbl-paquetes' cellspacing='0' width='100%' id='tablePrint'><tr>";
            myTable += "<th><strong>Materia</strong></th>";
            myTable += "<th><strong>Docente</strong></th>";
            myTable += "<th><strong>Curso-Grupo-Turno</strong></th>";
            myTable += "<th><strong>Acciones</strong></th>";

            myTable += "</tr>";
            res.forEach(element => {

                myTable += `<tr id="grupo${element.id}">`;
                myTable += `<td>${element.bachiller_materia.matClave}-${element.bachiller_materia.matNombre}</td>`;
                myTable += `<td>${element.bachiller_empleado.id}-${element.bachiller_empleado.empNombre} ${element.bachiller_empleado.empApellido1} ${element.bachiller_empleado.empApellido2}</td>`;
                myTable += `<td>${element.gpoGrado}-${element.gpoClave}-${element.gpoTurno}</td>`;
                myTable += `<td><input name="grupos[${element.id}]" type="hidden" value="${element.id}" readonly="true"/><a href="javascript:;" onclick="eliminarGrupo(${element.id})" class="button button--icon js-button js-ripple-effect" title="Eliminar grupo"><i class="material-icons">delete</i></a></td>`;
                myTable += "</tr>";
            });

            myTable += "</table>";
            //pintamos la tabla 
            document.getElementById('tablePrint').innerHTML = myTable;


        });
    });
}); 
</script>