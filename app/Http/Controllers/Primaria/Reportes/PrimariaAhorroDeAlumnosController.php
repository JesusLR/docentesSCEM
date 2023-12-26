<?php

namespace App\Http\Controllers\Primaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaAhorroDeAlumnosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ubicaciones = Ubicacion::sedes()->get();

        return view('primaria.reportes.ahorro_escolar.create', [
            "ubicaciones" => $ubicaciones
        ]);
    }

    public function imprimir(Request $request, $programa_id_ = null, $plan_id_ = null, $periodo_id_ = null, $grado_ = null, $grupo_ = null, $aluClave_ = null)
    {
        // dd($request->programa_id, $request->plan_id, $request->periodo_id, $request->gpoGrado, $request->gpoClave);
        // dd($programa_id_, $plan_id_, $periodo_id_, $grado_, $grupo_, $aluClave_);
        if($grado_ != ""){

            $resultado_array =  DB::select("call procPrimariaAhorroEscolarAlumno(" . $programa_id_ . ", 
            " . $plan_id_ . ",
            " . $periodo_id_ . ",
            " . $grado_ . ",
            '" . $grupo_ . "',
            " . $aluClave_ . ")");

            $resultado_collection = collect($resultado_array);
        }else{
            if ($request->clavePago != "") {
                $resultado_array =  DB::select("call procPrimariaAhorroEscolarAlumno(" . $request->programa_id . ", 
                " . $request->plan_id . ",
                " . $request->periodo_id . ",
                " . $request->gpoGrado . ",
                '" . $request->gpoClave . "',
                " . $request->clavePago . ")");
    
                $resultado_collection = collect($resultado_array);
            } else {
                $resultado_array =  DB::select("call procPrimariaAhorroEscolar(" . $request->programa_id . ", 
                " . $request->plan_id . ",
                " . $request->periodo_id . ",
                " . $request->gpoGrado . ",
                '" . $request->gpoClave . "')");
    
                $resultado_collection = collect($resultado_array);
            }
        }

        

        if ($resultado_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay datos registrados para este grupo.')->showConfirmButton();
            return back()->withInput();
        }

        // parametros generales 
        $anoActual = $resultado_collection[0]->perAnioPago;
        $anoSiguiente = $resultado_collection[0]->perAnioPago + 1;
        $ciclo_escolar = $anoActual . '-' . $anoSiguiente;
        $ubicacion = $resultado_collection[0]->ubiClave . ' ' . $resultado_collection[0]->ubiNombre;
        $nivel = $resultado_collection[0]->progClave . ' (' . $resultado_collection[0]->planClave . ') ' . $resultado_collection[0]->progNombre;
        $peridoescolar = $resultado_collection[0]->fecha_inicio . ' al ' . $resultado_collection[0]->fecha_fin;
        $grado = $resultado_collection[0]->cgtGradoSemestre;
        $grupo = $resultado_collection[0]->cgtGrupo;

        $aluClavePago = $resultado_collection->groupBy('clave_pago');

        $parametro_NombreArchivo = "pdf_primaria_ahorro_escolar";
        $pdf = PDF::loadView('primaria.pdf.ahorro_escolar.' . $parametro_NombreArchivo, [
            "ubicacion" => $ubicacion,
            "nivel" => $nivel,
            "peridoescolar" => $peridoescolar,
            "ahorro_alumnos" => $resultado_collection,
            "aluClavePago" => $aluClavePago,
            "grado" => $grado,
            "grupo" => $grupo

        ]);


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

 
}
