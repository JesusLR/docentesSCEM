<script>
    $(document).ready(function() {

        
        $(document).on("click", ".btn-guardar-grupo-cgt", function(e) {

            var cgtGrupo = $("#cgtGrupo").val();
            var cgtTurno = $("#cgtTurno").val()
            
            //var primaria_materia =  $("input[name='primaria_materia[]']").map(function(){return $(this).val();}).get();

            var a = document.querySelectorAll("input.micheckbox");
            //Ahora vamos hacer uso del Prototype de JS para digamos recorrer todo lo que se ha generado desde la variable a y lo devolvemos a la variable ids_ 
            var primaria_materia =  $("input[name='primaria_materia[]']:checked").map(function () {
                return this.value;
               }).get();

            var periodo_id = $("#periodo_id").val();
            var plan_id = $("#plan_id").val();
            var cgt_id = $("#cgt_id").val();
            var matSemestre = $("#matSemestre").val();



            e.preventDefault();

            

            swal({
                title: "Asignar materias",
                text: "¿Desea generar los grupos de las materias seleccionadas e inscribir a los alumnos cargados al CGT ?",
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
                        url: "{{route('primaria.primaria_cgt_materias.store')}}",
                        method: "POST",
                        dataType: "json",
                        data: {
                            "_token": $("meta[name=csrf-token]").attr("content"),
                            cgtGrupo: cgtGrupo,
                            cgtTurno: cgtTurno,
                            periodo_id: periodo_id,
                            plan_id: plan_id,
                            cgt_id: cgt_id,
                            matSemestre: matSemestre,
                            primaria_materia: primaria_materia
                        },
                        success: function(data){

                            if(data.res == 'error'){
                                swal("Escuela Modelo", "No se ha seleccionado al menos un grupo", "info");
                            } else if(data.res == false){

                                swal("Escuela Modelo", "Los grupos que intenta crear ya existen", "error");

                            }else{
                                
                                swal("Escuela Modelo", "Los grupos se crearon con éxito", "success");
                                //location.reload();
                                console.log(data.grupo)
                            }
                 
                        }
                      });
                      
                    swal.close()
                } else {
                    swal.close()
                }
            });
        });
    });
</script>