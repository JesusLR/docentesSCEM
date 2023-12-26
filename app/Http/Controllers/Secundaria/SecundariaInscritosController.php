<?php

namespace App\Http\Controllers\Secundaria;

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
use App\Http\Models\Secundaria\Secundaria_asistencia;
use App\Http\Models\Secundaria\Secundaria_grupos;

class SecundariaInscritosController extends Controller
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

        $grupo = Secundaria_grupos::select(
            'secundaria_grupos.id',
            'secundaria_grupos.gpoGrado',
            'secundaria_grupos.gpoClave',
            'secundaria_materias.id as secundaria_materia_id',
            'secundaria_materias.matClave',
            'secundaria_materias.matNombre',
            'secundaria_empleados.empNombre',
            'secundaria_empleados.empApellido1',
            'secundaria_empleados.empApellido2',
            'secundaria_empleados.empSexo'
        )
        ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
        ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
        ->where('secundaria_grupos.id', $grupo_id)
        ->first();

        return view('secundaria.inscritos.show-list-inscritos', [
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
            'secundaria_grupos.gpoGrado',
            'secundaria_inscritos.id as inscrito_id','secundaria_inscritos.grupo_id',
            'secundaria_grupos.gpoClave')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('secundaria_inscritos', 'cursos.id', '=', 'secundaria_inscritos.curso_id')
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('secundaria_inscritos.grupo_id',$grupo_id)
            ->where("cursos.curEstado", "!=", "B")
            ->whereNull('secundaria_inscritos.deleted_at')
            ->whereIn('depClave', ['SEC'])
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC');
            //->latest('cgt.created_at');


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
        'secundaria_grupos.gpoGrado',
        'secundaria_inscritos.id as inscrito_id','secundaria_inscritos.grupo_id',
        'secundaria_grupos.gpoClave')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('secundaria_inscritos', 'cursos.id', '=', 'secundaria_inscritos.curso_id')
        ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('secundaria_inscritos.grupo_id',$grupo_id)
        ->whereIn('depClave', ['SEC'])
        ->whereNull('secundaria_inscritos.deleted_at')
        ->orderBy('personas.perApellido1', 'ASC')
        ->orderBy('personas.perApellido2', 'ASC')
        ->orderBy('personas.perNombre', 'ASC')
        ->get();

        $paseAsistencia_collection = collect($cursosPaseLista);

        if($paseAsistencia_collection->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay datos para mostrar.')->showConfirmButton();
            return back();
        }

        $grupo = Secundaria_grupos::with('plan.programa','periodo','secundaria_materia','secundaria_empleado')->find($grupo_id);



        return view('secundaria.inscritos.paseDelista', [
            'paseAsistencia_collection' => $paseAsistencia_collection,
            'grupo' => $grupo,
            'validar_si_esta_activo_cva' => $validar_si_esta_activo_cva
        ]);
    }


    public function obtenerAlumnosPaseLista(Request $request, $grupo_id, $fecha)
    {

        if ($request->ajax()) {

            $secundaria_asistencia = Secundaria_asistencia::select(
                'secundaria_asistencia.id',
                'secundaria_asistencia.asistencia_inscrito_id',
                'secundaria_asistencia.fecha_asistencia',
                'secundaria_asistencia.estado',
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
            ->join('secundaria_inscritos', 'secundaria_asistencia.asistencia_inscrito_id', '=', 'secundaria_inscritos.id')
            ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->where('secundaria_inscritos.grupo_id', $grupo_id)
            ->where('secundaria_asistencia.fecha_asistencia', '=', $fecha)
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->whereNull('secundaria_inscritos.deleted_at')
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
            'secundaria_grupos.gpoGrado',
            'secundaria_inscritos.id as inscrito_id','secundaria_inscritos.grupo_id',
            'secundaria_grupos.gpoClave')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('secundaria_inscritos', 'cursos.id', '=', 'secundaria_inscritos.curso_id')
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('secundaria_inscritos.grupo_id',$grupo_id)
            ->whereIn('depClave', ['SEC'])
            ->whereNull('secundaria_inscritos.deleted_at')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

            return response()->json([
                'secundaria_asistencia' => $secundaria_asistencia,
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
                    DB::table('secundaria_asistencia')
                    ->where('id',$asistencia_id[$i])
                    ->where('fecha_asistencia',$fecha_asistencia)
                    ->update([
                        'estado' => $estado[$i],
                        'fecha_asistencia' => $fecha_asistencia,
                        'user_docente_id' => auth()->user()->id
                    ]);

                    $secundaria_faltas = DB::statement('call procSecundariaFaltasAlumnosInscritos(?, ?)',[$asistencia_inscrito_id[$i], $mes_asistencia]);
                }


                alert('Escuela Modelo', 'Se la asistencia del día se actualizo con éxito', 'success')->showConfirmButton();
                return back();
            }

            if($tipoDeAccion == "GUARDAR_ASISTENCIA"){
                for ($x = 0; $x < count($asistencia_inscrito_id); $x++) {

                    $asistenciaAlumno = array();
                    $asistenciaAlumno = new Secundaria_asistencia();
                    $asistenciaAlumno['asistencia_inscrito_id'] = $asistencia_inscrito_id[$x];
                    $asistenciaAlumno['estado'] = $estado[$x];
                    $asistenciaAlumno['fecha_asistencia'] = $fecha_asistencia;
                    $asistenciaAlumno['user_docente_id'] = auth()->user()->id;

                    $asistenciaAlumno->save();

                    $secundaria_faltas = DB::statement('call procSecundariaFaltasAlumnosInscritos(?, ?)',[$asistencia_inscrito_id[$x], $mes_asistencia]);

                }

                alert('Escuela Modelo', 'Se la asistencia del día se guardaron con éxito', 'success')->showConfirmButton();
                return back();
            }



        }catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('secundaria_inscritos/pase_lista/' . $grupo_id)->withInput();
        }
    }

    public function guardarPaseLista(Request $request)
    {

        $grupo_id = $request->grupo_id;
        $inscrito_idd = $request->inscrito_id;
        $existeInscrito = $inscrito_idd[0];


        //OBTENER GRUPO SELECCIONADO
        $grupo = Secundaria_grupos::with('plan','secundaria_materia','secundaria_empleado')->find($grupo_id);

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $fechaHoy = $fechaActual->format('Y-m-d');

        $existeIns = DB::select("SELECT * FROM secundaria_asistencia where asistencia_inscrito_id = '".$existeInscrito."' and fecha_asistencia = '".$fechaHoy."'");


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

                    DB::table('secundaria_asistencia')
                        ->where('id',$id[$i])
                        ->update([
                            'id' => $id[$i],
                            'asistencia_inscrito_id' => $inscrito_id[$i],
                            'estado' => $asistencia[$i],
                            'fecha_asistencia' => $request->fecha_asistencia
                    ]);

                    $secundaria_faltas = DB::statement('call procSecundariaFaltasAlumnosInscritos(?, ?)',[$inscrito_id[$i], $mes_asistencia]);

                }
                alert('Escuela Modelo', 'Se la asistencia del día se actualizo con éxito', 'success')->showConfirmButton();
                return back();

            }else{


                for ($i = 0; $i < $c; $i++) {

                    $asistenciaAlumno = array();
                    $asistenciaAlumno = new Secundaria_asistencia();
                    $asistenciaAlumno['asistencia_inscrito_id'] = $inscrito_id[$i];
                    $asistenciaAlumno['estado'] = $asistencia[$i];
                    $asistenciaAlumno['fecha_asistencia'] = $request->fecha_asistencia;
                    // $asistenciaAlumnos[$i] = $asistenciaAlumno;

                    $asistenciaAlumno->save();

                    $secundaria_faltas = DB::statement('call procSecundariaFaltasAlumnosInscritos(?, ?)',[$inscrito_id[$i], $mes_asistencia]);

                }
            }



            alert('Escuela Modelo', 'Se la asistencia del día se agrego con éxito', 'success')->showConfirmButton();
            return redirect('secundaria_inscritos/pase_lista/' . $grupo_id);


        }catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('secundaria_inscritos/pase_lista/' . $grupo_id)->withInput();
        }


    }

}
