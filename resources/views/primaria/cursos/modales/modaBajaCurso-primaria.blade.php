{{-- MODAL BAJA CURSO --}}
<div id="modalBajaCursoPrimaria" class="modal">
    <div class="modal-content">
      {!! Form::open(['onKeypress' => 'return disableEnterKey(event)','route' => 'primaria_curso.bajaCurso', 'method' => 'POST', 'class' => 'form-baja-alumno']) !!}
        <input type="hidden" value="" class="modalCursoId" name="curso_id">
        <div class="row">
          <div class="col s12 m12 l12">
            <h4>Dar de baja al alumno</h4>
            <p style="font-weight: 700; margin-bottom: 0px;">(<span class="modalAlumnoClave"></span>) <span class="modalAlumnoNombre"></span></p>
            <p class="modalCursosInfo" style="margin-top: 0px;"></p>
          </div>
        </div>

        <div class="row">
          <div class="col s16 m4 l6">
            {!! Form::label('fechaBaja', 'Fecha de baja', array('class' => '')); !!}
            {!! Form::date('fechaBaja', \Carbon\Carbon::now(), array('id' => 'fechaBaja', 'class' => 'validate', 'required')) !!}
          </div>
          <!-- AQUI VA SELECT2 -->
          <div class="col s6 m6 l6">
            {!! Form::label('bajRazonBaja', 'Motivo de baja', array('class' => '')); !!}
            <select id="conceptosBaja" class="browser-default validate " required name="conceptosBaja" style="width: 100%;" required>
                <option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>
            </select>
          </div>
        </div>


        <div class="row">
          <div class="col s6 m6 l6">
            {!! Form::label('bajObservaciones', 'Observaciones', array('class' => '')); !!}
            <textarea style="resize:none" name="bajObservaciones" rows="20"></textarea>
          </div>
          <!-- AQUI VA SELECT2 -->
          <div class="col s6 m6 l6">
            {!! Form::label('bajBajaTotal', '¿Es baja total?', array('class' => '')); !!}
            <select id="bajBajaTotal" class="browser-default validate " required name="bajBajaTotal" style="width: 100%;">
                <option value="NO">NO</option>
                <option value="SI">SI</option>
            </select>
          </div>
        </div>
        

        <div class="row">
          <div class="col s12">
            <h5>POSIBLES HERMANOS</h5>
            <table id="tbl-posibles-hermanos" class="responsive-table display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Clave del Alumno</th>
                  <th>Nombre</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>



        <div class="row">
          <div class="col s12 m12 l12">
            {!! Form::button('<i class="material-icons left">save</i> GUARDAR', [
              'class' => 'btn-large waves-effect  darken-3 confirmar-baja-alumno','type' => 'submit'
            ]) !!}
          </div>
        </div>



      {!! Form::close() !!}
    </div>
    <div class="modal-footer">
        <button type="button" class="modal-close waves-effect waves-green btn-flat">Cerrar</button>
    </div>
</div>