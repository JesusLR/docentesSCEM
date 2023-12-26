<script type="text/javascript">

    $(document).ready(function() {

        $("#escuela_id").change( event => {
            $("#programa_id").empty();

            $("#plan_id").empty();
            $("#cgt_id").empty();
            $("#materia_id").empty();
            $("#programa_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);

        
            $.get(base_url+`/secundaria_programa/api/programas/${event.target.value}`,function(res,sta){
                //seleccionar el post preservado
                var programaSeleccionadoOld = $("#programa_id").data("programa-idold")
                $("#programa_id").empty()

                res.forEach(element => {
                    var selected = "";
                    if (element.id === programaSeleccionadoOld) {
                        console.log("entra")
                        console.log(element.id)
                        selected = "selected";
                    }

                    $("#programa_id").append(`<option value=${element.id} ${selected}>${element.progClave}-${element.progNombre}</option>`);
                });

                $('#programa_id').trigger('change'); // Notify only Select2 of changes
            });
        });

     });
</script>

<script>
$(document).on('click', '#agregarPrograma', function (e) {
    var programa_id = $("#programa_id").val();
    if(programa_id != "" && programa_id != null){
        if(recorrerProgramas(programa_id)){
            $.get(base_url+`/secundaria_programa/api/programa/${programa_id}`,function(res,sta){
            $("#seccion-programas").show();
            $('#tbl-programas> tbody:last-child').append(`<tr id="programa${res.id}">
                    <td>${res.escuela.escNombre}</td>
                    <td>${res.progClave}</td>
                    <td>${res.progNombre}</td>
                    <td><input name="programas[${res.id}]" type="hidden" value="${res.id}" readonly="true"/>
                    <a href="javascript:;" onclick="eliminarPrograma(${res.id})" class="button button--icon js-button js-ripple-effect" title="Eliminar">
                        <i class="material-icons">delete</i>
                    </a>
                    </td>
                </tr>`);
            });
        }else{
            swal({
                title: "Ups...",
                text: "El programa ya se encuentra agregado",
                type: "warning",
                confirmButtonText: "Ok",
                confirmButtonColor: '#3085d6',
                showCancelButton: false
            });
        }
    }else{
        swal({
            title: "Ups...",
            text: "Debes seleccionar al menos un programa",
            type: "warning",
            confirmButtonText: "Ok",
            confirmButtonColor: '#3085d6',
            showCancelButton: false
        });
    }
});

function recorrerProgramas(id){
    encontro = true;
    $('#tbl-programas tr').each(function() {
        if(this.id == 'programa'+id){
            encontro = false;
            return false;
        }
    });
    return encontro;
}

function eliminarPrograma(id) {
    $('#programa' + id).remove();
}
</script>