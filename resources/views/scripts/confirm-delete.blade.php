<script>
$(document).on('click', '.confirm-delete', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    swal({
            title: "¿Estás seguro?",
            text: "Deseas eliminar este registro",
            type: "warning",
            confirmButtonText: "Si",
            confirmButtonColor: '#3085d6',
            cancelButtonText: "No",
            showCancelButton: true
        },
        function() {
            $('#delete_'+id).submit();
        });
    });
</script>