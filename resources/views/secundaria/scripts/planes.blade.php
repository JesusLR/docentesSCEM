<script type="text/javascript">

    $(document).ready(function() {
        // OBTENER PLANES
        $("#programa_id").change( event => {
            $("#plan_id").empty();

        
            $("#cgt_id").empty();
            $("#materia_id").empty();
            $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            console.log("event.target.value")
            console.log(event.target.value)
            
            $.get(base_url+`/secundaria_plan/api/planes/${event.target.value}`,function(res,sta){
                //seleccionar el post preservado
                var planSeleccionadoOld = $("#plan_id").data("plan-idold")
                $("#plan_id").empty()
                
                res.forEach(element => {
                    var selected = "";
                    if (element.id === planSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                    }


                    $("#plan_id").append(`<option value=${element.id} ${selected}>${element.planClave}</option>`);
                });

                $('#plan_id').trigger('change'); // Notify only Select2 of changes
            });
        });

     });
</script>