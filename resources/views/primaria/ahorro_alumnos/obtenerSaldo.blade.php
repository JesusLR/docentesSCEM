<script type="text/javascript">
    $(document).ready(function() {
        $( "#curso_id" ).select(function() {
            console.log( "Handler for .select() called." );
          });
        // OBTENER SALDO
        $("#curso_id").change( event => {
           
            $.get(base_url+`/primaria_ahorro_escolar/mostrarSaldoEnCuenta/${event.target.value}`,function(res,sta){
                if(res != ""){
                    res.forEach(element => {
                        $("#sal_fin").removeClass('input-field');
                        $("#saldo_final").val(element.saldo_final);    
                        
                        /*var valor_inicial = $('#saldo_final').val();
                        $('.Can_Produc').keyup(function () {
                            var valor = parseInt(valor_inicial);
                            var valor_restar = 0;
                            $('.Can_Produc').each(function () {
                              if ($(this).val() > 0) {
                                valor_restar += parseInt($(this).val());
                              }
                            });
                                
                            $('#saldo_final').val(valor + valor_restar);
                                  
                        });*/

                    });
                }else{
                    $("#sal_fin").addClass('input-field');
                    $("#saldo_final").val(""); 


                    /*var valor_inicial = $('#saldo_final').val();
                    if(valor_inicial == ""){
                        valor_inicial = 0;
                    }else{
                        valor_inicial = $('#saldo_final').val();
                    }
                        $('.Can_Produc').keyup(function () {
                            var valor = parseInt(valor_inicial);
                            var valor_restar = 0;
                            $('.Can_Produc').each(function () {
                              if ($(this).val() > 0) {
                                valor_restar += parseInt($(this).val());
                              }
                            });
                                
                            $('#saldo_final').val(valor + valor_restar);
                                  
                        });*/
                }
                
            });
        });
    
       

       
    });
</script>