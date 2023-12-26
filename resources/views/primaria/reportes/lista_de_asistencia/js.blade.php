<script type="text/javascript">

    $(document).ready(function() {

        function obtenerGrupos(programa_id, plan_id, periodo_id) {
           
            $("#gpoGrupo").empty();


            
            $("#gpoGrupo").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            

            $.get(base_url+`/primaria_reporte/lista_de_asistencia_ACD/getGruposACD/${programa_id}/${plan_id}/${periodo_id}`, function(res,sta) {

                //seleccionar el post preservado
                var grupoSeleccionadoOld = $("#grupo_id").data("gpoGrupo-idold")
                $("#gpoGrupo").empty()
                res.forEach(element => {
                    var selected = "";
                    if (element.id === grupoSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.primaria_grupo_id)
                        selected = "selected";
                    }

                    $("#gpoGrupo").append(`<option value=${element.gpoClave} ${selected}>Clave: ${element.gpoClave} -- Nombre: ${element.gpoMatComplementaria}</option>`);
                    
                });
                $('#gpoGrupo').trigger('change'); // Notify only Select2 of changes
            });
        }
        
        obtenerGrupos($("#programa_id").val(), $("#plan_id").val(),$("#periodo_id").val())
        $("#programa_id").change( eventPro => {
            $("#plan_id").change( eventPla => {
                $("#periodo_id").change( event => {

                    obtenerGrupos(eventPro.target.value, eventPla.target.value, event.target.value)
                });
            });
        });
     });


</script>