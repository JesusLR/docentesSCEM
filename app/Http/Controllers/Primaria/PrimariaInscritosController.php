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

use App\Http\Models\Grupo;
use App\Http\Models\Curso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Departamento;
use App\Http\Models\Primaria\Primaria_asistencia;
use App\Http\Models\Primaria\Primaria_grupo;
use App\Http\Models\Primaria\Primaria_inscrito;

class PrimariaInscritosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permisos:preescolarinscritos',['except' => ['index','list','pase_de_lista', 'guardarPaseLista', 'obtenerAlumnosPaseLista', 'asistencia_alumnos']]);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $grupo_id = $request->grupo_id;

        $grupo = Primaria_grupo::select(
            'primaria_grupos.id',
            'primaria_grupos.gpoGrado',
            'primaria_grupos.gpoClave',
            'primaria_materias.id as primaria_materia_id',
            'primaria_materias.matClave',
            'primaria_materias.matNombre',
            'primaria_empleados.empNombre',
            'primaria_empleados.empApellido1',
            'primaria_empleados.empApellido2',
            'primaria_empleados.empSexo',
            'primaria_materias_asignaturas.matClaveAsignatura',
            'primaria_materias_asignaturas.matNombreAsignatura'
        )
        ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
        ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
        ->where('primaria_grupos.id', $grupo_id)
        ->first();

        return view('primaria.inscritos.show-list-inscritos', [
            "grupo" => $grupo
        ]);


    }

    /**
     * Show user list.
     *
     */
    public function list($grupo_id)
    {
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;

        $cursos = Curso::select('cursos.id as curso_id',
            'alumnos.aluClave', 'alumnos.id as alumno_id', 'alumnos.aluMatricula', 'personas.perNombre', 'personas.id as personas_id',
            'personas.perApellido1', 'personas.perApellido2', 'periodos.id as periodo_id',
            'periodos.perNumero', 'periodos.perAnio', 'cursos.curEstado', 'cursos.curTipoIngreso',
            'cgt.cgtGradoSemestre', 'cgt.cgtGrupo', 'planes.id as plan_id', 'planes.planClave', 'programas.id as programa_id',
            'programas.progNombre', 'programas.progClave',
            'escuelas.escNombre', 'escuelas.escClave',
            'departamentos.depNombre', 'departamentos.depClave',
            'ubicacion.ubiNombre', 'ubicacion.ubiClave',
            'primaria_grupos.gpoGrado',
            'primaria_inscritos.id as inscrito_id','primaria_inscritos.primaria_grupo_id',
            'primaria_grupos.gpoClave',
            'primaria_empleados.id as empleado_id',
            'primaria_materias_asignaturas.matClaveAsignatura',
            'primaria_materias_asignaturas.matNombreAsignatura',
            'primaria_inscritos.inscTipoAsistencia',
            'primaria_empleados.empNombre',
            'primaria_empleados.empApellido1',
            'primaria_empleados.empApellido2',
            'primaria_inscritos.inscEmpleadoIdDocente')
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
            ->join('primaria_empleados', 'primaria_inscritos.inscEmpleadoIdDocente', '=', 'primaria_empleados.id')
            ->leftJoin('primaria_materias_asignaturas', 'primaria_grupos.primaria_materia_asignatura_id', '=', 'primaria_materias_asignaturas.id')
            ->where('primaria_inscritos.primaria_grupo_id',$grupo_id)
            ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
            ->whereIn('depClave', ['PRI'])
            ->whereNull('primaria_inscritos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC');
            //->latest('cgt.created_at');


        return Datatables::of($cursos)
            ->addColumn('action', function($cursos)
            {
                    $acciones = '';

                    //SIN AHORRO ESTE 2021
                    /*
                    $acciones = '<div class="row">
                    <a href="/primaria_ahorro_escolar/create/alumno/'.$cursos->empleado_id.'/'.$cursos->curso_id.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="Registrar ahorro" >
                        <i class="material-icons">local_atm</i>
                    </a>

                    <a href="/reporte/ahorro_escolar/desde_grupo/imprimir/'.$cursos->programa_id.'/'.$cursos->plan_id.'/'.$cursos->periodo_id.'/'.$cursos->cgtGradoSemestre.'/'.$cursos->cgtGrupo.'/'.$cursos->aluClave.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="Estado de cuenta" >
                    <i class="material-icons">picture_as_pdf</i>
                    </a>
                    </div>';
                    */


                return $acciones;
            })
        ->make(true);
    }

    public function pase_de_lista($grupo_id)
    {
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;


        $cursosPaseLista = Curso::select('cursos.id as curso_id',
        'alumnos.aluClave', 'alumnos.id as alumno_id', 'alumnos.aluMatricula', 'personas.perNombre', 'personas.id as personas_id',
        'personas.perApellido1', 'personas.perApellido2', 'periodos.id as periodo_id',
        'periodos.perNumero', 'periodos.perAnio', 'cursos.curEstado', 'cursos.curTipoIngreso',
        'cgt.cgtGradoSemestre', 'cgt.cgtGrupo', 'planes.id as plan_id', 'planes.planClave', 'programas.id as programa_id',
        'programas.progNombre', 'programas.progClave',
        'escuelas.escNombre', 'escuelas.escClave',
        'departamentos.depNombre', 'departamentos.depClave',
        'ubicacion.ubiNombre', 'ubicacion.ubiClave',
        'primaria_grupos.gpoGrado',
        'primaria_inscritos.id as inscrito_id','primaria_inscritos.primaria_grupo_id',
        'primaria_grupos.gpoClave',
        'primaria_inscritos.inscEmpleadoIdDocente',
        'primaria_inscritos.inscTipoAsistencia')
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
        ->where('primaria_inscritos.primaria_grupo_id',$grupo_id)
        ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
        ->whereIn('depClave', ['PRI'])
        ->whereNull('primaria_inscritos.deleted_at')
        ->orderBy('personas.perApellido1', 'ASC')
        ->orderBy('personas.perApellido2', 'ASC')
        ->orderBy('personas.perNombre', 'ASC')
        ->get();

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $fechaHoy = $fechaActual->format('Y-m-d');

        $primaria_asistencia1 = Primaria_asistencia::select(
            'primaria_asistencia.id',
            'primaria_asistencia.asistencia_inscrito_id',
            'primaria_asistencia.fecha_asistencia',
            'primaria_asistencia.estado',
            'alumnos.id as alumno_id',
            'alumnos.aluClave',
            'personas.id as persona_id',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnio',
            'primaria_inscritos.inscEmpleadoIdDocente',
            'primaria_inscritos.inscTipoAsistencia'
        )
        ->join('primaria_inscritos', 'primaria_asistencia.asistencia_inscrito_id', '=', 'primaria_inscritos.id')
        ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
        ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->where('primaria_inscritos.primaria_grupo_id', $grupo_id)
        ->where('primaria_asistencia.fecha_asistencia', '=', $fechaHoy)
        ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->get();
        $primaria_asistencia1_collection = collect($primaria_asistencia1);
        $Total = count($primaria_asistencia1_collection);


        $ldate = date('Y-m-d');



        $paseAsistencia_collection = collect($cursosPaseLista);

        if($paseAsistencia_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay datos para mostrar.')->showConfirmButton();
            return back();
        }

        $grupo = Primaria_grupo::with('plan.programa','primaria_materia','primaria_empleado', 'primaria_materia_asignatura')->find($grupo_id);


        $departamento = Departamento::with('ubicacion')->findOrFail(14);
        $perActual = $departamento->perActual;

        return view('primaria.inscritos.paseDelista', [
            'paseAsistencia_collection' => $paseAsistencia_collection,
            'grupo' => $grupo,
            'primaria_asistencia1' => $primaria_asistencia1,
            'ldate' => $ldate,
            'Total' => $Total,
            'perActual' => $perActual
        ]);
    }

    public function obtenerAlumnosPaseLista(Request $request, $grupo_id, $fecha)
    {

        $primaria_empleado_id = Auth::user()->primaria_empleado->id;

        if ($request->ajax()) {

            $primaria_asistencia = Primaria_asistencia::select(
                'primaria_asistencia.id',
                'primaria_asistencia.asistencia_inscrito_id',
                'primaria_asistencia.fecha_asistencia',
                'primaria_asistencia.estado',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.id as persona_id',
                'personas.perNombre',
                'personas.perApellido1',
                'personas.perApellido2',
                'periodos.id as periodo_id',
                'periodos.perNumero',
                'periodos.perAnioPago'
            )
            ->join('primaria_inscritos', 'primaria_asistencia.asistencia_inscrito_id', '=', 'primaria_inscritos.id')
            ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->where('primaria_inscritos.primaria_grupo_id', $grupo_id)
            ->where('primaria_asistencia.fecha_asistencia', '=', $fecha)
            ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
            ->whereNull('primaria_inscritos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();


            $cursosPaseLista = Curso::select('cursos.id as curso_id',
            'alumnos.aluClave', 'alumnos.id as alumno_id', 'alumnos.aluMatricula', 'personas.perNombre', 'personas.id as personas_id',
            'personas.perApellido1', 'personas.perApellido2', 'periodos.id as periodo_id',
            'periodos.perNumero', 'periodos.perAnio', 'cursos.curEstado', 'cursos.curTipoIngreso',
            'cgt.cgtGradoSemestre', 'cgt.cgtGrupo', 'planes.id as plan_id', 'planes.planClave', 'programas.id as programa_id',
            'programas.progNombre', 'programas.progClave',
            'escuelas.escNombre', 'escuelas.escClave',
            'departamentos.depNombre', 'departamentos.depClave',
            'ubicacion.ubiNombre', 'ubicacion.ubiClave',
            'primaria_grupos.gpoGrado',
            'primaria_inscritos.id as inscrito_id','primaria_inscritos.primaria_grupo_id',
            'primaria_grupos.gpoClave')
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
            ->where('primaria_inscritos.primaria_grupo_id',$grupo_id)
            ->whereIn('depClave', ['PRI'])
            ->whereNull('primaria_inscritos.deleted_at')
            ->where('primaria_inscritos.inscEmpleadoIdDocente', '=', $primaria_empleado_id)
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

            return response()->json([
                'primaria_asistencia' => $primaria_asistencia,
                'cursosPaseLista' => $cursosPaseLista
            ]);

        }
    }

    public function asistencia_alumnos(Request $request)
    {
        $asistencia_id = $request->asistencia_id;
        $estado = $request->estado;
        $fecha_asistencia = $request->fecha_asistencia;
        $tipoDeAccion = $request->tipoDeAccion;
        $grupo_id = $request->grupo_id;
        $asistencia_inscrito_id = $request->asistencia_inscrito_id;
        $date_asistencia = Carbon::createFromFormat('Y-m-d', $request->fecha_asistencia);
        $mes_asistencia = $date_asistencia->month;

        // dd($asistencia_id, $estado, $fecha_asistencia, $tipoDeAccion, $grupo_id);


        try{

            if($tipoDeAccion == "ACTUALIZAR_ASISTENCIA"){

                for($i=0; $i < count($asistencia_id); $i++){
                    DB::table('primaria_asistencia')
                    ->where('id',$asistencia_id[$i])
                    ->where('fecha_asistencia',$fecha_asistencia)
                    ->update([
                        'estado' => $estado[$i],
                        'fecha_asistencia' => $fecha_asistencia,
                        'user_docente_id' => auth()->user()->id
                    ]);

                    $primaria_faltas = DB::statement('call procPrimariaFaltasAlumnosInscritos(?, ?)',[$asistencia_inscrito_id[$i], $mes_asistencia]);
                }


                alert('Escuela Modelo', 'La asistencia y faltas de la fecha seleccionada, se ha actualizado con éxito', 'success')->showConfirmButton();
                return back();
            }

            if($tipoDeAccion == "GUARDAR_ASISTENCIA"){
                for ($x = 0; $x < count($asistencia_inscrito_id); $x++) {

                    $asistenciaAlumno = array();
                    $asistenciaAlumno = new Primaria_asistencia();
                    $asistenciaAlumno['asistencia_inscrito_id'] = $asistencia_inscrito_id[$x];
                    $asistenciaAlumno['estado'] = $estado[$x];
                    $asistenciaAlumno['fecha_asistencia'] = $fecha_asistencia;
                    $asistenciaAlumno['user_docente_id'] = auth()->user()->id;


                    $asistenciaAlumno->save();

                    $primaria_faltas = DB::statement('call procPrimariaFaltasAlumnosInscritos(?, ?)',[$asistencia_inscrito_id[$x], $mes_asistencia]);

                }

                alert('Escuela Modelo', 'La asistencia y faltas de la fecha seleccionada, se ha actualizado con éxito', 'success')->showConfirmButton();
                return back();
            }



        }catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('primaria_inscritos/pase_lista/' . $grupo_id)->withInput();
        }
    }

    public function guardarPaseLista(Request $request)
    {

        $grupo_id = $request->grupo_id;
        $inscrito_idd = $request->inscrito_id;
        $existeInscrito = $inscrito_idd[0];


        //OBTENER GRUPO SELECCIONADO
        $grupo = Primaria_grupo::with('plan','primaria_materia','primaria_empleado')->find($grupo_id);

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $fechaHoy = $fechaActual->format('Y-m-d');

        $existeIns = DB::select("SELECT * FROM primaria_asistencia where asistencia_inscrito_id = '".$existeInscrito."' and fecha_asistencia = '".$fechaHoy."'");


        $inscrito_id = $request->inscrito_id;
        $asistencia = $request->estado;
        $id = $request->id;
        $c = count($asistencia);


        $date_asistencia = Carbon::createFromFormat('Y-m-d', $request->fecha_asistencia);
        $mes_asistencia = $date_asistencia->month;
        //dd($request->fecha_asistencia, $date_asistencia, $mes_asistencia);

        try{

            if(!empty($existeIns)){
                for ($i=0; $i< $c; $i++) {

                    DB::table('primaria_asistencia')
                        ->where('id',$id[$i])
                        ->update([
                            'id' => $id[$i],
                            'asistencia_inscrito_id' => $inscrito_id[$i],
                            'estado' => $asistencia[$i],
                            'fecha_asistencia' => $request->fecha_asistencia,
                            'user_docente_id' => auth()->user()->id
                    ]);

                    $primaria_faltas = DB::statement('call procPrimariaFaltasAlumnosInscritos(?, ?)',[$inscrito_id[$i], $mes_asistencia]);

                }
                alert('Escuela Modelo', 'La asistencia y faltas de la fecha seleccionada, se ha actualizado con éxito', 'success')->showConfirmButton();
                return back();

            }else{


                for ($i = 0; $i < $c; $i++) {

                    $asistenciaAlumno = array();
                    $asistenciaAlumno = new Primaria_asistencia;
                    $asistenciaAlumno['asistencia_inscrito_id'] = $inscrito_id[$i];
                    $asistenciaAlumno['estado'] = $asistencia[$i];
                    $asistenciaAlumno['fecha_asistencia'] = $request->fecha_asistencia;
                    $asistenciaAlumno['user_docente_id'] =  auth()->user()->id;

                    // $asistenciaAlumnos[$i] = $asistenciaAlumno;

                    $asistenciaAlumno->save();

                    $primaria_faltas = DB::statement('call procPrimariaFaltasAlumnosInscritos(?, ?)',[$inscrito_id[$i], $mes_asistencia]);

                }
            }



            alert('Escuela Modelo', 'La asistencia y faltas de la fecha seleccionada, se ha actualizado con éxito', 'success')->showConfirmButton();
            return redirect('primaria_inscritos/pase_lista/' . $grupo_id);


        }catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('primaria_inscritos/pase_lista/' . $grupo_id)->withInput();
        }


    }

}
