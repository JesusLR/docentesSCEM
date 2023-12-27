<?php

namespace App\Http\Controllers\Primaria;

use App\clases\departamentos\MetodosDepartamentos;
use Auth;
use Validator;
use App\Models\User;
use App\Http\Helpers\Utils;
use App\Models\Cgt;
use App\Models\Primaria\Primaria_empleado;
use App\Models\Horario;
use App\Models\Periodo;
use App\Models\Plan;
use App\Models\Programa;
use App\Models\Ubicacion;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Escuela;
use App\Models\Primaria\Primaria_calificacione;
use App\Models\Primaria\Primaria_grupo;
use App\Models\Primaria\Primaria_grupos_evidencias;
use App\Models\Primaria\Primaria_inscrito;
use App\Models\Primaria\Primaria_materia;
use App\Models\Primaria\Primaria_materias_acd;
use App\Models\Primaria\Primaria_mes_evaluaciones;
use Carbon\Carbon;
use PDF;

class PrimariaGrupoController extends Controller
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
        return view('primaria.grupos.show-list');

    }


    public function list()
    {

        //PRIMARIA PERIODO ACTUAL
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;
        $departamentoCME = Departamento::with('ubicacion')->findOrFail(14);
        $perActualCME = $departamentoCME->perActual;

        $departamentoCVA = Departamento::with('ubicacion')->findOrFail(26);
        $perActualCVA = $departamentoCVA->perActual;

        $prim_grupos = Primaria_grupo::where('empleado_id_docente', $primaria_empleado_id)
        ->whereIn('periodo_id', [$perActualCME, $perActualCVA])
        ->whereNull('deleted_at')
        ->get();

        
        if(count($prim_grupos) > 0){
            foreach($prim_grupos as $grupo){

                // $procPrimariaActualizaDocente = DB::statement('call procPrimariaActualizaDocente(?, ?)',[$grupo->id, $grupo->empleado_id_docente]);



                $prim_inscritos = Primaria_inscrito::where('primaria_grupo_id', $grupo->id)
                ->get();
    
                if(count($prim_inscritos) > 0){
                    foreach($prim_inscritos as $inscrito){
                    
                        DB::update("UPDATE primaria_inscritos SET inscEmpleadoIdDocente = $primaria_empleado_id WHERE id = $inscrito->id");
                        
                    }
                }
                
            }
        }
        

        

        $grupos = Primaria_grupo::select('primaria_grupos.id',
        'primaria_grupos.gpoGrado',
        'primaria_grupos.gpoClave',
        'primaria_grupos.gpoTurno',
        'primaria_grupos.gpoMatComplementaria',
        'primaria_grupos.gpoASIGNATURA',
        'primaria_materias.id as materia_id',
        'primaria_materias.matClave',
        'primaria_materias.matNombre',
        'primaria_materias.matNombreCorto',
        'primaria_materias.matSemestre',
        'planes.id as plan_id',
        'planes.planClave',
        'planes.planPeriodos',
        'periodos.id as periodo_id',
        'periodos.perNumero',
        'periodos.perAnioPago',
        'periodos.perFechaInicial',
        'periodos.perFechaFinal',
        'periodos.perEstado',
        'departamentos.id as departamento_id',
        'departamentos.depNivel',
        'departamentos.depClave',
        'departamentos.depNombre',
        'departamentos.depNombreCorto',
        'ubicacion.id as ubicacion_id',
        'ubicacion.ubiClave',
        'ubicacion.ubiNombre',
        'ubicacion.ubiCalle',
        'primaria_empleados.empApellido1',
        'primaria_empleados.empApellido2',
        'primaria_empleados.empNombre',
        'programas.id as programa_id',
        'programas.progClave',
        'programas.progNombre',
        'programas.progNombreCorto',
        'departamentos.perActual',
        'primaria_materias_asignaturas.matClaveAsignatura',
        'primaria_materias_asignaturas.matNombreAsignatura')
        ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('primaria_inscritos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
        ->join('primaria_empleados', 'primaria_inscritos.inscEmpleadoIdDocente', '=', 'primaria_empleados.id')
        ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
        ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
        ->whereIn('periodos.id', [$perActualCME, $perActualCVA])
        ->orderBy('primaria_materias_asignaturas.matNombreAsignatura', 'ASC')
        ->distinct();

        $acciones = '';
        return Datatables::of($grupos)

            ->filterColumn('ubicacion', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(ubiNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('ubicacion', function ($query) {
                return $query->ubiNombre;
            })

            ->filterColumn('empleado_docente_titular', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(primaria_empleados.empNombre, ' ', primaria_empleados.empApellido1, ' ', primaria_empleados.empApellido2) like ?", ["%{$keyword}%"]);

            })
            ->addColumn('empleado_docente_titular', function ($query) {
                return $query->empNombre . " " . $query->empApellido1 . " " . $query->empApellido2;
            })

            ->filterColumn('empleado_docente_auxiliar', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(primaria_empleadosAux.empNombre, ' ', primaria_empleadosAux.empApellido1, ' ', primaria_empleadosAux.empApellido2) like ?", ["%{$keyword}%"]);
            })


            ->addColumn('empleado_docente_auxiliar', function ($query) {
                return $query->empNombreAux . " " . $query->empApellido1Aux . " " . $query->empApellido2Aux;
            })


            ->filterColumn('peranio', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(perAnioPago) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('peranio', function ($query) {
                return $query->perAnioPago;
            })

            ->filterColumn('planclave', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(planClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('planclave', function ($query) {
                return $query->planClave;
            })

            ->filterColumn('programa', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(progNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('programa', function ($query) {
                return $query->progNombre;
            })

            ->filterColumn('clave', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(matClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('clave', function ($query) {
                return $query->matClave;
            })

            ->filterColumn('matName', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(matNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('matName', function ($query) {
                return $query->matNombre;
            })

            ->filterColumn('es_asignatura', function ($query, $keyword) {

                if($keyword == "NO"){
                    $keyword = 0;
                    $query->whereRaw("CONCAT(gpoASIGNATURA) like ?", ["%{$keyword}%"]);
                }else{
                    if($keyword == "SI"){
                        $keyword = 1;
                        $query->whereRaw("CONCAT(gpoASIGNATURA) like ?", ["%{$keyword}%"]);
                    }
                }

            })
            ->addColumn('es_asignatura', function ($query) {
                if($query->gpoASIGNATURA == "1"){
                    return "SI";
                }else{
                    return "NO";
                }
            })

            ->filterColumn('asignatura_clave', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(matClaveAsignatura) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('asignatura_clave', function ($query) {
                return $query->matClaveAsignatura;
            })

            ->filterColumn('nombre_asignatura', function ($query, $keyword) {
                return $query->whereRaw("CONCAT(matNombreAsignatura) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('nombre_asignatura', function ($query) {
                return $query->matNombreAsignatura;
            })
            ->addColumn('action', function ($grupos) {
                $floatAnio = (float)$grupos->perAnioPago;
                if($floatAnio >= 2020)
                {

                    $btnEditarEvidencias = "";
                    $btnEditarCalificaciones = "";
                    $btnEditarFaltas= "";
                    $btnReporteFaltas = "";
                    $btnReporteCalificaciones = "";


                    $fechaActual = Carbon::now('America/Merida');
                    setlocale(LC_TIME, 'es_ES.UTF-8');
                    // En windows
                    setlocale(LC_TIME, 'spanish');
                    $fechaHoy = $fechaActual->format('Y-m-d');

                    // $mesEvidencia = Primaria_grupos_evidencias::select('primaria_grupos_evidencias.id',
                    // 'primaria_grupos_evidencias.primaria_grupo_id',
                    // 'primaria_grupos_evidencias.primaria_mes_evaluacion_id',
                    // 'primaria_mes_evaluaciones.mes',
                    // 'primaria_grupos.periodo_id',
                    // 'primaria_calendario_calificaciones_docentes.calInicioRevision',
                    // 'primaria_calendario_calificaciones_docentes.calFinRevision')
                    // ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
                    // ->join('primaria_grupos', 'primaria_grupos_evidencias.primaria_grupo_id', '=', 'primaria_grupos.id')
                    // ->join('departamentos', 'primaria_mes_evaluaciones.departamento_id', '=', 'departamentos.id')
                    // ->leftJoin('primaria_calendario_calificaciones_docentes', 'primaria_mes_evaluaciones.id', '=', 'primaria_calendario_calificaciones_docentes.primaria_mes_evaluaciones_id')
                    // ->where('primaria_grupos_evidencias.primaria_grupo_id', $grupos->id)
                    // ->where('primaria_calendario_calificaciones_docentes.calInicioRevision', '<=', $fechaHoy)
                    // ->where('primaria_calendario_calificaciones_docentes.calFinRevision', '>=', $fechaHoy)
                    // ->where('primaria_calendario_calificaciones_docentes.plan_id', $grupos->plan_id)
                    // ->where('primaria_calendario_calificaciones_docentes.periodo_id', $grupos->periodo_id)
                    // ->get();


                    $resultado_array =  DB::select("call procPrimariaMesAMostrar(" . $grupos->id . ", "
                    . $grupos->plan_id . ", "
                    . $grupos->periodo_id . ")");
                    $mesEvidencia = collect($resultado_array);

                    
                    

                    if ($mesEvidencia->isEmpty()) {
                        // alert()->warning('Modelo', 'Aun no hay fechas de captura de calificaciones disponibles.')->showConfirmButton();
                        // return back();
                    }else{
                        $btnEditarCalificaciones = '<a href="primaria_calificacion/grupo/' . $grupos->id . '/edit" class="button button--icon js-button js-ripple-effect" title="Ver/Editar calificaciones" >
                        <i class="material-icons">playlist_add_check</i>
                        </a>';


                        $btnReporteCalificaciones = '<a href="primaria_reporte/calificacion_por_materia/reporte_calificaciones/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Reporte de calificaciones" >
                                <i class="material-icons">picture_as_pdf</i>
                            </a>';
                    }




                    $btnEditarFaltas= '
                    <a href="primaria_inscritos/pase_lista/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Faltas de Alumnos" >
                    <i class="material-icons">assignment</i>
                    </a>';

                    $btnReporteFaltas = '<a href="primaria_grupo/' . $grupos->id . '/reporte_faltas" class="button button--icon js-button js-ripple-effect" title="Reporte de faltas" >
                            <i class="material-icons">picture_as_pdf</i>
                        </a>';



                    $acciones = '<div class="row">'.
                        $btnEditarEvidencias.'
                        <a href="primaria_inscritos/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Alumnos" >
                            <i class="material-icons">assignment_turned_in</i>
                        </a>'.
                        $btnEditarCalificaciones.
                        // $btnEditarFaltas.
                        $btnReporteFaltas.
                        $btnReporteCalificaciones.
                        '</div>';
                }
                return $acciones;
            })
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubicaciones = Ubicacion::whereIn('id', [1])->get();
        $empleados = Primaria_empleado::where('empEstado','A')->get();
        return view('primaria.grupos.create', [
            'ubicaciones' => $ubicaciones,
            'empleados' => $empleados
        ]);
    }


    public function getPrimariaMaterias(Request $request, $semestre, $plan_id)
    {
        if ($request->ajax()) {
            $materias = Primaria_materia::where([
                ['plan_id', '=', $plan_id],
                ['matSemestre', '=', $semestre],
                ['primaria_materias.matVigentePlanPeriodoActual', '=', 'SI']
            ])->get();

            return response()->json($materias);
        }
    }

    public function materiaComplementaria(Request $request, $primaria_materia_id, $plan_id, $periodo_id, $grado)
    {
        if ($request->ajax()) {

            $materiasACD = Primaria_materias_acd::select('primaria_materias_acd.id',
            'primaria_materias_acd.primaria_materia_id', 'primaria_materias_acd.plan_id', 'primaria_materias_acd.periodo_id',
            'primaria_materias_acd.gpoGrado', 'primaria_materias_acd.gpoMatComplementaria',
            'primaria_materias.matNombre',
            'primaria_materias.matClave')
            ->join('primaria_materias', 'primaria_materias_acd.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('periodos', 'primaria_materias_acd.periodo_id', '=', 'periodos.id')
            ->join('planes', 'primaria_materias_acd.plan_id', '=', 'planes.id')
            ->where('primaria_materias_acd.primaria_materia_id', '=', $primaria_materia_id)
            ->where('primaria_materias_acd.plan_id', '=', $plan_id)
            ->where('primaria_materias_acd.periodo_id', '=', $periodo_id)
            ->where('primaria_materias_acd.gpoGrado', '=', $grado)
            ->get();

            return response()->json($materiasACD);
        }
    }

    public function getDepartamentos(Request $request, $id)
    {
        if($request->ajax()){
            // $departamentos = Departamento::with('ubicacion')->where('ubicacion_id','=',$id)
            // ->whereIn('depClave', ['SUP', 'POS'])->get();

            if (Auth::user()->primaria == 1) {
                $departamentos = MetodosDepartamentos::buscarSoloAcademicos($id, ['PRI']);
            }

            //$departamentos = MetodosDepartamentos::buscarSoloAcademicos($id, ['POS', 'SUP', 'PRE']);
            return response()->json($departamentos);
        }
    }

    // seleccionar escuelas
    public function getEscuelas(Request $request)
    {

        if($request->ajax()){
            $escuelas = Escuela::where('departamento_id','=',$request->id)
                ->where(function($query) use ($request) {
                    $query->where("escNombre", "like", "ESCUELA%");
                    $query->orWhere('escNombre', "like", "POSGRADOS%");
                    $query->orWhere('escNombre', "like", "MAESTRIAS%");
                    $query->orWhere('escNombre', "like", "ESPECIALIDADES%");
                    $query->orWhere('escNombre', "like", "DOCTORADOS%");
                    $query->orWhere('escNombre', "like", "PRESCOLAR%");
                    $query->orWhere('escNombre', "like", "PRIMARIA%");

                    if ($request->otro == "diplomados") {
                        $query->orWhere('escNombre', "like", "DIPLOMADOS%");
                    }
                })
            ->get();

            return response()->json($escuelas);
        }
    }

    // OBTENER PERIDO PRIMARIA
    public function getPeriodos(Request $request, $departamento_id)
    {
        $fecha = Carbon::now('CDT');
        $periodos = Periodo::where('departamento_id',$departamento_id)
        ->where('perAnio', '<=', $fecha->year + 1)
        ->orderBy('id', 'desc')->get();

        /*
        * Si $request posee una variable llamada 'field'.
        * retorna un "distinct" de los valores.
        * (creada para selects perNumero o perAnio).
        */
        if($request->field && $request->field == 'perNumero') {
            $periodos = $periodos->sortBy('perNumero')->pluck('perNumero')->unique();
        } elseif ($request->field && $request->field == 'perAnio') {
            $periodos = $periodos->pluck('perAnio')->unique();
        }

        if ($request->ajax()) {
            return response()->json($periodos);
        }
    }

    public function listEquivalente(Request $request)
    {
        $periodo_id = $request->periodo_id;

        $grupo = Primaria_grupo::select("primaria_grupos.id as id", "planes.planClave as planClave", "programas.progClave as progClave",
            "primaria_materias.matClave as matClave", "primaria_materias.matNombre as matNombre", "optativas.optNombre as optNombre",
            "primaria_grupos.gpoGrado as gpoSemestre", "primaria_grupos.gpoClave as gpoClave", "primaria_grupos.gpoTurno as gpoTurno",
            "primaria_grupos.grupo_equivalente_id",
            "periodos.perNumero", "periodos.perAnio")
            ->join("primaria_materias", "primaria_materias.id", "=", "primaria_grupos.primaria_materia_id")
            ->join("periodos", "periodos.id", "=", "primaria_grupos.periodo_id")
            ->join("planes", "planes.id", "=", "primaria_grupos.plan_id")
                ->join("programas", "programas.id", "=", "planes.programa_id")
            ->leftJoin("optativas", "optativas.id", "=", "primaria_grupos.optativa_id", "optativas.optNombre")
            ->where("primaria_grupos.periodo_id", "=", $periodo_id)
            ->whereNull("primaria_grupos.grupo_equivalente_id");


        return Datatables::of($grupo)

            ->filterColumn('gpoSemestre', function($query, $keyword) {
                $query->whereRaw("CONCAT(gpoSemestre, gpoClave, gpoTurno) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('gpoSemestre', function($query) {
                return $query->gpoSemestre . $query->gpoClave . $query->gpoTurno;
            })

            ->addColumn('action', function($grupo) {
                return '<div class="row">
                    <div class="col s1">
                        <button class="btn modal-close" title="Ver" onclick="seleccionarGrupo(' . $grupo->id . ')">
                            <i class="material-icons">done</i>
                        </button>
                    </div>
                </div>';
            })
        ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empleadoRequired = 'required';

        $empleado_id_docente          = $request->empleado_id;
        $empleado_id_auxiliar         = Utils::validaEmpty($request->empleado_id_auxiliar);


        if ($request->grupo_equivalente_id) {
            $empleadoRequired = '';
            $grupoEq = Primaria_grupo::where("id", "=", $request->grupo_equivalente_id)->first();

            $empleado_id_docente                 = $grupoEq->empleado_id;
            $empleado_id_auxiliar         = Utils::validaEmpty($grupoEq->empleado_sinodal_id);
        }

        if($request->input('gpoACD') == 1){
            $gpoMatComplementaria = 'required';
            $texto = 'gpoMatComplementaria.required';
        }
        if($request->gpoACD == 0){
            $gpoMatComplementaria = 'nullable';
            $texto = 'gpoMatComplementaria.nullable';
        }

        $validator = Validator::make($request->all(),
            [
                'periodo_id' => 'required|unique:primaria_grupos,periodo_id,NULL,id,primaria_materia_id,' .
                    $request->input('materia_id') . ',plan_id,' . $request->input('plan_id') .
                    ',gpoGrado,' . $request->input('gpoSemestre') . ',gpoClave,' . $request->input('gpoClave') .
                    ',gpoTurno,' . $request->input('gpoTurno') . ',deleted_at,NULL',
                'materia_id'  => 'required',
                'empleado_id' => $empleadoRequired,
                'plan_id'     => 'required',
                'gpoSemestre' => 'required',
                'gpoClave'    => 'required',
                'gpoTurno'    => 'required',
                'gpoMatComplementaria' => $gpoMatComplementaria

                // 'gpoExtraCurr' => 'required',
            ],
            [
                'periodo_id.unique' => "El grupo ya existe",
                'empleado_id.required' => "El campo docente títular es obligatorio",
                'gpoClave.required' => "El campo clave de grupo es obligatorio",
                'materia_id.required' => "El campo materia es obligatorio",
                'gpoSemestre.required' => "El campo grado es obligatorio",
                $texto => "El campo materia complementaria es obligatorio"

            ]
        );

        //VALIDAR SI YA EXISTE EL GRUPO QUE SE ESTA CREANDO
        $grupo = Primaria_grupo::with("plan", "periodo", "primaria_empleado", "primaria_materia")
            ->where("primaria_materia_id", "=", $request->materia_id)
            ->where("plan_id", "=", $request->plan_id)
            ->where("gpoGrado", "=", $request->gpoSemestre)
            ->where("gpoClave", "=", $request->gpoClave)
            ->where("gpoTurno", "=", $request->gpoTurno)
            ->where("periodo_id", "=", $request->periodo_id)
        ->first();



        if(!$request->ajax()) {
            if ($validator->fails()) {
                return redirect()->route('primaria_grupo.create')->withErrors($validator)->withInput();
            }
        }

        if($request->ajax()) {
            if ($validator->fails()) {
                if ($grupo) {
                    return response()->json([
                        "res" => false,
                        "existeGrupo" => true,
                        "msg" => $grupo
                    ]);
                } else {

                    return response()->json([
                        "res" => false,
                        "existeGrupo" => false,
                        "msg" => $validator->errors()->messages()
                    ]);
                }
            }
        }


        DB::beginTransaction();
        try {
            // valida si viene check
            if($request->gpoACD == 1){
                $gpoACD = 1;
                $gpoMatComplementaria = $request->gpoMatComplementaria;

            }
            if($request->gpoACD == 0){
                $gpoACD = 0;
                $gpoMatComplementaria = null;
            }

            $grupo = Primaria_grupo::create([
                'primaria_materia_id'       => $request->input('materia_id'),
                'plan_id'                   => $request->input('plan_id'),
                'periodo_id'                => $request->input('periodo_id'),
                'gpoGrado'                  => $request->input('gpoSemestre'),
                'gpoClave'                  => $request->input('gpoClave'),
                'gpoTurno'                  => $request->input('gpoTurno'),
                'empleado_id_docente'       => $empleado_id_docente,
                'empleado_id_auxiliar'      => $empleado_id_auxiliar,
                'gpoMatComplementaria'      => $gpoMatComplementaria,
                'gpoFechaExamenOrdinario'   => null,
                'gpoHoraExamenOrdinario'    => null,


                'gpoCupo'                   => Utils::validaEmpty($request->input('gpoCupo')),
                'gpoNumeroFolio'            => $request->input('gpoNumeroFolio'),
                'gpoNumeroActa'             => $request->input('gpoNumeroActa'),
                'gpoNumeroLibro'            => $request->input('gpoNumeroLibro'),
                'grupo_equivalente_id'      => Utils::validaEmpty($request->input('grupo_equivalente_id')),
                'optativa_id'               => null,
                'estado_act'                =>  'A',
                'fecha_mov_ord_act'         => null,
                'clave_actv'                => null,
                'inscritos_gpo'             => 0,
                'nombreAlternativo'         => null,
                'gpoExtraCurr'              => 'g',
                'gpoACD'                    => $gpoACD
            ]);


        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            return response()->json([
                "res" => false,
                "existeGrupo" => false,
                "msg" => [['Ha ocurrido un problema.'.$errorCode.'|'.$errorMessage]],
            ]);

        }
        DB::commit(); #TEST
        return response()->json([
            "res"  => true,
            "data" => $grupo
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $primaria_grupo = Primaria_grupo::with('plan','primaria_materia','primaria_empleado')->findOrFail($id);
        $docente_auxiliar = Primaria_empleado::find($primaria_grupo->empleado_id_auxiliar);
        $grupo_equivalente = Primaria_grupo::with('plan','primaria_materia','primaria_empleado')->find($primaria_grupo->grupo_equivalente_id);

        return view('primaria.grupos.show', [
            'primaria_grupo' => $primaria_grupo,
            'docente_auxiliar' => $docente_auxiliar,
            'grupo_equivalente' => $grupo_equivalente
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empleados = Primaria_empleado::where('empEstado','A')->get();
        $grupo = Primaria_grupo::with('plan','primaria_materia','primaria_empleado')->findOrFail($id);
        $periodos = Periodo::where('departamento_id',$grupo->plan->programa->escuela->departamento_id)->get();
        $programas = Programa::with('empleado','escuela')->where('escuela_id',$grupo->plan->programa->escuela_id)->get();
        $planes = Plan::with('programa')->where('programa_id',$grupo->plan->programa->id)->get();


        $materiasACD = Primaria_materias_acd::select('primaria_materias_acd.id',
        'primaria_materias_acd.primaria_materia_id', 'primaria_materias_acd.plan_id', 'primaria_materias_acd.periodo_id',
        'primaria_materias_acd.gpoGrado', 'primaria_materias_acd.gpoMatComplementaria',
        'primaria_materias.matNombre',
        'primaria_materias.matClave')
        ->join('primaria_materias', 'primaria_materias_acd.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('periodos', 'primaria_materias_acd.periodo_id', '=', 'periodos.id')
        ->join('planes', 'primaria_materias_acd.plan_id', '=', 'planes.id')
        ->where('primaria_materias_acd.primaria_materia_id', '=', $grupo->primaria_materia_id)
        ->where('primaria_materias_acd.plan_id', '=', $grupo->plan_id)
        ->where('primaria_materias_acd.periodo_id', '=', $grupo->periodo_id)
        ->where('primaria_materias_acd.gpoGrado', '=', $grupo->gpoGrado)
        ->get();

        // if (!in_array($grupo->estado_act, ["A", "B"])) {
        //     alert()->error('Ups...', 'El grupo se encuentra cerrado, no se puede modificar')->showConfirmButton()->autoClose(5000);
        //     return redirect('grupo');
        // }

        $grupo_equivalente = Primaria_grupo::with('plan','periodo','primaria_materia')->find($grupo->grupo_equivalente_id);



        $cgts = Cgt::where([['plan_id', $grupo->plan_id],['periodo_id', $grupo->periodo_id]])->get();
        $materias = Primaria_materia::where([['plan_id', '=', $grupo->plan_id],['matSemestre', '=', $grupo->gpoGrado]])->get();
        // $optativas = Optativa::where('materia_id', '=', $grupo->materia_id)->get();




        return view('primaria.grupos.edit',compact('grupo','empleados','periodos','programas',
            'planes','cgts','materias','optativas','grupo_equivalente', 'materiasACD'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $empleadoRequired = 'required';

        $empleado_id_docente                 = $request->empleado_id;
        $empleado_id_auxiliar         = Utils::validaEmpty($request->empleado_id_auxiliar);

        if($request->gpoACD == 1){
            $gpoMatComplementaria = 'required';
            $texto = 'gpoMatComplementaria.required';
            $gpoMatComplementariaSave = $request->gpoMatComplementaria;
            $gpoACD = 1;
        }
        if($request->gpoACD == 0){
            $gpoMatComplementaria = 'nullable';
            $texto = 'gpoMatComplementaria.nullable';
            $gpoMatComplementariaSave = null;
            $gpoACD = 0;

        }

        $validator = Validator::make($request->all(),
            [
                'periodo_id'    => 'required',
                'materia_id'    => 'required',
                'empleado_id'   => $empleadoRequired,
                'plan_id'       => 'required',
                'gpoSemestre'   => 'required',
                'gpoClave'      => 'required',
                'gpoTurno'      => 'required',
                'gpoCupo'       => 'required',
                'gpoMatComplementaria' => $gpoMatComplementaria

            ],
            [
                'empleado_id.required' => "El campo docente títular es obligatorio",
                'gpoClave.required' => "El campo clave de grupo es obligatorio",
                'materia_id.required' => "El campo materia es obligatorio",
                'gpoSemestre.required' => "El campo grado es obligatorio",
                'gpoCupo.required' => "El campo Cupo es obligatorio",
                $texto => "El campo materia complementaria es obligatorio"

            ]
        );

        if ($validator->fails()) {
            return redirect('primaria_grupo/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        try {
            $grupo = Primaria_grupo::findOrFail($id);
            $grupo->empleado_id_docente                 = $empleado_id_docente;
            $grupo->empleado_id_auxiliar         = $empleado_id_auxiliar;
            $grupo->gpoFechaExamenOrdinario     = null;
            $grupo->gpoHoraExamenOrdinario      = null;
            $grupo->gpoMatComplementaria        = $gpoMatComplementariaSave;
            $grupo->gpoCupo                     = Utils::validaEmpty($request->gpoCupo);
            $grupo->gpoNumeroFolio              = $request->gpoNumeroFolio;
            $grupo->gpoNumeroActa               = $request->gpoNumeroActa;
            $grupo->gpoNumeroLibro              = $request->gpoNumeroLibro;
            $grupo->grupo_equivalente_id        = Utils::validaEmpty($request->grupo_equivalente_id);
            // $grupo->optativa_id                 = Utils::validaEmpty($request->optativa_id);
            $grupo->nombreAlternativo           = null;
            $grupo->gpoExtraCurr                 = $request->gpoExtraCurr;
            $grupo->gpoACD                      = $gpoACD;


            $success = $grupo->save();

            alert('Escuela Modelo', 'El grupo se ha actualizado con éxito','success')->showConfirmButton();
            return redirect()->back();
        }catch (QueryException $e){
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Error...'.$errorCode, $errorMessage)->showConfirmButton();
            return redirect('primaria_grupo/'.$id.'/edit')->withInput();
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                         llama la vista de evidencia                        */
    /* -------------------------------------------------------------------------- */
    public function evidenciaTable($id)
    {
        // return $grupo = Primaria_grupo::select('primaria_grupos.*', 'periodos.id as periodo_id')
        // ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        // ->where('primaria_grupos.id', $id)
        // ->first();

        $grupo = Primaria_grupo::with('periodo', 'primaria_materia', 'primaria_empleado', 'primaria_materia_asignatura')
        ->where('primaria_grupos.id', $id)
        ->first();

        $meses = Primaria_mes_evaluaciones::get();

        $Evidencias = Primaria_grupos_evidencias::where('primaria_grupo_id', $id)->first();


        return view('primaria.grupos.evidencia', [
            'grupo' => $grupo,
            'meses' => $meses,
            'Evidencias' => $Evidencias
        ]);
    }


    /* -------------------------------------------------------------------------- */
    /*             guarda o actualiza las evidencias segun sea el caso            */
    /* -------------------------------------------------------------------------- */
    public function guardar_actualizar_evidencia(Request $request)
    {
        $aplicarParaTodos = $request->aplicar;
        // valores de los request
        $primaria_grupo_id =            $request->primaria_grupo_id;
        $primaria_mes_evaluacion_id =   $request->primaria_mes_evaluacion_id;
        $numero_evidencias =            $request->numero_evidencias;
        $concepto_evidencia1 =          $request->concepto_evidencia1;
        $concepto_evidencia2 =          $request->concepto_evidencia2;
        $concepto_evidencia3 =          $request->concepto_evidencia3;
        $concepto_evidencia4 =          $request->concepto_evidencia4;
        $concepto_evidencia5 =          $request->concepto_evidencia5;
        $concepto_evidencia6 =          $request->concepto_evidencia6;
        $concepto_evidencia7 =          $request->concepto_evidencia7;
        $concepto_evidencia8 =          $request->concepto_evidencia8;
        $concepto_evidencia9 =          $request->concepto_evidencia9;
        $concepto_evidencia10 =         $request->concepto_evidencia10;
        $porcentaje_evidencia1 =        $request->porcentaje_evidencia1;
        $porcentaje_evidencia2 =        $request->porcentaje_evidencia2;
        $porcentaje_evidencia3 =        $request->porcentaje_evidencia3;
        $porcentaje_evidencia4 =        $request->porcentaje_evidencia4;
        $porcentaje_evidencia5 =        $request->porcentaje_evidencia5;
        $porcentaje_evidencia6 =        $request->porcentaje_evidencia6;
        $porcentaje_evidencia7 =        $request->porcentaje_evidencia7;
        $porcentaje_evidencia8 =        $request->porcentaje_evidencia8;
        $porcentaje_evidencia9 =        $request->porcentaje_evidencia9;
        $porcentaje_evidencia10 =       $request->porcentaje_evidencia10;
        $porcentajeTotal =              $request->porcentajeTotal;
        // $porcentajeTotal = 0;

        $grupo_evidencia = Primaria_grupos_evidencias::where('primaria_grupo_id', $primaria_grupo_id)
        ->where('primaria_mes_evaluacion_id', $primaria_mes_evaluacion_id)
        ->first();

        // obtener listado de calificaciones en dicho mes seleccionado
        $calificaciones = Primaria_calificacione::select('primaria_calificaciones.primaria_inscrito_id',
        'primaria_calificaciones.primaria_grupo_evidencia_id',
        'primaria_mes_evaluaciones.id as primaria_mes_evaluacion_id',
        'primaria_grupos.id')
        ->join('primaria_grupos_evidencias', 'primaria_calificaciones.primaria_grupo_evidencia_id', '=', 'primaria_grupos_evidencias.id')
        ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
        ->join('primaria_grupos', 'primaria_grupos_evidencias.primaria_grupo_id', '=', 'primaria_grupos.id')
        ->where('primaria_mes_evaluaciones.id', '=', $primaria_mes_evaluacion_id)
        ->where('primaria_grupos.id', '=', $primaria_grupo_id)
        ->get();

        // si hay calificaciones en dicho mes no se podra registrar nuevas evidencias
        if(count($calificaciones) > 0){
            alert()->error('Ups...', 'No se puede actualizar evidencias debido que cuenta con calificaciones registradas en el mes seleccionado')->showConfirmButton()->autoClose(7000);
            return back();
        }

        // valida si el porcentaje es menor o mayor a 100 para poder realizar el  registro
        if($porcentajeTotal > 100 || $porcentajeTotal < 100){
            alert()->error('Ups...', 'El porcentaje total no puede ser menor o mayor de %100')->showConfirmButton()->autoClose(5000);
            return back();
        }else{
            if(!empty($grupo_evidencia)){
                $grupo_evidencia->update([
                    'primaria_grupo_id'          => $primaria_grupo_id,
                    'primaria_mes_evaluacion_id' => $primaria_mes_evaluacion_id,
                    'numero_evidencias'          => $numero_evidencias,
                    'concepto_evidencia1'        => $concepto_evidencia1,
                    'concepto_evidencia2'        => $concepto_evidencia2,
                    'concepto_evidencia3'        => $concepto_evidencia3,
                    'concepto_evidencia4'        => $concepto_evidencia4,
                    'concepto_evidencia5'        => $concepto_evidencia5,
                    'concepto_evidencia6'        => $concepto_evidencia6,
                    'concepto_evidencia7'        => $concepto_evidencia7,
                    'concepto_evidencia8'        => $concepto_evidencia8,
                    'concepto_evidencia9'        => $concepto_evidencia9,
                    'concepto_evidencia10'       => $concepto_evidencia10,
                    'porcentaje_evidencia1'      => $porcentaje_evidencia1,
                    'porcentaje_evidencia2'      => $porcentaje_evidencia2,
                    'porcentaje_evidencia3'      => $porcentaje_evidencia3,
                    'porcentaje_evidencia4'      => $porcentaje_evidencia4,
                    'porcentaje_evidencia5'      => $porcentaje_evidencia5,
                    'porcentaje_evidencia6'      => $porcentaje_evidencia6,
                    'porcentaje_evidencia7'      => $porcentaje_evidencia7,
                    'porcentaje_evidencia8'      => $porcentaje_evidencia8,
                    'porcentaje_evidencia9'      => $porcentaje_evidencia9,
                    'porcentaje_evidencia10'     => $porcentaje_evidencia10,
                    'porcentaje_total'           => $porcentajeTotal
                ]);
            }
            else{
                // si el checkbox de aplicar todos esta seleccioando se crea un array
                if($aplicarParaTodos == "TODOS"){

                    if($primaria_mes_evaluacion_id == 1){
                        // array de id de los meses
                        $valor2 = [1,2,3,4,5,6,7,8,9,10];
                        $numeroVueltas = 10;
                    }
                    if($primaria_mes_evaluacion_id == 2){
                        // array de id de los meses
                        $valor2 = [2,3,4,5,6,7,8,9,10];
                        $numeroVueltas = 9;
                    }
                    if($primaria_mes_evaluacion_id == 3){
                        // array de id de los meses
                        $valor2 = [3,4,5,6,7,8,9,10];
                        $numeroVueltas = 8;
                    }
                    if($primaria_mes_evaluacion_id == 4){
                        // array de id de los meses
                        $valor2 = [4,5,6,7,8,9,10];
                        $numeroVueltas = 7;
                    }
                    if($primaria_mes_evaluacion_id == 5){
                        // array de id de los meses
                        $valor2 = [5,6,7,8,9,10];
                        $numeroVueltas = 6;
                    }
                    if($primaria_mes_evaluacion_id == 6){
                        // array de id de los meses
                        $valor2 = [6,7,8,9,10];
                        $numeroVueltas = 5;
                    }
                    if($primaria_mes_evaluacion_id == 7){
                        // array de id de los meses
                        $valor2 = [7,8,9,10];
                        $numeroVueltas = 4;
                    }
                    if($primaria_mes_evaluacion_id == 8){
                        // array de id de los meses
                        $valor2 = [8,9,10];
                        $numeroVueltas = 3;
                    }
                    if($primaria_mes_evaluacion_id == 9){
                        // array de id de los meses
                        $valor2 = [9,10];
                        $numeroVueltas = 2;
                    }
                    if($primaria_mes_evaluacion_id == 10){
                        // array de id de los meses
                        $valor2 = [10];
                        $numeroVueltas = 1;
                    }

                    // array de evidencias
                    for ($i=0; $i < $numeroVueltas; $i++) {

                        $evidencias = new Primaria_grupos_evidencias();
                        $evidencias['primaria_grupo_id']          = $primaria_grupo_id;
                        $evidencias['primaria_mes_evaluacion_id'] = $valor2[$i];
                        $evidencias['numero_evidencias']          = $numero_evidencias;
                        $evidencias['concepto_evidencia1']        = $concepto_evidencia1;
                        $evidencias['concepto_evidencia2']        = $concepto_evidencia2;
                        $evidencias['concepto_evidencia3']        = $concepto_evidencia3;
                        $evidencias['concepto_evidencia4']        = $concepto_evidencia4;
                        $evidencias['concepto_evidencia5']        = $concepto_evidencia5;
                        $evidencias['concepto_evidencia6']        = $concepto_evidencia6;
                        $evidencias['concepto_evidencia7']        = $concepto_evidencia7;
                        $evidencias['concepto_evidencia8']        = $concepto_evidencia8;
                        $evidencias['concepto_evidencia9']        = $concepto_evidencia9;
                        $evidencias['concepto_evidencia10']       = $concepto_evidencia10;
                        $evidencias['porcentaje_evidencia1']      = $porcentaje_evidencia1;
                        $evidencias['porcentaje_evidencia2']      = $porcentaje_evidencia2;
                        $evidencias['porcentaje_evidencia3']      = $porcentaje_evidencia3;
                        $evidencias['porcentaje_evidencia4']      = $porcentaje_evidencia4;
                        $evidencias['porcentaje_evidencia5']      = $porcentaje_evidencia5;
                        $evidencias['porcentaje_evidencia6']      = $porcentaje_evidencia6;
                        $evidencias['porcentaje_evidencia7']      = $porcentaje_evidencia7;
                        $evidencias['porcentaje_evidencia8']      = $porcentaje_evidencia8;
                        $evidencias['porcentaje_evidencia9']      = $porcentaje_evidencia9;
                        $evidencias['porcentaje_evidencia10']     = $porcentaje_evidencia10;
                        $evidencias['porcentaje_total']           = $porcentajeTotal;

                        $evidencias->save();
                    }

                }else{
                    // Se ejecuta si solo es un mes, es decir si no se da la opcion de meses restantes
                    Primaria_grupos_evidencias::create([
                        'primaria_grupo_id'          => $primaria_grupo_id,
                        'primaria_mes_evaluacion_id' => $primaria_mes_evaluacion_id,
                        'numero_evidencias'          => $numero_evidencias,
                        'concepto_evidencia1'        => $concepto_evidencia1,
                        'concepto_evidencia2'        => $concepto_evidencia2,
                        'concepto_evidencia3'        => $concepto_evidencia3,
                        'concepto_evidencia4'        => $concepto_evidencia4,
                        'concepto_evidencia5'        => $concepto_evidencia5,
                        'concepto_evidencia6'        => $concepto_evidencia6,
                        'concepto_evidencia7'        => $concepto_evidencia7,
                        'concepto_evidencia8'        => $concepto_evidencia8,
                        'concepto_evidencia9'        => $concepto_evidencia9,
                        'concepto_evidencia10'       => $concepto_evidencia10,
                        'porcentaje_evidencia1'      => $porcentaje_evidencia1,
                        'porcentaje_evidencia2'      => $porcentaje_evidencia2,
                        'porcentaje_evidencia3'      => $porcentaje_evidencia3,
                        'porcentaje_evidencia4'      => $porcentaje_evidencia4,
                        'porcentaje_evidencia5'      => $porcentaje_evidencia5,
                        'porcentaje_evidencia6'      => $porcentaje_evidencia6,
                        'porcentaje_evidencia7'      => $porcentaje_evidencia7,
                        'porcentaje_evidencia8'      => $porcentaje_evidencia8,
                        'porcentaje_evidencia9'      => $porcentaje_evidencia9,
                        'porcentaje_evidencia10'     => $porcentaje_evidencia10,
                        'porcentaje_total'           => $porcentajeTotal
                    ]);
                }
            }

            alert('Escuela Modelo', 'Los datos para la evidencia se han agregado con éxito', 'success')->showConfirmButton()->autoClose(3000);;
            return back();
        }



    }

    public function getEvidencias(Request $request,$id_grupo, $id_mes)
    {
        if($request->ajax()){

            $evidencias = Primaria_grupos_evidencias::select('primaria_grupos_evidencias.*')
            ->where('primaria_grupo_id', $id_grupo)
            ->where('primaria_mes_evaluacion_id', $id_mes)
            ->get();

            return response()->json($evidencias);
        }
    }

    public function getGrupos(Request $request, $id)
    {
        $usuarioLogueado = auth()->user()->id;

        if($request->ajax()){

            if($usuarioLogueado == 163){
                $grupos = Primaria_grupo::select('primaria_grupos.id',
                'primaria_grupos.primaria_materia_id',
                'primaria_materias.matNombre',
                'primaria_materias.matSemestre',
                'primaria_grupos.plan_id',
                'primaria_grupos.periodo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'primaria_grupos.gpoTurno',
                'primaria_grupos.empleado_id_docente',
                'primaria_empleados.empNombre',
                'primaria_empleados.empApellido1',
                'primaria_empleados.empApellido2',
                'primaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'primaria_grupos.gpoMatComplementaria',
                'primaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
                ->leftJoin('primaria_empleados as empleados', 'primaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('primaria_grupos.periodo_id', '=', $id)
                ->get();
            }else{
                $grupos = Primaria_grupo::select('primaria_grupos.id',
                'primaria_grupos.primaria_materia_id',
                'primaria_materias.matNombre',
                'primaria_materias.matSemestre',
                'primaria_grupos.plan_id',
                'primaria_grupos.periodo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'primaria_grupos.gpoTurno',
                'primaria_grupos.empleado_id_docente',
                'primaria_empleados.empNombre',
                'primaria_empleados.empApellido1',
                'primaria_empleados.empApellido2',
                'primaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'primaria_grupos.gpoMatComplementaria',
                'primaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
                ->leftJoin('primaria_empleados as empleados', 'primaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('primaria_grupos.periodo_id', '=', $id)
                ->where('primaria_grupos.empleado_id_docente', '=', $usuarioLogueado)
                ->get();
            }
            return response()->json($grupos);
        }
    }

    public function getMaterias(Request $request, $id)
    {
        $usuarioLogueado = auth()->user()->id;

        if($request->ajax()){
            if($usuarioLogueado == 163){
                $grupos = Primaria_grupo::select('primaria_grupos.id',
                'primaria_grupos.primaria_materia_id',
                'primaria_materias.matNombre',
                'primaria_materias.matSemestre',
                'primaria_grupos.plan_id',
                'primaria_grupos.periodo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'primaria_grupos.gpoTurno',
                'primaria_grupos.empleado_id_docente',
                'primaria_empleados.empNombre',
                'primaria_empleados.empApellido1',
                'primaria_empleados.empApellido2',
                'primaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'primaria_grupos.gpoMatComplementaria',
                'primaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
                ->leftJoin('primaria_empleados as empleados', 'primaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('primaria_grupos.id', '=', $id)
                ->get();
            }else{
                $grupos = Primaria_grupo::select('primaria_grupos.id',
                'primaria_grupos.primaria_materia_id',
                'primaria_materias.matNombre',
                'primaria_materias.matSemestre',
                'primaria_grupos.plan_id',
                'primaria_grupos.periodo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'primaria_grupos.gpoTurno',
                'primaria_grupos.empleado_id_docente',
                'primaria_empleados.empNombre',
                'primaria_empleados.empApellido1',
                'primaria_empleados.empApellido2',
                'primaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'primaria_grupos.gpoMatComplementaria',
                'primaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
                ->leftJoin('primaria_empleados as empleados', 'primaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('primaria_grupos.id', '=', $id)
                ->where('primaria_grupos.empleado_id_docente', '=', $usuarioLogueado)
                ->get();
            }

            return response()->json($grupos);
        }
    }

    /* -------------------------------------------------------------------------- */
    /*           obtener los meses de evidencia dados de alta por grupo           */
    /* -------------------------------------------------------------------------- */
    public function getMesEvidencias(Request $request, $id)
    {
        if($request->ajax()){

            $primaria_grupo = Primaria_grupo::where('id', $id)->first();

            // $resultado_array =  DB::select("call procPrimariaMesAMostrar(" . $id . ", ".$primaria_grupo->plan_id.", ".$primaria_grupo->periodo_id.")");
            // $mesEvidencia = collect($resultado_array);


            // $mesEvidencia = Primaria_grupos_evidencias::select('primaria_grupos_evidencias.id', 'primaria_grupos_evidencias.primaria_grupo_id',
            // 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', 'primaria_mes_evaluaciones.mes')
            // ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
            // ->join('primaria_grupos', 'primaria_grupos_evidencias.primaria_grupo_id', '=', 'primaria_grupos.id')
            // ->where('primaria_grupos_evidencias.primaria_grupo_id', $id)
            // ->where('primaria_mes_evaluaciones.mes', 'SEPTIEMBRE')
            // ->get();

            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');
            $fechaHoy = $fechaActual->format('Y-m-d');

            $mesEvidencia = Primaria_grupos_evidencias::select('primaria_grupos_evidencias.id',
            'primaria_grupos_evidencias.primaria_grupo_id',
            'primaria_grupos_evidencias.primaria_mes_evaluacion_id',
            'primaria_mes_evaluaciones.mes',
            'primaria_grupos.periodo_id',
            'primaria_calendario_calificaciones_docentes.calInicioCaptura',
            'primaria_calendario_calificaciones_docentes.calFinCaptura')
            ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
            ->join('primaria_grupos', 'primaria_grupos_evidencias.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('departamentos', 'primaria_mes_evaluaciones.departamento_id', '=', 'departamentos.id')
            ->leftJoin('primaria_calendario_calificaciones_docentes', 'primaria_mes_evaluaciones.id', '=', 'primaria_calendario_calificaciones_docentes.primaria_mes_evaluaciones_id')
            ->where('primaria_grupos_evidencias.primaria_grupo_id', $id)
            ->where('primaria_calendario_calificaciones_docentes.calInicioCaptura', '<=', $fechaHoy)
            ->where('primaria_calendario_calificaciones_docentes.calFinCaptura', '>=', $fechaHoy)
            ->where('primaria_calendario_calificaciones_docentes.plan_id', $primaria_grupo->plan_id)
            ->where('primaria_calendario_calificaciones_docentes.periodo_id', $primaria_grupo->periodo_id)
            ->where('primaria_mes_evaluaciones.numero_evaluacion', '!=', 4)
            ->get();

            return response()->json($mesEvidencia);
        }
    }


    public function getMeses(Request $request, $id)
    {
        if($request->ajax()){

            $meses = Primaria_grupos_evidencias::select('primaria_grupos_evidencias.id',
            'primaria_grupos_evidencias.primaria_grupo_id',
            'primaria_grupos_evidencias.primaria_mes_evaluacion_id',
            'primaria_mes_evaluaciones.mes')
            ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
            ->join('primaria_grupos', 'primaria_grupos_evidencias.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->where('primaria_grupos_evidencias.id', '=', $id)
            ->orderBy('primaria_grupos_evidencias.primaria_mes_evaluacion_id', 'ASC')
            ->get();

            return response()->json($meses);
        }
    }

    public function getNumeroEvaluacionCreate(Request $request, $mes)
    {
        if($request->ajax()){


            $numeroEvalucacion = Primaria_grupos_evidencias::select('primaria_grupos_evidencias.*',
            'primaria_mes_evaluaciones.*')
            ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
            ->where('primaria_grupos_evidencias.id', '=', $mes)
            ->get();

            return response()->json($numeroEvalucacion);
        }
    }

    public function getNumeroEvaluacion(Request $request, $mes)
    {
        if($request->ajax()){


            $numeroEvalucacion = Primaria_grupos_evidencias::select('primaria_grupos_evidencias.*',
            'primaria_mes_evaluaciones.*')
            ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
            ->where('primaria_mes_evaluaciones.mes', '=', $mes)
            ->get();

            return response()->json($numeroEvalucacion);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $primaria_grupo = Primaria_grupo::findOrFail($id);

        $primaria_inscritos = Primaria_inscrito::where('primaria_grupo_id', '=', $primaria_grupo->id)->get();

        if(count($primaria_inscritos) == "0"){
            try {

                if ($primaria_grupo->delete()) {
                    alert('Escuela Modelo', 'El grupo se ha eliminado con éxito', 'success')->showConfirmButton();
                    return redirect()->route('primaria_grupo.index');
                } else {
                    alert()->error('Error...', 'No se puedo eliminar el grupo')->showConfirmButton();
                    return redirect()->route('primaria_grupo.index');
                }
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
            }
        }else{
            alert('Escuela Modelo', 'No se puede eliminar este grupo debido que cuenta con alumnos inscritos', 'warning')->showConfirmButton();
            return redirect()->route('primaria_grupo.index');
        }


    }


    public function reporte_faltas($grupo_id)
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

        return view('primaria.grupos.create-reporte-faltas', [
            'primaria_inscritos' => $primaria_inscritos,
        ]);
    }

    public function imprimirFaltas(Request $request)
    {
        $mes_a_consultar = $request->mes_a_consultar;
        $grupo_id = $request->grupo_id;
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
        'ubicacion.ubiNombre',
        'primaria_empleados.empApellido1',
        'primaria_empleados.empApellido2',
        'primaria_empleados.empNombre',
        'escuelas.escClave',
        'escuelas.escNombre',
        'alumnos.aluClave',
        'primaria_faltas.falTotalSep',
        'primaria_faltas.falTotalOct',
        'primaria_faltas.falTotalNov',
        'primaria_faltas.falTotalDic',
        'primaria_faltas.falTotalEne',
        'primaria_faltas.falTotalFeb',
        'primaria_faltas.falTotalMar',
        'primaria_faltas.falTotalAbr',
        'primaria_faltas.falTotalMay',
        'primaria_faltas.falTotalJun',
        'cursos.id as curso_id',
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
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->leftJoin('primaria_faltas', 'primaria_inscritos.curso_id', '=', 'primaria_faltas.curso_id')
        ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
        ->where('primaria_inscritos.primaria_grupo_id', '=', $grupo_id)
        ->where('primaria_inscritos.inscEmpleadoIdDocente',$primaria_empleado_id)
        ->whereNull('primaria_inscritos.deleted_at')
        ->orderBy('personas.perApellido1', 'ASC')
        ->get();

        $parametro_NombreArchivo = "pdf_primaria_faltas";

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $pdf = PDF::loadView('primaria.pdf.reporte_de_faltas.' . $parametro_NombreArchivo, [
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "mes_a_consultar" => $mes_a_consultar,
            "primaria_inscritos" => $primaria_inscritos
        ]);


        // $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');


    }
}

