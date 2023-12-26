<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Utils;
use Illuminate\Database\QueryException;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use URL;
use Validator;
use Debugbar;

use App\Http\Models\Grupo;
use App\Http\Models\Curso;
use App\Http\Models\Cgt;
use App\Http\Models\Aula;
use App\Http\Models\Ubicacion;
use App\Http\Models\Empleado;
use App\Http\Models\Horario;
use App\Http\Models\Periodo;
use App\Http\Models\Programa;
use App\Http\Models\Plan;
use App\Http\Models\Materia;
use App\Http\Models\Optativa;
use App\Http\Models\Preescolar_grupo;
use App\Http\Models\Preescolar_inscrito;
use App\Http\Models\Preescolar_materia;
use App\Http\Models\Preescolar_calificacion;

class GrupoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permisos:grupo',['except' => ['index','show','list','getGrupo','getGrupos']]);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        $empleado_id = Auth::user()->empleado->id;
        $perActual = Auth::user()->empleado->escuela->departamento->perActual;
        $grupo = Preescolar_grupo::with('preescolar_materia','periodo','plan.programa.escuela.departamento.ubicacion')->select('preescolar_grupos.*')
            ->where('preescolar_grupos.periodo_id',$perActual)
            ->where('preescolar_grupos.empleado_id_docente',$empleado_id);
        dd($grupo); */

            return View('grupo.show-list');


    }

    /**
     * Show user list.
     *
     */
    public function list()
    {
        $empleado_id = Auth::user()->empleado->id;
        $perActual = Auth::user()->empleado->escuela->departamento->perActual;

        $grupo = Grupo::with('materia','periodo','plan.programa.escuela.departamento.ubicacion')->select('grupos.*')
            ->where('grupos.periodo_id',$perActual)
            ->where('grupos.empleado_id',$empleado_id);




        return Datatables::of($grupo)
            ->addColumn('action', function($grupo)
            {
                $acciones = '';

                    $acciones = '<div class="row">
                    <a href="calificacion/agregar/' . Auth::user()->empleado->escuela->departamento->depClave . '/' . $grupo->id . '" class="button button--icon js-button js-ripple-effect" title="Calificaciones" >
                        <i class="material-icons">assignment_turned_in</i>
                    </a>
                    <a href="grupo/' . $grupo->id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                        <i class="material-icons">visibility</i>
                    </a>
                    <a href="calificacion/reporte/' . $grupo->id . '" class="button button--icon js-button js-ripple-effect" title="Reporte" target="_blank">
                        <i class="material-icons">picture_as_pdf</i>
                    </a>

                    <a href="calificacion/reporte/listas_evaluacion_ordinaria/' . $grupo->id . '" class="button button--icon js-button js-ripple-effect" title="Lista Evaluación Ordinaria" target="_blank">
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    </div>';

                return $acciones;
            })
        ->make(true);
    }

    /**
     * Show user list.
     *
     */
    public function listHorario($id)
    {
        $horario = Horario::with('grupo','aula')->where('grupo_id',$id)->select('horarios.*');;
        return Datatables::of($horario)
            ->addColumn('dia',function($horario){
                return Utils::diaSemana($horario->ghDia);
            })
            ->addColumn('action',function($horario){
                return '<div class="row">
                    <div class="col s1">
                    <a href="'.url('grupo/eliminarHorario/' . $horario->id . '/' . $horario->grupo_id) . '" class="button button--icon js-button js-ripple-effect" title="Eliminar horario">
                        <i class="material-icons">delete</i>
                    </a>
                </div>';
            })
            ->make(true);
    }

    /**
     * Show user list equivalente.
     *
     */
    public function listEquivalente()
    {
        $grupo = Grupo::with('materia', 'periodo', 'plan.programa')->select('grupos.*');

        return Datatables::of($grupo)
            ->addColumn('action', function($grupo) {
                return '<div class="row">
                    <div class="col s1">
                        <button class="btn modal-close" title="Ver" onclick="seleccionarGrupo('.$grupo->id.')">
                            <i class="material-icons">done</i>
                        </button>
                    </div>
                </div>';
            })
            ->make(true);
    }

    /**
     * Show grupo.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGrupo(Request $request, $id)
    {
        if ($request->ajax()) {
            $grupo = Grupo::with('materia', 'empleado.persona', 'plan.programa', 'periodo')->find($id);

            return response()->json($grupo);
        }
    }

    /**
     * Show grupos.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGrupos(Request $request, $curso_id)
    {
        if ($request->ajax()) {
            //CURSO SELECCIONADO
            $curso = Curso::find($curso_id);
            $cgt = Cgt::find($curso->cgt_id);
            //VALIDA EL SEMESTRE DEL CGT Y EL GRUPO
            $grupos = Grupo::with('materia', 'empleado.persona')
                ->where('gpoSemestre', $cgt->cgtGradoSemestre)
                ->where('plan_id',$cgt->plan_id)
                ->where('periodo_id',$cgt->periodo_id)->get();

            return response()->json($grupos);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubicaciones = Ubicacion::all();
        $empleados = Empleado::with('persona')->get();
        return view('grupo.create', compact('ubicaciones', 'empleados'));
    }

    /**
     * Show create horario.
     *
     * @return \Illuminate\Http\Response
     */
    public function horario($id)
    {
        $grupo = Grupo::with('materia', 'empleado.persona', 'plan')->find($id);
        $ubicacion_id = Auth::user()->empleado->escuela->departamento->ubicacion->id;
        $aulas = Aula::where('ubicacion_id', $ubicacion_id)->get();
        $horarios = Horario::with('grupo')->where('grupo_id', $id);

        return view('grupo.horario', compact('grupo', 'aulas', 'horarios'));
    }


    /**
     * Delete horario.
     *
     * @return \Illuminate\Http\Response
     */
    public function eliminarHorario($id,$grupo_id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();
        alert('Felicidades...', 'El horario se ha eliminado con éxito', 'success');
        return redirect('grupo/horario/' . $grupo_id);
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
                'aula_id' => 'required',
                'ghDia' => 'required|max:1',
                'ghInicio' => 'required|max:2',
                'ghFinal' => 'required|max:2',
            ]
        );
        if ($validator->fails()) {
            return redirect ('grupo/horario/' . $grupo_id)->withErrors($validator)->withInput();
        } else {
            $grupo_id = $request->input('grupo_id');
            $empleado_id = $request->input('empleado_id');
            $aula_id = $request->input('aula_id');
            $ghInicio = $request->input('ghInicio');
            $ghFinal = $request->input('ghFinal');
            if ($ghFinal <= $ghInicio) {
                alert()
                    ->error('Ups...', "Horario no valido")
                    ->showConfirmButton();
                return redirect('grupo/horario/'.$grupo_id)->withInput();
            } else {
                //VALIDA SI ESTA DENTRO DE LA FECHA INICIO Y FECHA FINAL DE UN AULA
                $aulaOcupada = Horario::with('grupo')
                    ->where('aula_id',$aula_id)
                    ->where('ghFinal','>',$ghInicio)
                    ->where('ghInicio','<',$ghFinal)->first();

                //VALIDA SI ESTA DENTRO DE LA FECHA INICIO Y FECHA FINAL DE UN MAESTRO
                $maestroOcupado = Horario::with('grupo')
                ->whereHas('grupo', function($query) use ($empleado_id) {
                    $query->whereEmpleadoId($empleado_id);
                })->where('ghFinal', '>', $ghInicio)->where('ghInicio', '<', $ghFinal)->first();

                if ($aulaOcupada || $maestroOcupado) {
                    $mensaje = "";
                    if ($aulaOcupada) {
                        $mensaje .= "Aula no disponible \n";
                    }
                    if ($maestroOcupado) {
                        $mensaje .= "Horario de maestro no disponible";
                    }
                    alert()
                        ->error('Ups...', $mensaje)
                        ->showConfirmButton();
                    return redirect('grupo/horario/'.$grupo_id)->withInput();
                }else{
                    try {
                        Horario::create([
                            'grupo_id'      => $grupo_id,
                            'aula_id'       => $aula_id,
                            'ghDia'         => $request->input('ghDia'),
                            'ghInicio'      => $ghInicio,
                            'ghFinal'       => $ghFinal
                        ]);
                        alert('Felicidades...', 'El horario se ha creado con éxito','success');
                        return redirect('grupo/horario/'.$grupo_id)->withInput();
                    } catch (QueryException $e) {
                        $errorCode = $e->errorInfo[1];
                        $errorMessage = $e->errorInfo[2];
                        alert()
                            ->error('Ups...' . $errorCode, $errorMessage)
                            ->showConfirmButton();
                        return redirect('grupo/horario/'.$grupo_id)->withInput();
                    }
                }
            }

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'periodo_id'    => 'required|unique:grupos,periodo_id,NULL,id,materia_id,'
                    . $request->input('materia_id') . ',plan_id,'
                    . $request->input('plan_id') . ',gpoSemestre,'
                    . $request->input('gpoSemestre') . ',gpoClave,'
                    . $request->input('gpoClave') . ',gpoTurno,'
                    . $request->input('gpoTurno') . ',deleted_at,NULL',
                'materia_id'    => 'required',
                'empleado_id'   => 'required',
                'plan_id'       => 'required',
                'gpoSemestre'   => 'required',
                'gpoClave'      => 'required',
                'gpoTurno'      => 'required'
            ],
            [
                'periodo_id.unique' => "El grupo ya existe",
            ]
        );

        if ($validator->fails()) {
            return redirect ('grupo/create')->withErrors($validator)->withInput();
        } else {
            $programa_id = $request->input('programa_id');

            try {
                Grupo::create([
                    'periodo_id'                => $request->input('periodo_id'),
                    'materia_id'                => $request->input('materia_id'),
                    'plan_id'                   => $request->input('plan_id'),
                    'gpoSemestre'               => $request->input('gpoSemestre'),
                    'gpoClave'                  => $request->input('gpoClave'),
                    'gpoTurno'                  => $request->input('gpoTurno'),
                    'empleado_id'               => $request->input('empleado_id'),
                    'empleado_sinodal_id'       => Utils::validaEmpty($request->input('empleado_sinodal_id')),
                    'gpoMatClaveComplementaria' => $request->input('gpoMatClaveComplementaria'),
                    'gpoFechaExamenOrdinario'   => Utils::validaEmpty($request->input('gpoFechaExamenOrdinario')),
                    'gpoHoraExamenOrdinario'    => Utils::validaEmpty($request->input('gpoHoraExamenOrdinario')),
                    'gpoCupo'                   => Utils::validaEmpty($request->input('gpoCupo')),
                    'gpoNumeroFolio'            => $request->input('gpoNumeroFolio'),
                    'gpoNumeroActa'             => $request->input('gpoNumeroActa'),
                    'gpoNumeroLibro'            => $request->input('gpoNumeroLibro'),
                    'grupo_equivalente_id'      => Utils::validaEmpty($request->input('grupo_equivalente_id')),
                    'optativa_id'               => Utils::validaEmpty($request->input('optativa_id')),
                ]);
                alert('Felicidades...', 'El grupo se ha creado con éxito','success');

                return redirect('grupo');
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                    ->error('Error...'.$errorCode, $errorMessage)
                    ->showConfirmButton();
                return redirect('grupo/create')->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grupo = Grupo::with('plan', 'optativa.materia', 'materia', 'empleado.persona')->findOrFail($id);
        $sinodal = Empleado::with('persona')->find($grupo->empleado_sinodal_id);
        $grupo_equivalente = Grupo::with('plan', 'optativa.materia', 'materia', 'empleado.persona')->find($grupo->grupo_equivalente_id);

        return view('grupo.show',compact('grupo', 'sinodal', 'grupo_equivalente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empleados = Empleado::with('persona')->get();
        $grupo = Grupo::with('plan', 'optativa.materia', 'materia', 'empleado.persona')->findOrFail($id);
        $periodos = Periodo::where('departamento_id', $grupo->plan->programa->escuela->departamento_id)->get();
        $programas = Programa::with('empleado', 'escuela')->where('escuela_id', $grupo->plan->programa->escuela_id)->get();
        $planes = Plan::with('programa')->where('programa_id', $grupo->plan->programa->id)->get();
        $grupo_equivalente = Grupo::with('plan', 'periodo', 'optativa.materia', 'materia', 'empleado.persona')->find($grupo->grupo_equivalente_id);
        $cgts = Cgt::where([['plan_id', $grupo->plan_id], ['periodo_id', $grupo->periodo_id]])->get();
        $materias = Materia::where([['plan_id', '=', $grupo->plan_id], ['matSemestre', '=', $grupo->gpoSemestre]])->get();
        $optativas = Optativa::where('materia_id', '=', $grupo->materia_id)->get();
        return view('grupo.edit',compact('grupo', 'empleados', 'periodos', 'programas', 'planes', 'cgts', 'materias', 'optativas', 'grupo_equivalente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            [
                'periodo_id'    => 'required',
                'materia_id'    => 'required',
                'empleado_id'   => 'required',
                'plan_id'       => 'required',
                'gpoSemestre'   => 'required',
                'gpoClave'      => 'required',
                'gpoTurno'      => 'required'
            ]
        );

        if ($validator->fails()) {

            return redirect('grupo/'.$id.'/edit')->withErrors($validator)->withInput();
        } else {
            $programa_id = $request->input('programa_id');

            try {
                $grupo = Grupo::findOrFail($id);
                $grupo->empleado_id                 = $request->input('empleado_id');
                $grupo->empleado_sinodal_id         = Utils::validaEmpty($request->input('empleado_sinodal_id'));
                $grupo->gpoMatClaveComplementaria   = $request->input('gpoMatClaveComplementaria');
                $grupo->gpoFechaExamenOrdinario     = Utils::validaEmpty($request->input('gpoFechaExamenOrdinario'));
                $grupo->gpoHoraExamenOrdinario      = Utils::validaEmpty($request->input('gpoHoraExamenOrdinario'));
                $grupo->gpoCupo                     = Utils::validaEmpty($request->input('gpoCupo'));
                $grupo->gpoNumeroFolio              = $request->input('gpoNumeroFolio');
                $grupo->gpoNumeroActa               = $request->input('gpoNumeroActa');
                $grupo->gpoNumeroLibro              = $request->input('gpoNumeroLibro');
                $grupo->grupo_equivalente_id        = Utils::validaEmpty($request->input('grupo_equivalente_id'));
                $grupo->optativa_id                 = Utils::validaEmpty($request->input('optativa_id'));
                $grupo->save();
                alert('Felicidades...', 'El grupo se ha actualizado con éxito', 'success');

                return redirect('grupo');
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                    ->error('Error...'.$errorCode, $errorMessage)
                    ->showConfirmButton();
                return redirect('grupo/'.$id.'/edit')->withInput();
            }
        }
    }

    public function cambiarEstado($id,$estado_act)
    {
        try {
            $grupo = Grupo::findOrFail($id);
            $grupo->estado_act = $estado_act;
            $grupo->save();
            alert('Felicidades...', 'El grupo se abrio con éxito', 'success');

            return redirect('calificacion/agregar/' . Auth::user()->empleado->escuela->departamento->depClave . '/' . $id)->withInput();
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()
                ->error('Error...' . $errorCode, $errorMessage)
                ->showConfirmButton();

            return redirect('calificacion/agregar/' . Auth::user()->empleado->escuela->departamento->depClave . '/' . $id)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grupo = Grupo::findOrFail($id);
        try {
            $programa_id = $grupo->cgt->plan->programa_id;

            if ($grupo->delete()) {
                alert('Felicidades...', 'El grupo se ha eliminado con éxito','success');
            } else {
                alert()
                    ->error('Error...', 'No se puedo eliminar el grupo')
                    ->showConfirmButton();
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
        }

        return redirect('grupo');
    }
}
