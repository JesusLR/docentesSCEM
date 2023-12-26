<script type="text/javascript">

    $(document).ready(function() {

        $("#paisId").change( event => {
            $("#estado_id").empty();
            $("#municipio_id").empty();
            $("#estado_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#municipio_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/estados/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#estado_id").append(`<option value=${element.id}>${element.edoNombre}</option>`);
                });
            });
        });

     });
</script>