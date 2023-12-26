<script type="text/javascript">

   $(document).on('click', '#buscarAlumno', function (e) {
       var aluClave = $('#aluClave').val();
       var cuoAnio = $('#cuoAnio').val();
        $.get(base_url+`/api/curso/alumno/${aluClave}/${cuoAnio}`,function(res,sta){
            if(jQuery.isEmptyObject(res)){
                swal({
                    title: "Ups...",
                    text: "No se encontro el alumno",
                    type: "warning",
                    confirmButtonText: "Ok",
                    confirmButtonColor: '#3085d6',
                    showCancelButton: false
                });
            }else{
                $('#curso_id').val(res.id);
                $('#ubiNombre').val(res.cgt.periodo.departamento.ubicacion.ubiNombre);
                $('#perNumero').val(res.cgt.cgtGradoSemestre + ' SEMESTRE DE ' + res.cgt.plan.programa.progNombre);
                $('#aluNombre').val(res.alumno.persona.perNombre + ' ' + res.alumno.persona.perApellido1 + ' ' + res.alumno.persona.perApellido2);
                Materialize.updateTextFields();
            }
        });
    });

    $(document).on('click', '#buscarConcepto', function (e) {
       var cuoConcepto = $('#cuoConcepto').val();
        $.get(base_url+`/api/cuota/${cuoConcepto}`,function(res,sta){
            if(jQuery.isEmptyObject(res)){
                swal({
                    title: "Ups...",
                    text: "No se encontro el alumno",
                    type: "warning",
                    confirmButtonText: "Ok",
                    confirmButtonColor: '#3085d6',
                    showCancelButton: false
                });
            }else{
                $('#curso_id').val(res.id);
                $('#ubiNombre').val(res.cgt.periodo.departamento.ubicacion.ubiNombre);
                $('#perNumero').val(res.cgt.cgtGradoSemestre + ' SEMESTRE DE ' + res.cgt.plan.programa.progNombre);
                $('#aluNombre').val(res.alumno.persona.perNombre + ' ' + res.alumno.persona.perApellido1 + ' ' + res.alumno.persona.perApellido2);
                Materialize.updateTextFields();
            }
        });
    });

</script>
