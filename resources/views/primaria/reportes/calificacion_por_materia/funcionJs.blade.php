<script type="text/javascript">

    $(document).ready(function() {

        function obtenerGrupos(programa_id, plan_id, periodo_id) {
           
            $("#grupo_id").empty();


            
            $("#grupo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            

            $.get(base_url+`/primaria_reporte/calificacion_por_materia/getGrupos/${programa_id}/${plan_id}/${periodo_id}`, function(res,sta) {

                //seleccionar el post preservado
                var grupoSeleccionadoOld = $("#grupo_id").data("grupo_id-idold")
                $("#grupo_id").empty()
                res.forEach(element => {
                    var selected = "";
                    if (element.id === grupoSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.primaria_grupo_id)
                        selected = "selected";
                    }

                    $("#grupo_id").append(`<option value=${element.id} ${selected}>Grado: ${element.gpoGrado}, Grupo: ${element.gpoClave}, Materia: ${element.matNombre}</option>`);
                    
                });
                $('#grupo_id').trigger('change'); // Notify only Select2 of changes
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



    $("select[name=tipoReporte]").change(function(){
       if($('select[name=tipoReporte]').val() == "porMes"){
           $("#vistaPorMes").show();
           $("#vistaPorBimestre").hide();
           $("#vistaPorTrimestre").hide();

           $('#mesEvaluar').prop("required", true);
            $("#bimestreEvaluar").removeAttr("required");
            $("#trimestreEvaluar").removeAttr("required");
           
       }

       if($('select[name=tipoReporte]').val() == "porBimestre"){
            $("#vistaPorMes").hide();
            $("#vistaPorBimestre").show();
            $("#vistaPorTrimestre").hide();

            $('#bimestreEvaluar').prop("required", true);
            $("#mesEvaluar").removeAttr("required");
            $("#trimestreEvaluar").removeAttr("required");

       }

       if($('select[name=tipoReporte]').val() == "porTrimestre"){
        
            $("#vistaPorMes").hide();
            $("#vistaPorBimestre").hide();
            $("#vistaPorTrimestre").show();

            $('#trimestreEvaluar').prop("required", true);
            $("#mesEvaluar").removeAttr("required");
            $("#bimestreEvaluar").removeAttr("required");
       }
    });
</script>