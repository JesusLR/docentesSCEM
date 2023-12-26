<script type="text/javascript">
    $(document).ready(function() {
        function obtenerEscuelas (departamentoId) {

            console.log(departamentoId)
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



            $.get(base_url+`/api/escuelas/${departamentoId}`,function(res,sta){

                //seleccionar el post preservado
                var escuelaSeleccionadoOld = $("#escuela_id").data("escuela-idold")
                $("#escuela_id").empty()

                res.forEach(element => {
                    var selected = "";
                    if (element.id === escuelaSeleccionadoOld) {
                        selected = "selected";
                    }

                    $("#escuela_id").append(`<option value=${element.id} ${selected}>${element.escClave}-${element.escNombre}</option>`);
                });

                $('#escuela_id').trigger('change'); // Notify only Select2 of changes

            });

            //OBTENER PERIODOS
            $.get(base_url+`/bachiller_periodo/api/periodos/${departamentoId}`,function(res2,sta){
                var perSeleccionado;


                var periodoSeleccionadoOld = $("#periodo_id").data("periodo-idold")

                console.log(periodoSeleccionadoOld)
                $("#periodo_id").empty()
                res2.forEach(element => {

                    var selected = "";
                    if (element.id === periodoSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                    }

                    $("#periodo_id").append(`<option value=${element.perAnioPago} ${selected}>${element.perNumero}-${element.perAnio}</option>`);

                });
                //OBTENER FECHA INICIAL Y FINAL DEL PERIODO SELECCIONADO
                $.get(base_url+`/bachiller_periodo/api/periodoPerAnioPago/${perSeleccionado}`,function(res3,sta){
                    console.log(res3)

                    $("#perFechaInicial").val(res3.perFechaInicial);
                    $("#perFechaFinal").val(res3.perFechaFinal);
                    Materialize.updateTextFields();
                });

                $('#periodo_id').trigger('change'); // Notify only Select2 of changes
            });//TERMINA PERIODO
        }


        $("#departamento_id").change( event => {
            obtenerEscuelas(event.target.value)
        });
     });
</script>