{!! HTML::script(asset('vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script>

    function modalGruposEquivalentes() {
        var periodo_id = $("#periodo_id").val()

        //MOSTRAR MODAL
        $('.modal').modal();
        //MOSTRAR GRUPOS
        $('#tbl-grupo-equivalente').dataTable({
            "destroy": true, 
            "language":{"url":"api/lang/javascript/datatables"},
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "ajax": {
                "type" : "GET",
                'url': base_url + "/secundaria_grupo/api/grupoEquivalente/" + periodo_id,
                beforeSend: function () {
                    $('.preloader').fadeIn(200, function() {
                        $(this).append('<div id="preloader"></div>');
                    });
                },
                complete: function (data) {
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
            "columns": [
                {data: "planClave",   name: "planes.planClave"},
                {data: "progClave",   name: "programas.progClave"},
                {data: "matClave",    name: "secundaria_materias.matClave"},
                {data: "matNombre",   name: "secundaria_materias.matNombre"},
                {data: "optNombre",   name: "optativas.optNombre"},
                {data: "gpoSemestre"},
                {data: "action"}
            ],
            //Apply the search
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var columnClass = column.footer().className;
                    if(columnClass != 'non_searchable'){
                        var input = document.createElement("input");
                        $(input).attr("placeholder", "Buscar");
                        $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    }
                });
            }
        });
    }

    $(".btn-modal-grupos-equivalentes").on("click", function (e) {
        e.preventDefault()
        modalGruposEquivalentes()
    })


    $(document).ready(function(){



        // OBTENER GRUPO POR PLAN
        $("#plan_id").change( event => {
            var periodo_id = $("#periodo_id").val();
            var semestre_id = $("#semestre_id").val();
            if(periodo_id != "" && semestre_id != ""){
                $("#grupo_id").empty();
                $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $.get(base_url+`/secundaria_cgt/apiss/cgts/${event.target.value}/${periodo_id}/${semestre_id}`,function(res,sta){
                    res.forEach(element => {
                        element.forEach(element2 => {
                            $("#grupo_id").append(`<option value=${element2.id}><b>Materia:</b> ${element2.secundaria_materia.matClave}-${element2.secundaria_materia.matNombre} <b>Maestro:</b> ${element2.secundaria_empleado.id}-${element2.secundaria_empleado.empNombre} ${element2.secundaria_empleado.empApellido1} ${element2.secundaria_empleado.empApellido2} <b>CGT:</b> ${element2.cgt.cgtGradoSemestre}-${element2.cgt.cgtGrupo}-${element2.cgt.cgtTurno} </option>`);
                        });
                    });
                });
            }
        });

        // OBTENER GRUPO POR PLAN
        $("#periodo_id").change( event => {
            var plan_id = $("#plan_id").val();
            var semestre_id = $("#semestre_id").val();
            if(plan_id != "" && semestre_id != ""){
                $("#grupo_id").empty();
                $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $.get(base_url+`/secundaria_cgt/apiss/cgts/${plan_id}/${event.target.value}/${semestre_id}`,function(res,sta){
                    res.forEach(element => {
                        element.forEach(element2 => {
                            $("#grupo_id").append(`<option value=${element2.id}><b>Materia:</b> ${element2.secundaria_materia.matClave}-${element2.secundaria_materia.matNombre} <b>Maestro:</b> ${element2.secundaria_empleado.id}-${element2.secundaria_empleado.empNombre} ${element2.secundaria_empleado.empApellido1} ${element2.secundaria_empleado.empApellido2} <b>CGT:</b> ${element2.cgt.cgtGradoSemestre}-${element2.cgt.cgtGrupo}-${element2.cgt.cgtTurno} </option>`);
                        });
                    });
                });
            }
        });
        // OBTENER GRUPO POR SEMESTRE
        $("#semestre_id").change( event => {
            var plan_id = $("#plan_id").val();
            var periodo_id = $("#periodo_id").val();
            $("#grupo_id").empty();
            $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/secundaria_cgt/apiss/cgts/${plan_id}/${periodo_id}/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#grupo_id").append(`<option value=${element.id}><b>Materia:</b> ${element.secundaria_materia.matClave}-${element.secundaria_materia.matNombre} <b>Maestro:</b> ${element.secundaria_empleado.id}-${element.secundaria_empleado.empNombre} ${element.secundaria_empleado.empApellido1} ${element.secundaria_empleado.empApellido2} <b>CGT:</b> ${element.gpoSemestre}-${element.gpoClave}-${element.gpoTurno} </option>`);
                });
            });
        });
    });
</script>