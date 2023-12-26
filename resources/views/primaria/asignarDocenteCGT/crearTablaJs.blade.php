<script type="text/javascript">
    $(document).ready(function() {

        function obtenerPrimariaGrupos(periodo_id, plan_id,gpoGrado, gpoGrupo) {
            $.get(base_url+`/primaria_asignar_docente/obtenerGrupos/${periodo_id}/${plan_id}/${gpoGrado}/${gpoGrupo}`, function(res,sta) {

                console.log(res)
                if(res.length > 0){
                    //creamos la tabla
                    let myTable= "<table><tr><td style='color: #000; '></td>";
                     
                        myTable+="<td style='color: #000;'><strong>Núm</strong></td>";
                        myTable+="<td style='color: #000;'><strong>Clave</strong></td>";
                        myTable+="<td style='color: #000;'><strong>Materia</strong></td>";
                        myTable+="<td style='color: #000;'><strong>Grado y grupo</strong></td>";
                        myTable+="<td style='color: #000;'><strong>Docente actual</strong></td>";
                        myTable+="<td style='color: #000;'><strong>Asignar docente</strong></td>";
                        myTable+="</tr>";
        
                        for (let i = 0; i < res.length; i++) {
                                
                            let num = [i+1];
                        

                            myTable+="<tr><td style=''><input name='materia_id[]' type='hidden' value='"+res[i].primaria_grupo_id+"'></td>";        
                            
                            myTable+="<td style=''>"+num+"</td>";        

                            myTable+="<td style=''>"+res[i].matClave+"</td>";        

                            myTable+="<td style=''>"+res[i].matNombre+"</td>";        

                            myTable+="<td style='text-aling:center'>"+res[i].gpoGrado+'-'+ res[i].gpoClave +"</td>";   

                            myTable+="<td style=''>"+res[i].nombre_completo_empleado+"</td>";        

                            myTable+="<td><input class='micheckbox' type='checkbox' name='primaria_grupo_id[]' value='"+res[i].primaria_grupo_id+"' id='"+res[i].primaria_grupo_id+"'><label for='"+res[i].primaria_grupo_id+"'></label></td>";

                            myTable+="</tr>";
                            
                               
                            
                        }
                        
                        
                        myTable+="</table>";
                        //pintamos la tabla 
                        document.getElementById('tablePrint').innerHTML = myTable;
                        $("#boton-guardar-oculto").show();
                        $("#empleado_visible").show();
                        $("#sinResultado").html("");

                       
                }else{
                    document.getElementById('tablePrint').innerHTML = "";
                    $("#boton-guardar-oculto").hide();
                    $("#empleado_visible").hide();
                    $("#sinResultado").html("Sin resultados");



                }

                   
            });
        }
        
        obtenerPrimariaGrupos($("#periodo_id").val(),$("#plan_id").val(),$("#gpoGrado").val(),$("#gpoGrupo").val())        
        $("#periodo_id").change( eventPerido => {
            $("#plan_id").change( eventPlan => {
                $("#gpoGrado").blur( eventGru => {
                    $("#gpoGrupo").blur( eventGrad => {
                        obtenerPrimariaGrupos(eventPerido.target.value, eventPlan.target.value, eventGru.target.value, eventGrad.target.value)
                    });
                });
            });
        });


        $(document).on("click", ".btn-guardar-grupo-buscar", function(e) {

            $(function() {
                $('#gpoGrupo').keydown();
                $('#gpoGrupo').keypress();
                $('#gpoGrupo').keyup();
                $('#gpoGrupo').blur();
            });
        });
     });

</script>


