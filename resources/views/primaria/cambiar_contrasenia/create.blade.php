@extends('layouts.dashboard')

@section('template_title')
  Primaria acceso de docente
@endsection

@section('head')

@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_cambiar_contrasenia')}}" class="breadcrumb">Lista Docentes</a>
    <a href="{{url('primaria_cambiar_contrasenia/create')}}" class="breadcrumb">Crear Contraseña</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
        {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria.primaria_cambiar_contrasenia.store', 'method' => 'POST']) !!}
        <div class="card ">
          <div class="card-content ">
            <span class="card-title">AGREGAR DOCENTE CLAVE</span>

            {{-- NAVIGATION BAR--}}
            <nav class="nav-extended">
              <div class="nav-content">
                <ul class="tabs tabs-transparent">
                  <li class="tab"><a class="active" href="#general">General</a></li>
                </ul>
              </div>
            </nav>

            {{-- GENERAL BAR--}}
            <div id="general">

                <div class="row">
                  <div class="col s12 m6 l4">
                    <label for="empleado_id">Empleado *</label>
                    <select id="empleado_id" class="browser-default validate select2" required name="empleado_id" style="width: 100%;">
                      <option value="" disabled>SELECCIONE UNA OPCIÓN</option>
                      @foreach ($empleados as $empleado)
                          <option value="{{$empleado->empleado_id}} {{ old('empleado_id') == $empleado->empleado_id ? 'selected' : '' }}">{{$empleado->empNombre}} {{$empleado->empApellido1}} {{$empleado->empApellido2}}</option>
                      @endforeach
                    </select>
                  </div>


                  <div class="col s12 m6 l4">
                    <div class="input-field" id="input-field">
                      {!! Form::label('empCorreo1', 'Correo', array('class' => '')); !!}
                      {!! Form::text('empCorreo1', null, array('id' => 'empCorreo1', 'class' => 'validate','maxlength'=>'30'))!!}
                    </div>
                  </div>
                </div>

                <div class="row">
                    <div class="col s12 m6 l4">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" class="validate noUpperCase">
                    </div>
                    <div class="col s12 m6 l4">
                        <label for="confirmPassword">Confirmar contraseña</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" class="validate noUpperCase">
                    </div>
                </div>

            </div>
          </div>
          <div class="card-action">
            {!! Form::button('<i class="material-icons left">save</i> Guardar', ['class' => 'btn-large waves-effect  darken-3','type' => 'submit']) !!}
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>


@endsection

@section('footer_scripts')

<script type="text/javascript">


  $(document).ready(function() {

      function obtenerCorreoEmpleado(empleado_id) {


          $.get(base_url+`/primaria_cambiar_contrasenia/getEmpleadoCorreo/${empleado_id}`, function(res,sta) {



              res.forEach(element => {
                console.log(element.empCorreo1)

                if(element.empCorreo1 == null){
                  $("#empCorreo1").val("");
                  $("#input-field").addClass('input-field');

                }else{
                  $("#empCorreo1").val(element.empCorreo1);
                  $("#input-field").removeClass('input-field');
                }
            });
          });
      }

      obtenerCorreoEmpleado($("#empleado_id").val())
      $("#empleado_id").change( event => {
          obtenerCorreoEmpleado(event.target.value)
      });
   });
</script>
@endsection
