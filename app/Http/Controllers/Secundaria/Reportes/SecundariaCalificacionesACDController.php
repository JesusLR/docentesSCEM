<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

class SecundariaCalificacionesACDController extends Controller
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
    public function reporteBoleta()
    {
        $ubicaciones = Ubicacion::sedes()->get();
        return view('secundaria.reportes.boleta_de_calificaciones_ACD.create', [
            "ubicaciones" => $ubicaciones
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimir(Request $request)
    {
        $parametro_Titulo = "BOLETA DE CALIFICACIONES DEL ALUMNO(A)";

        $mostrar_observaciones = $request->mes_id;

        // busca cuando se proporciona grado y grupo 
        if ($request->gpoClave != "") {

            $resultado_array =  DB::select("call procSecundariaCalificacionesACDGradoGrupo(" . $request->programa_id . ", 
            " . $request->plan_id . ",
            " . $request->periodo_id . ",
            " . $request->gpoGrado . ",
            '" . $request->gpoClave . "')");
            $resultado_collection = collect($resultado_array);


            if ($resultado_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }
            $alumnoAgrupado = $resultado_collection->groupBy('clave_pago');
            $resultado_registro = $resultado_array[0];
        }

        // buscar por solo grado 
        if ($request->gpoClave == "" && $request->aluClave == "") {


            $resultado_array =  DB::select("call procSecundariaCalificacionesACDGradoCompleto(" . $request->programa_id . ", 
                    " . $request->plan_id . ",
                    " . $request->periodo_id . ",
                    " . $request->gpoGrado . ")");
            $resultado_collection = collect($resultado_array);


            if ($resultado_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grado. Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }
            $resultado_registro = $resultado_array[0];
            $alumnoAgrupado = $resultado_collection->groupBy('clave_pago');
        }

        if ($request->aluClave != "") {
            $resultado_array =  DB::select("call procSecundariaCalificacionesACDAlumno(" . $request->programa_id . ", 
                    " . $request->plan_id . ",
                    " . $request->periodo_id . ",
                    " . $request->gpoGrado . ",
                    " . $request->aluClave . ")");
            $resultado_collection = collect($resultado_array);


            if ($resultado_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este alumno. Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }
            $resultado_registro = $resultado_array[0];
            $alumnoAgrupado = $resultado_collection->groupBy('clave_pago');
        }


        $parametro_Ciclo = $resultado_registro->ciclo_escolar;

        $fechaActual = Carbon::now('America/Merida');

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $parametro_NombreArchivo = 'pdf_secundaria_boleta_calificaciones_general_acd';
        $pdf = PDF::loadView('reportes.pdf.secundaria.boleta_de_calificaciones_acd.' . $parametro_NombreArchivo, [
            "calificaciones" => $resultado_collection,
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "horaActual" => $fechaActual->format('H:i:s'),
            "cicloEscolar" => $parametro_Ciclo,
            "titulo" => $parametro_Titulo,
            "alumnoAgrupado" => $alumnoAgrupado,
            "observaciones" => $mostrar_observaciones
        ]);


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

    public function boletadesdecurso($curso_id)
    {

        $parametro_NombreArchivo = 'pdf_secundaria_boleta_calificaciones_curso';
        $parametro_Titulo = "BOLETA DE CALIFICACIONES DEL ALUMNO(A)";
        $resultado_array =  DB::select("call procSecundariaCalificacionesACDCurso("
            .$curso_id
            .")");
        $resultado_collection = collect( $resultado_array );

        if($resultado_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este alumno(a). Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }
        $resultado_registro = $resultado_array[0];


        $alumnoAgrupado = $resultado_collection->groupBy('clave_pago');


        //dd($pagos_deudores_collection);
        $parametro_Alumno = $resultado_registro->nombres . " ". $resultado_registro->ape_paterno .
            " " . $resultado_registro->ape_materno;
        $parametro_Clave = $resultado_registro->clave_pago;
        $parametro_Grupo = $resultado_registro->gpoGrado . "". $resultado_registro->gpoClave;
        $parametro_Curp = $resultado_registro->curp;
        $parametro_Ciclo = $resultado_registro->ciclo_escolar;

        //$fechaActual = Carbon::now();
        $fechaActual = Carbon::now('America/Merida');

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');


        $pdf = PDF::loadView('reportes.pdf.secundaria.boleta_de_calificaciones_acd.'. $parametro_NombreArchivo, [
            "calificaciones" => $resultado_collection,
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "horaActual" => $fechaActual->format('H:i:s'),
            "cicloEscolar" => $parametro_Ciclo,
            "curp" => $parametro_Curp,
            "nombreAlumno" => $parametro_Alumno,
            "clavepago" => $parametro_Clave,
            "gradogrupo" => $parametro_Grupo,
            "titulo" => $parametro_Titulo,
            "alumnoAgrupado" => $alumnoAgrupado
        ]);


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo. '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');

    }
 
}
