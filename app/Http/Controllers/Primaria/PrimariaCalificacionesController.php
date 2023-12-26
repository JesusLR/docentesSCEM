<?php

namespace App\Http\Controllers\Primaria;

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
use App\Http\Models\Departamento;
use App\Http\Models\Primaria\Primaria_calificacione;
use App\Http\Models\Primaria\Primaria_falta;
use App\Http\Models\Primaria\Primaria_grupo;
use App\Http\Models\Primaria\Primaria_grupos_evidencias;
use App\Http\Models\Primaria\Primaria_inscrito;
use App\Http\Models\Primaria\Primaria_mes_evaluaciones;
use Illuminate\Support\Facades\Log;

class PrimariaCalificacionesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permisos:preescolarcalificaciones',['except' => ['index','reporteTrimestre', 'reporteTrimestretodos', 'imprimirListaAsistencia',
        // 'create', 'getAlumnos','getGrupos','getMaterias2', 'guardarCalificacion', 'getCalificacionesAlumnos', 'getMesEvidencias','edit_calificacion', 'update_calificacion']]);

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

        $calificaciones = DB::table('primaria_calificaciones')
            ->where('primaria_calificaciones.primaria_inscrito_id', $inscrito_id)
            ->where('primaria_calificaciones.trimestre1', $trimestre_a_evaluar)
            ->where('primaria_calificaciones.aplica', 'SI')
            ->get();

        //OBTENER GRUPO SELECCIONADO
        //$grupo = Grupo::with('plan.programa', 'materia', 'empleado.persona')->find($grupo_id);
        //OBTENER PROMEDIO PONDERADO EN MATERIA
        //$materia = Preescolar_materia::where('id', $grupo->primaria_materia_id)->first();
        //$escuela = Escuela::where('id', $grupo->plan->programa->escuela_id)->first();

        $grupo = Primaria_grupo::with(
            'primaria_materia',
            'periodo',
            'empleado.persona',
            'plan.programa.escuela.departamento.ubicacion'
        )
            ->find($grupo_id);

        $inscrito = Primaria_inscrito::find($inscrito_id);
        $inscrito_faltas = "";
        $inscrito_observaciones = "";
        if ($trimestre_a_evaluar == 1) {
            $inscrito_faltas = $inscrito->trimestre1_faltas;
            $inscrito_observaciones = $inscrito->trimestre1_observaciones;
        }
        if ($trimestre_a_evaluar == 2) {
            $inscrito_faltas = $inscrito->trimestre2_faltas;
            $inscrito_observaciones = $inscrito->trimestre2_observaciones;
        }
        if ($trimestre_a_evaluar == 3) {
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
        return View(
            'primaria.calendario.show-list',
            compact(
                'calificaciones',
                'grupo',
                'grupo_id',
                'inscrito_id',
                'inscrito_faltas',
                'inscrito_observaciones',
                'curso',
                'trimestre_a_evaluar',
                'trimestre1_edicion',
                'grupo_abierto'
            )
        );
    }



    public function create()
    {
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;
        $departamento = Departamento::with('ubicacion')->findOrFail(14);
        $perActual = $departamento->perActual;

        $periodos = DB::table('primaria_inscritos')
            ->select(
                'periodos.perAnioPago',
                DB::raw('count(*) as perAnioPago, periodos.perAnioPago'),
                'periodos.id',
                DB::raw('count(*) as id, periodos.id'),
                'periodos.perNumero',
                DB::raw('count(*) as perNumero, periodos.perNumero')
            )
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->groupBy('periodos.perAnioPago')
            ->groupBy('periodos.id')
            ->groupBy('periodos.perNumero')
            ->orderBy('periodos.perAnioPago', 'desc')
            ->where('primaria_grupos.periodo_id', $perActual)
            ->where('primaria_grupos.empleado_id_docente', '=', $primaria_empleado_id)
            ->get();

        return view('primaria.calificaciones.create', [
            'periodos' => $periodos,
        ]);
    }


    public function getAlumnos(Request $request, $id)
    {
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;

        if ($request->ajax()) {

            $alumnos = Primaria_inscrito::select(
                'primaria_inscritos.id',
                'primaria_inscritos.curso_id',
                'primaria_inscritos.primaria_grupo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'programas.progClave',
                'periodos.perAnio',
                'primaria_materias.matNombre',
                'planes.planClave',
                'planes.planPeriodos',
                'periodos.id as periodo_id',
                'periodos.perFechaInicial',
                'periodos.perFechaFinal',
                'alumnos.aluClave',
                'alumnos.id as alumno_id',
                'personas.perNombre',
                'personas.perApellido1',
                'personas.perApellido2'
            )
                ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
                ->join('planes', 'cgt.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
                ->where('primaria_inscritos.primaria_grupo_id', '=', $id)
                ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();

            // return response()->json($alumnos);
            return response()->json($alumnos);
        }
    }

    public function getGrupos(Request $request, $id)
    {

        $primaria_empleado_id = Auth::user()->primaria_empleado->id;
        $perActual = Auth::user()->primaria_empleado->escuela->departamento->perActual;


        if ($request->ajax()) {


            $gruposactuales = DB::table('primaria_inscritos')
                ->select(
                    'primaria_inscritos.primaria_grupo_id',
                    DB::raw('count(*) as primaria_grupo_id, primaria_inscritos.primaria_grupo_id'),
                    'primaria_grupos.gpoGrado',
                    DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado'),
                    'primaria_grupos.gpoClave',
                    DB::raw('count(*) as gpoClave, primaria_grupos.gpoClave'),
                    'periodos.perAnio',
                    DB::raw('count(*) as perAnio, periodos.perAnio'),
                    'periodos.id',
                    DB::raw('count(*) as id, periodos.id'),
                    'programas.progNombre',
                    DB::raw('count(*) as progNombre, programas.progNombre'),
                    'programas.progClave',
                    DB::raw('count(*) as progClave, programas.progClave'),
                    'primaria_materias.matClave',
                    DB::raw('count(*) as matClave, primaria_materias.matClave'),
                    'primaria_materias.matNombre',
                    DB::raw('count(*) as matNombre, primaria_materias.matNombre'),
                    'primaria_materias_asignaturas.matClaveAsignatura',
                    DB::raw('count(*) as matClaveAsignatura, primaria_materias_asignaturas.matClaveAsignatura'),
                    'primaria_materias_asignaturas.matNombreAsignatura',
                    DB::raw('count(*) as matNombreAsignatura, primaria_materias_asignaturas.matNombreAsignatura')
                )
                ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
                ->join('planes', 'cgt.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
                ->groupBy('primaria_inscritos.primaria_grupo_id')
                ->groupBy('primaria_grupos.gpoGrado')
                ->groupBy('primaria_grupos.gpoClave')
                ->groupBy('periodos.perAnio')
                ->groupBy('periodos.id')
                ->groupBy('programas.progNombre')
                ->groupBy('programas.progClave')
                ->groupBy('primaria_materias.matClave')
                ->groupBy('primaria_materias.matNombre')
                ->groupBy('primaria_materias_asignaturas.matClaveAsignatura')
                ->groupBy('primaria_materias_asignaturas.matNombreAsignatura')
                ->where('periodos.id', '=', $id)
                ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
                ->get();

            return response()->json($gruposactuales);
        }
    }

    public function getMaterias2(Request $request, $id)
    {
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;

        if ($request->ajax()) {

            $materia2 = DB::table('primaria_inscritos')
                ->select(
                    'primaria_materias.matNombre',
                    DB::raw('count(*) as matNombre, primaria_materias.matNombre'),
                    'primaria_materias.id',
                    DB::raw('count(*) as id, primaria_materias.id'),
                    'primaria_inscritos.primaria_grupo_id',
                    DB::raw('count(*) as primaria_grupo_id, primaria_inscritos.primaria_grupo_id')
                )
                ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
                ->join('planes', 'cgt.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->groupBy('primaria_materias.matNombre')
                ->groupBy('primaria_materias.id')
                ->groupBy('primaria_inscritos.primaria_grupo_id')
                ->where('primaria_inscritos.primaria_grupo_id', '=', $id)
                ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
                ->get();

            return response()->json($materia2);
        }
    }

    public function guardarCalificacion(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'primaria_inscrito_id'  => 'required',

            ],
            [
                'primaria_inscrito_id.required' => 'El campo Alumno es obligatorio.',

            ]
        );
        $primaria_inscrito_id = $request->primaria_inscrito_id;
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
        $primaria_grupo_evidencia_id = $request->primaria_grupo_evidencia_id;
        $mes_evaluacion = $request->mes;


        $obtenerCalificaciones = Primaria_calificacione::select('primaria_inscrito_id', 'mes_evaluacion')
            ->where('primaria_inscrito_id', '=', $primaria_inscrito_id[0])
            ->where('mes_evaluacion', '=', $mes_evaluacion)
            ->first();

        if (!empty($obtenerCalificaciones)) {
            alert('Escuela Modelo', 'Ya se registro calificaciones en el mes seleccionado, ingrese a editar para realizar cambios si así lo desea', 'info')->showConfirmButton();
            // return back();
            return redirect('primaria_calificacion/create')->withErrors($validator)->withInput();
        }

        if (!empty($primaria_inscrito_id)) {
            for ($i = 0; $i < count($primaria_inscrito_id); $i++) {

                $calificaciones = array();
                $calificaciones = new Primaria_calificacione();
                $calificaciones['primaria_inscrito_id'] = $primaria_inscrito_id[$i];
                $calificaciones['primaria_grupo_evidencia_id'] = $primaria_grupo_evidencia_id;
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
        } else {
            alert('Escuela Modelo', 'No se ha seleccionado ningún grupo', 'info')->showConfirmButton();
            return back();
        }
    }

    // funcion para la vista de calificaciones del grupo seleccionado
    public function edit_calificacion($id)
    {

        $primaria_empleado_id = Auth::user()->primaria_empleado->id;


        $primaria_grupo = Primaria_grupo::find($id);

        $primaria_grupos_evidencias = Primaria_grupos_evidencias::select(
            'primaria_grupos_evidencias.*',
            'primaria_mes_evaluaciones.numero_evaluacion'
        )
            ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
            ->where('primaria_grupos_evidencias.primaria_grupo_id', $id)
            ->where('primaria_mes_evaluaciones.numero_evaluacion', '!=', 4)
            ->whereNull('primaria_grupos_evidencias.deleted_at')
            ->whereNull('primaria_mes_evaluaciones.deleted_at')
            ->orderBy('primaria_mes_evaluaciones.numero_evaluacion', 'ASC')
            ->get();


        $alumnos_inscritos = Primaria_inscrito::select(
            'primaria_inscritos.id',
            'primaria_inscritos.curso_id',
            'primaria_inscritos.primaria_grupo_id',
            'primaria_grupos.gpoGrado',
            'primaria_grupos.gpoClave',
            'primaria_grupos.plan_id',
            'primaria_grupos.gpoMatComplementaria',
            'programas.progClave',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnioPago',
            'primaria_materias.matClave',
            'primaria_materias.matNombre',
            'planes.planClave',
            'planes.planPeriodos',
            'periodos.id as periodo_id',
            'periodos.perFechaInicial',
            'periodos.perFechaFinal',
            'periodos.perAnio',
            'alumnos.aluClave',
            'alumnos.id as alumno_id',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'primaria_materias_asignaturas.matClaveAsignatura',
            'primaria_materias_asignaturas.matNombreAsignatura',
            'programas.progNombre'
        )
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
            ->where('primaria_inscritos.primaria_grupo_id', '=', $id)
            ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
            ->whereNull('primaria_grupos.deleted_at')
            ->whereNull('cursos.deleted_at')
            ->whereNull('cgt.deleted_at')
            ->whereNull('planes.deleted_at')
            ->whereNull('programas.deleted_at')
            ->whereNull('periodos.deleted_at')
            ->whereNull('primaria_materias.deleted_at')
            ->whereNull('primaria_inscritos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();


        if (count($alumnos_inscritos) <= 0) {
            alert()->warning('Escuela Modelo', 'Aun no hay alumnos inscritos a este grupo.')->showConfirmButton();
            return back();
        }


        // obtenemos el mes a evaluar y si no hay no dejara pasar a la vista de captura de calificaciones
        $resultado_array =  DB::select("call procPrimariaMesAMostrar(" . $id . ", "
            . $alumnos_inscritos[0]->plan_id . ", "
            . $alumnos_inscritos[0]->periodo_id . ")");
        $mesEvidencia = collect($resultado_array);


        if (count($mesEvidencia) == 0) {
            alert()->warning('Modelo', 'Aun no hay fechas de captura de calificaciones disponibles.')->showConfirmButton();
            return back();
        }



        $primaria_calificaciones = Primaria_calificacione::select('primaria_calificaciones.*')
            ->join('primaria_inscritos', 'primaria_calificaciones.primaria_inscrito_id', '=', 'primaria_inscritos.id')
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->whereNull('primaria_calificaciones.deleted_at')
            ->whereNull('primaria_grupos.deleted_at')
            ->whereNull('primaria_inscritos.deleted_at')
            ->whereNull('primaria_calificaciones.promedio_mes')
            ->where('primaria_grupos.id', $id)
            ->get();

        // actualizamos el estado del grupo 
        if (count($primaria_calificaciones) <= 0) {
            DB::update("UPDATE primaria_grupos SET estado_act='B' WHERE id=$id");
        }

        // validaremos si tiene calificaciones y si es menor a uno lo creamos 
        if (count($alumnos_inscritos) > 0) {
            // creamos un ciclo para agregar calificaciones de todos los meses 
            if (count($mesEvidencia) > 0) {
                foreach ($mesEvidencia as $key => $mes_evidencia) {
                    //dd($mesEvidencia[0]->id);
                    // $resultado_repetidos =  DB::select("call procPrimariaCalificacionesInexistentesRepetidos("
                    //     . $value->id . ", " . $id . ")");


                    foreach ($alumnos_inscritos as $jey => $alumno) {


                        $califica = Primaria_calificacione::where('primaria_inscrito_id', $alumno->id)
                            ->where('mes_evaluacion', $mes_evidencia->mes)
                            ->whereNull('deleted_at')
                            ->get();

                        if (count($califica) < 1) {
                            // $resultado_repetidos =  DB::select("call procPrimariaCalificacionesInexistentesRepetidos("
                            //  . $mes_evidencia->id . ", " . $id . ")");

                            $resultado_repetidos =  DB::select("call procPrimariaAgregaCalificacionEvidencia(
                                " . $alumno->primaria_grupo_id . ",
                                " . $mes_evidencia->id . ",
                                " . $alumno->id . ",
                                'DOCENTE',
                                " . auth()->user()->id . "
                            )");
                        }
                    }
                }
            }
        }

        //dd($mesEvidencia[0]->id);
        // $resultado_repetidos =  DB::select("call procPrimariaCalificacionesInexistentesRepetidos("
        //     . $mesEvidencia[0]->id . ", " . $id . ")");

        // Log::debug($resultado_repetidos);




        return view('primaria.calificaciones.calificaciones-new-version', [
            'calificaciones' => $alumnos_inscritos,
            'primaria_grupo' => $primaria_grupo,
            'primaria_grupos_evidencias' => $primaria_grupos_evidencias
        ]);
    }


    public function obtenerDatosEvidencia(Request $request, $id)
    {
        if ($request->ajax()) {

            $mesEvidencia = Primaria_grupos_evidencias::select(
                'primaria_grupos_evidencias.*',
                'primaria_mes_evaluaciones.numero_evaluacion'
            )
                ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
                ->where('primaria_grupos_evidencias.id', $id)
                ->whereNull('primaria_grupos_evidencias.deleted_at')
                ->whereNull('primaria_mes_evaluaciones.deleted_at')
                ->get();

            return response()->json($mesEvidencia);
        }
    }

    public function newCalificacionesAlumnos(Request $request, $id, $grupoId)
    {
        $resultado_array =  DB::select("call procPrimariaCalificacionesInexistentesRepetidos("
            . $id . ", " . $grupoId . ")");
    }

    public function getCalificacionesAlumnos(Request $request, $id, $grupoId)
    {

        if ($request->ajax()) {

            $calificaciones = Primaria_calificacione::select(
                'primaria_calificaciones.id',
                'primaria_calificaciones.primaria_inscrito_id',
                'primaria_calificaciones.primaria_grupo_evidencia_id',
                'primaria_calificaciones.numero_evaluacion',
                'primaria_calificaciones.mes_evaluacion',
                'primaria_calificaciones.calificacion_evidencia1',
                'primaria_calificaciones.calificacion_evidencia2',
                'primaria_calificaciones.calificacion_evidencia3',
                'primaria_calificaciones.calificacion_evidencia4',
                'primaria_calificaciones.calificacion_evidencia5',
                'primaria_calificaciones.calificacion_evidencia6',
                'primaria_calificaciones.calificacion_evidencia7',
                'primaria_calificaciones.calificacion_evidencia8',
                'primaria_calificaciones.calificacion_evidencia9',
                'primaria_calificaciones.calificacion_evidencia10',
                'primaria_calificaciones.promedio_mes',
                'primaria_inscritos.primaria_grupo_id',
                'primaria_grupos.gpoGrado',
                'primaria_grupos.gpoClave',
                'primaria_materias.id as id_materia',
                'primaria_materias.matClave',
                'primaria_materias.matNombre',
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
                'alumnos.aluClave',
                'primaria_faltas.retTotalSep',
                'primaria_faltas.retTotalOct',
                'primaria_faltas.retTotalNov',
                'primaria_faltas.retTotalEne',
                'primaria_faltas.retTotalFeb',
                'primaria_faltas.retTotalMar',
                'primaria_faltas.retTotalAbr',
                'primaria_faltas.retTotalMay',
                'primaria_faltas.retTotalJun',
                'primaria_faltas.falTotalSep',
                'primaria_faltas.falTotalOct',
                'primaria_faltas.falTotalNov',
                'primaria_faltas.falTotalEne',
                'primaria_faltas.falTotalFeb',
                'primaria_faltas.falTotalMar',
                'primaria_faltas.falTotalAbr',
                'primaria_faltas.falTotalMay',
                'primaria_faltas.falTotalJun'
            )
                ->join('primaria_inscritos', 'primaria_calificaciones.primaria_inscrito_id', '=', 'primaria_inscritos.id')
                ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->leftJoin('primaria_faltas', 'cursos.id', '=', 'primaria_faltas.curso_id')
                ->where('primaria_calificaciones.primaria_grupo_evidencia_id', '=', $id)
                ->where('primaria_inscritos.primaria_grupo_id', '=', $grupoId)
                ->whereNull('primaria_inscritos.deleted_at')
                ->whereNull('primaria_calificaciones.deleted_at')
                ->whereNull('primaria_faltas.deleted_at')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('personas.perApellido2', 'ASC')
                ->orderBy('personas.perNombre', 'ASC')
                ->get();

            $primaria_grupos_evidencias = Primaria_grupos_evidencias::select(
                'primaria_grupos_evidencias.*',
                'primaria_mes_evaluaciones.numero_evaluacion'
            )
                ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
                ->where('primaria_grupos_evidencias.id', $id)
                ->whereNull('primaria_grupos_evidencias.deleted_at')
                ->whereNull('primaria_mes_evaluaciones.deleted_at')
                ->get();

            return response()->json([
                'calificaciones' => $calificaciones,
                'primaria_grupos_evidencias' => $primaria_grupos_evidencias
            ]);
        }
    }

    // funcion para actualizar calificaciones del grupo seleccionado
    public function update_calificacion(Request $request)
    {

        $fechaActual = Carbon::now('America/Merida');

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');


        $id = $request->id;
        $primaria_inscrito_id = $request->primaria_inscrito_id;
        $primaria_grupo_evidencia_id = $request->primaria_grupo_evidencia_id;
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

        $retTotalSep = $request->retTotalSep;
        $falTotalSep = $request->falTotalSep;

        $retTotalOct = $request->retTotalOct;
        $falTotalOct = $request->falTotalOct;

        $retTotalNov = $request->retTotalNov;
        $falTotalNov = $request->falTotalNov;

        $retTotalDic = $request->retTotalDic;
        $falTotalDic = $request->falTotalDic;

        $retTotalEne = $request->retTotalEne;
        $falTotalEne = $request->falTotalEne;

        $retTotalFeb = $request->retTotalFeb;
        $falTotalFeb = $request->falTotalFeb;

        $retTotalMar = $request->retTotalMar;
        $falTotalMar = $request->falTotalMar;

        $retTotalAbr = $request->retTotalAbr;
        $falTotalAbr = $request->falTotalAbr;

        $retTotalMay = $request->retTotalMay;
        $falTotalMay = $request->falTotalMay;

        $retTotalJun = $request->retTotalJun;
        $falTotalJun = $request->falTotalJun;

        $usuario_at = auth()->user()->id;


        $matNombre = $request->matNombre;

        $primaria_grupos_evidencias = Primaria_grupos_evidencias::select('primaria_mes_evaluaciones.*')
            ->join('primaria_mes_evaluaciones', 'primaria_grupos_evidencias.primaria_mes_evaluacion_id', '=', 'primaria_mes_evaluaciones.id')
            ->where('primaria_grupos_evidencias.id', $primaria_grupo_evidencia_id)
            ->first();
        $mes_evaluacion = $primaria_grupos_evidencias->mes;
        $numero_evaluacion = $primaria_grupos_evidencias->numero_evaluacion;

        $tipoDeAccion = $request->tipoDeAccion;


        if ($tipoDeAccion == "ACTUALIZAR") {
            for ($i = 0; $i < count($primaria_inscrito_id); $i++) {

                if ($promedioTotal[$i] < 5 || $promedioTotal[$i] > 10) {
                    $actualizandoPromedio[$i] = null;
                } else {
                    $actualizandoPromedio[$i] = $promedioTotal[$i];
                }

                DB::table('primaria_calificaciones')
                    ->where('id', $id[$i])
                    ->update([

                        'primaria_inscrito_id' => $primaria_inscrito_id[$i],
                        'primaria_grupo_evidencia_id' => $primaria_grupo_evidencia_id,
                        'numero_evaluacion' => $numero_evaluacion,
                        'mes_evaluacion' => $mes_evaluacion,
                        'calificacion_evidencia1' => $evidencia1[$i],
                        'calificacion_evidencia2' => $evidencia2[$i],
                        'calificacion_evidencia3' => $evidencia3[$i],
                        'calificacion_evidencia4' => $evidencia4[$i],
                        'calificacion_evidencia5' => $evidencia5[$i],
                        'calificacion_evidencia6' => $evidencia6[$i],
                        'calificacion_evidencia7' => $evidencia7[$i],
                        'calificacion_evidencia8' => $evidencia8[$i],
                        'calificacion_evidencia9' => $evidencia9[$i],
                        'calificacion_evidencia10' => $evidencia10[$i],
                        'promedio_mes' => $actualizandoPromedio[$i],
                        'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                        'user_docente_id' => auth()->user()->id

                    ]);

                $inscrito = Primaria_inscrito::find($primaria_inscrito_id[$i]);
                $primaria_falta = Primaria_falta::where('curso_id', $inscrito->curso_id)->first();

                // SEPTIEMBRE
                if ($numero_evaluacion == "1") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionSep' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);


                    // para actualizar faltas y retardos 
                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalSep' => $retTotalSep[$i],
                                'falTotalSep' => $falTotalSep[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalSep' => $retTotalSep[$i],
                                    'falTotalSep' => $falTotalSep[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //OCTUBRE
                if ($numero_evaluacion == "2") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionOct' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);


                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalOct' => $retTotalOct[$i],
                                'falTotalOct' => $falTotalOct[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalOct' => $retTotalOct[$i],
                                    'falTotalOct' => $falTotalOct[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //NOVIEMBRE
                if ($numero_evaluacion == "3") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionNov' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);


                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalNov' => $retTotalNov[$i],
                                'falTotalNov' => $falTotalNov[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalNov' => $retTotalNov[$i],
                                    'falTotalNov' => $falTotalNov[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }



                //DICIEMBRE
                if ($numero_evaluacion == "4") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionDic' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);


                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalDic' => $retTotalDic[$i],
                                'falTotalDic' => $falTotalDic[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalDic' => $retTotalDic[$i],
                                    'falTotalDic' => $falTotalDic[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //ENERO
                if ($numero_evaluacion == "5") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionEne' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);

                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalEne' => $retTotalEne[$i],
                                'falTotalEne' => $falTotalEne[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalEne' => $retTotalEne[$i],
                                    'falTotalEne' => $falTotalEne[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //FEBRERO
                if ($numero_evaluacion == "6") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionFeb' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);

                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalFeb' => $retTotalFeb[$i],
                                'falTotalFeb' => $falTotalFeb[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalFeb' => $retTotalFeb[$i],
                                    'falTotalFeb' => $falTotalFeb[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //MARZO
                if ($numero_evaluacion == "7") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionMar' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);

                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalMar' => $retTotalMar[$i],
                                'falTotalMar' => $falTotalMar[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalMar' => $retTotalMar[$i],
                                    'falTotalMar' => $falTotalMar[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //ABRIL
                if ($numero_evaluacion == "8") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionAbr' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);

                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalAbr' => $retTotalAbr[$i],
                                'falTotalAbr' => $falTotalAbr[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalAbr' => $retTotalAbr[$i],
                                    'falTotalAbr' => $falTotalAbr[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //MAYO
                if ($numero_evaluacion == "9") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionMay' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);

                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalMay' => $retTotalMay[$i],
                                'falTotalMay' => $falTotalMay[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalMay' => $retTotalMay[$i],
                                    'falTotalMay' => $falTotalMay[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }

                //JUNIO
                if ($numero_evaluacion == "10") {
                    DB::table('primaria_inscritos')
                        ->where('id', $primaria_inscrito_id[$i])
                        ->update([
                            'inscCalificacionJun' => $actualizandoPromedio[$i],
                            'user_docente_id' => auth()->user()->id,
                            'updated_at' => $fechaActual->format('Y-m-d H:i:s')
                        ]);

                    if ($matNombre == "CONDUCTA") {
                        if ($primaria_falta == "") {

                            $primaria_falta_new = Primaria_falta::create([
                                'curso_id' => $inscrito->curso_id,
                                'retTotalJun' => $retTotalJun[$i],
                                'falTotalJun' => $falTotalJun[$i],
                                'created_at' => $fechaActual->format('Y-m-d H:i:s'),
                                'user_docente_id' => auth()->user()->id
                            ]);
                        } else {

                            // return Primaria_falta::find($primaria_falta->id)

                            DB::table('primaria_faltas')
                                ->where('id', $primaria_falta->id)
                                ->update([
                                    'retTotalJun' => $retTotalJun[$i],
                                    'falTotalJun' => $falTotalJun[$i],
                                    'updated_at' => $fechaActual->format('Y-m-d H:i:s'),
                                    'user_docente_id' => auth()->user()->id
                                ]);
                        }
                    }
                }


                // validamos si el registro se acaba de crear 
                if($primaria_falta == ""){
                    $falta_id = $primaria_falta_new->id;
                }else{
                    $falta_id = $primaria_falta->id;
                }


                DB::select("call procPrimariaActualizaTrimFaltas(".$falta_id.")");
            }

            alert('Escuela Modelo', 'Las calificaciones se actualizaron con éxito', 'success')->showConfirmButton();
            return back();
        }
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

            $rubricas = DB::table('primaria_calificaciones')
                ->where('primaria_calificaciones.primaria_inscrito_id', $inscrito_id)
                ->where('primaria_calificaciones.trimestre1', $trimestre_a_evaluar)
                ->where('primaria_calificaciones.aplica', 'SI')
                ->get();

            $calificaciones = $request->calificaciones;


            if ($trimestre_a_evaluar == 1) {
                $trimestre1Col  = $request->has("calificaciones.trimestre1")  ? collect($calificaciones["trimestre1"])  : collect();
                $trimestre1_faltas = $request->trimestreFaltas;
                $trimestre1_observaciones = $request->trimestreObservaciones;
            }



            // dd($inscritos->map(function ($item, $key) {
            //     return $item->id;
            // })->all());

            foreach ($rubricas as $rubrica) {
                $calificacion = Primaria_calificacione::where('id', $rubrica->id)->first();

                if ($trimestre_a_evaluar == 1) {
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

            $inscritofaltas = Primaria_inscrito::where('id', $inscrito_id)->first();
            if ($inscritofaltas) {
                if ($trimestre_a_evaluar == 1) {
                    $inscritofaltas->trimestre1_faltas = $trimestre1_faltas != null ? $trimestre1_faltas : $inscritofaltas->trimestre1_faltas;
                    $inscritofaltas->trimestre1_observaciones = $trimestre1_observaciones != null ? $trimestre1_observaciones : $inscritofaltas->trimestre1_observaciones;
                }

                $inscritofaltas->save();
            }


            alert('Escuela Modelo', 'Se registraron las calificaciones con éxito', 'success')->showConfirmButton()->autoClose(3000);
            return redirect('primaria_inscritos/' . $grupo_id);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();

            return redirect('primaria_inscritos/' . $grupo_id)->withInput();
        }
    }

    public function boletadesdecurso($curso_id)
    {

        $parametro_NombreArchivo = 'pdf_primaria_boleta_calificaciones';
        $parametro_Titulo = "BOLETA DE CALIFICACIONES DEL ALUMNO(A)";
        $resultado_array =  DB::select("call procPrimariaBoletaCalificacionesCurso("
            . $curso_id
            . ")");
        $resultado_collection = collect($resultado_array);

        if ($resultado_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este alumno(a). Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }
        $resultado_registro = $resultado_array[0];


        $alumnoAgrupado = $resultado_collection->groupBy('clave_pago');


        //dd($pagos_deudores_collection);
        $parametro_Alumno = $resultado_registro->nombres . " " . $resultado_registro->ape_paterno .
            " " . $resultado_registro->ape_materno;
        $parametro_Clave = $resultado_registro->clave_pago;
        $parametro_Grupo = $resultado_registro->gpoGrado . "" . $resultado_registro->gpoClave;
        $parametro_Curp = $resultado_registro->curp;
        $parametro_Ciclo = $resultado_registro->ciclo_escolar;

        //$fechaActual = Carbon::now();
        $fechaActual = Carbon::now('America/Merida');

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');


        $pdf = PDF::loadView('reportes.pdf.primaria.boleta_de_calificaciones.' . $parametro_NombreArchivo, [
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

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
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
            'primaria_grupos.gpoGrado',
            'primaria_inscritos.id as inscrito_id',
            'primaria_inscritos.primaria_grupo_id',
            'primaria_grupos.gpoClave',
            'primaria_inscritos.trimestre1_faltas',
            'primaria_inscritos.trimestre1_observaciones'
        )
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('primaria_inscritos', 'cursos.id', '=', 'primaria_inscritos.curso_id')
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('primaria_inscritos.primaria_grupo_id', $grupo_id)
            ->whereIn('depClave', ['PRI'])
            ->orderBy("personas.perApellido1", "asc")
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
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

        if ($grupos_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay datos para mostrar.')->showConfirmButton();
            return back();
        }


        $persona = Persona::findOrFail($cursos_grupo[0]->personas_id);
        //$inscritos = Preescolar_inscrito::findOrFail($cursos_grupo->inscrito_id);
        $grupos = Primaria_grupo::findOrFail($cursos_grupo[0]->primaria_grupo_id);
        $empleado = Empleado::findOrFail($grupos->empleado_id_docente);
        $personaDocente = Persona::findOrFail($empleado->persona_id);
        //$trimestre_faltas = $inscritos->trimestre1_faltas;
        // $trimestre_observaciones = $inscritos->trimestre1_observaciones;

        $fechaActual = Carbon::now();

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $cicloEscolar = "CICLO 2020 – 2021";

        // valida que trimestre es para asginar un nombre de reporte
        if ($trimestre_a_evaluar == 1) {
            $numeroReporte = "Primer Reporte";
        } elseif ($trimestre_a_evaluar == 2) {
            $numeroReporte = "Segundo Reporte";
        } elseif ($trimestre_a_evaluar == 3) {
            $numeroReporte = "Tercer Reporte";
        } else {
            $numeroReporte = "";
        }

        $kinderGradoTrimestre = "KINDER " . $cursos_grupo[0]->gpoGrado . $cursos_grupo[0]->gpoClave . " - " . $numeroReporte;
        $nombreAlumno = $persona->perNombre . " " . $persona->perApellido1 . " " . $persona->perApellido2;
        $nombreDocente = $personaDocente->perNombre . " " . $personaDocente->perApellido1 . " " . $personaDocente->perApellido2;

        $nombreArchivo = 'pdf_primaria_reporte_general_aprovechamiento';


        $pdf = PDF::loadView('reportes.pdf.primaria.' . $nombreArchivo, [
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
            'primaria_grupos.gpoGrado',
            'primaria_inscritos.id as inscrito_id',
            'primaria_inscritos.primaria_grupo_id',
            'primaria_grupos.gpoClave',
            'primaria_inscritos.trimestre1_faltas',
            'primaria_inscritos.trimestre2_faltas',
            'primaria_inscritos.trimestre3_faltas',
            'primaria_inscritos.trimestre1_observaciones',
            'periodos.perFechaInicial',
            'periodos.perFechaFinal',
            'primaria_materias.matNombre'
        )
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('primaria_inscritos', 'cursos.id', '=', 'primaria_inscritos.curso_id')
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->where('primaria_inscritos.primaria_grupo_id', $grupo_id)
            ->whereIn('depClave', ['PRE'])
            ->orderBy("personas.perApellido1", "asc")
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();
        $fechaActual = Carbon::now('CDT');


        foreach ($cursos_grupo as $item) {
            $persona = Persona::findOrFail($item->personas_id);
            $inscritos = Primaria_inscrito::findOrFail($item->inscrito_id);
            $grupos = Primaria_grupo::findOrFail($inscritos->primaria_grupo_id);
            $empleado = Empleado::findOrFail($grupos->empleado_id_docente);
            $personaDocente = Persona::findOrFail($empleado->persona_id);
            $periodo = Periodo::findOrFail($item->periodo_id);
            $programa = Programa::findOrFail($item->programa_id);
            $plan = Plan::findOrFail($item->plan_id);

            // ubicacion
            $ubiClave = $item->ubiClave;
            $ubiNombre = $item->ubiNombre;
            $primaria_materia = $item->matNombre;
        }



        $info = collect([
            'perFechaInicial' => Utils::fecha_string($periodo->perFechaInicial, 'mesCorto'),
            'perFechaFinal' => Utils::fecha_string($periodo->perFechaFinal, 'mesCorto'),
            'ubicacion' => $ubiClave . ' ' . $ubiNombre,

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
            'primaria_materia' => $primaria_materia

        ]);




        // echo '<br>';
        // echo 'plan id ' . $grupos->plan_id;
        // echo '<br>';
        // echo 'turno ' .$grupos->gpoTurno;

        // Unix
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $nombreArchivo = 'Lista primaria';
        $pdf = PDF::loadView('reportes.pdf.primaria.pdf_primaria_lista_asistencia', [
            "info" => $info,
            "cursos_grupo" => $cursos_grupo,
            "nombreArchivo" => $nombreArchivo,
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "horaActual" => $fechaActual->format('H:i:s'),
        ]);

        $pdf->setPaper('letter', 'portrait');
        $pdf->defaultFont = 'Times Sans Serif';
        return $pdf->stream($info['gradoAlumno'] . $info['grupoAlumno'] . "_" . $nombreArchivo);
        return $pdf->download($info['gradoAlumno'] . $info['grupoAlumno'] . "_" . $nombreArchivo);
    }

    public function destroy($id)
    {
    }
}
