@extends('layouts.dashboard')

@section('template_title')
Primaria agenda
@endsection

@section('breadcrumbs')
<a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
<a href="{{ route('primaria_calendario.index') }}" class="breadcrumb">Calendario</a>
@endsection

@section('content')

{!! HTML::style(asset('js/fullcalendar/lib2/main.css'), array('type' => 'text/css', 'rel' => 'stylesheet')) !!}
{!! HTML::script(asset('js/fullcalendar/lib2/main.js'), array('type' => 'text/javascript')) !!}


<script>
 document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('primaria_calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },

        views: {
            dayGridMonth: {
                buttonText: "Mes",
            },
            timeGridWeek: {
                buttonText: "Semana",
            },
            timeGridDay: {
                buttonText: "Día",
            },
            listMonth: {
                buttonText: "Lista",
            },
            timeGrid: {
                dayMaxEventRows: 4 // ajustar a 4 solo para timeGridWeek / timeGridDay
            },
        },
        navLinks: true, // cun clic en los nombres de día / semana para navegar por las vistas
        businessHours: true, // mostrar el horario comercial
        editable: true,
        selectable: true,
        initialView: 'timeGridWeek',


        eventClick: function(info) {
            $("#creador").html('Creado por: ' + info.event.extendedProps.perNombreCreador + ' ' + info.event.extendedProps.perApellido1Creador + ' ' + info.event.extendedProps.perApellido2Creador);


            // recupera fecha inicial
            let fechaI = moment(info.event.start).format('YYYY-MM-DD');
            let horaIHora = moment(info.event.start).format('H');
            let horaIMin = moment(info.event.start).format('mm');
            if(horaIHora < 10){
                horaIHora = '0' + horaIHora;
            }
            let horaI = horaIHora + ':' + horaIMin;



            /* -------------------------- recupera fecha final -------------------------- */
            let fechaF = moment(info.event.end).format('YYYY-MM-DD');
            let horaFHora = moment(info.event.end).format('H');
            let horaFMin = moment(info.event.start).format('mm');
            if(horaFHora < 10){
                horaFHora = '0' + horaFHora;
            }
            let horaF = horaFHora + ':' + horaFMin;
            $("#id_evento").val(info.event.id);
            $("#title").val(info.event.title);
            $("#start").val(fechaI);
            $("#hora-inicio").val(horaI)
            $("#end").val(fechaF);
            $("#hora-fin").val(horaF);
            $("#description").val(info.event.extendedProps.description);
            $("#lugarEvento").val(info.event.extendedProps.lugarEvento);
            $("#color").val(info.event.backgroundColor);
            $("#user_id").val(info.event.extendedProps.usuario_at);

            //validamos para llenar el combo
            if(info.event.extendedProps.empleado_id_dos != null){
                $("#primaria_empleado_id1").val(info.event.extendedProps.perApellido1Uno+ " " + info.event.extendedProps.perApellido2Uno + " " + info.event.extendedProps.perNombreUno);
            }

            if(info.event.extendedProps.empleado_id_dos != null){
                $("#primaria_empleado_id2").val(info.event.extendedProps.perApellido1Dos+ " " + info.event.extendedProps.perApellido2Dos + " " + info.event.extendedProps.perNombreDos);            }

            if(info.event.extendedProps.empleado_id_tres != null){
                $("#primaria_empleado_id3").val(info.event.extendedProps.perApellido1Tres+ " " + info.event.extendedProps.perApellido2Tres + " " + info.event.extendedProps.perNombreTres);
            }



            $("#classTitle").removeClass("input-field");
            $("#classDescription").removeClass("input-field");
            $('#btnAgregar').hide();

            /* ----------------- obtener los valores de id para comparar ---------------- */
            let empleado_id = info.event.extendedProps.empleado_id_creador;

            if ('{{ Auth::user()->empleado_id }}' == empleado_id) {
                $('#btnEditar').show();
                $('#btnDelete').show();
                $("#titulo").html('Editar evento');
            } else {
                $('#btnEditar').hide();
                $('#btnDelete').hide();
                $("#titulo").html('Detalle de evento');
            }

            //evitar cerrar el modal cuando se hace click fuera del cuadro
            $("#addEvento").modal({
                dismissible: false
            });
            $('#addEvento').modal('open');

        },

        /* ---------------- eventos que se muestran en el calendario ---------------- */
        events: "{{ url('/primaria_calendario/show') }}",



    });
    calendar.setOption('locale', 'Es');
    calendar.render();

});


</script>



<div class="row">
    <div class="col s2">
        {{--  //pinta el div de acuerdo al color de cada usuario          --}}
        @foreach ($colores_usuarios as $item)

        <div style="background-color: {{$item->preesColor}}" class="card-panel lighten-2">
            <strong style="color: white">{{ $item->perNombre }} {{ $item->perApellido1 }}
                {{ $item->perApellido2 }}</strong>
        </div>

        @endforeach
    </div>
    <div class="col s9">
        <div id='primaria_calendar'></div>
    </div>
    <div class="col s1"></div>
</div>

{{-- se incluye la vista del modal  --}}
@include('primaria.calendario.modaEvento')

<style>
    #calendar {
        max-width: 1100px;
        margin: 0 auto;
    }
</style>


{!! HTML::script(asset('js/moment.min.js'), array('type' => 'text/javascript')) !!}
{!! HTML::script(asset('js/sweetalert/sweetalert.all.js'), array('type' => 'text/javascript')) !!}

@endsection
