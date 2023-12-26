<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use Carbon\Carbon;

use PDF;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class SecundariaRelDeudasController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
    //$this->middleware('permisos:r_plantilla_profesores');
    set_time_limit(8000000);
  }

  public function reporte()
  {
    //obtener año actual para el filtro que genera el reporte del año de periodo
    $anioActual = Carbon::now();

    $aluEstado = [
        'R' => 'REGULARES',
        'P' => 'PREINSCRITOS',
        'C' => 'CONDICIONADO',
        'A' => 'CONDICIONADO 2',
        'B' => 'BAJA',
        'T' => 'TODOS',
    ];

      return view('secundaria.reportes.relacion_deudas.create', [
        "aluEstado" => $aluEstado,
        "anioActual"=>$anioActual
      ]);
  }


  public function imprimir(Request $request)
    {

        $userId = Auth::id();

        $tipoReporte = $request->tipoReporte;
        $parametro_NombreArchivo = "";
        $parametro_Titulo = "";
        $parametro_Mes = "";
        $parametro_Ubicacion = "";
        $parametro_Periodo = "";
        $parametro_UltimoPrograma = "";
        $parametro_FechaIngreso = "";
        $parametro_UltimoPeriodo = "";
        $parametro_UltimoGradoGrupo = "";
        $parametro_Alumno = "";

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $temporary_table_name = "_". substr(str_shuffle($permitted_chars), 0, 15);

        if($tipoReporte == "carrera")
        {
            $parametro_NombreArchivo = 'pdf_secundaria_relacion_deudas';
            $parametro_Titulo = "RELACIÓN DE DEUDAS DEL ALUMNO";
            $result =  DB::select("call procSecundariaDeudasAlumno("
                .$userId
                .",'".$request->aluClave
                ."','".$request->ubiClave
                ."','".$request->tipoResumen
                ."','".$temporary_table_name."')");

            $pagos_deudores_array = DB::select('select * from '.$temporary_table_name);
            $pagos_deudores_collection = collect( $pagos_deudores_array );

            if($pagos_deudores_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay datos que coincidan con la información proporcionada.')->showConfirmButton();
                return back()->withInput();
            }

            //dd($pagos_deudores_collection);

            $parametro_Mes = "Pagos registrados hasta el: ". $result[0]->_return_fecha_hasta;
            $parametro_UltimoPrograma = "Ultimo Programa Cursado: ". $result[0]->_return_ultimo_programa;
            $parametro_FechaIngreso = "Fecha de Registro al Período: ".$result[0]->_return_fecha_ingreso;
            $parametro_UltimoPeriodo = "Ultimo Período: ".$result[0]->_return_ultimo_periodo;
            $parametro_UltimoGradoGrupo = "Ultimo Grado/Grupo: ".$result[0]->_return_ultimo_grado_grupo;
            $parametro_Ubicacion = "Ubicación: ".$result[0]->_return_ubicacion;
            $parametro_Alumno = "Alumno: ".$result[0]->_return_alumno;

            DB::statement( 'DROP TABLE IF EXISTS '.$temporary_table_name );

        }

        // Unix
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $fechaActual = Carbon::now("CDT");
        $horaActual = $fechaActual->format("H:i:s");

        $pdf = PDF::loadView('reportes.pdf.secundaria.relacion_deudas.'. $parametro_NombreArchivo, [
            "pagos" => $pagos_deudores_collection,
            "fechaActual" => $fechaActual->toDateString(),
            "horaActual" => $horaActual,
            "nombreArchivo" => $parametro_NombreArchivo,
            "elTitulo" => $parametro_Titulo,
            "elMes" => $parametro_Mes,
            "laUbicacion" => $parametro_Ubicacion,
            "ubiClave" => $request->ubiClave,
            "elPeriodo" => $parametro_Periodo,
            "UltimoPrograma" => $parametro_UltimoPrograma,
            "FechaIngreso" => $parametro_FechaIngreso,
            "UltimoPeriodo" => $parametro_UltimoPeriodo,
            "UltimoGradoGrupo" => $parametro_UltimoGradoGrupo,
            "elAlumno" => $parametro_Alumno,
        ]);

        if($tipoReporte == "carrera")
        {
            $pdf->setPaper('letter', 'portrait');
        }

        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo.'.pdf');
        return $pdf->download($parametro_NombreArchivo.'.pdf');


    }

}
