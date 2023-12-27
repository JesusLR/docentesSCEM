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

use App\Models\Grupo;
use App\Models\Curso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bachiller\Bachiller_asistencia;
use App\Models\Bachiller\Bachiller_grupos;

class BachillerInscritosYucatanController extends Controller
{

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
        $grupo_id = $request->grupo_id;

        $grupo = Bachiller_grupos::select(
            'bachiller_grupos.id',
            'bachiller_grupos.gpoGrado',
            'bachiller_grupos.gpoMatComplementaria',
            'gpoMatComplementaria',
            'bachiller_materias.id as bachiller_materia_id',
            'bachiller_materias.matClave',
            'bachiller_materias.matNombre',
            'bachiller_empleados.empNombre',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_empleados.empSexo'
        )
        ->join('bachiller_materias', 'bachiller_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->join('bachiller_empleados', 'bachiller_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
        ->where('bachiller_grupos.id', $grupo_id)
        ->first();

        return view('bachiller.inscritos_yucatan.show-list-inscritos', [
            "grupo" => $grupo
        ]);


    }

    /**
     * Show user list.
     *
     */
    public function list($grupo_id)
    {
        $cursos = Curso::select('cursos.id as curso_id',
            'alumnos.aluClave', 'alumnos.id as alumno_id', 'alumnos.aluMatricula', 'personas.perNombre', 'personas.id as personas_id',
            'personas.perApellido1', 'personas.perApellido2', 'periodos.id as periodo_id',
            'periodos.perNumero', 'periodos.perAnio', 'cursos.curEstado', 'cursos.curTipoIngreso',
            'cgt.cgtGradoSemestre', 'cgt.cgtGrupo', 'planes.id as plan_id', 'planes.planClave', 'programas.id as programa_id',
            'programas.progNombre', 'programas.progClave',
            'escuelas.escNombre', 'escuelas.escClave',
            'departamentos.depNombre', 'departamentos.depClave',
            'ubicacion.ubiNombre', 'ubicacion.ubiClave',
            'bachiller_grupos.gpoGrado',
            'bachiller_inscritos.id as inscrito_id','bachiller_inscritos.bachiller_grupo_id',
            'bachiller_grupos.gpoClave')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('bachiller_inscritos', 'cursos.id', '=', 'bachiller_inscritos.curso_id')
            ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('bachiller_inscritos.bachiller_grupo_id',$grupo_id)
            ->whereIn('depClave', ['BAC'])
            ->whereNull('bachiller_inscritos.deleted_at')
            ->whereNull('alumnos.deleted_at')
            ->whereNull('personas.deleted_at')
            ->whereNull('bachiller_grupos.deleted_at')
            ->whereNull('cursos.deleted_at')
            ->orderBy("personas.perApellido1", "asc")
            ->orderBy("personas.perApellido2", "asc")
            ->orderBy("personas.perNombre", "ASC");            //->latest('cgt.created_at');


        return Datatables::of($cursos)
            ->addColumn('action', function($cursos)
            {
                    $acciones = '';

                    /*
                    $acciones = '<div class="row">
                    <a href="calificaciones/primerreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="1er Trimestre" >
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
        $validar_si_esta_activo_cva = Auth::user()->campus_cva;


        $cursosPaseLista = Curso::select('cursos.id as curso_id',
        'alumnos.aluClave', 'alumnos.id as alumno_id', 'alumnos.aluMatricula', 'personas.perNombre', 'personas.id as personas_id',
        'personas.perApellido1', 'personas.perApellido2', 'periodos.id as periodo_id',
        'periodos.perNumero', 'periodos.perAnio', 'cursos.curEstado', 'cursos.curTipoIngreso',
        'cgt.cgtGradoSemestre', 'cgt.cgtGrupo', 'planes.id as plan_id', 'planes.planClave', 'programas.id as programa_id',
        'programas.progNombre', 'programas.progClave',
        'escuelas.escNombre', 'escuelas.escClave',
        'departamentos.depNombre', 'departamentos.depClave',
        'ubicacion.ubiNombre', 'ubicacion.ubiClave',
        'bachiller_grupos.gpoGrado',
        'bachiller_inscritos.id as inscrito_id','bachiller_inscritos.bachiller_grupo_id',
        'bachiller_grupos.gpoClave')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('bachiller_inscritos', 'cursos.id', '=', 'bachiller_inscritos.curso_id')
        ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('bachiller_inscritos.bachiller_grupo_id',$grupo_id)
        ->whereIn('depClave', ['BAC'])
        ->whereNull('alumnos.deleted_at')
        ->whereNull('personas.deleted_at')
        ->whereNull('cgt.deleted_at')
        ->whereNull('bachiller_inscritos.deleted_at')
        ->whereNull('bachiller_grupos.deleted_at')
        ->whereNull('periodos.deleted_at')
        ->whereNull('planes.deleted_at')
        ->whereNull('programas.deleted_at')
        ->whereNull('departamentos.deleted_at')
        ->whereNull('ubicacion.deleted_at')
        ->orderBy("personas.perApellido1", "asc")
        ->get();

        $paseAsistencia_collection = collect($cursosPaseLista);

        if($paseAsistencia_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay datos para mostrar.')->showConfirmButton();
            return back();
        }

        $grupo = Bachiller_grupos::with('plan.programa','periodo','bachiller_materia','bachiller_empleado')->find($grupo_id);



        return view('bachiller.inscritos_yucatan.paseDelista', [
            'paseAsistencia_collection' => $paseAsistencia_collection,
            'grupo' => $grupo,
            'validar_si_esta_activo_cva' => $validar_si_esta_activo_cva
        ]);
    }


    public function obtenerAlumnosPaseLista(Request $request, $grupo_id, $fecha)
    {

        if ($request->ajax()) {

            $bachiller_asistencia = Bachiller_asistencia::select(
                'bachiller_asistencia.id',
                'bachiller_asistencia.asistencia_inscrito_id',
                'bachiller_asistencia.fecha_asistencia',
                'bachiller_asistencia.estado',
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
            ->join('bachiller_inscritos', 'bachiller_asistencia.asistencia_inscrito_id', '=', 'bachiller_inscritos.id')
            ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
            ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')            
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->where('bachiller_inscritos.bachiller_grupo_id', $grupo_id)
            ->where('bachiller_asistencia.fecha_asistencia', '=', $fecha)
            ->whereNull('bachiller_inscritos.deleted_at')
            ->whereNull('alumnos.deleted_at')
            ->whereNull('personas.deleted_at')
            ->whereNull('bachiller_grupos.deleted_at')
            ->whereNull('cursos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
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
            'bachiller_grupos.gpoGrado',
            'bachiller_inscritos.id as inscrito_id','bachiller_inscritos.bachiller_grupo_id',
            'bachiller_grupos.gpoClave')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('bachiller_inscritos', 'cursos.id', '=', 'bachiller_inscritos.curso_id')
            ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('bachiller_inscritos.bachiller_grupo_id',$grupo_id)
            ->whereIn('depClave', ['BAC'])
            ->whereNull('alumnos.deleted_at')
            ->whereNull('personas.deleted_at')
            ->whereNull('cgt.deleted_at')
            ->whereNull('bachiller_inscritos.deleted_at')
            ->whereNull('bachiller_grupos.deleted_at')
            ->whereNull('periodos.deleted_at')
            ->whereNull('planes.deleted_at')
            ->whereNull('programas.deleted_at')
            ->whereNull('departamentos.deleted_at')
            ->whereNull('ubicacion.deleted_at')
            ->orderBy("personas.perApellido1", "ASC")
            ->orderBy('personas.perApellido2', 'ASC')
            ->get();

            return response()->json([
                'bachiller_asistencia' => $bachiller_asistencia,
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
                    DB::table('bachiller_asistencia')
                    ->where('id',$asistencia_id[$i])
                    ->where('fecha_asistencia',$fecha_asistencia)
                    ->update([
                        'estado' => $estado[$i],
                        'fecha_asistencia' => $fecha_asistencia,
                        'user_docente_id' => auth()->user()->id
                    ]);

                    $bachiller_faltas = DB::statement('call procBachillerFaltasAlumnosInscritosMontejo(?, ?)',[$asistencia_inscrito_id[$i], $mes_asistencia]);
                }                


                alert('Escuela Modelo', 'Se la asistencia del día se actualizo con éxito', 'success')->showConfirmButton();
                return back();
            }    

            if($tipoDeAccion == "GUARDAR_ASISTENCIA"){
                for ($x = 0; $x < count($asistencia_inscrito_id); $x++) {

                    $asistenciaAlumno = array();
                    $asistenciaAlumno = new Bachiller_asistencia();
                    $asistenciaAlumno['asistencia_inscrito_id'] = $asistencia_inscrito_id[$x];
                    $asistenciaAlumno['estado'] = $estado[$x];
                    $asistenciaAlumno['fecha_asistencia'] = $fecha_asistencia;
                    $asistenciaAlumno['user_docente_id'] = auth()->user()->id;

                    $asistenciaAlumno->save();

                    $bachiller_faltas = DB::statement('call procBachillerFaltasAlumnosInscritosMontejo(?, ?)',[$asistencia_inscrito_id[$x], $mes_asistencia]);                   

                }

                alert('Escuela Modelo', 'Se la asistencia del día se guardaron con éxito', 'success')->showConfirmButton();
                return back();
            }



        }catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('bachiller_inscritos/pase_lista/' . $grupo_id)->withInput();
        }
    }

    public function guardarPaseLista(Request $request)
    {

        $grupo_id = $request->grupo_id;
        $inscrito_idd = $request->inscrito_id;
        $existeInscrito = $inscrito_idd[0];


        //OBTENER GRUPO SELECCIONADO
        $grupo = Bachiller_grupos::with('plan','bachiller_materia','bachiller_empleado')->find($grupo_id);

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $fechaHoy = $fechaActual->format('Y-m-d');

        $existeIns = DB::select("SELECT * FROM bachiller_asistencia where asistencia_inscrito_id = '".$existeInscrito."' and fecha_asistencia = '".$fechaHoy."'");


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

                    DB::table('bachiller_asistencia')
                        ->where('id',$id[$i])
                        ->update([
                            'id' => $id[$i],
                            'asistencia_inscrito_id' => $inscrito_id[$i],
                            'estado' => $asistencia[$i],
                            'fecha_asistencia' => $request->fecha_asistencia,
                            'user_docente_id' =>  auth()->user()->id
                    ]);

                    $bachiller_faltas = DB::statement('call procSecundariaFaltasAlumnosInscritosMontejo(?, ?)',[$inscrito_id[$i], $mes_asistencia]);

                }
                alert('Escuela Modelo', 'Se la asistencia del día se actualizo con éxito', 'success')->showConfirmButton();
                return back();

            }else{


                for ($i = 0; $i < $c; $i++) {

                    $asistenciaAlumno = array();
                    $asistenciaAlumno = new Bachiller_asistencia();
                    $asistenciaAlumno['asistencia_inscrito_id'] = $inscrito_id[$i];
                    $asistenciaAlumno['estado'] = $asistencia[$i];
                    $asistenciaAlumno['fecha_asistencia'] = $request->fecha_asistencia;
                    $asistenciaAlumno['user_docente_id'] = auth()->user()->id;
                    // $asistenciaAlumnos[$i] = $asistenciaAlumno;

                    $asistenciaAlumno->save();

                    $bachiller_faltas = DB::statement('call procSecundariaFaltasAlumnosInscritosMontejo(?, ?)',[$inscrito_id[$i], $mes_asistencia]);

                }
            }



            alert('Escuela Modelo', 'Se la asistencia del día se agrego con éxito', 'success')->showConfirmButton();
            return redirect('bachiller_inscritos/pase_lista/' . $grupo_id);


        }catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('bachiller_inscritos/pase_lista/' . $grupo_id)->withInput();
        }


    }

}
