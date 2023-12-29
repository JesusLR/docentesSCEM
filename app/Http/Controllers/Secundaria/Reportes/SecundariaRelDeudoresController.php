<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\Periodo;
use App\Models\Cgt;
use App\Models\Plan;
use App\Models\Programa;
use App\Models\Escuela;
use App\Models\Departamento;
use App\Models\Ubicacion;
use App\Models\Cuota;
use Auth;

use Carbon\Carbon;

use PDF;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class SecundariaRelDeudoresController extends Controller
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

    return view('secundaria.reportes.relacion_deudores.create', [
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
      $parametro_Semestre_Inicio = 1;
      $parametro_Semestre_Fin = 6;
      $parametro_Semestre_Filtro_Inicio = 1;
      $parametro_Semestre_Filtro_Fin = 6;
      //SEMESTRE PARES (PERIODO 1), SE TIENE QUE CONSULTAR EL SEMESTRE IMPAR (PERIODO 3), ES DECIR TODO EL AÑO ESCOLAR DESDE SEPTIEMBRE
      if ($request->numSemestre == "1" || $request->numSemestre == "2" || $request->numSemestre == "3" ||
      $request->numSemestre == "4" || $request->numSemestre == "5" || $request->numSemestre == "6") {

          $parametro_Semestre_Inicio = $request->numSemestre;
          $parametro_Semestre_Fin = $request->numSemestre;

          $parametro_Semestre_Filtro_Inicio = $request->numSemestre;
          $parametro_Semestre_Filtro_Fin = $request->numSemestre;
      }

      $parametro_curEstadoR = "R";
      $parametro_curEstadoC = "C";
      $parametro_curEstadoA = "A";
      $parametro_curEstadoP = "P";
      $parametro_curEstadoB = "B";

      if ($request->curEstados == "B")
      {
          $parametro_curEstadoR = "B";
          $parametro_curEstadoC = "B";
          $parametro_curEstadoA = "B";
          $parametro_curEstadoP = "B";
      }
      if ($request->curEstados == "RPCA")
      {
          $parametro_curEstadoB = "R";
      }

      $parametro_progClave = "SEC";
      $parametro_progClaveB = "SEC";
      if ($request->progClave == "TODOS")
      {
          $parametro_progClave = "SEC";
        //   $parametro_progClaveB = "PRB";
      }
      if ($request->progClave == "SEC")
      {
          $parametro_progClave = "SEC";
          $parametro_progClaveB = "SEC";
      }

      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      // Output: 54esmdr0qf
      $temporary_table_name = "_" . substr(str_shuffle($permitted_chars), 0, 15);

              $parametro_NombreArchivo = 'pdf_secundaria_relacion_deudores';
              $result =  DB::select("call procSecundariaColeAlumnosPrograma("
                  .$userId
                  .",".$request->perAnio
                  .",'".$request->ubiClave
                  ."','".$request->depClave
                  ."','".$parametro_progClave
                  ."','".$parametro_progClaveB
                  ."',".$parametro_Semestre_Inicio
                  .",".$parametro_Semestre_Fin
                  .",".$parametro_Semestre_Filtro_Inicio
                  .",".$parametro_Semestre_Filtro_Fin
                  .",'".$request->tipoResumen
                  ."','X"
                  ."','".$parametro_curEstadoR
                  ."','".$parametro_curEstadoA
                  ."','".$parametro_curEstadoC
                  ."','".$parametro_curEstadoP
                  ."','".$parametro_curEstadoB
                  ."','".$temporary_table_name."')");

              $pagos_deudores_array = DB::select('select * from '.$temporary_table_name);
              $pagos_deudores_collection = collect( $pagos_deudores_array );

              //dd($pagos_deudores_collection);
              $parametro_Titulo = "RELACIÓN DE DEUDORES DE ESCUELA: SECUNDARIA";//.$result[0]->_return_escuela;
              $parametro_Mes     = "Pagos registrados hasta el: ". $result[0]->_return_fecha_hasta;
              $parametro_Periodo = "Período: ".$result[0]->_return_periodo_inicio." - ".$result[0]->_return_periodo_fin;

              if ($request->curEstados == "RPCA")
              {
                  $parametro_Periodo = $parametro_Periodo . " | Solo R, P, C y A en el curso";
              }
              if ($request->curEstados == "B")
              {
                  $parametro_Periodo = $parametro_Periodo . " | Solo Bajas en el curso";
              }
              if ($request->curEstados == "X")
              {
                  $parametro_Periodo = $parametro_Periodo . " | Todos los alumnos (incluye bajas)";
              }

              $parametro_Ubicacion = "Ubicación: ".$result[0]->_return_ubicacion." - Nivel: ".$request->depClave;
              DB::statement( 'DROP TABLE IF EXISTS '.$temporary_table_name );




      if($pagos_deudores_collection->isEmpty()) {
        alert()->warning('Sin coincidencias', 'No hay registros que coincidan con la información proporcionada.')->showConfirmButton();
        return back()->withInput();
      }

        // Unix
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $fechaActual = Carbon::now("CDT");
        $horaActual = $fechaActual->format("H:i:s");

        $pdf = PDF::loadView('reportes.pdf.secundaria.relacion_deudores.'. $parametro_NombreArchivo, [
            "pagos" => $pagos_deudores_collection,
            "fechaActual" => $fechaActual->toDateString(),
            "horaActual" => $horaActual,
            "nombreArchivo" => $parametro_NombreArchivo,
            "elTitulo" => $parametro_Titulo,
            "elMes" => $parametro_Mes,
            "laUbicacion" => $parametro_Ubicacion,
            "ubiClave" => $request->ubiClave,
            "depClave" =>$request->depClave,
            "elPeriodo" => $parametro_Periodo,
        ]);


        if($request->tipoResumen == "00")
        {
            $pdf->setPaper('letter', 'portrait');
        }
        else
        {
            $pdf->setPaper('letter', 'landscape');
        }

        //$pdf->setPaper('letter', 'landscape');

        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo.'.pdf');
        return $pdf->download($parametro_NombreArchivo.'.pdf');


    }

}
