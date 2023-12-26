<div id="familiares">
    <br>
    <div class="row">
        <div class="col s12 m6 l12">
            <div class="input-field">
                {!! Form::text('tiempoResidencia', $familia->tiempoResidencia, array('id' => 'tiempoResidencia', 'class' =>
                'validate','required','maxlength'=>'20')) !!}
                {!! Form::label('tiempoResidencia', 'Si proviene de otra ciudad ¿Cuánto tiempo tiene de residir en Mérida?', array('class' => '')); !!}
            </div>
        </div>
    </div>
    
    <div class="row" style="background-color:#ECECEC;">
        <p style="text-align: center;font-size:1.2em;">Datos del padre</p>
    </div>
    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('nombresPadre', $familia->nombresPadre, array('id' => 'nombresPadre', 'class' =>
                'validate','required','maxlength'=>'100')) !!}
                {!! Form::label('nombresPadre', 'Nombre(s) *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('apellido1Padre', $familia->apellido1Padre, array('id' => 'apellido1Padre', 'class' =>
                'validate','required','maxlength'=>'40')) !!}
                {!! Form::label('apellido1Padre', 'Apellido paterno *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('apellido2Padre', $familia->apellido2Padre, array('id' => 'apellido2Padre', 'class' =>
                'validate','required','maxlength'=>'40')) !!}
                {!! Form::label('apellido2Padre', 'Apellido materno *', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::number('celularPadre', $familia->celularPadre, array('id' => 'celularPadre', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"',
                'required')) !!}
                {!! Form::label('celularPadre', 'Celular *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::number('edadPadre', $familia->edadPadre, array('id' => 'edadPadre', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==3) return false;"',
                'required')) !!}
                {!! Form::label('edadPadre', 'Edad *', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('ocupacioPadre', $familia->ocupacioPadre, array('id' => 'ocupacioPadre', 'class' =>
                'validate','maxlength'=>'40', 'required')) !!}
                {!! Form::label('ocupacioPadre', 'Ocuparacón *', array('class' => '')); !!}
            </div>
        </div>

    </div>

  
    <br>
    <div class="row" style="background-color:#ECECEC;">
        <p style="text-align: center;font-size:1.2em;">Datos de la madre</p>
    </div>
  
    <div class="row">
        {{--  nombres de la madre   --}}
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('nombresMadre', $familia->nombresMadre, array('id' => 'nombresMadre', 'class' =>
                'validate','required','maxlength'=>'100')) !!}
                {!! Form::label('nombresMadre', 'Nombre(s) *', array('class' => '')); !!}
            </div>
        </div>

        {{--  Apellido parterno madre   --}}
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('apellido1Madre', $familia->apellido1Madre, array('id' => 'apellido1Madre', 'class' =>
                'validate','required','maxlength'=>'40')) !!}
                {!! Form::label('apellido1Madre', 'Apellido paterno *', array('class' => '')); !!}
            </div>
        </div>

        {{--  apellido materno madre   --}}
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('apellido2Madre', $familia->apellido2Madre, array('id' => 'apellido2Madre', 'class' =>
                'validate','required','maxlength'=>'40')) !!}
                {!! Form::label('apellido2Madre', 'Apellido materno *', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::number('celularMadre', $familia->celularMadre, array('id' => 'celularMadre', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"',
                'required')) !!}
                {!! Form::label('celularMadre', 'Celular *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::number('edadMadre', $familia->edadMadre, array('id' => 'edadMadre', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==3) return false;"',
                'required')) !!}
                {!! Form::label('edadMadre', 'Edad *', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('ocupacionMadre', $familia->ocupacionMadre, array('id' => 'ocupacionMadre', 'class' =>
                'validate','maxlength'=>'40', 'required')) !!}
                {!! Form::label('ocupacionMadre', 'Ocuparacón *', array('class' => '')); !!}
            </div>
        </div>

    </div>

    <div class="row" style="background-color:#ECECEC;">
        <p style="text-align: center;font-size:1.2em;">Datos generales</p>
    </div>

    <div class="row">
        <div class="col s12 m6 l4">
            {!! Form::label('estadoCilvilPadres', 'Estado civil de los padres*', ['class' => '', ]) !!}
            <select id="estadoCilvilPadres" class="browser-default" name="estadoCilvilPadres" style="width: 100%;">
                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
                <option value="CASADOS" {{ $familia->estadoCilvilPadres == "CASADOS" ? 'selected="selected"' : '' }}>Casados</option>
                <option value="UNIÓN LIBRE" {{ $familia->estadoCilvilPadres == "UNIÓN LIBRE" ? 'selected="selected"' : '' }}>Unión libre</option>
                <option value="DIVORCIADOS" {{ $familia->estadoCilvilPadres == "DIVORCIADOS" ? 'selected="selected"' : '' }}>Divorciados</option>
                <option value="VIUDO/A" {{ $familia->estadoCilvilPadres == "VIUDO/A" ? 'selected="selected"' : '' }}>Viudo/a</option>
            </select>
        </div>
        <div class="col s12 m6 l8">
            <div class="input-field">
                {!! Form::text('observacionesPadres', $familia->observaciones, array('id' => 'observacionesPadres', 'class' =>
                'validate','maxlength'=>'255')) !!}
                {!! Form::label('observacionesPadres', 'Observaciones', array('class' => '')); !!}
            </div>
        </div>      
    </div>

    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('religion', $familia->religion, array('id' => 'religion', 'class' =>
                'validate','maxlength'=>'40')) !!}
                {!! Form::label('religion', 'Religion', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <p><strong>Breve descripción de su familia (integrantes, relacion, edad, ocupacion)</strong></p>

    <div class="row">
        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('integrante1', $familia->integrante1, array('id' => 'integrante1', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('integrante1', 'Integrante 1', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('relacionIntegrante1', $familia->relacionIntegrante1, array('id' => 'relacionIntegrante1', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('relacionIntegrante1', 'Relacion integrante 1', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::number('edadIntegrante1', $familia->edadIntegrante1, array('id' => 'edadIntegrante1', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==3) return false;"')) !!}
                {!! Form::label('edadIntegrante1', 'Edad integrante 1', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('ocupacionIntegrante1', $familia->ocupacionIntegrante1, array('id' => 'ocupacionIntegrante1', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('ocupacionIntegrante1', 'Ocupación integrante 1', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('integrante2', $familia->integrante2, array('id' => 'integrante2', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('integrante2', 'Integrante 2', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('relacionIntegrante2', $familia->relacionIntegrante2, array('id' => 'relacionIntegrante2', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('relacionIntegrante2', 'Relacion integrante 2', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::number('edadIntegrante2', $familia->edadIntegrante2, array('id' => 'edadIntegrante2', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==3) return false;"')) !!}
                {!! Form::label('edadIntegrante2', 'Edad integrante 2', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('ocupacionIntegrante2', $familia->ocupacionIntegrante2, array('id' => 'ocupacionIntegrante2', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('ocupacionIntegrante2', 'Ocupación integrante 2', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('integrante3', $familia->integrante3, array('id' => 'integrante3', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('integrante3', 'Integrante 2', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('relacionIntegrante3', $familia->relacionIntegrante3, array('id' => 'relacionIntegrante3', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('relacionIntegrante3', 'Relacion integrante 3', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::number('edadIntegrante3', $familia->edadIntegrante3, array('id' => 'edadIntegrante3', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==3) return false;"')) !!}
                {!! Form::label('edadIntegrante3', 'Edad integrante 3', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('ocupacionIntegrante4', $familia->ocupacionIntegrante4, array('id' => 'ocupacionIntegrante4', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('ocupacionIntegrante4', 'Ocupación integrante 3', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('integrante4', $familia->integrante4, array('id' => 'integrante4', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('integrante4', 'Integrante 4', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('relacionIntegrante4', $familia->relacionIntegrante4, array('id' => 'relacionIntegrante4', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('relacionIntegrante4', 'Relacion integrante 4', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::number('edadIntegrante4', $familia->edadIntegrante4, array('id' => 'edadIntegrante4', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==3) return false;"')) !!}
                {!! Form::label('edadIntegrante4', 'Edad integrante 4', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="input-field">
                {!! Form::text('ocupacionIntegrante3', $familia->ocupacionIntegrante3, array('id' => 'ocupacionIntegrante3', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('ocupacionIntegrante3', 'Ocupación integrante 4', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('apoyoTareas', $familia->apoyoTareas, array('id' => 'apoyoTareas', 'class' =>
                'validate','maxlength'=>'40', 'required')) !!}
                {!! Form::label('apoyoTareas', '¿Quién apoya a su hijo(a) en las tareas en casa? *', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('deporteActividad', $familia->deporteActividad, array('id' => 'deporteActividad', 'class' =>
                'validate','maxlength'=>'40', 'required')) !!}
                {!! Form::label('deporteActividad', 'Deporte (s) o actividad cultural que practica *', array('class' => '')); !!}
            </div>
        </div>

     
    </div>
</div> 

