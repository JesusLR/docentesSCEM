<script type="text/javascript">
    $(document).ready(function() {

        function obserBoleta(plan_id, periodo_id,cgt_id, mes) {
            $.get(base_url+`/primaria_obs_boleta/obtenerObsBoleta/${plan_id}/${periodo_id}/${cgt_id}/${mes}`, function(res,sta) {

                console.log(mes)

                                
                if(res.length > 0){
                    //creamos la tabla
                    let myTable= "<table><tr><td style='color: #000; '></td>";
                        myTable+="<td style='color: #000;'>Observaciones</td>";                    
                        myTable+="</tr>";
        
                 
                        for (let i = 0; i < res.length; i++) {
                                
                                              

                            myTable+="<tr><td style=''><input name='id' type='hidden' value='"+res[i].id+"'></td>";        

                            //mostrar observaciones de septiembre 
                            if(mes == "SEPTIEMBRE")
                            {
                                if(res[i].observacionSep != null && res[i].observacionSep != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionSep+"</textarea></td>";        
    
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de septiembre' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
    
                                }
                            }
                            //mostrar observaciones de octubre 
                            if(mes == "OCTUBRE")
                            {
                                if(res[i].observacionOct != null && res[i].observacionOct != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionOct+"</textarea></td>";        
    
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de octubre' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
    
                                }
                            }

                            //mostrar observaciones de NOVIEMBRE 
                            if(mes == "NOVIEMBRE")
                            {
                                if(res[i].observacionNov != null && res[i].observacionNov != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionNov+"</textarea></td>";  
    
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de noviembre' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
    
                                }
                            }

                            //mostrar observaciones de DICIEMBRE 
                            if(mes == "DICIEMBRE")
                            {
                                if(res[i].observacionDic != null && res[i].observacionDic != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionDic+"</textarea></td>";  
    
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de diciembre' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
    
                                }
                            }

                            //mostrar observaciones de ENERO 
                            if(mes == "ENERO")
                            {
                                if(res[i].observacionEne != null && res[i].observacionEne != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionEne+"</textarea></td>";  
    
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de enero' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
    
                                }
                            }

                            //mostrar observaciones de FEBRERO 
                            if(mes == "FEBRERO")
                            {
                                if(res[i].observacionFeb != null && res[i].observacionFeb != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionFeb+"</textarea></td>";  
    
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de febrero' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
    
                                }
                            }

                            //mostrar observaciones de MARZO 
                            if(mes == "MARZO")
                            {
                                if(res[i].observacionMar != null && res[i].observacionMar != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionMar+"</textarea></td>";  
     
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de marzo' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
     
                                }
                            }

                            //mostrar observaciones de ABRIL 
                            if(mes == "ABRIL")
                            {
                                if(res[i].observacionAbr != null && res[i].observacionAbr != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionAbr+"</textarea></td>";  
     
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de abril' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
     
                                }
                            }

                            //mostrar observaciones de MAYO 
                            if(mes == "MAYO")
                            {
                                if(res[i].observacionMay != null && res[i].observacionMay != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionMay+"</textarea></td>";  
     
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de mayo' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
     
                                }
                            }

                            //mostrar observaciones de JUNIO 
                            if(mes == "JUNIO")
                            {
                                if(res[i].observacionJun != null && res[i].observacionJun != ""){
                                    myTable+="<td><textarea class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'>"+res[i].observacionJun+"</textarea></td>";  
     
                                }else{
                                    myTable+="<td><textarea placeholder='Agregar observación para el mes de junio' class='materialize-textarea' name='observaciones' id='observaciones' cols='30' rows='10'></textarea></td>";        
     
                                }
                            }

                            
                            
                         

                            myTable+="</tr>";
                            
                               
                            
                        }
                        
                        myTable+="</table>";
                        //pintamos la tabla 
                        document.getElementById('tablePrint').innerHTML = myTable;

                        //muestra el boton guardar
                        $("#boton-guardar").show();
                        document.getElementById('nohayObs').innerHTML = "";
                }else{
                    document.getElementById('tablePrint').innerHTML = "";

                    document.getElementById('nohayObs').innerHTML = "<label for='observaciones'>Observación</label>"+
                    "<textarea class='materialize-textarea' placeholder='Agregar observacion' name='observacionesCero' id='observacionesCero' cols='30' rows='10'></textarea>";

                    $("#boton-guardar").show();

                }
                   
            });
        }
        
        obserBoleta($("#periodo_id").val(),$("#plan_id").val(),$("#cgt_id").val(),$("#mes_id").val())        
        $("#periodo_id").change( eventPeri => {
            $("#plan_id").change( eventPlan => {
                $("#cgt_id").change( eventCgt => {
                    $("#mes_id").change( event => {
                        obserBoleta(eventPeri.target.value, eventPlan.target.value, eventCgt.target.value, event.target.value)
                    });
                });
            });
        });
     });

</script>

