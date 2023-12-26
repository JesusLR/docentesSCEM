<div id="tutores">

	<div class="row">
		<div class="col s12 m6 l4">
			<div class="input-field">
				{!! Form::text('tutNombre', NULL, array('id' => 'tutNombre', 'class' => 'validate')) !!}
				{!! Form::label('tutNombre', 'Nombre(s) de Tutor', array('class' => '')); !!}
			</div>
		</div>
		<div class="col s12 m6 l4">
			<div class="input-field">
				{!! Form::number('tutTelefono', NULL, array('id' => 'tutTelefono', 'class' => 'validate','min'=>'0','max'=>'9999999999')) !!}
	            {!! Form::label('tutTelefono', 'Teléfono de tutor', array('class' => '')); !!}
			</div>
		</div>
		<div class="col s12 m6 l4">
          <div class="input-field col s6 m6 l3">
          	<a name="buscarTutor" id="buscarTutor" class="waves-effect btn-large tooltipped" data-position="right" data-tooltip="Buscar tutor por nombre y teléfono">
	          <i class=material-icons>search</i>
	        </a>
          </div>
          <div class="input-field col s6 m6 l3">
              <a name="vincularTutor" id="vincularTutor" class="waves-effect btn-large tooltipped" 
            	  data-position="right" data-tooltip="Vincular tutor a este alumno" disabled>
                  <i class=material-icons>sync</i>
              </a>
          </div>
        </div>
	</div>

	<br><br>
	<p>(Los siguientes datos son opcionales)</p>
	<div class="row">
		<div class="col s12 m6 l4">
			<div class="input-field">
				{!! Form::text('tutCalle', NULL, array('id' => 'tutCalle', 'class' => 'validate')) !!}
				{!! Form::label('tutCalle', 'Calle', array('class' => '')); !!}
			</div>
		</div>
		<div class="col s12 m6 l4">
			<div class="input-field">
				{!! Form::text('tutColonia', NULL, array('id' => 'tutColonia', 'class' => 'validate')) !!}
				{!! Form::label('tutColonia', 'Colonia', array('class' => '')); !!}
			</div>
		</div>
		<div class="col s12 m6 l4">
			<div class="input-field col s12 m6 l4">
				{!! Form::number('tutCodigoPostal', NULL, array('id' => 'tutCodigoPostal', 'class' => 'validate','min'=>'0','max'=>'99999')) !!}
	            {!! Form::label('tutCodigoPostal', 'Código Postal', array('class' => '')); !!}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col s12 m6 l4">
			<div class="input-field">
				{!! Form::text('tutPoblacion', NULL, array('id' => 'tutPoblacion', 'class' => 'validate')) !!}
				{!! Form::label('tutPoblacion', 'Población', array('class' => '')); !!}
			</div>
		</div>
		<div class="col s12 m6 l4">
            <div class="input-field">
            {!! Form::text('tutEstado', NULL, array('id' => 'tutEstado', 'class' => 'validate'))!!}
            {!! Form::label('tutEstado', 'Estado', array('class' => '')); !!}
            </div>
        </div>
		<div class="col s12 m6 l4">
			<div class="input-field">
				{!! Form::label('tutCorreo', 'Correo Electrónico', array('class' => 'validate')); !!}
				{!! Form::email('tutCorreo', NULL, array('id' => 'tutCorreo')) !!}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col s12 m6 l4">
          <div class="input-field col s6 m6 l3">
              <a name="crearTutor" id="crearTutor" class="waves-effect btn-large tooltipped #2e7d32 green darken-3" 
            	  data-position="right" data-tooltip="Crear nuevo tutor">
                  <i class=material-icons>person_add</i>
              </a>
          </div>
        </div>
	</div>
	
	<br>
	<!-- TABLA DE TUTORES DEL ALUMNO. -->
	<div class="row">
        <div class="col s12">
            <table id="tbl-tutores" class="responsive-table display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Nombre(s)</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- tutoresForm -->

