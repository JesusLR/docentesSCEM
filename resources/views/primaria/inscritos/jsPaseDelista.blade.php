//Para obtener los alumnos
<script type="text/javascript">
    $(document).ready(function() {
    
        //Cuando se hace cambio de fecha 
        $("#fecha_asistencia").change( event => {
            var grupo_id = $("#grupo_id").val();
            $.get(base_url+`/primaria_inscritos/obtenerAlumnosPaseLista/${grupo_id}/${event.target.value}`,function(res,sta){

                
                var registros_guardados = res.primaria_asistencia.length;
                var alumnos_curso = res.cursosPaseLista.length;
    
                if(registros_guardados > 0){
    
                    $("#alertaPaseLista").hide();
                    $(".btn-cambia-nombre").text("ACTUALIZAR");
    
                    var estadoAsistencia = [];
                    var asistenciaId = [];
    
                    const data = res.primaria_asistencia;
                    
    
                            const tableData = data.map(function(element){              
                                
                                estadoAsistencia.push(element.estado);
                                asistenciaId.push(element.id);
    
                                return (
    
                                    `<tr>
                                        <td style='display:none;'><input id='' name='asistencia_id[]' value='${element.id}'></td>
                                        <td>${element.aluClave}</td>
                                        <td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>
                                        <td>                                       
                                            <select style='margin-top: -18px;' id="asistencia_${element.id}" class="browser-default validate select2" name="estado[]" style="width: 100%;">
                                                <option value="A">ASISTENCIA</option>
                                                <option value="F">FALTA</option>
                                                <option value="FJ">FALTA JUSTIFICADA</option>
                                            </select>
                                        </td>
                                        <td style='display:none;'><input name='tipoDeAccion' value='ACTUALIZAR_ASISTENCIA'></td>
                                        <td style='display:none;'><input id='' name='asistencia_inscrito_id[]' value='${element.asistencia_inscrito_id}'></td>
    
                                    </tr>`
                                    
                                );
                            }).join('');
    
                        const tableBody = document.querySelector("#tableBodyLista");
                        tableBody.innerHTML = tableData;  
    
                        for(var asis=0; asis < estadoAsistencia.length; asis++){
                            for(var asisID=0; asisID < asistenciaId.length; asisID++){
                                $("#asistencia_"+asistenciaId[asis]).val(estadoAsistencia[asis]);
                            }
                        }
                }else{
    
                    $("#alertaPaseLista").show();
                    $(".btn-cambia-nombre").text("GUARDAR");

                    //Si no hay asistencias guardadas del dia del sistema carga los siguiente
                    const dataNuevoRegistro = res.cursosPaseLista;
                    
    
                            const tableData = dataNuevoRegistro.map(function(element){              
                                                         
    
                                return (
    
                                    `<tr>
                                        <td>${element.aluClave}</td>
                                        <td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>
                                        <td>                                       
                                            <select style='margin-top: -18px;' id="asistencia_${element.id}" class="browser-default validate select2" name="estado[]" style="width: 100%;">
                                                <option value="A">ASISTENCIA</option>
                                                <option value="F">FALTA</option>
                                                <option value="FJ">FALTA JUSTIFICADA</option>
                                            </select>
                                        </td>
                                        <td style='display:none;'><input name='tipoDeAccion' value='GUARDAR_ASISTENCIA'></td>
                                        <td style='display:none;'><input id='' name='asistencia_inscrito_id[]' value='${element.inscrito_id}'></td>
    
                                    </tr>`
                                    
                                );
                            }).join('');
    
                        const tableBody = document.querySelector("#tableBodyLista");
                        tableBody.innerHTML = tableData;  
                }
            });
        });


        //Para cuando carga la pantalla
        var grupo_id = $("#grupo_id").val();
        var fecha_asistencia = $("#fecha_asistencia").val();

        $.get(base_url+`/primaria_inscritos/obtenerAlumnosPaseLista/${grupo_id}/${fecha_asistencia}`,function(res,sta){
            var registros_guardados = res.primaria_asistencia.length;
            var alumnos_curso = res.cursosPaseLista.length;

            console.log(res.cursosPaseLista)
            if(registros_guardados > 0){

                $("#alertaPaseLista").hide();
                $(".btn-cambia-nombre").text("ACTUALIZAR");

                var estadoAsistencia = [];
                var asistenciaId = [];

                const data = res.primaria_asistencia;
                

                        const tableData = data.map(function(element){              
                            
                            estadoAsistencia.push(element.estado);
                            asistenciaId.push(element.id);

                            return (

                                `<tr>
                                    <td style='display:none;'><input id='' name='asistencia_id[]' value='${element.id}'></td>
                                    <td>${element.aluClave}</td>
                                    <td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>
                                    <td>                                       
                                        <select style='margin-top: -18px;' id="asistencia_${element.id}" class="browser-default validate select2" name="estado[]" style="width: 100%;">
                                            <option value="A">ASISTENCIA</option>
                                            <option value="F">FALTA</option>
                                            <option value="FJ">FALTA JUSTIFICADA</option>
                                        </select>
                                    </td>
                                    <td style='display:none;'><input name='tipoDeAccion' value='ACTUALIZAR_ASISTENCIA'></td>
                                    <td style='display:none;'><input id='' name='asistencia_inscrito_id[]' value='${element.asistencia_inscrito_id}'></td>

                                </tr>`
                                
                            );
                        }).join('');

                    const tableBody = document.querySelector("#tableBodyLista");
                    tableBody.innerHTML = tableData;  

                    for(var asis=0; asis < estadoAsistencia.length; asis++){
                        for(var asisID=0; asisID < asistenciaId.length; asisID++){
                            $("#asistencia_"+asistenciaId[asis]).val(estadoAsistencia[asis]);
                        }
                    }
            }else{

                $("#alertaPaseLista").show();
                $(".btn-cambia-nombre").text("GUARDAR");

                //Si no hay asistencias guardadas del dia del sistema carga los siguiente
                const dataNuevoRegistro = res.cursosPaseLista;
                

                        const tableData = dataNuevoRegistro.map(function(element){              
                                                     

                            return (

                                `<tr>
                                    <td>${element.aluClave}</td>
                                    <td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>
                                    <td>                                       
                                        <select style='margin-top: -18px;' id="asistencia_${element.id}" class="browser-default validate select2" name="estado[]" style="width: 100%;">
                                            <option value="A">ASISTENCIA</option>
                                            <option value="F">FALTA</option>
                                            <option value="FJ">FALTA JUSTIFICADA</option>
                                        </select>
                                    </td>
                                    <td style='display:none;'><input name='tipoDeAccion' value='GUARDAR_ASISTENCIA'></td>
                                    <td style='display:none;'><input id='' name='asistencia_inscrito_id[]' value='${element.inscrito_id}'></td>

                                </tr>`
                                
                            );
                        }).join('');

                    const tableBody = document.querySelector("#tableBodyLista");
                    tableBody.innerHTML = tableData;  
            }
        });
    });
</script>

