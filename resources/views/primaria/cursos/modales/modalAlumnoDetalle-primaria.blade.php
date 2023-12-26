{{-- MODAL EQUIVALENTES --}}
<div id="modalAlumnoDetalle-primaria" class="modal">


    <div class="modal-content">
      <div class="row">
        <div class="col s12 ">
          <button class="btn modal-close" style="float:right;">cerrar</button>
        </div>
        <div class="col s12 ">
          <div class="card ">
            <div class="card-content">
              <span class="card-title">Detalle del alumno <span class="nombres"></span></span>
  
  
                
              <div class="row">
                <div class="col s12 m6 l4">
                    <div class="input-field">
                      <input type="text" id="mperDirCalle" readonly="true">
                    {!! Form::label('mperDirCalle', 'Calle ', array('class' => '')); !!}
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="input-field">
                      <input type="text" id="perDirNumExt" readonly="true">
                    {!! Form::label('perDirNumExt', 'Número exterior ', array('class' => '')); !!}
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="input-field">
                      <input type="text" id="perDirNumInt" readonly="true">
                    {!! Form::label('perDirNumInt', 'Número interior', array('class' => '')); !!}
                    </div>
                </div>
              </div>
  
              <div class="row">
                  <div class="col s12 m6 l4">
                    <div class="input-field">
                      <input type="text" id="paisId" readonly="true">
                      {!! Form::label('paisId', 'País', array('class' => '')); !!}
                    </div>
                  </div>
                  <div class="col s12 m6 l4">
                    <div class="input-field">
                      <input type="text" id="estado_id" readonly="true">
                      {!! Form::label('estado_id', 'Estado', array('class' => '')); !!}
                    </div>
                  </div>
                  <div class="col s12 m6 l4">
                          <div class="input-field">
                          <input type="text" id="municipio_id" readonly="true">
                          {!! Form::label('municipio_id', 'Municipio', array('class' => '')); !!}
                          </div>
                  </div>
              </div>
  
  
  
              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                          <input type="text" id="paisPrepa" readonly="true">
                          {!! Form::label('paisPrepa', 'Preparatoria país', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                          <input type="text" id="estadoPrepa" readonly="true">
                          {!! Form::label('estadoPrepa', 'Preparatoria estado', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                          <input type="text" id="municipioPrepa" readonly="true">
                          {!! Form::label('municipioPrepa', 'Preparatoria municipio', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                          <input type="text" id="preparatoria_id" readonly="true">
                          {!! Form::label('preparatoria_id', 'Preparatoria procedencia', array('class' => '')); !!}
                      </div>
                  </div>
              </div>
  
      
              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      <input type="text" id="perDirColonia" readonly="true">
                      {!! Form::label('perDirColonia', 'Colonia ', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      <input type="text" id="perDirCP" readonly="true">
                      {!! Form::label('perDirCP', 'Código Postal ', array('class' => '')); !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      <input type="text" id="perSexo" readonly="true">
                      {!! Form::label('perSexo', 'Sexo ', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      {!! Form::label('perFechaNac', 'Fecha de nacimiento', array('class' => '')); !!}
                      <input type="date" id="perFechaNac" readonly="true">
                  </div>
              </div>
  
              <div class="row">
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      <input type="text" id="perTelefono1" readonly="true">
                      {!! Form::label('perTelefono1', 'Teléfono fijo', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      <input type="text" id="perTelefono2" readonly="true">
                      {!! Form::label('perTelefono2', 'Teléfono móvil', array('class' => '')); !!}
                      </div>
                  </div>
                  <div class="col s12 m6 l4">
                      <div class="input-field">
                      <input type="text" id="perCorreo1" readonly="true">
                      {!! Form::label('perCorreo1', 'Correo', array('class' => '')); !!}
                      </div>
                  </div>
              </div>
  
  
  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script type="text/javascript">
    $(document).ready(function() {
        $('.modal').modal();
  
        $(document).on("click", ".btn-modal-alumno-detalle-primaria", function (e) {
            e.preventDefault()
            var alumno_id = $(this).data("alumno-id");
  
            $.get(base_url+`/primaria_alumno/alumnoById/${alumno_id}`, function(res,sta) {
                console.log("res", res)
                console.log("res", res.persona.perDirCalle)
                $(".nombres").html(res.persona.perNombre + " " + res.persona.perApellido1 + " " + res.persona.perApellido2)
                $("#mperDirCalle").val(res.persona.perDirCalle);
                $("#perDirNumExt").val(res.persona.perDirNumExt);
                $("#perDirNumInt").val(res.persona.perDirNumInt);
  
                $("#paisId").val(res.persona.municipio.estado.pais.paisNombre);
                $("#estado_id").val(res.persona.municipio.estado.edoNombre);
                $("#municipio_id").val(res.persona.municipio.munNombre);
  
                $("#paisPrepa").val(res.preparatoria.municipio.estado.pais.paisNombre);
                $("#estadoPrepa").val(res.preparatoria.municipio.estado.edoNombre);
                $("#municipioPrepa").val(res.preparatoria.municipio.munNombre);
                $("#preparatoria_id").val(res.preparatoria.prepNombre);
  
                $("#perDirColonia").val(res.persona.perDirColonia)
                $("#perDirCP").val(res.persona.perDirCP)
                $("#perSexo").val(res.persona.perSexo)
                $("#perFechaNac").val(res.persona.perFechaNac)
                $("#perTelefono1").val(res.persona.perTelefono1)
                $("#perTelefono2").val(res.persona.perTelefono2)
                $("#perCorreo1").val(res.persona.perCorreo1)
  
                $("#modalAlumnoDetalle-primaria label").addClass("active")
  
            });
            $('.modal').modal();
        })
     
    })
  </script>