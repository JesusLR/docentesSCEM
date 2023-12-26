<script type="text/javascript">

    function click(){
        $("input[type='number']").blur(function() {
            this.value = parseFloat(this.value).toFixed(1);
        });
    }
    $(document).ready(function() {

        function obtenerGrupos(grupoId) {
           
            $("#grupo_id2").empty();


            
            $("#grupo_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            

            $.get(base_url+`/secundaria_calificacion/api/getGrupos/${grupoId}`, function(res,sta) {

                //seleccionar el post preservado
                var grupoSeleccionadoOld = $("#grupo_id2").data("grupo2-idold")
                $("#grupo_id2").empty()
                res.forEach(element => {
                    var selected = "";
                    if (element.id === grupoSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.grupo_id)
                        selected = "selected";
                    }

                    $("#grupo_id2").append(`<option value=${element.grupo_id} ${selected}>${element.gpoGrado}${element.gpoClave}, Programa: ${element.progNombre}, Clave materia: ${element.matClave}</option>`);
                    
                });
                $('#grupo_id2').trigger('change'); // Notify only Select2 of changes
            });
        }
        
        obtenerGrupos($("#periodo_id2").val())
        $("#periodo_id2").change( event => {
            obtenerGrupos(event.target.value)
        });
     });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        function obtenerMaterias2(materiaId2) {
           
            $("#materia_id2").empty();


            
            $("#materia_id2").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

            

            $.get(base_url+`/secundaria_calificacion/api/getMaterias2/${materiaId2}`, function(res,sta) {

                //seleccionar el post preservado
                var materia2SeleccionadoOld = $("#materia_id2").data("materia2-idold")
                $("#materia_id2").empty()
                res.forEach(element => {
                    var selected = "";
                    if (element.id === materia2SeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                    }

                    $("#materia_id2").append(`<option value=${element.id} ${selected}>${element.matNombre}</option>`);
                    
                });
                $('#materia_id2').trigger('change'); // Notify only Select2 of changes
            });
        }
        
        obtenerMaterias2($("#grupo_id2").val())
        $("#grupo_id2").change( event => {
            obtenerMaterias2(event.target.value)
        });
     });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        function obtenerAlumnos(alumnoId) {
            $.get(base_url+`/secundaria_calificacion/api/getAlumnos/${alumnoId}`, function(res,sta) {

               
                    const data = res;
                        const tableData = data.map(function(element){

                            function calcularPromedio(id){
                                var parciales = 0;
                                var promedio  = 0;
                                $('.evidencia_' + element.alumno_id).each(function(){
                                    if ($(this).val() != "") {
                                        parciales++;
                                        promedio = promedio + parseInt($(this).val());
                                    }
                                });

                                var parrafo1 = document.getElementById('evi1');
                                var porcentaje1 = parrafo1.innerHTML;

                                var parrafo2 = document.getElementById('evi2');
                                var porcentaje2 = parrafo2.innerHTML;

                                var parrafo3 = document.getElementById('evi3');
                                var porcentaje3 = parrafo3.innerHTML;

                                var parrafo4 = document.getElementById('evi4');
                                var porcentaje4 = parrafo4.innerHTML;

                                var parrafo5 = document.getElementById('evi5');
                                var porcentaje5 = parrafo5.innerHTML;

                                var parrafo6 = document.getElementById('evi6');
                                var porcentaje6 = parrafo6.innerHTML;

                                var parrafo7 = document.getElementById('evi7');
                                var porcentaje7 = parrafo7.innerHTML;

                                var parrafo8 = document.getElementById('evi8');
                                var porcentaje8 = parrafo8.innerHTML;

                                var parrafo9 = document.getElementById('evi9');
                                var porcentaje9 = parrafo9.innerHTML;

                                var parrafo10 = document.getElementById('evi10');
                                var porcentaje10 = parrafo10.innerHTML;

                                var numero_evidencias = $("#numero_evidencias").val();

                                if(numero_evidencias == 1){
                                    promedio = (promedio * (porcentaje1/100))                                    
                                }

                                if(numero_evidencias == 2){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));

                                    promedio = res1 + res2;
                                }

                                if(numero_evidencias == 3){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));

                                    promedio = res1 + res2 + res3;
                                }

                                if(numero_evidencias == 4){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));

                                    promedio = res1 + res2 + res3 + res4;
                                }

                                if(numero_evidencias == 5){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));

                                    promedio = res1 + res2 + res3 + res4 + res5;
                                }

                                if(numero_evidencias == 6){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6;
                                }

                                if(numero_evidencias == 7){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7;
                                }

                                if(numero_evidencias == 8){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));
                                    var res8 = (promedio * (porcentaje8/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7 + res8;
                                }
                                if(numero_evidencias == 9){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));
                                    var res8 = (promedio * (porcentaje8/100));
                                    var res9 = (promedio * (porcentaje8/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7 + res8 + res9;
                                }

                                if(numero_evidencias == 10){
                                    var res1 = (promedio * (porcentaje1/100));
                                    var res2 = (promedio * (porcentaje2/100));
                                    var res3 = (promedio * (porcentaje3/100));
                                    var res4 = (promedio * (porcentaje4/100));
                                    var res5 = (promedio * (porcentaje5/100));
                                    var res6 = (promedio * (porcentaje6/100));
                                    var res7 = (promedio * (porcentaje7/100));
                                    var res8 = (promedio * (porcentaje8/100));
                                    var res9 = (promedio * (porcentaje8/100));
                                    var res10 = (promedio * (porcentaje8/100));

                                    promedio = res1 + res2 + res3 + res4 + res5 + res6 + res7 + res8 + res9 + res10;
                                }
                             
                                promedio = promedio / parciales;

                                //muestra un solo decimal
                                promedio = promedio.toFixed(1);
                                
                                //promedio = promedio + 0.5
                            
                                //promedio = Math.trunc(promedio);
                            
                            
                                $('#promedioTotal' + element.alumno_id).val(promedio);
                            }
                                   
                            
                            $(function() {
                                $(".calif").on('change keyup', function(e) {
                                    var value = e.target.value
                                    console.log("entra")
                                    console.log(value)
    
                                    $(this).val(value || 0)
                            
                            
                                    if ($(this).data('inscritoid')) {
                            
                                        var inscritoId = $(this).data('inscritoid')
                            
                                        calcularPromedio(inscritoId)
                                    }
                                });
                            
                            
                            });


                            return (
                                `<tr>
                                    <td><input name='secundaria_inscrito_id[]' type='hidden' value='${element.id}'></td>
                                    <td>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</td>
                                    <td><input id='evidencia1' name='evidencia1[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia2' name='evidencia2[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia3' name='evidencia3[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia4' name='evidencia4[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia5' name='evidencia5[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia6' name='evidencia6[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia7' name='evidencia7[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia8' name='evidencia8[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia9' name='evidencia9[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input id='evidencia10' name='evidencia10[]' step="0.0" type='number' min="5" max="10" class='calif evidencia_${element.alumno_id}' data-inscritoid='${element.id}'></td>
                                    <td><input onmouseover="this.value = parseFloat(this.value).toFixed(1)" readonly='true' id='promedioTotal${element.alumno_id}' name='promedioTotal[]' step="0.0" type='number' min="5" max="10"></td>
                                </tr>`
                            );

                        
                        }).join('');
                    const tabelBody = document.querySelector("#tableBody");
                        tableBody.innerHTML = tableData;
                        $("#tableBody").hide();
                    
                        $("input[type='number']").blur(function() {
                            this.value = parseFloat(this.value).toFixed(1);
                        });
                        
                        
                   
                        
            });
        }
        
        obtenerAlumnos($("#grupo_id2").val())        
        $("#grupo_id2").change( event => {
            obtenerAlumnos(event.target.value)
        });
     });
</script>

{{--  obtener meses vigentes de evaluacion  --}}
<script type="text/javascript">
    $(document).ready(function() {
  
        function obtenerMesEvaluacion(mes_id) {
           
            $("#secundaria_grupo_evidencia_id").empty();
  
  
  
            $.get(base_url+`/secundaria_grupo/getMesEvidencias/${mes_id}`, function(res,sta) {
  
                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#secundaria_grupo_evidencia_id").data("mes-idold")
                $("#secundaria_grupo_evidencia_id").empty()

                if(res != ""){
                    $("#secundaria_grupo_evidencia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === numeroEvaSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }
      
                        $("#secundaria_grupo_evidencia_id").append(`<option value=${element.id} ${selected}>${element.mes}</option>`);
                        
                    });
                    $('#secundaria_grupo_evidencia_id').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#secundaria_grupo_evidencia_id").append(`<option value="" selected disabled>NO HAY MES EVIDICENCIA PARA ESTE GRUPO</option>`);

                }
                
            });
        }
        
        obtenerMesEvaluacion($("#grupo_id2").val())
        $("#grupo_id2").change( event => {
            obtenerMesEvaluacion(event.target.value)
        });
     });
  </script>


<script type="text/javascript">
    $(document).ready(function() {
  
        function obtenerMeses(id_evidencia_grupo) {
           
            $("#mes").empty();
  
  
            
            $("#mes").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`); 
            
  
            $.get(base_url+`/secundaria_calificacion/getMeses/${id_evidencia_grupo}`, function(res,sta) {
  
                //seleccionar el post preservado
                var mesesSeleccionadoOld = $("#mes").data("mes-idold")
                $("#mes").empty()
                console.log(res)
                if(res != ""){
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === mesesSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }
      
                        $("#mes").append(`<option value=${element.mes} ${selected}>${element.mes}</option>`);
                        
                    });
                    $('#mes').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#mes").append(`<option value="" selected disabled>EL MES NO SE ENCUENTRA SELECCIONADO</option>`);
                }
            });
        }
        
        obtenerMeses($("#secundaria_grupo_evidencia_id").val())
        $("#secundaria_grupo_evidencia_id").change( event => {
            obtenerMeses(event.target.value)
        });
     });    
  </script>

  {{--  obtener numero de  evaluacion  --}}
<script type="text/javascript">
    $(document).ready(function() {
  
        function obtenerNumEvaluacion(mes) {
           
            $("#numero_evaluacion").empty();
  
  
            
            $("#numero_evaluacion").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`); 
            
  
            $.get(base_url+`/secundaria_calificacion/getNumeroEvaluacion/${mes}`, function(res,sta) {
  
                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#numero_evaluacion").data("numero-evaluacion-idold")
                $("#numero_evaluacion").empty()
                console.log(res)
                if(res != ""){
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === numeroEvaSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }
      
                        $("#numero_evaluacion").append(`<option value=${element.numero_evaluacion} ${selected}>${element.numero_evaluacion}</option>`);
                        $("#input-field").removeClass("input-field");
                        $("#numero_evidencias").val(element.numero_evidencias);
                        $("#Tabla").show();
                        $("#tableBody").show();

                        if(element.numero_evidencias == 1){
                                                        
                            $(".classEvi2").hide();
                            $('td:nth-child(4)').hide();

                            $(".classEvi3").hide();
                            $('td:nth-child(5)').hide();

                            $(".classEvi4").hide();
                            $('td:nth-child(6)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 2){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").hide();
                            $('td:nth-child(5)').hide();

                            $(".classEvi4").hide();
                            $('td:nth-child(6)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 3){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").hide();
                            $('td:nth-child(6)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 4){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi5").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 5){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi6").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 6){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi7").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 7){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi8").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 8){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi9").hide();
                            $('td:nth-child(11)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 9){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi9").show();
                            $('td:nth-child(11)').show();
                            $(".classEvi10").hide();
                            $('td:nth-child(12)').hide();
                        }

                        if(element.numero_evidencias == 10){
                                                        
                            $(".classEvi2").show();
                            $('td:nth-child(4)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi9").show();
                            $('td:nth-child(11)').show();
                            $(".classEvi10").show();
                            $('td:nth-child(12)').show();
                        }



                        //pintar los porcentajes de cada evidencia 
                        if(element.porcentaje_evidencia1 != null){
                            $("#nombreEvidencia1").text(element.concepto_evidencia1);
                            $("#evi1").text(element.porcentaje_evidencia1);                            
                        }else{
                            $("#nombreEvidencia1").text("NA");
                            $("#evi1").text("NA");
                        }

                        if(element.porcentaje_evidencia2 != null){
                            $("#nombreEvidencia2").text(element.concepto_evidencia2);
                            $("#evi2").text(element.porcentaje_evidencia2);
                        }else{
                            $("#nombreEvidencia2").text("NA");
                            $("#evi2").text("NA");
                        }

                        if(element.porcentaje_evidencia3 != null){
                            $("#nombreEvidencia3").text(element.concepto_evidencia3);
                            $("#evi3").text(element.porcentaje_evidencia3);
                        }else{
                            $("#nombreEvidencia3").text("NA");
                            $("#evi3").text("NA");
                        }

                        if(element.porcentaje_evidencia4 != null){
                            $("#nombreEvidencia4").text(element.concepto_evidencia4);
                            $("#evi4").text(element.porcentaje_evidencia4);
                        }else{
                            $("#nombreEvidencia4").text("NA");
                            $("#evi4").text("NA");
                        }
                        if(element.porcentaje_evidencia5 != null){
                            $("#nombreEvidencia5").text(element.concepto_evidencia5);
                            $("#evi5").text(element.porcentaje_evidencia5);
                        }else{
                            $("#nombreEvidencia5").text("NA");
                            $("#evi5").text("NA");
                        }

                        if(element.porcentaje_evidencia6 != null){
                            $("#nombreEvidencia6").text(element.concepto_evidencia6);
                            $("#evi6").text(element.porcentaje_evidencia6);
                        }else{
                            $("#nombreEvidencia6").text("NA");
                            $("#evi6").text("NA");
                        }

                        if(element.porcentaje_evidencia7 != null){
                            $("#nombreEvidencia7").text(element.concepto_evidencia7);
                            $("#evi7").text(element.porcentaje_evidencia7);
                        }else{
                            $("#nombreEvidencia7").text("NA");
                            $("#evi7").text("NA");
                        }

                        if(element.porcentaje_evidencia8 != null){
                            $("#nombreEvidencia8").text(element.concepto_evidencia8);
                            $("#evi8").text(element.porcentaje_evidencia8);
                        }else{
                            $("#nombreEvidencia8").text("");
                            $("#evi8").text("NA");
                        }

                        if(element.porcentaje_evidencia9 != null){
                            $("#nombreEvidencia9").text(element.concepto_evidencia9);
                            $("#evi9").text(element.porcentaje_evidencia9);
                        }else{
                            $("#nombreEvidencia9").text("NA");
                            $("#evi9").text("NA");
                        }

                        if(element.porcentaje_evidencia10 != null){
                            $("#nombreEvidencia10").text(element.concepto_evidencia10);
                            $("#evi10").text(element.porcentaje_evidencia10);
                        }else{
                            $("#nombreEvidencia10").text("NA");
                            $("#evi10").text("NA");
                        }
                       
                        
                    });
                    $('#numero_evaluacion').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#numero_evaluacion").append(`<option value="" selected disabled>EL MES NO SE ENCUENTRA SELECCIONADO</option>`);
                }
            });
        }
        
        obtenerNumEvaluacion($("#mes").val())
        $("#mes").change( event => {
            obtenerNumEvaluacion(event.target.value)
        });
     });
    
  </script>