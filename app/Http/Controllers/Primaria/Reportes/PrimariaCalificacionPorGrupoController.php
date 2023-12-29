<?php

namespace App\Http\Controllers\Primaria\Reportes;

use App\clases\personas\MetodosPersonas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conceptoscursoestado;
use App\Models\Departamento;
use App\Models\Periodo;
use App\Models\Primaria\Primaria_inscrito;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDF;
use Auth;
class PrimariaCalificacionPorGrupoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Reporte()
    {
        // $ubicaciones = Ubicacion::sedes()->get();
        $ubicaciones = Ubicacion::whereIn('id', [1,2])->get();


        // $conceptos = Conceptoscursoestado::get();

        return view('primaria.reportes.calificaciones_por_grupo.boleta-calificaciones-create', [
            'ubicaciones' => $ubicaciones,
            // 'conceptos' => $conceptos
        ]);
    }



    public function imprimirCalificaciones(Request $request)
    {

        $primaria_empleado_id = Auth::user()->primaria_empleado->id;

        $departamento = Departamento::findOrFail(14);
        $periodo = Periodo::where('id',$departamento->perActual)->first();

        // filtra las calificaciones de acuerdo al mes que el usuario indique
        $mesEvaluar = $request->mesEvaluar;
        $conceptos = $request->conceptos;
        $perAnioPago = $periodo->perAnioPago;
        $gpoGrado = $request->gpoGrado;
        $gpoClave = $request->gpoClave;
        $programa_id = $request->programa_id;
        $plan_id = $request->plan_id;
        $tipoReporte = $request->tipoReporte;
        $bimestreEvaluar = $request->bimestreEvaluar;
        $trimestreEvaluar = $request->trimestreEvaluar;
        $tipoCalificacionVista = $request->tipoCalificacionVista;

        if ($tipoReporte == "porMes") {
            $parametro_Titulo = "HOJA PARA REPORTAR CALIFICACIONES MENSUAL POR GRUPO";
        }

        if ($tipoReporte == "porBimestre") {
            $parametro_Titulo = "HOJA PARA REPORTAR CALIFICACIONES BIMESTRAL POR GRUPO";
        }

        if ($tipoReporte == "porTrimestre") {
            $parametro_Titulo = "HOJA PARA REPORTAR CALIFICACIONES TRIMESTRAL POR GRUPO";
        }


        //dd($request->periodo_id);
        if ($perAnioPago >= 2021) {

            // agrupa los alumnos
            $calificacionesInscritos = DB::table('primaria_inscritos')
                ->select(
                    'alumnos.aluClave',
                    DB::raw('count(*) as aluClave, alumnos.aluClave'),
                    'personas.perApellido1',
                    DB::raw('count(*) as perApellido1, personas.perApellido1'),
                    'personas.perApellido2',
                    DB::raw('count(*) as perApellido2, personas.perApellido2'),
                    'personas.perNombre',
                    DB::raw('count(*) as perNombre, personas.perNombre'),
                    'alumnos.aluClave',
                    DB::raw('count(*) as aluClave, alumnos.aluClave'),
                    'alumnos.id',
                    DB::raw('count(*) as id, alumnos.id'),
                    'primaria_inscritos.curso_id',
                    DB::raw('count(*) as curso_id, primaria_inscritos.curso_id')
                )
                ->leftJoin('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->leftJoin('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->leftJoin('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
                ->leftJoin('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->leftJoin('programas', 'planes.programa_id', '=', 'programas.id')
                ->leftJoin('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->leftJoin('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
                ->leftJoin('primaria_empleados', 'primaria_inscritos.inscEmpleadoIdDocente', '=', 'primaria_empleados.id')
                ->leftJoin('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->leftJoin('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->leftJoin('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
                ->where('periodos.perAnioPago', $perAnioPago)
                ->where('primaria_grupos.gpoGrado', $request->gpoGrado)
                ->where('primaria_grupos.gpoClave', $request->gpoClave)
                ->where('cursos.curEstado', $request->conceptos)
                ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
                ->groupBy('alumnos.aluClave')
                ->groupBy('personas.perApellido1')
                ->groupBy('personas.perApellido2')
                ->groupBy('personas.perNombre')
                ->groupBy('alumnos.aluClave')
                ->groupBy('alumnos.id')
                ->groupBy('primaria_inscritos.curso_id')
                ->orderBy('personas.perApellido1', 'asc')
                ->get();

            $resultado_collection1 = collect($calificacionesInscritos);
            // si no hay datos muestra alerta
            if ($resultado_collection1->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }

            // consulta para llenar los datos de la cabecera del pdf
            $datos_cabecera = Primaria_inscrito::select(
                'primaria_inscritos.id',
                'primaria_inscritos.inscCalificacionSep',
                'primaria_inscritos.inscCalificacionOct',
                'primaria_inscritos.inscCalificacionNov',
                'primaria_inscritos.inscCalificacionDic',
                'primaria_inscritos.inscCalificacionEne',
                'primaria_inscritos.inscCalificacionFeb',
                'primaria_inscritos.inscCalificacionMar',
                'primaria_inscritos.inscCalificacionAbr',
                'primaria_inscritos.inscCalificacionMay',
                'primaria_inscritos.inscCalificacionJun',
                'primaria_inscritos.inscPromedioMes',
                'primaria_inscritos.inscBimestre1',
                'primaria_inscritos.inscBimestre2',
                'primaria_inscritos.inscBimestre3',
                'primaria_inscritos.inscBimestre4',
                'primaria_inscritos.inscBimestre5',
                'primaria_inscritos.inscPromedioBim',
                'primaria_grupos.id as primaria_grupo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'primaria_grupos.gpoTurno',
                'primaria_materias.id as primaria_materia_id',
                'primaria_materias.matClave',
                'primaria_materias.matNombre',
                'primaria_materias.matNombreCorto',
                'planes.id as plan_id',
                'planes.planClave',
                'planes.planPeriodos',
                'periodos.id as periodo_id',
                'periodos.perNumero',
                'periodos.perAnio',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.id as programa_id',
                'programas.progClave',
                'programas.progNombre',
                'programas.progNombreCorto',
                'departamentos.id as departamento_id',
                'departamentos.depNivel',
                'departamentos.depClave',
                'departamentos.depNombre',
                'departamentos.depNombreCorto',
                'ubicacion.id as ubicacion_id',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'escuelas.id as escuela_id',
                'escuelas.escClave',
                'escuelas.escNombre',
                'escuelas.escNombreCorto',
                'primaria_empleados.id as primaria_empleado_id',
                'primaria_empleados.empApellido1',
                'primaria_empleados.empApellido2',
                'primaria_empleados.empNombre',
                'cursos.id as curso_id',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'alumnos.aluEstado',
                'alumnos.aluMatricula',
                'personas.id as persona_id',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perNombre',
                'primaria_materias_asignaturas.matClaveAsignatura',
                'primaria_materias_asignaturas.matNombreAsignatura'
            )
                ->leftJoin('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->leftJoin('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->leftJoin('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
                ->leftJoin('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->leftJoin('programas', 'planes.programa_id', '=', 'programas.id')
                ->leftJoin('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->leftJoin('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
                ->leftJoin('primaria_empleados', 'primaria_inscritos.inscEmpleadoIdDocente', '=', 'primaria_empleados.id')
                ->leftJoin('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->leftJoin('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->leftJoin('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
                ->where('periodos.perAnioPago', $perAnioPago)
                ->where('primaria_grupos.gpoGrado', $request->gpoGrado)
                ->where('primaria_grupos.gpoClave', $request->gpoClave)
                ->where('cursos.curEstado', $request->conceptos)
                ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
                ->get();

            // llama al procedure de los alumnos a buscar
            $resultado_array =  DB::select("call procPrimariaCalificacionesGrupoAsignaturasPortalDocente(" . $perAnioPago . ", " . $gpoGrado . ", '" . $gpoClave . "', '" . $conceptos . "'," . $programa_id . "," . $plan_id . ", ".$primaria_empleado_id.")");
        } 


        $resultado_collection = collect($resultado_array);

        // return $hola = $resultado_collection->groupBy('matClave');

        // si no hay datos muestra alerta
        if ($resultado_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }


        $resultado_registro = $resultado_array[0];
        $parametro_Grado = $resultado_registro->gpoGrado;
        $parametro_Grupo = $resultado_registro->gpoClave;
        $parametro_Ciclo = $resultado_registro->ciclo_escolar;
        $parametro_progClave = $resultado_registro->progClave;
        $parametro_planClave = $resultado_registro->planClave;
        $parametro_progNombre = $resultado_registro->progNombre;




        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        // validamos si el periodo es mayor o igual al año 2021
        if ($perAnioPago >= 2021) {

            if ($tipoCalificacionVista == "todosGrupos") {
                $materia_alumos =  DB::select("SELECT DISTINCT
                pm.matClave,
                pm.matNombre,
                pm.matNombreCorto,
                pma.matClaveAsignatura,
                pma.matNombreAsignatura
                FROM
                cursos
                INNER JOIN periodos ON cursos.periodo_id = periodos.id
                AND periodos.deleted_at IS NULL
                INNER JOIN cgt ON cursos.cgt_id = cgt.id
                AND cgt.deleted_at IS NULL
                INNER JOIN planes ON cgt.plan_id = planes.id
                AND planes.deleted_at IS NULL
                INNER JOIN programas ON planes.programa_id = programas.id
                AND programas.deleted_at IS NULL
                INNER JOIN escuelas ON programas.escuela_id = escuelas.id
                AND escuelas.deleted_at IS NULL
                INNER JOIN departamentos ON escuelas.departamento_id = departamentos.id
                AND departamentos.deleted_at IS NULL
                INNER JOIN ubicacion ON departamentos.ubicacion_id = ubicacion.id
                AND ubicacion.deleted_at IS NULL
                    INNER JOIN primaria_inscritos pi ON pi.curso_id = cursos.id
                    AND pi.deleted_at IS NULL
                    INNER JOIN primaria_grupos pg ON pg.id = pi.primaria_grupo_id
                    AND pg.deleted_at IS NULL
                    INNER JOIN primaria_materias pm ON pm.id = pg.primaria_materia_id
                    AND pg.deleted_at IS NULL
                    LEFT JOIN primaria_materias_asignaturas pma ON pma.id = pg.primaria_materia_asignatura_id
    
                WHERE
                cursos.deleted_at IS NULL
                    AND departamentos.depClave = 'PRI'
                AND pg.gpoGrado = '" . $request->gpoGrado . "'
                    AND	pg.gpoClave = '" . $request->gpoClave . "'
                    AND periodos.perAnioPago = '" . $perAnioPago . "'
                    AND pi.inscEmpleadoIdDocente = $primaria_empleado_id
                    ORDER BY pm.matClave asc");
            } else {
                // obtiene las materias que se relacionan con el alumno en curso
                $materia_alumos =  DB::select("SELECT DISTINCT
            pm.matClave,
            pm.matNombre,
            pm.matNombreCorto,
            pm.matOrdenVisual
            FROM
            cursos
            INNER JOIN periodos ON cursos.periodo_id = periodos.id
            AND periodos.deleted_at IS NULL
            INNER JOIN cgt ON cursos.cgt_id = cgt.id
            AND cgt.deleted_at IS NULL
            INNER JOIN planes ON cgt.plan_id = planes.id
            AND planes.deleted_at IS NULL
            INNER JOIN programas ON planes.programa_id = programas.id
            AND programas.deleted_at IS NULL
            INNER JOIN escuelas ON programas.escuela_id = escuelas.id
            AND escuelas.deleted_at IS NULL
            INNER JOIN departamentos ON escuelas.departamento_id = departamentos.id
            AND departamentos.deleted_at IS NULL
            INNER JOIN ubicacion ON departamentos.ubicacion_id = ubicacion.id
            AND ubicacion.deleted_at IS NULL
                INNER JOIN primaria_inscritos pi ON pi.curso_id = cursos.id
                AND pi.deleted_at IS NULL
                INNER JOIN primaria_grupos pg ON pg.id = pi.primaria_grupo_id
                AND pg.deleted_at IS NULL
                INNER JOIN primaria_materias pm ON pm.id = pg.primaria_materia_id
                AND pg.deleted_at IS NULL
            WHERE
            cursos.deleted_at IS NULL
                AND departamentos.depClave = 'PRI'
            AND pg.gpoGrado = '" . $request->gpoGrado . "'
                AND	pg.gpoClave = '" . $request->gpoClave . "'
                AND periodos.perAnioPago = '" . $perAnioPago . "'
                AND pi.inscEmpleadoIdDocente = $primaria_empleado_id
                ORDER BY pm.matOrdenVisual asc");
            }
        } 



        if ($tipoCalificacionVista == "todosGrupos") {

            if ($perAnioPago >= 2021) {
                $parametro_NombreArchivo = 'pdf_primaria_calificaciones_asignaturas'; //nombre del archivo blade
            } 



            $pdf = PDF::loadView('primaria.pdf.calificacion_por_grupo.' . $parametro_NombreArchivo, [
                "materia_alumos" => $materia_alumos,
                "calificaciones" => $resultado_collection,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "cicloEscolar" => $parametro_Ciclo,
                "grado" => $parametro_Grado,
                'grupo' => $parametro_Grupo,
                "titulo" => $parametro_Titulo,
                'parametro_Titulo' => $parametro_Titulo,
                'parametro_NombreArchivo' => $parametro_NombreArchivo,
                'parametro_progClave' => $parametro_progClave,
                'parametro_planClave' => $parametro_planClave,
                'parametro_progNombre' => $parametro_progNombre,
                'calificacionesInscritos' => $calificacionesInscritos,
                "mesEvaluar" => $mesEvaluar,
                "datos_cabecera" => $datos_cabecera,
                "conceptos" => $conceptos,
                "tipoReporte" => $tipoReporte,
                "bimestreEvaluar" => $bimestreEvaluar,
                "trimestreEvaluar" => $trimestreEvaluar,
                "tipoCalificacionVista" => $tipoCalificacionVista,
            ]);

            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        } else {

            if ($perAnioPago >= 2021) {
                // obtiene el nombre de la materia (SEP)
                $materiaNombreSep =  DB::select("call procPrimariaCalificacionesSEPGrupoMateriasPortalDocente("
                    . $perAnioPago . ", " . $gpoGrado . ", '" . $gpoClave . "', '"
                    . $conceptos . "'," . $programa_id . "," . $plan_id . ", ".$primaria_empleado_id.")");
            } 

            $resultadoMatSep_collection = collect($materiaNombreSep);

            $matNombreColumna = $resultadoMatSep_collection->groupBy('matNombreColumna');
            $matClave = $resultadoMatSep_collection->groupBy('matClave');

            if ($perAnioPago >= 2021) {
                $parametro_NombreArchivo = 'pdf_primaria_calificaciones_sep_asignaturas'; //nombre del archivo blade

            }


            
            $pdf = PDF::loadView('primaria.pdf.calificacion_por_grupo.'. $parametro_NombreArchivo, [
                "materia_alumos" => $materia_alumos,
                "calificaciones" => $resultadoMatSep_collection,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "cicloEscolar" => $parametro_Ciclo,
                "grado" => $parametro_Grado,
                'grupo' => $parametro_Grupo,
                "titulo" => $parametro_Titulo,
                'parametro_Titulo' => $parametro_Titulo,
                'parametro_NombreArchivo' => $parametro_NombreArchivo,
                'parametro_progClave' => $parametro_progClave,
                'parametro_planClave' => $parametro_planClave,
                'parametro_progNombre' => $parametro_progNombre,
                'calificacionesInscritos' => $calificacionesInscritos,
                "mesEvaluar" => $mesEvaluar,
                "datos_cabecera" => $datos_cabecera,
                "conceptos" => $conceptos,
                "tipoReporte" => $tipoReporte,
                "bimestreEvaluar" => $bimestreEvaluar,
                "trimestreEvaluar" => $trimestreEvaluar,
                "tipoCalificacionVista" => $tipoCalificacionVista,
                "matNombreColumna" => $matNombreColumna,
                "matClave" => $matClave
            ]);

            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        }
    }
}
