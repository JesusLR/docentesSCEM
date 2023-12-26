<?php

namespace App\Http\Controllers\Primaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Primaria\Primaria_inscrito;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use PhpParser\Node\Stmt\Else_;
use Auth;

class PrimariaCalificacionPorMateriaController extends Controller
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
    public function reporte()
    {
        $ubicaciones = Ubicacion::sedes()->get();
        return view('primaria.reportes.calificacion_por_materia.create', [
            'ubicaciones' => $ubicaciones
        ]);
    }

    public function getGrupos(Request $request, $programa_id, $plan_id, $id_periodo)
    {

        $usuarioLogueado = auth()->user()->username;

        $EMPLEADO = auth()->user()->empleado_id;

        if($usuarioLogueado == "GINAESTHER" || $usuarioLogueado == "DESARROLLO.PRIMARIA")
        {
            if($request->ajax()){


                $grupos = DB::table('primaria_inscritos')
                ->select('primaria_grupos.gpoGrado', DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado'),
                'primaria_grupos.gpoClave', DB::raw('count(*) as gpoClave, primaria_grupos.gpoClave'),
                'primaria_grupos.id', DB::raw('count(*) as id, primaria_grupos.id'),
                'primaria_materias.matNombre', DB::raw('count(*) as matNombre, primaria_materias.matNombre'))
                ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
                ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->groupBy('primaria_grupos.gpoGrado')
                ->groupBy('primaria_grupos.gpoClave')
                ->groupBy('primaria_grupos.id')
                ->groupBy('primaria_materias.matNombre')
                ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.id', $id_periodo)
                ->get();

                return response()->json($grupos);
            }
        }else{
            if($request->ajax()){


                $grupos = DB::table('primaria_inscritos')
                ->select('primaria_grupos.gpoGrado', DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado'),
                'primaria_grupos.gpoClave', DB::raw('count(*) as gpoClave, primaria_grupos.gpoClave'),
                'primaria_grupos.id', DB::raw('count(*) as id, primaria_grupos.id'),
                'primaria_materias.matNombre', DB::raw('count(*) as matNombre, primaria_materias.matNombre'))
                ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
                ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->groupBy('primaria_grupos.gpoGrado')
                ->groupBy('primaria_grupos.gpoClave')
                ->groupBy('primaria_grupos.id')
                ->groupBy('primaria_materias.matNombre')
                ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.id', $id_periodo)
                ->where('primaria_empleados.id as empleados_id', $EMPLEADO)
                ->get();

                return response()->json($grupos);
            }
        }


    }

    public function imprimir(Request $request)
    {
        $grupo_id = $request->grupo_id;
        $tipoReporte = $request->tipoReporte;
        $mesEvaluar = $request->mesEvaluar;
        $bimestreEvaluar = $request->bimestreEvaluar;
        $trimestreEvaluar = $request->trimestreEvaluar;

        $alumnos_grupo =  Primaria_inscrito::select(
                'primaria_inscritos.id',
                'primaria_inscritos.inscCalificacionSep as septiembre',
                'primaria_inscritos.inscCalificacionOct as octubre',
                'primaria_inscritos.inscCalificacionNov as noviembre',
                'primaria_inscritos.inscCalificacionDic as diciembre',
                'primaria_inscritos.inscCalificacionEne as enero',
                'primaria_inscritos.inscCalificacionFeb as febrero',
                'primaria_inscritos.inscCalificacionMar as marzo',
                'primaria_inscritos.inscCalificacionAbr as abril',
                'primaria_inscritos.inscCalificacionMay as mayo',
                'primaria_inscritos.inscCalificacionJun as junio',
                'primaria_inscritos.inscBimestre1 as bimestre1',
                'primaria_inscritos.inscBimestre2 as bimestre2',
                'primaria_inscritos.inscBimestre3 as bimestre3',
                'primaria_inscritos.inscBimestre4 as bimestre4',
                'primaria_inscritos.inscBimestre5 as bimestre5',
                'primaria_inscritos.inscTrimestre1 as trimestre1',
                'primaria_inscritos.inscTrimestre2 as trimestre2',
                'primaria_inscritos.inscTrimestre3 as trimestre3',
                'primaria_grupos.id as primaria_grupo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'cursos.id as curso_id',
                'cursos.curEstado',
                'primaria_materias.id as primaria_materia_id',
                'primaria_materias.matClave',
                'primaria_materias.matNombre',
                'primaria_materias.matNombreCorto',
                'periodos.id as periodo_id',
                'periodos.perAnioPago',
                'periodos.perFechaInicial as fecha_inicio',
                'periodos.perFechaFinal as fecha_fin',
                'alumnos.id as alumno_id',
                'alumnos.aluClave as clavePago',
                'personas.id as persona_id',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perNombre',
                'primaria_empleados.id as empleados_id',
                'primaria_empleados.empApellido1',
                'primaria_empleados.empApellido2',
                'primaria_empleados.empNombre',
                'planes.planClave',
                'programas.progClave',
                'programas.progNombre',
                'ubicacion.id as ubicacion_id',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre'
            )
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
            ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')


            ->where('primaria_inscritos.primaria_grupo_id', $grupo_id)
            ->get();

            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');



            $parametro_NombreArchivo = "pdf_primaria_calificacion_por_materia";
            $pdf = PDF::loadView('reportes.pdf.primaria.calificacion_por_materia.' . $parametro_NombreArchivo, [
                "inscritos" => $alumnos_grupo,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "parametro_NombreArchivo" => $parametro_NombreArchivo,
                "tipoReporte" => $tipoReporte,
                "mesEvaluar" => $mesEvaluar,
                "bimestreEvaluar" => $bimestreEvaluar,
                "trimestreEvaluar" => $trimestreEvaluar

            ]);


            // $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

    public function reporteDesdeGrupos($grupo_id)
    {
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;

        $primaria_inscritos = Primaria_inscrito::select('primaria_inscritos.id',
        'primaria_inscritos.primaria_grupo_id',
        'primaria_grupos.gpoGrado',
        'primaria_grupos.gpoClave',
        'primaria_grupos.gpoMatComplementaria',
        'primaria_materias.id as id_materia',
        'primaria_materias.matClave',
        'primaria_materias.matNombre',
        'planes.id as id_plan',
        'planes.planClave',
        'periodos.id as periodo_id',
        'periodos.perAnioPago',
        'periodos.perFechaInicial',
        'periodos.perFechaFinal',
        'departamentos.depClave',
        'departamentos.depNombre',
        'personas.perNombre',
        'personas.perApellido1',
        'personas.perApellido2',
        'programas.id as programa_id',
        'programas.progClave',
        'programas.progNombre',
        'alumnos.id as alumno_id',
        'ubicacion.ubiClave',
        'primaria_empleados.empApellido1',
        'primaria_empleados.empApellido2',
        'primaria_empleados.empNombre',
        'primaria_materias_asignaturas.matClaveAsignatura',
        'primaria_materias_asignaturas.matNombreAsignatura'
        )
        ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
        ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('primaria_empleados', 'primaria_inscritos.inscEmpleadoIdDocente', '=', 'primaria_empleados.id')
        ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
        ->where('primaria_inscritos.primaria_grupo_id', '=', $grupo_id)
        ->where('primaria_inscritos.inscEmpleadoIdDocente',$primaria_empleado_id)
        ->whereNull('primaria_inscritos.deleted_at')
        ->get();


        if($primaria_inscritos->isEmpty()) {
            alert()->warning('Modelo', 'No cuenta con alumnos inscritos este grupo.')->showConfirmButton();
            return back();
        }

        return view('primaria.grupos.create-reporte-calificaciones', [
            'primaria_inscritos' => $primaria_inscritos
        ]);
    }

    public function imprimirDesdeGrupos(Request $request)
    {
        $grupo_id = $request->grupo_id;
        $mes_a_consultar = $request->mes_a_consultar;
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;


        $alumnos_grupo =  Primaria_inscrito::select(
                'primaria_inscritos.id',
                'primaria_inscritos.inscCalificacionSep as septiembre',
                'primaria_inscritos.inscCalificacionOct as octubre',
                'primaria_inscritos.inscCalificacionNov as noviembre',
                'primaria_inscritos.inscCalificacionDic as diciembre',
                'primaria_inscritos.inscCalificacionEne as enero',
                'primaria_inscritos.inscCalificacionFeb as febrero',
                'primaria_inscritos.inscCalificacionMar as marzo',
                'primaria_inscritos.inscCalificacionAbr as abril',
                'primaria_inscritos.inscCalificacionMay as mayo',
                'primaria_inscritos.inscCalificacionJun as junio',
                'primaria_grupos.id as primaria_grupo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'cursos.id as curso_id',
                'primaria_materias.id as primaria_materia_id',
                'primaria_materias.matClave',
                'primaria_materias.matNombre',
                'primaria_materias.matNombreCorto',
                'periodos.id as periodo_id',
                'periodos.perAnioPago',
                'periodos.perFechaInicial as fecha_inicio',
                'periodos.perFechaFinal as fecha_fin',
                'alumnos.id as alumno_id',
                'alumnos.aluClave as clavePago',
                'personas.id as persona_id',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perNombre',
                'primaria_empleados.id as empleados_id',
                'primaria_empleados.empApellido1',
                'primaria_empleados.empApellido2',
                'primaria_empleados.empNombre',
                'planes.planClave',
                'programas.progClave',
                'programas.progNombre',
                'ubicacion.id as ubicacion_id',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'primaria_materias_asignaturas.matClaveAsignatura',
                'primaria_materias_asignaturas.matNombreAsignatura'
            )
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('primaria_empleados', 'primaria_inscritos.inscEmpleadoIdDocente', '=', 'primaria_empleados.id')
            ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
            ->where('primaria_inscritos.primaria_grupo_id', $grupo_id)
            ->where('primaria_inscritos.inscEmpleadoIdDocente',$primaria_empleado_id)
            ->whereNull('primaria_inscritos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');


            $parametro_NombreArchivo = "pdf_primaria_calificacion_por_materia";
            $pdf = PDF::loadView('primaria.pdf.calificacion_por_materia.' . $parametro_NombreArchivo, [
                "inscritos" => $alumnos_grupo,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "parametro_NombreArchivo" => $parametro_NombreArchivo,
                "mes_a_consultar" => $mes_a_consultar,

            ]);


            // $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }


}
