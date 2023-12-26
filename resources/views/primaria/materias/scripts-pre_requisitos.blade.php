{!! HTML::script(asset('/vendors/data-tables/js/jquery.dataTables.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('/js/scripts/data-tables.js'), array('type' => 'text/javascript')) !!}

<script>

$(document).ready(function() {
    var materia_id = $('#materia_id').val();
    if(materia_id != "" && materia_id != null){
        $('#tbl-prerequisitos').dataTable({
            "language":{"url":base_url+"/api/lang/javascript/datatables"},
            "serverSide": true,
            "dom": '"top"i',
            "pageLength": 5,
            "ajax": {
                "type" : "GET",
                'url': base_url+"/primaria_materia/materia/prerequisitos/"+materia_id,
                beforeSend: function () {
                    $('.preloader').fadeIn(200,function(){$(this).append('<div id="preloader"></div>');;});
                },
                complete: function () {
                    $('.preloader').fadeOut(200,function(){$('#preloader').remove();});
                },
            },
            "columns":[
                {data: "matClave"},
                {data: "matNombre"},
                {data: "action"}
            ]
        });
    }
});


</script>