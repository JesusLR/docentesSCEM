<script type="text/javascript">

    $(document).ready(function() {

        $("#estado_id").change( event => {
            $("#municipio_id").empty();
            $("#municipio_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/municipios/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#municipio_id").append(`<option value=${element.id}>${element.munNombre}</option>`);
                });
            });
        });

     });
</script>