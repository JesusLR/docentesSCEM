<?php

namespace App\Http\Controllers\Secundaria;

use App\clases\departamentos\MetodosDepartamentos;
use App\Models\Departamento;
use Auth;
use Validator;
use App\Models\User;
use App\Http\Helpers\Utils;
use App\Models\Cgt;
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
use App\Models\Escuela;
use App\Models\Secundaria\Secundaria_calendario_calificaciones_docentes;
use App\Models\Secundaria\Secundaria_calificaciones;
use App\Models\Secundaria\Secundaria_empleados;
use App\Models\Secundaria\Secundaria_grupos;
use App\Models\Secundaria\Secundaria_grupos_evidencias;
use App\Models\Secundaria\Secundaria_inscritos;
use App\Models\Secundaria\Secundaria_materias;
use App\Models\Secundaria\Secundaria_mes_evaluaciones;
use Carbon\Carbon;
use PDF;

class SecundariaGrupoController extends Controller
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
        return view('secundaria.grupos.show-list');

    }


    public function list()
    {
        $secundaria_empleado_id = Auth::user()->secundaria_empleado->id;
        // 1952
        //$perActual = 1952;

        //SECUNDARIA PERIODO ACTUAL (MERIDA Y VALLADOLID)
        $departamentoCME = Departamento::with('ubicacion')->findOrFail(15);
        $perActualCME = $departamentoCME->perActual;

        $departamentoCVA = Departamento::with('ubicacion')->findOrFail(19);
        $perActualCVA = $departamentoCVA->perActual;


        $grupos = Secundaria_grupos::select('secundaria_grupos.id',
        'secundaria_grupos.gpoGrado',
        'secundaria_grupos.gpoClave',
        'secundaria_grupos.gpoTurno',
        'secundaria_grupos.gpoMatComplementaria',
        'secundaria_materias.id as materia_id',
        'secundaria_materias.matClave',
        'secundaria_materias.matNombre',
        'secundaria_materias.matNombreCorto',
        'secundaria_materias.matSemestre',
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
        'secundaria_empleados.empApellido1',
        'secundaria_empleados.empApellido2',
        'secundaria_empleados.empNombre',
        'programas.id as programa_id',
        'programas.progClave',
        'programas.progNombre',
        'programas.progNombreCorto',
        'departamentos.perActual')
        ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
        ->join('planes', 'secundaria_materias.plan_id', '=', 'planes.id')
        ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->where('secundaria_empleados.id',$secundaria_empleado_id)
        ->whereIn('periodos.id', [$perActualCME, $perActualCVA])
        ->orderBy('secundaria_grupos.id', 'desc');


        $acciones = '';
        return Datatables::of($grupos)

            ->filterColumn('materia_complementaria', function ($query, $keyword) {
                $query->whereRaw("CONCAT(gpoMatComplementaria) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('materia_complementaria', function ($query) {
                return $query->gpoMatComplementaria;
            })

            ->filterColumn('ubicacion', function ($query, $keyword) {
                $query->whereRaw("CONCAT(ubiNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('ubicacion', function ($query) {
                return $query->ubiNombre;
            })

            ->filterColumn('nombre', function ($query, $keyword) {
                $query->whereRaw("CONCAT(empNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('nombre', function ($query) {
                return $query->empNombre;
            })
            ->filterColumn('apellido1', function ($query, $keyword) {
                $query->whereRaw("CONCAT(empApellido1) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('apellido1', function ($query) {
                return $query->empApellido1;
            })
            ->filterColumn('apellido2', function ($query, $keyword) {
                $query->whereRaw("CONCAT(empApellido2) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('apellido2', function ($query) {
                return $query->empApellido2;
            })

            ->filterColumn('peranio', function ($query, $keyword) {
                $query->whereRaw("CONCAT(perAnioPago) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('peranio', function ($query) {
                return $query->perAnioPago;
            })

            ->filterColumn('planclave', function ($query, $keyword) {
                $query->whereRaw("CONCAT(planClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('planclave', function ($query) {
                return $query->planClave;
            })

            ->filterColumn('programa', function ($query, $keyword) {
                $query->whereRaw("CONCAT(progNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('programa', function ($query) {
                return $query->progNombre;
            })

            ->filterColumn('clave', function ($query, $keyword) {
                $query->whereRaw("CONCAT(matClave) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('clave', function ($query) {
                return $query->matClave;
            })

            ->filterColumn('matName', function ($query, $keyword) {
                $query->whereRaw("CONCAT(matNombre) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('matName', function ($query) {
                return $query->matNombre;
            })
            ->addColumn('action', function ($grupos) {
                $floatAnio = (float)$grupos->perAnioPago;
                if($floatAnio >= 2020)
                {
                    $validar_si_esta_activo_cva = Auth::user()->campus_cva;
                    $validar_si_esta_activo_cme = Auth::user()->campus_cme;

                    $btnPaseDeLista = "";
                    $btnReporteFaltas = "";
                    $btnEditarCalificaciones = "";
                    $btnReporteCalificaciones = "";
                    $btnListadoAsistencia = "";

                    $calificaciones = Secundaria_calificaciones::select('secundaria_calificaciones.id',
                        'secundaria_calificaciones.secundaria_inscrito_id',
                        'secundaria_calificaciones.numero_evaluacion',
                        'secundaria_calificaciones.mes_evaluacion',
                        'secundaria_calificaciones.calificacion_evidencia1',
                        'secundaria_calificaciones.calificacion_evidencia2',
                        'secundaria_calificaciones.calificacion_evidencia3',
                        'secundaria_calificaciones.calificacion_evidencia4',
                        'secundaria_calificaciones.calificacion_evidencia5',
                        'secundaria_calificaciones.calificacion_evidencia6',
                        'secundaria_calificaciones.calificacion_evidencia7',
                        'secundaria_calificaciones.calificacion_evidencia8',
                        'secundaria_calificaciones.calificacion_evidencia9',
                        'secundaria_calificaciones.calificacion_evidencia10',
                        'secundaria_calificaciones.promedio_mes',
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
                        'periodos.perAnio',
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
                        'secundaria_mes_evaluaciones.id as mes_id',
                        'secundaria_mes_evaluaciones.mes',
                        'ubicacion.ubiClave'
                    )
                        ->join('secundaria_inscritos', 'secundaria_calificaciones.secundaria_inscrito_id', '=', 'secundaria_inscritos.id')
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
                        ->join('secundaria_mes_evaluaciones', 'secundaria_calificaciones.numero_evaluacion', '=', 'secundaria_mes_evaluaciones.id')
                        ->where('secundaria_inscritos.grupo_id', '=', $grupos->id)
                        ->whereNull('secundaria_inscritos.deleted_at')
                        ->whereNull('secundaria_calificaciones.deleted_at')
                        ->get();

                    $grupos_calificaciones = collect($calificaciones);

                    if(!$grupos_calificaciones->isEmpty()) {

                        // obtenemos el mes a evaluar y si no hay no dejara pasar a la vista de captura de calificaciones
                        $fechaActual = Carbon::now('America/Merida');
                        setlocale(LC_TIME, 'es_ES.UTF-8');
                        // En windows
                        setlocale(LC_TIME, 'spanish');
                        $fechaHoy = $fechaActual->format('Y-m-d');

                        $mesEvidencia = Secundaria_grupos_evidencias::select('secundaria_grupos_evidencias.id',
                            'secundaria_grupos_evidencias.secundaria_grupo_id',
                            'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id',
                            'secundaria_mes_evaluaciones.mes',
                            'secundaria_grupos.periodo_id',
                            'secundaria_calendario_calificaciones_docentes.calInicioCaptura',
                            'secundaria_calendario_calificaciones_docentes.calFinCaptura')
                            ->join('secundaria_mes_evaluaciones', 'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id', '=', 'secundaria_mes_evaluaciones.id')
                            ->join('secundaria_grupos', 'secundaria_grupos_evidencias.secundaria_grupo_id', '=', 'secundaria_grupos.id')
                            ->join('departamentos', 'secundaria_mes_evaluaciones.departamento_id', '=', 'departamentos.id')
                            ->leftJoin('secundaria_calendario_calificaciones_docentes', 'secundaria_mes_evaluaciones.id', '=', 'secundaria_calendario_calificaciones_docentes.secundaria_mes_evaluaciones_id')
                            ->where('secundaria_grupos_evidencias.secundaria_grupo_id', $grupos->id)
                            ->where('secundaria_calendario_calificaciones_docentes.calInicioCaptura', '<=', $fechaHoy)
                            ->where('secundaria_calendario_calificaciones_docentes.calFinCaptura', '>=', $fechaHoy)
                            ->where('secundaria_calendario_calificaciones_docentes.plan_id', $calificaciones[0]->id_plan)
                            ->where('secundaria_calendario_calificaciones_docentes.periodo_id', $calificaciones[0]->periodo_id)
                            ->get();


                        if(!$mesEvidencia->isEmpty())
                        {
                                $btnEditarCalificaciones = '<a href="secundaria_calificacion/grupo/' . $grupos->id . '/edit" class="button button--icon js-button js-ripple-effect" title="Ver/Editar calificaciones" >
                                <i class="material-icons">playlist_add_check</i>
                                </a>';
                                if($validar_si_esta_activo_cva == 1){
                                    // $btnPaseDeLista = '<a href="secundaria_inscritos/pase_lista/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Pase de lista" >
                                    // <i class="material-icons">assignment</i>
                                    // </a>';
                                    $btnPaseDeLista = "";
                                }
                        }
                        $btnReporteFaltas = '<a href="secundaria_grupo/' . $grupos->id . '/reporte_faltas" class="button button--icon js-button js-ripple-effect" title="Reporte de faltas" >
                                    <i class="material-icons">picture_as_pdf</i>
                                </a>';
                        $btnReporteCalificaciones = '<a href="secundaria_reporte/calificacion_por_materia/reporte_calificaciones/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Reporte de calificaciones" >
                                <i class="material-icons">picture_as_pdf</i>
                                </a>';

                        $btnListadoAsistencia = '<a href="' . route('secundaria.secundaria_grupo_materia.imprimir', ['grupo_id' => $grupos->id]) . '" target="_blank" class="button button--icon js-button js-ripple-effect" title="Lista de asistencia" >
                        <i class="material-icons">picture_as_pdf</i>
                        </a>';

                        
                    }


                    $acciones = '<a href="secundaria_inscritos/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Alumnos" >
                            <i class="material-icons">assignment_turned_in</i>
                        </a>'
                        .$btnEditarCalificaciones
                        .$btnPaseDeLista
                        .$btnReporteFaltas
                        .$btnReporteCalificaciones
                        .$btnListadoAsistencia;
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
        $ubicaciones = Ubicacion::all();
        $empleados = Secundaria_empleados::where('empEstado','A')->get();
        return view('secundaria.grupos.create', [
            'ubicaciones' => $ubicaciones,
            'empleados' => $empleados
        ]);
    }


    public function getSecundariaMaterias(Request $request, $semestre, $plan_id)
    {
        if ($request->ajax()) {
            $materias = Secundaria_materias::where([
                ['plan_id', '=', $plan_id],
                ['matSemestre', '=', $semestre]
            ])->get();

            return response()->json($materias);
        }
    }

    public function getDepartamentos(Request $request, $id)
    {
        if($request->ajax()){
            // $departamentos = Departamento::with('ubicacion')->where('ubicacion_id','=',$id)
            // ->whereIn('depClave', ['SUP', 'POS'])->get();

            if (Auth::user()->empleado->escuela->departamento->depClave == "SEC") {
                $departamentos = MetodosDepartamentos::buscarSoloAcademicos($id, ['SEC']);
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
                    $query->orWhere('escNombre', "like", "SECUNDARIA%");


                    if ($request->otro == "diplomados") {
                        $query->orWhere('escNombre', "like", "DIPLOMADOS%");
                    }
                })
            ->get();

            return response()->json($escuelas);
        }
    }

    // OBTENER PERIDO SECUNDARIA
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

        $grupo = Secundaria_grupos::select("secundaria_grupos.id as id", "planes.planClave as planClave", "programas.progClave as progClave",
            "secundaria_materias.matClave as matClave", "secundaria_materias.matNombre as matNombre", "optativas.optNombre as optNombre",
            "secundaria_grupos.gpoGrado as gpoSemestre", "secundaria_grupos.gpoClave as gpoClave", "secundaria_grupos.gpoTurno as gpoTurno",
            "secundaria_grupos.grupo_equivalente_id",
            "periodos.perNumero", "periodos.perAnio")
            ->join("secundaria_materias", "secundaria_materias.id", "=", "secundaria_grupos.secundaria_materia_id")
            ->join("periodos", "periodos.id", "=", "secundaria_grupos.periodo_id")
            ->join("planes", "planes.id", "=", "secundaria_grupos.plan_id")
                ->join("programas", "programas.id", "=", "planes.programa_id")
            ->leftJoin("optativas", "optativas.id", "=", "secundaria_grupos.optativa_id", "optativas.optNombre")
            ->where("secundaria_grupos.periodo_id", "=", $periodo_id)
            ->whereNull("secundaria_grupos.grupo_equivalente_id");


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
            $grupoEq = Secundaria_grupos::where("id", "=", $request->grupo_equivalente_id)->first();

            $empleado_id_docente                 = $grupoEq->empleado_id;
            $empleado_id_auxiliar         = Utils::validaEmpty($grupoEq->empleado_sinodal_id);
        }


        $validator = Validator::make($request->all(),
            [
                'periodo_id' => 'required|unique:secundaria_grupos,periodo_id,NULL,id,secundaria_materia_id,' .
                    $request->input('materia_id') . ',plan_id,' . $request->input('plan_id') .
                    ',gpoGrado,' . $request->input('gpoSemestre') . ',gpoClave,' . $request->input('gpoClave') .
                    ',gpoTurno,' . $request->input('gpoTurno') . ',deleted_at,NULL',
                'materia_id'  => 'required',
                'empleado_id' => $empleadoRequired,
                'plan_id'     => 'required',
                'gpoSemestre' => 'required',
                'gpoClave'    => 'required',
                'gpoTurno'    => 'required',
                // 'gpoExtraCurr' => 'required',
            ],
            [
                'periodo_id.unique' => "El grupo ya existe",
                'empleado_id.required' => "El campo docente títular es obligatorio",
                'gpoClave.required' => "El campo clave de grupo es obligatorio",
                'materia_id.required' => "El campo materia es obligatorio",
                'gpoSemestre.required' => "El campo grado es obligatorio",
            ]
        );

        //VALIDAR SI YA EXISTE EL GRUPO QUE SE ESTA CREANDO
        $grupo = Secundaria_grupos::with("plan", "periodo", "secundaria_empleado", "secundaria_materia")
            ->where("secundaria_materia_id", "=", $request->materia_id)
            ->where("plan_id", "=", $request->plan_id)
            ->where("gpoGrado", "=", $request->gpoSemestre)
            ->where("gpoClave", "=", $request->gpoClave)
            ->where("gpoTurno", "=", $request->gpoTurno)
            ->where("periodo_id", "=", $request->periodo_id)
        ->first();



        if(!$request->ajax()) {
            if ($validator->fails()) {
                return redirect()->route('secundaria.secundaria_grupo.create')->withErrors($validator)->withInput();
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
            $grupo = Secundaria_grupos::create([
                'secundaria_materia_id'       => $request->input('materia_id'),
                'plan_id'                   => $request->input('plan_id'),
                'periodo_id'                => $request->input('periodo_id'),
                'gpoGrado'                  => $request->input('gpoSemestre'),
                'gpoClave'                  => $request->input('gpoClave'),
                'gpoTurno'                  => $request->input('gpoTurno'),
                'empleado_id_docente'       => $empleado_id_docente,
                'empleado_id_auxiliar'      => $empleado_id_auxiliar,
                'gpoMatComplementaria'      => null,
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
                'nombreAlternativo'         => $request->input('nombreAlternativo'),
                'gpoExtraCurr'              => 'g'
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
        $secundaria_grupo = Secundaria_grupos::with('plan','secundaria_materia','secundaria_empleado')->findOrFail($id);
        $docente_auxiliar = Secundaria_empleados::find($secundaria_grupo->empleado_id_auxiliar);
        $grupo_equivalente = Secundaria_grupos::with('plan','secundaria_materia','secundaria_empleado')->find($secundaria_grupo->grupo_equivalente_id);

        return view('secundaria.grupos.show', [
            'secundaria_grupo' => $secundaria_grupo,
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
        $empleados = Secundaria_empleados::where('empEstado','A')->get();
        $grupo = Secundaria_grupos::with('plan','secundaria_materia','secundaria_empleado')->findOrFail($id);
        $periodos = Periodo::where('departamento_id',$grupo->plan->programa->escuela->departamento_id)->get();
        $programas = Programa::with('empleado','escuela')->where('escuela_id',$grupo->plan->programa->escuela_id)->get();
        $planes = Plan::with('programa')->where('programa_id',$grupo->plan->programa->id)->get();


        // if (!in_array($grupo->estado_act, ["A", "B"])) {
        //     alert()->error('Ups...', 'El grupo se encuentra cerrado, no se puede modificar')->showConfirmButton()->autoClose(5000);
        //     return redirect('grupo');
        // }

        $grupo_equivalente = Secundaria_grupos::with('plan','periodo','secundaria_materia')->find($grupo->grupo_equivalente_id);



        $cgts = Cgt::where([['plan_id', $grupo->plan_id],['periodo_id', $grupo->periodo_id]])->get();
        $materias = Secundaria_materias::where([['plan_id', '=', $grupo->plan_id],['matSemestre', '=', $grupo->gpoGrado]])->get();
        // $optativas = Optativa::where('materia_id', '=', $grupo->materia_id)->get();




        return view('secundaria.grupos.edit',compact('grupo','empleados','periodos','programas',
            'planes','cgts','materias','optativas','grupo_equivalente'));
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

        $validator = Validator::make($request->all(),
            [
                'periodo_id'    => 'required',
                'materia_id'    => 'required',
                'empleado_id'   => $empleadoRequired,
                'plan_id'       => 'required',
                'gpoSemestre'   => 'required',
                'gpoClave'      => 'required',
                'gpoTurno'      => 'required',
                'gpoCupo'       => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect('secundaria_grupo/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        try {
            $grupo = Secundaria_grupos::findOrFail($id);
            $grupo->empleado_id_docente                 = $empleado_id_docente;
            $grupo->empleado_id_auxiliar         = $empleado_id_auxiliar;
            $grupo->gpoFechaExamenOrdinario     = null;
            $grupo->gpoHoraExamenOrdinario      = null;
            // $grupo->gpoMatClaveComplementaria   = $request->gpoMatClaveComplementaria;
            $grupo->gpoCupo                     = Utils::validaEmpty($request->gpoCupo);
            $grupo->gpoNumeroFolio              = $request->gpoNumeroFolio;
            $grupo->gpoNumeroActa               = $request->gpoNumeroActa;
            $grupo->gpoNumeroLibro              = $request->gpoNumeroLibro;
            $grupo->grupo_equivalente_id        = Utils::validaEmpty($request->grupo_equivalente_id);
            // $grupo->optativa_id                 = Utils::validaEmpty($request->optativa_id);
            $grupo->nombreAlternativo           = $request->nombreAlternativo;
            $grupo->gpoExtraCurr                 = $request->gpoExtraCurr;

            $success = $grupo->save();

            alert('Escuela Modelo', 'El grupo se ha actualizado con éxito','success')->showConfirmButton();
            return redirect()->back();
        }catch (QueryException $e){
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Error...'.$errorCode, $errorMessage)->showConfirmButton();
            return redirect('secundaria_grupo/'.$id.'/edit')->withInput();
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                         llama la vista de evidencia                        */
    /* -------------------------------------------------------------------------- */
    public function evidenciaTable($id)
    {
        $grupo = Secundaria_grupos::where('id', $id)->first();

        $meses = Secundaria_mes_evaluaciones::get();

        $Evidencias = Secundaria_grupos_evidencias::where('secundaria_grupo_id', $id)->first();


        return view('secundaria.grupos.evidencia', [
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
        $secundaria_grupo_id =            $request->secundaria_grupo_id;
        $secundaria_mes_evaluacion_id =   $request->secundaria_mes_evaluacion_id;
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

        $grupo_evidencia = Secundaria_grupos_evidencias::where('secundaria_grupo_id', $secundaria_grupo_id)
        ->where('secundaria_mes_evaluacion_id', $secundaria_mes_evaluacion_id)
        ->first();

        // obtener listado de calificaciones en dicho mes seleccionado
        $calificaciones = Secundaria_calificaciones::select('secundaria_calificaciones.secundaria_inscrito_id',
        'secundaria_calificaciones.secundaria_grupo_evidencia_id',
        'secundaria_mes_evaluaciones.id as secundaria_mes_evaluacion_id',
        'secundaria_grupos.id')
        ->join('secundaria_grupos_evidencias', 'secundaria_calificaciones.secundaria_grupo_evidencia_id', '=', 'secundaria_grupos_evidencias.id')
        ->join('secundaria_mes_evaluaciones', 'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id', '=', 'secundaria_mes_evaluaciones.id')
        ->join('secundaria_grupos', 'secundaria_grupos_evidencias.secundaria_grupo_id', '=', 'secundaria_grupos.id')
        ->where('secundaria_mes_evaluaciones.id', '=', $secundaria_mes_evaluacion_id)
        ->where('secundaria_grupos.id', '=', $secundaria_grupo_id)
        ->get();

        // si hay calificaciones en dicho mes no se podra registrar nuevas evidencias
        if(count($calificaciones) > 0){
            alert()->error('Ups...', 'No se puede actualizar evidencias debido que cuenta con calificaciones registradas en el mes seleccionado')->showConfirmButton()->autoClose(7000);
            return back();
        }

        // valida si el porcentaje es menor o mayor a 100 para poder realizar el  registro
        if($porcentajeTotal > 100 || $porcentajeTotal < 100){
            alert()->error('Ups...', 'El porcentaje total no puede ser meno o mayor de %100')->showConfirmButton()->autoClose(5000);
            return back();
        }else{
            if(!empty($grupo_evidencia)){
                $grupo_evidencia->update([
                    'secundaria_grupo_id'          => $secundaria_grupo_id,
                    'secundaria_mes_evaluacion_id' => $secundaria_mes_evaluacion_id,
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

                    if($secundaria_mes_evaluacion_id == 1){
                        // array de id de los meses
                        $valor2 = [1,2,3,4,5,6,7,8,9,10];
                        $numeroVueltas = 10;
                    }
                    if($secundaria_mes_evaluacion_id == 2){
                        // array de id de los meses
                        $valor2 = [2,3,4,5,6,7,8,9,10];
                        $numeroVueltas = 9;
                    }
                    if($secundaria_mes_evaluacion_id == 3){
                        // array de id de los meses
                        $valor2 = [3,4,5,6,7,8,9,10];
                        $numeroVueltas = 8;
                    }
                    if($secundaria_mes_evaluacion_id == 4){
                        // array de id de los meses
                        $valor2 = [4,5,6,7,8,9,10];
                        $numeroVueltas = 7;
                    }
                    if($secundaria_mes_evaluacion_id == 5){
                        // array de id de los meses
                        $valor2 = [5,6,7,8,9,10];
                        $numeroVueltas = 6;
                    }
                    if($secundaria_mes_evaluacion_id == 6){
                        // array de id de los meses
                        $valor2 = [6,7,8,9,10];
                        $numeroVueltas = 5;
                    }
                    if($secundaria_mes_evaluacion_id == 7){
                        // array de id de los meses
                        $valor2 = [7,8,9,10];
                        $numeroVueltas = 4;
                    }
                    if($secundaria_mes_evaluacion_id == 8){
                        // array de id de los meses
                        $valor2 = [8,9,10];
                        $numeroVueltas = 3;
                    }
                    if($secundaria_mes_evaluacion_id == 9){
                        // array de id de los meses
                        $valor2 = [9,10];
                        $numeroVueltas = 2;
                    }
                    if($secundaria_mes_evaluacion_id == 10){
                        // array de id de los meses
                        $valor2 = [10];
                        $numeroVueltas = 1;
                    }

                    // array de evidencias
                    for ($i=0; $i < $numeroVueltas; $i++) {

                        $evidencias = new Secundaria_grupos_evidencias();
                        $evidencias['secundaria_grupo_id']          = $secundaria_grupo_id;
                        $evidencias['secundaria_mes_evaluacion_id'] = $valor2[$i];
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
                    Secundaria_grupos_evidencias::create([
                        'secundaria_grupo_id'          => $secundaria_grupo_id,
                        'secundaria_mes_evaluacion_id' => $secundaria_mes_evaluacion_id,
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

            $evidencias = Secundaria_grupos_evidencias::select('secundaria_grupos_evidencias.*')
            ->where('secundaria_grupo_id', $id_grupo)
            ->where('secundaria_mes_evaluacion_id', $id_mes)
            ->get();

            return response()->json($evidencias);
        }
    }

    public function getGrupos(Request $request, $id)
    {
        $usuarioLogueado = auth()->user()->id;

        if($request->ajax()){

            if($usuarioLogueado == 163){
                $grupos = Secundaria_grupos::select('secundaria_grupos.id',
                'secundaria_grupos.secundaria_materia_id',
                'secundaria_materias.matNombre',
                'secundaria_materias.matSemestre',
                'secundaria_grupos.plan_id',
                'secundaria_grupos.periodo_id',
                'secundaria_grupos.gpoGrado',
                'secundaria_grupos.gpoClave',
                'secundaria_grupos.gpoTurno',
                'secundaria_grupos.empleado_id_docente',
                'secundaria_empleados.empNombre',
                'secundaria_empleados.empApellido1',
                'secundaria_empleados.empApellido2',
                'secundaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'secundaria_grupos.gpoMatComplementaria',
                'secundaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
                ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
                ->leftJoin('secundaria_empleados as empleados', 'secundaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'secundaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('secundaria_grupos.periodo_id', '=', $id)
                ->get();
            }else{
                $grupos = Secundaria_grupos::select('secundaria_grupos.id',
                'secundaria_grupos.secundaria_materia_id',
                'secundaria_materias.matNombre',
                'secundaria_materias.matSemestre',
                'secundaria_grupos.plan_id',
                'secundaria_grupos.periodo_id',
                'secundaria_grupos.gpoGrado',
                'secundaria_grupos.gpoClave',
                'secundaria_grupos.gpoTurno',
                'secundaria_grupos.empleado_id_docente',
                'secundaria_empleados.empNombre',
                'secundaria_empleados.empApellido1',
                'secundaria_empleados.empApellido2',
                'secundaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'secundaria_grupos.gpoMatComplementaria',
                'secundaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
                ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
                ->leftJoin('secundaria_empleados as empleados', 'secundaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'secundaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('secundaria_grupos.periodo_id', '=', $id)
                ->where('secundaria_grupos.empleado_id_docente', '=', $usuarioLogueado)
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
                $grupos = Secundaria_grupos::select('secundaria_grupos.id',
                'secundaria_grupos.secundaria_materia_id',
                'secundaria_materias.matNombre',
                'secundaria_materias.matSemestre',
                'secundaria_grupos.plan_id',
                'secundaria_grupos.periodo_id',
                'secundaria_grupos.gpoGrado',
                'secundaria_grupos.gpoClave',
                'secundaria_grupos.gpoTurno',
                'secundaria_grupos.empleado_id_docente',
                'secundaria_empleados.empNombre',
                'secundaria_empleados.empApellido1',
                'secundaria_empleados.empApellido2',
                'secundaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'secundaria_grupos.gpoMatComplementaria',
                'secundaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
                ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
                ->leftJoin('secundaria_empleados as empleados', 'secundaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'secundaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('secundaria_grupos.id', '=', $id)
                ->get();
            }else{
                $grupos = Secundaria_grupos::select('secundaria_grupos.id',
                'secundaria_grupos.secundaria_materia_id',
                'secundaria_materias.matNombre',
                'secundaria_materias.matSemestre',
                'secundaria_grupos.plan_id',
                'secundaria_grupos.periodo_id',
                'secundaria_grupos.gpoGrado',
                'secundaria_grupos.gpoClave',
                'secundaria_grupos.gpoTurno',
                'secundaria_grupos.empleado_id_docente',
                'secundaria_empleados.empNombre',
                'secundaria_empleados.empApellido1',
                'secundaria_empleados.empApellido2',
                'secundaria_grupos.empleado_id_auxiliar',
                'empleados.empNombre as empNombre_aux',
                'empleados.empApellido1 as empApellido1_aux',
                'empleados.empApellido2 as empApellido2_aux',
                'secundaria_grupos.gpoMatComplementaria',
                'secundaria_grupos.nombreAlternativo',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'programas.progNombre')
                ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
                ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
                ->leftJoin('secundaria_empleados as empleados', 'secundaria_grupos.empleado_id_auxiliar', '=', 'empleados.id')
                ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'secundaria_materias.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->where('secundaria_grupos.id', '=', $id)
                ->where('secundaria_grupos.empleado_id_docente', '=', $usuarioLogueado)
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

        if ($request->ajax()) {

            $secundaria_grupo = Secundaria_grupos::where('id', $id)->first();

            // $resultado_array =  DB::select("call procSecundariaMesAMostrar(" . $id . ", " . $secundaria_grupo->plan_id . ", " . $secundaria_grupo->periodo_id . ")");
            // $mesEvidencia = collect($resultado_array);


            // $mesEvidencia = Secundaria_grupos_evidencias::select('secundaria_grupos_evidencias.id', 'secundaria_grupos_evidencias.secundaria_grupo_id',
            // 'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id', 'secundaria_mes_evaluaciones.mes')
            // ->join('secundaria_mes_evaluaciones', 'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id', '=', 'secundaria_mes_evaluaciones.id')
            // ->join('secundaria_grupos', 'secundaria_grupos_evidencias.secundaria_grupo_id', '=', 'secundaria_grupos.id')
            // ->where('secundaria_grupos_evidencias.secundaria_grupo_id', $id)
            // ->where('secundaria_mes_evaluaciones.mes', 'SEPTIEMBRE')
            // ->get();
            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');
            $fechaHoy = $fechaActual->format('Y-m-d');

            $mesEvidencia = Secundaria_grupos_evidencias::select('secundaria_grupos_evidencias.id',
            'secundaria_grupos_evidencias.secundaria_grupo_id',
            'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id',
            'secundaria_mes_evaluaciones.mes',
            'secundaria_grupos.periodo_id',
            'secundaria_calendario_calificaciones_docentes.calInicioCaptura',
            'secundaria_calendario_calificaciones_docentes.calFinCaptura')
            ->join('secundaria_mes_evaluaciones', 'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id', '=', 'secundaria_mes_evaluaciones.id')
            ->join('secundaria_grupos', 'secundaria_grupos_evidencias.secundaria_grupo_id', '=', 'secundaria_grupos.id')
            ->join('departamentos', 'secundaria_mes_evaluaciones.departamento_id', '=', 'departamentos.id')
            ->leftJoin('secundaria_calendario_calificaciones_docentes', 'secundaria_mes_evaluaciones.id', '=', 'secundaria_calendario_calificaciones_docentes.secundaria_mes_evaluaciones_id')
            ->where('secundaria_grupos_evidencias.secundaria_grupo_id', $id)
            ->where('secundaria_calendario_calificaciones_docentes.calInicioCaptura', '<=', $fechaHoy)
            ->where('secundaria_calendario_calificaciones_docentes.calFinCaptura', '>=', $fechaHoy)
            ->where('secundaria_calendario_calificaciones_docentes.plan_id', $secundaria_grupo->plan_id)
            ->where('secundaria_calendario_calificaciones_docentes.periodo_id', $secundaria_grupo->periodo_id)
            ->get();

            return response()->json([
                'mesEvidencia' => $mesEvidencia
            ]);
        }
    }


    public function getMeses(Request $request, $id)
    {
        if($request->ajax()){

            $meses = Secundaria_grupos_evidencias::select('secundaria_grupos_evidencias.id',
            'secundaria_grupos_evidencias.secundaria_grupo_id',
            'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id',
            'secundaria_mes_evaluaciones.mes')
            ->join('secundaria_mes_evaluaciones', 'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id', '=', 'secundaria_mes_evaluaciones.id')
            ->join('secundaria_grupos', 'secundaria_grupos_evidencias.secundaria_grupo_id', '=', 'secundaria_grupos.id')
            ->where('secundaria_grupos_evidencias.id', '=', $id)
            ->get();

            return response()->json($meses);
        }
    }

    public function getNumeroEvaluacion(Request $request, $mes)
    {
        if($request->ajax()){


            $numeroEvalucacion = Secundaria_grupos_evidencias::select('secundaria_grupos_evidencias.*',
            'secundaria_mes_evaluaciones.*')
            ->join('secundaria_mes_evaluaciones', 'secundaria_grupos_evidencias.secundaria_mes_evaluacion_id', '=', 'secundaria_mes_evaluaciones.id')
            // ->where('secundaria_mes_evaluaciones.mes', '=', $mes)
            ->where('secundaria_grupos_evidencias.id', '=', $mes)
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

        $secundaria_grupo = Secundaria_grupos::findOrFail($id);
        try {

            if ($secundaria_grupo->delete()) {
                alert('Escuela Modelo', 'El grupo se ha eliminado con éxito', 'success')->showConfirmButton();
                return redirect()->route('secundaria.secundaria_grupo.index');
            } else {
                alert()->error('Error...', 'No se puedo eliminar el grupo')->showConfirmButton();
                return redirect()->route('secundaria.secundaria_grupo.index');
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }

    }


    public function reporte_faltas($grupo_id)
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

        return view('secundaria.grupos.creat-reporte-faltas', [
            'secundaria_inscritos' => $secundaria_inscritos,
            'mesEvidencia' => $mesEvidencia
        ]);
    }

    public function imprimirFaltas(Request $request)
    {
        $mes_a_consultar = $request->mes_a_consultar;
        $grupo_id = $request->grupo_id;

        $secundaria_inscritos = Secundaria_inscritos::select('secundaria_inscritos.id',
        'secundaria_inscritos.grupo_id',
        'secundaria_inscritos.inscFaltasInjSep',
        'secundaria_inscritos.inscFaltasInjOct',
        'secundaria_inscritos.inscFaltasInjNov',
        'secundaria_inscritos.inscFaltasInjDic',
        'secundaria_inscritos.inscFaltasInjEne',
        'secundaria_inscritos.inscFaltasInjFeb',
        'secundaria_inscritos.inscFaltasInjMar',
        'secundaria_inscritos.inscFaltasInjAbr',
        'secundaria_inscritos.inscFaltasInjMay',
        'secundaria_inscritos.inscFaltasInjJun',
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
        'ubicacion.ubiNombre',
        'secundaria_empleados.empApellido1',
        'secundaria_empleados.empApellido2',
        'secundaria_empleados.empNombre',
        'escuelas.escClave',
        'escuelas.escNombre',
        'alumnos.aluClave'
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
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->where('secundaria_inscritos.grupo_id', '=', $grupo_id)
        ->whereNull('secundaria_inscritos.deleted_at')
        ->orderBy('personas.perApellido1', 'ASC')
        ->orderBy('personas.perApellido2', 'ASC')
        ->orderBy('personas.perNombre', 'ASC')
        ->get();

        $parametro_NombreArchivo = "pdf_secundaria_faltas";

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $pdf = PDF::loadView('secundaria.pdf.reporte_de_faltas.' . $parametro_NombreArchivo, [
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "mes_a_consultar" => $mes_a_consultar,
            "secundaria_inscritos" => $secundaria_inscritos
        ]);


        // $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');


    }
}

