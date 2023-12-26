<script type="text/javascript">

    $(document).ready(function() {

        // OBTENER MATERIAS OPTATIVAS POR CGT
        $("#plan_id").change( event => {
            $("#materia_id").empty();
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/materias/optativas/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#materia_id").append(`<option value=${element.id}>${element.matClave} - ${element.matNombre}</option>`);
                });
            });
        });

     });
</script>