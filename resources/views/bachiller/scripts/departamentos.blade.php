<script type="text/javascript">
    $(document).ready(function() {

        function obtenerDepartamentos(ubicacionId) {
            console.log(ubicacionId);

            console.log("aqui")
            $("#departamento_id").empty();


            $("#escuela_id").empty();
            $("#periodo_id").empty();
            $("#programa_id").empty();
            $("#plan_id").empty();
            $("#cgt_id").empty();
            $("#materia_id").empty();
            $("#departamento_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#escuela_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#periodo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#programa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            $("#perFechaInicial").val('');
            $("#perFechaFinal").val('');

            $.get(base_url+`/api/departamentos/${ubicacionId}`, function(res,sta) {

                //seleccionar el post preservado
                var departamentoSeleccionadoOld = $("#departamento_id").data("departamento-idold")
                $("#departamento_id").empty()
                res.forEach(element => {
                    var selected = "";
                    if (element.id === departamentoSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                    }

                    $("#departamento_id").append(`<option value=${element.id} ${selected}>${element.depClave}-${element.depNombre}</option>`);
                });
                $('#departamento_id').trigger('change'); // Notify only Select2 of changes
            });
        }
        
        obtenerDepartamentos($("#ubicacion_id").val())
        $("#ubicacion_id").change( event => {
            obtenerDepartamentos(event.target.value)
        });
     });
</script>