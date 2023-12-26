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
            $.get(base_url+`/api/plan/semestre/${event.target.value}`,function(res,sta){
                for (i = 1; i <= res.planPeriodos; i++) {
                    $("#matSemestre").append(`<option value="${i}">${i}</option>`);
                    $("#cgtGradoSemestre").append(`<option value="${i}">${i}</option>`);
                    $("#gpoSemestre").append(`<option value="${i}">${i}</option>`);
                }
            });
        });
     });
</script>