<script type="text/javascript">
    $(document).ready(function() {



        //Buscar si hay materia seleccionada en la tabla de bachiller_evidencias 

        var matSemestre = $("#matSemestre").val();
        var periodo_id = $("#periodo_id").val();
        var materia_id = $("#materia_id").val();
        var materia_acd_id = $("#materia_acd_id").val();

        var contadorRestantes = $("#contadorRestantes").val();
        

        if($('select[id=materia_acd_id]').val() != "NULL"){

            
    
                $.get(base_url+`/bachiller_evidencias/getMateriasEvidenciasPeriodoACD/${periodo_id}/${materia_id}/${matSemestre}/${materia_acd_id}`,function(res,sta){
                    var datos = res.length;
                    var sumarPuntos = 0;
                    var sumarPuntosProceso = 0;
                    var sumarPuntosProducto = 0;
    
    
                    if(datos > 0){
                        res.forEach(function (element, i) {
    
                            sumarPuntos = parseInt(sumarPuntos) + parseInt(element.eviPuntos);
                            $("#contador").val(sumarPuntos);
    
                            if(element.eviTipo == "A"){
                                sumarPuntosProceso = parseInt(sumarPuntosProceso) + parseInt(element.eviPuntos);
                                $("#puntosProceso").val(sumarPuntosProceso);
    
                            }
    
                            if(element.eviTipo == "P"){
                                sumarPuntosProducto = parseInt(sumarPuntosProducto) + parseInt(element.eviPuntos);
                                $("#puntosProducto").val(sumarPuntosProducto);
    
                            }
    
                            if(sumarPuntos < 100){
                                $("#contadorRestantes").val(parseInt(contadorRestantes) - sumarPuntos);
                                swal("Evidencias actuales "+datos+" ", "El período y la materia seleccionada ya tiene evidencias agregadas, se mostrara los puntos evidencias actuales y los puntos restantes por agregar, así como los puntos totales de evidencias tipo Proceso y tipo Producto", "info");
                            }else{
                                $("#contadorRestantes").val(0);
                                swal("", "El período y la materia seleccionada ya ha alcanzado el limite de puntaje de evidencia", "info");
                            }
    
                        });
                    }else{
                        $("#contador").val(0);
                        $("#contadorRestantes").val(100);
                        $("#puntosProceso").val(0);
                        $("#puntosProducto").val(0);
    
    
                    }
                });
        }else{
            $.get(base_url+`/bachiller_evidencias/sinACDgetMateriasEvidenciasPeriodo/${periodo_id}/${materia_id}/${matSemestre}`,function(res,sta){
                var datos = res.length;
                var sumarPuntos = 0;
                var sumarPuntosProceso = 0;
                var sumarPuntosProducto = 0;
    
    
    
                if(datos > 0){
                    res.forEach(element => {
    
                        sumarPuntos = parseInt(sumarPuntos) + parseInt(element.eviPuntos);
                        $("#contador").val(sumarPuntos);
    
                        if(element.eviTipo == "A"){
                            sumarPuntosProceso = parseInt(sumarPuntosProceso) + parseInt(element.eviPuntos);
                            $("#puntosProceso").val(sumarPuntosProceso);
    
                        }
    
                        if(element.eviTipo == "P"){
                            sumarPuntosProducto = parseInt(sumarPuntosProducto) + parseInt(element.eviPuntos);
                            $("#puntosProducto").val(sumarPuntosProducto);
    
                        }
    
                        if(sumarPuntos < 100){
                            $("#contadorRestantes").val(parseInt(contadorRestantes) - sumarPuntos);
                            swal("Evidencias actuales "+datos+" ", "El período y la materia seleccionada ya tiene evidencias agregadas, se mostrara los puntos evidencias actuales y los puntos restantes por agregar, así como los puntos totales de evidencias tipo Proceso y tipo Producto", "info");
    
                        }else{
                            $("#contadorRestantes").val(0);
                            swal("", "El período y la materia seleccionada ya ha alcanzado el limite de puntaje de evidencia", "info");
                        }
    
                    });
                }else{
                    $("#contador").val(0);
                    $("#contadorRestantes").val(100);
                    $("#puntosProceso").val(0);
                    $("#puntosProducto").val(0);
    
                }
    
            });
        }

     });
</script>