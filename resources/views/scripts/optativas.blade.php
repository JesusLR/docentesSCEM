<script type="text/javascript">

    $(document).ready(function() {

        // OBTENER OPTATIVAS POR MATERIA
        $("#materia_id").change( event => {
            $("#seccion_optativa").hide();
            $("#optativa_id").empty();
            $("#optativa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/optativas/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#seccion_optativa").show();
                    $("#optativa_id").append(`<option value=${element.id}>${element.optNumero}-${element.optNombre}</option>`);
                });
            });
        });

     });
</script>