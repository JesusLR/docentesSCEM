<script type="text/javascript">

    $(document).ready(function() {

        // OBTENER GRUPOS POR ALUMNO PREINSCRITO SELECCIONADO
        $("#curso_id").change( event => {
            $("#grupo_id").empty();
            $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/grupos/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $("#grupo_id").append(`<option value=${element.id}>Grupo: ${element.gpoSemestre}-${element.gpoClave}-${element.gpoTurno} Materia: ${element.materia.matClave}-${element.materia.matNombre} Maestro:${element.empleado.id}-${element.empleado.persona.perNombre} ${element.empleado.persona.perApellido1} ${element.empleado.persona.perApellido2}</option>`);
                });
            });
        });

     });
</script>