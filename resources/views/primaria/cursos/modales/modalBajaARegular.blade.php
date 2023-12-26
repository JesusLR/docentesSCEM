{{-- MODAL BAJA CURSO --}}
<div id="modalBajaARegularCurso" class="modal">
    <div class="modal-content">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_curso.altaCurso', 'method' => 'POST', 'class' => 'form-alta-alumno']) !!}
        <input type="hidden" value="" class="modalCursoId" name="curso_id">
        <div class="row">
          <div class="col s12 m12 l12">
            <h4>Cambiar estado</h4>
            <p style="font-weight: 700; margin-bottom: 0px;">(<span class="modalAlumnoClave"></span>) <span class="modalAlumnoNombre"></span></p>
            <p style="font-weight: 400; margin-bottom: 0px;">(<span class="modalProgClave"></span>) <span class="modalProgNombre"></span></p>
            <p style="font-weight: 400; margin-bottom: 0px;">Período <span class="modalPerNumero"></span>-<span class="modalPerAnio"></span></p>
          </div>
        </div>
  
        <div class="row">
          <div class="col s16 m4 l6">
            {!! Form::label('fechaAlta', 'Fecha de alta', array('class' => '')); !!}
            {!! Form::date('fechaAlta', \Carbon\Carbon::now(), array('id' => 'fechaAlta', 'class' => 'validate', 'required')) !!}
          </div>
          <!-- AQUI VA SELECT2 -->
          <div class="col s6 m6 l6">
            {!! Form::label('curEstado', 'Tipo de alta', array('class' => '')); !!}
            <select id="curEstado" class="browser-default validate " required name="curEstado" style="width: 100%;" required>
                <option value="" selected>SELECCIONE UNA OPCIÓN</option>
                <option value="P">Preinscrito</option>
                <option value="R">Regular</option>
                <option value="C">Condicionado Uno</option>
                <option value="A">Condicionado Dos</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col s12 m12 l12">
            <p>Favor de marcar los grupos que desea reactivar, que corresponden al curso del alumno dado de baja.</p>
            <div class="inscritos-eliminados">
            </div>
          </div>
        </div>
  
  
        {{-- <div class="row">
          <div class="col s6 m6 l6">
            {!! Form::label('bajObservaciones', 'Observaciones', array('class' => '')); !!}
            <textarea style="resize:none" name="bajObservaciones" rows="20"></textarea>
          </div>
        </div> --}}
        
        <div class="row">
          <div class="col s12 m12 l12">
            {!! Form::button('<i class="material-icons left">save</i> GUARDAR', [
              'class' => 'btn-large waves-effect  darken-3 confirmar-alta-alumno','type' => 'submit'
            ]) !!}
          </div>
        </div>
  
  
  
      {!! Form::close() !!}
    </div>
    <div class="modal-footer">
        <button type="button" class="modal-close waves-effect waves-green btn-flat">Cerrar</button>
    </div>
  </div>