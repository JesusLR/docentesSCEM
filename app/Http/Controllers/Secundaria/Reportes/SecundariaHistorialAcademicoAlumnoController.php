<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

use App\Http\Models\Plan;
use App\Http\Models\Curso;
use App\Http\Models\Alumno;
use App\Http\Models\Firmante;
use App\Http\Models\Programa;
use App\Http\Models\Historico;
use App\Http\Models\Secundaria\Secundaria_grupos;
use App\Http\Models\Secundaria\Secundaria_resumenacademico;
use App\Http\Models\Ubicacion;

use DB;
use PDF;
use Validator;
use Carbon\Carbon;

class SecundariaHistorialAcademicoAlumnoController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
    // $this->middleware('permisos:r_plantilla_profesores');
    set_time_limit(8000000);
  }

  public function reporte()
  {
    $anioActual = Carbon::now('America/Merida');
    $programas = Programa::where('progClave','<>','000')->get()->unique('progClave');
    $ubicaciones = Ubicacion::where('ubiClave','<>','000')->get();

    return view('secundaria.reportes.historial_academico_alumno.create', [
      'anioActual' => $anioActual,
      'programas' => $programas,
      'ubicaciones' => $ubicaciones
    ]);

  }

  /**
  * Retorna los resúmenes académicos del alumno.
  */
  private function buscar_resacas($alumno_id, $plan_id = null) {

    return Secundaria_resumenacademico::with('plan.programa')
    ->where('alumno_id', $alumno_id)
    ->whereHas('plan.programa', static function ($query) use ($plan_id) {
      if($plan_id) {
        $query->where('plan_id', $plan_id);
      }
    })->latest('resFechaIngreso')->get();
  } //buscar_resacas.

  /**
  * retorna los planes que ha cursado el alumno
  */
  private function mapear_planes($alumno_id) {

    $resacas = $this->buscar_resacas($alumno_id);

    return $resacas->map(static function($resaca, $key) {
      return collect([
        'plan_id' => $resaca->plan->id,
        'progClave' => $resaca->plan->programa->progClave,
        'planClave' => $resaca->plan->planClave,
        'depClave' => $resaca->plan->programa->escuela->departamento->depClave
      ]);
    })->keyBy('plan_id');
  }// map_resacas_por_planes.


  public function obtenerProgramasClave($aluClave)
  {
    $alumno = Alumno::where('aluClave',$aluClave)->first();
    if(!$alumno) { return json_encode(null); }

    return response()->json($this->mapear_planes($alumno->id));
  }


  public function obtenerProgramasMatricula($aluMatricula)
  {
    $alumno = Alumno::where('aluMatricula',$aluMatricula)->first();
    if(!$alumno) { return json_encode(null); }

    return response()->json($this->mapear_planes($resacas));
  }




  public function imprimir(Request $request)
  {

    $validator = Validator::make($request->all(),[
      'aluClave' =>'required_without:aluMatricula',
      'aluMatricula'=>'required_without:aluClave',
      'plan_id' => 'required',
    ],
    [
      'aluClave.required_without'=>'El campo Clave del alumno es obligatorio cuando la Matrícula del alumno no está presente',
      'aluMatricula.required_without'=>'El campo Matrícula del alumno es obligatorio cuando la Clave del alumno no está presente',
      'plan_id.required' => 'Es necesario que proporcione un plan.',
    ]);

    if ($validator->fails()) {
      return redirect ('reporte/secundaria_historial_alumno')->withErrors($validator)->withInput();
    }

    $plan = Plan::with('programa.escuela.departamento.periodoActual')->findOrFail($request->plan_id);
    $programa = $plan->programa;
    $escuela = $programa->escuela;
    $departamento = $escuela->departamento;
    $periodo = $departamento->periodoActual;
    $ubicacion = $departamento->ubicacion;

    $historial = Historico::with(['alumno.persona','secundaria_materia.plan', 'periodo'])

      ->whereHas('alumno.persona', function($query) use ($request) {
        if ($request->aluClave) {
          $query->where('aluClave', '=', $request->aluClave);//
        }
        if ($request->aluMatricula) {
          $query->where('aluMatricula', '=', $request->aluMatricula);//
        }
      })
      ->whereHas('secundaria_materia.plan', function($query) use ($request) {
        if ($request->plan_id) {
          $query->where('plan_id', '=', $request->plan_id);//
        }
      })
      ->whereHas('periodo', static function($query) use ($periodo) {
        $query->whereDate('perFechaInicial', '<=', $periodo->perFechaInicial);
      })
      ->latest()->get();

    $historialFirst = $historial->first();

    if(!$historial->first()){
      alert()->warning('Escuela Modelo','No se encuentran datos con la información proporcionada.
      Favor de verificar.')->showConfirmButton();
      return back()->withInput();
    }

    $ultimoCurso = $historialFirst->alumno->cursos()
      ->whereHas('periodo', static function($query) use ($periodo) {
        $query->whereDate('perFechaInicial', '<=', $periodo->perFechaInicial);
      })
      // ->where("curEstado", "=", "R")
      ->latest("curFechaRegistro")
    ->first();

    $historialA = collect();
    $fechaActual = Carbon::now('America/Merida');


    //variables que se mandan a la vista fuera del array
    $alumnoNombre = $historialFirst->alumno;
    $personaNombre = $historialFirst->alumno->persona;

    $grupoNom = Secundaria_grupos::select('gpoGrado','gpoClave')->where('secundaria_materia_id',$historialFirst->materia_id)
      ->where('plan_id',$historialFirst->plan_id)->where('periodo_id',$historialFirst->periodo_id)
    ->first();


    $resumenAcademico = $this->buscar_resacas($historialFirst->alumno_id, $historialFirst->plan_id)->first();


    $fechaIngreso = Carbon::parse($resumenAcademico->resFechaIngreso)->format("d-m-Y");



    foreach ($historial  as $key => $historico){
      $grupo = Secundaria_grupos::where('secundaria_materia_id','=',$historico->materia_id)->where('plan_id','=',$historico->plan_id)->where('periodo_id','=',$historico->periodo_id)->first();
      $curso = Curso::where('alumno_id','=',$historico->alumno_id)->where('periodo_id','=',$historico->periodo_id)->first();
      if ($curso) {
        $cgt = $curso->cgt;
        $curTipoIngreso = $curso->curTipoIngreso;
        $cgtGradoSemestre = $cgt->cgtGradoSemestre;
      }else{
        $curTipoIngreso = '';
        $cgtGradoSemestre = '';
      }

      $matClave = $historico->secundaria_materia->matClave;
      $matNombre = $historico->secundaria_materia->matNombre;
      $matCreditos = $historico->secundaria_materia->matCreditos;
      $periodoInicial = $historico->periodo->perFechaInicial;
      $periodoFinal = $historico->periodo->perFechaFinal;
      $calificacion = $historico->histCalificacion;
      $fechaMat = $historico->histFechaExamen;
      $matTipoAcreditacion = $historico->secundaria_materia->matTipoAcreditacion;
      $depCalMinAprob = $historico->plan->programa->escuela->departamento->depCalMinAprob;

      $calificacionName = '';
      $calificacionNumerica = $calificacion;
      if ($matTipoAcreditacion == 'A') {
        if ($calificacion == 0)
        {
          $calificacion = 'Apr';
        }elseif ($calificacion == 1)
        {
          $calificacion = 'No Apr';
        }
      }

      if ($calificacion == -1) {
        $calificacion = 0;
      }

      $historialA->push((Object)[
        'historico'=>$historico,
        'periodo_id' => $historico->periodo_id,
        'curTipoIngreso'=>$curTipoIngreso,
        'cgtGradoSemestre'=>$cgtGradoSemestre,
        'matClave'=>$matClave,
        'matNombre'=>$matNombre,
        'matCreditos'=>$matCreditos,
        'periodoInicial'=>$periodoInicial,
        'periodoFinal'=>$periodoFinal,
        'fechaMat'=>$fechaMat,
        'matClaveFecha'=>$matClave.$fechaMat,
        'calificacion'=>"$calificacion",
        'depCalMinAprob'=>$depCalMinAprob,
        'grupo'=>$grupo,
        'ordenar' =>$periodoInicial,
        'matTipoAcreditacion' => $matTipoAcreditacion,
        'calificacionNumerica' => $calificacionNumerica
      ]);
    }

    $historialA = $historialA->sortBy('matClaveFecha')->groupBy('periodoInicial')->sortKeys();


    $firmante = Firmante::where("id", "=", $request->firmante)->first();

    $nombreArchivo = 'pdf_historial_alumno';
    return PDF::loadView('reportes.pdf.secundaria.historial_alumno.'. $nombreArchivo, [
      "historialA" => $historialA,
      "fechaActual" => $fechaActual->toDateString(),
      "horaActual" => $fechaActual->toTimeString(),
      "historialFirst" => $historialFirst,
      "nombreArchivo" => $nombreArchivo,
      "ubicacionNombre" => $ubicacion,
      "programaNombre" => $programa,
      "credPlan" => $plan,
      "alumnoNombre" => $alumnoNombre,
      "personaNombre" => $personaNombre,
      "grupoNom" => $grupoNom,
      "resumenAcademico" =>$resumenAcademico,
      'ultimoCurso'     => $ultimoCurso,
      "fechaIngreso" =>$fechaIngreso,
      "perAnio" => $request->perAnio,
      "firmante" => $firmante
    ])->stream($nombreArchivo.'.pdf');

  }

}
