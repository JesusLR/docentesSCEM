<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ubicacion;
use App\Models\Horario;
use App\Models\Paquete_detalle;
use App\Models\Secundaria\Secundaria_empleados;
use App\Models\Secundaria\Secundaria_inscritos;
use Carbon\Carbon;
use PDF;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class SecundariaGrupoSemestreController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
    // $this->middleware('permisos:r_grupo_semestre');
  }

  public function reporte()
  {

    return view('secundaria.reportes.grupo_semestre.create', [
      "ubicaciones"     => Ubicacion::where('ubiClave', '<>', '000')->get(),
      "empleados"     => Secundaria_empleados::where('id', '<>', '0')->get()
    ]);
  }



  public function horariosGrupoSemestre($request)
  {
    $grupos = Horario::with(['secundaria_grupo.secundaria_materia.plan.programa.escuela', 'aula', 'secundaria_grupo.secundaria_empleado'])
      ->whereHas('secundaria_grupo.secundaria_materia.plan', function($query) use ($request) {
        $query->where('periodo_id', $request->periodo_id);
        $query->where('plan_id', $request->plan_id);
        if ($request->gpoSemestre) {
          $query->where('gpoGrado', '=', $request->gpoSemestre);
        }
        if ($request->gpoClavegpoClave) {
          $query->where('gpoClave', '=', $request->gpoClavegpoClave);
        }
        if ($request->empleado_id) {
          $query->where("empleado_id_docente", "=", $request->empleado_id);
        }
        if ($request->materia_id) {
          $query->where('secundaria_materia_id', '=', $request->materia_id);
        }
      })->get();

    if($grupos->isEmpty()) {
      return false;
    }

    $inscritosByGrupoIds = Secundaria_inscritos::whereIn('grupo_id', $grupos->pluck('id'))->get();

    //hacer merge de cantidad de alumnos a grupos
    $grupos = $grupos->map(function ($item, $key) use ($inscritosByGrupoIds) {
      
      $item["sortGrupoClaveMat"] = str_slug($item["secundaria_grupo"]["gpoClave"]
      .'-'.$item["secundaria_grupo"]["secundaria_materia"]["matClave"], '-');

      $cantidadAlumnos = $inscritosByGrupoIds->filter(function ($value, $key) use ($item) {
        return $value->grupo_id == $item["id"];
      })->count();

      $item["cantidadAlumnos"] = $cantidadAlumnos;

      return $item;
    });

    return collect($grupos)->sortBy("secundaria_grupo.gpoGrado")->groupBy("secundaria_grupo.gpoGrado");
  }


  public function horariosGrupoSemestrePaquetes(Request $request)
  {
    $grupos = Paquete_detalle::with('paquete', 'secundaria_grupo.secundaria_materia.plan.programa.escuela','secundaria_grupo.secundaria_empleado')
      ->whereHas('paquete', static function($query) use ($request) {
        $query->where('periodo_id', $request->periodo_id);
        $query->where('plan_id', $request->plan_id);
      })
      ->whereHas('secundaria_grupo.secundaria_materia.plan', function($query) use ($request) {
        if ($request->gpoSemestre) {
          $query->where('gpoGrado', '=', $request->gpoSemestre);
        }
        if ($request->gpoClave) {
          $query->where('gpoClave', '=', $request->gpoClave);
        }
        if ($request->empleado_id) {
          $query->where("empleado_id_docente", "=", $request->empleado_id);
        }
        if ($request->materia_id) {
          $query->where('secundaria_materia_id', '=', $request->materia_id);
        }
      })->get();

    if($grupos->isEmpty()) {
      return false;
    }

    $inscritosByGrupoIds = Secundaria_inscritos::whereIn('grupo_id', $grupos->pluck('grupo_id'))->get();
    $horariosByGrupoIds = Horario::whereIn('grupo_id', $grupos->pluck('grupo_id'))
      ->leftJoin("aulas", "horarios.aula_id", "=", "aulas.id")
      ->get();

    //hacer merge de cantidad de alumnos a grupos
    $grupos = $grupos->map(function ($item, $key) use ($inscritosByGrupoIds, $horariosByGrupoIds) {

      $item["sortGrupoClaveMat"] = str_slug($item["secundaria_grupo"]["gpoClave"]
      .'-'.$item["secundaria_grupo"]["secundaria_materia"]["matClave"], '-');

      $cantidadAlumnos = $inscritosByGrupoIds->filter(function ($value, $key) use ($item) {
        return $value->grupo_id == $item["id"];
      })->count();
    
      $horarios = $horariosByGrupoIds->filter(function ($value, $key) use ($item) {
        return $value->grupo_id == $item["grupo_id"];
      });


      $item["cantidadAlumnos"] = $cantidadAlumnos;
      $item["horarios"] = $horarios;

      return $item;
    });


    return collect($grupos)->sortBy("secundaria_grupo.gpoGrado")->groupBy("paquete_id");
  }



  public function imprimir(Request $request)
  {
    if ($request->tipoReporte == "gradoMateria") {
      $grupos = $this->horariosGrupoSemestre($request);
    }

    if ($request->tipoReporte == "paquete") {
      $grupos = $this->horariosGrupoSemestrePaquetes($request);
    }

    if(!$grupos) {
      alert()->warning('Sin datos', 'No hay registros que coincidan con la información proporcionada. Favor de verificar')->showConfirmButton();
      return back()->withInput();
    }


    $fechaActual = Carbon::now('America/Merida');

    if ($request->tipoReporte == "gradoMateria" && $request->tipoGradoMateria == "horarios") {
      $nombreArchivo = 'pdf_grupo_grado';
    }
    if ($request->tipoReporte == "gradoMateria" && $request->tipoGradoMateria == "maestros") {
      $nombreArchivo = 'pdf_grupo_grado_maestros';
    }
    // if ($request->tipoReporte == "paquete") {
    //   $nombreArchivo = 'pdf_grupo_semestre_paquete';
    // }

    $pdf = PDF::loadView('reportes.pdf.secundaria.relacion_grupo_materias.'.$nombreArchivo, [
      "grupos" => $grupos,
      "nombreArchivo" => $nombreArchivo,
      "fechaActual" => $fechaActual->toDateString(),
      "horaActual" => $fechaActual->toTimeString(),
    ]);

    return $pdf->stream($nombreArchivo.'.pdf');
  }
}