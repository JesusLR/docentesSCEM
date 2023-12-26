<?php

namespace App\Http\Controllers\Primaria\Reportes;

use App\clases\personas\MetodosPersonas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Departamento;
use App\Http\Models\Primaria\Primaria_grupo;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaRelacionMaestrosACDController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function reporte()
    {
      return view('primaria.reportes.relacion_grupos_ACD.create', [
        'ubicaciones' => Ubicacion::sedes()->get(),
      ]);

    }


    public function relacionMaestrosGenNombre($request)
    {
      $grupos = self::buscarGrupos($request);

      if($grupos->isEmpty()) {
        return false;
      }



      return $grupos->map(static function($grupo) {
        $grupo['PrimariaNombreCompleto'] = MetodosPersonas::PrimariaNombreCompleto($grupo->primaria_empleado, true);
        $grupo['gpoGradoProgClave'] = $grupo->gpoGrado .'-'. $grupo->plan->programa->progClave;
        $grupo['progClave'] = $grupo->plan->programa->progClave;
        $grupo['planClave'] = $grupo->plan->planClave;
        return $grupo;
      })->sortBy('PrimariaNombreCompleto')
          ->groupBy('empleado_id_docente')
          ;
    }


    public function relacionMaestrosEscuelaSemestre($request)
    {
      $grupos = self::buscarGrupos($request);
      if($grupos->isEmpty()) {
        return false;
      }

      return $grupos->map(static function($grupo) {
        $nombreCompleto = MetodosPersonas::PrimariaNombreCompleto($grupo->primaria_empleado, true);
        $escuela = $grupo->plan->programa->escuela;
        $grupo['nombreCompleto'] = $nombreCompleto;
        $grupo['escuela_nombreCompleto'] = "{$escuela->escClave}-{$nombreCompleto}";

        return $grupo;
      })->sortBy('escuela_nombreCompleto')->groupBy('plan.programa.progClave');
    }



    public function imprimir(Request $request)
    {
      $nombreArchivo = "pdf_rel_maestro_gral_nombre";
      $departamento = Departamento::with('ubicacion')->findOrFail($request->departamento_id);
      $grupos = collect();

      $empEstado = $request->empEstado;

      if ($request->tipoPdf == "G") {//GENERAL POR NOMBRE
        $nombreArchivo = "pdf_rel_maestro_gral_nombre";
        $grupos = $this->relacionMaestrosGenNombre($request);
      }


      if(!$grupos) {
        alert()->warning('Sin coincidencias', 'No hay registros que coincidan con la información proporcionada. Favor de verificar')->showConfirmButton();
        return back()->withInput();
      }

      $fechaActual = Carbon::now('America/Merida');
      $pdf = PDF::loadView('reportes.pdf.primaria.relacion_grupos_ACD.' . $nombreArchivo, [
        "grupos" => $grupos,
        "ubicacion" => $departamento->ubicacion,
        "nombreArchivo" => $nombreArchivo . ".pdf",
        "curEstado" => $request->curEstado,
        "fechaActual" => $fechaActual->format('d/m/Y'),
        "horaActual" => $fechaActual->format('H:i:s'),
        "tipoEspacio" => $request->tipoEspacio,
        "empEstado" => $empEstado
      ]);
      $pdf->setPaper('letter', 'landscape');

      return $pdf->stream($nombreArchivo.'.pdf');
    }

    /**
    * @param Illuminate\Http\Request
    */
    private static function buscarGrupos($request) {

      return Primaria_grupo::with(['primaria_empleado', 'plan.programa.escuela', 'periodo', 'primaria_inscritos.curso.cgt'])
      ->whereNotNull('gpoMatComplementaria')
      ->whereHas('plan.programa.escuela', static function($query) use ($request) {
        $query->where('departamento_id', $request->departamento_id);
        if($request->plan_id)
          $query->where('plan_id', $request->plan_id);
        if($request->programa_id)
          $query->where('programa_id', $request->programa_id);
        if($request->escuela_id)
          $query->where('escuela_id', $request->escuela_id);
      })
      ->whereHas('primaria_empleado', static function($query) use ($request) {
        if($request->empEstado && $request->empEstado != 'T')
          $query->where('empEstado', $request->empEstado);
        if($request->perApellido1)
          $query->where('empApellido1', $request->perApellido1);
        if($request->perApellido2)
          $query->where('empApellido2', $request->perApellido2);
        if($request->perNombre)
          $query->where('empNombre', $request->perNombre);
      })
      ->whereHas('primaria_inscritos.curso.cgt',static function($query) use ($request) {
        if($request->periodo_id)
          $query->where('periodo_id', $request->periodo_id);
        if($request->gpoSemestre)
          $query->where('cgtGradoSemestre', $request->gpoSemestre);
        if($request->empleado_id)
          $query->where('empleado_id_docente', $request->empleado_id);
      })
      ->get();
    }
  }
