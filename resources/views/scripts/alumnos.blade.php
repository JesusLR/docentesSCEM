<script type="text/javascript">

$(document).ready(function() {
    $.get(base_url+`/api/alumnos`,function(res,sta){
        res.forEach(element => {
            $("#alumno_id").append(`<option value=${element.id}>${element.aluClave}-${element.persona.perNombre} ${element.persona.perApellido1} ${element.persona.perApellido2}</option>`);
        });
    });
});
</script>