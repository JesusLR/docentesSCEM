<?php

namespace App\Http\Controllers\Primaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Conceptoscursoestado;
use App\Http\Models\Primaria\Primaria_inscrito;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaCalificacionesIngresController extends Controller
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
    public function index()
    {
        $ubicaciones = Ubicacion::sedes()->get();
        $conceptos = Conceptoscursoestado::get();

        return view('primaria.reportes.calificacion_ingles.create', [
            'ubicaciones' => $ubicaciones,
            'conceptos' => $conceptos
        ]);
    }

    public function imprimir(Request $request)
    {
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
            DB::raw('count(*) as id, alumnos.id')
        )
            ->leftJoin('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->leftJoin('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->leftJoin('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
            ->leftJoin('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
            ->leftJoin('programas', 'planes.programa_id', '=', 'programas.id')
            ->leftJoin('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->leftJoin('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->leftJoin('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
            ->leftJoin('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->leftJoin('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->leftJoin('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->where('periodos.perAnioPago', $request->periodo_id)
            ->where('primaria_grupos.gpoGrado', $request->gpoGrado)
            ->where('primaria_grupos.gpoClave', $request->gpoClave)
            ->where('cursos.curEstado', $request->conceptos)
            ->where('programas.id', $request->programa_id)
            ->where('planes.id', $request->plan_id)
            ->groupBy('alumnos.aluClave')
            ->groupBy('personas.perApellido1')
            ->groupBy('personas.perApellido2')
            ->groupBy('personas.perNombre')
            ->groupBy('alumnos.aluClave')
            ->groupBy('alumnos.id')
            ->orderBy('personas.perApellido1', 'asc')
            ->get();

        $resultado_collection1 = collect($calificacionesInscritos);
        // si no hay datos muestra alerta 
        if ($resultado_collection1->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }


        $parametro_NombreArchivo = 'pdf_primaria_calificaciones_ingles'; //nombre del archivo blade
        $parametro_Titulo = "CALIFICACIONES DE INGLES";

        // filtra las calificaciones de acuerdo al mes que el usuario indique 
        $mesEvaluar = $request->mesEvaluar;
        $conceptos = $request->conceptos;
        $perAnioPago = $request->periodo_id;
        $gpoGrado = $request->gpoGrado;
        $gpoClave = $request->gpoClave;
        $programa_id = $request->programa_id;
        $plan_id = $request->plan_id;

        // llama al procedure de los alumnos a buscar 
        $resultado_array =  DB::select("call procPrimariaCalificacionesMateriaIngles(" . $perAnioPago . ", " . $gpoGrado . ", '" . $gpoClave . "', '" . $conceptos . "'," . $programa_id . "," . $plan_id . ")");

        $resultado_collection = collect($resultado_array);

        // si no hay datos muestra alerta 
        if ($resultado_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }

        // obtiene las materias que se relacionan con el alumno en curso 
        $materia_alumos =  DB::select("SELECT DISTINCT
                pm.matClave, 
                pm.matNombre,
                pm.matNombreCorto		
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
                    AND pm.matClave like ('ING%')
                AND pg.gpoGrado = '" . $request->gpoGrado . "'
                    AND	pg.gpoClave = '" . $request->gpoClave . "'
                    AND periodos.perAnioPago = '" . $request->periodo_id . "'
                    ORDER BY pm.matClave asc");

                    // consulta para llenar los datos de la cabecera del pdf 
        $datos_cabecera = Primaria_inscrito::select(
            'primaria_inscritos.id',            
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
            'personas.perNombre'
        )
        ->leftJoin('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
        ->leftJoin('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
        ->leftJoin('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
        ->leftJoin('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        ->leftJoin('programas', 'planes.programa_id', '=', 'programas.id')
        ->leftJoin('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->leftJoin('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->leftJoin('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
        ->leftJoin('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
        ->leftJoin('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->leftJoin('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->where('periodos.perAnioPago', $request->periodo_id)
        ->where('primaria_grupos.gpoGrado', $request->gpoGrado)
        ->where('primaria_grupos.gpoClave', $request->gpoClave)
        ->where('cursos.curEstado', $request->conceptos)
        ->where('programas.id', $request->programa_id)
        ->where('planes.id', $request->plan_id)
        ->get();


        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $resultado_registro = $resultado_array[0];
        $parametro_Grado = $resultado_registro->gpoGrado;
        $parametro_Grupo = $resultado_registro->gpoClave;
        $parametro_Ciclo = $resultado_registro->ciclo_escolar;
        $parametro_progClave = $resultado_registro->progClave;
        $parametro_planClave = $resultado_registro->planClave;
        $parametro_progNombre = $resultado_registro->progNombre;

        $pdf = PDF::loadView('reportes.pdf.primaria.calificacion_ingles.' . $parametro_NombreArchivo, [
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
            "conceptos" => $conceptos
        ]);


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

  
}
