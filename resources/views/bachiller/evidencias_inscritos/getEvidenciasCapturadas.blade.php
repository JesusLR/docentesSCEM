<script type="text/javascript">
    $(document).ready(function() {

        $("#bachiller_evidencia_id").change(event => {
            var bachiller_grupo_id = $("#bachiller_grupo_id").val();
    
            //$("#tableBody").html("");
            $("#puntosMaximos").text("");
    
            document.getElementById('tablePrint').innerHTML = "";
    
    
            $.get(base_url + `/bachiller_evidencias_inscritos/capturas_realizadas/${bachiller_grupo_id}/${event.target.value}`, function(res, sta) {
    
                //Variable para cuando hay evidencias capturadas
                var bachiller_evidencias = res.bachiller_evidencias;
                //Variable a usar cuando no hay evidencias capturadas
                var bachiller_inscritos = res.bachiller_inscritos;

                

    
                //Creamos tabla cuando haya evidencias capturadas 
                if (bachiller_evidencias.length > 0) {

                    var se_registra_faltas = bachiller_evidencias[0].eviFaltas;
                    var ubicacion = bachiller_evidencias[0].ubiClave;
                    var estado_act = bachiller_evidencias[0].estado_act;
    
                    $("#Tabla").show();

                    if(estado_act == "C"){
                        $(".submit-button").hide();
                    }else{
                        $(".submit-button").show();
                    }
                   
                    $("#puntos").show();
    
    
                    let myTable = "<table><tr>"
                    myTable += "<th style='display:none;'></th>";
                    myTable += "<th style='display:none;'></th>";
                    myTable += "<th style='display:none;'></th>";
                    myTable += "<th>NÚMERO <p>LISTA</p></th>";
                    myTable += "<th>CLAVE <p>PAGO</p></th>";
                    myTable += "<th>NOMBRE <p>COMPLETO</p></th>";
                    myTable += "<th>PUNTOS <p>EVIDENCIA</p></th>";

                    if(ubicacion == "CME"){
                        if(se_registra_faltas == "S"){
                            myTable += "<th>FALTAS <p>EVIDENCIA</p></th>";
                            myTable += "<th>CLAVE <p>CUALITATIVA 1</p></th>";
                            myTable += "<th>CLAVE <p>CUALITATIVA 2</p></th>";
                            myTable += "<th>CLAVE <p>CUALITATIVA 3</p></th>";
                        }
                    }

                    if(ubicacion == "CVA"){
                        myTable += "<th>FALTAS <p>EVIDENCIA</p></th>";
                        myTable += "<th>CLAVE <p>CUALITATIVA 1</p></th>";
                        myTable += "<th>CLAVE <p>CUALITATIVA 2</p></th>";
                        myTable += "<th>CLAVE <p>CUALITATIVA 3</p></th>";
                    }
                    
                    
                    myTable += "<th></th>";
                    myTable += "</tr>";
    
    
                    bachiller_evidencias.forEach(function(element, i) {
    
                        $("#puntosMaximos").text(element.eviPuntos);
    
    
                        var ievClaveCualitativa1 = element.ievClaveCualitativa1;
                        var ievClaveCualitativa2 = element.ievClaveCualitativa2;
                        var ievClaveCualitativa3 = element.ievClaveCualitativa3;
    
                        myTable += "<tr>";
                        myTable += `<td style='display:none;'><input name='bachiller_inscrito_evidencia_id[]' id='bachiller_inscrito_evidencia_id' type='hidden' value='${element.id}'></td>`;
                        myTable += `<td style='display:none;'><input style='display:none;' id='bachiller_inscrito_id' name='bachiller_inscrito_id[]' type='hidden' value='${element.bachiller_inscrito_id}'></td>`;
                        myTable += `<td style='display:none;'><input name='evidencianumero[]' type='hidden' value='${element.eviNumero}'></td>`;
                        myTable += `<td>${i+1}</td>`;
                        myTable += `<td>${element.aluClave}</td>`;
                        myTable += `<td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>`;

                        if(estado_act == "C"){
                            myTable += `<td><input readonly type='number' onkeyup="if(this.value > ${element.eviPuntos}){this.value='';}else if(this.value < 0){this.value='';}" id='ievPuntos' class='noUpperCase' name='ievPuntos[]' value='${element.ievPuntos}' step='0.1'></td>`;

                        }else{
                            myTable += `<td><input type='number' onkeyup="if(this.value > ${element.eviPuntos}){this.value='';}else if(this.value < 0){this.value='';}" id='ievPuntos' class='noUpperCase' name='ievPuntos[]' value='${element.ievPuntos}' step='0.1'></td>`;

                        }
                        
                        if(ubicacion == "CME"){
                            if(se_registra_faltas == "S"){
                                myTable += `<td><input type='number' id='ievFaltas' name='ievFaltas[]' value='${element.ievFaltas}' step='1'></td>`;
        
                                myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa1_${element.bachiller_inscrito_id}' class='browser-default js-example-basic-single' name='ievClaveCualitativa1[]'>`;
                                myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                    var selected1 = "";
                                    if (elemen.id == ievClaveCualitativa1) {
                                        selected1 = "selected";
                                    }
                                    myTable += `<option value='${elemen.id}' ${selected1}>${elemen.cuaClave} - ${elemen.cuaDescripcion}</option>`;
    
                                });
                                myTable += `</select></td>`;
            
                                myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa2_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa2[]'>`;
                                myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                    var selected2 = "";
                                    if (elemen.id == ievClaveCualitativa2) {
                                        selected2 = "selected";
                                    }
                                    myTable += `<option value='${elemen.id}' ${selected2}>${elemen.cuaClave} - ${elemen.cuaDescripcion}</option>`;
    
                                });
                                myTable += `</select></td>`;
            
                                myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa3_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa3[]'>`;
                                myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                    var selected3 = "";
                                    if (elemen.id == ievClaveCualitativa3) {
                                        selected3 = "selected";
                                    }
                                    myTable += `<option value='${elemen.id}' ${selected3}>${elemen.cuaClave} - ${elemen.cuaDescripcion}</option>`;
                                });
                                myTable += `</select></td>`;
                            }
                        }

                        if(ubicacion == "CVA"){
                            myTable += `<td><input type='number' id='ievFaltas' name='ievFaltas[]' value='${element.ievFaltas}' step='1'></td>`;
        
                            myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa1_${element.bachiller_inscrito_id}' class='browser-default js-example-basic-single' name='ievClaveCualitativa1[]'>`;
                            myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                            res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                var selected1 = "";
                                if (elemen.id == ievClaveCualitativa1) {
                                    selected1 = "selected";
                                }
                                myTable += `<option value='${elemen.id}' ${selected1}>${elemen.cuaClave} - ${elemen.cuaDescripcion}</option>`;

                            });
                            myTable += `</select></td>`;
        
                            myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa2_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa2[]'>`;
                            myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                            res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                var selected2 = "";
                                if (elemen.id == ievClaveCualitativa2) {
                                    selected2 = "selected";
                                }
                                myTable += `<option value='${elemen.id}' ${selected2}>${elemen.cuaClave} - ${elemen.cuaDescripcion}</option>`;

                            });
                            myTable += `</select></td>`;
        
                            myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa3_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa3[]'>`;
                            myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                            res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                var selected3 = "";
                                if (elemen.id == ievClaveCualitativa3) {
                                    selected3 = "selected";
                                }
                                myTable += `<option value='${elemen.id}' ${selected3}>${elemen.cuaClave} - ${elemen.cuaDescripcion}</option>`;
                            });
                            myTable += `</select></td>`;
                        }
                        
                        
                        

                        myTable += `<td style='display:none;'><input name='aluClave[]' id='aluClave' type='hidden' value='${element.aluClave}'></td>`;
                        myTable += `<td style='display:none;'><input name='periodo_id' id='periodo_id' type='hidden' value='${element.periodo_id}'></td>`;
                        myTable += `<td><input type='hidden' id='movimiento' name='movimiento' value='ACTUALIZAR'></td>`;
    
                        myTable += "</tr>";
    
    
    
                    });
    
                    myTable += "</table>";
    
                    document.getElementById('tablePrint').innerHTML = myTable;
    
                }else{
                    swal("Escuela Modelo", "No se han encontrado alumnos inscritos a este grupo (materia)", "warning");
                }
                /*else {
    
                    if (bachiller_inscritos.length > 0) {
                        //Cuando no hay evidencias creadas
    
                        var evidencia = res.evidencia;
                        $("#puntosMaximos").text(evidencia.eviPuntos);
    
                        $("#Tabla").show();
                        $("#submit-button").show();
                        $("#puntos").show();
                        //Aqui
                        let myTable = "<table><tr>"
                        myTable += "<th></th>";
                        myTable += "<th></th>";
                        myTable += "<th></th>";
                        myTable += "<th>NÚMERO <p>LISTA</p></th>";
                        myTable += "<th>CLAVE <p>PAGO</p></th>";
                        myTable += "<th>NOMBRE <p>COMPLETO</p></th>";
                        myTable += "<th>PUNTOS <p>EVIDENCIA</p></th>";
                        if(ubicacion == "CME"){
                            if(se_registra_faltas == "S"){
                                myTable += "<th>FALTAS <p>EVIDENCIA</p></th>";
                                myTable += "<th>CLAVE <p>CUALITATIVA 1</p></th>";
                                myTable += "<th>CLAVE <p>CUALITATIVA 2</p></th>";
                                myTable += "<th>CLAVE <p>CUALITATIVA 3</p></th>";
                            }
                        }
    
                        if(ubicacion == "CVA"){
                            myTable += "<th>FALTAS <p>EVIDENCIA</p></th>";
                            myTable += "<th>CLAVE <p>CUALITATIVA 1</p></th>";
                            myTable += "<th>CLAVE <p>CUALITATIVA 2</p></th>";
                            myTable += "<th>CLAVE <p>CUALITATIVA 3</p></th>";
                        }
                        myTable += "<th></th>";
                        myTable += "</tr>";
    
    
                        bachiller_inscritos.forEach(function(element, i) {
    
                            $("#puntosMaximos").text(element.eviPuntos);
    
    
    
                            myTable += "<tr>";
                            myTable += `<td><input name='bachiller_inscrito_evidencia_id[]' id='bachiller_inscrito_evidencia_id' type='hidden' value='${element.id}'></td>`;
                            myTable += `<td><input style='display:none;' id='bachiller_inscrito_id' name='bachiller_inscrito_id[]' type='hidden' value='${element.id}'></td>`;
                            myTable += `<td><input name='evidencianumero[]' type='hidden' value=''></td>`;
                            myTable += `<td>${i+1}</td>`;
                            myTable += `<td>${element.aluClave}</td>`;
                            myTable += `<td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>`;
                            myTable += `<td><input type='number' onkeyup="if(this.value > ${element.eviPuntos}){this.value='';}else if(this.value < 0){this.value='';}" id='ievPuntos' class='noUpperCase' name='ievPuntos[]' value='' step='0.1'></td>`;
                            
                            if(ubicacion == "CME"){
                                if(se_registra_faltas == "S"){
                                    myTable += `<td><input type='number' id='ievFaltas' name='ievFaltas[]' value='' step='1'></td>`;
    
                                    myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa1_${element.bachiller_inscrito_id}' class='browser-default js-example-basic-single' name='ievClaveCualitativa1[]'>`;
                                    myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                    res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                        myTable += `<option value='${elemen.id}'>Clave: ${elemen.cuaClave}</option>`;
                                    });
                                    myTable += `</select></td>`;
            
                                    myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa2_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa2[]'>`;
                                    myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                    res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                        myTable += `<option value='${elemen.id}'>Clave: ${elemen.cuaClave}</option>`;
                                    });
                                    myTable += `</select></td>`;
            
                                    myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa3_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa3[]'>`;
                                    myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                    res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                        myTable += `<option value='${elemen.id}'>Clave: ${elemen.cuaClave}</option>`;
                                    });
                                    myTable += `</select></td>`;
                                }
                            }

                            if(ubicacion == "CVA"){
                                myTable += `<td><input type='number' id='ievFaltas' name='ievFaltas[]' value='' step='1'></td>`;
    
                                    myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa1_${element.bachiller_inscrito_id}' class='browser-default js-example-basic-single' name='ievClaveCualitativa1[]'>`;
                                    myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                    res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                        myTable += `<option value='${elemen.id}'>Clave: ${elemen.cuaClave}</option>`;
                                    });
                                    myTable += `</select></td>`;
            
                                    myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa2_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa2[]'>`;
                                    myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                    res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                        myTable += `<option value='${elemen.id}'>Clave: ${elemen.cuaClave}</option>`;
                                    });
                                    myTable += `</select></td>`;
            
                                    myTable += `<td><select style='margin-top:-15px;' id='ievClaveCualitativa3_${element.bachiller_inscrito_id}' class='browser-default validate select2' name='ievClaveCualitativa3[]'>`;
                                    myTable += `<option value=''>SELECCIONE UNA OPCIÓN</option>`;
                                    res.bachiller_conceptos_cualitativos.forEach(function(elemen, i) {
                                        myTable += `<option value='${elemen.id}'>Clave: ${elemen.cuaClave}</option>`;
                                    });
                                    myTable += `</select></td>`;
                            }
                            
    
                            myTable += `<td><input type='hidden' id='movimiento' name='movimiento' value='CREAR'></td>`;
    
                            myTable += "</tr>";
    
    
    
                        });
    
                        myTable += "</table>";
    
                        document.getElementById('tablePrint').innerHTML = myTable;
                    } else {
    
                        swal("Escuela Modelo", "El grupo no cuenta con alumnos inscritos", "info");
                    }
    
    
                }*/
    
            });
        });
    
    });
</script>