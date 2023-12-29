<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

class SecundariaRelacionPadresTutoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ubicaciones = Ubicacion::sedes()->get();

        return view('secundaria.reportes.relacion_de_tutores.create', [
            "ubicaciones" => $ubicaciones
        ]);
    }

   
    // llamada para impresión de PDF 
    public function imprimir(Request $request)
    {
        // parametros que viene de la vista 
        $programa_id = $request->programa_id;
        $plan_id = $request->plan_id;
        $periodo_id = $request->periodo_id;
        $gpoGrado = $request->gpoGrado;
        $gpoClave = $request->gpoClave;
        $tipoVista = $request->tipoVista;
        $aluClave = $request->aluClave;

        if($aluClave == "") {
            // llamada al SP 
            $resultado_array =  DB::select("call procSecundariaFamiliaresTutoresGradoGrupo(" . $programa_id . ", 
            " . $plan_id . ",
            " . $periodo_id . ",
            " . $gpoGrado . ",
            '" . $gpoClave . "')");
            $resultado_collection = collect($resultado_array);    


            if ($resultado_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay información capturada con los datos proporcionados.')->showConfirmButton();
            return back()->withInput();
            }
        }else{
            // llamada al SP 
            $resultado_array =  DB::select("call procSecundariaFamiliaresTutoresAlumno(" . $programa_id . ", 
            " . $plan_id . ",
            " . $periodo_id . ",
            " . $gpoGrado . ",
            '" . $gpoClave . "',
            " . $aluClave . ")");
            $resultado_collection = collect($resultado_array);    


            if ($resultado_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay información capturada con los datos proporcionados.')->showConfirmButton();
            return back()->withInput();
            }
        }

        


        $fechaActual = Carbon::now('America/Merida');
       

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');


        // parametros generales 
        $fecha_inicio = $resultado_collection[0]->fecha_inicio;
        $fecha_fin = $resultado_collection[0]->fecha_fin;
        $perAnioPago = $resultado_collection[0]->perAnioPago;
        $depClave = $resultado_collection[0]->depClave;
        $depNombre = $resultado_collection[0]->depNombre;
        $planClave = $resultado_collection[0]->planClave;
        $ubiClave = $resultado_collection[0]->ubiClave;
        $ubiNombre = $resultado_collection[0]->ubiNombre;
        $cgtGradoSemestre = $resultado_collection[0]->cgtGradoSemestre;
        $cgtGrupo = $resultado_collection[0]->cgtGrupo;







        $parametro_NombreArchivo = 'pdf_relacion_de_tutores';
        $pdf = PDF::loadView('reportes.pdf.secundaria.relacion_de_tutores.' . $parametro_NombreArchivo, [
            "tutoresAlumno"     => $resultado_collection,
            "fechaActual"       => $fechaActual->format('d/m/Y'),
            "horaActual"        => $fechaActual->format('H:i:s'),
            "fecha_inicio"      => $fecha_inicio,
            "fecha_fin"         => $fecha_fin,
            "perAnioPago"       => $perAnioPago,
            "depClave"          => $depClave,
            "depNombre"         => $depNombre,
            "planClave"         => $planClave,
            "ubiClave"          => $ubiClave,
            "ubiNombre"         => $ubiNombre,
            "nombre_archivo"    => $parametro_NombreArchivo,
            "tipoVista"         => $tipoVista,
            "cgtGradoSemestre"  => $cgtGradoSemestre,
            "cgtGrupo"          => $cgtGrupo
        ]);


        // $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }


}
