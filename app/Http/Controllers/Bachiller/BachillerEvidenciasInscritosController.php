<?php

namespace App\Http\Controllers\Bachiller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bachiller\Bachiller_calendarioexamen;
use App\Models\Bachiller\Bachiller_conceptos_cualitativos;
use App\Models\Bachiller\Bachiller_evidencias;
use App\Models\Bachiller\Bachiller_evidencias_capturadas;
use App\Models\Bachiller\Bachiller_grupos;
use App\Models\Bachiller\Bachiller_inscritos;
use App\Models\Bachiller\Bachiller_inscritos_evidencias;
use App\Models\Departamento;
use App\Models\Periodo;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BachillerEvidenciasInscritosController extends Controller
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
        $bachiller_conceptos_cualitativos = Bachiller_conceptos_cualitativos::get();
        return view('bachiller.evidencias_inscritos.show-list', [
            'bachiller_conceptos_cualitativos' => $bachiller_conceptos_cualitativos
        ]);
    }

    public function list()
    {
        $bachiller_evidencias = Bachiller_inscritos_evidencias::select(
            'bachiller_inscritos_evidencias.id',
            'bachiller_inscritos_evidencias.evidencia_id',
            'bachiller_inscritos_evidencias.bachiller_inscrito_id',
            'bachiller_inscritos_evidencias.ievPuntos',
            'bachiller_inscritos_evidencias.ievFaltas',
            'bachiller_inscritos_evidencias.ievClaveCualitativa1',
            'bachiller_inscritos_evidencias.ievClaveCualitativa2',
            'bachiller_inscritos_evidencias.ievClaveCualitativa3',
            'bachiller_inscritos_evidencias.ievFechaCaptura',
            'bachiller_inscritos_evidencias.ievHoraCaptura',
            'bachiller_inscritos_evidencias.bachiller_empleado_id',
            'bachiller_evidencias.periodo_id',
            'bachiller_evidencias.bachiller_materia_id',
            'bachiller_evidencias.eviNumero',
            'bachiller_evidencias.eviDescripcion',
            'bachiller_evidencias.eviFechaEntrega',
            'bachiller_evidencias.eviPuntos',
            'bachiller_evidencias.eviTipo',
            'bachiller_evidencias.eviFaltas',
            'periodos.perNumero',
            'periodos.perAnio',
            'periodos.perAnioPago',
            'bachiller_materias.matNombre',
            'bachiller_materias.matClave',
            'planes.planClave',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'departamentos.depClave',
            'departamentos.depNombre',
            'escuelas.escClave',
            'escuelas.escNombre',
            'programas.progClave',
            'bachiller_grupos.gpoGrado',
            'bachiller_grupos.gpoClave',
            'alumnos.aluClave',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'bachiller_empleados.empNombre',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2'
        )
            ->join('bachiller_evidencias', 'bachiller_inscritos_evidencias.evidencia_id', '=', 'bachiller_evidencias.id')
            ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('periodos', 'bachiller_evidencias.periodo_id', '=', 'periodos.id')
            ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->join('bachiller_inscritos', 'bachiller_inscritos_evidencias.bachiller_inscrito_id', '=', 'bachiller_inscritos.id')
            ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
            ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->leftJoin('bachiller_empleados', 'bachiller_inscritos_evidencias.bachiller_empleado_id', '=', 'bachiller_empleados.id');
        // ->where('periodos.perAnioPago', '2021');


        return DataTables::of($bachiller_evidencias)
            ->filterColumn('numero_periodo', function ($query, $keyword) {
                $query->whereRaw("CONCAT(perNumero) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('numero_periodo', function ($query) {
                return $query->perNumero;
            })

            ->filterColumn('anio_periodo', function ($query, $keyword) {
                $query->whereRaw("CONCAT(perAnio) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('anio_periodo', function ($query) {
                return $query->perAnio;
            })

            ->filterColumn('clave_materia', function ($query, $keyword) {
                $query->whereRaw("CONCAT(matClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('clave_materia', function ($query) {
                return $query->matClave;
            })

            ->filterColumn('nombre_materia', function ($query, $keyword) {
                $query->whereRaw("CONCAT(matNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('nombre_materia', function ($query) {
                return $query->matNombre;
            })

            ->filterColumn('ubicacion', function ($query, $keyword) {
                $query->whereRaw("CONCAT(ubiClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('ubicacion', function ($query) {
                return $query->ubiClave;
            })

            ->filterColumn('departamento', function ($query, $keyword) {
                $query->whereRaw("CONCAT(depClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('departamento', function ($query) {
                return $query->depClave;
            })

            ->filterColumn('escuela', function ($query, $keyword) {
                $query->whereRaw("CONCAT(escClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('escuela', function ($query) {
                return $query->escClave;
            })

            ->filterColumn('programa_', function ($query, $keyword) {
                $query->whereRaw("CONCAT(progClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('programa_', function ($query) {
                return $query->progClave;
            })

            ->filterColumn('plan', function ($query, $keyword) {
                $query->whereRaw("CONCAT(planClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('plan', function ($query) {
                return $query->planClave;
            })

            ->filterColumn('clave_alumno', function ($query, $keyword) {
                $query->whereRaw("CONCAT(aluClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('clave_alumno', function ($query) {
                return $query->aluClave;
            })

            ->filterColumn('nombreCompletoAlumno', function ($query, $keyword) {
                $query->whereRaw("CONCAT(perNombre, ' ', perApellido1, ' ', perApellido2) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('nombreCompletoAlumno', function ($query) {
                return $query->perNombre . " " . $query->perApellido1 . " " . $query->perApellido2;
            })

            ->filterColumn('puntos_nscritos_ev', function ($query, $keyword) {
                $query->whereRaw("CONCAT(ievPuntos) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('puntos_nscritos_ev', function ($query) {
                return $query->ievPuntos;
            })

            ->filterColumn('faltas_inscritos_ev', function ($query, $keyword) {
                $query->whereRaw("CONCAT(ievFaltas) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('faltas_inscritos_ev', function ($query) {
                return $query->ievFaltas;
            })


            ->filterColumn('grado', function ($query, $keyword) {
                $query->whereRaw("CONCAT(gpoGrado) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('grado', function ($query) {
                return $query->gpoGrado;
            })

            ->filterColumn('grupo', function ($query, $keyword) {
                $query->whereRaw("CONCAT(gpoClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('grupo', function ($query) {
                return $query->gpoClave;
            })

            ->addColumn('action', function ($query) {

                return '<a href="/bachiller_evidencias/' . $query->id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                    <i class="material-icons">visibility</i>
                </a>
                <a href="/bachiller_evidencias/' . $query->id . '/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                    <i class="material-icons">edit</i>
                </a>
                <form id="delete_' . $query->id . '" action="bachiller_evidencias/' . $query->id . '" method="POST" style="display:inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <a href="#" data-id="'  . $query->id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                        <i class="material-icons">delete</i>
                    </a>
                </form>';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function capturaEvidencia($grupo_id, $periodo_id, $materia_id, $materia_acd_id = null)
    {

        $bachiller_grupo = Bachiller_grupos::select(
            'bachiller_grupos.id',
            'bachiller_grupos.gpoGrado',
            'bachiller_grupos.gpoClave',
            'bachiller_grupos.gpoMatComplementaria',
            'bachiller_grupos.bachiller_materia_acd_id',
            'bachiller_grupos.estado_act',
            'bachiller_grupos.plan_id',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnio',
            'periodos.perAnioPago',
            'bachiller_materias.id as materia_id',
            'bachiller_materias.matClave',
            'bachiller_materias.matNombre',
            'bachiller_grupos.inscritos_gpo',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_empleados.empNombre',
            'bachiller_materias_acd.gpoMatComplementaria as acd',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre'
        )
            ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
            ->join('bachiller_materias', 'bachiller_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->leftJoin('bachiller_empleados', 'bachiller_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
            ->leftJoin('bachiller_materias_acd', 'bachiller_grupos.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('bachiller_grupos.id', $grupo_id)
            ->first();


        if ($materia_acd_id != "") {
            $bachiller_evidencias = Bachiller_evidencias::select(
                'bachiller_evidencias.id',
                'bachiller_evidencias.eviNumero',
                'bachiller_evidencias.eviDescripcion',
                'bachiller_evidencias.eviFaltas',
                'bachiller_evidencias.bachiller_materia_acd_id',
                'bachiller_materias.id as bachiller_materia_id',
                'bachiller_evidencias.eviFechaEntrega as fecha_entrega',
                'bachiller_materias.matClave',
                'bachiller_materias.matNombre',
                'bachiller_materias_acd.bachiller_matClave',
                'bachiller_materias_acd.gpoMatComplementaria',
                'bachiller_evidencias.deleted_at'
            )
                ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->leftJoin('bachiller_materias_acd', 'bachiller_evidencias.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
                ->where('bachiller_evidencias.periodo_id', $periodo_id)
                ->where('bachiller_evidencias.bachiller_materia_id', $materia_id)
                ->where('bachiller_evidencias.bachiller_materia_acd_id', $materia_acd_id)
                ->whereNull('bachiller_materias.deleted_at')
                ->whereNull('bachiller_evidencias.deleted_at')
                ->orderBy('bachiller_evidencias.eviNumero', 'ASC')
                ->get();
        } else {

            $bachiller_evidencias = Bachiller_evidencias::select(
                'bachiller_evidencias.id',
                'bachiller_evidencias.eviNumero',
                'bachiller_evidencias.eviDescripcion',
                'bachiller_evidencias.eviFaltas',
                'bachiller_evidencias.bachiller_materia_acd_id',
                'bachiller_materias.id as bachiller_materia_id',
                'bachiller_evidencias.eviFechaEntrega as fecha_entrega',
                'bachiller_materias.matClave',
                'bachiller_materias.matNombre',
                'bachiller_evidencias.deleted_at'
            )
                ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->where('bachiller_evidencias.periodo_id', $periodo_id)
                ->where('bachiller_evidencias.bachiller_materia_id', $materia_id)
                ->whereNull('bachiller_materias.deleted_at')
                ->whereNull('bachiller_evidencias.deleted_at')
                ->orderBy('bachiller_evidencias.eviNumero', 'ASC')
                ->get();
        }

        $bachiller_inscritos = Bachiller_inscritos::select(
            'bachiller_inscritos.id',
            'alumnos.id as alumno_id',
            'alumnos.aluClave',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'bachiller_grupos.gpoGrado',
            'bachiller_grupos.gpoClave'
        )
            ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
            ->where('bachiller_grupos.id', $grupo_id)
            ->whereNull('bachiller_inscritos.deleted_at')
            ->whereNull('alumnos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();


        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_ALL, 'es_MX', 'es', 'ES');
        $fechaHoy = $fechaActual->format('Y-m-d H:i:s');

        if($bachiller_grupo->bachiller_materia_acd_id != ""){
            $bachiller_materia_acd_id = $bachiller_grupo->bachiller_materia_acd_id;
        }else{
            $bachiller_materia_acd_id = "NULL";
        }


        // return count($bachiller_evidencias);
        if(count($bachiller_evidencias) <= 0){
            alert('Escuela Modelo', 'Las ADAS de esta materia aun no se encuentran capturadas', 'warning')->showConfirmButton();
            return back();
        }


        if(count($bachiller_inscritos) > 0){

            foreach ($bachiller_inscritos as $inscrito) {
                $bachiller_inscrito_id = $inscrito->id;

                foreach ($bachiller_evidencias as $evidencia) {
                    $evidencia_id = $evidencia->id;

                    $bachiller_inscritos_evidencias =  Bachiller_inscritos_evidencias::where('evidencia_id', '=', $evidencia_id)
                    ->where('bachiller_inscrito_id', '=', $bachiller_inscrito_id)
                    ->whereNull('deleted_at')
                    ->first();


                    // Si esta vacio creara el registro
                    if($bachiller_inscritos_evidencias == ""){

                        $procBachillerAgregaEvidenciaAlumno = DB::select("call procBachillerAgregaEvidenciaAlumno(
                            ". $evidencia_id .",
                            ". $bachiller_inscrito_id .",
                            'DOCENTE',
                            ".  auth()->user()->id ."
                        )");
                    }
                }
            }



        }



        // foreach($bachiller_inscritos as $inscrito){
        //     // Creamos registros en la bachiller_inscritos_evidencias a los alumnos faltantes
        //     $procBachillerCrearEvidenciasInscritos = DB::select("call procBachillerCrearEvidenciasInscritos(
        //         ".$bachiller_grupo->periodo_id.",
        //         ".$bachiller_grupo->materia_id.",
        //         ".$inscrito->id.",
        //         ".$bachiller_grupo->id.",
        //         ".$bachiller_materia_acd_id."
        //     )");
        // }




        $usuario_docente_id = auth()->user()->id;


        $Bachiller_inscritos_evidencias = Bachiller_inscritos_evidencias::select('bachiller_inscritos_evidencias.bachiller_inscrito_id', 'bachiller_inscritos_evidencias.ievPuntos', 'bachiller_inscritos_evidencias.evidencia_id')
        ->join('bachiller_inscritos', 'bachiller_inscritos_evidencias.bachiller_inscrito_id', '=', 'bachiller_inscritos.id')
        ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
        ->join('bachiller_evidencias', 'bachiller_inscritos_evidencias.evidencia_id', '=', 'bachiller_evidencias.id')
        ->where('bachiller_grupos.id', '=', $grupo_id)
        ->whereNull('bachiller_inscritos_evidencias.ievPuntos')
        ->whereNull('bachiller_inscritos_evidencias.deleted_at')
        ->whereNull('bachiller_inscritos.deleted_at')
        ->whereNull('bachiller_evidencias.deleted_at')
        ->whereNull('bachiller_inscritos_evidencias.deleted_at')
        ->get();

        if(count($Bachiller_inscritos_evidencias) == 0){

            if($bachiller_grupo->estado_act == "A"){
                $bachiller_grupo->update([
                    'estado_act' => "B"
                ]);
            }


            $bachiller_inscritos2 = Bachiller_inscritos::select(
                'bachiller_inscritos.id',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.perNombre',
                'personas.perApellido1',
                'personas.perApellido2',
                'bachiller_grupos.gpoGrado',
                'bachiller_grupos.gpoClave'
            )
                ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
                ->where('bachiller_grupos.id', $grupo_id)
                ->whereNull('bachiller_inscritos.deleted_at')
                ->whereNull('cursos.deleted_at')
                ->whereNull('alumnos.deleted_at')
                ->whereNull('personas.deleted_at')
                ->whereNull('bachiller_grupos.deleted_at')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();


            foreach($bachiller_inscritos2 as $insc){
                $total_puntos = DB::select("SELECT SUM(ievPuntos) as total_puntos FROM bachiller_inscritos_evidencias WHERE bachiller_inscrito_id=$insc->id AND deleted_at IS NULL");

                if($total_puntos[0]->total_puntos != ""){
                    DB::update("UPDATE bachiller_inscritos set insCalificacionOrdinario = '".$total_puntos[0]->total_puntos."', insCalificacionFinal = '".$total_puntos[0]->total_puntos."', insPuntosObtenidosFinal = '".$total_puntos[0]->total_puntos."', user_docente_id = $usuario_docente_id WHERE id = $insc->id");

                }


            }
        }



        $calendario_examen = Bachiller_calendarioexamen::where('plan_id', '=', $bachiller_grupo->plan_id)
        ->where('periodo_id', '=', $bachiller_grupo->periodo_id)
        ->first();

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');


        if($fechaActual->format('Y-m-d') > $calendario_examen->calexFinOrdinario){
            alert('Escuela Modelo', 'El período de captura de calificaciones ha finalizado', 'warning')->showConfirmButton();
            return back();
        }

        return view('bachiller.evidencias_inscritos.create', [
            "bachiller_grupo" => $bachiller_grupo,
            "bachiller_evidencias" => $bachiller_evidencias,
            "calendario_examen" => $calendario_examen,
            "ubicacion" => $bachiller_grupo->ubiClave,
            "fechaHoy" => $fechaActual->format('Y-m-d')
        ]);
    }

    public function getMateriasEvidencias(Request $request, $grupo_id, $evidencia_id)
    {
        if ($request->ajax()) {

            // Buscar si hay evidencias capturadas
            $bachiller_evidencias = Bachiller_inscritos_evidencias::select(
                'bachiller_inscritos_evidencias.id',
                'bachiller_inscritos_evidencias.evidencia_id',
                'bachiller_inscritos_evidencias.bachiller_inscrito_id',
                'bachiller_inscritos_evidencias.ievPuntos',
                'bachiller_inscritos_evidencias.ievFaltas',
                'bachiller_inscritos_evidencias.ievClaveCualitativa1',
                'bachiller_inscritos_evidencias.ievClaveCualitativa2',
                'bachiller_inscritos_evidencias.ievClaveCualitativa3',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.perNombre',
                'personas.perApellido1',
                'personas.perApellido2',
                'bachiller_grupos.gpoGrado',
                'bachiller_grupos.gpoClave',
                'bachiller_evidencias.eviNumero',
                'bachiller_evidencias.eviPuntos',
                'bachiller_evidencias.eviFaltas',
                'ubicacion.ubiClave',
                'periodos.id as periodo_id',
                'bachiller_grupos.estado_act'
            )
                ->join('bachiller_inscritos', 'bachiller_inscritos_evidencias.bachiller_inscrito_id', '=', 'bachiller_inscritos.id')
                ->join('bachiller_evidencias', 'bachiller_inscritos_evidencias.evidencia_id', '=', 'bachiller_evidencias.id')
                ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
                ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->where('bachiller_grupos.id', $grupo_id)
                ->where('bachiller_inscritos_evidencias.evidencia_id', $evidencia_id)
                ->whereNull('bachiller_inscritos.deleted_at')
                ->whereNull('alumnos.deleted_at')
                ->whereNull('cursos.deleted_at')
                ->whereNull('bachiller_grupos.deleted_at')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();

            // Para enviar cuando no hay evidencias captutadas
            $bachiller_inscritos = Bachiller_inscritos::select(
                'bachiller_inscritos.id',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.perNombre',
                'personas.perApellido1',
                'personas.perApellido2',
                'bachiller_grupos.gpoGrado',
                'bachiller_grupos.gpoClave'
            )
                ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
                ->where('bachiller_grupos.id', $grupo_id)
                ->whereNull('bachiller_inscritos.deleted_at')
                ->whereNull('alumnos.deleted_at')
                ->whereNull('cursos.deleted_at')
                ->whereNull('bachiller_grupos.deleted_at')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();

            $evidencia = Bachiller_evidencias::findOrFail($evidencia_id);

            $bachiller_conceptos_cualitativos = Bachiller_conceptos_cualitativos::get();


            return response()->json([
                "bachiller_evidencias" => $bachiller_evidencias,
                "bachiller_inscritos" => $bachiller_inscritos,
                "evidencia" => $evidencia,
                "bachiller_conceptos_cualitativos" => $bachiller_conceptos_cualitativos
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if ($request->movimiento == "CREAR") {
        //     $bachiller_grupo_id = $request->bachiller_grupo_id;
        //     $bachiller_evidencia_id = $request->bachiller_evidencia_id;
        //     $bachiller_inscrito_id = $request->bachiller_inscrito_id;
        //     $ievPuntos = $request->ievPuntos;
        //     $ievFaltas = $request->ievFaltas;
        //     $ievClaveCualitativa1 = $request->ievClaveCualitativa1;
        //     $ievClaveCualitativa2 = $request->ievClaveCualitativa2;
        //     $ievClaveCualitativa3 = $request->ievClaveCualitativa3;


        //     $fechaActual = Carbon::now('America/Merida');
        //     setlocale(LC_TIME, 'es_ES.UTF-8');
        //     // En windows
        //     setlocale(LC_TIME, 'spanish');

        //     $bachiller_evidencias = Bachiller_evidencias::findOrFail($bachiller_evidencia_id);

        //     for ($x = 0; $x < count($ievPuntos); $x++) {
        //         if ($ievPuntos[$x] != "") {
        //             if ($ievPuntos[$x] > $bachiller_evidencias->eviPuntos) {
        //                 alert('Escuela Modelo', 'Los puntos evidencias no pueden ser mayor a lo permitido', 'warning')->showConfirmButton();
        //                 return back();
        //             }
        //         }
        //     }

        //     for ($x = 0; $x < count($bachiller_inscrito_id); $x++) {

        //         $evidencias = array();
        //         $evidencias = new Bachiller_inscritos_evidencias();
        //         $evidencias['evidencia_id'] = $bachiller_evidencia_id;
        //         $evidencias['bachiller_inscrito_id'] = $bachiller_inscrito_id[$x];
        //         $evidencias['ievPuntos'] = $ievPuntos[$x];
        //         $evidencias['ievFaltas'] = $ievFaltas[$x];
        //         $evidencias['ievClaveCualitativa1'] = $ievClaveCualitativa1[$x];
        //         $evidencias['ievClaveCualitativa2'] = $ievClaveCualitativa2[$x];
        //         $evidencias['ievClaveCualitativa3'] = $ievClaveCualitativa3[$x];
        //         $evidencias['ievFechaCaptura'] = $fechaActual->format('Y-m-d');
        //         $evidencias['ievHoraCaptura'] = $fechaActual->format('H:i:s');
        //         $evidencias['bachiller_empleado_id'] = auth()->user()->id;
        //         $evidencias->save();
        //     }

        //     // apartir de aqui es para agregar o actualzar el log
        //     $bachiller_evidencias_capturadas = Bachiller_evidencias_capturadas::where('bachiller_grupo_id', '=', $bachiller_grupo_id)
        //     ->where('evidencia_id', '=', $bachiller_evidencia_id)
        //     ->first();

        //     if($bachiller_evidencias_capturadas != ""){

        //         // actualizamos
        //         DB::table('bachiller_evidencias_capturadas')
        //             ->where('id', $bachiller_evidencias_capturadas->id)
        //             ->update([
        //                 'evcFechaEvidencia' => $fechaActual->format('Y-m-d'),
        //                 'evcHoraEvidencia' => $fechaActual->format('H:i:s'),
        //                 'bachiller_empleado_id' => auth()->user()->id
        //             ]);

        //     }else{
        //         // creamos
        //         Bachiller_evidencias_capturadas::create([
        //             'bachiller_grupo_id' => $bachiller_grupo_id,
        //             'evidencia_id' => $bachiller_evidencia_id,
        //             'evcFechaReal' => $fechaActual->format('Y-m-d'),
        //             'evcFechaEvidencia' => $fechaActual->format('Y-m-d'),
        //             'evcHoraEvidencia' => $fechaActual->format('H:i:s'),
        //             'bachiller_empleado_id' => auth()->user()->id
        //         ]);
        //     }

        //     alert('Escuela Modelo', 'Los puntos evidencias se han guardado con éxito', 'success')->showConfirmButton();
        //     return back();
        // }


        if ($request->movimiento == "ACTUALIZAR") {

            $bachiller_grupo_id = $request->bachiller_grupo_id;
            $bachiller_evidencia_id = $request->bachiller_evidencia_id;
            $bachiller_inscrito_id = $request->bachiller_inscrito_id;
            $ievPuntos = $request->ievPuntos;
            $ievFaltas = $request->ievFaltas;
            $bachiller_inscrito_evidencia_id = $request->bachiller_inscrito_evidencia_id;
            $ievClaveCualitativa1 = $request->ievClaveCualitativa1;
            $ievClaveCualitativa2 = $request->ievClaveCualitativa2;
            $ievClaveCualitativa3 = $request->ievClaveCualitativa3;
            $aluClave = $request->aluClave;
            $periodo_id = $request->periodo_id;

            $periodo = Periodo::find($periodo_id);
            $departamento = Departamento::find($periodo->departamento_id);
            $ubicacion = Ubicacion::find($departamento->ubicacion_id);

            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');

            $fecha_hora_hoy = $fechaActual->format('Y-m-d H:i:s');

            $bachiller_evidencias = Bachiller_evidencias::findOrFail($bachiller_evidencia_id);



            // Validamos que los puntos no sean mayor a lo capturado para la evidencia
            for ($x = 0; $x < count($ievPuntos); $x++) {
                if ($ievPuntos[$x] != "") {
                    if ($ievPuntos[$x] > $bachiller_evidencias->eviPuntos) {
                        alert('Escuela Modelo', 'Los puntos evidencias no pueden ser mayor a lo permitido', 'warning')->showConfirmButton();
                        return back();
                    }
                }
            }

            // ciclo para guardar las evidencias
            for ($x = 0; $x < count($bachiller_inscrito_evidencia_id); $x++) {

                if($ievPuntos[$x] == NULL){
                    $puntos_nuevos[$x] = 0.0;
                }else{
                    $puntos_nuevos[$x] = $ievPuntos[$x];
                }

                DB::table('bachiller_inscritos_evidencias')
                    ->where('id', $bachiller_inscrito_evidencia_id[$x])
                    ->update([
                        'bachiller_inscrito_id' => $bachiller_inscrito_id[$x],
                        'ievPuntos' => $puntos_nuevos[$x],
                        'ievClaveCualitativa1' => (isset($request->ievClaveCualitativa1)) ? $ievClaveCualitativa1[$x] : false,
                        'ievClaveCualitativa2' => (isset($request->ievClaveCualitativa2)) ? $ievClaveCualitativa2[$x] : false,
                        'ievClaveCualitativa3' => (isset($request->ievClaveCualitativa3)) ? $ievClaveCualitativa3[$x] : false,
                        'ievFaltas' => (isset($request->ievFaltas)) ? $ievFaltas[$x] : false,
                        'ievFechaCaptura' => $fechaActual->format('Y-m-d'),
                        'ievHoraCaptura' => $fechaActual->format('H:i:s'),
                        'bachiller_empleado_id' => auth()->user()->id,
                        'updated_at' => $fecha_hora_hoy
                    ]);


                    DB::table('bachiller_inscritos')
                    ->where('id', $bachiller_inscrito_id[$x])
                    ->update([
                        'user_docente_id' => auth()->user()->id,
                        'updated_at' => $fecha_hora_hoy
                    ]);


                    if($ubicacion->ubiClave == "CME"){
                        $ejecutarSP = DB::select("call procBachillerEvidenciasAcumuladoAdmin(".$periodo_id.", ".$aluClave[$x].")");

                    }

                    if($ubicacion->ubiClave == "CVA"){
                        $ejecutarSP = DB::select("call procBachillerEvidenciasAcumuladoAdminCVA(".$periodo_id.", ".$aluClave[$x].")");

                    }

            }





            // apartir de aqui es para agregar o actualzar el log
            $bachiller_evidencias_capturadas = Bachiller_evidencias_capturadas::where('bachiller_grupo_id', '=', $bachiller_grupo_id)
            ->where('evidencia_id', '=', $bachiller_evidencia_id)
            ->first();

            if($bachiller_evidencias_capturadas != ""){

                // actualizamos
                DB::table('bachiller_evidencias_capturadas')
                    ->where('id', $bachiller_evidencias_capturadas->id)
                    ->update([
                        'evcFechaEvidencia' => $fechaActual->format('Y-m-d'),
                        'evcHoraEvidencia' => $fechaActual->format('H:i:s'),
                        'bachiller_empleado_id' => auth()->user()->id,
                        'user_docente_id' => auth()->user()->id,
                        'updated_at' => $fecha_hora_hoy
                    ]);

            }else{
                // creamos
                Bachiller_evidencias_capturadas::create([
                    'bachiller_grupo_id' => $bachiller_grupo_id,
                    'evidencia_id' => $bachiller_evidencia_id,
                    'evcFechaReal' => $fechaActual->format('Y-m-d'),
                    'evcFechaEvidencia' => $fechaActual->format('Y-m-d'),
                    'evcHoraEvidencia' => $fechaActual->format('H:i:s'),
                    'bachiller_empleado_id' => auth()->user()->id,
                    'user_docente_id' => auth()->user()->id,
                    'created_at' => $fechaActual->format('Y-m-d').' '.$fechaActual->format('H:i:s')
                ]);
            }

            $faltantes = DB::select("SELECT
            COUNT(*) AS total_null
            FROM
                bachiller_inscritos_evidencias
                INNER JOIN bachiller_inscritos ON bachiller_inscritos_evidencias.bachiller_inscrito_id = bachiller_inscritos.id
            WHERE
            bachiller_inscritos_evidencias.deleted_at IS NULL
            AND bachiller_inscritos.deleted_at IS NULL
            AND bachiller_inscritos.bachiller_grupo_id = $bachiller_grupo_id
            AND bachiller_inscritos_evidencias.ievPuntos IS NULL");

            alert('Escuela Modelo', 'Los puntos evidencias se han actualizado con éxito', 'success')->showConfirmButton();
            return back();
        }
    }


}
