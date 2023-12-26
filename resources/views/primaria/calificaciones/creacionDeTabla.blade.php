  {{--  obtener meses vigentes de evaluacion  --}}

  <script type="text/javascript">
    $(document).ready(function() {

        function obtenerMesEvaluacion(mes_id) {

            $("#primaria_grupo_evidencia_id").empty();



            $.get(base_url+`/primaria_grupo/getMesEvidencias/${mes_id}`, function(res,sta) {

                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#primaria_grupo_evidencia_id").data("primaria_grupo_evidencia-id")
                $("#primaria_grupo_evidencia_id").empty()

                if(res != ""){

                    res.forEach(element => {
                        var selected = "";
                        if (element.id === numeroEvaSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }

                        if(element.mes == "ENERO"){
                            $("#primaria_grupo_evidencia_id").append(`<option value=${element.id} ${selected}>DICIEMBRE-ENERO</option>`);
                        }else{
                            $("#primaria_grupo_evidencia_id").append(`<option value=${element.id} ${selected}>${element.mes}</option>`);
                        }
                        

                    });
                    $('#primaria_grupo_evidencia_id').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#primaria_grupo_evidencia_id").append(`<option value="" selected disabled>NO HAY MES EVIDICENCIA PARA ESTE GRUPO</option>`);

                }

            });
        }

        obtenerMesEvaluacion($("#primaria_grupo_id").val())
        $("#primaria_grupo_id").change( event => {
            obtenerMesEvaluacion(event.target.value)
        });
     });
</script>






<script type="text/javascript">

    $(document).ready(function() {

        $("#primaria_grupo_evidencia_id").change( event => {

            var primaria_grupo_id = $("#primaria_grupo_id").val();
           
       

            $.get(base_url+`/api/getCalificacionesAlumnos/${event.target.value}/${primaria_grupo_id}`,function(res,sta){

                var calificaciones = res.calificaciones;
                var primaria_grupos_evidencias = res.primaria_grupos_evidencias;

                
                if(calificaciones.length > 0){
                    var numero_evaluacion = primaria_grupos_evidencias[0].numero_evaluacion;
                    $("#numero_evaluacion").append(`<option value=${numero_evaluacion}>${numero_evaluacion}</option>`);
                    var numero_evidencias = primaria_grupos_evidencias[0].numero_evidencias;
                    $("#numero_evidencias").append(`<option value=${numero_evidencias}>${numero_evidencias}</option>`);
                    var porcentaje_total = primaria_grupos_evidencias[0].porcentaje_total;

                    var concepto_evidencia1 = primaria_grupos_evidencias[0].concepto_evidencia1;
                    var concepto_evidencia2 = primaria_grupos_evidencias[0].concepto_evidencia2;
                    var concepto_evidencia3 = primaria_grupos_evidencias[0].concepto_evidencia3;
                    var concepto_evidencia4 = primaria_grupos_evidencias[0].concepto_evidencia4;
                    var concepto_evidencia5 = primaria_grupos_evidencias[0].concepto_evidencia5;
                    var concepto_evidencia6 = primaria_grupos_evidencias[0].concepto_evidencia6;
                    var concepto_evidencia7 = primaria_grupos_evidencias[0].concepto_evidencia7;
                    var concepto_evidencia8 = primaria_grupos_evidencias[0].concepto_evidencia8;
                    var concepto_evidencia9 = primaria_grupos_evidencias[0].concepto_evidencia9;
                    var concepto_evidencia10 = primaria_grupos_evidencias[0].concepto_evidencia10;

                    var porcentaje_evidencia1 = primaria_grupos_evidencias[0].porcentaje_evidencia1;
                    var porcentaje_evidencia2 = primaria_grupos_evidencias[0].porcentaje_evidencia2;
                    var porcentaje_evidencia3 = primaria_grupos_evidencias[0].porcentaje_evidencia3;
                    var porcentaje_evidencia4 = primaria_grupos_evidencias[0].porcentaje_evidencia4;
                    var porcentaje_evidencia5 = primaria_grupos_evidencias[0].porcentaje_evidencia5;
                    var porcentaje_evidencia6 = primaria_grupos_evidencias[0].porcentaje_evidencia6;
                    var porcentaje_evidencia7 = primaria_grupos_evidencias[0].porcentaje_evidencia7;
                    var porcentaje_evidencia8 = primaria_grupos_evidencias[0].porcentaje_evidencia8;
                    var porcentaje_evidencia9 = primaria_grupos_evidencias[0].porcentaje_evidencia9;
                    var porcentaje_evidencia10 = primaria_grupos_evidencias[0].porcentaje_evidencia10;
                    var mes = primaria_grupos_evidencias[0].mes;

                    var materia = calificaciones[0].matNombre;

                    $("#matNombre").val(materia);
                


                    $(".btn-guardar").show();
                    $(".btn-guardar").text("ACTUALIZAR"),
                    $("#info").html("");

                    //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                    if(porcentaje_total == 100){
                        $("#btn-ocultar-si-es-menor-a-cien").show();
                        $("#alerta-menos-de-ciente").hide();
                        $("#alerta-min-max-calif").show();

                    }else{
                        $("#btn-ocultar-si-es-menor-a-cien").hide();
                        $("#alerta-menos-de-ciente").show();
                        $("#alerta-min-max-calif").hide();

                    }

                    var myTable= "<table class='hoverTable'><tr>";
                        myTable+=`<th class="title1" style="display: none"><p>id cal</p></th>`;
                        myTable+=`<th class="title2" style="display: none;" ><p>inscrito id</p></th>`;
                        myTable+=`<th scope="col"> <p>#</p></th>`;
                        myTable+=`<th scope="col">CLAVE <p>PAGO</p></th>`;
                        myTable+=`<th scope="col">NOMBRE <p>COMPLETO</p></th>`;
                        
                        if(materia == "CONDUCTA"){
                            if(mes == "ENERO"){
                                myTable+=`<th scope="col">RETARDOS<p>DICIEMBRE-ENERO</p></th>`;
                                myTable+=`<th scope="col">FALTAS<p id="faltasQueMes">DICIEMBRE-ENERO</p></th>`;
                            }else{
                                myTable+=`<th scope="col">RETARDOS<p>${mes}</p></th>`;
                                myTable+=`<th scope="col">FALTAS<p>${mes}</p></th>`;
                            }
                        }

                        
                        
                        if(numero_evidencias == 1){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 2){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 3){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 4){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi4" scope="col"><p id="nombreEvidencia4">${concepto_evidencia4}</p> <p> <label style="color:#fff" id="evi4">${porcentaje_evidencia4}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 5){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi4" scope="col"><p id="nombreEvidencia4">${concepto_evidencia4}</p> <p> <label style="color:#fff" id="evi4">${porcentaje_evidencia4}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi5" scope="col"><p id="nombreEvidencia5">${concepto_evidencia5}</p> <p> <label style="color:#fff" id="evi5">${porcentaje_evidencia5}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 6){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi4" scope="col"><p id="nombreEvidencia4">${concepto_evidencia4}</p> <p> <label style="color:#fff" id="evi4">${porcentaje_evidencia4}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi5" scope="col"><p id="nombreEvidencia5">${concepto_evidencia5}</p> <p> <label style="color:#fff" id="evi5">${porcentaje_evidencia5}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi6" scope="col"><p id="nombreEvidencia6">${concepto_evidencia6}</p> <p> <label style="color:#fff" id="evi6">${porcentaje_evidencia6}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 7){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi4" scope="col"><p id="nombreEvidencia4">${concepto_evidencia4}</p> <p> <label style="color:#fff" id="evi4">${porcentaje_evidencia4}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi5" scope="col"><p id="nombreEvidencia5">${concepto_evidencia5}</p> <p> <label style="color:#fff" id="evi5">${porcentaje_evidencia5}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi6" scope="col"><p id="nombreEvidencia6">${concepto_evidencia6}</p> <p> <label style="color:#fff" id="evi6">${porcentaje_evidencia6}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi7" scope="col"><p id="nombreEvidencia7">${concepto_evidencia7}</p> <p> <label style="color:#fff" id="evi7">${porcentaje_evidencia7}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 8){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi4" scope="col"><p id="nombreEvidencia4">${concepto_evidencia4}</p> <p> <label style="color:#fff" id="evi4">${porcentaje_evidencia4}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi5" scope="col"><p id="nombreEvidencia5">${concepto_evidencia5}</p> <p> <label style="color:#fff" id="evi5">${porcentaje_evidencia5}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi6" scope="col"><p id="nombreEvidencia6">${concepto_evidencia6}</p> <p> <label style="color:#fff" id="evi6">${porcentaje_evidencia6}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi7" scope="col"><p id="nombreEvidencia7">${concepto_evidencia7}</p> <p> <label style="color:#fff" id="evi7">${porcentaje_evidencia7}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi8" scope="col"><p id="nombreEvidencia8">${concepto_evidencia8}</p> <p> <label style="color:#fff" id="evi8">${porcentaje_evidencia8}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 9){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi4" scope="col"><p id="nombreEvidencia4">${concepto_evidencia4}</p> <p> <label style="color:#fff" id="evi4">${porcentaje_evidencia4}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi5" scope="col"><p id="nombreEvidencia5">${concepto_evidencia5}</p> <p> <label style="color:#fff" id="evi5">${porcentaje_evidencia5}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi6" scope="col"><p id="nombreEvidencia6">${concepto_evidencia6}</p> <p> <label style="color:#fff" id="evi6">${porcentaje_evidencia6}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi7" scope="col"><p id="nombreEvidencia7">${concepto_evidencia7}</p> <p> <label style="color:#fff" id="evi7">${porcentaje_evidencia7}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi8" scope="col"><p id="nombreEvidencia8">${concepto_evidencia8}</p> <p> <label style="color:#fff" id="evi8">${porcentaje_evidencia8}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi9" scope="col"><p id="nombreEvidencia9">${concepto_evidencia9}</p> <p> <label style="color:#fff" id="evi9">${porcentaje_evidencia9}</label> <label style="color:#fff">%</label></p></th>`;
                        }
                        if(numero_evidencias == 10){
                            myTable+=`<th class="classEvi1" scope="col"><p id="nombreEvidencia1">${concepto_evidencia1}</p> <p> <label style="color:#fff" id="evi1">${porcentaje_evidencia1}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi2" scope="col"><p id="nombreEvidencia2">${concepto_evidencia2}</p> <p> <label style="color:#fff" id="evi2">${porcentaje_evidencia2}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi3" scope="col"><p id="nombreEvidencia3">${concepto_evidencia3}</p> <p> <label style="color:#fff" id="evi3">${porcentaje_evidencia3}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi4" scope="col"><p id="nombreEvidencia4">${concepto_evidencia4}</p> <p> <label style="color:#fff" id="evi4">${porcentaje_evidencia4}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi5" scope="col"><p id="nombreEvidencia5">${concepto_evidencia5}</p> <p> <label style="color:#fff" id="evi5">${porcentaje_evidencia5}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi6" scope="col"><p id="nombreEvidencia6">${concepto_evidencia6}</p> <p> <label style="color:#fff" id="evi6">${porcentaje_evidencia6}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi7" scope="col"><p id="nombreEvidencia7">${concepto_evidencia7}</p> <p> <label style="color:#fff" id="evi7">${porcentaje_evidencia7}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi8" scope="col"><p id="nombreEvidencia8">${concepto_evidencia8}</p> <p> <label style="color:#fff" id="evi8">${porcentaje_evidencia8}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi9" scope="col"><p id="nombreEvidencia9">${concepto_evidencia9}</p> <p> <label style="color:#fff" id="evi9">${porcentaje_evidencia9}</label> <label style="color:#fff">%</label></p></th>`;
                            myTable+=`<th class="classEvi10" scope="col"><p id="nombreEvidencia10">${concepto_evidencia10}</p> <p> <label style="color:#fff" id="evi10">${porcentaje_evidencia10}</label> <label style="color:#fff">%</label></p></th>`;                        
                        }
                        
                        
                        if(mes == "ENERO"){
                            myTable+=`<th class="classPromedioMes" scope="col">PROMEDIO<p id="queMes">DICIEMBRE-ENERO</p></th>`;
                        }else{
                            myTable+=`<th class="classPromedioMes" scope="col">PROMEDIO<p id="queMes">${mes}</p></th>`;
                        }
                        

                        myTable+="</tr>";

      
                    calificaciones.forEach(function callback(element, index) {

                       

                        myTable+="<tr>";        

                        myTable+=`<td style="display:none"><input name='id[]' type='text' value='${element.id}'></td>`;
                        myTable+=`<td style="display:none"><input name='primaria_inscrito_id[]' type='text' value='${element.primaria_inscrito_id}'></td>`;
                        myTable+=`<td>${index+1}</td>`;
                        myTable+=`<td>${element.aluClave}</td>`;
                        myTable+=`<td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>`;


                        if(materia == "CONDUCTA"){
                            if(mes == "SEPTIEMBRE"){

                                if(element.retTotalSep == "" || element.retTotalSep == null || element.retTotalSep == 0)
                                var retTotalSep = "";
                                else
                                var retTotalSep = element.retTotalSep;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalSep' value='${retTotalSep}' name='retTotalSep[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalSep='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalSep == "" || element.falTotalSep == null || element.falTotalSep == 0)
                                var falTotalSep = "";
                                else
                                var falTotalSep = element.falTotalSep;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalSep' value='${falTotalSep}' name='falTotalSep[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalSep='${element.primaria_inscrito_id}'></td>`;
                            }
    
                            if(mes == "OCTUBRE"){
    
                                if(element.retTotalOct == "" || element.retTotalOct == null || element.retTotalOct == 0)
                                var retTotalOct = "";
                                else
                                var retTotalOct = element.retTotalOct;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalOct' value='${retTotalOct}' name='retTotalOct[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalOct='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalOct == "" || element.falTotalOct == null || element.falTotalOct == 0)
                                var falTotalOct = "";
                                else
                                var falTotalOct = element.falTotalOct;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalOct' value='${falTotalOct}' name='falTotalOct[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalOct='${element.primaria_inscrito_id}'></td>`;
                            }
    
                            if(mes == "NOVIEMBRE"){
                                if(element.retTotalNov == "" || element.retTotalNov == null || element.retTotalNov == 0)
                                var retTotalNov = "";
                                else
                                var retTotalNov = element.retTotalNov;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalNov' value='${retTotalNov}' name='retTotalNov[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalNov='${element.primaria_inscrito_id}'></td>`;
    
                                if(element.falTotalNov == "" || element.falTotalNov == null || element.falTotalNov == 0)
                                var falTotalNov = "";
                                else
                                var falTotalNov = element.falTotalNov;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalNov' value='${falTotalNov}' name='falTotalNov[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalNov='${element.primaria_inscrito_id}'></td>`;
                            }
                            
                            if(mes == "ENERO"){
    
                                if(element.retTotalEne == "" || element.retTotalEne == null || element.retTotalEne == 0)
                                var retTotalEne = "";
                                else
                                var retTotalEne = element.retTotalEne;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalEne' value='${retTotalEne}' name='retTotalEne[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalEne='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalEne == "" || element.falTotalEne == null || element.falTotalEne == 0)
                                var falTotalEne = "";
                                else
                                var falTotalEne = element.falTotalEne;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalEne' value='${falTotalEne}' name='falTotalEne[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalEne='${element.primaria_inscrito_id}'></td>`;
                            }
    
                            if(mes == "FEBRERO"){
    
                                if(element.retTotalFeb == "" || element.retTotalFeb == null || element.retTotalFeb == 0)
                                var retTotalFeb = "";
                                else
                                var retTotalFeb = element.retTotalFeb;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalFeb' value='${retTotalFeb}' name='retTotalFeb[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalFeb='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalFeb == "" || element.falTotalFeb == null || element.falTotalFeb == 0)
                                var falTotalFeb = "";
                                else
                                var falTotalFeb = element.falTotalFeb;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalFeb' value='${falTotalFeb}' name='falTotalFeb[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalFeb='${element.primaria_inscrito_id}'></td>`;
                            }
    
                            if(mes == "MARZO"){
    
                                if(element.retTotalMar == "" || element.retTotalMar == null || element.retTotalMar == 0)
                                var retTotalMar = "";
                                else
                                var retTotalMar = element.retTotalMar;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalMar' value='${retTotalMar}' name='retTotalMar[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalMar='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalMar == "" || element.falTotalMar == null || element.falTotalMar == 0)
                                var falTotalMar = "";
                                else
                                var falTotalMar = element.falTotalMar;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalMar' value='${falTotalMar}' name='falTotalMar[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalMar='${element.primaria_inscrito_id}'></td>`;
                            }
    
                            if(mes == "ABRIL"){
    
                                if(element.retTotalAbr == "" || element.retTotalAbr == null || element.retTotalAbr == 0)
                                var retTotalAbr = "";
                                else
                                var retTotalAbr = element.retTotalAbr;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalAbr' value='${retTotalAbr}' name='retTotalAbr[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalAbr='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalAbr == "" || element.falTotalAbr == null || element.falTotalAbr == 0)
                                var falTotalAbr = "";
                                else
                                var falTotalAbr = element.falTotalAbr;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalAbr' value='${falTotalAbr}' name='falTotalAbr[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalAbr='${element.primaria_inscrito_id}'></td>`;
                            }
    
                            if(mes == "MAYO"){
    
                                if(element.retTotalMay == "" || element.retTotalMay == null || element.retTotalMay == 0)
                                var retTotalMay = "";
                                else
                                var retTotalMay = element.retTotalMay;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalMay' value='${retTotalMay}' name='retTotalMay[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalMay='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalMay == "" || element.falTotalMay == null || element.falTotalMay == 0)
                                var falTotalMay = "";
                                else
                                var falTotalMay = element.falTotalMay;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalMay' value='${falTotalMay}' name='falTotalMay[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalMay='${element.primaria_inscrito_id}'></td>`;
                            }
    
                            if(mes == "JUNIO"){
    
                                if(element.retTotalJun == "" || element.retTotalJun == null || element.retTotalJun == 0)
                                var retTotalJun = "";
                                else
                                var retTotalJun = element.retTotalJun;
    
                                myTable+=`<td><input tabindex="13" type='number' id='retTotalJun' value='${retTotalJun}' name='retTotalJun[]' step="1" lang="en"  min="0" class='noUpperCase' data-retTotalJun='${element.primaria_inscrito_id}'></td>`;
    
    
                                if(element.falTotalJun == "" || element.falTotalJun == null || element.falTotalJun == 0)
                                var falTotalJun = "";
                                else
                                var falTotalJun = element.falTotalJun;
                                myTable+=`<td><input tabindex="12" type='number' id='falTotalJun' value='${falTotalJun}' name='falTotalJun[]' step="1" lang="en"  min="0" class='noUpperCase' data-falTotalJun='${element.primaria_inscrito_id}'></td>`;
                            }
                        }

                        

                        if(numero_evidencias == 1){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;                           
                        }
                        if(numero_evidencias == 2){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;                
                        }
                        if(numero_evidencias == 3){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        if(numero_evidencias == 4){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="4" type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        if(numero_evidencias == 5){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="4" type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="5" type='number' id='evidencia5' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        if(numero_evidencias == 6){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="4" type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="5" type='number' id='evidencia5' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="6" type='number' id='evidencia6' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia6}' name='evidencia6[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        if(numero_evidencias == 7){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="4" type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="5" type='number' id='evidencia5' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="6" type='number' id='evidencia6' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia6}' name='evidencia6[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="7" type='number' id='evidencia7' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia7}' name='evidencia7[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        if(numero_evidencias == 8){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="4" type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="5" type='number' id='evidencia5' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="6" type='number' id='evidencia6' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia6}' name='evidencia6[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="7" type='number' id='evidencia7' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia7}' name='evidencia7[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="8" type='number' id='evidencia8' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia8}' name='evidencia8[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        if(numero_evidencias == 9){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="4" type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="5" type='number' id='evidencia5' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="6" type='number' id='evidencia6' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia6}' name='evidencia6[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="7" type='number' id='evidencia7' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia7}' name='evidencia7[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="8" type='number' id='evidencia8' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia8}' name='evidencia8[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="9" type='number' id='evidencia9' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia9}' name='evidencia9[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        if(numero_evidencias == 10){
                            myTable+=`<td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="2" type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="3" type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="4" type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="5" type='number' id='evidencia5' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="6" type='number' id='evidencia6' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia6}' name='evidencia6[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="7" type='number' id='evidencia7' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia7}' name='evidencia7[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="8" type='number' id='evidencia8' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia8}' name='evidencia8[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="9" type='number' id='evidencia9' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia9}' name='evidencia9[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                            myTable+=`<td><input tabindex="10" type='number' id='evidencia10' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia10}' name='evidencia10[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>`;
                        }
                        
                        myTable+=`<td><input tabindex="11" type='number' lang="en" onmouseover="this.value = parseFloat(this.value).toFixed(1)" readonly='true' id='promedioTotal${element.primaria_inscrito_id}' name='promedioTotal[]' step="0.01"  min="5" max="10" value='${element.promedio_mes}' onclick="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" onmouseout="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}"></td>`;
                        myTable+=`<td style="display:none"><input name='tipoDeAccion' type='text' value='ACTUALIZAR'></td>`;

                            

                        myTable+="</tr>";

                        function calcularPromedio(id){

                            var calificacion1 = 0;
                            var calificacion2 = 0;
                            var calificacion3 = 0;
                            var calificacion4 = 0;
                            var calificacion5 = 0;
                            var calificacion6 = 0;
                            var calificacion7 = 0;
                            var calificacion8 = 0;
                            var calificacion9 = 0;
                            var calificacion10 = 0;
    
                           
                            var evidencia = 0;
                            var valorCalificacion  = 0;
                            var promedio  = 0;
                            $('.evidencia_' + element.primaria_inscrito_id).each(function(){
                                if ($(this).val() != "") {
                                    evidencia++;
                                    valorCalificacion = parseFloat($(this).val());
                                    if(evidencia == 1){
                                        calificacion1 = valorCalificacion * (porcentaje_evidencia1/100);
                                    }
                                    if(evidencia == 2){
                                        calificacion2 = valorCalificacion * (porcentaje_evidencia2/100);
                                    }
                                    if(evidencia == 3){
                                        calificacion3 = valorCalificacion * (porcentaje_evidencia3/100);
                                    }
                                    if(evidencia == 4){
                                        calificacion4 = valorCalificacion * (porcentaje_evidencia4/100);
                                    }
                                    if(evidencia == 5){
                                        calificacion5 = valorCalificacion * (porcentaje_evidencia5/100);
                                    }
                                    if(evidencia == 6){
                                        calificacion6 = valorCalificacion * (porcentaje_evidencia6/100);
                                    }
                                    if(evidencia == 2){
                                        calificacion7 = valorCalificacion * (porcentaje_evidencia7/100);
                                    }
                                    if(evidencia == 8){
                                        calificacion8 = valorCalificacion * (porcentaje_evidencia8/100);
                                    }
                                    if(evidencia == 9){
                                        calificacion9 = valorCalificacion * (porcentaje_evidencia9/100);
                                    }
                                    if(evidencia == 10){
                                        calificacion10 = valorCalificacion * (porcentaje_evidencia10/100);
                                    }
                                }
                            });
    
                            if(numero_evidencias == 1){
                                promedio = calificacion1;
                            }
    
                            if(numero_evidencias == 2){
                                promedio = calificacion1 + calificacion2;
                            }
    
                            if(numero_evidencias == 3){
                                promedio = calificacion1 + calificacion2 + calificacion3;
                            }
    
                            if(numero_evidencias == 4){
                                promedio = calificacion1 + calificacion2 + calificacion3 + calificacion4;
                            }
    
                            if(numero_evidencias == 5){
                                promedio = calificacion1 + calificacion2 + calificacion3 + calificacion4 +
                                calificacion5;
                            }
    
                            if(numero_evidencias == 6){
                                promedio = calificacion1 + calificacion2 + calificacion3 + calificacion4 +
                                    calificacion5 + calificacion6;
                            }
    
                            if(numero_evidencias == 7){
                                promedio = calificacion1 + calificacion2 + calificacion3 + calificacion4 +
                                    calificacion5 + calificacion6 + calificacion7;
                            }
    
                            if(numero_evidencias == 8){
                                promedio = calificacion1 + calificacion2 + calificacion3 + calificacion4 +
                                    calificacion5 + calificacion6 + calificacion7 + calificacion8;
                            }
                            if(numero_evidencias == 9){
                                promedio = calificacion1 + calificacion2 + calificacion3 + calificacion4 +
                                    calificacion5 + calificacion6 + calificacion7 + calificacion8 +
                                    calificacion9;
                            }
    
                            if(numero_evidencias == 10){
                                promedio = calificacion1 + calificacion2 + calificacion3 + calificacion4 +
                                    calificacion5 + calificacion6 + calificacion7 + calificacion8 +
                                    calificacion9 + calificacion10;
                            }
    
                            //promedio = promedio / parciales;
    
                            //muestra un solo decimal
                            promedio = promedio.toFixed(1);
    
                            //promedio = promedio + 0.5
    
                            //promedio = Math.trunc(promedio);
    
    
                            $('#promedioTotal' + element.primaria_inscrito_id).val(promedio);
                        }

                        $(function() {
                            $(".calif").on('change keyup', function(e) {
                                var value = e.target.value
                                console.log("entra")
                                console.log(value)

                                //$(this).val(value || 0)


                                if ($(this).data('inscritoid')) {

                                    var inscritoId = $(this).data('inscritoid')

                                    calcularPromedio(inscritoId)
                                }
                            });


                        });
                        
                    });
                    


                    myTable+="</table>";
                    
                    //pintamos la tabla 
                    document.getElementById('tablePrint').innerHTML = myTable;
                    
                }else{
                    document.getElementById('tablePrint').innerHTML = "";
                    swal("Upss..", "No se han encontrado calificaciones para el mes seleccionado", "info");
                    $("#btn-ocultar-si-es-menor-a-cien").hide();
                    $("#alerta-menos-de-ciente").hide();
                    $("#alerta-min-max-calif").hide();
                    $(".btn-guardar").hide();
                }

                

                
            });
        });
       
        

    });
</script>



