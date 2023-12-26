{{-- MODAL EQUIVALENTES --}}
<div id="modalPreinscritoDetalle" class="modal">
  <input class="modal_curso_id" value="" type="hidden" />
  <div class="modal-content">
    <div class="row">
      <div class="col s12 ">
          <button class="btn modal-close" style="float:right;">cerrar</button>
      </div>

      <div class="col s12 ">
          <div class="card ">
            <div class="card-content ">
              <span class="card-title">PREINSCRIPCIÓN #<span class="modalCursoId"></span></span>

              {{-- NAVIGATION BAR--}}
              <nav class="nav-extended">
                <div class="nav-content">
                  <ul class="tabs tabs-transparent">
                    <li class="tab"><a class="active" href="#general">General</a></li>
                    <li class="tab"><a href="#cuotas">Cuotas</a></li>
                    <li class="tab"><a href="#becas">Becas</a></li>
                  </ul>
                </div>
              </nav>

              {{-- GENERAL BAR--}}
              <div id="general">
                  <div class="row">
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('ubiClave','', array('class' => 'modalUbiClave','readonly' => 'true')) !!}
                              {!! Form::label('ubiClave', 'Campus', array('class' => '')); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('departamento_id', '', array('class' => 'modalDepartamentoId', 'readonly' => 'true')) !!}
                              {!! Form::label('departamento_id', 'Departamento', array('class' => '')); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('escuela_id', '', array('readonly' => 'true', "class" => "modalEscuelaId")) !!}
                              {!! Form::label('escuela_id', 'Escuela', array('class' => '')); !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('periodo_id', '', array("class" => "modalPeriodo",  'readonly' => 'true')) !!}
                              {!! Form::label('periodo_id', 'Periodo', array('class' => '')); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                          {!! Form::text('perFechaInicial', '', array("class" => "modalPerFechaInicial", 'readonly' => 'true')) !!}
                          {!! Form::label('perFechaInicial', 'Fecha Inicio', ['class' => '']); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                          {!! Form::text('perFechaFinal', '', array('readonly' => 'true', "class" => "modalPerFechaFinal")) !!}
                          {!! Form::label('perFechaFinal', 'Fecha Final', ['class' => '']); !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('programa_id', '', array('readonly' => 'true', "class" => "modalProgNombre")) !!}
                              {!! Form::label('programa_id', 'Programa', array('class' => '')); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('plan_id', '', array('readonly' => 'true', "class" => "modalPlanClave")) !!}
                              {!! Form::label('plan_id', 'Plan', array('class' => '')); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('cgt_id', '', array('readonly' => 'true', "class" => "modalCgtGradoSemestre")) !!}
                              {!! Form::label('cgt_id', 'CGT', array('class' => '')); !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s12 m8">
                          <div class="input-field">
                              {!! Form::text('alumno_id', '', array('readonly' => 'true', "class" => "modalPerNombre")) !!}
                              {!! Form::label('alumno_id', 'Alumno', array('class' => '')); !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('curEstado', '', array('readonly' => 'true', "class" => "modalCurEstado")) !!}
                            {!! Form::label('curEstado', 'Estado del curso', array('class' => '')); !!}
                        </div>
                      </div>
                      <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('curTipoIngreso', '', array('readonly' => 'true', "class" => "modalCurTipoIngreso")) !!}
                            {!! Form::label('curTipoIngreso', 'Tipo de ingreso', array('class' => '')); !!}
                        </div>
                      </div>

                      <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::text('curOpcionTitulo', '', array('readonly' => 'true', "class" => "modalCurOpcionTitulo")) !!}
                            {!! Form::label('curOpcionTitulo', 'Opción de titulo', array('class' => '')); !!}
                        </div>
                      </div>
                  </div>

                  <div class="row">
                    <div class="col s12 m6 l4">
                            {!! Form::label('curExaniFoto', 'Foto exani', ['class' => '']); !!}
                            <div class="input-field">
                                <img style="width:200px;" class="curexaniimg" src="" alt="" style="display:none;">

                                <embed class="curexanipdf" src=""  style="display:none;"
                                    type="application/pdf"
                                    width="100%"
                                    height="600px" /> 
                            </div>
                    </div>
                    <div class="col s12 m6 l4">
                        <div class="input-field">
                            {!! Form::number('curExani', '', ['id' => 'curExani', 'class' => 'validate','readonly', 'min' => '900', 'max' => '1300']) !!}
                            {!! Form::label('curExani', 'Resultado Calificación Exani', ['class' => '']); !!}
                        </div>
                    </div>
                  </div>


              </div>

              {{-- CUOTAS BAR--}}
              <div id="cuotas">
                  <div class="row">
                      <div class="col s4">
                          <div class="input-field">
                          {!! Form::text('curAnioCuotas', '', array('readonly' => 'true','class' => "modalCurAnioCuotas")) !!}
                          {!! Form::label('curAnioCuotas', 'Año cuota', ['class' => '']); !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                          {!! Form::text('curImporteInscripcion', '', array('class' => 'modalCurImporteInscripcion', 'readonly' => 'true')) !!}
                          {!! Form::label('curImporteInscripcion', 'Importe inscripción', ['class' => '']); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                          {!! Form::text('curImporteMensualidad', '', array('readonly' => 'true', 'class' => 'modalCurImporteMensualidad')) !!}
                          {!! Form::label('curImporteMensualidad', 'Importe mensual', ['class' => '']); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                              {!! Form::text('curImporteVencimiento', '', array('readonly' => 'true', 'class' => 'modalCurImporteVencimiento')) !!}
                              {!! Form::label('curImporteVencimiento', 'Importe vencido', ['class' => '']); !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                          {!! Form::text('curImporteDescuento', '', array('readonly' => 'true','class' => 'modalCurImporteDescuento')) !!}
                          {!! Form::label('curImporteDescuento', 'Descuento pronto pago', ['class' => '']); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                          {!! Form::text('curDiasProntoPago', '', array('readonly' => 'true', 'class' => 'modalCurDiasProntoPago')) !!}
                          {!! Form::label('curDiasProntoPago', 'Días pronto pago', ['class' => '']); !!}
                          </div>
                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                            {!! Form::text('curPlanPago', '', array('readonly' => 'true', "class" => "modalCurPlanPago")) !!}
                            {!! Form::label('curPlanPago', 'Plan de pago', array('class' => '')); !!}
                          </div>
                      </div>
                  </div>
              </div>
              {{-- BECAS BAR--}}
              <div id="becas">
                  <div class="row">
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                            {!! Form::text('curTipoBeca', '', array('readonly' => 'true', "class" => "modalCurTipoBeca")) !!}
                            {!! Form::label('curTipoBeca', 'Tipo de beca', array('class' => '')); !!}
                          </div>

                      </div>
                      <div class="col s12 m6 l4">
                          <div class="input-field">
                          {!! Form::text('curPorcentajeBeca', '', array('readonly' => 'true', 'class' => "modalCurPorcentajeBeca")) !!}
                          {!! Form::label('curPorcentajeBeca', '% Beca', ['class' => '']); !!}
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s12 m8">
                          <div class="input-field">
                          {!! Form::textarea('curObservacionesBeca', '', ['readonly' => 'true', 'class'=> 'modalCurObservacionesBeca']) !!}
                          {!! Form::label('curObservacionesBeca', 'Observaciones', ['class' => '']); !!}
                          </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
  </div>
</div>

<script>
    // var curso_id
</script>