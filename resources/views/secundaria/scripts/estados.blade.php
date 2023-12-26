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

        //para traer estados en historia clinica
        $("#paisMadre_Id").change( event => {
            $("#estadoMadre_id").empty();
            $("#municipioMadre_id").empty();
            $("#estadoMadre_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#municipioMadre_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/estados/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#estadoMadre_id").append(`<option value=${element.id}>${element.edoNombre}</option>`);
                });
            });
        });

        //para traer estados en historia clinica
        $("#paisPadre_Id").change( event => {
            $("#estadoPadre_id").empty();
            $("#municipioPadre_id").empty();
            $("#estadoPadre_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#municipioPadre_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/estados/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#estadoPadre_id").append(`<option value=${element.id}>${element.edoNombre}</option>`);
                });
            });
        });

     });
</script>