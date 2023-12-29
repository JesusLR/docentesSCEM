<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Alumno;
use App\Models\Pago;

use PDF;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class SecundariaHistorialPagosAlumnoController extends Controller
{
    //
    public function __construct() {
    	$this->middleware('auth');
    	$this->middleware('permisos:r_plantilla_profesores');
    }

    public function reporte() {
        return view('secundaria.reportes.historial_pagos_alumno.create');
    }

    /**
    * Los parámetros importantes que debe contener el Request.
    * $request->alumno_id.
    * $request->formatoImpresion. ( PDF || EXCEL )
    */
    public function imprimir(Request $request) {

    	$alumno = Alumno::with('persona')->where('aluClave', $request->aluClave)->first();
    	if(!$alumno) {
    		alert()->warning('Clave no válida', 'No existe la clave de alumno '.$request->aluClave.'. Favor de verificar.')->showConfirmButton();
    		return back()->withInput();
    	}


        $pagos = $this->obtenerPagosDeAlumno($alumno);
        if($pagos->isEmpty()) {
            alert()->warning('Sin pagos', 'No se encontraron pagos de este alumno. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }

    	return $this->imprimirPDF($pagos, $alumno);
    }



    public function obtenerPagosDeAlumno($alumno) {
        //
        return Pago::with('concepto')
        ->where('pagClaveAlu', $alumno->aluClave)->where('pagEstado', 'A')
        ->whereIn('pagConcPago', ["99", "01", "02", "03", "04", "05", "00", "06", "07", "08", "09", "10", "11", "12"])->get()
        ->sortByDesc(static function($pago, $key) {
            return $pago->pagAnioPer.' '.$pago->concepto->ordenReportes;
        });
    }



    public function imprimirPDF($pagos, $alumno) {

        $fechaActual = Carbon::now('CDT');
        //
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $nombreArchivo = 'pdf_historial_pagos_alumno';
        $pdf = PDF::loadView('reportes.pdf.secundaria.historial_pagos_alumno.'. $nombreArchivo, [
          "alumno" => $alumno,
          "pagos" => $pagos,
          "fechaActual" => $fechaActual->format('d/m/Y'),
          "horaActual" => $fechaActual->format('H:i:s'),
          "nombreArchivo" => $nombreArchivo,
        ]);
        $pdf->setPaper('letter', 'portrait');
        $pdf->defaultFont = 'Sans Serif';
        return $pdf->stream($nombreArchivo.'.pdf');
        return $pdf->download($nombreArchivo.'.pdf');
    }



}
