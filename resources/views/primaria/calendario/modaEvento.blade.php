<div id="addEvento" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 id="titulo"></h4>
        <h6 id="creador"></h6>
        <input type="text" style="display: none" id="id_evento" name="id_evento">   
        <div class="row">
            <div class="col s12 m6 l12">
                <div id="classTitle" class="input-field">
                    {!! Form::label('title', 'Título del evento*', array('class' => '')); !!}
                    {!! Form::text('title', NULL, array('id' => 'title', 'class' =>
                    '','required','maxlength'=>'255', 'readonly')) !!}
                </div>
            </div>
            <div class="col s12 m6 l12">
                <div id="classDescription" class="input-field">
                    {!! Form::label('description', 'Descripción del evento*', array('class' => '')); !!}
                    {!! Form::textarea('description', NULL, array('id' => 'description', 'class' =>
                    'materialize-textarea ', '','required', 'rows'=>'5', 'maxlength'=>'15000', 'readonly')) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6 l12">
                {!! Form::label('lugarEvento', 'Lugar/Aula del evento *', array('class' => '')); !!}
                {!! Form::text('lugarEvento', NULL, array('id' => 'lugarEvento', 'class' => '','required','maxlength'=>'255', 'readonly')) !!}
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6 l3">
                {!! Form::label('start', 'Fecha de inicio *', array('class' => '')); !!}
                {!! Form::date('start', NULL, array('id' => 'start', 'class' => ' ','required', 'readonly')) !!}
            </div>
            <div class="col s12 m6 l3">
                {!! Form::label('hora-inicio', 'Hora de inicio *', array('class' => '')); !!}
                {!! Form::time('hora-inicio', NULL, array('id' => 'hora-inicio', 'class' => ' ','required')) !!}
            </div>
            <div class="col s12 m6 l3">
                {!! Form::label('end', 'Fecha de termino *', array('class' => '')); !!}
                {!! Form::date('end', NULL, array('id' => 'end', 'class' => '','required', 'readonly')) !!}
            </div>
            <div class="col s12 m6 l3">
                {!! Form::label('hora-fin', 'Hora final *', array('class' => '')); !!}
                {!! Form::time('hora-fin', NULL, array('id' => 'hora-fin', 'class' => ' ','required', 'readonly')) !!}
            </div>
        </div>

        <div class="row">
            <div class="col s12 l4">
                <h6>Docentes que asistiran</h6>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 l4">                
                {!! Form::label('primaria_empleado_id1', 'Docente 1 *', array('class' => '')); !!}
                {!! Form::text('primaria_empleado_id1', NULL, array('id' => 'primaria_empleado_id1', 'class' => ' ','required', 'readonly')) !!}
            </div>

            <div class="col s12 m6 l4">
                {!! Form::label('primaria_empleado_id2', 'Docente 2', array('class' => '')); !!}
                {!! Form::text('primaria_empleado_id2', NULL, array('id' => 'primaria_empleado_id2', 'class' => ' ','required', 'readonly')) !!}
            </div>

            <div class="col s12 m6 l4">
                {!! Form::label('primaria_empleado_id3', 'Docente 3', array('class' => '')); !!}
                {!! Form::text('primaria_empleado_id2', NULL, array('id' => 'primaria_empleado_id3', 'class' => ' ','required', 'readonly')) !!}
            </div>
        </div>
    </div>
    <div class="modal-footer" style="height: 70px">
        <button id="btnCancelar" class="modal-action modal-close waves-effect waves-red btn-flat">Cancelar</button>
        {!! Form::button('<i class="material-icons left">save</i> Guardar', ['id'=>'btnAgregar','class' => 'btn-large
        waves-effect darken-3']) !!}
        {!! Form::button('<i class="material-icons left">save</i> Guardar', ['id'=>'btnEditar','class' => 'btn-large
        waves-effect darken-3']) !!}
        {!! Form::button('<i class="material-icons left">delete</i> Cerrar', ['id'=>'btnDelete','class' => 'btn-large
        waves-effect darken-3']) !!}

    </div>
    <br>
</div>

<script>
    $(document).ready(function(){
        $('.modal').modal();
    });
</script>