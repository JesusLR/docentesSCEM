<script type="text/javascript">

    {{--  para traer municipios en historia clinica   --}}
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

        $("#estadoMadre_id").change( event => {
            $("#municipioMadre_id").empty();
            $("#municipioMadre_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/municipios/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#municipioMadre_id").append(`<option value=${element.id}>${element.munNombre}</option>`);
                });
            });
        });


        $("#estadoPadre_id").change( event => {
            $("#municipioPadre_id").empty();
            $("#municipioPadre_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/municipios/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#municipioPadre_id").append(`<option value=${element.id}>${element.munNombre}</option>`);
                });
            });
        });

     });
</script>