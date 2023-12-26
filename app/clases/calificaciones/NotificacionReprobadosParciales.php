<?php
namespace App\clases\calificaciones;

use App\Http\Models\Grupo;
use App\clases\SCEM\Mailer;
use App\clases\Recolectores\AlumnosReprobadosParcialesRecolector;

/**
 * Se creó para envío de notificaciones al modificar o registrar calificaciones en CalificacionController::store
 * Recibe el grupo en el cuál se realizaron los cambios, y el recolector de reprobados.
 */
class NotificacionReprobadosParciales
{
	protected $grupo;
	protected $recolector;
	protected $empleado;
	protected $materia;
	protected $plan;
	protected $programa;
	protected $periodo;
	protected $departamento;
	protected $ubicacion;

	public function __construct(Grupo $grupo, AlumnosReprobadosParcialesRecolector $recolector)
	{
		$this->grupo = $grupo;
		$this->recolector = $recolector;

		$this->empleado = $this->grupo->empleado;
		$this->materia = $this->grupo->materia;
		$this->plan = $this->grupo->plan;
		$this->programa = $this->plan->programa;
		$this->periodo = $grupo->periodo;
		$this->departamento = $this->periodo->departamento;
		$this->ubicacion = $this->departamento->ubicacion;
	}

	public function enviar()
	{
		// CAMBIA CORREO Y CONTRASEÑA
		// parciales@unimodelo.com
		// JYtbj678


		// username_email' => 'parciales@modelo.edu.mx',
		// 	'password_email' => 'tF8R4ssEWp',
		$this->mail = new Mailer([
			'username_email' => 'parciales@modelo.edu.mx',
			'password_email' => '2TvgO5jB5iqk', // 'tF8R4ssEWp',
			'to_email' => 'luislara@modelo.edu.mx',
			'to_name' => '',
			'cc_email' => '',
			'subject' => 'Importante! Se ha realizado captura de calificaciones.',
			'body' => $this->armar_mensaje_reprobados_parciales(),
		]);
		$nombre_archivo = $this->recolector->nombreArchivoExcel;
		$this->mail->adjuntar_archivo(storage_path($nombre_archivo), $nombre_archivo);
		// $this->mail->agregar_destinatario('jmanuel.lopez@modelo.edu.mx'); #TEST
		$director = $this->programa->escuela->empleado;
		$coordinador = $this->programa->empleado;

		if($director && $director->empCorreo1)
			$this->mail->agregar_destinatario($director->empCorreo1);
		if($coordinador && $coordinador->empCorreo1)
			$this->mail->agregar_destinatario($coordinador->empCorreo1);
		
		$this->mail->enviar();
	}

	/**
	* @param App\Http\Models\Baja
	*/
	private function armar_mensaje_reprobados_parciales()
	{
		$usuario = auth()->user(); 
		$nombre_empleado = $usuario->empleado->persona->nombreCompleto();

		return "<p>{$nombre_empleado} ({$usuario->empleado_id}) ha capturado calificaciones y aparecen alumnos reprobados: </p>
		<br>
		<p><b>Campus: </b> {$this->ubicacion->ubiClave} - {$this->ubicacion->ubiNombre}</p>
		<p><b>Programa: </b> {$this->programa->progClave} ({$this->plan->planClave}) {$this->programa->progNombre}</p>
		<p><b>Grupo: </b> {$this->grupo->gpoSemestre} - {$this->grupo->gpoClave}</p>
		<p><b>Materia: </b> {$this->materia->matClave} - {$this->materia->matNombreOficial}</p>
		<br>
		<p><b>Nombre del profesor: </b>{$this->empleado->id} {$this->empleado->persona->nombreCompleto()}</p>
		<br>
		<p>Favor de no responder a este correo automatizado.</p>
		";
	}
}