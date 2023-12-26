<script type="text/javascript">
    $(document).ready(function() {
        // OBTENER GRUPOS POR PLAN
        $("#plan_id").change( event => {
            var periodo_id = $("#periodo_id").val();
            var programa_id = $("#programa_id").val();
            var gpoGrado = $("#gpoGrado").val();

            $("#primaria_grupo_id").empty();
            $("#primaria_grupo_id").append(`<option value="">SELECCIONE UNA OPCIÓN</option>`);

            $.get(base_url+`/primaria_planeacion_docente/getGrupo/${periodo_id}/${programa_id}/${event.target.value}/${gpoGrado}`,function(res,sta){
                res.forEach(element => {
                    if(element.gpoTurno != ""){
                        $("#primaria_grupo_id").append(`<option value=${element.id}>${element.gpoGrado}-${element.gpoClave}-${element.gpoTurno}, Materia: ${element.matClave}-${element.matNombre}</option>`);
                    }else{
                        $("#primaria_grupo_id").append(`<option value=${element.id}>${element.gpoGrado}-${element.gpoClave}, Materia: ${element.matClave}-${element.matNombre}</option>`);
                    }
                    
                });
            });
        });
    
            // OBTENER GRUPOS POR PERIODO
            $("#periodo_id").change( event => {
                var programa_id = $("#programa_id").val();
                var plan_id = $("#plan_id").val();
                var gpoGrado = $("#gpoGrado").val();

                $("#primaria_grupo_id").empty();
                $("#primaria_grupo_id").append(`<option value="">SELECCIONE UNA OPCIÓN</option>`);
                /*$("#materia_id").empty();
                $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);*/
                $.get(base_url+`/primaria_planeacion_docente/getGrupo/${event.target.value}/${programa_id}/${plan_id}/${gpoGrado}`,function(res,sta){
                    res.forEach(element => {
                        if(element.gpoTurno != ""){
                            $("#primaria_grupo_id").append(`<option value=${element.id}>${element.gpoGrado}-${element.gpoClave}-${element.gpoTurno}, Materia: ${element.matClave}-${element.matNombre}</option>`);
                        }else{
                            $("#primaria_grupo_id").append(`<option value=${element.id}>${element.gpoGrado}-${element.gpoClave}, Materia: ${element.matClave}-${element.matNombre}</option>`);
                        }
                    });
                });
            });
    
      
         });

         //OBTENER GRUPOS POR GRADO 
         $("#gpoGrado").change( event => {
            var periodo_id = $("#periodo_id").val();
            var programa_id = $("#programa_id").val();
            var plan_id = $("#plan_id").val();

            $("#primaria_grupo_id").empty();
            $("#primaria_grupo_id").append(`<option value="">SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/primaria_planeacion_docente/getGrupo/${periodo_id}/${programa_id}/${plan_id}/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    if(element.gpoTurno != ""){
                        $("#primaria_grupo_id").append(`<option value=${element.id}>${element.gpoGrado}-${element.gpoClave}-${element.gpoTurno}, Materia: ${element.matClave}-${element.matNombre}</option>`);
                    }else{
                        $("#primaria_grupo_id").append(`<option value=${element.id}>${element.gpoGrado}-${element.gpoClave}, Materia: ${element.matClave}-${element.matNombre}</option>`);
                    }
                });
            });
        });
</script>