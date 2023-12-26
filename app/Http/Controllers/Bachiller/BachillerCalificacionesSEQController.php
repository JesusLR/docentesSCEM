<?php

namespace App\Http\Controllers\Bachiller;

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
use PDF;


use App\Http\Models\Grupo;
use App\Http\Models\Curso;
use App\Http\Models\Cgt;
use App\Http\Models\Aula;
use App\Http\Models\Ubicacion;
use App\Http\Models\Empleado;
use App\Http\Models\Periodo;
use App\Http\Models\Programa;
use App\Http\Models\Plan;
use App\Http\Models\Escuela;
use App\Http\Models\Persona;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Bachiller\Bachiller_cch_calificaciones;
use App\Http\Models\Bachiller\Bachiller_cch_grupos;
use App\Http\Models\Bachiller\Bachiller_cch_inscritos;
use App\Http\Models\Bachiller\Bachiller_empleados;
use App\Http\Models\Bachiller\Bachiller_extraordinarios;
use App\Http\Models\Bachiller\Bachiller_inscritosextraordinarios;
use App\Http\Models\Bachiller\Bachiller_mes_evaluaciones;
use App\Http\Models\Bachiller\Bachiller_porcentajes;

class BachillerCalificacionesSEQController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');     

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inscrito_id = $request->inscrito_id;
        $grupo_id = $request->grupo_id;
        $trimestre_a_evaluar = 1;

        $calificaciones = DB::table('bachiller_cch_calificaciones')
            ->where('bachiller_cch_calificaciones.bachiller_cch_inscrito_id',$inscrito_id)
            ->where('bachiller_cch_calificaciones.trimestre1',$trimestre_a_evaluar)
            ->where('bachiller_cch_calificaciones.aplica','SI')
            ->get();

        //OBTENER GRUPO SELECCIONADO
        //$grupo = Grupo::with('plan.programa', 'materia', 'empleado.persona')->find($grupo_id);
        //OBTENER PROMEDIO PONDERADO EN MATERIA
        //$materia = Preescolar_materia::where('id', $grupo->bachiller_materia_id)->first();
        //$escuela = Escuela::where('id', $grupo->plan->programa->escuela_id)->first();

        $grupo = Bachiller_cch_grupos::with('bachiller_materia','periodo',
            'bachiller_empleado','plan.programa.escuela.departamento.ubicacion')
            ->find($grupo_id);

        $inscrito = Bachiller_cch_inscritos::find($inscrito_id);
        $inscrito_faltas = "";
        $inscrito_observaciones = "";
        if ($trimestre_a_evaluar == 1)
        {
            $inscrito_faltas = $inscrito->trimestre1_faltas;
            $inscrito_observaciones = $inscrito->trimestre1_observaciones;
        }
        if ($trimestre_a_evaluar == 2)
        {
            $inscrito_faltas = $inscrito->trimestre2_faltas;
            $inscrito_observaciones = $inscrito->trimestre2_observaciones;
        }
        if ($trimestre_a_evaluar == 3)
        {
            $inscrito_faltas = $inscrito->trimestre3_faltas;
            $inscrito_observaciones = $inscrito->trimestre3_observaciones;
        }
        $curso = Curso::with('alumno.persona')->find($inscrito->curso_id);
        $trimestre1_edicion = 'SI';
        $grupo_abierto = 'SI';
        //dd($empleado);
        /*
        $grupo = Preescolar_grupo::with('preescolar_materia','periodo',
            'empleado.persona','plan.programa.escuela.departamento.ubicacion')
            ->select('preescolar_grupos.*')
            ->where('id',$grupo_id);
        */
        /*
        $data = DB::table('preescolar_calificaciones')
            ->select('preescolar_calificaciones.id',
                'preescolarpreescolar_calificaciones.tipo as categoria',
                'preescolar_calificaciones.trimestre1 as trimestre',
                'preescolar_calificaciones.rubrica as aprendizaje',
                'preescolar_calificaciones.trimestre1_nivel as nivel')
            ->where('preescolar_calificaciones.preescolar_inscrito_id',$inscrito_id);
            //->where('preescolar_calificaciones.preescolar_inscrito_id',$inscrito_id)
            //->orderBy("alumnos.id", "desc");
        */
        //return view('table_edit', compact('data'));
        return view('bachiller.calendario.show-list',
            compact('calificaciones',
                'grupo',
                  'grupo_id',
                  'inscrito_id',
                  'inscrito_faltas',
                  'inscrito_observaciones',
                  'curso',
                  'trimestre_a_evaluar',
                  'trimestre1_edicion',
                  'grupo_abierto'));

    }



    public function create()
    {

        $periodos = DB::table('bachiller_cch_inscritos')
        ->select('periodos.perAnioPago', DB::raw('count(*) as perAnioPago, periodos.perAnioPago'),
        'periodos.id', DB::raw('count(*) as id, periodos.id'),
        'periodos.perNumero', DB::raw('count(*) as perNumero, periodos.perNumero'))
        ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
        ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->groupBy('periodos.perAnioPago')
        ->groupBy('periodos.id')
        ->groupBy('periodos.perNumero')
        ->orderBy('periodos.perAnioPago', 'desc')
        ->get();

        return view('bachiller.calificaciones_chetumal.create', [
            'periodos' => $periodos,
        ]);
    }


    public function getAlumnos(Request $request, $id)
    {
        if($request->ajax()){

            $alumnos = Bachiller_cch_inscritos::select('bachiller_cch_inscritos.id', 'bachiller_cch_inscritos.curso_id', 'bachiller_cch_inscritos.bachiller_grupo_id',
            'bachiller_cch_grupos.gpoGrado', 'bachiller_cch_grupos.gpoClave', 'programas.progClave', 'periodos.perAnio', 'bachiller_materias.matNombre', 'planes.planClave',
            'planes.planPeriodos', 'periodos.id as periodo_id', 'periodos.perFechaInicial', 'periodos.perFechaFinal',
            'alumnos.aluClave','alumnos.id as alumno_id', 'personas.perNombre', 'personas.perApellido1', 'personas.perApellido2')
            ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
            ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $id)
            ->get();

            // return response()->json($alumnos);
            return response()->json($alumnos);
        }
    }

    public function getGrupos(Request $request, $id)
    {

        if($request->ajax()){


            $gruposactuales = DB::table('bachiller_cch_inscritos')
                ->select('bachiller_cch_inscritos.bachiller_grupo_id', DB::raw('count(*) as grupo_id, bachiller_cch_inscritos.bachiller_grupo_id'),
                'bachiller_cch_grupos.gpoGrado', DB::raw('count(*) as gpoGrado, bachiller_cch_grupos.gpoGrado'),
                'bachiller_cch_grupos.gpoClave', DB::raw('count(*) as gpoClave, bachiller_cch_grupos.gpoClave'),
                'periodos.perAnio', DB::raw('count(*) as perAnio, periodos.perAnio'),
                'periodos.id', DB::raw('count(*) as id, periodos.id'),
                'programas.progNombre', DB::raw('count(*) as progNombre, programas.progNombre'),
                'bachiller_materias.matClave', DB::raw('count(*) as matClave, bachiller_materias.matClave'))
                ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
                ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
                ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
                ->join('planes', 'cgt.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->groupBy('bachiller_cch_inscritos.bachiller_grupo_id')
                ->groupBy('bachiller_cch_grupos.gpoGrado')
                ->groupBy('bachiller_cch_grupos.gpoClave')
                ->groupBy('periodos.perAnio')
                ->groupBy('periodos.id')
                ->groupBy('programas.progNombre')
                ->groupBy('bachiller_materias.matClave')
                ->where('periodos.id', '=', $id)
                ->get();
            

            return response()->json($gruposactuales);
        }
    }

    public function getMaterias2(Request $request, $id)
    {

        if($request->ajax()){

            $materia2 = DB::table('bachiller_cch_inscritos')
            ->select('bachiller_materias.matNombre', DB::raw('count(*) as matNombre, bachiller_materias.matNombre'),
            'bachiller_materias.id', DB::raw('count(*) as id, bachiller_materias.id'),
            'bachiller_cch_inscritos.bachiller_grupo_id', DB::raw('count(*) as bachiller_grupo_id, bachiller_cch_inscritos.bachiller_grupo_id'))
            ->leftJoin('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
            ->leftJoin('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
            ->leftJoin('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->leftJoin('planes', 'cgt.plan_id', '=', 'planes.id')
            ->leftJoin('programas', 'planes.programa_id', '=', 'programas.id')
            ->leftJoin('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->leftJoin('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->groupBy('bachiller_materias.matNombre')
            ->groupBy('bachiller_materias.id')
            ->groupBy('bachiller_cch_inscritos.bachiller_grupo_id')
            ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $id)
            ->get();


            return response()->json($materia2);
        }
    }

    public function guardarCalificacion(Request $request)
    {
        $bachiller_inscrito_id = $request->bachiller_inscrito_id;
        $evidencia1 = $request->evidencia1;
        $evidencia2 = $request->evidencia2;
        $evidencia3 = $request->evidencia3;
        $evidencia4 = $request->evidencia4;
        $evidencia5 = $request->evidencia5;
        $evidencia6 = $request->evidencia6;
        $evidencia7 = $request->evidencia7;
        $evidencia8 = $request->evidencia8;
        $evidencia9 = $request->evidencia9;
        $evidencia10 = $request->evidencia10;
        $promedioTotal = $request->promedioTotal;
        $numero_evaluacion = $request->numero_evaluacion;
        $bachiller_cch_grupo_evidencia_id = $request->bachiller_grupo_evidencia_id;
        $mes_evaluacion = $request->mes;


        $obtenerCalificaciones = Bachiller_cch_calificaciones::select('bachiller_cch_inscrito_id','mes_evaluacion')
        ->where('bachiller_cch_inscrito_id', '=', $bachiller_inscrito_id[0])
        ->where('mes_evaluacion', '=', $mes_evaluacion)
        ->first();

        if(!empty($obtenerCalificaciones)){
            alert('Escuela Modelo', 'Ya se registro calificaciones en el mes seleccionado, ingrese a editar para realizar cambios si así lo desea', 'info')->showConfirmButton();
            return back();
        }

        if(!empty($bachiller_inscrito_id)){
            for ($i=0; $i < count($bachiller_inscrito_id) ; $i++) {

                $calificaciones = array();
                $calificaciones = new Bachiller_cch_calificaciones();
                $calificaciones['bachiller_cch_inscrito_id'] = $bachiller_inscrito_id[$i];
                $calificaciones['bachiller_cch_grupo_evidencia_id'] = $bachiller_cch_grupo_evidencia_id;
                $calificaciones['numero_evaluacion'] = $numero_evaluacion;
                $calificaciones['mes_evaluacion'] = $mes_evaluacion;
                $calificaciones['calificacion_evidencia1'] = $evidencia1[$i];
                $calificaciones['calificacion_evidencia2'] = $evidencia2[$i];
                $calificaciones['calificacion_evidencia3'] = $evidencia3[$i];
                $calificaciones['calificacion_evidencia4'] = $evidencia4[$i];
                $calificaciones['calificacion_evidencia5'] = $evidencia5[$i];
                $calificaciones['calificacion_evidencia6'] = $evidencia6[$i];
                $calificaciones['calificacion_evidencia7'] = $evidencia7[$i];
                $calificaciones['calificacion_evidencia8'] = $evidencia8[$i];
                $calificaciones['calificacion_evidencia9'] = $evidencia9[$i];
                $calificaciones['calificacion_evidencia10'] = $evidencia10[$i];
                $calificaciones['promedio_mes'] = $promedioTotal[$i];

                $calificaciones->save();
            }

            alert('Escuela Modelo', 'Las calificaciones de crearon con éxito', 'success')->showConfirmButton();
            return back();
        }else{
            alert('Escuela Modelo', 'No se ha seleccionado ningún grupo', 'info')->showConfirmButton();
            return back();
        }


    }

        // funcion para la vista de calificaciones del grupo seleccionado
    public function edit_calificacion_old($id)
    {
        $validar_si_esta_activo_cch = Auth::user()->campus_cch;

        // $calificaciones = Bachiller_cch_calificaciones::select(
        //     'bachiller_cch_calificaciones.id',
        //     'bachiller_cch_calificaciones.bachiller_cch_inscrito_id',
        //     'bachiller_cch_calificaciones.numero_evaluacion',
        //     'bachiller_cch_calificaciones.mes_evaluacion',
        //     'bachiller_cch_calificaciones.calificacion_evidencia1',
        //     'bachiller_cch_calificaciones.calificacion_evidencia2',
        //     'bachiller_cch_calificaciones.calificacion_evidencia3',
        //     'bachiller_cch_calificaciones.calificacion_evidencia4',
        //     'bachiller_cch_calificaciones.calificacion_evidencia5',
        //     'bachiller_cch_calificaciones.calificacion_evidencia6',
        //     'bachiller_cch_calificaciones.calificacion_evidencia7',
        //     'bachiller_cch_calificaciones.calificacion_evidencia8',
        //     'bachiller_cch_calificaciones.calificacion_evidencia9',
        //     'bachiller_cch_calificaciones.calificacion_evidencia10',
        //     'bachiller_cch_calificaciones.promedio_mes',
        //     'bachiller_cch_inscritos.bachiller_grupo_id',
        //     'bachiller_cch_grupos.gpoGrado',
        //     'bachiller_cch_grupos.gpoClave',
        //     'bachiller_cch_grupos.gpoMatComplementaria',
        //     'bachiller_materias.id as id_materia',
        //     'bachiller_materias.matClave',
        //     'bachiller_materias.matNombre',
        //     'planes.id as id_plan',
        //     'planes.planClave',
        //     'periodos.id as periodo_id',
        //     'periodos.perAnio',
        //     'periodos.perFechaInicial',
        //     'periodos.perFechaFinal',
        //     'departamentos.depClave',
        //     'departamentos.depNombre',
        //     'personas.perNombre',
        //     'personas.perApellido1',
        //     'personas.perApellido2',
        //     'programas.id as programa_id',
        //     'programas.progClave',
        //     'programas.progNombre',
        //     'alumnos.id as alumno_id',
        //     'bachiller_mes_evaluaciones.id as mes_id',
        //     'bachiller_mes_evaluaciones.mes',
        //     'ubicacion.ubiClave'
        // )
        //     ->join('bachiller_cch_inscritos', 'bachiller_cch_calificaciones.bachiller_cch_inscrito_id', '=', 'bachiller_cch_inscritos.id')
        //     ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
        //     ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
        //     ->join('planes', 'bachiller_cch_grupos.plan_id', '=', 'planes.id')
        //     ->join('programas', 'planes.programa_id', '=', 'programas.id')
        //     ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
        //     ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        //     ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
        //     ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        //     ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        //     ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        //     ->join('bachiller_mes_evaluaciones', 'bachiller_cch_calificaciones.numero_evaluacion', '=', 'bachiller_mes_evaluaciones.id')
        //     ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $id)
        //     ->whereNull('bachiller_cch_inscritos.deleted_at')
        //     ->whereNull('bachiller_cch_grupos.deleted_at')
        //     ->whereNull('bachiller_materias.deleted_at')
        //     ->whereNull('planes.deleted_at')
        //     ->whereNull('programas.deleted_at')
        //     ->whereNull('periodos.deleted_at')
        //     ->whereNull('departamentos.deleted_at')
        //     ->whereNull('alumnos.deleted_at')
        //     ->whereNull('personas.deleted_at')
        //     ->get();

        // $grupos_calificaciones = collect($calificaciones);


            $bachiller_cch_inscritos = Bachiller_cch_inscritos::select(
                'bachiller_cch_inscritos.id',
                'bachiller_cch_inscritos.curso_id',
                'bachiller_cch_inscritos.bachiller_grupo_id',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial1',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial1',
                'bachiller_cch_inscritos.insAproboParcial1',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial2',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial2',
                'bachiller_cch_inscritos.insAproboParcial2',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial3',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial3',
                'bachiller_cch_inscritos.insAproboParcial3',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial4',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial4',
                'bachiller_cch_inscritos.insAproboParcial4',
                'bachiller_cch_inscritos.insPromedioOrdinario4Parciales',
                'bachiller_cch_inscritos.insCantidadReprobadasOrdinarioParciales',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial1',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial2',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial3',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial4',
                'bachiller_cch_inscritos.insCantidadReprobadasRecuperativos',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial1',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial2',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial3',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial4',
                'bachiller_cch_inscritos.insCantidadReprobadasExtraOrdinario',
                'bachiller_cch_inscritos.insRecursaraComoRepetidor',
                'bachiller_cch_inscritos.insCalificacionEspecial',
                'bachiller_cch_inscritos.insCalificacionFinalParcial1',
                'bachiller_cch_inscritos.insCalificacionFinalParcial2',
                'bachiller_cch_inscritos.insCalificacionFinalParcial3',
                'bachiller_cch_inscritos.insCalificacionFinalParcial4',
                'bachiller_cch_inscritos.insCalificacionFinalPromedio',
                'bachiller_cch_inscritos.preparatoria_historico_id',
                'bachiller_cch_grupos.gpoGrado',
                'bachiller_cch_grupos.gpoClave',
                'bachiller_cch_grupos.gpoMatComplementaria',
                'bachiller_materias.id as id_materia',
                'bachiller_materias.matClave',
                'bachiller_materias.matNombre',
                'planes.id as id_plan',
                'planes.planClave',
                'periodos.id as periodo_id',
                'periodos.perNumero',
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
                'alumnos.aluClave',
                'ubicacion.ubiClave'
            )
                ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
                ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->join('planes', 'bachiller_cch_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $id)
                ->whereNull('bachiller_cch_inscritos.deleted_at')
                ->whereNull('bachiller_cch_grupos.deleted_at')
                ->whereNull('bachiller_materias.deleted_at')
                ->whereNull('planes.deleted_at')
                ->whereNull('programas.deleted_at')
                ->whereNull('periodos.deleted_at')
                ->whereNull('departamentos.deleted_at')
                ->whereNull('alumnos.deleted_at')
                ->whereNull('personas.deleted_at')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();
    

        $grupos_inscritos = collect($bachiller_cch_inscritos);

        if ($grupos_inscritos->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay datos para mostrar.')->showConfirmButton();
            return back();
        }

        $mes_evaluacion = Bachiller_mes_evaluaciones::get();

        return view('bachiller.calificaciones_chetumal.calificaciones-edit', [
            'mes_evaluacion' => $mes_evaluacion,
            'validar_si_esta_activo_cch' => $validar_si_esta_activo_cch,
            'grupos_inscritos' => $grupos_inscritos
        ]);
    }

    public function edit_calificacion($id)
    {
        $validar_si_esta_activo_cch = Auth::user()->campus_cch;

        $bachiller_cch_inscritos = Bachiller_cch_inscritos::select(
            'bachiller_cch_inscritos.id',
            'bachiller_cch_inscritos.curso_id',
            'bachiller_cch_inscritos.bachiller_grupo_id',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial1',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial1',
            'bachiller_cch_inscritos.insAproboParcial1',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial2',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial2',
            'bachiller_cch_inscritos.insAproboParcial2',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial3',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial3',
            'bachiller_cch_inscritos.insAproboParcial3',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial4',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial4',
            'bachiller_cch_inscritos.insAproboParcial4',
            'bachiller_cch_inscritos.insPromedioOrdinario4Parciales',
            'bachiller_cch_inscritos.insCantidadReprobadasOrdinarioParciales',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial1',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial2',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial3',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial4',
            'bachiller_cch_inscritos.insCantidadReprobadasRecuperativos',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial1',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial2',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial3',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial4',
            'bachiller_cch_inscritos.insCantidadReprobadasExtraOrdinario',
            'bachiller_cch_inscritos.insRecursaraComoRepetidor',
            'bachiller_cch_inscritos.insCalificacionEspecial',
            'bachiller_cch_inscritos.insCalificacionFinalParcial1',
            'bachiller_cch_inscritos.insCalificacionFinalParcial2',
            'bachiller_cch_inscritos.insCalificacionFinalParcial3',
            'bachiller_cch_inscritos.insCalificacionFinalParcial4',
            'bachiller_cch_inscritos.insCalificacionFinalPromedio',
            'bachiller_cch_inscritos.preparatoria_historico_id',
            'bachiller_cch_grupos.gpoGrado',
            'bachiller_cch_grupos.gpoClave',
            'bachiller_cch_grupos.gpoMatComplementaria',
            'bachiller_materias.id as id_materia',
            'bachiller_materias.matClave',
            'bachiller_materias.matNombre',
            'planes.id as id_plan',
            'planes.planClave',
            'periodos.id as periodo_id',
            'periodos.perNumero',
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
            'alumnos.aluClave',
            'ubicacion.ubiClave'
        )
            ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
            ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('planes', 'bachiller_cch_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $id)
            ->whereNull('bachiller_cch_inscritos.deleted_at')
            ->whereNull('bachiller_cch_grupos.deleted_at')
            ->whereNull('bachiller_materias.deleted_at')
            ->whereNull('planes.deleted_at')
            ->whereNull('programas.deleted_at')
            ->whereNull('periodos.deleted_at')
            ->whereNull('departamentos.deleted_at')
            ->whereNull('alumnos.deleted_at')
            ->whereNull('personas.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

        $grupos_inscritos = collect($bachiller_cch_inscritos);

        if ($grupos_inscritos->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay datos para mostrar.')->showConfirmButton();
            return back();
        }


        $suma = 0;
        $sumaRecuperativos = 0;
        foreach ($grupos_inscritos as $key => $value) {

            if ($value->insAproboParcial1 == 'NO') {
                $suma++;
            }
            if ($value->insAproboParcial2 == 'NO') {
                $suma++;
            }
            if ($value->insAproboParcial3 == 'NO') {
                $suma++;
            }
            if ($value->insAproboParcial4 == 'NO') {
                $suma++;
            }


            if ($value->insAproboRecuperativoParcial1 == 'NO') {
                $sumaRecuperativos++;
            }
            if ($value->insAproboRecuperativoParcial2 == 'NO') {
                $sumaRecuperativos++;
            }
            if ($value->insAproboRecuperativoParcial3 == 'NO') {
                $sumaRecuperativos++;
            }
            if ($value->insAproboRecuperativoParcial3 == 'NO') {
                $sumaRecuperativos++;
            }

            // return $sumaRecuperativos;
            $ejecutar_sp_ordinarios = DB::update("call procBachillerCCHActualizaParcialesReprobados(" . $value->id . ", " . $suma . ")");

            // $ejecutar_sp_recuperativos =DB::update("call procBachillerCCHActualizaRecuperativosReprobados(" . $value->id . ", " . $sumaRecuperativos . ")");

            $suma = 0;
            $sumaRecuperativos = 0;
        }


        // die();
        $mes_evaluacion = Bachiller_mes_evaluaciones::get();



        $bachiller_cch_inscritos2 = Bachiller_cch_inscritos::select(
            'bachiller_cch_inscritos.id',
            'bachiller_cch_inscritos.curso_id',
            'bachiller_cch_inscritos.bachiller_grupo_id',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial1',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial1',
            'bachiller_cch_inscritos.insAproboParcial1',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial2',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial2',
            'bachiller_cch_inscritos.insAproboParcial2',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial3',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial3',
            'bachiller_cch_inscritos.insAproboParcial3',
            'bachiller_cch_inscritos.insCalificacionOrdinarioParcial4',
            'bachiller_cch_inscritos.insFaltasOrdinarioParcial4',
            'bachiller_cch_inscritos.insAproboParcial4',
            'bachiller_cch_inscritos.insPromedioOrdinario4Parciales',
            'bachiller_cch_inscritos.insCantidadReprobadasOrdinarioParciales',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial1',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial2',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial3',
            'bachiller_cch_inscritos.insCalificacionRecuperativoParcial4',
            'bachiller_cch_inscritos.insCantidadReprobadasRecuperativos',
            'bachiller_cch_inscritos.insAproboRecuperativoParcial1',
            'bachiller_cch_inscritos.insAproboRecuperativoParcial2',
            'bachiller_cch_inscritos.insAproboRecuperativoParcial3',
            'bachiller_cch_inscritos.insAproboRecuperativoParcial4',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial1',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial2',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial3',
            'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial4',
            'bachiller_cch_inscritos.insCantidadReprobadasExtraOrdinario',
            'bachiller_cch_inscritos.insRecursaraComoRepetidor',
            'bachiller_cch_inscritos.insCalificacionEspecial',
            'bachiller_cch_inscritos.insCalificacionFinalParcial1',
            'bachiller_cch_inscritos.insCalificacionFinalParcial2',
            'bachiller_cch_inscritos.insCalificacionFinalParcial3',
            'bachiller_cch_inscritos.insCalificacionFinalParcial4',
            'bachiller_cch_inscritos.insCalificacionFinalPromedio',
            'bachiller_cch_inscritos.preparatoria_historico_id',
            'bachiller_cch_inscritos.insEstaEnRecuperativo',
            'bachiller_cch_inscritos.insEstaEnEspecial',
            'bachiller_cch_inscritos.insCantidadReprobadasDespuesRecuperativo',
            'bachiller_cch_inscritos.insAproboExtra1',
            'bachiller_cch_inscritos.insAproboExtra2',
            'bachiller_cch_inscritos.insAproboExtra3',
            'bachiller_cch_inscritos.insAproboExtra4',
            'bachiller_cch_inscritos.insCantidadReprobadasExtraRegulares',
            'bachiller_cch_inscritos.insCantidadReprobadasDespuesExtraRegulares',
            'bachiller_cch_grupos.gpoGrado',
            'bachiller_cch_grupos.gpoClave',
            'bachiller_cch_grupos.gpoMatComplementaria',
            'bachiller_materias.id as id_materia',
            'bachiller_materias.matClave',
            'bachiller_materias.matNombre',
            'bachiller_materias.matTipoAcreditacion',
            'planes.id as id_plan',
            'planes.planClave',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnio',
            'periodos.perFechaInicial',
            'periodos.perFechaFinal',
            'departamentos.depClave',
            'departamentos.depNombre',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perCurp',
            'programas.id as programa_id',
            'programas.progClave',
            'programas.progNombre',
            'alumnos.id as alumno_id',
            'alumnos.aluClave',
            'ubicacion.ubiClave',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_empleados.empNombre'
        )
            ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
            ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('planes', 'bachiller_cch_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->join('bachiller_empleados', 'bachiller_cch_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
            ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $id)
            ->whereNull('bachiller_cch_inscritos.deleted_at')
            ->whereNull('bachiller_cch_grupos.deleted_at')
            ->whereNull('bachiller_materias.deleted_at')
            ->whereNull('planes.deleted_at')
            ->whereNull('programas.deleted_at')
            ->whereNull('periodos.deleted_at')
            ->whereNull('departamentos.deleted_at')
            ->whereNull('alumnos.deleted_at')
            ->whereNull('personas.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

            return view('bachiller.calificaciones_chetumal.calificaciones-edit', [
            'mes_evaluacion' => $mes_evaluacion,
            'validar_si_esta_activo_cch' => $validar_si_esta_activo_cch,
            'bachiller_cch_inscritos2' => $bachiller_cch_inscritos2,
            'bachiller_grupo_id' => $id
        ]);
    }

    public function getCalificacionesAlumnosCCH(Request $request, $bachiller_cch_grupo_id, $que_vamos_a_calificar)
    {
        if ($request->ajax()) {
            $bachiller_cch_inscritos = Bachiller_cch_inscritos::select(
                'bachiller_cch_inscritos.id',
                'bachiller_cch_inscritos.curso_id',
                'bachiller_cch_inscritos.bachiller_grupo_id',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial1',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial1',
                'bachiller_cch_inscritos.insAproboParcial1',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial2',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial2',
                'bachiller_cch_inscritos.insAproboParcial2',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial3',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial3',
                'bachiller_cch_inscritos.insAproboParcial3',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial4',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial4',
                'bachiller_cch_inscritos.insAproboParcial4',
                'bachiller_cch_inscritos.insPromedioOrdinario4Parciales',
                'bachiller_cch_inscritos.insCantidadReprobadasOrdinarioParciales',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial1',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial2',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial3',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial4',
                'bachiller_cch_inscritos.insCantidadReprobadasRecuperativos',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial1',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial2',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial3',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial4',
                'bachiller_cch_inscritos.insCantidadReprobadasExtraOrdinario',
                'bachiller_cch_inscritos.insRecursaraComoRepetidor',
                'bachiller_cch_inscritos.insCalificacionEspecial',
                'bachiller_cch_inscritos.insCalificacionFinalParcial1',
                'bachiller_cch_inscritos.insCalificacionFinalParcial2',
                'bachiller_cch_inscritos.insCalificacionFinalParcial3',
                'bachiller_cch_inscritos.insCalificacionFinalParcial4',
                'bachiller_cch_inscritos.insCalificacionFinalPromedio',
                'bachiller_cch_inscritos.preparatoria_historico_id',
                'bachiller_cch_grupos.gpoGrado',
                'bachiller_cch_grupos.gpoClave',
                'bachiller_cch_grupos.gpoMatComplementaria',
                'bachiller_materias.id as id_materia',
                'bachiller_materias.matClave',
                'bachiller_materias.matNombre',
                'bachiller_materias.matTipoAcreditacion',
                'planes.id as id_plan',
                'planes.planClave',
                'periodos.id as periodo_id',
                'periodos.perNumero',
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
                'alumnos.aluClave',
                'ubicacion.ubiClave'
            )
                ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
                ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->join('planes', 'bachiller_cch_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $bachiller_cch_grupo_id)
                ->whereNull('bachiller_cch_inscritos.deleted_at')
                ->whereNull('bachiller_cch_grupos.deleted_at')
                ->whereNull('bachiller_materias.deleted_at')
                ->whereNull('planes.deleted_at')
                ->whereNull('programas.deleted_at')
                ->whereNull('periodos.deleted_at')
                ->whereNull('departamentos.deleted_at')
                ->whereNull('alumnos.deleted_at')
                ->whereNull('personas.deleted_at')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();

            $bachiller_cch_inscritos_recuperativos = Bachiller_cch_inscritos::select(
                'bachiller_cch_inscritos.id',
                'bachiller_cch_inscritos.curso_id',
                'bachiller_cch_inscritos.bachiller_grupo_id',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial1',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial1',
                'bachiller_cch_inscritos.insAproboParcial1',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial2',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial2',
                'bachiller_cch_inscritos.insAproboParcial2',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial3',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial3',
                'bachiller_cch_inscritos.insAproboParcial3',
                'bachiller_cch_inscritos.insCalificacionOrdinarioParcial4',
                'bachiller_cch_inscritos.insFaltasOrdinarioParcial4',
                'bachiller_cch_inscritos.insAproboParcial4',
                'bachiller_cch_inscritos.insPromedioOrdinario4Parciales',
                'bachiller_cch_inscritos.insCantidadReprobadasOrdinarioParciales',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial1',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial2',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial3',
                'bachiller_cch_inscritos.insCalificacionRecuperativoParcial4',
                'bachiller_cch_inscritos.insCantidadReprobadasRecuperativos',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial1',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial2',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial3',
                'bachiller_cch_inscritos.insCalificacionExtraOrdinarioParcial4',
                'bachiller_cch_inscritos.insCantidadReprobadasExtraOrdinario',
                'bachiller_cch_inscritos.insRecursaraComoRepetidor',
                'bachiller_cch_inscritos.insCalificacionEspecial',
                'bachiller_cch_inscritos.insCalificacionFinalParcial1',
                'bachiller_cch_inscritos.insCalificacionFinalParcial2',
                'bachiller_cch_inscritos.insCalificacionFinalParcial3',
                'bachiller_cch_inscritos.insCalificacionFinalParcial4',
                'bachiller_cch_inscritos.insCalificacionFinalPromedio',
                'bachiller_cch_inscritos.preparatoria_historico_id',
                'bachiller_cch_grupos.gpoGrado',
                'bachiller_cch_grupos.gpoClave',
                'bachiller_cch_grupos.gpoMatComplementaria',
                'bachiller_materias.id as id_materia',
                'bachiller_materias.matClave',
                'bachiller_materias.matNombre',
                'bachiller_materias.matTipoAcreditacion',
                'planes.id as id_plan',
                'planes.planClave',
                'periodos.id as periodo_id',
                'periodos.perNumero',
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
                'alumnos.aluClave',
                'ubicacion.ubiClave'
            )
                ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
                ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->join('planes', 'bachiller_cch_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $bachiller_cch_grupo_id)
                ->whereNull('bachiller_cch_inscritos.deleted_at')
                ->whereNull('bachiller_cch_grupos.deleted_at')
                ->whereNull('bachiller_materias.deleted_at')
                ->whereNull('planes.deleted_at')
                ->whereNull('programas.deleted_at')
                ->whereNull('periodos.deleted_at')
                ->whereNull('departamentos.deleted_at')
                ->whereNull('alumnos.deleted_at')
                ->whereNull('personas.deleted_at')
                ->where('bachiller_cch_inscritos.insCantidadReprobadasOrdinarioParciales', '=', '1')
                ->orWhere('bachiller_cch_inscritos.insCantidadReprobadasOrdinarioParciales', '=', '2')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();

            return response()->json([
                "bachiller_cch_inscritos" => $bachiller_cch_inscritos,
                "que_vamos_a_calificar" => $que_vamos_a_calificar,
                "bachiller_cch_inscritos_recuperativos" => $bachiller_cch_inscritos_recuperativos
            ]);
        }
    }

    public function update_calificacion_ordinarios(Request $request)
    {

        $bachiller_cch_inscrito_id = $request->bachiller_cch_inscrito_id;
        $insCalificacionOrdinarioParcial1 = $request->insCalificacionOrdinarioParcial1;
        $insCalificacionOrdinarioParcial2 = $request->insCalificacionOrdinarioParcial2;
        $insCalificacionOrdinarioParcial3 = $request->insCalificacionOrdinarioParcial3;
        $insCalificacionOrdinarioParcial4 = $request->insCalificacionOrdinarioParcial4;
        $se_esta_calificando = $request->se_esta_calificando;


        if ($se_esta_calificando == "parciales_ordinarios") {

            for ($x = 0; $x < count($bachiller_cch_inscrito_id); $x++) {

                // validando si es calificacion numerica o alfanumerico parcial 1
                if ($insCalificacionOrdinarioParcial1[$x] == -1 || $insCalificacionOrdinarioParcial1[$x] == -2) {

                    if ($insCalificacionOrdinarioParcial1[$x] == -1) {
                        $pasoParcial1[$x] = 'SI';
                    } else {
                        $pasoParcial1[$x] = 'NO';
                    }
                } else {
                    if ($insCalificacionOrdinarioParcial1[$x] < 6) {
                        $pasoParcial1[$x] = 'NO';
                    } else {
                        $pasoParcial1[$x] = 'SI';
                    }
                }

                // validando si es calificacion numerica o alfanumerico parcial 2
                if ($insCalificacionOrdinarioParcial2[$x] == -1 || $insCalificacionOrdinarioParcial2[$x] == -2) {

                    if ($insCalificacionOrdinarioParcial2[$x] == -1) {
                        $pasoParcial2[$x] = 'SI';
                    } else {
                        $pasoParcial2[$x] = 'NO';
                    }
                } else {
                    if ($insCalificacionOrdinarioParcial2[$x] < 6) {
                        $pasoParcial2[$x] = 'NO';
                    } else {
                        $pasoParcial2[$x] = 'SI';
                    }
                }


                // validando si es calificacion numerica o alfanumerico parcial 3
                if ($insCalificacionOrdinarioParcial3[$x] == -1 || $insCalificacionOrdinarioParcial3[$x] == -2) {

                    if ($insCalificacionOrdinarioParcial3[$x] == -1) {
                        $pasoParcial3[$x] = 'SI';
                    } else {
                        $pasoParcial3[$x] = 'NO';
                    }
                } else {
                    if ($insCalificacionOrdinarioParcial3[$x] < 6) {
                        $pasoParcial3[$x] = 'NO';
                    } else {
                        $pasoParcial3[$x] = 'SI';
                    }
                }

                // validando si es calificacion numerica o alfanumerico parcial 4
                if ($insCalificacionOrdinarioParcial4[$x] == -1 || $insCalificacionOrdinarioParcial4[$x] == -2) {

                    if ($insCalificacionOrdinarioParcial4[$x] == -1) {
                        $pasoParcial4[$x] = 'SI';
                    } else {
                        $pasoParcial4[$x] = 'NO';
                    }
                } else {
                    if ($insCalificacionOrdinarioParcial4[$x] < 6) {
                        $pasoParcial4[$x] = 'NO';
                    } else {
                        $pasoParcial4[$x] = 'SI';
                    }
                }

                DB::table('bachiller_cch_inscritos')
                    ->where('id', $bachiller_cch_inscrito_id[$x])
                    ->update([

                        'insCalificacionOrdinarioParcial1' => $insCalificacionOrdinarioParcial1[$x],
                        'insAproboParcial1' => $pasoParcial1[$x],
                        'insCalificacionOrdinarioParcial2' => $insCalificacionOrdinarioParcial2[$x],
                        'insAproboParcial2' => $pasoParcial2[$x],
                        'insCalificacionOrdinarioParcial3' => $insCalificacionOrdinarioParcial3[$x],
                        'insAproboParcial3' => $pasoParcial3[$x],
                        'insCalificacionOrdinarioParcial4' => $insCalificacionOrdinarioParcial4[$x],
                        'insAproboParcial4' => $pasoParcial4[$x]
                    ]);
            }
        }



        alert('Escuela Modelo', 'Las calificaciones se actualizarón con éxito', 'success')->showConfirmButton()->autoClose(5000);

        return back();
    }

    public function update_calificacion_recuperativos(Request $request)
    {

        $bachiller_cch_inscrito_id = $request->bachiller_cch_inscrito_id;
        $insCalificacionRecuperativoParcial1 = $request->insCalificacionRecuperativoParcial1;
        $insCalificacionRecuperativoParcial2 = $request->insCalificacionRecuperativoParcial2;
        $insCalificacionRecuperativoParcial3 = $request->insCalificacionRecuperativoParcial3;
        $insCalificacionRecuperativoParcial4 = $request->insCalificacionRecuperativoParcial4;
        $tipoacreditacion = $request->tipoacreditacion;

        $se_esta_calificando = $request->se_esta_calificando;

        if ($se_esta_calificando == "recuperativos") {


            
            for ($x = 0; $x < count($bachiller_cch_inscrito_id); $x++) {

                // validando si es calificacion numerica o alfanumerico parcial 1
                if($tipoacreditacion == "alfanumerico"){
                    if ($insCalificacionRecuperativoParcial1[$x] == -1 || $insCalificacionRecuperativoParcial1[$x] == -2) {
                    

                        if ($insCalificacionRecuperativoParcial1[$x] == -2) {
                            $insAproboRecuperativoParcial1[$x] = "NO";
                            $cantidadDebidad1 = 1;
                            $insCalificacionRecuperativoParcial1[$x] = -2;
                        } else {
                            $insAproboRecuperativoParcial1[$x] = "SI";
                            $cantidadDebidad1 = 0;
                            $insCalificacionRecuperativoParcial1[$x] = -1;
                        }
                    }else{
                        $insAproboRecuperativoParcial1[$x] = NULL;
                        $cantidadDebidad1 = 0;
                        $insCalificacionRecuperativoParcial1[$x] = NULL;
                    }
                }else{
                    if ($request->validandoSiEstaInactivo1[$x] == "SiDisabled") {
                        $insAproboRecuperativoParcial1[$x] = NULL;
                        $cantidadDebidad1 = 0;
                        $insCalificacionRecuperativoParcial1 = NULL;
                    } else {
                        if ($insCalificacionRecuperativoParcial1[$x] < 6) {
                            $insAproboRecuperativoParcial1[$x] = "NO";
                            $cantidadDebidad1 = 1;
                            $insCalificacionRecuperativoParcial1 = $request->insCalificacionRecuperativoParcial1;
                        } else {
                            $insAproboRecuperativoParcial1[$x] = "SI";
                            $cantidadDebidad1 = 0;
                            $insCalificacionRecuperativoParcial1 = $request->insCalificacionRecuperativoParcial1;
                        }
                    }
                }


                // validando si es calificacion numerica o alfanumerico parcial 2
                if($tipoacreditacion == "alfanumerico"){
                    if ($insCalificacionRecuperativoParcial2[$x] == -1 || $insCalificacionRecuperativoParcial2[$x] == -2) {
                    

                        if ($insCalificacionRecuperativoParcial2[$x] == -2) {
                            $insAproboRecuperativoParcial2[$x] = "NO";
                            $cantidadDebidad2 = 1;
                            $insCalificacionRecuperativoParcial2[$x] = -2;
                        } else {
                            $insAproboRecuperativoParcial2[$x] = "SI";
                            $cantidadDebidad2 = 0;
                            $insCalificacionRecuperativoParcial2[$x] = -1;
                        }
                    }else{
                        $insAproboRecuperativoParcial2[$x] = NULL;
                        $cantidadDebidad2 = 0;
                        $insCalificacionRecuperativoParcial2[$x] = NULL;
                    }
                }else{
                    if ($request->validandoSiEstaInactivo2[$x] == "SiDisabled") {
                        $insAproboRecuperativoParcial2[$x] = NULL;
                        $cantidadDebidad2 = 0;
                        $insCalificacionRecuperativoParcial2 = NULL;
                    } else {
                        if ($insCalificacionRecuperativoParcial2[$x] < 6) {
                            $insAproboRecuperativoParcial2[$x] = "NO";
                            $cantidadDebidad2 = 1;
                            $insCalificacionRecuperativoParcial2 = $request->insCalificacionRecuperativoParcial2;
                        } else {
                            $insAproboRecuperativoParcial2[$x] = "SI";
                            $cantidadDebidad2 = 0;
                            $insCalificacionRecuperativoParcial2 = $request->insCalificacionRecuperativoParcial2;
                        }
                    }
                }


                 // validando si es calificacion numerica o alfanumerico parcial 3
                 if($tipoacreditacion == "alfanumerico"){
                    if ($insCalificacionRecuperativoParcial3[$x] == -1 || $insCalificacionRecuperativoParcial3[$x] == -2) {
                    

                        if ($insCalificacionRecuperativoParcial3[$x] == -2) {
                            $insAproboRecuperativoParcial3[$x] = "NO";
                            $cantidadDebidad3 = 1;
                            $insCalificacionRecuperativoParcial3[$x] = -2;
                        } else {
                            $insAproboRecuperativoParcial3[$x] = "SI";
                            $cantidadDebidad3 = 0;
                            $insCalificacionRecuperativoParcial3[$x] = -1;
                        }
                    }else{
                        $insAproboRecuperativoParcial3[$x] = NULL;
                        $cantidadDebidad3 = 0;
                        $insCalificacionRecuperativoParcial3[$x] = NULL;
                    }
                }else{
                    if ($request->validandoSiEstaInactivo3[$x] == "SiDisabled") {
                        $insAproboRecuperativoParcial3[$x] = NULL;
                        $cantidadDebidad3 = 0;
                        $insCalificacionRecuperativoParcial3 = NULL;
                    } else {
                        if ($insCalificacionRecuperativoParcial3[$x] < 6) {
                            $insAproboRecuperativoParcial3[$x] = "NO";
                            $cantidadDebidad3 = 1;
                            $insCalificacionRecuperativoParcial3 = $request->insCalificacionRecuperativoParcial3;
                        } else {
                            $insAproboRecuperativoParcial3[$x] = "SI";
                            $cantidadDebidad3 = 0;
                            $insCalificacionRecuperativoParcial3 = $request->insCalificacionRecuperativoParcial3;
                        }
                    }
                }


                // validando si es calificacion numerica o alfanumerico parcial 3
                if($tipoacreditacion == "alfanumerico"){
                    if ($insCalificacionRecuperativoParcial4[$x] == -1 || $insCalificacionRecuperativoParcial4[$x] == -2) {
                    

                        if ($insCalificacionRecuperativoParcial4[$x] == -2) {
                            $insAproboRecuperativoParcial4[$x] = "NO";
                            $cantidadDebidad4 = 1;
                            $insCalificacionRecuperativoParcial4[$x] = -2;
                        } else {
                            $insAproboRecuperativoParcial4[$x] = "SI";
                            $cantidadDebidad4 = 0;
                            $insCalificacionRecuperativoParcial4[$x] = -1;
                        }
                    }else{
                        $insAproboRecuperativoParcial4[$x] = NULL;
                        $cantidadDebidad4 = 0;
                        $insCalificacionRecuperativoParcial4[$x] = NULL;
                    }
                }else{
                    if ($request->validandoSiEstaInactivo4[$x] == "SiDisabled") {
                        $insAproboRecuperativoParcial4[$x] = NULL;
                        $cantidadDebidad4 = 0;
                        $insCalificacionRecuperativoParcial4 = NULL;
                    } else {
                        if ($insCalificacionRecuperativoParcial4[$x] < 6) {
                            $insAproboRecuperativoParcial4[$x] = "NO";
                            $cantidadDebidad4 = 1;
                            $insCalificacionRecuperativoParcial4 = $request->insCalificacionRecuperativoParcial4;
                        } else {
                            $insAproboRecuperativoParcial4[$x] = "SI";
                            $cantidadDebidad4 = 0;
                            $insCalificacionRecuperativoParcial4 = $request->insCalificacionRecuperativoParcial4;
                        }
                    }
                }

                $sumaDeCuantosDebe[$x] = $cantidadDebidad1 + $cantidadDebidad2 + $cantidadDebidad3 + $cantidadDebidad4;

                // print_r($sumaDeCuantosDebe[$x].'<br>');


                DB::table('bachiller_cch_inscritos')
                    ->where('id', $bachiller_cch_inscrito_id[$x])
                    ->update([

                        'insCalificacionRecuperativoParcial1' => $insCalificacionRecuperativoParcial1[$x],
                        'insAproboRecuperativoParcial1' => $insAproboRecuperativoParcial1[$x],
                        'insCalificacionRecuperativoParcial2' => $insCalificacionRecuperativoParcial2[$x],
                        'insAproboRecuperativoParcial2' => $insAproboRecuperativoParcial2[$x],
                        'insCalificacionRecuperativoParcial3' => $insCalificacionRecuperativoParcial3[$x],
                        'insAproboRecuperativoParcial3' => $insAproboRecuperativoParcial3[$x],
                        'insCalificacionRecuperativoParcial4' => $insCalificacionRecuperativoParcial4[$x],
                        'insAproboRecuperativoParcial4' => $insAproboRecuperativoParcial4[$x],
                        'insCantidadReprobadasRecuperativos' => $sumaDeCuantosDebe[$x],
                        'insEstaEnRecuperativo' => "SI",
                        'insCantidadReprobadasDespuesRecuperativo' => $sumaDeCuantosDebe[$x]

                    ]);
            }
        }

        // die();

        alert('Escuela Modelo', 'Las calificaciones se actualizarón con éxito', 'success')->showConfirmButton()->autoClose(5000);

        return back();
    }

    public function update_calificacion_extraregular(Request $request)
    {

        $bachiller_cch_inscrito_id = $request->bachiller_cch_inscrito_id;
        $insCalificacionExtraOrdinarioParcial1 = $request->insCalificacionExtraOrdinarioParcial1;
        $insCalificacionExtraOrdinarioParcial2 = $request->insCalificacionExtraOrdinarioParcial2;
        $insCalificacionExtraOrdinarioParcial3 = $request->insCalificacionExtraOrdinarioParcial3;
        $insCalificacionExtraOrdinarioParcial4 = $request->insCalificacionExtraOrdinarioParcial4;

        $se_esta_calificando = $request->se_esta_calificando;

        $tipoacreditacion = $request->tipoacreditacion;

        if ($se_esta_calificando == "extraregular") {


            
            for ($x = 0; $x < count($bachiller_cch_inscrito_id); $x++) {

                // validando si es calificacion numerica o alfanumerico parcial 1
                if($tipoacreditacion == "alfanumerico"){
                    // validamos si hay envio de datos o es null 
                    if ($insCalificacionExtraOrdinarioParcial1[$x] == -1 || $insCalificacionExtraOrdinarioParcial1[$x] == -2) {
                        if ($request->validandoSiEstaInactivo1[$x] == "SiDisabled") {
                            $insAproboExtra1[$x] = NULL;
                            $cantidadDebidad1 = 0;
                            $insCalificacionExtraOrdinarioParcial1[$x] = NULL;
                        } else {
                            if ($insCalificacionExtraOrdinarioParcial1[$x] == -2) {
                                $insAproboExtra1[$x] = "NO";
                                $cantidadDebidad1 = 1;
                                $insCalificacionExtraOrdinarioParcial1[$x] = -2;
                            } else {
                                $insAproboExtra1[$x] = "SI";
                                $cantidadDebidad1 = 0;
                                $insCalificacionExtraOrdinarioParcial1[$x] = -1;
                            }
                        }
                        
                    } else{
                        $insAproboExtra1[$x] = NULL;
                        $cantidadDebidad1 = 0;
                        $insCalificacionExtraOrdinarioParcial1[$x] = NULL;
                    }
                } else {
                    if ($request->validandoSiEstaInactivo1[$x] == "SiDisabled") {
                        $insAproboExtra1[$x] = NULL;
                        $cantidadDebidad1 = 0;
                        $insCalificacionExtraOrdinarioParcial1 = NULL;
                    } else {
                        if ($insCalificacionExtraOrdinarioParcial1[$x] < 6) {
                            $insAproboExtra1[$x] = "NO";
                            $cantidadDebidad1 = 1;
                            $insCalificacionExtraOrdinarioParcial1 = $request->insCalificacionExtraOrdinarioParcial1;
                        } else {
                            $insAproboExtra1[$x] = "SI";
                            $cantidadDebidad1 = 0;
                            $insCalificacionExtraOrdinarioParcial1 = $request->insCalificacionExtraOrdinarioParcial1;
                        }
                    }
                }
                

                // validando si es calificacion numerica o alfanumerico parcial 2
                if($tipoacreditacion == "alfanumerico"){
                    // validamos si hay envio de datos o es null 
                    if ($insCalificacionExtraOrdinarioParcial2[$x] == -1 || $insCalificacionExtraOrdinarioParcial2[$x] == -2) {
                        if ($request->validandoSiEstaInactivo2[$x] == "SiDisabled") {
                            $insAproboExtra2[$x] = NULL;
                            $cantidadDebidad2 = 0;
                            $insCalificacionExtraOrdinarioParcial2[$x] = NULL;
                        } else {
                            if ($insCalificacionExtraOrdinarioParcial2[$x] == -2) {
                                $insAproboExtra2[$x] = "NO";
                                $cantidadDebidad2 = 1;
                                $insCalificacionExtraOrdinarioParcial2[$x] = -2;
                            } else {
                                $insAproboExtra2[$x] = "SI";
                                $cantidadDebidad2 = 0;
                                $insCalificacionExtraOrdinarioParcial2[$x] = -1;
                            }
                        }
                        
                    } else{
                        $insAproboExtra2[$x] = NULL;
                        $cantidadDebidad2 = 0;
                        $insCalificacionExtraOrdinarioParcial2[$x] = NULL;
                    }
                } else {
                    if ($request->validandoSiEstaInactivo2[$x] == "SiDisabled") {
                        $insAproboExtra2[$x] = NULL;
                        $cantidadDebidad2 = 0;
                        $insCalificacionExtraOrdinarioParcial2 = NULL;
                    } else {
                        if ($insCalificacionExtraOrdinarioParcial2[$x] < 6) {
                            $insAproboExtra2[$x] = "NO";
                            $cantidadDebidad2 = 1;
                            $insCalificacionExtraOrdinarioParcial2 = $request->insCalificacionExtraOrdinarioParcial2;
                        } else {
                            $insAproboExtra2[$x] = "SI";
                            $cantidadDebidad2 = 0;
                            $insCalificacionExtraOrdinarioParcial2 = $request->insCalificacionExtraOrdinarioParcial2;
                        }
                    }
                }

                // validando si es calificacion numerica o alfanumerico parcial 3
                if($tipoacreditacion == "alfanumerico"){
                    // validamos si hay envio de datos o es null 
                    if ($insCalificacionExtraOrdinarioParcial3[$x] == -1 || $insCalificacionExtraOrdinarioParcial3[$x] == -2) {
                        if ($request->validandoSiEstaInactivo3[$x] == "SiDisabled") {
                            $insAproboExtra3[$x] = NULL;
                            $cantidadDebidad3 = 0;
                            $insCalificacionExtraOrdinarioParcial3[$x] = NULL;
                        } else {
                            if ($insCalificacionExtraOrdinarioParcial3[$x] == -2) {
                                $insAproboExtra3[$x] = "NO";
                                $cantidadDebidad3 = 1;
                                $insCalificacionExtraOrdinarioParcial3[$x] = -2;
                            } else {
                                $insAproboExtra3[$x] = "SI";
                                $cantidadDebidad3 = 0;
                                $insCalificacionExtraOrdinarioParcial3[$x] = -1;
                            }
                        }
                        
                    } else{
                        $insAproboExtra3[$x] = NULL;
                        $cantidadDebidad3 = 0;
                        $insCalificacionExtraOrdinarioParcial3[$x] = NULL;
                    }
                } else {
                    if ($request->validandoSiEstaInactivo3[$x] == "SiDisabled") {
                        $insAproboExtra3[$x] = NULL;
                        $cantidadDebidad3 = 0;
                        $insCalificacionExtraOrdinarioParcial3 = NULL;
                    } else {
                        if ($insCalificacionExtraOrdinarioParcial3[$x] < 6) {
                            $insAproboExtra3[$x] = "NO";
                            $cantidadDebidad3 = 1;
                            $insCalificacionExtraOrdinarioParcial3 = $request->insCalificacionExtraOrdinarioParcial3;
                        } else {
                            $insAproboExtra3[$x] = "SI";
                            $cantidadDebidad3 = 0;
                            $insCalificacionExtraOrdinarioParcial3 = $request->insCalificacionExtraOrdinarioParcial3;
                        }
                    }
                }


                // validando si es calificacion numerica o alfanumerico parcial 4
                if($tipoacreditacion == "alfanumerico"){
                    // validamos si hay envio de datos o es null 
                    if ($insCalificacionExtraOrdinarioParcial4[$x] == -1 || $insCalificacionExtraOrdinarioParcial4[$x] == -2) {
                        if ($request->validandoSiEstaInactivo4[$x] == "SiDisabled") {
                            $insAproboExtra4[$x] = NULL;
                            $cantidadDebidad4 = 0;
                            $insCalificacionExtraOrdinarioParcial4[$x] = NULL;
                        } else {
                            if ($insCalificacionExtraOrdinarioParcial4[$x] == -2) {
                                $insAproboExtra4[$x] = "NO";
                                $cantidadDebidad4 = 1;
                                $insCalificacionExtraOrdinarioParcial4[$x] = -2;
                            } else {
                                $insAproboExtra4[$x] = "SI";
                                $cantidadDebidad4 = 0;
                                $insCalificacionExtraOrdinarioParcial4[$x] = -1;
                            }
                        }
                        
                    } else{
                        $insAproboExtra4[$x] = NULL;
                        $cantidadDebidad4 = 0;
                        $insCalificacionExtraOrdinarioParcial4[$x] = NULL;
                    }
                } else {
                    if ($request->validandoSiEstaInactivo4[$x] == "SiDisabled") {
                        $insAproboExtra4[$x] = NULL;
                        $cantidadDebidad4 = 0;
                        $insCalificacionExtraOrdinarioParcial4 = NULL;
                    } else {
                        if ($insCalificacionExtraOrdinarioParcial4[$x] < 6) {
                            $insAproboExtra4[$x] = "NO";
                            $cantidadDebidad4 = 1;
                            $insCalificacionExtraOrdinarioParcial4 = $request->insCalificacionExtraOrdinarioParcial4;
                        } else {
                            $insAproboExtra4[$x] = "SI";
                            $cantidadDebidad4 = 0;
                            $insCalificacionExtraOrdinarioParcial4 = $request->insCalificacionExtraOrdinarioParcial4;
                        }
                    }
                }

                $sumaDeCuantosDebe[$x] = $cantidadDebidad1 + $cantidadDebidad2 + $cantidadDebidad3 + $cantidadDebidad4;


                DB::table('bachiller_cch_inscritos')
                    ->where('id', $bachiller_cch_inscrito_id[$x])
                    ->update([

                        'insCalificacionExtraOrdinarioParcial1' => $insCalificacionExtraOrdinarioParcial1[$x],
                        'insAproboExtra1' => $insAproboExtra1[$x],
                        'insCalificacionExtraOrdinarioParcial2' => $insCalificacionExtraOrdinarioParcial2[$x],
                        'insAproboExtra2' => $insAproboExtra2[$x],
                        'insCalificacionExtraOrdinarioParcial3' => $insCalificacionExtraOrdinarioParcial3[$x],
                        'insAproboExtra3' => $insAproboExtra3[$x],
                        'insCalificacionExtraOrdinarioParcial4' => $insCalificacionExtraOrdinarioParcial4[$x],
                        'insAproboExtra4' => $insAproboExtra4[$x],
                        'insCantidadReprobadasExtraRegulares' => $sumaDeCuantosDebe[$x],
                        'insEstaEnExtraRegular' => "SI",
                        'insCantidadReprobadasDespuesExtraRegulares' => $sumaDeCuantosDebe[$x]

                    ]);
            }
        }

        alert('Escuela Modelo', 'Las calificaciones se actualizarón con éxito', 'success')->showConfirmButton()->autoClose(5000);

        return back();
    }

    public function update_calificacion_especial(Request $request)
    {

        $bachiller_cch_inscrito_id = $request->bachiller_cch_inscrito_id;
        $insCalificacionEspecial = $request->insCalificacionEspecial;      

        $se_esta_calificando = $request->se_esta_calificando;

        $tipoacreditacion = $request->tipoacreditacion;

        if ($se_esta_calificando == "especial") {


            
            for ($x = 0; $x < count($bachiller_cch_inscrito_id); $x++) {

                if($tipoacreditacion == "alfanumerico"){
                    // validamos si hay envio de datos o es null 
                    if ($insCalificacionEspecial[$x] == -1 || $insCalificacionEspecial[$x] == -2) {
                        if ($request->validandoSiEstaInactivo4[$x] == "SiDisabled") {
                            $insAproboExamenEspecial[$x] = NULL;
                            $insCalificacionEspecial[$x] = NULL;
                        } else {
                            if ($insCalificacionEspecial[$x] == -2) {
                                $insAproboExamenEspecial[$x] = "NO";
                                $insCalificacionEspecial[$x] = -2;
                            } else {
                                $insAproboExamenEspecial[$x] = "SI";
                                $insCalificacionEspecial[$x] = -1;
                            }
                        }
                        
                    } else{
                        $insAproboExamenEspecial[$x] = NULL;
                        $insCalificacionExtraOrdinarioParcial4[$x] = NULL;
                    }
                }else{
                    if ($insCalificacionEspecial[$x] < 6) {
                        $insAproboExamenEspecial[$x] = "NO";
                        $insCalificacionEspecial = $request->insCalificacionEspecial;
                    } else {
                        $insAproboExamenEspecial[$x] = "SI";
                        $insCalificacionEspecial = $request->insCalificacionEspecial;
                    }
                }
                




                DB::table('bachiller_cch_inscritos')
                    ->where('id', $bachiller_cch_inscrito_id[$x])
                    ->update([

                        'insCalificacionEspecial' => $insCalificacionEspecial[$x],   
                        'insAproboExamenEspecial' => $insAproboExamenEspecial[$x],                     
                        'insEstaEnEspecial' => "SI"

                    ]);
            }
        }

        alert('Escuela Modelo', 'Las calificaciones se actualizarón con éxito', 'success')->showConfirmButton()->autoClose(5000);

        return back();
    }
    
    public function getCalificacionesAlumnos(Request $request, $id, $grupoId)
    {
        if($request->ajax()){


            $calificaciones = Bachiller_cch_calificaciones::select('bachiller_cch_calificaciones.id',
            'bachiller_cch_calificaciones.bachiller_cch_inscrito_id',
            'bachiller_cch_calificaciones.bachiller_cch_grupo_evidencia_id',
            'bachiller_cch_calificaciones.numero_evaluacion',
            'bachiller_cch_calificaciones.mes_evaluacion',
            'bachiller_cch_calificaciones.calificacion_evidencia1',
            'bachiller_cch_calificaciones.calificacion_evidencia2',
            'bachiller_cch_calificaciones.calificacion_evidencia3',
            'bachiller_cch_calificaciones.calificacion_evidencia4',
            'bachiller_cch_calificaciones.calificacion_evidencia5',
            'bachiller_cch_calificaciones.calificacion_evidencia6',
            'bachiller_cch_calificaciones.calificacion_evidencia7',
            'bachiller_cch_calificaciones.calificacion_evidencia8',
            'bachiller_cch_calificaciones.calificacion_evidencia9',
            'bachiller_cch_calificaciones.calificacion_evidencia10',
            'bachiller_cch_calificaciones.promedio_mes',
            'bachiller_cch_inscritos.bachiller_grupo_id',
            'bachiller_cch_grupos.gpoGrado',
            'bachiller_cch_grupos.gpoClave',
            'bachiller_materias.id as id_materia',
            'bachiller_materias.matClave',
            'bachiller_materias.matNombre',
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
            'bachiller_cch_inscritos.inscFaltasInjSep',
            'bachiller_cch_inscritos.inscFaltasInjOct',
            'bachiller_cch_inscritos.inscFaltasInjNov',
            'bachiller_cch_inscritos.inscFaltasInjDic',
            'bachiller_cch_inscritos.inscFaltasInjEne',
            'bachiller_cch_inscritos.inscFaltasInjFeb',
            'bachiller_cch_inscritos.inscFaltasInjMar',
            'bachiller_cch_inscritos.inscFaltasInjAbr',
            'bachiller_cch_inscritos.inscFaltasInjMay',
            'bachiller_cch_inscritos.inscFaltasInjJun',
            'bachiller_cch_inscritos.inscConductaSep',
            'bachiller_cch_inscritos.inscConductaOct',
            'bachiller_cch_inscritos.inscConductaNov',
            'bachiller_cch_inscritos.inscConductaDic',
            'bachiller_cch_inscritos.inscConductaEne',
            'bachiller_cch_inscritos.inscConductaFeb',
            'bachiller_cch_inscritos.inscConductaMar',
            'bachiller_cch_inscritos.inscConductaAbr',
            'bachiller_cch_inscritos.inscConductaMay',
            'bachiller_cch_inscritos.inscConductaJun'

            )
            ->join('bachiller_cch_inscritos', 'bachiller_cch_calificaciones.bachiller_cch_inscrito_id', '=', 'bachiller_cch_inscritos.id')
            ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
            ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('planes', 'bachiller_cch_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'bachiller_cch_grupos.periodo_id', '=', 'periodos.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('cursos', 'bachiller_cch_inscritos.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->where('bachiller_cch_calificaciones.bachiller_cch_grupo_evidencia_id', '=', $id)
            ->where('bachiller_cch_inscritos.bachiller_grupo_id', '=', $grupoId)
            ->orderBy('personas.perApellido1', 'ASC')
            ->get();

            return response()->json($calificaciones);
        }
    }

        // funcion para actualizar calificaciones del grupo seleccionado
        // funcion para actualizar calificaciones del grupo seleccionado
    public function update_calificacion(Request $request)
    {

        $bachiller_cch_inscrito_id = $request->bachiller_cch_inscrito_id;
        $insCalificacionOrdinarioParcial1 = $request->insCalificacionOrdinarioParcial1;
        $insFaltasOrdinarioParcial1 = $request->insFaltasOrdinarioParcial1;
        $insCalificacionOrdinarioParcial2 = $request->insCalificacionOrdinarioParcial2;
        $insFaltasOrdinarioParcial2 = $request->insFaltasOrdinarioParcial2;
        $insCalificacionOrdinarioParcial3 = $request->insCalificacionOrdinarioParcial3;
        $insFaltasOrdinarioParcial3 = $request->insFaltasOrdinarioParcial3;
        $insCalificacionOrdinarioParcial4 = $request->insCalificacionOrdinarioParcial4;
        $insFaltasOrdinarioParcial4 = $request->insFaltasOrdinarioParcial4;
        $insCalificacionRecuperacion = $request->insCalificacionRecuperacion;
        $insCalificacionExtraOrdinarioRegularizacion = $request->insCalificacionExtraOrdinarioRegularizacion;
        $insCalificacionEspecial = $request->insCalificacionEspecial;

        for($x = 0; $x < count($bachiller_cch_inscrito_id); $x++){

            // $insCalificacionOrdinarioParcial1_valorNuevo[$x] = $insCalificacionOrdinarioParcial1[$x];

            DB::table('bachiller_cch_inscritos')
            ->where('id', $bachiller_cch_inscrito_id[$x])
            ->update([

                'insCalificacionOrdinarioParcial1' => $insCalificacionOrdinarioParcial1[$x],
                'insFaltasOrdinarioParcial1' => $insFaltasOrdinarioParcial1[$x],
                'insCalificacionOrdinarioParcial2' => $insCalificacionOrdinarioParcial2[$x],
                'insFaltasOrdinarioParcial2' => $insFaltasOrdinarioParcial2[$x],
                'insCalificacionOrdinarioParcial3' => $insCalificacionOrdinarioParcial3[$x],
                'insFaltasOrdinarioParcial3' => $insFaltasOrdinarioParcial3[$x],
                'insCalificacionOrdinarioParcial4' => $insCalificacionOrdinarioParcial4[$x],
                'insFaltasOrdinarioParcial4' => $insFaltasOrdinarioParcial4[$x],
                'insCalificacionRecuperacion' => $insCalificacionRecuperacion[$x],
                'insCalificacionExtraOrdinarioRegularizacion' => $insCalificacionExtraOrdinarioRegularizacion[$x],
                'insCalificacionEspecial' => $insCalificacionEspecial[$x]

            ]);
        }

        alert('Escuela Modelo', 'Las calificaciones se actualizarón con éxito', 'success')->showConfirmButton()->autoClose(5000);

        return back();

    }

    public function update(Request $request, $id)
    {

    }

    public function store(Request $request)
    {
        $grupo_id = $request->grupo_id;
        $trimestre1_edicion = $request->trimestre1_edicion;
        $inscrito_id = $request->inscrito_id;
        $trimestre_a_evaluar = $request->trimestre_a_evaluar;
        $trimestre1_faltas = 0;
        $trimestre1_observaciones = "";


        try {

            $rubricas = DB::table('bachiller_cch_calificaciones')
                ->where('bachiller_cch_calificaciones.bachiller_cch_inscrito_id',$inscrito_id)
                ->where('bachiller_cch_calificaciones.trimestre1',$trimestre_a_evaluar)
                ->where('bachiller_cch_calificaciones.aplica','SI')
                ->get();

            $calificaciones = $request->calificaciones;


            if ($trimestre_a_evaluar == 1)
            {
                $trimestre1Col  = $request->has("calificaciones.trimestre1")  ? collect($calificaciones["trimestre1"])  : collect();
                $trimestre1_faltas = $request->trimestreFaltas;
                $trimestre1_observaciones = $request->trimestreObservaciones;
            }



            // dd($inscritos->map(function ($item, $key) {
            //     return $item->id;
            // })->all());

            foreach ($rubricas as $rubrica) {
                $calificacion = Bachiller_cch_calificaciones::where('id', $rubrica->id)->first();

                if ($trimestre_a_evaluar == 1)
                {
                    $inscCalificacionRubrica = $trimestre1Col->filter(function ($value, $key) use ($rubrica) {
                        return $key == $rubrica->id;
                    })->first();

                    if ($calificacion) {
                        $calificacion->trimestre1_nivel = $inscCalificacionRubrica != null ? $inscCalificacionRubrica : $calificacion->trimestre1_nivel;
                        $calificacion->save();

                        //$result =  DB::select("call procInscritoPromedioParcial("." ".$inscrito->id." )");
                    }
                }
            }

            $inscritofaltas = Bachiller_cch_inscritos::where('id', $inscrito_id)->first();
            if ($inscritofaltas) {
                if ($trimestre_a_evaluar == 1)
                {
                    $inscritofaltas->trimestre1_faltas = $trimestre1_faltas != null ? $trimestre1_faltas : $inscritofaltas->trimestre1_faltas;
                    $inscritofaltas->trimestre1_observaciones = $trimestre1_observaciones != null ? $trimestre1_observaciones : $inscritofaltas->trimestre1_observaciones;
                }

                $inscritofaltas->save();
            }


            alert('Escuela Modelo', 'Se registraron las calificaciones con éxito', 'success')->showConfirmButton()->autoClose(3000);
            return redirect('bachiller_inscritos_seq/'.$grupo_id);

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();

            return redirect('bachiller_inscritos_seq/'.$grupo_id )->withInput();
        }
    }

    public function boletadesdecurso($curso_id)
    {

        $cursos = Curso::select('cursos.id', 'periodos.id as periodo_id', 'periodos.departamento_id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->where('cursos.id', '=', $curso_id)
        ->first();

        $periodoEscolar = Periodo::where('id', $cursos->periodo_id)->first();

        if($periodoEscolar->perAnioPago >= 2021){

            $parametro_NombreArchivo = 'pdf_bachiller_boleta_calificaciones_2021';
            $parametro_Titulo = "BOLETA DE CALIFICACIONES DEL ALUMNO(A)";
            $resultado_array =  DB::select("call procBachillerCalificacionesCurso("
                .$curso_id
                .")");
            $resultado_collection = collect( $resultado_array );
    
            if($resultado_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este alumno(a). Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }
            $resultado_registro = $resultado_array[0];



            // obtener los porcentajes 
            $bachiller_porcentajes = Bachiller_porcentajes::where('departamento_id', '=', $cursos->departamento_id)
            ->where('periodo_id', '=', $cursos->periodo_id)
            ->whereNull('deleted_at')
            ->first();
    
    
            $alumnoAgrupado = $resultado_collection->groupBy('clave_pago');
    
    
            //dd($pagos_deudores_collection);
            $parametro_Alumno = $resultado_registro->nombres . " ". $resultado_registro->ape_paterno .
                " " . $resultado_registro->ape_materno;
            $parametro_Clave = $resultado_registro->clave_pago;
            $parametro_Grupo = $resultado_registro->gpoGrado . "". $resultado_registro->gpoClave;
            $parametro_Curp = $resultado_registro->curp;
            $parametro_Ciclo = $resultado_registro->ciclo_escolar;
    
            //$fechaActual = Carbon::now();
            $fechaActual = Carbon::now('America/Merida');
    
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');
    
    
            $pdf = PDF::loadView('reportes.pdf.bachiller.boleta_de_calificaciones.'. $parametro_NombreArchivo, [
                "calificaciones" => $resultado_collection,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "cicloEscolar" => $parametro_Ciclo,
                "curp" => $parametro_Curp,
                "nombreAlumno" => $parametro_Alumno,
                "clavepago" => $parametro_Clave,
                "gradogrupo" => $parametro_Grupo,
                "titulo" => $parametro_Titulo,
                "alumnoAgrupado" => $alumnoAgrupado,
                "bachiller_porcentajes" => $bachiller_porcentajes
            ]);
    
    
            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';
    
            return $pdf->stream($parametro_NombreArchivo. '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');

        }else{
            $parametro_NombreArchivo = 'pdf_bachiller_boleta_calificaciones2';
            $parametro_Titulo = "BOLETA DE CALIFICACIONES DEL ALUMNO(A)";
            $resultado_array =  DB::select("call procBachillerCalificacionesCurso("
                .$curso_id
                .")");
            $resultado_collection = collect( $resultado_array );
    
            if($resultado_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este alumno(a). Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }
            $resultado_registro = $resultado_array[0];
    
    
            $alumnoAgrupado = $resultado_collection->groupBy('clave_pago');
    
    
            //dd($pagos_deudores_collection);
            $parametro_Alumno = $resultado_registro->nombres . " ". $resultado_registro->ape_paterno .
                " " . $resultado_registro->ape_materno;
            $parametro_Clave = $resultado_registro->clave_pago;
            $parametro_Grupo = $resultado_registro->gpoGrado . "". $resultado_registro->gpoClave;
            $parametro_Curp = $resultado_registro->curp;
            $parametro_Ciclo = $resultado_registro->ciclo_escolar;
    
            //$fechaActual = Carbon::now();
            $fechaActual = Carbon::now('America/Merida');
    
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');
    
    
            $pdf = PDF::loadView('reportes.pdf.bachiller.boleta_de_calificaciones.'. $parametro_NombreArchivo, [
                "calificaciones" => $resultado_collection,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "cicloEscolar" => $parametro_Ciclo,
                "curp" => $parametro_Curp,
                "nombreAlumno" => $parametro_Alumno,
                "clavepago" => $parametro_Clave,
                "gradogrupo" => $parametro_Grupo,
                "titulo" => $parametro_Titulo,
                "alumnoAgrupado" => $alumnoAgrupado
            ]);
    
    
            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';
    
            return $pdf->stream($parametro_NombreArchivo. '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        }



    }

    public function reporteTrimestretodos($grupo_id, $trimestre_a_evaluar)
    {

        $cursos_grupo = Curso::select(
            'cursos.id as curso_id',
            'alumnos.aluClave',
            'alumnos.id as alumno_id',
            'alumnos.aluMatricula',
            'personas.perNombre',
            'personas.id as personas_id',
            'personas.perApellido1',
            'personas.perApellido2',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnio',
            'cursos.curEstado',
            'cursos.curTipoIngreso',
            'cgt.cgtGradoSemestre',
            'cgt.cgtGrupo',
            'planes.id as plan_id',
            'planes.planClave',
            'programas.id as programa_id',
            'programas.progNombre',
            'programas.progClave',
            'escuelas.escNombre',
            'escuelas.escClave',
            'departamentos.depNombre',
            'departamentos.depClave',
            'ubicacion.ubiNombre',
            'ubicacion.ubiClave',
            'bachiller_cch_grupos.gpoGrado',
            'bachiller_cch_inscritos.id as inscrito_id',
            'bachiller_cch_inscritos.bachiller_grupo_id',
            'bachiller_cch_grupos.gpoClave',
            'bachiller_cch_inscritos.trimestre1_faltas',
            'bachiller_cch_inscritos.trimestre1_observaciones'
        )
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('bachiller_cch_inscritos', 'cursos.id', '=', 'bachiller_cch_inscritos.curso_id')
        ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('bachiller_cch_inscritos.bachiller_grupo_id', $grupo_id)
            ->whereIn('depClave', ['BAC'])
            ->orderBy("personas.perApellido1", "asc")
            ->get();

        /*foreach ($cursos_grupo as $curso_grupo) {*/
            /*
            $calificaciones_array = DB::table('preescolar_calificaciones')
                ->join('preescolar_inscritos', 'preescolar_inscritos.id', '=', 'preescolar_calificaciones.preescolar_inscrito_id')
                ->where('preescolar_inscritos.preescolar_grupo_id', $grupo_id)
                ->where('preescolar_calificaciones.trimestre1', $trimestre_a_evaluar)
                ->where('preescolar_calificaciones.aplica', 'SI')
                ->orderBy('preescolar_inscritos.id','asc')
                ->orderBy('preescolar_calificaciones.rubrica_id', 'asc')
                ->get();

            if (!$calificaciones_array) {
                alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este alumno. Favor de verificar')->showConfirmButton();
                return back()->withInput();
            }
                        $calificaciones_collection = collect($calificaciones_array);
            */

            //dd($calificaciones_array);

            $grupos_collection = collect($cursos_grupo);

            if($grupos_collection->isEmpty()) {
              alert()->warning('Sin coincidencias', 'No hay datos para mostrar.')->showConfirmButton();
              return back();
            }


            $persona = Persona::findOrFail($cursos_grupo[0]->personas_id);
            //$inscritos = Preescolar_inscrito::findOrFail($cursos_grupo->inscrito_id);
            $grupos = Bachiller_cch_grupos::findOrFail($cursos_grupo[0]->grupo_id);
            $empleado = Bachiller_empleados::findOrFail($grupos->empleado_id_docente);
            $personaDocente = Persona::findOrFail($empleado->persona_id);
            //$trimestre_faltas = $inscritos->trimestre1_faltas;
           // $trimestre_observaciones = $inscritos->trimestre1_observaciones;

            $fechaActual = Carbon::now();

            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');

            $cicloEscolar = "CICLO 2020 – 2021";

            // valida que trimestre es para asginar un nombre de reporte
            if($trimestre_a_evaluar == 1){
                $numeroReporte = "Primer Reporte";
            }
            elseif($trimestre_a_evaluar == 2){
                $numeroReporte = "Segundo Reporte";
            }
            elseif($trimestre_a_evaluar == 3){
                $numeroReporte = "Tercer Reporte";
            }else{
                $numeroReporte = "";
            }

            $kinderGradoTrimestre = "KINDER " . $cursos_grupo[0]->gpoGrado . $cursos_grupo[0]->gpoClave . " - ". $numeroReporte;
            $nombreAlumno = $persona->perNombre . " " . $persona->perApellido1 . " " . $persona->perApellido2;
            $nombreDocente = $personaDocente->perNombre . " " . $personaDocente->perApellido1 . " " . $personaDocente->perApellido2;

            $nombreArchivo = 'pdf_bachiller_reporte_general_aprovechamiento';


            $pdf = PDF::loadView('reportes.pdf.bachiller.' . $nombreArchivo, [
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "cicloEscolar" => $cicloEscolar,
                "kinderGradoTrimestre" => $kinderGradoTrimestre,
                "nombreDocente" => $nombreDocente,
                "nombreArchivo" => $nombreArchivo,
                "trimestre" => $trimestre_a_evaluar,
                "cursos_grupo" => $cursos_grupo

            ]);

            $pdf->setPaper('letter', 'portrait');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($nombreAlumno . '_' . $nombreArchivo . '.pdf');
            return $pdf->download($nombreAlumno . '_' . $nombreArchivo  . '.pdf');
        /*}*/
    }

    public function imprimirListaAsistencia($grupo_id)
    {

        $cursos_grupo = Curso::select(
            'cursos.id as curso_id',
            'alumnos.aluClave',
            'alumnos.id as alumno_id',
            'alumnos.aluMatricula',
            'personas.perNombre',
            'personas.id as personas_id',
            'personas.perApellido1',
            'personas.perApellido2',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnio',
            'cursos.curEstado',
            'cursos.curTipoIngreso',
            'cgt.cgtGradoSemestre',
            'cgt.cgtGrupo',
            'planes.id as plan_id',
            'planes.planClave',
            'programas.id as programa_id',
            'programas.progNombre',
            'programas.progClave',
            'escuelas.escNombre',
            'escuelas.escClave',
            'departamentos.depNombre',
            'departamentos.depClave',
            'ubicacion.ubiNombre',
            'ubicacion.ubiClave',
            'bachiller_cch_grupos.gpoGrado',
            'bachiller_cch_inscritos.id as inscrito_id',
            'bachiller_cch_inscritos.bachiller_grupo_id',
            'bachiller_cch_grupos.gpoClave',
            'bachiller_cch_inscritos.trimestre1_faltas',
            'bachiller_cch_inscritos.trimestre2_faltas',
            'bachiller_cch_inscritos.trimestre3_faltas',
            'bachiller_cch_inscritos.trimestre1_observaciones',
            'periodos.perFechaInicial',
            'periodos.perFechaFinal',
            'bachiller_materias.matNombre'
        )
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('bachiller_cch_inscritos', 'cursos.id', '=', 'bachiller_cch_inscritos.curso_id')
        ->join('bachiller_cch_grupos', 'bachiller_cch_inscritos.bachiller_grupo_id', '=', 'bachiller_cch_grupos.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('bachiller_materias', 'bachiller_cch_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->where('bachiller_cch_inscritos.bachiller_grupo_id', $grupo_id)
            ->whereIn('depClave', ['BAC'])
            ->orderBy("personas.perApellido1", "asc")
            ->get();
        $fechaActual = Carbon::now('CDT');


        foreach($cursos_grupo as $item){
            $persona = Persona::findOrFail($item->personas_id);
            $inscritos = Bachiller_cch_inscritos::findOrFail($item->inscrito_id);
            $grupos = Bachiller_cch_grupos::findOrFail($inscritos->grupo_id);
            $empleado = Empleado::findOrFail($grupos->empleado_id_docente);
            $personaDocente = Persona::findOrFail($empleado->persona_id);
            $periodo = Periodo::findOrFail($item->periodo_id);
            $programa = Programa::findOrFail($item->programa_id);
            $plan = Plan::findOrFail($item->plan_id);

            // ubicacion
            $ubiClave = $item->ubiClave;
            $ubiNombre = $item->ubiNombre;
            $bachiller_materia = $item->matNombre;
        }



        $info = collect([
            'perFechaInicial' => Utils::fecha_string($periodo->perFechaInicial, 'mesCorto'),
            'perFechaFinal' => Utils::fecha_string($periodo->perFechaFinal, 'mesCorto'),
            'ubicacion' => $ubiClave.' '.$ubiNombre,

            // grupo y grado
            'gradoAlumno' => $grupos->gpoGrado,
            'grupoAlumno' => $grupos->gpoClave,
            // maestro
            'nombreDocente' => $personaDocente->perNombre . ' ' . $personaDocente->perApellido1 . ' ' . $personaDocente->perApellido2,

             // programa
             'progClave' => $programa->progClave,
             'progNombre' => $programa->progNombre,
             'progNombreCorto' => $programa->progNombreCorto,

              // plan
            'planClave' => $plan->planClave,

            //materia
            'bachiller_materia' => $bachiller_materia

        ]);




        // echo '<br>';
        // echo 'plan id ' . $grupos->plan_id;
        // echo '<br>';
        // echo 'turno ' .$grupos->gpoTurno;

      // Unix
      setlocale(LC_TIME, 'es_ES.UTF-8');
      // En windows
      setlocale(LC_TIME, 'spanish');

        $nombreArchivo = 'Lista bachiller';
        $pdf = PDF::loadView('reportes.pdf.bachiller.pdf_bachiller_lista_asistencia', [
            "info" => $info,
            "cursos_grupo" => $cursos_grupo,
            "nombreArchivo" => $nombreArchivo,
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "horaActual" => $fechaActual->format('H:i:s'),
        ]);

        $pdf->setPaper('letter', 'portrait');
        $pdf->defaultFont = 'Times Sans Serif';
        return $pdf->stream($info['gradoAlumno'].$info['grupoAlumno']."_".$nombreArchivo);
        return $pdf->download($info['gradoAlumno'].$info['grupoAlumno']."_".$nombreArchivo);
    }

    public function destroy($id)
    {

    }
}
