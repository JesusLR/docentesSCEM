<script type="text/javascript">

    $(document).ready(function() {
        // OBTENER MATERIA SEMESTRE Y SEMESTRE CGT
        $("#plan_id").change( event => {
            $("#matSemestre").empty();
            $("#matSemestre").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgtGradoSemestre").empty();
            $("#cgtGradoSemestre").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            $("#gpoSemestre").empty();
            $("#gpoSemestre").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);


            $.get(base_url + `/api/plan/semestre/${event.target.value}`, function(res,sta) {
                //seleccionar el post preservado
                var gpoSemestreSeleccionadoOld = $(".gpoSemestreOld").data("gposemestre-idold")
                console.log(gpoSemestreSeleccionadoOld)

                $("#gpoSemestre").empty()
                var numeroSemestres = res.planPeriodos;
                if (numeroSemestres == 0)
                {
                    numeroSemestres = 9;
                }
                //for (i = 1; i <= res.planPeriodos; i++) {
                for (i = 1; i <= numeroSemestres; i++) {
                    var selected = "";
                    if (i === gpoSemestreSeleccionadoOld) {
                        selected = "selected";
                    }


                    $("#matSemestre").append(`<option value="${i}">${i}</option>`);
                    $("#cgtGradoSemestre").append(`<option value="${i}">${i}</option>`);
                    $("#gpoSemestre").append(`<option value="${i}" ${selected}>${i}</option>`);
                }

                $('#gpoSemestre').trigger('change'); // Notify only Select2 of changes
            });
        });
     });
</script>