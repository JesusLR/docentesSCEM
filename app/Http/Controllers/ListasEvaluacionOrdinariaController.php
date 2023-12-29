<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Inscrito;

use Carbon\Carbon;

use PDF;
use DB;

class ListasEvaluacionOrdinariaController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('permisos:r_plantilla_profesores');
    set_time_limit(8000000);
  }

  public function reporte()
  {
    return View('reportes/listas_evaluacion_ordinaria.create');
  }

  
  public function listasEvaluacionOrdinaria($request)
  {
    // dd($request->all());
    $grupos = Grupo::with('periodo', 'plan.programa.escuela.departamento.ubicacion', 'plan.programa.escuela.empleado.persona',
      'periodo', 'empleado.persona', 'materia')

      ->where("id", "=", $request->grupo_id);

    //   ->whereHas('plan.programa.escuela', function($query) use ($request) {
    //     if ($request->escClave) {
    //       $query->where('escClave', '=', $request->escClave);//
    //     }
    //   })

    //   ->whereHas('periodo', function($query) use ($request) {
    //     if ($request->perNumero) {
    //       $query->where('perNumero', '=', $request->perNumero);//
    //     }
    //     if ($request->perAnio) {
    //       $query->where('perAnio', '=', $request->perAnio);//
    //     }
    //   })
    //   ->whereHas('plan.programa.escuela.departamento.ubicacion', function($query) use ($request) {
    //     if ($request->ubiClave) {
    //       $query->where('ubiClave', '=', $request->ubiClave);//
    //     }
    //     if ($request->depClave) {
    //       $query->where('depClave','=',  $request->depClave);//
    //     }
    //     if ($request->progClave) {
    //       $query->where('progClave','=', $request->progClave);//
    //     }
    //     if ($request->planClave) {
    //       $query->where('planClave', '=', $request->planClave);//
    //     }

    //   })
    //   ->whereHas('materia', function($query) use ($request) {
    //     if ($request->matClave) {
    //       $query->where('matClave', '=', $request->matClave);//
    //     }
    //   });



    // if ($request->gpoSemestre) {
    //   $grupos = $grupos->where('gpoSemestre', '=', $request->gpoSemestre);//
    // }
    // if ($request->cgtGrupo) {
    //   $grupos = $grupos->where('gpoClave', '=', $request->cgtGrupo);//
    // }
    // if ($request->empleado_id) {
    //   $grupos = $grupos->where("empleado_id", "=", $request->empleado_id);//
    // }

    $grupos = $grupos->get();



    //obtener inscritos por cada grupo (grupo_id)
    $grupoIds = ($grupos)->map(function ($item, $key) {
      return $item["id"];
    })->all();


    // dd($grupos);

    //obtener escolaridad de los directores 
    // plan.programa.escuela.empleado.persona
    $directorIds = $grupos->map(function ($item, $key) {
      return $item->plan->programa->escuela->empleado->id;
    })->unique()->all();

    // dd($directorIds);

    $escolaridad = DB::table("escolaridad")->whereIn("empleado_id", $directorIds) // $directorIds
      ->leftJoin('abreviaturastitulos', 'escolaridad.abreviaturaTitulo_id', '=', 'abreviaturastitulos.id')
      ->where('escolaridad.escoUltimoGrado', '=', 'S')->get();


    $grupos = $grupos->map(function ($item, $key) use ($escolaridad) {
      $dirEscolaridad = $escolaridad->filter(function ($value, $key) use ($item) {
        return $value->empleado_id == $item->plan->programa->escuela->empleado->id;
      })->first();

      if ($dirEscolaridad) {
        $item->escolaridadDirector  = $dirEscolaridad->abtAbreviatura;
      } else {
        $item->escolaridadDirector = "";
      }

      return $item;
    });


    //meter columna de inscritos a grupos
    $inscritos = Inscrito::whereIn("grupo_id", $grupoIds)
      ->leftJoin("calificaciones", "inscritos.id", "=", "calificaciones.inscrito_id")
      ->leftJoin("cursos", "inscritos.curso_id", "=", "cursos.id")
      ->leftJoin("alumnos", "cursos.alumno_id", "=", "alumnos.id")
      ->leftJoin("personas", "alumnos.persona_id", "=", "personas.id")
      ->where("cursos.curEstado", "<>", "B")
    ->get(); // tiene repetidos los id de alumnos


    //SORTBY APELLIDOS NOMBRE
    $inscritos = $inscritos->map(function ($item, $key) {
      $item->sortByApellidoNombre = str_slug($item->perApellido1 . "-" . $item->perApellido2 . "-" . $item->perNombre, '-');
      return $item;
    })->sortBy("sortByApellidoNombre")->groupBy("grupo_id");


    $grupos = ($grupos)->map(function ($item, $key) use ($inscritos) {

      //sortBy materia grupo semestre
      $item->sortByMateriaGrupoSemestre = str_slug($item->materia->matClave . "-" . $item->gpoClave . '-' . $item->gpoSemestre, '-');


      //meter columna de inscritos
      $grupoId = $item->id;
      $inscritosGpo = $inscritos->filter(function ($value, $key) use ($grupoId) {
        return $key == $grupoId;
      })->first();

      if ($inscritosGpo) {      
        $item->inscritos = $inscritosGpo->all();
      } else {
        $item->inscritos = [];
      }

      return $item;
    });


    if ($request->numAlumnos) {
      $grupos = $grupos->filter(function ($grupo, $key) use ($request) {

        if ($request->filtroNumAlumnos == "mayor") {
          return count($grupo->inscritos) >= $request->numAlumnos;
        }
        if ($request->filtroNumAlumnos == "menor") {
          return count($grupo->inscritos) <= $request->numAlumnos;
        }
        if ($request->filtroNumAlumnos == "igual" || !$request->filtroNumAlumnos) {
          return count($grupo->inscritos) == $request->numAlumnos;
        }
      });
    }


    return collect($grupos)->sortBy("sortByMateriaGrupoSemestre");
  }


  public function imprimir(Request $request)
  {
    $grupos = $this->listasEvaluacionOrdinaria($request);


    if (!$grupos->first()) {
      alert()->error('Error', 'No se encontraron resultados')->showConfirmButton()->autoClose(2000);
      return redirect()->back()->withInput();
    }


    $fechaActual = Carbon::now();

    // Unix
    setlocale(LC_TIME, 'es_ES.UTF-8');
    // En windows
    setlocale(LC_TIME, 'spanish');

    $escuelaPorcentajeExamenOrdinario = 30;
    $escuelaPorcentajeExamenParcial   = 70;

    if ($grupos->first()->plan->programa->escuela->escPorcExaPar) {
      $escuelaPorcentajeExamenParcial = $grupos->first()->plan->programa->escuela->escPorcExaPar;

    }
    if ($grupos->first()->plan->programa->escuela->escPorcExaOrd) {
      $escuelaPorcentajeExamenOrdinario = $grupos->first()->plan->programa->escuela->escPorcExaOrd;
    }


    if ($grupos->first()->materia->matPorcentajeParcial) {
      $escuelaPorcentajeExamenParcial = $grupos->first()->materia->matPorcentajeParcial;
    }
    if ($grupos->first()->materia->matPorcentajeOrdinario) {
      $escuelaPorcentajeExamenOrdinario = $grupos->first()->materia->matPorcentajeOrdinario;
    }



    $nombreArchivo = 'pdf_listas_evaluacion_ordinaria';
    $pdf = PDF::loadView('pdf.'. $nombreArchivo, [
      "grupos" => $grupos,
      "nombreArchivo" => $nombreArchivo,
      "curEstado" => $request->curEstado,
      "escuelaPorcentajeExamenParcial" => $escuelaPorcentajeExamenParcial,
      "escuelaPorcentajeExamenOrdinario" => $escuelaPorcentajeExamenOrdinario,

      "fechaActual" => $fechaActual->toDateString(),
      "horaActual" => $fechaActual->toTimeString()
    ]);


    $pdf->setPaper('letter', 'portrait');
    $pdf->defaultFont = 'Times Sans Serif';
    return $pdf->stream($nombreArchivo . '.pdf');
    return $pdf->download($nombreArchivo . '.pdf');

    // dd($curso);
    // return response()->json($curso);
  }
}