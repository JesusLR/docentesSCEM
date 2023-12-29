<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Ubicacion;
use App\Models\Baja;
use App\Models\Pago;

use PDF;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

use App\Http\Helpers\Utils;

class SecundariaRelacionBajasPeriodoController extends Controller
{
    //REPORTE RELACIÓN DE BAJAS POR PERIODO.

    public function __construct() {
    	$this->middleware('auth');
    	// $this->middleware('permisos:r_plantilla_profesores');
    }

    public function reporte() {
    	$ubicaciones = Ubicacion::all();
    	return view('secundaria.reportes.relacion_bajas_periodo.create', compact('ubicaciones'));
    }//reporte.

    public function imprimir(Request $request) {

    	$fechaActual = Carbon::now('CDT');

    	$bajas = Baja::with('curso.periodo', 'curso.alumno.persona', 'curso.cgt.plan.programa.escuela.departamento.ubicacion')
    	->whereHas('curso.cgt.plan.programa.escuela.departamento.ubicacion', static function($query) use ($request) {
    		if($request->departamento_id) {
    			$query->where('departamento_id', $request->departamento_id);
    		}
    		if($request->escuela_id) {
    			$query->where('escuela_id', $request->escuela_id);
    		}
    		if($request->programa_id) {
    			$query->where('programa_id', $request->programa_id);
    		}
    	})
    	->whereHas('curso.periodo', static function($query) use ($request) {
    		if($request->periodo_id) {
    			$query->where('periodo_id', $request->periodo_id);
    		}
    	})
    	->whereHas('curso.alumno.persona', static function($query) use ($request) {
    		if($request->aluClave) {
    			$query->where('aluClave', $request->aluClave);
    		}
    		if($request->aluMatricula) {
    			$query->where('aluMatricula', $request->aluMatricula);
    		}
    		if($request->perApellido1) {
    			$query->where('perApellido1', $request->perApellido1);
    		}
    		if($request->perApellido2) {
    			$query->where('perApellido2', $request->perApellido2);
    		}
    		if($request->perNombre) {
    			$query->where('perNombre', $request->perNombre);
    		}
    	})
    	->where(static function ($query) use ($request) {
    		if($request->bajFechaBaja) {
    			$query->where('bajFechaBaja', $request->bajFechaBaja);
    		}
    	})->get();

    	if($bajas->isEmpty()) {
    		alert()->warning('Sin datos', 'No se encontraron registros con la información proporcionada')->showConfirmButton();
    		return back()->withInput();
    	}

    	$datos = collect([]);

    	$baja1 = $bajas->first();
    	$periodo = $baja1->curso->periodo;
    	$perAnioPago = $periodo->perAnioPago;
    	$ubicacion = $periodo->departamento->ubicacion;

    	$aluClaves = $bajas->pluck('curso.alumno.aluClave');
    	$pagosData = Pago::whereIn('pagClaveAlu', $aluClaves)
    	->where('pagAnioPer', $perAnioPago)
    	->whereIn('pagConcPago', ['00', '99'])
    	->get();

    	$bajas->each(static function ($item, $key) use ($datos, $pagosData, $ubicacion) {
    		$curso = $item->curso;
    		$planPago = $curso->curPlanPago;
    		$programa = $curso->cgt->plan->programa;
    		$alumno = $curso->alumno;
    		$persona = $alumno->persona;
    		$nombreCompleto = $persona->perApellido1.' '.$persona->perApellido2.' '.$persona->perNombre;

    		$pagConcPago = '00';
    		if($ubicacion->ubiClave == 'CVA' || $planPago == 'A' || $planPago == 'O' || $planPago == 'D') {
    			$pagConcPago = '99';
    		}
    		$pagoInscripcion = $pagosData->where('pagClaveAlu', $alumno->aluClave)
    			->where('pagConcPago', $pagConcPago)
    			->first();

    		$datos->push([
    			'aluClave' => $alumno->aluClave,
    			'aluMatricula' => $alumno->aluMatricula,
    			'nombreCompleto' => $nombreCompleto,
    			'grado' => $curso->cgt->cgtGradoSemestre,
    			'grupo' => $curso->cgt->cgtGrupo,
    			'estado' => $alumno->aluEstado.' '.$curso->curEstado,
    			'pagFechaPago' => $pagoInscripcion ? Utils::fecha_string($pagoInscripcion->pagFechaPago, 'mesCorto') : '',
    			'bajFechaBaja' => Utils::fecha_string($item->bajFechaBaja, 'mesCorto'),
    			// 'bajRazonBaja' => $item->conceptoBaja ? $item->conceptoBaja->conbNombre : '',
				'bajRazonBaja' => $item->bajObservaciones,
    			'progClave' => $programa->progClave,
    			'progNombre' => $programa->progNombre,
    			'escNombre' => $programa->escuela->escNombre,
    			'orden' => $programa->progClave.'-'.$nombreCompleto.'-'.$curso->cgt->cgtGrupo
    		]);
    	});

    	$escuelas = $datos->sortBy('orden')->groupBy(['escNombre', 'progClave', 'grado'])->sortKeys();

    	// Unix
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        //Nombre del archivo PDF de descarga
        $nombreArchivo = "pdf_relacion_bajas_periodo";
        //Cargar vista del PDF
        $pdf = PDF::loadView("reportes.pdf.secundaria.relacion_bajas_periodo.".$nombreArchivo, [
        "escuelas" => $escuelas,
        'periodo' => $periodo,
        "departamento" => $periodo->departamento,
        "ubicacion" => $ubicacion,
        "perFechaInicial" => Utils::fecha_string($periodo->perFechaInicial, 'mesCorto'),
        "perFechaFinal" => Utils::fecha_string($periodo->perFechaFinal, 'mesCorto'),
        "fechaActual" => Utils::fecha_string($fechaActual, 'mesCorto'),
        "horaActual" => $fechaActual->format('H:i:s'),
        "nombreArchivo" => $nombreArchivo
        ]);
        $pdf->setPaper('letter', 'portrait');
        $pdf->defaultFont = 'Times Sans Serif';
        return $pdf->stream($nombreArchivo);
        return $pdf->download($nombreArchivo);
    }//imprimir

}//Controller class.