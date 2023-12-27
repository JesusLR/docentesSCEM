<?php

namespace App\Http\Controllers\Preescolar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

use App\Models\Grupo;
use App\Models\Curso;
use App\Models\Cgt;
use App\Models\Aula;
use App\Models\Ubicacion;
use App\Models\Empleado;
use App\Models\Horario;
use App\Models\Periodo;
use App\Models\Programa;
use App\Models\Plan;
use App\Models\Materia;
use App\Models\Optativa;
use App\Models\Departamento;
use App\Models\Preescolar_grupo;
use App\Models\Preescolar_inscrito;
use App\Models\Preescolar_materia;
use App\Models\Preescolar_calificacion;

class PreescolarGrupoController extends Controller
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
        $departamentoPre = Departamento::with('ubicacion')->findOrFail(13);
        $perActualPre =  $departamentoPre->perActual;
        $departamentoMat = Departamento::with('ubicacion')->findOrFail(11);
        $perActualMat = $departamentoMat->perActual;

        $grupo = Preescolar_grupo::with('preescolar_materia','periodo','plan.programa.escuela.departamento.ubicacion')
            ->select('preescolar_grupos.*')
            ->whereIn('preescolar_grupos.periodo_id', [$perActualPre, $perActualMat])
            ->where('preescolar_grupos.empleado_id_docente',$empleado_id);

        dd($grupo);
        */


        return View('preescolar.show-list-preescolar');


    }

    /**
     * Show user list.
     *
     */
    public function list()
    {
        $empleado_id = Auth::user()->empleado->id;
        $departamentoPre = Departamento::with('ubicacion')->findOrFail(13);
        $perActualPre =  $departamentoPre->perActual;
        $departamentoMat = Departamento::with('ubicacion')->findOrFail(11);
        $perActualMat = $departamentoMat->perActual;

        $grupo = Preescolar_grupo::with('preescolar_materia','periodo','plan.programa.escuela.departamento.ubicacion')
            ->select('preescolar_grupos.*')
            ->whereIn('preescolar_grupos.periodo_id', [$perActualPre, $perActualMat])
            ->where('preescolar_grupos.empleado_id_docente',$empleado_id);

        return Datatables::of($grupo)
            ->addColumn('action', function($grupo)
            {
                $acciones = '';
                if ((Auth::user()->preescolar == 1)  || (Auth::user()->maternal == 1))
                {
                    $acciones = '<div class="row">
                    <a href="preescolarinscritos/' . $grupo->id . '" class="button button--icon js-button js-ripple-effect" title="Alumnos" >
                        <i class="material-icons">assignment_turned_in</i>
                    </a>
                    </div>';
                }

                return $acciones;
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


}
