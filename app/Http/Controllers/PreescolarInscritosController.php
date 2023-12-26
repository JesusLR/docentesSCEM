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

class PreescolarInscritosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permisos:preescolarinscritos',['except' => ['index','show','list','getGrupo','getGrupos']]);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $grupo_id = $request->grupo_id;

        return View('preescolar.show-list-inscritos', compact('grupo_id'));

    }

    /**
     * Show user list.
     *
     */
    public function list($grupo_id)
    {
        $cursos = Curso::select('cursos.id as curso_id',
            'alumnos.aluClave', 'alumnos.id as alumno_id', 'alumnos.aluMatricula', 'personas.perNombre',
            'personas.id as personas_id',
            'personas.perApellido1', 'personas.perApellido2', 'periodos.id as periodo_id',
            'periodos.perNumero', 'periodos.perAnio',
            'periodos.perAnioPago', 'cursos.curEstado', 'cursos.curTipoIngreso',
            'cgt.cgtGradoSemestre', 'cgt.cgtGrupo', 'planes.id as plan_id', 'planes.planClave', 'programas.id as programa_id',
            'programas.progNombre', 'programas.progClave',
            'escuelas.escNombre', 'escuelas.escClave',
            'departamentos.depNombre', 'departamentos.depClave',
            'ubicacion.ubiNombre', 'ubicacion.ubiClave',
            'preescolar_grupos.gpoGrado',
            'preescolar_inscritos.id as inscrito_id','preescolar_inscritos.preescolar_grupo_id',
            'preescolar_grupos.gpoClave')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('preescolar_inscritos', 'cursos.id', '=', 'preescolar_inscritos.curso_id')
            ->join('preescolar_grupos', 'preescolar_inscritos.preescolar_grupo_id', '=', 'preescolar_grupos.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('preescolar_inscritos.preescolar_grupo_id',$grupo_id)
            ->whereIn('depClave', ['PRE'])
            ->orderBy("personas.perApellido1", "asc");
            //->latest('cgt.created_at');


        return Datatables::of($cursos)
            ->addColumn('action', function($cursos)
            {
                    $acciones = '';

                    //PRIMER REPORTE TRIMESTRAL
                    /*
                    $acciones = '<div class="row">
                    <a href="preescolarcalificaciones/' . $cursos->inscrito_id . '/'.$cursos->preescolar_grupo_id.'/1" class="button button--icon js-button js-ripple-effect" title="1er Trimestre" >
                        <i class="material-icons">assignment_turned_in</i>
                    </a>
                    </div>';
                    */

                    //SEGUNDO REPORTE TRIMESTRAL
                   /*
                    $acciones = '<div class="row">
                    <a href="calificaciones/primerreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="1er Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    <a href="preescolarcalificaciones/' . $cursos->inscrito_id . '/'.$cursos->preescolar_grupo_id.'/2" class="button button--icon js-button js-ripple-effect" title="2do Trimestre" >
                        <i class="material-icons">assignment_turned_in</i>
                    </a>
                    </div>';
                   */

                    //TERCER REPORTE TRIMESTRAL
                    /*
                    $acciones = '<div class="row">
                    <a href="calificaciones/primerreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="1er Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    <a href="calificaciones/segundoreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="2do Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    <a href="preescolarcalificaciones/' . $cursos->inscrito_id . '/'.$cursos->preescolar_grupo_id.'/3" class="button button--icon js-button js-ripple-effect" title="3er Trimestre" >
                        <i class="material-icons">assignment_turned_in</i>
                    </a>
                    </div>';
                    */


                    //SOLO CONSULTAR PRIMER REPORTE
                    /*
                    $acciones = '<div class="row">
                    <a href="calificaciones/primerreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="1er Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    </div>';
                    */

                    //SOLO CONSULTAR PRIMER REPORTE Y SEGUNDO
                    /*
                    $acciones = '<div class="row">
                    <a href="calificaciones/primerreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="1er Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    <a href="calificaciones/segundoreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="2do Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    </div>';
                    */

                    //SOLO CONSULTAR PRIMER, SEGUNDO Y TERCER REPORTE

                    $acciones = '<div class="row">
                    <a href="calificaciones/primerreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="1er Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    <a href="calificaciones/segundoreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="2do Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    <a href="calificaciones/tercerreporte/'.$cursos->inscrito_id.'/'.$cursos->personas_id.'/'.$cursos->gpoGrado.'/'.$cursos->gpoClave.'/'.$cursos->perAnioPago.'" target="_blank" class="button button--icon js-button js-ripple-effect" title="3er Trimestre" >
                        <i class="material-icons">picture_as_pdf</i>
                    </a>
                    </div>';



                return $acciones;
            })
        ->make(true);
    }

}
