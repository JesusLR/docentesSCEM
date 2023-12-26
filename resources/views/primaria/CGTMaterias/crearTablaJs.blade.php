<script type="text/javascript">
    $(document).ready(function() {

        function obtenerMaterias(periodo_id, programa_id,plan_id, cgt_id) {
            $.get(base_url+`/primaria_cgt_materias/obtenerMaterias/${periodo_id}/${programa_id}/${plan_id}/${cgt_id}`, function(res,sta) {

                
                        if(res.length > 0){
                            //creamos la tabla
                            let myTable= "<table><tr><td style='color: #000; '></td>";
                                myTable+="<td style='color: #000;'></td>";
                                myTable+="<td style='color: #000;'></td>";
                                myTable+="<td style='color: #000;'></td>";
                                myTable+="<td style='color: #000;'><strong>Núm</strong></td>";
                                myTable+="<td style='color: #000;'><strong>Clave</strong></td>";
                                myTable+="<td style='color: #000;'><strong>Materia</strong></td>";
                                myTable+="<td style='color: #000;'><strong>Asignar</strong></td>";
                                myTable+="</tr>";
                
                                for (let i = 0; i < res.length; i++) {
                                        
                                    let num = [i+1];
                                

                                    myTable+="<tr><td style=''><input name='materia_id[]' type='hidden' value='"+res[i].id+"'></td>";        
                                    
                                    myTable+="<td style=''><input name='cgtGrupo' id='cgtGrupo' type='hidden' value='"+res[i].cgtGrupo+"'></td>";        
                                        
                                    myTable+="<td style=''><input name='cgtTurno' id='cgtTurno' type='hidden' value='"+res[i].cgtTurno+"'></td>";  
                                    
                                    myTable+="<td style=''><input name='matSemestre' id='matSemestre' type='hidden' value='"+res[i].matSemestre+"'></td>";        


                                    myTable+="<td style=''>"+num+"</td>";        
    
                                    myTable+="<td style=''>"+res[i].matClave+"</td>";        

                                    myTable+="<td style=''>"+res[i].matNombre+"</td>";        
                                    
                                    myTable+="<td><input class='micheckbox' type='checkbox' checked name='primaria_materia[]' value='"+res[i].id+"' id='"+res[i].id+"'><label for='"+res[i].id+"'></label></td>";

                                    myTable+="</tr>";
                                    
                                       
                                    
                                }
                                
                                
                                myTable+="</table>";
                                //pintamos la tabla 
                                document.getElementById('tablePrint').innerHTML = myTable;

                                //muestra el boton guardar
                                //$("#boton-guardar").show();
                        }else{
                            document.getElementById('tablePrint').innerHTML = "";

                        }
                   
            });
        }
        
        obtenerMaterias($("#periodo_id").val(),$("#programa_id").val(),$("#plan_id").val(),$("#cgt_id").val())        
        $("#periodo_id").change( eventPerido => {
            $("#programa_id").change( eventPrograma => {
                $("#plan_id").change( eventPlan => {
                    $("#cgt_id").change( event => {
                        obtenerMaterias(eventPerido.target.value, eventPrograma.target.value, eventPlan.target.value, event.target.value)
                    });
                });
            });
        });
     });

</script>


