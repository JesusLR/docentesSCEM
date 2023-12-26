<script>
    $(document).ready(function() {

        
        $(document).on("click", ".btn-guardar-programa", function(e) {

            var clave_pago = $("#aluClave").val();
            var alumno = $("#alumnoSeleccionado").val();
            var programaActual = $("#programaActual").val();
            var programaNuevo = $("#programaNuevo").val();
            var curso_id = $("#curso_id").val();
            var plan_id = $("#plan_id").val();
            var cgt_id = $("#cgt_id").val();
            var periodo_id = $("#periodo_id").val();
            var programa_id2 = $("#programa_id2").val();
            var plan_id2 = $("#plan_id2").val();
            var cgt_id2 = $("#cgt_id2").val();
            var usuario_at = $("#usuario_at").val();
            var departamento_id = $("#departamento_id").val();
            var programa_id = $("#programa_id").val();

            
           
            e.preventDefault();

            swal({
                title: "Cambio de programa",
                text: "Está seguro que desea transferir al alumno(a): "+alumno+" con clave de pago: "+ clave_pago +" del programa actual: "+programaActual+" al programa seleccionado: "+programaNuevo+" ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#0277bd',
                confirmButtonText: 'SI',
                cancelButtonText: "NO",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {

                    $.ajax({
                        url: "{{route('primaria.primaria_cambio_programa.store')}}",
                        method: "POST",
                        dataType: "json",
                        data: {
                            "_token": $("meta[name=csrf-token]").attr("content"),
                            curso_id: curso_id,
                            plan_id: plan_id,
                            cgt_id: cgt_id,
                            periodo_id: periodo_id,
                            plan_id2: plan_id2,
                            cgt_id2: cgt_id2,
                            usuario_at: usuario_at,
                            departamento_id: departamento_id,
                            programa_id: programa_id,
                            programa_id2: programa_id2
                           
                        },
                        success: function(data){
                            
                            if(data.res == true){
                                swal("Escuela Modelo", "Se realizo el cambio de programa con éxito", "success");
                                location.reload();

                            }

                            if(data.res == "GradoDiferente"){
                                swal("Escuela Modelo", "El grado donde desea cambiar al alumno es difente al grado actual", "info");
                            }

                            if(data.res == "perActualDiferente"){
                                swal("Escuela Modelo", "Solo se puede realizar el tramite en el periodo vigente actual", "info");
                            }

                            if(data.res == "programaIgual"){
                                swal("Escuela Modelo", "Solo se puede realizar el tramite a un programa diferente al actual", "info");
                            }
                 
                        },
                        error: function(){
                            swal("Escuela Modelo", "Error inesperado, intende nuevamente (verique si ha seleccionado todos los campos)", "error");
                        }
                      });

                
                      
                } else {
                    swal.close()
                }
            });
        });
    });
</script>

