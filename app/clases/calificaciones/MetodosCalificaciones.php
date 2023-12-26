<?php
namespace App\clases\calificaciones;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Models\Calificacion;
use App\Http\Models\CalificacionHistorial;
use Carbon\Carbon;

class MetodosCalificaciones
{
	/**
	* @param string $clave
	*/
	public static function etapas($clave = null) 
	{
		$etapas = collect([
			'P1' => 'inscCalificacionParcial1',
			'P2' => 'inscCalificacionParcial2',
			'P3' => 'inscCalificacionParcial3',
			'PP' => 'inscPromedioParciales',
			'OR' => 'inscCalificacionOrdinario',
			'CF' => 'incsCalificacionFinal',
		]);

		return self::es_clave_valida($clave) ? $etapas[$clave] : $etapas;
	}

	/**
	* @param App\Http\Models\Calificacion
	* @param App\Http\Models\Materia
	* @param string $clave_etapa (opcional)
	*/
	public static function definirCalificacion($calificacion, $materia, $clave_etapa = null)
	{
		if(self::es_clave_valida($clave_etapa)) {
			$etapa = self::etapas($clave_etapa);
			$numero = $calificacion->$etapa;

			return $clave_etapa != 'CF' ? self::definir($numero, $materia) : self::calificacionFinal($calificacion, $materia);
		} else {
			return self::etapas()->map(static function($etapa, $clave) use ($calificacion, $materia) {
				$numero = $calificacion->$etapa;
				
				return [
					$etapa => $clave != 'CF' ? self::definir($numero, $materia) : self::calificacionFinal($calificacion, $materia),
				];
			});
		}
	}

	/**
	* @param int $numero
	* @param App\Http\Models\Materia $materia
	*/
	private static function definir($numero, $materia) {

		if($materia->esAlfabetica()) {
			return $numero == 0 ? 'Apr' : 'No Apr';
		} else {
			return self::calificacionNumerica($numero);
		}
	}


	/**
	* Define la calificacion de una materia tipo numérica.
	*
	* @param int
	*/
	public static function calificacionNumerica($numero)
	{
		switch ($numero) {
		    case -1:
		    	$numero = 'Des';
		    	break;
		    case -2:
		    	$numero = 'S/D';
		    	break;
		    case -3:
		    	$numero = 'Npa';
		    	break;
	  }

		return $numero;
	}

	/**
	* @param App\Http\Models\Calificacion
	* @param App\Http\Models\Materia
	*/
	private static function calificacionFinal($calificacion, $materia) {
		$motivo_id = $calificacion->motivofalta_id;
		$motivo = $motivo_id && $motivo_id != 10 ? self::motivo_falta($motivo_id) : null;
		$puntaje = $calificacion->incsCalificacionFinal;
		if($materia->esAlfabetica()) {
			return $puntaje == 0 ? 'Apr' : 'No Apr';
		} else {
			return ($puntaje >= 0 && !$motivo) ? $puntaje : ($motivo ? $motivo->mfAbreviatura : '');
		}
	}

	/**
	* @param int
	*/
	public static function motivo_falta($motivo_id = null) {

		return $motivo_id ? DB::table('motivosfalta')->where('id', $motivo_id)->first() : null;
	}

	public static function es_aprobada($calificacion, $calificacion_minima)
	{
		if(is_null($calificacion))
			return false;

		return $calificacion >= $calificacion_minima || $calificacion == 'Apr';
	}

	public static function es_reprobada($calificacion, $calificacion_minima)
	{
		if(is_null($calificacion))
			return false;
		
		return $calificacion < $calificacion_minima || $calificacion == 'No Apr';
	}

	public static function es_clave_valida($clave = null) 
	{
		return ($clave && in_array($clave, ['P1', 'P2', 'P3', 'PP', 'OR', 'CF']));
	}

	/**
	 * @param App\Http\Models\Calificacion $calificacion_anterior
	 * @param App\Http\Models\Calificacion $calificacion_actual
	 * @return App\Http\Models\CalificacionHistorial
	 */
	public static function crearHistorial($calificacion_anterior, $calificacion_actual): CalificacionHistorial
	{
		return CalificacionHistorial::create([
			'calificacion_id' 	=> $calificacion_actual->id,
			'parcial1' 			=> $calificacion_actual->inscCalificacionParcial1,
			'parcial1_anterior' => $calificacion_anterior->inscCalificacionParcial1,
			'faltas1' 			=> $calificacion_actual->inscFaltasParcial1,
			'faltas1_anterior' 	=> $calificacion_anterior->inscFaltasParcial1,
			'parcial2' 			=> $calificacion_actual->inscCalificacionParcial2,
			'parcial2_anterior' => $calificacion_anterior->inscCalificacionParcial2,
			'faltas2' 			=> $calificacion_actual->inscFaltasParcial2,
			'faltas2_anterior' 	=> $calificacion_anterior->inscFaltasParcial2,
			'parcial3' 			=> $calificacion_actual->inscCalificacionParcial3,
			'parcial3_anterior' => $calificacion_anterior->inscCalificacionParcial3,
			'faltas3' 			=> $calificacion_actual->inscFaltasParcial3,
			'faltas3_anterior' 	=> $calificacion_anterior->inscFaltasParcial3,
			'ordinario' 		=> $calificacion_actual->inscCalificacionOrdinario,
			'ordinario_anterior' => $calificacion_anterior->inscCalificacionOrdinario,
			'fecha_cambio' 		=> Carbon::now('America/Merida'),
			'motivofalta_id' 	=> $calificacion_actual->motivofalta_id,
			'motivofalta_anterior_id' => $calificacion_anterior->motivofalta_id,
			'admin_id' 			=> auth()->user()->id,
			'docente_id' 		=> null,
		]);
	}
}