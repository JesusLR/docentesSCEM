<script type="text/javascript">

    $(document).ready(function() {

        // OBTENER ALUMNOS PREINSCRITOS POR CGT
        $("#cgt_id").change( event => {
            var cgt_id = $("#cgt_id").val();
            $("#curso_id").empty();
            $("#curso_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#grupo_id").empty();
            $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/cursos/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#curso_id").append(`<option value=${element.id}>${element.alumno.aluClave}-${element.alumno.persona.perNombre} ${element.alumno.persona.perApellido1} ${element.alumno.persona.perApellido2}</option>`);
                });
            });
        });
     });
</script>