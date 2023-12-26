<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Secundaria\Secundaria_inscritos;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use PhpParser\Node\Stmt\Else_;

class SecundariaCalificacionPorMateriaController extends Controller
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
        return view('secundaria.reportes.calificacion_por_materia.create', [
            'ubicaciones' => $ubicaciones
        ]);
    }

    public function getGrupos(Request $request, $programa_id, $plan_id, $id_periodo)
    {

        $usuarioLogueado = auth()->user()->username;

        $EMPLEADO = auth()->user()->empleado_id;

        if($usuarioLogueado == "GINAESTHER" || $usuarioLogueado == "DESARROLLO.SECUNDARIA")
        {
            if($request->ajax()){


                $grupos = DB::table('secundaria_inscritos')
                ->select('secundaria_grupos.gpoGrado', DB::raw('count(*) as gpoGrado, secundaria_grupos.gpoGrado'),
                'secundaria_grupos.gpoClave', DB::raw('count(*) as gpoClave, secundaria_grupos.gpoClave'),
                'secundaria_grupos.id', DB::raw('count(*) as id, secundaria_grupos.id'),
                'secundaria_materias.matNombre', DB::raw('count(*) as matNombre, secundaria_materias.matNombre'))
                ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
                ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
                ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
                ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->groupBy('secundaria_grupos.gpoGrado')
                ->groupBy('secundaria_grupos.gpoClave')
                ->groupBy('secundaria_grupos.id')
                ->groupBy('secundaria_materias.matNombre')
                ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.id', $id_periodo)
                ->get();

                return response()->json($grupos);
            }
        }else{
            if($request->ajax()){


                $grupos = DB::table('secundaria_inscritos')
                ->select('secundaria_grupos.gpoGrado', DB::raw('count(*) as gpoGrado, secundaria_grupos.gpoGrado'),
                'secundaria_grupos.gpoClave', DB::raw('count(*) as gpoClave, secundaria_grupos.gpoClave'),
                'secundaria_grupos.id', DB::raw('count(*) as id, secundaria_grupos.id'),
                'secundaria_materias.matNombre', DB::raw('count(*) as matNombre, secundaria_materias.matNombre'))
                ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
                ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
                ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
                ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->groupBy('secundaria_grupos.gpoGrado')
                ->groupBy('secundaria_grupos.gpoClave')
                ->groupBy('secundaria_grupos.id')
                ->groupBy('secundaria_materias.matNombre')
                ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.id', $id_periodo)
                ->where('secundaria_empleados.id as empleados_id', $EMPLEADO)
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

        $alumnos_grupo =  Secundaria_inscritos::select(
                'secundaria_inscritos.id',
                'secundaria_inscritos.inscCalificacionSep as septiembre',
                'secundaria_inscritos.inscCalificacionOct as octubre',
                'secundaria_inscritos.inscCalificacionNov as noviembre',
                'secundaria_inscritos.inscCalificacionDic as diciembre',
                'secundaria_inscritos.inscCalificacionEne as enero',
                'secundaria_inscritos.inscCalificacionFeb as febrero',
                'secundaria_inscritos.inscCalificacionMar as marzo',
                'secundaria_inscritos.inscCalificacionAbr as abril',
                'secundaria_inscritos.inscCalificacionMay as mayo',
                'secundaria_inscritos.inscCalificacionJun as junio',
                'secundaria_inscritos.inscPromedioBimestre1 as bimestre1',
                'secundaria_inscritos.inscPromedioBimestre2 as bimestre2',
                'secundaria_inscritos.inscPromedioBimestre3 as bimestre3',
                'secundaria_inscritos.inscPromedioBimestre4 as bimestre4',
                'secundaria_inscritos.inscPromedioBimestre5 as bimestre5',
                'secundaria_inscritos.inscTrimestre1 as trimestre1',
                'secundaria_inscritos.inscTrimestre2 as trimestre2',
                'secundaria_inscritos.inscTrimestre3 as trimestre3',
                'secundaria_grupos.id as secundaria_grupo_id',
                'secundaria_grupos.gpoGrado',
                'secundaria_grupos.gpoClave',
                'cursos.id as curso_id',
                'secundaria_materias.id as secundaria_materia_id',
                'secundaria_materias.matClave',
                'secundaria_materias.matNombre',
                'secundaria_materias.matNombreCorto',
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
                'secundaria_empleados.id as empleados_id',
                'secundaria_empleados.empApellido1',
                'secundaria_empleados.empApellido2',
                'secundaria_empleados.empNombre',
                'planes.planClave',
                'programas.progClave',
                'programas.progNombre',
                'ubicacion.id as ubicacion_id',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre'
            )
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
            ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
            ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('secundaria_inscritos.grupo_id', $grupo_id)
            ->get();

            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');



            $parametro_NombreArchivo = "pdf_secundaria_calificacion_por_materia";
            $pdf = PDF::loadView('reportes.pdf.secundaria.calificacion_por_materia.' . $parametro_NombreArchivo, [
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
        $secundaria_inscritos = Secundaria_inscritos::select('secundaria_inscritos.id',
        'secundaria_inscritos.grupo_id',
        'secundaria_grupos.gpoGrado',
        'secundaria_grupos.gpoClave',
        'secundaria_grupos.gpoMatComplementaria',
        'secundaria_materias.id as id_materia',
        'secundaria_materias.matClave',
        'secundaria_materias.matNombre',
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
        'secundaria_empleados.empApellido1',
        'secundaria_empleados.empApellido2',
        'secundaria_empleados.empNombre'
        )
        ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
        ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
        ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
        ->where('secundaria_inscritos.grupo_id', '=', $grupo_id)
        ->whereNull('secundaria_inscritos.deleted_at')
        ->get();

        if($secundaria_inscritos->isEmpty()) {
            alert()->warning('Modelo', 'No hay inscritos en el grupo seleccionado.')->showConfirmButton();
            return back();
        }

        $resultado_array =  DB::select("call procSecundariaMesAMostrar(" . $grupo_id . ",
        ".$secundaria_inscritos[0]->id_plan.", ".$secundaria_inscritos[0]->periodo_id.")");
        $mesEvidencia = collect($resultado_array);

        return view('secundaria.grupos.create-reporte-calificaciones', [
            'secundaria_inscritos' => $secundaria_inscritos,
            'mesEvidencia' => $mesEvidencia
        ]);
    }

    public function imprimirDesdeGrupos(Request $request)
    {
        $grupo_id = $request->grupo_id;
        $mes_a_consultar = $request->mes_a_consultar;

        $alumnos_grupo =  Secundaria_inscritos::select(
                'secundaria_inscritos.id',
                'secundaria_inscritos.inscCalificacionSep as septiembre',
                'secundaria_inscritos.inscCalificacionOct as octubre',
                'secundaria_inscritos.inscCalificacionNov as noviembre',
                'secundaria_inscritos.inscCalificacionDic as diciembre',
                'secundaria_inscritos.inscCalificacionEne as enero',
                'secundaria_inscritos.inscCalificacionFeb as febrero',
                'secundaria_inscritos.inscCalificacionMar as marzo',
                'secundaria_inscritos.inscCalificacionAbr as abril',
                'secundaria_inscritos.inscCalificacionMay as mayo',
                'secundaria_inscritos.inscCalificacionJun as junio',
                'secundaria_inscritos.inscPromedioBimestre1 as bimestre1',
                'secundaria_inscritos.inscPromedioBimestre2 as bimestre2',
                'secundaria_inscritos.inscPromedioBimestre3 as bimestre3',
                'secundaria_inscritos.inscPromedioBimestre4 as bimestre4',
                'secundaria_inscritos.inscPromedioBimestre5 as bimestre5',
                'secundaria_inscritos.inscTrimestre1 as trimestre1',
                'secundaria_inscritos.inscTrimestre2 as trimestre2',
                'secundaria_inscritos.inscTrimestre3 as trimestre3',
                'secundaria_grupos.id as secundaria_grupo_id',
                'secundaria_grupos.gpoGrado',
                'secundaria_grupos.gpoClave',
                'cursos.id as curso_id',
                'secundaria_materias.id as secundaria_materia_id',
                'secundaria_materias.matClave',
                'secundaria_materias.matNombre',
                'secundaria_materias.matNombreCorto',
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
                'secundaria_empleados.id as empleados_id',
                'secundaria_empleados.empApellido1',
                'secundaria_empleados.empApellido2',
                'secundaria_empleados.empNombre',
                'planes.planClave',
                'programas.progClave',
                'programas.progNombre',
                'ubicacion.id as ubicacion_id',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre'
            )
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
            ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
            ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('secundaria_inscritos.grupo_id', $grupo_id)
            ->whereNull('secundaria_inscritos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');


            $parametro_NombreArchivo = "pdf_secundaria_calificacion_por_materia";
            $pdf = PDF::loadView('secundaria.pdf.calificacion_por_materia.' . $parametro_NombreArchivo, [
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
