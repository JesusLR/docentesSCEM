<div id="escolares">
    <br>
    
    <div class="row" style="background-color:#ECECEC;">
        <p style="text-align: center;font-size:1.2em;">Datos escolares</p>
    </div>
    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('escuelaProcedencia', $escolar->escuelaProcedencia, array('id' => 'escuelaProcedencia', 'class' =>
                'validate','required','maxlength'=>'40')) !!}
                {!! Form::label('escuelaProcedencia', 'Nombre de la escuela donde cursó *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::number('aniosEstudios', $escolar->aniosEstudios, array('id' => 'aniosEstudios', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==3) return false;"',
                'required')) !!}
                {!! Form::label('aniosEstudios', 'Años estudiados en la escuela anterior *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('motivosCambio', $escolar->motivosCambio, array('id' => 'motivosCambio', 'class' =>
                'validate','required','maxlength'=>'200')) !!}
                {!! Form::label('motivosCambio', 'Motivos del cambio de escuela *', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('kinder', $escolar->kinder, array('id' => 'kinder', 'class' =>
                'validate','required','maxlength'=>'40')) !!}
                {!! Form::label('kinder', 'Kinder *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('observaciones', $escolar->observaciones, array('id' => 'observaciones', 'class' =>
                'validate','required','maxlength'=>'200')) !!}
                {!! Form::label('observaciones', 'Observaciones *', array('class' => '')); !!}
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col s12 m6 l12">
            <div class="input-field">
                {!! Form::text('primaria', $escolar->primaria, array('id' => 'primaria', 'class' =>
                'validate','required','maxlength'=>'80')) !!}
                {!! Form::label('primaria', 'Primaria *', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('promedioGrado1', $escolar->promedioGrado1, array('id' => 'promedioGrado1', 'class' =>
                'validate','maxlength'=>'4')) !!}
                {!! Form::label('promedioGrado1', 'Promedio en 1º', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('promedioGrado2', $escolar->promedioGrado2, array('id' => 'promedioGrado2', 'class' =>
                'validate','maxlength'=>'4')) !!}
                {!! Form::label('promedioGrado2', 'Promedio en 2º', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('promedioGrado3', $escolar->promedioGrado3, array('id' => 'promedioGrado3', 'class' =>
                'validate','maxlength'=>'4')) !!}
                {!! Form::label('promedioGrado3', 'Promedio en 3º', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('promedioGrado4', $escolar->promedioGrado4, array('id' => 'promedioGrado4', 'class' =>
                'validate','maxlength'=>'4')) !!}
                {!! Form::label('promedioGrado4', 'Promedio en 4º', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('promedioGrado5', $escolar->promedioGrado5, array('id' => 'promedioGrado5', 'class' =>
                'validate','maxlength'=>'4')) !!}
                {!! Form::label('promedioGrado5', 'Promedio en 5º', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('promedioGrado6', $escolar->promedioGrado6, array('id' => 'promedioGrado6', 'class' =>
                'validate','maxlength'=>'4')) !!}
                {!! Form::label('promedioGrado6', 'Promedio en 6º', array('class' => '')); !!}
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('gradoRepetido', $escolar->gradoRepetido, array('id' => 'gradoRepetido', 'class' =>
                'validate','maxlength'=>'40', 'required')) !!}
                {!! Form::label('gradoRepetido', 'Repetición de algún grado *', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('promedioRepetido', $escolar->promedioRepetido, array('id' => 'promedioRepetido', 'class' =>
                'validate','maxlength'=>'4')) !!}
                {!! Form::label('promedioRepetido', 'Promedio', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l6">
            {!! Form::label('apoyoPedagogico', '¿Ha recibido su hijo(a) apoyo pedagógico en algún grado escolar? *', array('class' => '')); !!}
            <select id="apoyoPedagogico" required class="browser-default" name="apoyoPedagogico"
                style="width: 100%;">
                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                <option value="SI" {{ $escolar->apoyoPedagogico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                <option value="NO" {{ $escolar->apoyoPedagogico == "NO" ? 'selected="selected"' : '' }}>NO</option>
            </select>
        </div>
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('observacionesApoyo', $escolar->observacionesApoyo, array('id' => 'observacionesApoyo', 'class' =>
                'validate','maxlength'=>'900')) !!}
                {!! Form::label('observacionesApoyo', 'Observaciones', array('class' => '')); !!}
            </div>
        </div>
    </div>
    <br>
    <p><strong>¿Ha recibido su hijo(a) algún tratamiento?</strong></p>
    <br>
    <div class="row">
        <div class="col s12 m6 l6">
            {!! Form::label('medico', 'Medico *', array('class' => '')); !!}
            <select id="medico" required class="browser-default" name="medico"
                style="width: 100%;">
                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                <option value="SI" {{ $escolar->medico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                <option value="NO" {{ $escolar->medico == "NO" ? 'selected="selected"' : '' }}>NO</option>
            </select>
        </div>
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('observacionesMedico', $escolar->observacionesMedico, array('id' => 'observacionesMedico', 'class' =>
                'validate','maxlength'=>'900')) !!}
                {!! Form::label('observacionesMedico', 'Observaciones', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l6">
            {!! Form::label('neurologico', 'Neurológico *', array('class' => '')); !!}
            <select id="neurologico" required class="browser-default" name="neurologico"
                style="width: 100%;">
                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                <option value="SI" {{ $escolar->neurologico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                <option value="NO" {{ $escolar->neurologico == "NO" ? 'selected="selected"' : '' }}>NO</option>
            </select>
        </div>
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('observacionesNerologico', $escolar->observacionesNerologico, array('id' => 'observacionesNerologico', 'class' =>
                'validate','maxlength'=>'900')) !!}
                {!! Form::label('observacionesNerologico', 'Observaciones', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l6">
            {!! Form::label('psicologico', 'Psicologico *', array('class' => '')); !!}
            <select id="psicologico" required class="browser-default" name="psicologico"
                style="width: 100%;">
                <option value="" selected disabled>SELECCIONE UN OPCIÓN</option>
                <option value="SI" {{ $escolar->psicologico == "SI" ? 'selected="selected"' : '' }}>SI</option>
                <option value="NO" {{ $escolar->psicologico == "NO" ? 'selected="selected"' : '' }}>NO</option>
            </select>
        </div>
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('observacionesPsicologico', $escolar->observacionesPsicologico, array('id' => 'observacionesPsicologico', 'class' =>
                'validate','maxlength'=>'900')) !!}
                {!! Form::label('observacionesPsicologico', 'Observaciones', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l12">
            <div class="input-field">
                {!! Form::text('motivoInscripcion', $escolar->motivoInscripcion, array('id' => 'motivoInscripcion', 'class' =>
                'validate','maxlength'=>'900')) !!}
                {!! Form::label('motivoInscripcion', 'Motivo por el que se solicita la inscripción en la Escuela Modelo', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <p><strong>Nombre de familiares o conocidos que estudien o trabajen en esta escuela</strong></p>
    <div class="row">
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('familiar1', $escolar->familiar1, array('id' => 'familiar1', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('familiar1', 'Familiar 1', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('familiar2', $escolar->familiar2, array('id' => 'familiar2', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('familiar2', 'Familiar 2', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::text('familiar3', $escolar->familiar3, array('id' => 'familiar3', 'class' =>
                'validate','maxlength'=>'100')) !!}
                {!! Form::label('familiar3', 'Familiar 3', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <p><strong>Nombre de familiares o conocidos a quien se le pueda pedir referencia</strong></p>

    <div class="row">
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('referencia1', $escolar->referencia1, array('id' => 'referencia1', 'class' =>
                'validate','maxlength'=>'100', 'required')) !!}
                {!! Form::label('referencia1', 'Referencia 1 *', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::number('celularReferencia1', $escolar->celularReferencia1, array('id' => 'celularReferencia1', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"',
                'required')) !!}
                {!! Form::label('celularReferencia1', 'Celular referencia 1 *', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('referencia2', $escolar->referencia2, array('id' => 'referencia2', 'class' =>
                'validate','maxlength'=>'100', 'required')) !!}
                {!! Form::label('referencia2', 'Referencia 2 *', array('class' => '')); !!}
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="input-field">
                {!! Form::number('celularReferencia2', $escolar->celularReferencia2, array('id' => 'celularReferencia2', 'class' =>
                'validate','min'=>'0','max'=>'9999999999','onKeyPress="if(this.value.length==10) return false;"',
                'required')) !!}
                {!! Form::label('celularReferencia2', 'Celular referencia 2 *', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l12">
            <div class="input-field">
                {!! Form::text('observacionesGenerales', $escolar->observacionesGenerales, array('id' => 'observacionesGenerales', 'class' =>
                'validate','maxlength'=>'900', 'required')) !!}
                {!! Form::label('observacionesGenerales', 'Observaciones generales', array('class' => '')); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('entrevisto', $escolar->entrevisto, array('id' => 'entrevisto', 'class' =>
                'validate','maxlength'=>'100', 'required')) !!}
                {!! Form::label('entrevisto', 'Entrevisto *', array('class' => '')); !!}
            </div>
        </div>

        <div class="col s12 m6 l6">
            <div class="input-field">
                {!! Form::text('ubicacion', $escolar->ubicacion, array('id' => 'ubicacion', 'class' =>
                'validate','maxlength'=>'100', 'required')) !!}
                {!! Form::label('ubicacion', 'Ubicacion *', array('class' => '')); !!}
            </div>
        </div>
    </div>

</div>