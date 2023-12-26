<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Carbon\Carbon;
use App\Http\Models\Grupo;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Http\Models\Inscrito;
use App\Http\Controllers\Controller;

class ListasEvaluacionParcialController extends Controller
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
  }

  
  
  public function listasEvaluacionParcial(Request $request)
  {
    $grupos = Grupo::with('periodo', 'plan.programa.escuela.departamento.ubicacion', 'plan.programa.escuela.empleado.persona',
      'periodo', 'empleado.persona', 'materia')->where("id", "=", $request->grupo_id)->get();



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
      ->get();// tiene repetidos los id de alumnos


    $inscritos = $inscritos->map(function ($item, $key) {
      $alumno = $item->curso->alumno->persona->perApellido1 . "-" . 
          $item->curso->alumno->persona->perApellido2  . "-" . 
          $item->curso->alumno->persona->perNombre;

      $item->sortByNombres = Str::slug($alumno, "-");

      return $item;
    });



    $inscritos = $inscritos->sortBy("sortByNombres")->groupBy("grupo_id");



    $grupos = ($grupos)->map(function ($item, $key) use ($inscritos) {

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

    

    return collect($grupos)->where("inscritos_gpo", ">", 0)->sortBy("gpoSemestre");
  }


  public function imprimir(Request $request)
  {
    $grupos = $this->listasEvaluacionParcial($request);

    $fechaActual = Carbon::now();

    // Unix
    setlocale(LC_TIME, 'es_ES.UTF-8');
    // En windows
    setlocale(LC_TIME, 'spanish');



    $nombreArchivo = 'pdf_listas_evaluacion_parcial';
    $pdf = PDF::loadView('pdf.'. $nombreArchivo, [
      "grupos" => $grupos,
      "nombreArchivo" => $nombreArchivo,
      "curEstado" => $request->curEstado,
      "fechaActual" => $fechaActual->toDateString(),
      "horaActual" => $fechaActual->toTimeString(),
    ]);


    $pdf->setPaper('letter', 'portrait');
    $pdf->defaultFont = 'Times Sans Serif';
    return $pdf->stream($nombreArchivo . '.pdf');
    return $pdf->download($nombreArchivo . '.pdf');

    // dd($curso);
    // return response()->json($curso);
  }
}