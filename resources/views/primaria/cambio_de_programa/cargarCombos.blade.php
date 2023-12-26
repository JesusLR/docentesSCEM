<script type="text/javascript">

    $(document).ready(function() {

        $("#escuela_id").change( event => {
            $("#programa_id2").empty();

            $("#plan_id2").empty();
            $("#cgt_id2").empty();
            $("#materia_id").empty();
            $("#programa_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#plan_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

        
            $.get(base_url+`/primaria_programa/api/programas/${event.target.value}`,function(res,sta){
                //seleccionar el post preservado
                var programaSeleccionadoOld = $("#programa_id2").data("programa-idold")
                $("#programa_id2").empty()
                $("#programa_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                

                res.forEach(element => {
                    var selected = "";
                    if (element.id === programaSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                    }

                    $("#programa_id2").append(`<option value=${element.id} ${selected}>${element.progClave}-${element.progNombre}</option>`);
                });

                $('#programa_id2').trigger('change'); // Notify only Select2 of changes
            });
        });

        // OBTENER PLANES
        $("#programa_id2").change( event => {
            $("#plan_id2").empty();

        
            $("#cgt_id2").empty();
            $("#materia_id").empty();
            $("#plan_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            console.log("event.target.value")
            console.log(event.target.value)
            
            $.get(base_url+`/primaria_plan/api/planes/${event.target.value}`,function(res,sta){
                //seleccionar el post preservado
                var planSeleccionadoOld = $("#plan_id2").data("plan-idold")
                $("#plan_id2").empty()
                
                res.forEach(element => {
                    var selected = "";
                    if (element.id === planSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                    }


                    $("#plan_id2").append(`<option value=${element.id} ${selected}>${element.planClave}</option>`);
                });

                $('#plan_id2').trigger('change'); // Notify only Select2 of changes
            });
        });

        // OBTENER CGTS POR PLAN
        $("#plan_id2").change( event => {
            var periodo_id = $("#periodo_id").val();
            $("#cgt_id2").empty();
            $("#cgt_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").empty();
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_cgt/api/cgts_sin_n/${event.target.value}/${periodo_id}`,function(res,sta){
                res.forEach(element => {
                    $("#cgt_id2").append(`<option value=${element.id}>${element.cgtGradoSemestre}-${element.cgtGrupo}-${element.cgtTurno}</option>`);
                });
            });
        });

        // OBTENER CGTS POR PERIODO
        $("#periodo_id").change( event => {
            var plan_id2 = $("#plan_id2").val();
            $("#cgt_id2").empty();
            $("#materia_id").empty();
            $("#cgt_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_cgt/api/cgts_sin_n/${plan_id}/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#cgt_id2").append(`<option value=${element.id}>${element.cgtGradoSemestre}-${element.cgtGrupo}-${element.cgtTurno}</option>`);
                });
            });
        });

     });
</script>