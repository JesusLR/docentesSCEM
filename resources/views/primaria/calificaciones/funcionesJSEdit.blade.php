
  {{--  obtener meses vigentes de evaluacion  --}}
<script type="text/javascript">
    $(document).ready(function() {

        function obtenerMesEvaluacion(mes_id) {

            $("#primaria_grupo_evidencia_id").empty();



            $.get(base_url+`/primaria_grupo/getMesEvidencias/${mes_id}`, function(res,sta) {

                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#primaria_grupo_evidencia_id").data("mes-idold")
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

        obtenerMesEvaluacion($("#primaria_grupo_id2").val())
        $("#primaria_grupo_id2").change( event => {
            obtenerMesEvaluacion(event.target.value)
        });
     });
</script>


<script type="text/javascript">
    $(document).ready(function() {

        function obtenerMeses(id_evidencia_grupo) {

            $("#mes").empty();



            $.get(base_url+`/primaria_calificacion/getMeses/${id_evidencia_grupo}`, function(res,sta) {

                //seleccionar el post preservado
                var mesesSeleccionadoOld = $("#mes").data("mes-idold")
                $("#mes").empty()

                if(res != ""){
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === mesesSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }

                        $("#mes").append(`<option value=${element.mes} ${selected}>${element.mes}</option>`);

                        $("#queMes").text(`${element.mes}`);



                    });
                    $('#mes').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#mes").append(`<option value="" selected disabled>EL MES NO SE ENCUENTRA SELECCIONADO</option>`);

                }
            });
        }

        obtenerMeses($("#primaria_grupo_evidencia_id").val())
        $("#primaria_grupo_evidencia_id").change( event => {
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


            $.get(base_url+`/primaria_calificacion/getNumeroEvaluacionCreate/${mes}`, function(res,sta) {

                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#numero_evaluacion").data("numero-evaluacion-idold")
                $("#numero_evaluacion").empty()

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

                        if(element.numero_evidencias == 1){



                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();

                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();

                            }
                        }

                        if(element.numero_evidencias == 2){

                                              //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 3){



                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 4){


                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 5){


                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 6){

                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 7){




                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 8){


                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 9){


                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
                        }

                        if(element.numero_evidencias == 10){

                            //Validamos el porcentaje del mes -> es en igual a 100 mostramos el boton de guardar, de lo contrario se oculta, lo mismo pasa con la alerta
                            if(element.porcentaje_total == 100){
                                $("#btn-ocultar-si-es-menor-a-cien").show();
                                $("#alerta-menos-de-ciente").hide();
                                $("#alerta-min-max-calif").show();
                            }else{
                                $("#btn-ocultar-si-es-menor-a-cien").hide();
                                $("#alerta-menos-de-ciente").show();
                                $("#alerta-min-max-calif").hide();
                            }
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

        obtenerNumEvaluacion($("#primaria_grupo_evidencia_id").val())
        $("#primaria_grupo_evidencia_id").change( event => {
            obtenerNumEvaluacion(event.target.value)
        });
     });

  </script>


  <script type="text/javascript">
    $(document).ready(function() {     


        $("#primaria_grupo_evidencia_id").change( event => {
            var primaria_grupo_id2 = $("#primaria_grupo_id2").val();
            
            $.get(base_url+`/api/getCalificacionesAlumnos/${event.target.value}/${primaria_grupo_id2}`,function(res,sta){
                const calificaciones = res.calificaciones;
                let numero_evidencias = $("#numero_evidencias").val();
                let mes = $("#mes").val();

                if(calificaciones.length > 0)
                {
                    $(".btn-guardar").show();
                    $(".btn-guardar").text("ACTUALIZAR")

                    $("#Tabla").show();
                    $("#info").html("");

                    const data = res.calificaciones;

                    const tableData = data.map(function(element, index, arrayobj) {

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

                                var evidencia = 0;
                                var valorCalificacion  = 0;
                                var promedio  = 0;
                                $('.evidencia_' + element.primaria_inscrito_id).each(function(){
                                    if ($(this).val() != "") {
                                        evidencia++;
                                        valorCalificacion = parseFloat($(this).val());
                                        if(evidencia == 1){
                                            calificacion1 = valorCalificacion * (porcentaje1/100);
                                        }
                                        if(evidencia == 2){
                                            calificacion2 = valorCalificacion * (porcentaje2/100);
                                        }
                                        if(evidencia == 3){
                                            calificacion3 = valorCalificacion * (porcentaje3/100);
                                        }
                                        if(evidencia == 4){
                                            calificacion4 = valorCalificacion * (porcentaje4/100);
                                        }
                                        if(evidencia == 5){
                                            calificacion5 = valorCalificacion * (porcentaje5/100);
                                        }
                                        if(evidencia == 6){
                                            calificacion6 = valorCalificacion * (porcentaje6/100);
                                        }
                                        if(evidencia == 2){
                                            calificacion7 = valorCalificacion * (porcentaje7/100);
                                        }
                                        if(evidencia == 8){
                                            calificacion8 = valorCalificacion * (porcentaje8/100);
                                        }
                                        if(evidencia == 9){
                                            calificacion9 = valorCalificacion * (porcentaje9/100);
                                        }
                                        if(evidencia == 10){
                                            calificacion10 = valorCalificacion * (porcentaje10/100);
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
                            return (
                                `<tr>
                                    <td style="display:none"><input name='id[]' type='text' value='${element.id}'></td>
                                    <td style="display:none"><input name='primaria_inscrito_id[]' type='text' value='${element.primaria_inscrito_id}'></td>
                                    <td>${index+1}</td>
                                    <td><p>${element.aluClave}</p></td>
                                    <td><p>${element.perApellido1} ${element.perApellido2} ${element.perNombre}</p></td>
                                    <td><input tabindex="1" type='number' id='evidencia1' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia1}' name='evidencia1[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia2' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia2}' name='evidencia2[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia3' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia3}' name='evidencia3[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia4' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia4}' name='evidencia4[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia5' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia5}' name='evidencia5[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia6' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia6}' name='evidencia6[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia7' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia7}' name='evidencia7[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia8' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia8}' name='evidencia8[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia9' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia9}' name='evidencia9[]' step="0.1" lang="en"  min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input type='number' id='evidencia10' onblur="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" value='${element.calificacion_evidencia10}' name='evidencia10[]' step="0.1" lang="en" min="5" max="10" class='calif evidencia_${element.primaria_inscrito_id} noUpperCase' data-inscritoid='${element.primaria_inscrito_id}'></td>
                                    <td><input tabindex="11" type='number' lang="en" onmouseover="this.value = parseFloat(this.value).toFixed(1)" readonly='true' id='promedioTotal${element.primaria_inscrito_id}' name='promedioTotal[]' step="0.01"  min="5" max="10" value='${element.promedio_mes}' onclick="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}" onmouseout="if(this.value>10){this.value='';}else if(this.value<5){this.value='';}"></td>
                                    <td style="display:none"><input name='tipoDeAccion' type='text' value='ACTUALIZAR'></td>
                                </tr>`
                            );


                        }).join('');
                    const tableBody = document.querySelector("#tableBody");
                        tableBody.innerHTML = tableData;

                        $("input[type='number']").blur(function() {
                            this.value = parseFloat(this.value).toFixed(1);
                        });





                    //document.getElementById('tableBody').innerHTML = myTable;
                    $('td:nth-child(2)').hide();
                    $('td:nth-child(1)').hide();



                    //numero de evidencias mostrar
                    if (numero_evidencias == 1) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").hide();
                        $(".classEvi3").hide();
                        $(".classEvi4").hide();
                        $(".classEvi5").hide();
                        $(".classEvi6").hide();
                        $(".classEvi7").hide();
                        $(".classEvi8").hide();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').hide();
                        $('td:nth-child(8)').hide();
                        $('td:nth-child(9)').hide();
                        $('td:nth-child(10)').hide();
                        $('td:nth-child(11)').hide();
                        $('td:nth-child(12)').hide();
                        $('td:nth-child(13)').hide();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();


                    }

                    if (numero_evidencias == 2) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").hide();
                        $(".classEvi4").hide();
                        $(".classEvi5").hide();
                        $(".classEvi6").hide();
                        $(".classEvi7").hide();
                        $(".classEvi8").hide();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();


                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').hide();
                        $('td:nth-child(9)').hide();
                        $('td:nth-child(10)').hide();
                        $('td:nth-child(11)').hide();
                        $('td:nth-child(12)').hide();
                        $('td:nth-child(13)').hide();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();


                    }
                    if (numero_evidencias == 3) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").hide();
                        $(".classEvi5").hide();
                        $(".classEvi6").hide();
                        $(".classEvi7").hide();
                        $(".classEvi8").hide();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').hide();
                        $('td:nth-child(10)').hide();
                        $('td:nth-child(11)').hide();
                        $('td:nth-child(12)').hide();
                        $('td:nth-child(13)').hide();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();
                    }

                    if (numero_evidencias == 4) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").show();
                        $(".classEvi5").hide();
                        $(".classEvi6").hide();
                        $(".classEvi7").hide();
                        $(".classEvi8").hide();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').show();
                        $('td:nth-child(10)').hide();
                        $('td:nth-child(11)').hide();
                        $('td:nth-child(12)').hide();
                        $('td:nth-child(13)').hide();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();
                    }

                    if (numero_evidencias == 5) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").show();
                        $(".classEvi5").show();
                        $(".classEvi6").hide();
                        $(".classEvi7").hide();
                        $(".classEvi8").hide();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').show();
                        $('td:nth-child(10)').show();
                        $('td:nth-child(11)').hide();
                        $('td:nth-child(12)').hide();
                        $('td:nth-child(13)').hide();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();
                    }

                    if (numero_evidencias == 6) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").show();
                        $(".classEvi5").show();
                        $(".classEvi6").show();
                        $(".classEvi7").hide();
                        $(".classEvi8").hide();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').show();
                        $('td:nth-child(10)').show();
                        $('td:nth-child(11)').show();
                        $('td:nth-child(12)').hide();
                        $('td:nth-child(13)').hide();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();
                    }

                    if (numero_evidencias == 7) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").show();
                        $(".classEvi5").show();
                        $(".classEvi6").show();
                        $(".classEvi7").show();
                        $(".classEvi8").hide();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').show();
                        $('td:nth-child(10)').show();
                        $('td:nth-child(11)').show();
                        $('td:nth-child(12)').show();
                        $('td:nth-child(13)').hide();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();
                    }

                    if (numero_evidencias == 8) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").show();
                        $(".classEvi5").show();
                        $(".classEvi6").show();
                        $(".classEvi7").show();
                        $(".classEvi8").show();
                        $(".classEvi9").hide();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').show();
                        $('td:nth-child(10)').show();
                        $('td:nth-child(11)').show();
                        $('td:nth-child(12)').show();
                        $('td:nth-child(13)').show();
                        $('td:nth-child(14)').hide();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();
                    }

                    if (numero_evidencias == 9) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").show();
                        $(".classEvi5").show();
                        $(".classEvi6").show();
                        $(".classEvi7").show();
                        $(".classEvi8").show();
                        $(".classEvi9").show();
                        $(".classEvi10").hide();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').show();
                        $('td:nth-child(10)').show();
                        $('td:nth-child(11)').show();
                        $('td:nth-child(12)').show();
                        $('td:nth-child(13)').show();
                        $('td:nth-child(14)').show();
                        $('td:nth-child(15)').hide();
                        $('td:nth-child(16)').show();
                    }

                    if (numero_evidencias == 10) {

                        //ocultamos y mostramos las cabeceras correspondientes
                        $(".classEvi1").show();
                        $(".classEvi2").show();
                        $(".classEvi3").show();
                        $(".classEvi4").show();
                        $(".classEvi5").show();
                        $(".classEvi6").show();
                        $(".classEvi7").show();
                        $(".classEvi8").show();
                        $(".classEvi9").show();
                        $(".classEvi10").show();
                        $(".classPromedioMes").show();

                        //ocultamos o mostramos los colunmnas correspondientes
                        $('td:nth-child(6)').show();
                        $('td:nth-child(7)').show();
                        $('td:nth-child(8)').show();
                        $('td:nth-child(9)').show();
                        $('td:nth-child(10)').show();
                        $('td:nth-child(11)').show();
                        $('td:nth-child(12)').show();
                        $('td:nth-child(13)').show();
                        $('td:nth-child(14)').show();
                        $('td:nth-child(15)').show();
                        $('td:nth-child(16)').show();
                    }



                }else{

                }
            });
        });
        
    });
</script>
