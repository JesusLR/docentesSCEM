{!! HTML::script(asset('vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}
<script>
    $(document).ready(function(){
        //MOSTRAR MODAL
        $('.modal').modal();
        //MOSTRAR GRUPOS
        $('#tbl-grupo').dataTable({
            "language":{"url":"api/lang/javascript/datatables"},
            "serverSide": true,
            "ajax": {
                "type" : "GET",
                'url': base_url+"/api/grupoEquivalente",
                beforeSend: function () {
                    $('.preloader').fadeIn('slow',function(){$(this).append('<div id="preloader"></div>');;});
                },
                complete: function () {
                    $('.preloader').fadeOut('slow',function(){$('#preloader').remove();});
                },
            },
            "columns":[
                {data: "periodo.perNumero"},
                {data: "periodo.perAnio"},
                {data: "plan.planClave"},
                {data: "plan.programa.progClave"},
                {data: "materia.matClave"},
                {data: "materia.matNombre"},
                {data: "gpoSemestre"},
                {data: "action"}
            ]
        });

        // OBTENER GRUPO POR PLAN
        $("#plan_id").change( event => {
            var periodo_id = $("#periodo_id").val();
            var semestre_id = $("#semestre_id").val();
            if(periodo_id != "" && semestre_id != ""){
                $("#grupo_id").empty();
                $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                $.get(base_url+`/api/cgts/${event.target.value}/${periodo_id}/${semestre_id}`,function(res,sta){
                    res.forEach(element => {
                        element.forEach(element2 => {
                            $("#grupo_id").append(`<option value=${element2.id}><b>Materia:</b> ${element2.materia.matClave}-${element2.materia.matNombre} <b>Maestro:</b> ${element2.empleado.id}-${element2.empleado.persona.perNombre} ${element2.empleado.persona.perApellido1} ${element2.empleado.persona.perApellido2} <b>CGT:</b> ${element2.cgt.cgtGradoSemestre}-${element2.cgt.cgtGrupo}-${element2.cgt.cgtTurno} </option>`);
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
                $.get(base_url+`/api/cgts/${plan_id}/${event.target.value}/${semestre_id}`,function(res,sta){
                    res.forEach(element => {
                        element.forEach(element2 => {
                            $("#grupo_id").append(`<option value=${element2.id}><b>Materia:</b> ${element2.materia.matClave}-${element2.materia.matNombre} <b>Maestro:</b> ${element2.empleado.id}-${element2.empleado.persona.perNombre} ${element2.empleado.persona.perApellido1} ${element2.empleado.persona.perApellido2} <b>CGT:</b> ${element2.cgt.cgtGradoSemestre}-${element2.cgt.cgtGrupo}-${element2.cgt.cgtTurno} </option>`);
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
            $.get(base_url+`/api/cgts/${plan_id}/${periodo_id}/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#grupo_id").append(`<option value=${element.id}><b>Materia:</b> ${element.materia.matClave}-${element.materia.matNombre} <b>Maestro:</b> ${element.empleado.id}-${element.empleado.persona.perNombre} ${element.empleado.persona.perApellido1} ${element.empleado.persona.perApellido2} <b>CGT:</b> ${element.gpoSemestre}-${element.gpoClave}-${element.gpoTurno} </option>`);
                });
            });
        });
    });
</script>