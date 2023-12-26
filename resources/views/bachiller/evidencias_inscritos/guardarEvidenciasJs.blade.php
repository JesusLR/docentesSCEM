

<script type="text/javascript">

    $(document).ready(function() {


        $(document).on("click", "#submit-button", function(e) {

            var bachiller_inscrito_id = $("input[id='bachiller_inscrito_id']").map(function(){return $(this).val();}).get();
            var ievPuntos = $("input[id='ievPuntos']").map(function(){return $(this).val();}).get();
            var ievFaltas = $("input[id='ievFaltas']").map(function(){return $(this).val();}).get();
            var bachiller_evidencia_id = $("#bachiller_evidencia_id").val();
            var movimiento = $("#movimiento").val();
            var bachiller_inscrito_evidencia_id = $("input[id='bachiller_inscrito_evidencia_id']").map(function(){return $(this).val();}).get();


            
            
        
           
            //e.preventDefault();
        
            $.ajax({
                url: "{{route('bachiller.bachiller_evidencias_inscritos.store')}}",
                method: "POST",
                dataType: "json",
                data: {
                    "_token": $("meta[name=csrf-token]").attr("content"),
                    bachiller_evidencia_id: bachiller_evidencia_id,
                    bachiller_inscrito_id: bachiller_inscrito_id,
                    ievPuntos: ievPuntos,
                    ievFaltas: ievFaltas,
                    movimiento: movimiento,
                    bachiller_inscrito_evidencia_id: bachiller_inscrito_evidencia_id           
                },
                beforeSend: function () {
                                  
                    //$("guardar_calificaciones").prop('disabled', true);
        
                    var html = "";
                    html += "<div class='preloader-wrapper big active'>"+
                        "<div class='spinner-layer spinner-blue-only'>"+
                          "<div class='circle-clipper left'>"+
                            "<div class='circle'></div>"+
                          "</div><div class='gap-patch'>"+
                            "<div class='circle'></div>"+
                          "</div><div class='circle-clipper right'>"+
                            "<div class='circle'></div>"+
                          "</div>"+
                        "</div>"+
                      "</div>";
                    
                    html += "<p>" + "</p>"
        
                    swal({
                        html:true,
                        title: "Guardando...",
                        text: html,
                        showConfirmButton: false
                        //confirmButtonText: "Ok",
                    })
        
                },
                success: function(data){
                    
                    console.log(data.res)
                    if(data.res == "puntosMayores"){
                        swal("Escuela Modelo", "No se puede guardar las evidencias debido que los puntos capturados son mayores a los puntos maximos de la evidencia", "info");
        
                    }
                    
                    if(data.res == "true"){
                        swal("Escuela Modelo", "Las evidencias se guardaron con éxito", "success");

                        location.reload();
        
                    }
                },
                error: function(){
                    swal("Escuela Modelo", "Error inesperado, intende nuevamente", "error");
                }
            });
        });

    });
</script>