<script type="text/javascript">

    function calcularPromedio(incrito_id){
        var parciales = 0;
        var promedio  = 0;
        $('.parcial' + incrito_id).each(function(){
            if ($(this).val() != "") {
                parciales++;
                promedio = promedio + parseInt($(this).val());
            }
        });
        promedio = promedio / parciales;
        
        promedio = promedio + 0.5
    
        promedio = Math.trunc(promedio);
    
    
        console.log("promedio");
        console.log(promedio);
    
        $('#inscPromedioParciales' + incrito_id).val(promedio);
        $('#inscPromedioParciales2' + incrito_id).val(promedio);
    }
    
    function calcularPromedioFinal(incrito_id) {
        var matPorcentajeParcial      = $('#matPorcentajeParcial').val();
        var matPorcentajeOrdinario    = $('#matPorcentajeOrdinario').val();
        var inscPromedioParciales     = $('#inscPromedioParciales' + incrito_id).val();
        var inscCalificacionOrdinario = $('#inscCalificacionOrdinario' + incrito_id).val();
    
    
        var prom_parcial = (parseFloat(inscPromedioParciales) * (matPorcentajeParcial / 100)) + 0.5;
        prom_parcial = Math.trunc(prom_parcial);
    
    
    
    
        
        var prom_ord = (inscCalificacionOrdinario * (matPorcentajeOrdinario / 100)) + 0.5;
            prom_ord = Math.trunc(prom_ord);
    
    
    
        if (inscCalificacionOrdinario !== "") {
            var prom_total = (prom_parcial + prom_ord);
            if (prom_total > 100) {
                prom_total = 100
            }
    
    
            if (inscCalificacionOrdinario < 0) {
                prom_total = inscCalificacionOrdinario
            }
    
            $('#incsCalificacionFinal' + incrito_id).val(Math.round(prom_total));
        }
    }
    
    
    function calcularPromedioFinalApr(incrito_id) {
    
        var inscCalificacionOrdinario = $('#inscCalificacionOrdinario' + incrito_id).val();
    
        $('#incsCalificacionFinal' + incrito_id).val(inscCalificacionOrdinario);
    
    
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
                calcularPromedioFinal(inscritoId)
            }
        });
    
    
    
    
    });
    
    </script>