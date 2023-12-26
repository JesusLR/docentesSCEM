

  {{--  obtener meses vigentes de evaluacion  --}}
  <script type="text/javascript">
    $(document).ready(function() {

        function obtenerMesEvaluacion(mes_id) {

            $("#bachiller_cch_grupo_evidencia_id").empty();



            $.get(base_url+`/bachiller_grupo_seq/getMesEvidencias/${mes_id}`, function(res,sta) {

                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#bachiller_cch_grupo_evidencia_id").data("mes-idold")
                $("#bachiller_cch_grupo_evidencia_id").empty()
                //$("#bachiller_cch_grupo_evidencia_id").append(`<option value='' selected disabled}>SELECCIONE UNA OPCION</option>`);


                if(res.mesEvidencia != ""){

                    res.mesEvidencia.forEach(element => {
                        var selected = "";
                        if (element.id === numeroEvaSeleccionadoOld) {
                            console.log("entra")
                            console.log(element.id)
                            selected = "selected";
                        }




                       $("#bachiller_cch_grupo_evidencia_id").append(`<option value=${element.id} ${selected}>${element.mes}</option>`);

                    });
                    $('#bachiller_cch_grupo_evidencia_id').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#bachiller_cch_grupo_evidencia_id").append(`<option value="" selected disabled>NO HAY MES EVIDICENCIA PARA ESTE GRUPO</option>`);

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



            $.get(base_url+`/bachiller_calificacion_seq/getMeses/${id_evidencia_grupo}`, function(res,sta) {

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

                    });
                    $('#mes').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#mes").append(`<option value="" selected disabled>EL MES NO SE ENCUENTRA SELECCIONADO</option>`);

                }
            });
        }

        obtenerMeses($("#bachiller_cch_grupo_evidencia_id").val())
        $("#bachiller_cch_grupo_evidencia_id").change( event => {
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


            $.get(base_url+`/bachiller_calificacion_seq/getNumeroEvaluacion/${mes}`, function(res,sta) {

                //seleccionar el post preservado
                var numeroEvaSeleccionadoOld = $("#numero_evaluacion").data("numero-evaluacion-idold")
                $("#numero_evaluacion").empty()

                if(res != ""){
                    res.forEach(element => {
                        var selected = "";
                        if (element.id === numeroEvaSeleccionadoOld) {
                            console.log("entra")
                            selected = "selected";
                        }

                        $("#numero_evaluacion").append(`<option value=${element.numero_evaluacion} ${selected}>${element.numero_evaluacion}</option>`);
                        $("#input-field").removeClass("input-field");
                        $("#numero_evidencias").val(element.numero_evidencias);

                        //mostrar las faltas del mes correspondiente  SEPTIMBRE
                        /*if($('select[id=numero_evaluacion]').val() == "1"){
                            $("#faltaSep").show();

                            $(".classtotalFaltasSep").show();
                            $('td:nth-child(15)').show();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();


                            //CONDUCTA
                            $(".classConductaSep").show();
                            $('td:nth-child(25)').show();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();


                        }else{
                            $("#faltaSep").hide();
                        }

                        //mostrar las faltas del mes correspondiente  OCTUBRE
                        if($('select[id=numero_evaluacion]').val() == "2"){
                            $("#faltaOct").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").show();
                            $('td:nth-child(16)').show();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();


                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").show();
                            $('td:nth-child(26)').show();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();

                        }else{
                            $("#faltaOct").hide();
                        }

                        //mostrar las faltas del mes correspondiente  NOVIEMBRE
                        if($('select[id=numero_evaluacion]').val() == "3"){
                            $("#faltaNov").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").show();
                            $('td:nth-child(17)').show();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();


                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").show();
                            $('td:nth-child(27)').show();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();
                        }else{
                            $("#faltaNov").hide();
                        }

                        //mostrar las faltas del mes correspondiente  DICIEMBRE
                        if($('select[id=numero_evaluacion]').val() == "4"){
                            $("#faltaDic").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").show();
                            $('td:nth-child(18)').show();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();

                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").show();
                            $('td:nth-child(28)').show();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();
                        }else{
                            $("#faltaDic").hide();
                        }

                        //mostrar las faltas del mes correspondiente  ENERO
                        if($('select[id=numero_evaluacion]').val() == "5"){
                            $("#faltaEne").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").show();
                            $('td:nth-child(19)').show();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();

                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").show();
                            $('td:nth-child(29)').show();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();
                        }else{
                            $("#faltaEne").hide();
                        }

                        //mostrar las faltas del mes correspondiente  FEBRERO
                        if($('select[id=numero_evaluacion]').val() == "6"){
                            $("#faltaFeb").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").show();
                            $('td:nth-child(20)').show();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();


                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").show();
                            $('td:nth-child(30)').show();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();
                        }else{
                            $("#faltaFeb").hide();
                        }

                        //mostrar las faltas del mes correspondiente  MARZO
                        if($('select[id=numero_evaluacion]').val() == "7"){
                            $("#faltaMar").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").show();
                            $('td:nth-child(21)').show();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();


                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").show();
                            $('td:nth-child(31)').show();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();
                        }else{
                            $("#faltaMar").hide();
                        }

                        //mostrar las faltas del mes correspondiente  ABRIL
                        if($('select[id=numero_evaluacion]').val() == "8"){
                            $("#faltaAbr").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").show();
                            $('td:nth-child(22)').show();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();

                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").show();
                            $('td:nth-child(32)').show();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();
                        }else{
                            $("#faltaAbr").hide();
                        }

                        //mostrar las faltas del mes correspondiente  MAYO
                        if($('select[id=numero_evaluacion]').val() == "9"){
                            $("#faltaMay").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").show();
                            $('td:nth-child(23)').show();

                            $(".classtotalFaltasJun").hide();
                            $('td:nth-child(24)').hide();


                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").show();
                            $('td:nth-child(33)').show();

                            $(".classConductaJun").hide();
                            $('td:nth-child(34)').hide();
                        }else{
                            $("#faltaMay").hide();
                        }

                        //mostrar las faltas del mes correspondiente  JUNIO
                        if($('select[id=numero_evaluacion]').val() == "10"){
                            $("#faltaJun").show();

                            $(".classtotalFaltasSep").hide();
                            $('td:nth-child(15)').hide();

                            $(".classtotalFaltasOct").hide();
                            $('td:nth-child(16)').hide();

                            $(".classtotalFaltasNov").hide();
                            $('td:nth-child(17)').hide();

                            $(".classtotalFaltasDic").hide();
                            $('td:nth-child(18)').hide();

                            $(".classtotalFaltasEne").hide();
                            $('td:nth-child(19)').hide();

                            $(".classtotalFaltasFeb").hide();
                            $('td:nth-child(20)').hide();

                            $(".classtotalFaltasMar").hide();
                            $('td:nth-child(21)').hide();

                            $(".classtotalFaltasAbr").hide();
                            $('td:nth-child(22)').hide();

                            $(".classtotalFaltasMay").hide();
                            $('td:nth-child(23)').hide();

                            $(".classtotalFaltasJun").show();
                            $('td:nth-child(24)').show();

                            //CONDUCTA
                            $(".classConductaSep").hide();
                            $('td:nth-child(25)').hide();

                            $(".classConductaOct").hide();
                            $('td:nth-child(26)').hide();

                            $(".classConductaNov").hide();
                            $('td:nth-child(27)').hide();

                            $(".classConductaDic").hide();
                            $('td:nth-child(28)').hide();

                            $(".classConductaEne").hide();
                            $('td:nth-child(29)').hide();

                            $(".classConductaFeb").hide();
                            $('td:nth-child(30)').hide();

                            $(".classConductaMar").hide();
                            $('td:nth-child(31)').hide();

                            $(".classConductaAbr").hide();
                            $('td:nth-child(32)').hide();

                            $(".classConductaMay").hide();
                            $('td:nth-child(33)').hide();

                            $(".classConductaJun").show();
                            $('td:nth-child(34)').show();
                        }else{
                            $("#faltaJun").hide();
                        }*/

                        if(element.numero_evidencias == 1){


                            $(".classEvi2").hide();
                            $('td:nth-child(5)').hide();


                            $(".classEvi3").hide();
                            $('td:nth-child(6)').hide();

                            $(".classEvi4").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();

                            $(".classPromedioMes").hide();
                            //$('td:nth-child(14)').hide();

                    


                        }

                        if(element.numero_evidencias == 2){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").hide();
                            $('td:nth-child(6)').hide();

                            $(".classEvi4").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 3){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").hide();
                            $('td:nth-child(7)').hide();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 4){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").hide();
                            $('td:nth-child(8)').hide();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 5){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").hide();
                            $('td:nth-child(9)').hide();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 6){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").hide();
                            $('td:nth-child(10)').hide();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 7){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").hide();
                            $('td:nth-child(11)').hide();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 8){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(11)').show();

                            $(".classEvi9").hide();
                            $('td:nth-child(12)').hide();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 9){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(11)').show();

                            $(".classEvi9").show();
                            $('td:nth-child(12)').show();
                            $(".classEvi10").hide();
                            $('td:nth-child(13)').hide();
                        }

                        if(element.numero_evidencias == 10){

                            $(".classEvi2").show();
                            $('td:nth-child(5)').show();

                            $(".classEvi3").show();
                            $('td:nth-child(6)').show();

                            $(".classEvi4").show();
                            $('td:nth-child(7)').show();

                            $(".classEvi5").show();
                            $('td:nth-child(8)').show();

                            $(".classEvi6").show();
                            $('td:nth-child(9)').show();

                            $(".classEvi7").show();
                            $('td:nth-child(10)').show();

                            $(".classEvi8").show();
                            $('td:nth-child(11)').show();

                            $(".classEvi9").show();
                            $('td:nth-child(12)').show();
                            $(".classEvi10").show();
                            $('td:nth-child(13)').show();
                        }

                        //pintar los porcentajes de cada evidencia
                        if(element.porcentaje_evidencia1 != null){
                            $("#nombreEvidencia1").text(element.concepto_evidencia1);
                            $("#evi1").text(element.porcentaje_evidencia1);
                        }else{
                            $("#nombreEvidencia1").text("");
                            $("#evi1").text("");
                        }

                        if(element.porcentaje_evidencia2 != null){
                            $("#nombreEvidencia2").text(element.concepto_evidencia2);
                            $("#evi2").text(element.porcentaje_evidencia2);
                        }else{
                            $("#nombreEvidencia2").text("");
                            $("#evi2").text("");
                            $("#evi2").hide();
                        }

                        if(element.porcentaje_evidencia3 != null){
                            $("#nombreEvidencia3").text(element.concepto_evidencia3);
                            $("#evi3").text(element.porcentaje_evidencia3);
                        }else{
                            $("#nombreEvidencia3").text("");
                            $("#evi3").text("");
                        }

                        if(element.porcentaje_evidencia4 != null){
                            $("#nombreEvidencia4").text(element.concepto_evidencia4);
                            $("#evi4").text(element.porcentaje_evidencia4);
                        }else{
                            $("#nombreEvidencia4").text("");
                            $("#evi4").text("");
                        }
                        if(element.porcentaje_evidencia5 != null){
                            $("#nombreEvidencia5").text(element.concepto_evidencia5);
                            $("#evi5").text(element.porcentaje_evidencia5);
                        }else{
                            $("#nombreEvidencia5").text("");
                            $("#evi5").text("");
                        }

                        if(element.porcentaje_evidencia6 != null){
                            $("#nombreEvidencia6").text(element.concepto_evidencia6);
                            $("#evi6").text(element.porcentaje_evidencia6);
                        }else{
                            $("#nombreEvidencia6").text("");
                            $("#evi6").text("");
                        }

                        if(element.porcentaje_evidencia7 != null){
                            $("#nombreEvidencia7").text(element.concepto_evidencia7);
                            $("#evi7").text(element.porcentaje_evidencia7);
                        }else{
                            $("#nombreEvidencia7").text("");
                            $("#evi7").text("");
                        }

                        if(element.porcentaje_evidencia8 != null){
                            $("#nombreEvidencia8").text(element.concepto_evidencia8);
                            $("#evi8").text(element.porcentaje_evidencia8);
                        }else{
                            $("#nombreEvidencia8").text("");
                            $("#evi8").text("");
                        }

                        if(element.porcentaje_evidencia9 != null){
                            $("#nombreEvidencia9").text(element.concepto_evidencia9);
                            $("#evi9").text(element.porcentaje_evidencia9);
                        }else{
                            $("#nombreEvidencia9").text("");
                            $("#evi9").text("");
                        }

                        if(element.porcentaje_evidencia10 != null){
                            $("#nombreEvidencia10").text(element.concepto_evidencia10);
                            $("#evi10").text(element.porcentaje_evidencia10);
                        }else{
                            $("#nombreEvidencia10").text("");
                            $("#evi10").text("");
                        }




                    });
                    $('#numero_evaluacion').trigger('change'); // Notify only Select2 of changes
                }else{
                    $("#numero_evaluacion").append(`<option value="" selected disabled>EL MES NO SE ENCUENTRA SELECCIONADO</option>`);
                }
            });
        }

        obtenerNumEvaluacion($("#bachiller_cch_grupo_evidencia_id").val())
        $("#bachiller_cch_grupo_evidencia_id").change( event => {
            obtenerNumEvaluacion(event.target.value)
        });
     });

</script>


<script type="text/javascript">
    $(document).ready(function() {

        function obtenerAlumnos(alumnoId, grupoId) {
            var variableDeSep = [];
            var variableDeOct = [];
            var variableDeNov = [];
            var variableDeDic = [];
            var variableDeEne = [];
            var variableDeFeb = [];
            var variableDeMar = [];
            var variableDeAbr = [];
            var variableDeMay = [];
            var variableDeJun = [];

            var variableDeID = [];


            $.get(base_url+`/bachiller_calificacion_seq/getCalificacionesAlumnos/${alumnoId}/${grupoId}`, function(res,sta) {
                $("#tableBody").html("");

                if(res.length > 0){

                    //muestra el boton guardar
                    $(".btn-guardar").show();

                    let numero_evidencias = $("#numero_evidencias").val();
                    let mes = $("#mes").val();


                    // Ahora dibujamos la tabla
                    const $cuerpoTabla = document.querySelector("#tableBody");
                    // Recorrer todos los productos
                    res.forEach(element => {                        

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
                        $('.evidencia_' + element.bachiller_cch_inscrito_id).each(function(){
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


                        $('#promedioTotal' + element.bachiller_cch_inscrito_id).val(promedio);
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


                    variableDeSep.push(element.inscConductaSep);
                    variableDeOct.push(element.inscConductaOct)
                    variableDeNov.push(element.inscConductaNov)
                    variableDeDic.push(element.inscConductaDic)
                    variableDeEne.push(element.inscConductaEne)
                    variableDeFeb.push(element.inscConductaFeb)
                    variableDeMar.push(element.inscConductaMar)
                    variableDeAbr.push(element.inscConductaAbr)
                    variableDeMay.push(element.inscConductaMay)
                    variableDeJun.push(element.inscConductaJun)
                    variableDeID.push(element.bachiller_cch_inscrito_id)

                    

                    // Crear un <tr>
                    const $tr = document.createElement("tr");
                    // Creamos el <td> de nombre y lo adjuntamos a tr
                    let $id = document.createElement("td");
                    $id.innerHTML = `<input style='display:none;' name='id[]' type='hidden' value='${element.id}'>`; // el textContent del td es el nombre
                    $tr.appendChild($id); //1
                    // El td de precio
                    let $bachiller_cch_inscrito_id = document.createElement("td");
                    $bachiller_cch_inscrito_id.innerHTML =`<input style='display:none;' name='bachiller_cch_inscrito_id[]' type='hidden' value='${element.bachiller_cch_inscrito_id}'>`; 
                    $tr.appendChild($bachiller_cch_inscrito_id);//2
                    // El td del código
                    let $nombreAlumno = document.createElement("td");
                    $nombreAlumno.textContent = `${element.perApellido1} ${element.perApellido2} ${element.perNombre}`; 
                    $tr.appendChild($nombreAlumno);//3

                    let $evidencia1 = document.createElement("td");
                    if(element.calificacion_evidencia1 != null){
                        $evidencia1.innerHTML = `<input id='evidencia1_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia1}' lang="en" name='evidencia1[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia1.innerHTML = `<input id='evidencia1_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia1[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia1);//4

                    let $evidencia2 = document.createElement("td");
                    if(element.calificacion_evidencia2 != null){
                        $evidencia2.innerHTML = `<input id='evidencia2_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia2}' lang="en" name='evidencia2[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia2.innerHTML = `<input id='evidencia2_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia2[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`;

                    }
                    $tr.appendChild($evidencia2);//5

                    let $evidencia3 = document.createElement("td");
                    if(element.calificacion_evidencia3 != null){
                        $evidencia3.innerHTML = `<input id='evidencia3_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia3}' lang="en" name='evidencia3[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia3.innerHTML = `<input id='evidencia3_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia3[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia3);//6

                    let $evidencia4 = document.createElement("td");
                    if(element.calificacion_evidencia4 != null){
                        $evidencia4.innerHTML = `<input id='evidencia4_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia4}' lang="en" name='evidencia4[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia4.innerHTML = `<input id='evidencia4_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia4[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia4);//7

                    let $evidencia5 = document.createElement("td");
                    if(element.calificacion_evidencia5 != null){
                        $evidencia5.innerHTML = `<input id='evidencia5_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia5}' lang="en" name='evidencia5[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia5.innerHTML = `<input id='evidencia5_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia5[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia5);//8

                    let $evidencia6 = document.createElement("td");
                    if(element.calificacion_evidencia6 != null){
                        $evidencia6.innerHTML = `<input id='evidencia6_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia6}' lang="en" name='evidencia6[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia6.innerHTML = `<input id='evidencia6_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia6[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia6);//9

                    let $evidencia7 = document.createElement("td");
                    if(element.calificacion_evidencia7 != null){
                        $evidencia7.innerHTML = `<input id='evidencia7_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia7}' lang="en" name='evidencia7[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia7.innerHTML = `<input id='evidencia7_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia7[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia7);//10

                    let $evidencia8 = document.createElement("td");
                    if(element.calificacion_evidencia8 != null){
                        $evidencia8.innerHTML = `<input id='evidencia8_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia8}' lang="en" name='evidencia8[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia8.innerHTML = `<input id='evidencia8_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia8[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia8);//11

                    let $evidencia9 = document.createElement("td");
                    if(element.calificacion_evidencia9 != null){
                        $evidencia9.innerHTML = `<input id='evidencia9_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia9}' lang="en" name='evidencia9[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia9.innerHTML = `<input id='evidencia9_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia9[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia9);//12

                    let $evidencia10 = document.createElement("td");
                    if(element.calificacion_evidencia10 != null){
                        $evidencia10.innerHTML = `<input id='evidencia10_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='${element.calificacion_evidencia10}' lang="en" name='evidencia10[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }else{
                        $evidencia10.innerHTML = `<input id='evidencia10_${element.bachiller_cch_inscrito_id}' onKeyUp="if(this.value>10){this.value='0';}else if(this.value<0){this.value='0';}" value='' lang="en" name='evidencia10[]' step="0.1" type='number' min="0" max="10" class='calif evidencia_${element.bachiller_cch_inscrito_id}' data-inscritoid='${element.bachiller_cch_inscrito_id}'>`; 

                    }
                    $tr.appendChild($evidencia10);//13

                    let $faltasSep = document.createElement("td");
                    if(element.inscFaltasInjSep != null){
                        $faltasSep.innerHTML = `<input id='faltaSep' value='${element.inscFaltasInjSep}' name='faltaSep[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasSep.innerHTML = `<input id='faltaSep' value='' name='faltaSep[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasSep);//14

                    let $faltasOct = document.createElement("td");
                    if(element.inscFaltasInjOct != null){
                        $faltasOct.innerHTML = `<input id='faltaOct' value='${element.inscFaltasInjOct}' name='faltaOct[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasOct.innerHTML = `<input id='faltaOct' value='' name='faltaOct[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasOct);//15

                    let $faltasNov = document.createElement("td");
                    if(element.inscFaltasInjNov != null){
                        $faltasNov.innerHTML = `<input id='faltaNov' value='${element.inscFaltasInjNov}' name='faltaNov[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasNov.innerHTML = `<input id='faltaNov' value='' name='faltaNov[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasNov);//16

                    let $faltasDic = document.createElement("td");
                    if(element.inscFaltasInjDic != null){
                        $faltasDic.innerHTML = `<input id='faltaDic' value='${element.inscFaltasInjDic}' name='faltaDic[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasDic.innerHTML = `<input id='faltaDic' value='' name='faltaDic[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasDic);//17

                    let $faltasEne = document.createElement("td");
                    if(element.inscFaltasInjEne != null){
                        $faltasEne.innerHTML = `<input id='faltaEne' value='${element.inscFaltasInjEne}' name='faltaEne[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasEne.innerHTML = `<input id='faltaEne' value='${element.inscFaltasInjEne}' name='faltaEne[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasEne);//18

                    let $faltasFeb = document.createElement("td");
                    if(element.inscFaltasInjFeb != null){
                        $faltasFeb.innerHTML = `<input id='faltaFeb' value='${element.inscFaltasInjFeb}' name='faltaFeb[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasFeb.innerHTML = `<input id='faltaFeb' value='' name='faltaFeb[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasFeb);//19

                    let $faltasMar = document.createElement("td");
                    if(element.inscFaltasInjMar != null){
                        $faltasMar.innerHTML = `<input id='faltaMar' value='${element.inscFaltasInjMar}' name='faltaMar[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasMar.innerHTML = `<input id='faltaMar' value='' name='faltaMar[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasMar);//20

                    let $faltasAbr = document.createElement("td");
                    if(element.inscFaltasInjAbr != null){
                        $faltasAbr.innerHTML = `<input id='faltaAbr' value='${element.inscFaltasInjAbr}' name='faltaAbr[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasAbr.innerHTML = `<input id='faltaAbr' value='' name='faltaAbr[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasAbr);//21

                    let $faltasMay = document.createElement("td");
                    if(element.inscFaltasInjMay != null){
                        $faltasMay.innerHTML = `<input id='faltaMay' value='${element.inscFaltasInjMay}' name='faltaMay[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasMay.innerHTML = `<input id='faltaMay' value='' name='faltaMay[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasMay);//22

                    let $faltasJun = document.createElement("td");
                    if(element.inscFaltasInjJun != null){
                        $faltasJun.innerHTML = `<input id='faltaJun' value='${element.inscFaltasInjJun}' name='faltaJun[]' type='number' step='any' min='0' max="20">`; 

                    }else{
                        $faltasJun.innerHTML = `<input id='faltaJun' value='' name='faltaJun[]' type='number' step='any' min='0' max="20">`; 

                    }
                    $tr.appendChild($faltasJun);//23
                    
                    let $conductaSep = document.createElement("td");
                    $conductaSep.innerHTML = `<select style='margin-top: -18px;' id="conductaSep_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaSep[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaSep);//24


                    let $conductaOct = document.createElement("td");
                    $conductaOct.innerHTML = `<select style='margin-top: -18px;' id="conductaOct_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaOct[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaOct);//25

                    let $conductaNov = document.createElement("td");
                    $conductaNov.innerHTML = `<select style='margin-top: -18px;' id="conductaNov_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaNov[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaNov);//26

                    let $conductaDic = document.createElement("td");
                    $conductaDic.innerHTML = `<select style='margin-top: -18px;' id="conductaDic_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaDic[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaDic);//27

                    let $conductaEne = document.createElement("td");
                    $conductaEne.innerHTML = `<select style='margin-top: -18px;' id="conductaEne_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaEne[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaEne);//28


                    let $conductaFeb = document.createElement("td");
                    $conductaFeb.innerHTML = `<select style='margin-top: -18px;' id="conductaFeb_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaFeb[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaFeb);//29


                    let $conductaMar = document.createElement("td");
                    $conductaMar.innerHTML = `<select style='margin-top: -18px;' id="conductaMar_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaMar[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaMar);//30


                    let $conductaAbr = document.createElement("td");
                    $conductaAbr.innerHTML = `<select style='margin-top: -18px;' id="conductaAbr_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaAbr[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaAbr);//31

                    let $conductaMay = document.createElement("td");
                    $conductaMay.innerHTML = `<select style='margin-top: -18px;' id="conductaMay_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaMay[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaMay);//32

                    let $conductaJun = document.createElement("td");
                    $conductaJun.innerHTML = `<select style='margin-top: -18px;' id="conductaJun_${element.bachiller_cch_inscrito_id}" class="browser-default validate select2" name="conductaJun[]" style="width: 100%;">
                        <option value="B">BUENA</option>
                        <option value="M">MALA</option>
                        <option value="R">REGULAR</option>
                    </select>`; 
                    $tr.appendChild($conductaJun);//33

                    let $promediomes = document.createElement("td");
                    if(element.promedio_mes != null){
                        $promediomes.innerHTML = `<td><input onmouseover="this.value = parseFloat(this.value).toFixed(1)" value='${element.promedio_mes}' readonly='true' id='promedioTotal${element.bachiller_cch_inscrito_id}' name='promedioTotal[]' step="0.0" type='number' min="5" max="10"></td>`; 

                    }else{
                        $promediomes.innerHTML = `<input onmouseover="this.value = parseFloat(this.value).toFixed(1)" readonly='true' id='promedioTotal${element.bachiller_cch_inscrito_id}' name='promedioTotal[]' step="0.0" type='number' min="5" max="10">`; 

                    }
                    $tr.appendChild($promediomes);//34
                    
                    // Finalmente agregamos el <tr> al cuerpo de la tabla
                    $cuerpoTabla.appendChild($tr);
                    // Y el ciclo se repite hasta que se termina de recorrer todo el arreglo
                });
                    //creamos la tabla

                        //pintamos la tabla 
                        $("#Tabla").show();
                        $("#info").html("");

                        //Septiembre
                        for(var i=0; i < variableDeSep.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeSep[i] == "null" || variableDeSep[i] == null){
                                    $("#conductaSep_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaSep_"+variableDeID[i]).val(variableDeSep[i]);
                                }

                            }
                        }

                        //octubre
                        for(var i=0; i < variableDeOct.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeOct[i] == "null" || variableDeOct[i] == null){
                                    $("#conductaOct_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaOct_"+variableDeID[i]).val(variableDeOct[i]);
                                }

                            }
                        }

                        //noviembre
                        for(var i=0; i < variableDeNov.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeNov[i] == "null" || variableDeNov[i] == null){
                                    $("#conductaNov_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaNov_"+variableDeID[i]).val(variableDeNov[i]);
                                }
                            }
                        }

                        //diciembre
                        for(var i=0; i < variableDeDic.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeDic[i] == "null" || variableDeDic[i] == null){
                                    $("#conductaDic_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaDic_"+variableDeID[i]).val(variableDeDic[i]);
                                }

                            }
                        }

                        //enero
                        for(var i=0; i < variableDeEne.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeEne[i] == "null" || variableDeEne[i] == null){
                                    $("#conductaEne_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaEne_"+variableDeID[i]).val(variableDeEne[i]);
                                }

                            }
                        }

                        //febereo
                        for(var i=0; i < variableDeFeb.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeFeb[i] == "null" || variableDeFeb[i] == null){
                                    $("#conductaFeb_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaFeb_"+variableDeID[i]).val(variableDeFeb[i]);
                                }

                            }
                        }

                        //marzo
                        for(var i=0; i < variableDeMar.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeMar[i] == "null" || variableDeMar[i] == null){
                                    $("#conductaMar_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaMar_"+variableDeID[i]).val(variableDeMar[i]);
                                }

                            }
                        }

                        //abril
                        for(var i=0; i < variableDeAbr.length; i++){
                            for(var x=0; x < variableDeID.length; x++){

                                if(variableDeAbr[i] == "null" || variableDeAbr[i] == null){
                                    $("#conductaAbr_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaAbr_"+variableDeID[i]).val(variableDeAbr[i]);
                                }

                            }
                        }

                        //mayo
                        for(var i=0; i < variableDeMay.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeMay[i] == "null" || variableDeMay[i] == null){
                                    $("#conductaMay_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaMay_"+variableDeID[i]).val(variableDeMay[i]);
                                }

                            }
                        }

                         //junio
                        for(var i=0; i < variableDeJun.length; i++){
                            for(var x=0; x < variableDeID.length; x++){
                                if(variableDeMay[i] == "null" || variableDeMay[i] == null){
                                    $("#conductaJun_"+variableDeID[i]).val("B");
                                }else{
                                    $("#conductaJun_"+variableDeID[i]).val(variableDeJun[i]);
                                }

                            }
                        }


                        //document.getElementById('tableBody').innerHTML = myTable;
                        $('td:nth-child(2)').hide();
                        $('td:nth-child(1)').hide();
                        $('td:nth-child(34)').hide();


                        

                        //numero de evidencias mostrar 
                        if(numero_evidencias == 1){

                            $('td:nth-child(5)').hide();
                            $('td:nth-child(6)').hide();
                            $('td:nth-child(7)').hide();
                            $('td:nth-child(8)').hide();
                            $('td:nth-child(9)').hide();
                            $('td:nth-child(10)').hide();
                            $('td:nth-child(11)').hide();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
                            $(".classEvi2").hide();
                            $(".classEvi3").hide();
                            $(".classEvi4").hide();
                            $(".classEvi5").hide();
                            $(".classEvi6").hide();
                            $(".classEvi7").hide();
                            $(".classEvi8").hide();
                            $(".classEvi9").hide();
                            $(".classEvi10").hide();                           
                            
                            
                            $(".classPromedioMes").hide();

                        }

                        if(numero_evidencias == 2){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').hide();
                            $('td:nth-child(7)').hide();
                            $('td:nth-child(8)').hide();
                            $('td:nth-child(9)').hide();
                            $('td:nth-child(10)').hide();
                            $('td:nth-child(11)').hide();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(numero_evidencias == 3){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').hide();
                            $('td:nth-child(8)').hide();
                            $('td:nth-child(9)').hide();
                            $('td:nth-child(10)').hide();
                            $('td:nth-child(11)').hide();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(numero_evidencias == 4){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').show();
                            $('td:nth-child(8)').hide();
                            $('td:nth-child(9)').hide();
                            $('td:nth-child(10)').hide();
                            $('td:nth-child(11)').hide();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(numero_evidencias == 5){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').show();
                            $('td:nth-child(8)').show();
                            $('td:nth-child(9)').hide();
                            $('td:nth-child(10)').hide();
                            $('td:nth-child(11)').hide();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(numero_evidencias == 6){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').show();
                            $('td:nth-child(8)').show();
                            $('td:nth-child(9)').show();
                            $('td:nth-child(10)').hide();
                            $('td:nth-child(11)').hide();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(numero_evidencias == 7){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').show();
                            $('td:nth-child(8)').show();
                            $('td:nth-child(9)').show();
                            $('td:nth-child(10)').show();
                            $('td:nth-child(11)').hide();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }


                        if(numero_evidencias == 8){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').show();
                            $('td:nth-child(8)').show();
                            $('td:nth-child(9)').show();
                            $('td:nth-child(10)').show();
                            $('td:nth-child(11)').show();
                            $('td:nth-child(12)').hide();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(numero_evidencias == 9){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').show();
                            $('td:nth-child(8)').show();
                            $('td:nth-child(9)').show();
                            $('td:nth-child(10)').show();
                            $('td:nth-child(11)').show();
                            $('td:nth-child(12)').show();
                            $('td:nth-child(13)').hide();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(numero_evidencias == 10){

                            $('td:nth-child(5)').show();
                            $('td:nth-child(6)').show();
                            $('td:nth-child(7)').show();
                            $('td:nth-child(8)').show();
                            $('td:nth-child(9)').show();
                            $('td:nth-child(10)').show();
                            $('td:nth-child(11)').show();
                            $('td:nth-child(12)').show();
                            $('td:nth-child(13)').show();

                            
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
                            $('td:nth-child(34)').show();


                        }

                        if(mes == "SEPTIEMBRE"){
                            

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }

                          

                            $(".classConductaSep").show();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide()

                            


                            $('td:nth-child(24)').show();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();

                            
                        }

                        if(mes == "OCTUBRE"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }
                            
                            
                            $(".classConductaSep").hide();
                            $(".classConductaOct").show();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide();

                            

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').show();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();
                        }

                        if(mes == "NOVIEMBRE"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                 //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }


                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").show();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide();

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').show();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();
                        }

                        if(mes == "DICIEMBRE"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }

                         
                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").show();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide();

                            

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').show();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();
                        }

                        if(mes == "ENERO"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){

                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();
                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }
                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").show();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide();

                            

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').show();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();
                        }

                        if(mes == "FEBRERO"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }

                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").show();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide();


                            

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').show();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();
                        }

                        if(mes == "MARZO"){


                            if("{{$validar_si_esta_activo_cch}}" == "1"){

                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }

                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").show();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide();

                            

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').show();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();
                        }

                        if(mes == "ABRIL"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();

                            }
                            
                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").show();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").hide();

                            

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').show();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').hide();
                        }


                        if(mes == "MAYO"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }


                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").show();
                            $(".classConductaJun").hide();

                            

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').show();
                            $('td:nth-child(33)').hide();
                        }

                        if(mes == "JUNIO"){

                            if("{{$validar_si_esta_activo_cch}}" == "1"){
                                $(".classtotalFaltasSep").hide();
                                $(".classtotalFaltasOct").hide();
                                $(".classtotalFaltasNov").hide();
                                $(".classtotalFaltasDic").hide();
                                $(".classtotalFaltasEne").hide();
                                $(".classtotalFaltasFeb").hide();
                                $(".classtotalFaltasMar").hide();
                                $(".classtotalFaltasAbr").hide();
                                $(".classtotalFaltasMay").hide();
                                $(".classtotalFaltasJun").hide();

                                 //ocultamos input faltas 
                                $('td:nth-child(14)').hide();
                                $('td:nth-child(15)').hide();
                                $('td:nth-child(16)').hide();
                                $('td:nth-child(17)').hide();
                                $('td:nth-child(18)').hide();
                                $('td:nth-child(19)').hide();
                                $('td:nth-child(20)').hide();
                                $('td:nth-child(21)').hide();
                                $('td:nth-child(22)').hide();
                                $('td:nth-child(23)').hide();
                            }

                           
                            $(".classConductaSep").hide();
                            $(".classConductaOct").hide();
                            $(".classConductaNov").hide();
                            $(".classConductaDic").hide();
                            $(".classConductaEne").hide();
                            $(".classConductaFeb").hide();
                            $(".classConductaMar").hide();
                            $(".classConductaAbr").hide();
                            $(".classConductaMay").hide();
                            $(".classConductaJun").show();

                           

                            //ocultamos los input de conducta
                            $('td:nth-child(24)').hide();
                            $('td:nth-child(25)').hide();
                            $('td:nth-child(26)').hide();
                            $('td:nth-child(27)').hide();
                            $('td:nth-child(28)').hide();
                            $('td:nth-child(29)').hide();
                            $('td:nth-child(30)').hide();
                            $('td:nth-child(31)').hide();
                            $('td:nth-child(32)').hide();
                            $('td:nth-child(33)').show();
                        }


                        
                }else{
                  
                    $(".btn-guardar").hide();
                    $("#Tabla").hide();
                    $("#info").html("No hay calificaciones registradas en el mes seleccionado");

                }

                

                    

            });
        }


        obtenerAlumnos($("#bachiller_cch_grupo_evidencia_id").val(), $("#grupo_id2").val())
        $("#bachiller_cch_grupo_evidencia_id").change( event => {
            obtenerAlumnos(event.target.value,$("#grupo_id2").val())

        });
     });
</script>