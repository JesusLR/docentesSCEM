<script>


    $("input[id='porcentaje_evidencia1']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia2']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia3']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia4']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia5']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia6']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia7']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });$("input[id='porcentaje_evidencia8']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia9']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentaje_evidencia10']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });
    $("input[id='porcentajeTotal']").blur(function() {
        this.value = parseFloat(this.value).toFixed(1);
    });

</script>
<script>

function cuentaTotalEvidencia(){
    let totalNu = $("#numero_evidencias").val().length;

    if(totalNu > 0){
        let numero = $("#numero_evidencias").val();

        if(numero == 1){
            $("#div1").show();
            $("#div2").hide();
            $("#div3").hide();
            $("#div4").hide();
            $("#div5").hide();
            $("#div6").hide();
            $("#div7").hide();
            $("#div8").hide();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();    
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', false);
            $("#concepto_evidencia3").prop('required', false);
            $("#concepto_evidencia4").prop('required', false);
            $("#concepto_evidencia5").prop('required', false);
            $("#concepto_evidencia6").prop('required', false)
            $("#concepto_evidencia7").prop('required', false);
            $("#concepto_evidencia8").prop('required', false);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado
            $("#concepto_evidencia2").val("");
            $("#concepto_evidencia3").val("");
            $("#concepto_evidencia4").val("");
            $("#concepto_evidencia5").val("");
            $("#concepto_evidencia6").val("")
            $("#concepto_evidencia7").val("");
            $("#concepto_evidencia8").val("");
            $("#concepto_evidencia9").val("");
            $("#concepto_evidencia10").val("");
            $("#porcentaje_evidencia2").val("");
            $("#porcentaje_evidencia3").val("");
            $("#porcentaje_evidencia4").val("");
            $("#porcentaje_evidencia5").val("");
            $("#porcentaje_evidencia6").val("");
            $("#porcentaje_evidencia7").val("");
            $("#porcentaje_evidencia8").val("");
            $("#porcentaje_evidencia9").val("");
            $("#porcentaje_evidencia10").val("");

        
        }

        if(numero == 2){
            $("#div1").show();
            $("#div2").show();
            $("#div3").hide();
            $("#div4").hide();
            $("#div5").hide();
            $("#div6").hide();
            $("#div7").hide();
            $("#div8").hide();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', false);
            $("#concepto_evidencia4").prop('required', false);
            $("#concepto_evidencia5").prop('required', false);
            $("#concepto_evidencia6").prop('required', false)
            $("#concepto_evidencia7").prop('required', false);
            $("#concepto_evidencia8").prop('required', false);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado            
            $("#concepto_evidencia3").val("");
            $("#concepto_evidencia4").val("");
            $("#concepto_evidencia5").val("");
            $("#concepto_evidencia6").val("")
            $("#concepto_evidencia7").val("");
            $("#concepto_evidencia8").val("");
            $("#concepto_evidencia9").val("");
            $("#concepto_evidencia10").val("");            
            $("#porcentaje_evidencia3").val("");
            $("#porcentaje_evidencia4").val("");
            $("#porcentaje_evidencia5").val("");
            $("#porcentaje_evidencia6").val("");
            $("#porcentaje_evidencia7").val("");
            $("#porcentaje_evidencia8").val("");
            $("#porcentaje_evidencia9").val("");
            $("#porcentaje_evidencia10").val("");
        }

        if(numero == 3){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").hide();
            $("#div5").hide();
            $("#div6").hide();
            $("#div7").hide();
            $("#div8").hide();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', false);
            $("#concepto_evidencia5").prop('required', false);
            $("#concepto_evidencia6").prop('required', false)
            $("#concepto_evidencia7").prop('required', false);
            $("#concepto_evidencia8").prop('required', false);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado       
            $("#concepto_evidencia4").val("");
            $("#concepto_evidencia5").val("");
            $("#concepto_evidencia6").val("")
            $("#concepto_evidencia7").val("");
            $("#concepto_evidencia8").val("");
            $("#concepto_evidencia9").val("");
            $("#concepto_evidencia10").val("");        
            $("#porcentaje_evidencia4").val("");
            $("#porcentaje_evidencia5").val("");
            $("#porcentaje_evidencia6").val("");
            $("#porcentaje_evidencia7").val("");
            $("#porcentaje_evidencia8").val("");
            $("#porcentaje_evidencia9").val("");
            $("#porcentaje_evidencia10").val("");
        }

        if(numero == 4){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").show();
            $("#div5").hide();
            $("#div6").hide();
            $("#div7").hide();
            $("#div8").hide();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', true);
            $("#concepto_evidencia5").prop('required', false);
            $("#concepto_evidencia6").prop('required', false)
            $("#concepto_evidencia7").prop('required', false);
            $("#concepto_evidencia8").prop('required', false);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado  
            $("#concepto_evidencia5").val("");
            $("#concepto_evidencia6").val("")
            $("#concepto_evidencia7").val("");
            $("#concepto_evidencia8").val("");
            $("#concepto_evidencia9").val("");
            $("#concepto_evidencia10").val("");           
            $("#porcentaje_evidencia5").val("");
            $("#porcentaje_evidencia6").val("");
            $("#porcentaje_evidencia7").val("");
            $("#porcentaje_evidencia8").val("");
            $("#porcentaje_evidencia9").val("");
            $("#porcentaje_evidencia10").val("");
        }

        if(numero == 5){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").show();
            $("#div5").show();
            $("#div6").hide();
            $("#div7").hide();
            $("#div8").hide();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', true);
            $("#concepto_evidencia5").prop('required', true);
            $("#concepto_evidencia6").prop('required', false)
            $("#concepto_evidencia7").prop('required', false);
            $("#concepto_evidencia8").prop('required', false);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado 
            $("#concepto_evidencia6").val("")
            $("#concepto_evidencia7").val("");
            $("#concepto_evidencia8").val("");
            $("#concepto_evidencia9").val("");
            $("#concepto_evidencia10").val("");           
            $("#porcentaje_evidencia6").val("");
            $("#porcentaje_evidencia7").val("");
            $("#porcentaje_evidencia8").val("");
            $("#porcentaje_evidencia9").val("");
            $("#porcentaje_evidencia10").val("");
        }

        if(numero == 6){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").show();
            $("#div5").show();
            $("#div6").show();
            $("#div7").hide();
            $("#div8").hide();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', true);
            $("#concepto_evidencia5").prop('required', true);
            $("#concepto_evidencia6").prop('required', true);
            $("#concepto_evidencia7").prop('required', false);
            $("#concepto_evidencia8").prop('required', false);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado 
            $("#concepto_evidencia7").val("");
            $("#concepto_evidencia8").val("");
            $("#concepto_evidencia9").val("");
            $("#concepto_evidencia10").val("");     
            $("#porcentaje_evidencia7").val("");
            $("#porcentaje_evidencia8").val("");
            $("#porcentaje_evidencia9").val("");
            $("#porcentaje_evidencia10").val("");
        }

        if(numero == 7){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").show();
            $("#div5").show();
            $("#div6").show();
            $("#div7").show();
            $("#div8").hide();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', true);
            $("#concepto_evidencia5").prop('required', true);
            $("#concepto_evidencia6").prop('required', true);
            $("#concepto_evidencia7").prop('required', true);
            $("#concepto_evidencia8").prop('required', false);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

             //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado 
             $("#concepto_evidencia8").val("");
             $("#concepto_evidencia9").val("");
             $("#concepto_evidencia10").val("");        
             $("#porcentaje_evidencia8").val("");
             $("#porcentaje_evidencia9").val("");
             $("#porcentaje_evidencia10").val("");
        }

        if(numero == 8){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").show();
            $("#div5").show();
            $("#div6").show();
            $("#div7").show();
            $("#div8").show();
            $("#div9").hide();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', true);
            $("#concepto_evidencia5").prop('required', true);
            $("#concepto_evidencia6").prop('required', true);
            $("#concepto_evidencia7").prop('required', true);
            $("#concepto_evidencia8").prop('required', true);
            $("#concepto_evidencia9").prop('required', false);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado
            $("#concepto_evidencia9").val("");
            $("#concepto_evidencia10").val("");     
            $("#porcentaje_evidencia9").val("");
            $("#porcentaje_evidencia10").val("");
        }

        if(numero == 9){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").show();
            $("#div5").show();
            $("#div6").show();
            $("#div7").show();
            $("#div8").show();
            $("#div9").show();
            $("#div10").hide();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', true);
            $("#concepto_evidencia5").prop('required', true);
            $("#concepto_evidencia6").prop('required', true);
            $("#concepto_evidencia7").prop('required', true);
            $("#concepto_evidencia8").prop('required', true);
            $("#concepto_evidencia9").prop('required', true);
            $("#concepto_evidencia10").prop('required', false);

            //limpia los campos para poder guardar la cantidad de evidencias segun lo seleccionado
            $("#concepto_evidencia10").val("");         
            $("#porcentaje_evidencia10").val("");

        }

        if(numero == 10){
            $("#div1").show();
            $("#div2").show();
            $("#div3").show();
            $("#div4").show();
            $("#div5").show();
            $("#div6").show();
            $("#div7").show();
            $("#div8").show();
            $("#div9").show();
            $("#div10").show();
            $("#divPorcentaje").show();
            $("#concepto_evidencia1").prop('required', true);
            $("#concepto_evidencia2").prop('required', true);
            $("#concepto_evidencia3").prop('required', true);
            $("#concepto_evidencia4").prop('required', true);
            $("#concepto_evidencia5").prop('required', true);
            $("#concepto_evidencia6").prop('required', true);
            $("#concepto_evidencia7").prop('required', true);
            $("#concepto_evidencia8").prop('required', true);
            $("#concepto_evidencia9").prop('required', true);
            $("#concepto_evidencia10").prop('required', true);

           
        }
    }else{
        $("#div1").hide();
        $("#div2").hide();
        $("#div3").hide();
        $("#div4").hide();
        $("#div5").hide();
        $("#div6").hide();
        $("#div7").hide();
        $("#div8").hide();
        $("#div9").hide();
        $("#div10").hide();
        $("#divPorcentaje").hide();
        $("#concepto_evidencia1").prop('required', false);
        $("#concepto_evidencia2").prop('required', false);
        $("#concepto_evidencia3").prop('required', false);
        $("#concepto_evidencia4").prop('required', false);
        $("#concepto_evidencia5").prop('required', false);
        $("#concepto_evidencia6").prop('required', false);
        $("#concepto_evidencia7").prop('required', false);
        $("#concepto_evidencia8").prop('required', false);
        $("#concepto_evidencia9").prop('required', false);
        $("#concepto_evidencia10").prop('required', false);
    }

    
}

var valor_inicial = $('#porcentajeTotal').val();

$( document ).ready(function() {
    $('.porcentaje').keyup(function () {
        var valor = parseInt(valor_inicial);
        var valor_sumar = 0;
        $('.porcentaje').each(function () {
          if ($(this).val() > 0) {
            valor_sumar += parseInt($(this).val());
          }
        });
            
        $('#porcentajeTotal').val(valor + valor_sumar);
              
    });
});

function cuentaLetras() {
    let numeroCaracteres1 = $("#concepto_evidencia1").val().length;

    if(numeroCaracteres1 > 0){
        $("#porcentaje_evidencia1").prop('required', true);
    }else{
        $("#porcentaje_evidencia1").prop('required', false);
        $("#porcentaje_evidencia1").val("");
    }

    let numeroCaracteres2 = $("#concepto_evidencia2").val().length;
    if(numeroCaracteres2 > 0){
        $("#porcentaje_evidencia2").prop('required', true);
    }else{
        $("#porcentaje_evidencia2").prop('required', false);
        $("#porcentaje_evidencia2").val("");
    }

    let numeroCaracteres3 = $("#concepto_evidencia3").val().length;
    if(numeroCaracteres3 > 0){
        $("#porcentaje_evidencia3").prop('required', true);
    }else{
        $("#porcentaje_evidencia3").prop('required', false);
        $("#porcentaje_evidencia3").val("");
    }

    let numeroCaracteres4 = $("#concepto_evidencia4").val().length;
    if(numeroCaracteres4 > 0){
        $("#porcentaje_evidencia4").prop('required', true);
    }else{
        $("#porcentaje_evidencia4").prop('required', false);
        $("#porcentaje_evidencia4").val("");
    }

    let numeroCaracteres5 = $("#concepto_evidencia5").val().length;
    if(numeroCaracteres5 > 0){
        $("#porcentaje_evidencia5").prop('required', true);
    }else{
        $("#porcentaje_evidencia5").prop('required', false);
        $("#porcentaje_evidencia5").val("");
    }

    let numeroCaracteres6 = $("#concepto_evidencia6").val().length;
    if(numeroCaracteres6 > 0){
        $("#porcentaje_evidencia6").prop('required', true);
    }else{
        $("#porcentaje_evidencia6").prop('required', false);
        $("#porcentaje_evidencia6").val("");
    }

    let numeroCaracteres7 = $("#concepto_evidencia7").val().length;
    if(numeroCaracteres7 > 0){
        $("#porcentaje_evidencia7").prop('required', true);
    }else{
        $("#porcentaje_evidencia7").prop('required', false);
        $("#porcentaje_evidencia7").val("");
    }

    let numeroCaracteres8 = $("#concepto_evidencia8").val().length;
    if(numeroCaracteres8 > 0){
        $("#porcentaje_evidencia8").prop('required', true);
    }else{
        $("#porcentaje_evidencia8").prop('required', false);
        $("#porcentaje_evidencia8").val("");
    }

    let numeroCaracteres9 = $("#concepto_evidencia9").val().length;
    if(numeroCaracteres9 > 0){
        $("#porcentaje_evidencia9").prop('required', true);
    }else{
        $("#porcentaje_evidencia9").prop('required', false);
        $("#porcentaje_evidencia9").val("");
    }

    let numeroCaracteres10 = $("#concepto_evidencia10").val().length;
    if(numeroCaracteres10 > 0){
        $("#porcentaje_evidencia10").prop('required', true);
    }else{
        $("#porcentaje_evidencia10").prop('required', false);
        $("#porcentaje_evidencia10").val("");
    }

    
}   
    

{{--  validar si es input esta check   --}}
$( '#aplicar').on( 'click', function() {
    if( $(this).is(':checked') ){
        $("#aplicar").val("TODOS")
    } else {
        $("#aplicar").val("SOLO UNO")
    }
});

function changeHandler(val)
  {
    if (Number(val.value) > 100)
    {
      val.value = 100
    }
  }
</script>