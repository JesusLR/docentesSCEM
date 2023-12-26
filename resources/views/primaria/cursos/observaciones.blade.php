@extends('layouts.dashboard')

@section('template_title')
    Observaciones
@endsection

@section('breadcrumbs')
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Inicio</a>
    <a href="{{url('primaria_grupo')}}" class="breadcrumb">Lista de Preinscripción</a>
    <a href="{{url('primaria_curso/observaciones/'.$curso->id)}}" class="breadcrumb">Observaciones</a>
@endsection

@section('content')


<div class="row">
    <div class="col s12 ">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_curso.storeObservacionesCurso', 'method' => 'POST','enctype' => 'multipart/form-data', "class" => "form-guardar-primaria"]) !!}
        {!! Form::hidden('curso_id', $curso->id) !!}

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="card ">
            <div class="card-content ">
                <span class="card-title">OBSERVACIONES</span>
                <p style="font-weight: bold; margin-bottom:5px;">({{$curso->id}}) {{ $curso->alumno->persona->perNombre.' '.$curso->alumno->persona->perApellido1.' '.$curso->alumno->persona->perApellido2}}</p>
                <p style="margin-bottom:5px;">({{$curso->cgt->plan->programa->progClave}}) {{$curso->cgt->plan->programa->progNombre}}</p>
                <p style="margin-bottom:5px;">Período {{$curso->cgt->periodo->perNumero.'-'.$curso->cgt->periodo->perAnio}}</p>
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
                        <div class="col s12 m6 l8">
                            <div class="input-field">
                                {!! Form::textarea('curPagoObservaciones', !is_null($cursoObservaciones) ? $cursoObservaciones->curPagoObservaciones: null,  ["style" => "height:80px;"]) !!}
                                {!! Form::label('curPagoObservaciones', 'Ingrese las observaciones ó condiciones de pago del alumno para este curso (año/periodo)', array('class' => '')); !!}
                            </div>
                        </div>

                        <div class="col s12 m6 l8">
                            <div class="file-field input-field">
                                <a {{!$cursoObservaciones ? "disabled": ""}}
                                   class="btn" href="{{url('primaria_curso/curso_archivo_observaciones/'. $curso->id)}}" target="blank">
                                    <i class=" material-icons left">picture_as_pdf</i> Descargar archivo guardado (carpeta de descargas del navegador web)
                                </a>
                            </div>
                        </div>

                        <div class="col s12 m6 l8">
                            <div class="file-field input-field">
                                <div class="btn">
                                <span>ADJUNTAR Ó ACTUALIZAR UN NUEVO ARCHIVO .PDF</span>
                                <input value="" type="file" name="image">
                                </div>
                                <div class="file-path-wrapper">
                                <input class="file-path validate"  type="text">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">
                                <i class=" material-icons left">save</i> Guardar Cambios
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
      {!! Form::close() !!}
    </div>
</div>
@endsection

@section('footer_scripts')

<script type="text/javascript">
    $(document).ready(function() {

        $(".btn-success").on("click", function (e) {
        e.preventDefault()
		    swal({
				title: "Guardar cambios",
				text: "¿Esta seguro que desea guardar los cambios?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Si',
				cancelButtonText: "No",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
                    $(".form-guardar-primaria").submit()
                } else {
                    swal.close()
				}
			});



      });
    })
</script>
@endsection
