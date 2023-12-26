<script type="text/javascript">
    $(document).ready(function() {

        $("#que_se_va_a_calificar").change(event => {
            var que_se_va_a_calificar = $("#que_se_va_a_calificar").val();

            var bachiller_cch_grupo_id = $("#bachiller_cch_grupo_id").val();
          
    
            $.get(base_url + `/bachiller_calificacion_seq/getCalificacionesAlumnosCCH/${bachiller_cch_grupo_id}/${event.target.value}`, function(res, sta) {
    
                var bachiller_cch_inscritos = res.bachiller_cch_inscritos;
                var que_vamos_a_calificar = res.que_vamos_a_calificar;
                var bachiller_cch_inscritos_recuperativos = res.bachiller_cch_inscritos_recuperativos;

                var tipoAcreditacion = bachiller_cch_inscritos[0].matTipoAcreditacion;


                var total = 1;

                //Si el tipo de acreditacion es númerico entra aquí
                if(tipoAcreditacion == "N"){

                    if(que_vamos_a_calificar == "parcial1"){

                        let myTable = "<table><tr>"
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>#</th>";
                            myTable += "<th>Clave Pago</th>";
                            myTable += "<th>Nombre Alumno</th>";
                            myTable += "<th>Ordinario Calificacion Parcial 1</th>";
                            myTable += "<th>Faltas Ordinario Parcial 1</th>";   
                            myTable += "<th style='display:none;'></th>";
                        
                            myTable += "</tr>";
    
                            bachiller_cch_inscritos.forEach(function(element, i) {
        
                                myTable += "<tr>";
                                    myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                    myTable += `<td>${total++}</td>`;
                                    myTable += `<td>${element.aluClave}</td>`;
                                    
                                    if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                    }else{
                                            var perApellido1 = element.perApellido1;
                                    }

                                    if(element.perApellido2 == null){
                                        var perApellido2 = "";
                                    }else{
                                        var perApellido2 = element.perApellido2;
                                    }

                                    if(element.perNombre == null){
                                        var perNombre = "";
                                    }else{
                                        var perNombre = element.perNombre;
                                    }
                                    myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
            
                                    if (element.insCalificacionOrdinarioParcial1 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" value="${element.insCalificacionOrdinarioParcial1}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" value="${element.insCalificacionOrdinarioParcial1}"></td>`;
            
                                    } 
                                    
                                    myTable += `<td><input type="number" id="insFaltasOrdinarioParcial1" name="insFaltasOrdinarioParcial1[]" value="${element.insFaltasOrdinarioParcial1}"></td>`;                        
                                    
                                    myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial1"></td>`;
    
                                    myTable += "</tr>";
                        
            
                                
            
            
            
                        });
            
                        myTable += "</table>";
            
                        document.getElementById('tablePrint').innerHTML = myTable;
                    }
                       
        
                    if(que_vamos_a_calificar == "parcial2"){
    
                        let myTable = "<table><tr>"
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>#</th>";
                            myTable += "<th>Clave Pago</th>";
                            myTable += "<th>Nombre Alumno</th>";
                            myTable += "<th>Ordinario Calificacion Parcial 2</th>";
                            myTable += "<th>Faltas Ordinario Parcial 2</th>";        
                            myTable += "<th style='display:none;'></th>";
                   
                            myTable += "</tr>";
    
                            bachiller_cch_inscritos.forEach(function(element, i) {
        
                                myTable += "<tr>";
                                    myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                    myTable += `<td>${total++}</td>`;
                                    myTable += `<td>${element.aluClave}</td>`;
                                    
                                    if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
            
                                    if (element.insCalificacionOrdinarioParcial2 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial2" name="insCalificacionOrdinarioParcial2[]" value="${element.insCalificacionOrdinarioParcial2}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial2" name="insCalificacionOrdinarioParcial2[]" value="${element.insCalificacionOrdinarioParcial2}"></td>`;
            
                                    } 
                                    
                                    myTable += `<td><input type="number" id="insFaltasOrdinarioParcial2" name="insFaltasOrdinarioParcial2[]" value="${element.insFaltasOrdinarioParcial2}"></td>`;                        
                                    myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial2"></td>`;
    
                
                                    myTable += "</tr>";      
            
            
            
                        });
            
                        myTable += "</table>";
            
                        document.getElementById('tablePrint').innerHTML = myTable;
                    }
        
                    if(que_vamos_a_calificar == "parcial3"){
    
                        let myTable = "<table><tr>"
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>#</th>";
                            myTable += "<th>Clave Pago</th>";
                            myTable += "<th>Nombre Alumno</th>";
                            myTable += "<th>Ordinario Calificacion Parcial 3</th>";
                            myTable += "<th>Faltas Ordinario Parcial 3</th>";       
                            myTable += "<th style='display:none;'></th>";
                    
                            myTable += "</tr>";
    
                            bachiller_cch_inscritos.forEach(function(element, i) {
        
                                myTable += "<tr>";
                                    myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                    myTable += `<td>${total++}</td>`;
                                    myTable += `<td>${element.aluClave}</td>`;
                                    
                                    if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
            
                                    if (element.insCalificacionOrdinarioParcial3 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial3" name="insCalificacionOrdinarioParcial3[]" value="${element.insCalificacionOrdinarioParcial3}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial3" name="insCalificacionOrdinarioParcial3[]" value="${element.insCalificacionOrdinarioParcial3}"></td>`;
            
                                    } 
                                    
                                    myTable += `<td><input type="number" id="insFaltasOrdinarioParcial3" name="insFaltasOrdinarioParcial3[]" value="${element.insFaltasOrdinarioParcial3}"></td>`;                        
                                    
                
                                    myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial3"></td>`;
    
                                    myTable += "</tr>";      
            
            
            
                        });
            
                        myTable += "</table>";
            
                        document.getElementById('tablePrint').innerHTML = myTable;
                    }
        
                    if(que_vamos_a_calificar == "parcial4"){
    
                        let myTable = "<table><tr>"
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>#</th>";
                            myTable += "<th>Clave Pago</th>";
                            myTable += "<th>Nombre Alumno</th>";
                            myTable += "<th>Ordinario Calificacion Parcial 4</th>";
                            myTable += "<th>Faltas Ordinario Parcial 4</th>";   
                            myTable += "<th style='display:none;'></th>";
                        
                            myTable += "</tr>";
    
                            bachiller_cch_inscritos.forEach(function(element, i) {
        
                                myTable += "<tr>";
                                    myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                    myTable += `<td>${total++}</td>`;
                                    myTable += `<td>${element.aluClave}</td>`;
                                    
                                    if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
            
                                    if (element.insCalificacionOrdinarioParcial4 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial4" name="insCalificacionOrdinarioParcial4[]" value="${element.insCalificacionOrdinarioParcial4}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionOrdinarioParcial4" name="insCalificacionOrdinarioParcial4[]" value="${element.insCalificacionOrdinarioParcial4}"></td>`;
            
                                    } 
                                    
                                    myTable += `<td><input type="number" id="insFaltasOrdinarioParcial4" name="insFaltasOrdinarioParcial4[]" value="${element.insFaltasOrdinarioParcial4}"></td>`;                        
                                    
                                    myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial4"></td>`;
    
                
                                    myTable += "</tr>";      
            
            
            
                        });
            
                        myTable += "</table>";
            
                        document.getElementById('tablePrint').innerHTML = myTable;
                    }
                    
    
                    if(que_vamos_a_calificar == "recuperacion"){
                        
                        document.getElementById('tablePrint').innerHTML = "";
                        let myTable = "<table><tr>"
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>#</th>";
                            myTable += "<th>Clave Pago</th>";
                            myTable += "<th>Nombre Alumno</th>";
                            myTable += "<th>Calificación Recuperativo 1</th>";    
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>Calificación Recuperativo 2</th>";    
                            myTable += "<th style='display:none;'></th>";                
                            myTable += "<th>Calificación Recuperativo 3</th>"; 
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>Calificación Recuperativo 4</th>"; 
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th style='display:none;'></th>";
                            myTable += "</tr>";
    
                            bachiller_cch_inscritos.forEach(function(element, i) {
    
                                if(element.insCantidadReprobadasOrdinarioParciales == "1" || element.insCantidadReprobadasOrdinarioParciales == "2"){
    
                                    myTable += "<tr>";
                                        myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                        myTable += `<td>${total++}</td>`;
                                        myTable += `<td>${element.aluClave}</td>`;
                                        
                                        if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
                
                                        if(element.insAproboParcial1 == "SI"){
                                            myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="${element.insCalificacionRecuperativoParcial1}"></td>`;
                                            myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="SiDisabled"></td>`;
    
                                        }else{
                                            if (element.insCalificacionRecuperativoParcial1 >= 6){
                                                myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="${element.insCalificacionRecuperativoParcial1}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>`;
    
                                            }else{
                                                myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="${element.insCalificacionRecuperativoParcial1}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>`;
    
                                            } 
                                        }
                                        
    
                                        if(element.insAproboParcial2 == "SI"){
                                            myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="${element.insCalificacionRecuperativoParcial2}"></td>`;
                                            myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="SiDisabled"></td>`;
    
                                        }else{
                                            if (element.insCalificacionRecuperativoParcial2 >= 6){
                                                myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="${element.insCalificacionRecuperativoParcial2}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>`;
    
                                            }else{
                                                myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="${element.insCalificacionRecuperativoParcial2}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>`;
    
                                            } 
                                        }
    
                                        if(element.insAproboParcial3 == "SI"){
                                            myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="${element.insCalificacionRecuperativoParcial3}"></td>`;
                                            myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="SiDisabled"></td>`;
    
                                        }else{
                                            if (element.insCalificacionRecuperativoParcial3 >= 6){
                                                myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="${element.insCalificacionRecuperativoParcial3}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>`;
    
                                            }else{
                                                myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="${element.insCalificacionRecuperativoParcial3}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>`;
    
                                            } 
                                        }
    
                                        if(element.insAproboParcial4 == "SI"){
                                            myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="${element.insCalificacionRecuperativoParcial4}"></td>`;
                                            myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="SiDisabled"></td>`;
    
                                        }else{
                                            if (element.insCalificacionRecuperativoParcial4 >= 6){
                                                myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="${element.insCalificacionRecuperativoParcial4}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="Noisabled"></td>`;
    
                                            }else{
                                                myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="${element.insCalificacionRecuperativoParcial4}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="Noisabled"></td>`;
    
                                            } 
                                        }                               
                                        
                                        
                                        myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="recuperacion"></td>`;
    
                    
                                        myTable += "</tr>";      
                
    
                                }  
                                
            
            
                        });
            
                        myTable += "</table>";
            
                        document.getElementById('tablePrint').innerHTML = myTable;
                    }
    
                    if(que_vamos_a_calificar == "extraordinario"){
    
                        let myTable = "<table><tr>"
                            myTable += "<th style='display:none;'></th>";
                            myTable += "<th>#</th>";
                            myTable += "<th>Clave Pago</th>";
                            myTable += "<th>Nombre Alumno</th>";
                            myTable += "<th>Calificación Recuperativo 1</th>";    
                            myTable += "<th>Calificación Recuperativo 2</th>";                    
                            myTable += "<th>Calificación Recuperativo 3</th>"; 
                            myTable += "<th>Calificación Recuperativo 4</th>"; 
                            myTable += "<th style='display:none;'></th>"; 
                            myTable += "<th style='display:none;'></th>";
                        
                            myTable += "</tr>";
    
                            bachiller_cch_inscritos.forEach(function(element, i) {
        
                                myTable += "<tr>";
                                    myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                    myTable += `<td>${total++}</td>`;
                                    myTable += `<td>${element.aluClave}</td>`;
                                    
                                    if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
            
                                    if (element.insCalificacionExtraOrdinarioParcial1 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="${element.insCalificacionExtraOrdinarioParcial1}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="${element.insCalificacionExtraOrdinarioParcial1}"></td>`;
            
                                    } 
                                    
                                    if (element.insCalificacionExtraOrdinarioParcial2 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="${element.insCalificacionExtraOrdinarioParcial2}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="${element.insCalificacionExtraOrdinarioParcial2}"></td>`;
            
                                    } 
    
                                    if (element.insCalificacionExtraOrdinarioParcial3 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="${element.insCalificacionExtraOrdinarioParcial3}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="${element.insCalificacionExtraOrdinarioParcial3}"></td>`;
            
                                    } 
    
    
                                    if (element.insCalificacionExtraOrdinarioParcial4 < 6){
                                        myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="${element.insCalificacionExtraOrdinarioParcial4}"></td>`;
            
                                    }else{
                                        myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="${element.insCalificacionExtraOrdinarioParcial4}"></td>`;
            
                                    } 
    
                                    
                                    myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="extraordinario"></td>`;
    
                
                                    myTable += "</tr>";      
            
            
            
                        });
            
                        myTable += "</table>";
            
                        document.getElementById('tablePrint').innerHTML = myTable;
                    }
                    
                }else{

                    //Si el tipo de acreditacion es Alfanúmerico entra aquí
                    if(tipoAcreditacion == "A"){

                        if(que_vamos_a_calificar == "parcial1"){
    
                            let myTable = "<table><tr>"
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>#</th>";
                                myTable += "<th>Clave Pago</th>";
                                myTable += "<th>Nombre Alumno</th>";
                                myTable += "<th>Ordinario Calificacion Parcial 1</th>";
                                myTable += "<th>Faltas Ordinario Parcial 1</th>";   
                                myTable += "<th style='display:none;'></th>";
                            
                                myTable += "</tr>";
        
                                bachiller_cch_inscritos.forEach(function(element, i) {
            
                                    myTable += "<tr>";
                                        myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                        myTable += `<td>${total++}</td>`;
                                        myTable += `<td>${element.aluClave}</td>`;

                                        if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
                
                                        if (element.insCalificacionOrdinarioParcial1 == -2){

                                            
                                            myTable += `<td>
                                                <select style="border-color: red;" class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1">A</option>
                                                    <option value="-2" selected>NA</option>
                                                </select>
                                            </td>`;
                
                                        }else{

                                           
                                            myTable += `<td>
                                                <select class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1" selected>A</option>
                                                    <option value="-2">NA</option>
                                                </select>
                                                </td>`;
                
                                        } 
                                        
                                        myTable += `<td><input type="number" id="insFaltasOrdinarioParcial1" name="insFaltasOrdinarioParcial1[]" value="${element.insFaltasOrdinarioParcial1}"></td>`;                        
                                        
                                        myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial1"></td>`;
        
                                        myTable += "</tr>";
                            
                
                                    
                
                
                
                            });
                
                            myTable += "</table>";
                
                            document.getElementById('tablePrint').innerHTML = myTable;
                        }
                           
            
                        if(que_vamos_a_calificar == "parcial2"){
        
                            let myTable = "<table><tr>"
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>#</th>";
                                myTable += "<th>Clave Pago</th>";
                                myTable += "<th>Nombre Alumno</th>";
                                myTable += "<th>Ordinario Calificacion Parcial 2</th>";
                                myTable += "<th>Faltas Ordinario Parcial 2</th>";        
                                myTable += "<th style='display:none;'></th>";
                       
                                myTable += "</tr>";
        
                                bachiller_cch_inscritos.forEach(function(element, i) {
            
                                    myTable += "<tr>";
                                        myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                        myTable += `<td>${total++}</td>`;
                                        myTable += `<td>${element.aluClave}</td>`;
                                        
                                        
                                        if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
                
                                        if (element.insCalificacionOrdinarioParcial1 == -2){

                                            
                                            myTable += `<td>
                                                <select style="border-color: red;" class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1">A</option>
                                                    <option value="-2" selected>NA</option>
                                                </select>
                                            </td>`;
                
                                        }else{

                                           
                                            myTable += `<td>
                                                <select class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1" selected>A</option>
                                                    <option value="-2">NA</option>
                                                </select>
                                                </td>`;
                
                                        } 
                                        
                                        myTable += `<td><input type="number" id="insFaltasOrdinarioParcial2" name="insFaltasOrdinarioParcial2[]" value="${element.insFaltasOrdinarioParcial2}"></td>`;                        
                                        myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial2"></td>`;
        
                    
                                        myTable += "</tr>";      
                
                
                
                            });
                
                            myTable += "</table>";
                
                            document.getElementById('tablePrint').innerHTML = myTable;
                        }
            
                        if(que_vamos_a_calificar == "parcial3"){
        
                            let myTable = "<table><tr>"
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>#</th>";
                                myTable += "<th>Clave Pago</th>";
                                myTable += "<th>Nombre Alumno</th>";
                                myTable += "<th>Ordinario Calificacion Parcial 3</th>";
                                myTable += "<th>Faltas Ordinario Parcial 3</th>";       
                                myTable += "<th style='display:none;'></th>";
                        
                                myTable += "</tr>";
        
                                bachiller_cch_inscritos.forEach(function(element, i) {
            
                                    myTable += "<tr>";
                                        myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                        myTable += `<td>${total++}</td>`;
                                        myTable += `<td>${element.aluClave}</td>`;
                                       
                                        if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
                
                                        if (element.insCalificacionOrdinarioParcial1 == -2){

                                            
                                            myTable += `<td>
                                                <select style="border-color: red;" class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1">A</option>
                                                    <option value="-2" selected>NA</option>
                                                </select>
                                            </td>`;
                
                                        }else{

                                           
                                            myTable += `<td>
                                                <select class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1" selected>A</option>
                                                    <option value="-2">NA</option>
                                                </select>
                                                </td>`;
                
                                        } 
                                        
                                        myTable += `<td><input type="number" id="insFaltasOrdinarioParcial3" name="insFaltasOrdinarioParcial3[]" value="${element.insFaltasOrdinarioParcial3}"></td>`;                        
                                        
                    
                                        myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial3"></td>`;
        
                                        myTable += "</tr>";      
                
                
                
                            });
                
                            myTable += "</table>";
                
                            document.getElementById('tablePrint').innerHTML = myTable;
                        }
            
                        if(que_vamos_a_calificar == "parcial4"){
        
                            let myTable = "<table><tr>"
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>#</th>";
                                myTable += "<th>Clave Pago</th>";
                                myTable += "<th>Nombre Alumno</th>";
                                myTable += "<th>Ordinario Calificacion Parcial 4</th>";
                                myTable += "<th>Faltas Ordinario Parcial 4</th>";   
                                myTable += "<th style='display:none;'></th>";
                            
                                myTable += "</tr>";
        
                                bachiller_cch_inscritos.forEach(function(element, i) {
            
                                    myTable += "<tr>";
                                        myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                        myTable += `<td>${total++}</td>`;
                                        myTable += `<td>${element.aluClave}</td>`;
                                        
                                        if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;

                                        if (element.insCalificacionOrdinarioParcial1 == -2){

                                            
                                            myTable += `<td>
                                                <select style="border-color: red;" class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1">A</option>
                                                    <option value="-2" selected>NA</option>
                                                </select>
                                            </td>`;
                
                                        }else{

                                           
                                            myTable += `<td>
                                                <select class="browser-default validate select2" id="insCalificacionOrdinarioParcial1" name="insCalificacionOrdinarioParcial1[]" >
                                                    <option value="-1" selected>A</option>
                                                    <option value="-2">NA</option>
                                                </select>
                                                </td>`;
                
                                        } 
                                        
                                        myTable += `<td><input type="number" id="insFaltasOrdinarioParcial4" name="insFaltasOrdinarioParcial4[]" value="${element.insFaltasOrdinarioParcial4}"></td>`;                        
                                        
                                        myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="parcial4"></td>`;
        
                    
                                        myTable += "</tr>";      
                
                
                
                            });
                
                            myTable += "</table>";
                
                            document.getElementById('tablePrint').innerHTML = myTable;
                        }
                        
        
                        if(que_vamos_a_calificar == "recuperacion"){
                            
                            document.getElementById('tablePrint').innerHTML = "";
                            let myTable = "<table><tr>"
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>#</th>";
                                myTable += "<th>Clave Pago</th>";
                                myTable += "<th>Nombre Alumno</th>";
                                myTable += "<th>Calificación Recuperativo 1</th>";    
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>Calificación Recuperativo 2</th>";    
                                myTable += "<th style='display:none;'></th>";                
                                myTable += "<th>Calificación Recuperativo 3</th>"; 
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>Calificación Recuperativo 4</th>"; 
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th style='display:none;'></th>";
                                myTable += "</tr>";
        
                                bachiller_cch_inscritos.forEach(function(element, i) {
        
                                    if(element.insCantidadReprobadasOrdinarioParciales == "1" || element.insCantidadReprobadasOrdinarioParciales == "2"){
        
                                        myTable += "<tr>";
                                            myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                            myTable += `<td>${total++}</td>`;
                                            myTable += `<td>${element.aluClave}</td>`;
                                           
                                            
                                            if(element.perApellido1 == null){
                                                var perApellido1 = "";
                                            }else{
                                                var perApellido1 = element.perApellido1;
                                            }
    
                                            if(element.perApellido2 == null){
                                                var perApellido2 = "";
                                            }else{
                                                var perApellido2 = element.perApellido2;
                                            }
    
                                            if(element.perNombre == null){
                                                var perNombre = "";
                                            }else{
                                                var perNombre = element.perNombre;
                                            }
                                            myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;
                    
                                            if(element.insAproboParcial1 == "SI"){
                                                myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="${element.insCalificacionRecuperativoParcial1}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="SiDisabled"></td>`;
        
                                            }else{
                                                if (element.insCalificacionRecuperativoParcial1 >= 6){
                                                    myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="${element.insCalificacionRecuperativoParcial1}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>`;
        
                                                }else{
                                                    myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial1" name="insCalificacionRecuperativoParcial1[]" value="${element.insCalificacionRecuperativoParcial1}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo1" name="validandoSiEstaInactivo1[]" value="NoDisabled"></td>`;
        
                                                } 
                                            }
                                            
        
                                            if(element.insAproboParcial2 == "SI"){
                                                myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="${element.insCalificacionRecuperativoParcial2}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="SiDisabled"></td>`;
        
                                            }else{
                                                if (element.insCalificacionRecuperativoParcial2 >= 6){
                                                    myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="${element.insCalificacionRecuperativoParcial2}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>`;
        
                                                }else{
                                                    myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial2" name="insCalificacionRecuperativoParcial2[]" value="${element.insCalificacionRecuperativoParcial2}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo2" name="validandoSiEstaInactivo2[]" value="NoDisabled"></td>`;
        
                                                } 
                                            }
        
                                            if(element.insAproboParcial3 == "SI"){
                                                myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="${element.insCalificacionRecuperativoParcial3}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="SiDisabled"></td>`;
        
                                            }else{
                                                if (element.insCalificacionRecuperativoParcial3 >= 6){
                                                    myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="${element.insCalificacionRecuperativoParcial3}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>`;
        
                                                }else{
                                                    myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial3" name="insCalificacionRecuperativoParcial3[]" value="${element.insCalificacionRecuperativoParcial3}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo3" name="validandoSiEstaInactivo3[]" value="NoDisabled"></td>`;
        
                                                } 
                                            }
        
                                            if(element.insAproboParcial4 == "SI"){
                                                myTable += `<td><input readonly style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="${element.insCalificacionRecuperativoParcial4}"></td>`;
                                                myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="SiDisabled"></td>`;
        
                                            }else{
                                                if (element.insCalificacionRecuperativoParcial4 >= 6){
                                                    myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="${element.insCalificacionRecuperativoParcial4}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="Noisabled"></td>`;
        
                                                }else{
                                                    myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionRecuperativoParcial4" name="insCalificacionRecuperativoParcial4[]" value="${element.insCalificacionRecuperativoParcial4}"></td>`;
                                                    myTable += `<td style='display:none;'><input class="noUpperCase" type="text" id="validandoSiEstaInactivo4" name="validandoSiEstaInactivo4[]" value="Noisabled"></td>`;
        
                                                } 
                                            }                               
                                            
                                            
                                            myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="recuperacion"></td>`;
        
                        
                                            myTable += "</tr>";      
                    
        
                                    }  
                                    
                
                
                            });
                
                            myTable += "</table>";
                
                            document.getElementById('tablePrint').innerHTML = myTable;
                        }
        
                        if(que_vamos_a_calificar == "extraordinario"){
        
                            let myTable = "<table><tr>"
                                myTable += "<th style='display:none;'></th>";
                                myTable += "<th>#</th>";
                                myTable += "<th>Clave Pago</th>";
                                myTable += "<th>Nombre Alumno</th>";
                                myTable += "<th>Calificación Recuperativo 1</th>";    
                                myTable += "<th>Calificación Recuperativo 2</th>";                    
                                myTable += "<th>Calificación Recuperativo 3</th>"; 
                                myTable += "<th>Calificación Recuperativo 4</th>"; 
                                myTable += "<th style='display:none;'></th>"; 
                                myTable += "<th style='display:none;'></th>";
                            
                                myTable += "</tr>";
        
                                bachiller_cch_inscritos.forEach(function(element, i) {
            
                                    myTable += "<tr>";
                                        myTable += `<td style='display:none;'><input type="text" name="bachiller_cch_inscrito_id[]" id="bachiller_cch_inscrito_id" value="${element.id}"></td>`;
                                        myTable += `<td>${total++}</td>`;
                                        myTable += `<td>${element.aluClave}</td>`;
                                        
                                        if(element.perApellido1 == null){
                                            var perApellido1 = "";
                                        }else{
                                            var perApellido1 = element.perApellido1;
                                        }

                                        if(element.perApellido2 == null){
                                            var perApellido2 = "";
                                        }else{
                                            var perApellido2 = element.perApellido2;
                                        }

                                        if(element.perNombre == null){
                                            var perNombre = "";
                                        }else{
                                            var perNombre = element.perNombre;
                                        }
                                        myTable += `<td>${perApellido1} ${perApellido2} ${perNombre}</td>`;

                
                                        if (element.insCalificacionExtraOrdinarioParcial1 < 6){
                                            myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="${element.insCalificacionExtraOrdinarioParcial1}"></td>`;
                
                                        }else{
                                            myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial1" name="insCalificacionExtraOrdinarioParcial1[]" value="${element.insCalificacionExtraOrdinarioParcial1}"></td>`;
                
                                        } 
                                        
                                        if (element.insCalificacionExtraOrdinarioParcial2 < 6){
                                            myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="${element.insCalificacionExtraOrdinarioParcial2}"></td>`;
                
                                        }else{
                                            myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial2" name="insCalificacionExtraOrdinarioParcial2[]" value="${element.insCalificacionExtraOrdinarioParcial2}"></td>`;
                
                                        } 
        
                                        if (element.insCalificacionExtraOrdinarioParcial3 < 6){
                                            myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="${element.insCalificacionExtraOrdinarioParcial3}"></td>`;
                
                                        }else{
                                            myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial3" name="insCalificacionExtraOrdinarioParcial3[]" value="${element.insCalificacionExtraOrdinarioParcial3}"></td>`;
                
                                        } 
        
        
                                        if (element.insCalificacionExtraOrdinarioParcial4 < 6){
                                            myTable += `<td><input style="border-color: red;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="${element.insCalificacionExtraOrdinarioParcial4}"></td>`;
                
                                        }else{
                                            myTable += `<td><input style="border-color: #01579B;" onblur="if(this.value>10){this.value='';}else if(this.value<0){this.value='';}" step="0.1" class="noUpperCase" type="number" id="insCalificacionExtraOrdinarioParcial4" name="insCalificacionExtraOrdinarioParcial4[]" value="${element.insCalificacionExtraOrdinarioParcial4}"></td>`;
                
                                        } 
        
                                        
                                        myTable += `<td style='display:none;'><input type="text" name="que_vamos_a_calificar" id="que_vamos_a_calificar" value="extraordinario"></td>`;
        
                    
                                        myTable += "</tr>";      
                
                
                
                            });
                
                            myTable += "</table>";
                
                            document.getElementById('tablePrint').innerHTML = myTable;
                        }
                        
                    }
                }

                
            });
        });
    
    });
</script>