<?php

namespace App\Http\Controllers\Primaria\Reportes;

use App\clases\alumnos\MetodosAlumnos;
use App\clases\cgts\MetodosCgt;
use App\clases\personas\MetodosPersonas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Beca;
use App\Models\Calificacion;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Primaria\Primaria_inscrito;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaAlumnosBecadosController extends Controller
{

    protected $alumnos;
    protected $perAnioPago;
  
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');

      $this->alumnos = new Collection();
    }
  
    public function reporte()
    {
      $tiposBeca = Beca::get();
      $estadosCurso = ESTADO_CURSO;
  
      return view('primaria.reportes.alumnos_becados.create', [
        'tiposBeca' => $tiposBeca,
        'estadosCurso' => $estadosCurso,
        'anioActual' => Carbon::now('America/Merida')->year,
        'ubicaciones' => Ubicacion::sedes()->get()
      ]);
      
    }
  
  
    public function imprimir(Request $request)
    {
      $this->perAnioPago = $request->perAnioPago;
      $this->buscarDesdeRequest($request);
  
      if($this->alumnos->isEmpty()) {
        alert('Sin coincidencias', 'No hay datos que coincidan con la información proporcionada.', 'warning')->showConfirmButton();
        return back()->withInput();
      }
  
      $this->alumnos = $this->alumnos->keyBy('alumno_id');
      # ---------------------------------------------------------------------------------
      if($request->validar_hermanos) {
        $this->buscarPosiblesHermanos($this->alumnos);
        $this->filtrar_solo_hermanos();
      }
  
      if($this->alumnos->isEmpty()) {
        alert('Sin coincidencias', 'No hay datos que coincidan con la información proporcionada.', 'warning')->showConfirmButton();
        return back()->withInput();
      }
      # ---------------------------------------------------------------------------------
      $cursos_ids = $this->obtener_cursos_ids($request->promedio_de_curso);
      $promedios = $this->buscar_promedios($cursos_ids);
      $this->agregarPromedioPorAlumno($promedios);
      $this->definir_orden_agrupacion($request->validar_hermanos);
  
      $nombreArchivo = $request->tipoReporte == 'N' ? 'pdf_alumnos_becados' : 'pdf_alumnos_becados_firmas';
      $fechaActual = Carbon::now('America/Merida');
  
      return PDF::loadView('reportes.pdf.primaria.alumnos_becados.' . $nombreArchivo, [
        "datos" => $this->alumnos,
        "nombreArchivo" => $nombreArchivo,
        "tiposBecas" => $request->curTipoBeca ? Beca::where('bcaClave', $request->curTipoBeca)->get() : Beca::all(),
        "departamento" => Departamento::find($request->departamento_id),
        "cicloEscolar" => $this->perAnioPago.'-'.($this->perAnioPago + 1),
        "fechaActual" => $fechaActual->format('d/m/Y'),
        "horaActual" => $fechaActual->format('H:i:s'),
      ])->stream($nombreArchivo.'.pdf');
    } #imprimir.
  
  
  
    /**
    * @param Illuminate\Http\Request
    */
    private function buscarDesdeRequest($request)
    {
      Curso::with(['alumno.persona', 'cgt.plan.programa'])
      ->whereHas('periodo.departamento', static function($query) use ($request) {
        $query->where('perAnioPago', $request->perAnioPago)
        ->where('perNumero', 0)
        ->where('departamento_id', $request->departamento_id)
        ->where('ubicacion_id', $request->ubicacion_id);
      })
      ->whereHas('cgt.plan.programa', static function($query) use ($request) {
        if($request->cgtGradoSemestre) {
          $query->where('cgtGradoSemestre', $request->cgtGradoSemestre);
        }
        if($request->cgtGrupo) {
          $query->where('cgtGrupo', $request->cgtGrupo);
        }
        if($request->programa_id) {
          $query->where('programa_id', $request->programa_id);
        }
        if($request->escuela_id) {
          $query->where('escuela_id', $request->escuela_id);
        }
      })
      ->whereHas('alumno.persona', static function($query) use ($request) {
        if($request->aluMatricula) {
          $query->where('aluMatricula', $request->aluMatricula);
        }
        if($request->aluClave) {
          $query->where('aluClave', $request->aluClave);
        }
        if($request->perApellido1) {
          $query->where('perApellido1', 'like', "%{$request->perApellido1}%");
        }
        if($request->perApellido2) {
          $query->where('perApellido2', 'like', "%{$request->perApellido2}%");
        }
        if($request->perNombre) {
          $query->where('perNombre', 'like', "%{$request->perNombre}%");
        }
      })
      ->where(static function($query) use ($request) {
        $query->whereNotNull('curTipoBeca');
        if($request->curTipoBeca) {
          $query->where('curTipoBeca', $request->curTipoBeca);
        }
        if($request->curPorcentajeBeca) {
          $query->where('curPorcentajeBeca', $request->curPorcentajeBeca);
        }
        if($request->curEstado) {
          if($request->curEstado == 'T') $query->where('curEstado', '<>', 'B');
          if($request->curEstado == 'RCA') $query->whereIn('curEstado', ['R', 'C', 'A']);
          if(!in_array($request->curEstado, ['T', 'RCA'])) $query->where('curEstado', $request->curEstado);
        }
        if($request->curFechaRegistro) {
          $query->where('curFechaRegistro', $request->curFechaRegistro);
        }
        if($request->curObservacionesBeca) {
          $query->where('curObservacionesBeca', 'like', "%{$request->curObservacionesBeca}%");
        }
      })
      ->oldest('curFechaRegistro')
      ->chunk(100, function($cursos) {
  
        if($cursos->isEmpty()) return false;
        $this->mapear_cursos($cursos);
  
      });
    }#filtrarDesdeRequest.
  
  
    /**
    * @param Collection
    */
    private function buscarPosiblesHermanos($alumnos)
    {
      $apellidos_combinaciones = $alumnos->pluck('apellidos_filtrados')->flatten();
  
      Curso::with(['alumno.persona', 'cgt.plan.programa'])
      ->whereHas('periodo', function($query) {
        $query->where('perAnioPago', $this->perAnioPago)
        ->where('perNumero', 0);
      })
      ->whereHas('alumno.persona', static function($query) use ($apellidos_combinaciones) {
        $sql = DB::raw("CONCAT(perApellido1,' ',perApellido2)");
        $query->whereIn($sql, $apellidos_combinaciones);
      })
      ->whereNotIn('alumno_id', $this->alumnos->pluck('alumno_id'))
      ->oldest('curFechaRegistro')
      ->chunk(100, function($cursos) {
  
        if($cursos->isEmpty()) return false;
        $this->mapear_cursos($cursos);
  
      });
    }
  
  
    /**
    * @param Collection
    */
    private function mapear_cursos($cursos) 
    {
      $cursos->each(function($curso) {
        $alumno = self::obtener_info_alumno($curso);
        $this->alumnos->push($alumno);
      });
    }
  
  
    /**
    * @param App\Models\Curso
    */
    private static function obtener_info_alumno($curso)
    {
      $cgt = $curso->cgt;
      $programa = $cgt->plan->programa;
      $alumno = $curso->alumno;
      $persona = $alumno->persona;
  
      return collect([
        'curso_id' => $curso->id,
        'alumno_id' => $alumno->id,
        'aluClave' => $alumno->aluClave,
        'nombreCompleto' => MetodosPersonas::nombreCompleto($persona, true),
        'apellidos_filtrados' => MetodosAlumnos::filtrarApellidos($persona)->unique(),
        'progClave' => $programa->progClave,
        'grado' => $cgt->cgtGradoSemestre,
        'grupo' => $cgt->cgtGrupo,
        'curEstado' => $curso->curEstado,
        'curTipoBeca' => $curso->curTipoBeca,
        'curPorcentajeBeca' => $curso->curPorcentajeBeca ? $curso->curPorcentajeBeca.'%' : '',
        'curObservacionesBeca' => $curso->curObservacionesBeca,
        'programa_grado_grupo' => $programa->progClave.'-'.MetodosCgt::stringOrden($cgt->cgtGradoSemestre, $cgt->cgtGrupo),
      ]);
    }
  
  
    /**
    * - Solo deja alumnos que tengan posibles hermanos
    */
    private function filtrar_solo_hermanos()
    {
      $this->alumnos = $this->alumnos->groupBy(static function($alumno) {
        return $alumno['apellidos_filtrados'][0]; #apellidos sin tildes.
      })->filter(static function($coincidencias_apellidos) {
        return $coincidencias_apellidos->count() > 1;
      })->flatten(1)->keyBy('alumno_id');
    }
  
  
  
    /**
    * @param string
    */
    private function obtener_cursos_ids($promedio_de_curso = null)
    {
      return $promedio_de_curso 
          ? $this->alumnos->pluck('curso_id') 
          : self::buscarCursosAnteriores($this->alumnos, $this->perAnioPago - 1);
    }
  
  
  
    /**
    * @param Collection
    */
    private function buscarCursosAnteriores($alumnos, $perAnioPago)
    {
      return Curso::whereIn('alumno_id', $alumnos->pluck('alumno_id'))
      ->whereHas('periodo', static function($query) use ($perAnioPago) {
        $query->where('perNumero', 0)
        ->where('perAnioPago', $perAnioPago);
      })
      ->oldest('curFechaRegistro')->pluck('id', 'alumno_id');
    }
  
  
    /**
    * @param array
    */
    private function buscar_promedios($cursos_ids)
    {
      return Primaria_inscrito::with('curso')
      ->whereHas('curso', static function($query) use ($cursos_ids) {
        $query->whereIn('curso_id', $cursos_ids);
      })
      ->whereHas('primaria_grupo.primaria_materia', static function($query) {
        $query->where('matTipoAcreditacion', 'N');
      })->get()->pluck('incsPromedioMes', 'primaria_inscrito.curso.alumno_id');
    }
  
  
    /**
    * @param array
    */
    private function agregarPromedioPorAlumno($promedios)
    {
      $this->alumnos->each(static function($alumno, $alumno_id) use ($promedios) {
        $promedio = $promedios->pull($alumno_id);
        $alumno->put('promedio', $promedio);
      });
    }
  
  
    /**
    * @param string
    */
    private function definir_orden_agrupacion($validar_hermanos = null)
    {
      $this->alumnos = $this->alumnos->sortBy(static function($alumno) use ($validar_hermanos) {
        return $validar_hermanos ? $alumno['apellidos_filtrados'][0] : $alumno['programa_grado_grupo'];
      })->groupBy(static function($alumno) use ($validar_hermanos) {
        return $validar_hermanos ? $alumno['apellidos_filtrados'][0] : $alumno['programa_grado_grupo'];
      })->sortKeys();
    }
  
  
  }
