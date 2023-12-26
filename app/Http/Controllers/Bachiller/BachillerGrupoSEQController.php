<?php

namespace App\Http\Controllers\Bachiller;

use App\clases\departamentos\MetodosDepartamentos;
use App\clases\horarios\MetodosHorarios;
use Auth;
use Validator;
use App\Models\User;
use App\Http\Helpers\Utils;
use App\Http\Models\Cgt;
use App\Http\Models\Horario;
use App\Http\Models\Periodo;
use App\Http\Models\Plan;
use App\Http\Models\Programa;
use App\Http\Models\Ubicacion;
use App\Http\Models\Departamento;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Aula;
use App\Http\Models\Bachiller\Bachiller_cch_calificaciones;
use App\Http\Models\Bachiller\Bachiller_cch_grupos;
use App\Http\Models\Bachiller\Bachiller_cch_grupos_evidencias;
use App\Http\Models\Bachiller\Bachiller_cch_horarios;
use App\Http\Models\Bachiller\Bachiller_cch_horariosadmivos;
use App\Http\Models\Bachiller\Bachiller_empleados;
use App\Http\Models\Bachiller\Bachiller_materias;
use App\Http\Models\Bachiller\Bachiller_materias_acd;
use App\Http\Models\Bachiller\Bachiller_mes_evaluaciones;
use App\Http\Models\Escuela;
use App\Http\Models\HorarioAdmivo;
use Carbon\Carbon;

class BachillerGrupoSEQController extends Controller
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
        return view('bachiller.grupos_chetumal.show-list');

    }


    public function list()
    {

        //BACHILLER PERIODO ACTUAL (MERIDA Y VALLADOLID)
        // $perActualUser = Auth::user()->empleado->escuela->departamento->perActual;

        $departamentoCCH = Departamento::with('ubicacion')->findOrFail(1);
        $perActualCCH = $departamentoCCH->perActual;

     



        $grupos = Bachiller_cch_grupos::select('bachiller_cch_grupos.id',
        'bachiller_cch_grupos.gpoGrado',
        'bachiller_cch_grupos.gpoClave',
        'bachiller_cch_grupos.gpoTurno',
        'bachiller_cch_grupos.gpoMatComplementaria',
        'bachiller_materias.id as materia_id',
        'bachiller_materias.matClave',
        'bachiller_materias.matNombre',
        'bachiller_materias.matNombreCorto',
        'bachiller_materias.matSemestre',
        'planes.id as plan_id',
        'planes.planClave',
        'planes.planPeriodos',
        'periodos.id as periodo_id',
        'periodos.perNumero',
        'periodos.perAnio',
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
        'bachiller_empleados.empApellido1',
        'bachiller_empleados.empApellido2',
        'bachiller_empleados.empNombre',
        'programas.id as programa_id',
        'programas.progClave',
        'programas.progNombre',
        'programas.progNombreCorto',
        'departamentos.perActual')
        ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
        ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('bachiller_empleados', 'bachiller_cch_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->whereIn('periodos.id', [$perActualCCH])
        ->orderBy('bachiller_cch_grupos.id', 'desc');

        //->where('periodos.id', $perActual)


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
                $query->whereRaw("CONCAT(perAnio) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('peranio', function ($query) {
                return $query->perAnio;
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

            ->filterColumn('periodo_numero', function ($query, $keyword) {
                $query->whereRaw("CONCAT(perNumero) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('periodo_numero', function ($query) {
                return $query->perNumero;
            })
            ->addColumn('action', function ($grupos) {
                $floatAnio = (float)$grupos->perAnio;
                if($floatAnio >= 2020)
                {
                
                    $btnModificarCalificaciones = "";
                    $btnEvidencias = '<a href="bachiller_grupo_seq/' . $grupos->id . '/evidencia" class="button button--icon js-button js-ripple-effect" title="Evidencias" >
                    <i class="material-icons">description</i>
                    </a>';
                
                    $btnModificarCalificaciones = '<a href="bachiller_grupo_seq/horario/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Horario">
                    <i class="material-icons">alarm_add</i>';

                    $btnEvidencias = '';

                    $btnModificarCalificacionesAlumno = '<a href="bachiller_calificacion_seq/grupo/' . $grupos->id . '/edit" class="button button--icon js-button js-ripple-effect" title="Ver/Editar calificaciones" >
                    <i class="material-icons">playlist_add_check</i>
                    </a>';
                
                $acciones = '<div class="row">'

                    .$btnModificarCalificaciones.

                    '                    
                    <a href="bachiller_inscritos_seq/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Alumnos" >
                        <i class="material-icons">assignment_turned_in</i>
                    </a>

                                      

                    <a href="bachiller_inscritos_seq/pase_lista/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Pase de lista" >
                    <i class="material-icons">assignment</i>
                    </a>

                    <a href="bachiller_grupo_seq/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                    <i class="material-icons">visibility</i>
                    </a>               

                    </div>';
                }else{
                    $acciones = '<div class="row">

                    <a href="bachiller_grupo_seq/' . $grupos->id . '/evidencia" class="button button--icon js-button js-ripple-effect" title="Evidencias" >
                    <i class="material-icons">description</i>
                    </a>

                    <a href="bachiller_inscritos_seq/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Alumnos" >
                        <i class="material-icons">assignment_turned_in</i>
                    </a>


                    <a href="bachiller_calificacion_seq/grupo/' . $grupos->id . '/edit" class="button button--icon js-button js-ripple-effect" title="Ver/Editar calificaciones" >
                    <i class="material-icons">playlist_add_check</i>
                    </a>


                    <a href="bachiller_grupo_seq/' . $grupos->id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                    <i class="material-icons">visibility</i>
                    </a>

                    </div>';
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
        $ubicaciones = Ubicacion::whereIn('id', [3])->get();
        $empleados = Bachiller_empleados::where('empEstado','A')->get();
        return view('bachiller.grupos_chetumal.create', [
            'ubicaciones' => $ubicaciones,
            'empleados' => $empleados
        ]);
    }


    public function materiaComplementaria(Request $request, $bachiller_materia_id, $plan_id, $periodo_id, $grado)
    {
        if ($request->ajax()) {

            $materiasACD = Bachiller_materias_acd::select('bachiller_materias_acd.id', 
            'bachiller_materias_acd.bachiller_materia_id', 'bachiller_materias_acd.plan_id', 'bachiller_materias_acd.periodo_id',
            'bachiller_materias_acd.gpoGrado', 'bachiller_materias_acd.gpoMatComplementaria',
            'bachiller_materias.matNombre',
            'bachiller_materias.matClave')
            ->join('bachiller_materias', 'bachiller_materias_acd.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('periodos', 'bachiller_materias_acd.periodo_id', '=', 'periodos.id')
            ->join('planes', 'bachiller_materias_acd.plan_id', '=', 'planes.id')
            ->where('bachiller_materias_acd.bachiller_materia_id', '=', $bachiller_materia_id)
            ->where('bachiller_materias_acd.plan_id', '=', $plan_id)
            ->where('bachiller_materias_acd.periodo_id', '=', $periodo_id)
            ->where('bachiller_materias_acd.gpoGrado', '=', $grado)
            ->get();

            return response()->json($materiasACD);
        }
    }

    public function getDepartamentos(Request $request, $id)
    {
        if($request->ajax()){
            // $departamentos = Departamento::with('ubicacion')->where('ubicacion_id','=',$id)
            // ->whereIn('depClave', ['SUP', 'POS'])->get();

            if (Auth::user()->bachiller == 1) {
                $departamentos = MetodosDepartamentos::buscarSoloAcademicos($id, ['BAC']);
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
                    $query->orWhere('escNombre', "like", "BACHILLER%");



                    if ($request->otro == "diplomados") {
                        $query->orWhere('escNombre', "like", "DIPLOMADOS%");
                    }
                })
            ->get();

            return response()->json($escuelas);
        }
    }

    // OBTENER PERIDO BACHILLER
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

        $grupo = Bachiller_cch_grupos::select("bachiller_cch_grupos.id as id", "planes.planClave as planClave", "programas.progClave as progClave",
            "bachiller_materias.matClave as matClave", "bachiller_materias.matNombre as matNombre", "optativas.optNombre as optNombre",
            "bachiller_cch_grupos.gpoGrado as gpoSemestre", "bachiller_cch_grupos.gpoClave as gpoClave", "bachiller_cch_grupos.gpoTurno as gpoTurno",
            "bachiller_cch_grupos.grupo_equivalente_id",
            "periodos.perNumero", "periodos.perAnio")
            ->join("bachiller_materias", "bachiller_materias.id", "=", "bachiller_cch_grupos.bachiller_materia_id")
            ->join("periodos", "periodos.id", "=", "bachiller_cch_grupos.periodo_id")
            ->join("planes", "planes.id", "=", "bachiller_cch_grupos.plan_id")
                ->join("programas", "programas.id", "=", "planes.programa_id")
            ->leftJoin("optativas", "optativas.id", "=", "bachiller_cch_grupos.optativa_id", "optativas.optNombre")
            ->where("bachiller_cch_grupos.periodo_id", "=", $periodo_id)
            ->whereNull("bachiller_cch_grupos.grupo_equivalente_id");


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
            $grupoEq = Bachiller_cch_grupos::where("id", "=", $request->grupo_equivalente_id)->first();

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
                'periodo_id' => 'required|unique:bachiller_cch_grupos,periodo_id,NULL,id,bachiller_materia_id,' .
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
        $grupo = Bachiller_cch_grupos::with("plan", "periodo", "bachiller_empleado", "bachiller_materia")
            ->where("bachiller_materia_id", "=", $request->materia_id)
            ->where("plan_id", "=", $request->plan_id)
            ->where("gpoGrado", "=", $request->gpoSemestre)
            ->where("gpoClave", "=", $request->gpoClave)
            ->where("gpoTurno", "=", $request->gpoTurno)
            ->where("periodo_id", "=", $request->periodo_id)
        ->first();



        if(!$request->ajax()) {
            if ($validator->fails()) {
                return redirect()->route('bachiller.bachiller_grupo.create')->withErrors($validator)->withInput();
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

            $grupo = Bachiller_cch_grupos::create([
                'bachiller_materia_id'     => $request->input('materia_id'),
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
        $bachiller_grupo = Bachiller_cch_grupos::with('plan','bachiller_materia','bachiller_empleado')->findOrFail($id);
        $docente_auxiliar = Bachiller_empleados::find($bachiller_grupo->empleado_id_auxiliar);
        $grupo_equivalente = Bachiller_cch_grupos::with('plan','bachiller_materia','bachiller_empleado')->find($bachiller_grupo->grupo_equivalente_id);

        return view('bachiller.grupos_chetumal.show', [
            'bachiller_grupo' => $bachiller_grupo,
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
        $empleados = Bachiller_empleados::where('empEstado','A')->get();
        $grupo = Bachiller_cch_grupos::with('plan','bachiller_materia','bachiller_empleado')->findOrFail($id);
        $periodos = Periodo::where('departamento_id',$grupo->plan->programa->escuela->departamento_id)->get();
        $programas = Programa::with('empleado','escuela')->where('escuela_id',$grupo->plan->programa->escuela_id)->get();
        $planes = Plan::with('programa')->where('programa_id',$grupo->plan->programa->id)->get();

        $materiasACD = Bachiller_materias_acd::select('bachiller_materias_acd.id', 
        'bachiller_materias_acd.bachiller_materia_id', 'bachiller_materias_acd.plan_id', 'bachiller_materias_acd.periodo_id',
        'bachiller_materias_acd.gpoGrado', 'bachiller_materias_acd.gpoMatComplementaria',
        'bachiller_materias.matNombre',
        'bachiller_materias.matClave')
        ->join('bachiller_materias', 'bachiller_materias_acd.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->join('periodos', 'bachiller_materias_acd.periodo_id', '=', 'periodos.id')
        ->join('planes', 'bachiller_materias_acd.plan_id', '=', 'planes.id')
        ->where('bachiller_materias_acd.bachiller_materia_id', '=', $grupo->bachiller_materia_id)
        ->where('bachiller_materias_acd.plan_id', '=', $grupo->plan_id)
        ->where('bachiller_materias_acd.periodo_id', '=', $grupo->periodo_id)
        ->where('bachiller_materias_acd.gpoGrado', '=', $grupo->gpoGrado)
        ->get();

        // if (!in_array($grupo->estado_act, ["A", "B"])) {
        //     alert()->error('Ups...', 'El grupo se encuentra cerrado, no se puede modificar')->showConfirmButton()->autoClose(5000);
        //     return redirect('grupo');
        // }

        $grupo_equivalente = Bachiller_cch_grupos::with('plan','periodo','bachiller_materia')->find($grupo->grupo_equivalente_id);



        $cgts = Cgt::where([['plan_id', $grupo->plan_id],['periodo_id', $grupo->periodo_id]])->get();
        $materias = Bachiller_materias::where([['plan_id', '=', $grupo->plan_id],['matSemestre', '=', $grupo->gpoGrado]])->get();
        // $optativas = Optativa::where('materia_id', '=', $grupo->materia_id)->get();




        return view('bachiller.grupos_chetumal.edit',compact('grupo','empleados','periodos','programas',
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
            return redirect('bachiller_grupo_seq/'.$id.'/edit')->withErrors($validator)->withInput();
        }

        try {
          
            $grupo = Bachiller_cch_grupos::findOrFail($id);
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
            $grupo->gpoExtraCurr                = $request->gpoExtraCurr;
            $grupo->gpoACD                      = $gpoACD;


            $success = $grupo->save();

            alert('Escuela Modelo', 'El grupo se ha actualizado con éxito','success')->showConfirmButton();
            return redirect()->back();
        }catch (QueryException $e){
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Error...'.$errorCode, $errorMessage)->showConfirmButton();
            return redirect('bachiller_grupo_seq/'.$id.'/edit')->withInput();
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                         llama la vista de evidencia                        */
    /* -------------------------------------------------------------------------- */
    public function evidenciaTable($id)
    {
        $grupo = Bachiller_cch_grupos::where('id', $id)->first();

        $meses = Bachiller_mes_evaluaciones::get();

        $Evidencias = Bachiller_cch_grupos_evidencias::where('bachiller_grupo_id', $id)->first();

        return view('bachiller.grupos_chetumal.evidencia', [
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
        $bachiller_grupo_id =            $request->bachiller_grupo_id;
        $bachiller_mes_evaluacion_id =   $request->bachiller_mes_evaluacion_id;
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

        $grupo_evidencia = Bachiller_cch_grupos_evidencias::where('bachiller_grupo_id', $bachiller_grupo_id)
        ->where('bachiller_mes_evaluacion_id', $bachiller_mes_evaluacion_id)
        ->first();

        // obtener listado de calificaciones en dicho mes seleccionado
        $calificaciones = Bachiller_cch_calificaciones::select('bachiller_cch_calificaciones.bachiller_cch_inscrito_id',
        'bachiller_cch_calificaciones.bachiller_cch_grupo_evidencia_id',
        'bachiller_mes_evaluaciones.id as bachiller_mes_evaluacion_id',
        'bachiller_cch_grupos.id')
        ->join('bachiller_cch_grupos_evidencias', 'bachiller_cch_calificaciones.bachiller_cch_grupo_evidencia_id', '=', 'bachiller_cch_grupos_evidencias.id')
        ->join('bachiller_mes_evaluaciones', 'bachiller_cch_grupos_evidencias.bachiller_mes_evaluacion_id', '=', 'bachiller_mes_evaluaciones.id')
        ->join('bachiller_cch_grupos', 'bachiller_cch_grupos_evidencias.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
        ->where('bachiller_mes_evaluaciones.id', '=', $bachiller_mes_evaluacion_id)
        ->where('bachiller_cch_grupos.id', '=', $bachiller_grupo_id)
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
                    'bachiller_grupo_id'          => $bachiller_grupo_id,
                    'bachiller_mes_evaluacion_id' => $bachiller_mes_evaluacion_id,
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

                    if($bachiller_mes_evaluacion_id == 1){
                        // array de id de los meses
                        $valor2 = [1,2,3,4,5,6,7,8,9,10];
                        $numeroVueltas = 10;
                    }
                    if($bachiller_mes_evaluacion_id == 2){
                        // array de id de los meses
                        $valor2 = [2,3,4,5,6,7,8,9,10];
                        $numeroVueltas = 9;
                    }
                    if($bachiller_mes_evaluacion_id == 3){
                        // array de id de los meses
                        $valor2 = [3,4,5,6,7,8,9,10];
                        $numeroVueltas = 8;
                    }
                    if($bachiller_mes_evaluacion_id == 4){
                        // array de id de los meses
                        $valor2 = [4,5,6,7,8,9,10];
                        $numeroVueltas = 7;
                    }
                    if($bachiller_mes_evaluacion_id == 5){
                        // array de id de los meses
                        $valor2 = [5,6,7,8,9,10];
                        $numeroVueltas = 6;
                    }
                    if($bachiller_mes_evaluacion_id == 6){
                        // array de id de los meses
                        $valor2 = [6,7,8,9,10];
                        $numeroVueltas = 5;
                    }
                    if($bachiller_mes_evaluacion_id == 7){
                        // array de id de los meses
                        $valor2 = [7,8,9,10];
                        $numeroVueltas = 4;
                    }
                    if($bachiller_mes_evaluacion_id == 8){
                        // array de id de los meses
                        $valor2 = [8,9,10];
                        $numeroVueltas = 3;
                    }
                    if($bachiller_mes_evaluacion_id == 9){
                        // array de id de los meses
                        $valor2 = [9,10];
                        $numeroVueltas = 2;
                    }
                    if($bachiller_mes_evaluacion_id == 10){
                        // array de id de los meses
                        $valor2 = [10];
                        $numeroVueltas = 1;
                    }

                    // array de evidencias
                    for ($i=0; $i < $numeroVueltas; $i++) {

                        $evidencias = new Bachiller_cch_grupos_evidencias();
                        $evidencias['bachiller_grupo_id']          = $bachiller_grupo_id;
                        $evidencias['bachiller_mes_evaluacion_id'] = $valor2[$i];
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
                    Bachiller_cch_grupos_evidencias::create([
                        'bachiller_grupo_id'          => $bachiller_grupo_id,
                        'bachiller_mes_evaluacion_id' => $bachiller_mes_evaluacion_id,
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

            $evidencias = Bachiller_cch_grupos_evidencias::select('bachiller_cch_grupos_evidencias.*')
            ->where('bachiller_grupo_id', $id_grupo)
            ->where('bachiller_mes_evaluacion_id', $id_mes)
            ->get();

            return response()->json($evidencias);
        }
    }

    public function getGrupos(Request $request, $id)
    {

        if($request->ajax()){

            $grupos = Bachiller_cch_grupos::select('bachiller_cch_grupos.id',
            'bachiller_cch_grupos.bachiller_materia_id',
            'bachiller_materias.matNombre',
            'bachiller_materias.matSemestre',
            'bachiller_cch_grupos.plan_id',
            'bachiller_cch_grupos.periodo_id',
            'bachiller_cch_grupos.gpoGrado',
            'bachiller_cch_grupos.gpoClave',
            'bachiller_cch_grupos.gpoTurno',
            'bachiller_cch_grupos.empleado_id_docente',
            'bachiller_empleados.empNombre',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_cch_grupos.empleado_id_auxiliar',
            'empleados.empNombre as empNombre_aux',
            'empleados.empApellido1 as empApellido1_aux',
            'empleados.empApellido2 as empApellido2_aux',
            'bachiller_cch_grupos.gpoMatComplementaria',
            'bachiller_cch_grupos.nombreAlternativo',
            'periodos.perFechaInicial',
            'periodos.perFechaFinal',
            'programas.progNombre')
            ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('bachiller_empleados', 'bachiller_cch_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
            ->leftJoin('bachiller_empleados as empleados', 'bachiller_cch_grupos.empleado_id_auxiliar', '=', 'empleados.id')
            ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->where('bachiller_cch_grupos.periodo_id', '=', $id)
            ->get();

            return response()->json($grupos);
        }
    }

    public function getMaterias(Request $request, $id)
    {

        if($request->ajax()){
            $grupos = Bachiller_cch_grupos::select('bachiller_cch_grupos.id',
            'bachiller_cch_grupos.bachiller_materia_id',
            'bachiller_materias.matNombre',
            'bachiller_materias.matSemestre',
            'bachiller_cch_grupos.plan_id',
            'bachiller_cch_grupos.periodo_id',
            'bachiller_cch_grupos.gpoGrado',
            'bachiller_cch_grupos.gpoClave',
            'bachiller_cch_grupos.gpoTurno',
            'bachiller_cch_grupos.empleado_id_docente',
            'bachiller_empleados.empNombre',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_cch_grupos.empleado_id_auxiliar',
            'empleados.empNombre as empNombre_aux',
            'empleados.empApellido1 as empApellido1_aux',
            'empleados.empApellido2 as empApellido2_aux',
            'bachiller_cch_grupos.gpoMatComplementaria',
            'bachiller_cch_grupos.nombreAlternativo',
            'periodos.perFechaInicial',
            'periodos.perFechaFinal',
            'programas.progNombre')
            ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('bachiller_empleados', 'bachiller_cch_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
            ->leftJoin('bachiller_empleados as empleados', 'bachiller_cch_grupos.empleado_id_auxiliar', '=', 'empleados.id')
            ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->where('bachiller_cch_grupos.id', '=', $id)
            ->get();

            return response()->json($grupos);
        }
    }

    /* -------------------------------------------------------------------------- */
    /*           obtener los meses de evidencia dados de alta por grupo           */
    /* -------------------------------------------------------------------------- */
    public function getMesEvidencias(Request $request, $id)
    {
      
        if($request->ajax()){

            $mesEvidencia = Bachiller_cch_grupos_evidencias::select('bachiller_cch_grupos_evidencias.id',
            'bachiller_cch_grupos_evidencias.bachiller_grupo_id',
            'bachiller_cch_grupos_evidencias.bachiller_mes_evaluacion_id',
            'bachiller_mes_evaluaciones.mes',
            'bachiller_cch_grupos.periodo_id')
            ->join('bachiller_mes_evaluaciones', 'bachiller_cch_grupos_evidencias.bachiller_mes_evaluacion_id', '=', 'bachiller_mes_evaluaciones.id')
            ->join('bachiller_cch_grupos', 'bachiller_cch_grupos_evidencias.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
            ->where('bachiller_cch_grupos_evidencias.bachiller_grupo_id', '=', $id)
            ->orderBy('bachiller_cch_grupos_evidencias.bachiller_mes_evaluacion_id', 'ASC')
            ->get();


            return response()->json([
                'mesEvidencia' => $mesEvidencia
            ]);
           


        }
    }


    public function getMeses(Request $request, $id)
    {
        if($request->ajax()){

            $meses = Bachiller_cch_grupos_evidencias::select('bachiller_cch_grupos_evidencias.id',
            'bachiller_cch_grupos_evidencias.bachiller_grupo_id',
            'bachiller_cch_grupos_evidencias.bachiller_mes_evaluacion_id',
            'bachiller_mes_evaluaciones.mes')
            ->join('bachiller_mes_evaluaciones', 'bachiller_cch_grupos_evidencias.bachiller_mes_evaluacion_id', '=', 'bachiller_mes_evaluaciones.id')
            ->join('bachiller_cch_grupos', 'bachiller_cch_grupos_evidencias.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
            ->where('bachiller_cch_grupos_evidencias.id', '=', $id)
            ->get();

            return response()->json($meses);
        }
    }

    public function getNumeroEvaluacion(Request $request, $mes)
    {
        if($request->ajax()){


            $numeroEvalucacion = Bachiller_cch_grupos_evidencias::select('bachiller_cch_grupos_evidencias.*',
            'bachiller_mes_evaluaciones.*')
            ->join('bachiller_mes_evaluaciones', 'bachiller_cch_grupos_evidencias.bachiller_mes_evaluacion_id', '=', 'bachiller_mes_evaluaciones.id')
            // ->where('bachiller_mes_evaluaciones.mes', '=', $mes)
            ->where('bachiller_cch_grupos_evidencias.id', '=', $mes)
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

        $bachiller_grupo = Bachiller_cch_grupos::findOrFail($id);
        try {

            if ($bachiller_grupo->delete()) {
                alert('Escuela Modelo', 'El grupo se ha eliminado con éxito', 'success')->showConfirmButton();
                return redirect()->route('bachiller.bachiller_grupo_seq.index');
            } else {
                alert()->error('Error...', 'No se puedo eliminar el grupo')->showConfirmButton();
                return redirect()->route('bachiller.bachiller_grupo_seq.index');
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }

    }

    public function horario($id)
    {
        $bachiller_grupo = Bachiller_cch_grupos::with('bachiller_materia', 'bachiller_empleado', 'plan', 'periodo')->find($id);

        $ubicacion_id = $bachiller_grupo->plan->programa->escuela->departamento->ubicacion_id;
        $aulas = Aula::where('ubicacion_id', $ubicacion_id)->get();
        $horarios = Bachiller_cch_horarios::with('bachiller_grupo_seq')->where('grupo_id',$id);
        //VALIDA PERMISOS EN EL PROGRAMA
        // if (Utils::validaPermiso('grupo', $grupo->plan->programa_id)) {
        //     alert()->error('Ups...', 'Sin privilegios en el programa!')->showConfirmButton()->autoClose(2000);
        //     return redirect('grupo');
        // }



        return view('bachiller.grupos_chetumal.horario',compact('bachiller_grupo','aulas','horarios'));
    }

    
    /**
     * Add horario.
     *
     * @return \Illuminate\Http\Response
     */
    public function agregarHorario(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'grupo_id' => 'required',
                'aula_id'  => 'required',
                'ghDia'    => 'required|max:1',
                'ghInicio' => 'required|max:2',
                'ghFinal'  => 'required|max:2',
            ]
        );

        if (!$request->ajax()) {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }



        $grupo_id = $request->grupo_id;
        $empleado_id = $request->empleado_id;
        $aula_id = $request->aula_id;
        $ghDia = $request->ghDia;

        $ghInicio = $request->ghInicio;
        $gMinInicio = $request->gMinInicio;

        $ghFinal = $request->ghFinal;
        $gMinFinal = $request->gMinFinal;

        $horaMinInicio = $ghInicio . $gMinInicio;
        $horaMinFinal  = $ghFinal . $gMinFinal;

        $peridoId = DB::table("bachiller_cch_grupos")->select("periodo_id")->where("id", "=", $grupo_id)->first();
        $periodoId = $peridoId->periodo_id;



        if (!$request->ajax()) {
            if ($horaMinFinal <= $horaMinInicio) {
                alert()->error('Ups...', "Horario no valido")->showConfirmButton();
                return back()->withInput();
            }
        }

        if($request->ajax()) {
            if ($horaMinFinal <= $horaMinInicio) {

                return response()->json([
                    "res" => false,
                    "msg" => "Horario no valido"
                ]);
            }
        }


        try {



            Bachiller_cch_horarios::create([
                'grupo_id'      => $grupo_id,
                'aula_id'       => $aula_id,
                'ghDia'         => $request->ghDia,

                'ghInicio'      => $ghInicio,
                'gMinInicio'    => (int) $gMinInicio,

                'ghFinal'       => $ghFinal,
                'gMinFinal'     => (int) $gMinFinal
            ]);


            //COPIAR LOS HORARIOS A LOS GRUPOS HIJOS
            if (!$request->ajax()) {
                $horariosPadre = Bachiller_cch_horarios::where("grupo_id", "=", $grupo_id)->get();


                $gruposHijo = Bachiller_cch_grupos::where("grupo_equivalente_id", "=", $grupo_id)->get();
                $gruposHijoIds = $gruposHijo->map(function($item, $key) {
                    return $item->id;
                });


                if (count($gruposHijoIds) > 0) {
                    if (Bachiller_cch_horarios::whereIn("grupo_id",  $gruposHijoIds)->first()) {
                        DB::table("bachiller_cch_horarios")->whereIn("grupo_id",  $gruposHijoIds)->delete();
                    }


                    foreach ($gruposHijoIds as $grupoId) {
                        $nuevosHorarios = collect();
                        foreach ($horariosPadre as $item) {
                            $nuevosHorarios->push([
                                "grupo_id"=> $grupoId,
                                "aula_id" => $item->aula_id,
                                "ghDia"   => $item->ghDia,
                                "ghInicio" => $item->ghInicio,
                                "ghFinal"  => $item->ghFinal,
                                "gMinFinal" => $item->gMinFinal,
                                "gMinInicio" => $item->gMinInicio,
                                "usuario_at" => Auth::user()->id
                            ]);
                        }

                        Bachiller_cch_horarios::insert($nuevosHorarios->all());
                    }
                }
            }


            if($request->ajax()) {
                return response()->json([
                    "res" => true,
                    "msg" => "success"
                ]);
            }

            if (!$request->ajax()) {
                alert('Escuela Modelo', 'El horario se ha creado con éxito', 'success')->showConfirmButton();
                return redirect()->back()->withInput();
            }

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect()->back()->withInput();
        }

    }

    public function listHorario(Request $request, $id)
    {
        $horario = Bachiller_cch_horarios::with('bachiller_grupo_chetumal.bachiller_materia','aula')->where('grupo_id', $id)->select('bachiller_cch_horarios.*');

        return Datatables::of($horario)->addColumn('dia', function($horario) {
            return Utils::diaSemana($horario->ghDia);
        })


        ->addColumn('horaInicio', function($horario) {
            return $horario->ghInicio . " : " . $horario->gMinInicio;
        })
        ->addColumn('horaFinal', function($horario) {
            return $horario->ghFinal . " : " . $horario->gMinFinal;
        })

        ->addColumn('materia', function($horario) {
            return $horario->bachiller_grupo_chetumal->bachiller_materia->matClave ."-". $horario->bachiller_grupo_chetumal->bachiller_materia->matNombre;
        })

        // ->addColumn('action', function($horario) use ($request) {
        //     $btnDelete = "";
        //     if (!$horario->bachiller_grupo_chetumal->grupo_equivalente_id) {
        //         if (!$request->ajax()) {
        //             $btnDelete = '<div class="row">
        //                 <div class="col s1">
        //                     <a href="'.url('bachiller_grupo_chetumal/eliminarHorario/'.$horario->id.'/'.$horario->grupo_id).'" class="button button--icon js-button js-ripple-effect" title="Eliminar horario">
        //                         <i class="material-icons">delete</i>
        //                     </a>
        //                 </div>
        //             </row>';
        //         }

        //         if ($request->ajax()) {
        //             $btnDelete = '<div class="row">
        //                 <div class="col s1">
        //                     <a href="'.url('bachiller_grupo_chetumal/eliminarHorario/'.$horario->id.'/'.$horario->grupo_id).'" data-grupo-id="'.$horario->grupo_id.'" data-horario-id="'.$horario->id.'"  class="btn-delete-horario button button--icon js-button js-ripple-effect" title="Eliminar horario">
        //                         <i class="material-icons">delete</i>
        //                     </a>
        //                 </div>
        //             </row>';
        //         }
        //     }

        //     return $btnDelete;
        // })
        ->make(true);
    }

     /**
     * Delete horario.
     *
     * @return \Illuminate\Http\Response
     */
    public function eliminarHorario(Request $request,$id,$grupo_id)
    {
        $horario = Bachiller_cch_horarios::findOrFail($id);
        $horarios_equivalentes = MetodosHorarios::buscarHorariosEquivalentes($horario);

        if($horarios_equivalentes->isNotEmpty()){
            $horarios_equivalentes->each(static function($horario) {
                $horario->delete();
            });
        }
        $horario->delete();

        if (!$request->ajax()) {
            alert('Escuela Modelo', 'El horario se ha eliminado con éxito','success')->showConfirmButton();
            return redirect('bachiller_grupo_seq/horario/'.$grupo_id);
        }

        if ($request->ajax()) {
            return response()->json([
                "res" => true
            ]);
        }
    }

    public function verificarHorasRepetidas(Request $request)
    {
        $grupo_id = $request->grupo_id;
        $empleado_id = $request->empleado_id;
        $aula_id = $request->aula_id;
        $ghDia = $request->ghDia;

        $ghInicio = $request->ghInicio;
        $gMinInicio = $request->gMinInicio;

        $ghFinal = $request->ghFinal;
        $gMinFinal = $request->gMinFinal;

        $horaMinInicio = $ghInicio . $gMinInicio;
        $horaMinFinal  = $ghFinal . $gMinFinal;

        $periodoId = Bachiller_cch_grupos::find($grupo_id)->periodo_id;

        if(!$request->ajax()) {
            if ($horaMinFinal <= $horaMinInicio) {
                alert()->error('Ups...', "Horario no valido")->showConfirmButton();
                return back()->withInput();
            }
        }

        if($request->ajax()) {
            if ($horaMinFinal <= $horaMinInicio) {

                return response()->json([
                    "res" => false,
                    "msg" => "Horario no valido"
                ]);
            }
        }


        //VALIDA SI ESTA DENTRO DE LA FECHA INICIO Y FECHA FINAL DE UN MAESTRO
        $maestroOcupadoAdmin = Bachiller_cch_horariosadmivos::where('empleado_id', '=', $empleado_id)
            ->select("periodo_id", "hadmDia", "hadmHoraInicio", "hadmFinal")

            ->where('periodo_id', '=', $periodoId)
            ->where('hadmDia', '=', $ghDia)
            ->where(DB::raw('CONVERT(CONCAT(hadmFinal, gMinFinal), SIGNED)'), '>', (int) $horaMinInicio)
            ->where(DB::raw('CONVERT(CONCAT(hadmHoraInicio, gMinInicio), SIGNED)'), '<', (int) $horaMinFinal)
        ->first();


        //VALIDA SI ESTA DENTRO DE LA FECHA INICIO Y FECHA FINAL DE UN AULA
        $aulaOcupada = Bachiller_cch_horarios::leftJoin("bachiller_cch_grupos", "bachiller_cch_horarios.grupo_id", "=", "bachiller_cch_grupos.id")
            ->leftJoin("aulas", "bachiller_cch_horarios.aula_id", "=", "aulas.id")
            ->where('bachiller_cch_grupos.periodo_id', '=', $periodoId)
            ->where('aulas.aula_categoria_id', '=', 1)
            ->where('aula_id', $aula_id)
            ->where('ghDia', '=', $ghDia)
            ->where(DB::raw('CONVERT(CONCAT(ghFinal, gMinFinal), SIGNED)'), '>', (int) $horaMinInicio)
            ->where(DB::raw('CONVERT(CONCAT(ghInicio, gMinInicio), SIGNED)'), '<', (int) $horaMinFinal)
        ->first();

        //VALIDA SI ESTA DENTRO DE LA FECHA INICIO Y FECHA FINAL DE UN MAESTRO
        $maestroOcupado = Bachiller_cch_horarios::leftJoin("bachiller_cch_grupos", "bachiller_cch_horarios.grupo_id", "=", "bachiller_cch_grupos.id")
            ->leftJoin("aulas", "bachiller_cch_horarios.aula_id", "=", "aulas.id")
            ->where('aulas.aula_categoria_id', '=', 1)
            ->where('aula_id', $aula_id)
            ->where('bachiller_cch_grupos.empleado_id_docente', '=', $empleado_id)
            ->where('bachiller_cch_grupos.periodo_id', '=', $periodoId)
            ->where('ghDia', '=', $ghDia)

            ->where(DB::raw('CONVERT(CONCAT(ghFinal, gMinFinal), SIGNED)'), '>', (int) $horaMinInicio)
            ->where(DB::raw('CONVERT(CONCAT(ghInicio, gMinInicio), SIGNED)'), '<', (int) $horaMinFinal)
        ->first();

        if ($aulaOcupada || $maestroOcupado || $maestroOcupadoAdmin) {
            return response()->json([
                "res" => false
            ]);
        }

        return response()->json([
            "res" => true
        ]);
    }

        /**
     * Show user list.
     *
     */
    public function listHorarioAdmin(Request $request)
    {
        $horario = Bachiller_cch_horariosadmivos::where('empleado_id', '=', $request->empleado_id)
            ->where('periodo_id', '=', $request->periodo_id)
            ->select("bachiller_cch_horariosadmivos.id", "bachiller_cch_horariosadmivos.hadmDia", "bachiller_cch_horariosadmivos.hadmHoraInicio", "bachiller_cch_horariosadmivos.hadmFinal", "bachiller_cch_horariosadmivos.gMinInicio", "bachiller_cch_horariosadmivos.gMinFinal",
                DB::raw('CONCAT(bachiller_cch_horariosadmivos.hadmDia, "-", bachiller_cch_horariosadmivos.hadmHoraInicio, "-", bachiller_cch_horariosadmivos.hadmFinal) AS sortByDiaHInicioHFinal'))
            ->orderBy("sortByDiaHInicioHFinal");

        return Datatables::of($horario)
            ->addColumn('dia', function($horario) {
                return Utils::diaSemana($horario->hadmDia);
            })

            ->addColumn('horaInicio', function($horario) {
                return $horario->hadmHoraInicio . " : " . $horario->gMinInicio;
            })
            ->addColumn('horaFinal', function($horario) {
                return $horario->hadmFinal . " : " . $horario->gMinFinal;
            })
        ->make(true);
    }
}

