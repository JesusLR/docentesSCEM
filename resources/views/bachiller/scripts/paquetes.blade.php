<script>
    $(document).ready(function() {
        // OBTENER PAQUETES POR CURSO SELECCIONADO
        $("#curso_id").change( event => {
            $("#paquete_id").empty();
            $("#paquete_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $.get(base_url+`/api/bachiller_paquete/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    console.log(element)
                    $("#paquete_id").append(`<option value=${element.id}>${element.consecutivo}</option>`);
                });
            });
        });

        //OBTENER GRUPO POR PAQUETE SELECCIONADO
        $("#paquete_id").change( event => {
            $('#tbl-paquetes> tbody').empty();
            $.get(base_url+`/api/bachiller_paquete/detalle/${event.target.value}`,function(res,sta){
                res.forEach(element => {
                    $('#tbl-paquetes> tbody:last-child').append(`<tr>
                    <td>${element.bachiller_grupo_yucatan.bachiller_materia.matClave}-${element.bachiller_grupo_yucatan.bachiller_materia.matNombre}</td>
                    <td>${element.bachiller_grupo_yucatan.bachiller_empleado.id}-${element.bachiller_grupo_yucatan.bachiller_empleado.empNombre} ${element.bachiller_grupo_yucatan.bachiller_empleado.empApellido1} ${element.bachiller_grupo_yucatan.bachiller_empleado.empApellido2}</td>
                    <td>${element.bachiller_grupo_yucatan.cgt.cgtGradoSemestre}-${element.bachiller_grupo_yucatan.cgt.cgtGrupo}-${element.bachiller_grupo_yucatan.cgt.cgtTurno}</td>
                    </tr>`);
                });
            });
        });

    });

$(document).on('click', '#agregarGrupo', function (e) {
    var grupo_id = $("#grupo_id").val();
    if(grupo_id != "" && grupo_id != null){
        if(recorrerGrupos(grupo_id)){
            $.get(base_url+`/bachiller_grupo_merida/getGrupo/${grupo_id}`,function(res,sta){
            $('#tbl-paquetes> tbody:last-child').append(`<tr id="grupo${res.id}">
                    <td>${res.bachiller_materia.matClave}-${res.bachiller_materia.matNombre}</td>
                    <td>${res.bachiller_empleado.id}-${res.bachiller_empleado.empNombre} ${res.bachiller_empleado.empApellido1} ${res.bachiller_empleado.empApellido2}</td>
                    <td>${res.gpoGrado}-${res.gpoClave}-${res.gpoTurno}</td>
                    <td><input name="grupos[${res.id}]" type="hidden" value="${res.id}" readonly="true"/>
                    <a href="javascript:;" onclick="eliminarGrupo(${res.id})" class="button button--icon js-button js-ripple-effect" title="Eliminar grupo">
                        <i class="material-icons">delete</i>
                    </a>
                    </td>
                </tr>`);
            });
        }else{
            swal({
                title: "Ups...",
                text: "Este grupo ya se encuentra agregado",
                type: "warning",
                confirmButtonText: "Ok",
                confirmButtonColor: '#3085d6',
                showCancelButton: false
            });
        }
    }else{
        swal({
            title: "Ups...",
            text: "Debes seleccionar al menos un grupo",
            type: "warning",
            confirmButtonText: "Ok",
            confirmButtonColor: '#3085d6',
            showCancelButton: false
        });
    }
});

function recorrerGrupos(id){
    encontro = true;
    $('#tbl-paquetes tr').each(function() {
        if(this.id == 'grupo'+id){
            encontro = false;
            return false;
        }
    });
    return encontro;
}

function eliminarGrupo(id){
    $('#grupo'+id).remove();
}

</script>