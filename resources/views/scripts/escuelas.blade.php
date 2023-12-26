<script type="text/javascript">
    $(document).ready(function() {

        $("#departamento_id").change( event => {
            $("#escuela_id").empty();
            $("#periodo_id").empty();
            $("#programa_id").empty();
            $("#plan_id").empty();
            $("#cgt_id").empty();
            $("#materia_id").empty();
            $("#escuela_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#periodo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#programa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#perFechaInicial").val('');
            $("#perFechaFinal").val('');
            $.get(base_url+`/api/escuelas/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#escuela_id").append(`<option value=${element.id}>${element.escClave}-${element.escNombre}</option>`);
                });
            });
            $.get(base_url+`/api/periodos/${event.target.value}`,function(res2,sta){
                var perSeleccionado;
                res2.forEach(element => {
                    $("#periodo_id").append(`<option value=${element.id}>${element.perNumero}-${element.perAnio}</option>`);
                });
                //OBTENER FECHA INICIAL Y FINAL DEL PERIODO SELECCIONADO
                $.get(base_url+`/api/periodo/${perSeleccionado}`,function(res3,sta){
                    $("#perFechaInicial").val(res3.perFechaInicial);
                    $("#perFechaFinal").val(res3.perFechaFinal);
                    Materialize.updateTextFields();
                });
            });//TERMINA PERIODO
        });

     });
</script>