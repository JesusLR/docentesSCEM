<script type="text/javascript">

    $(document).ready(function() {

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
       
    });

</script>