<?php

namespace App\Http\Controllers\Primaria;

use App\clases\departamentos\MetodosDepartamentos;
use Validator;
use Auth;

use App\Http\Helpers\Utils;
use App\Models\Cgt;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Inscrito;
use App\Models\InscritosRechazados;
use App\Models\Periodo;
use App\Models\Plan;
use App\Models\Preescolar\Preescolar_grupo;
use App\Models\Preescolar\Preescolar_inscrito;
use App\Models\Prerequisito;
use App\Models\Programa;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Escuela;
use App\Models\Primaria\Primaria_grupo;
use App\Models\Primaria\Primaria_inscrito;

class PrimariaAsignarGrupoController extends Controller
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
        return view('primaria.asignar_grupo.show-index');
    }

    public function list()
    {
        $inscritos = Primaria_inscrito::select(
            'primaria_inscritos.id as inscrito_id',
            'alumnos.aluClave',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'primaria_inscritos.curso_id',
            'primaria_inscritos.primaria_grupo_id',
            'primaria_grupos.gpoClave',
            'primaria_grupos.gpoGrado',
            'primaria_grupos.gpoTurno',
            'primaria_materias.matNombre',
            'planes.planClave',
            'periodos.perNumero',
            'periodos.perAnio',
            'programas.progNombre',
            'escuelas.escNombre',
            'departamentos.depNombre',
            'departamentos.depClave',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre'
        )
        ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
        ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
        ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
        ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->latest('primaria_inscritos.created_at');


        $permisoC = (User::permiso("inscrito") == "C" || User::permiso("inscrito") == "A");
     


        return DataTables::of($inscritos)
            ->filterColumn('nombreCompleto',function($query,$keyword) {
                return $query->whereHas('curso.alumno.persona', function($query) use($keyword) {
                    $query->whereRaw("CONCAT(perNombre, ' ', perApellido1, ' ', perApellido2) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('nombreCompleto',function($query) {
                return $query->perNombre." ".$query->perApellido1." ".$query->perApellido2;
            })
            ->addColumn('action',function($query) use ($permisoC) {
                $btnCambiarGrupo = "";

                if ($permisoC) {
                    $btnCambiarGrupo = '<a href="primaria_asignar_grupo/cambiar_grupo/' . $query->inscrito_id . '" class="button button--icon js-button js-ripple-effect" title="Cambiar grupo">
                        <i class="material-icons">sync_alt</i>
                    </a>';
                }

                return '<a href="primaria_asignar_grupo/' . $query->inscrito_id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                    <i class="material-icons">visibility</i>
                </a>
                <a href="primaria_asignar_grupo/' . $query->inscrito_id . '/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
                </a>
                <form id="delete_' . $query->inscrito_id . '" action="primaria_asignar_grupo/' . $query->inscrito_id . '" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <a href="#" data-id="' . $query->inscrito_id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                        <i class="material-icons">delete</i>
                    </a>
                </form>'
                . $btnCambiarGrupo;
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
        $ubicaciones = Ubicacion::get();
        $departamento = Departamento::select()->findOrFail(13);
        return view('primaria.asignar_grupo.create',[
            'ubicaciones' => $ubicaciones,
            'departamento' => $departamento
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $grupo = Primaria_grupo::where("id", "=", $request->grupo_id)->first();

        $validator = Validator::make(
            $request->all(),
            [
                'curso_id' => 'required|unique:primaria_inscritos,curso_id,NULL,id,primaria_grupo_id,'.$request->input('grupo_id').',deleted_at,NULL',
                'grupo_id' => 'required'
            ],
            [
                'curso_id.unique' => "El inscrito ya existe",
            ]
        );

        if ($validator->fails()) {
            return redirect ()->route('primaria_asignar_grupo.create')->withErrors($validator)->withInput();
        }

        try {

            $programa_id = $request->input('programa_id');

           //FILTRO EXISTE INSCRITO EN CURSO
            $primaria_grupo = Primaria_grupo::where("id", "=", $request->grupo_id)->first();
            // $existeInscritoEnCurso = Preescolar_inscrito::with("preescolar_grupo")
            // ->where("curso_id", "=", $request->curso_id)
            //     ->whereHas('preescolar_grupo', function ($query) use ($preescolar_grupo) {
            //         $query->where('preescolar_materia_id', $preescolar_grupo->preescolar_materia_id);
            //         $query->where('periodo_id', $preescolar_grupo->periodo_id);
            //     })
            //     ->first();

                // if ($existeInscritoEnCurso->IsNotEmpty())
                // {
                    
                //     alert()->error('El alumno ya esta inscrito a ese grupo. Favor de verificar.' )->showConfirmButton();
        
                //     return redirect()->route('inscritos.create')->withInput();
                // }

            //FILTRO TIENE DERECHO A INSCRIBIRSE A GRUPOS
            $ubicacion = Ubicacion::where("id", "=", $request->ubicacion_id)->first();
            $departamento = Departamento::where("id", "=", $request->departamento_id)->first();
            $programa = Programa::where("id", "=", $request->programa_id)->first();
            $cursos = [$request->curso_id];
            $grupo  = $request->grupo_id;

            return $this->inscribirAlumnoPrimaria($request->curso_id, $request->grupo_id);

         
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();

            return redirect()->route('primaria_asignar_grupo.create')->withInput();
        }

        
    }

    private function inscribirAlumnoPrimaria($curso_id, $grupo_id) {
        $primaria_inscrito = Primaria_inscrito::create([
            'curso_id'      => $curso_id,
            'primaria_grupo_id'      => $grupo_id
        ]);


        if ($primaria_inscrito) {
            $grupo = Primaria_grupo::find($grupo_id);
            $grupo->inscritos_gpo = $grupo->inscritos_gpo + 1;
            $grupo->save();

            /*
            Preescolar_calificacion::create([
                'preescolar_inscrito_id'   => $preescolar_inscrito->id
            ]);
            */
        }

        alert('Escuela Modelo', 'Se ha inscrito con éxito', 'success')->showConfirmButton();
        return back();
    }


    // obtener los grupos de primaria 
    public function getGrupos(Request $request, $curso_id)
    {
        if ($request->ajax()) {
            //CURSO SELECCIONADO
            $curso = Curso::find($curso_id);
            $cgt = Cgt::find($curso->cgt_id);

            //VALIDA EL SEMESTRE DEL CGT Y EL GRUPO
            $grupos = Primaria_grupo::select("primaria_grupos.id as id", "primaria_grupos.gpoGrado", "primaria_grupos.gpoClave", "primaria_grupos.gpoTurno",
                "primaria_materias.matClave", "primaria_materias.matNombre", 
                "primaria_empleados.id as empleadoId",
                "primaria_empleados.empNombre", "primaria_empleados.empApellido1", "primaria_empleados.empApellido2")
                ->where('primaria_grupos.plan_id', $cgt->plan_id)
                ->where('primaria_grupos.periodo_id', $cgt->periodo_id)
                // ->where('primaria_grupos.gpoExtraCurr', "=", "g")
                ->join("primaria_materias", "primaria_materias.id", "=", "primaria_grupos.primaria_materia_id")
                ->join("primaria_empleados", "primaria_empleados.id", "=", "primaria_grupos.empleado_id_docente")
            ->get();

            return response()->json($grupos);
        }
    }

    // public function getGrupos(Request $request, $curso_id)
    // {
    //     if ($request->ajax()) {
    //         //CURSO SELECCIONADO
    //         $curso = Curso::with('cgt.periodo.departamento.ubicacion')->find($curso_id);
    //         $cgt = $curso->cgt;
    //         $ubicacion = $cgt->periodo->departamento->ubicacion;

    //         //VALIDA EL SEMESTRE DEL CGT Y EL GRUPO
    //         $grupos = Primaria_grupo::select("primaria_grupos.id as id", "primaria_grupos.gpoGrado", "primaria_grupos.gpoClave", "primaria_grupos.gpoTurno",
    //             "primaria_materias.matClave", "primaria_materias.matNombre", "primaria_empleados.id as empleadoId",
    //             "primaria_empleados.empNombre as perNombre", "primaria_empleados.empApellido1 as perApellido1", "primaria_empleados.empApellido2 as perApellido2",'optativas.optNombre')

    //             ->where('primaria_grupos.plan_id', $cgt->plan_id)
    //             ->where('primaria_grupos.periodo_id', $cgt->periodo_id)
    //             ->where('primaria_grupos.gpoExtraCurr', "=", "N")
                
    //             ->when($ubicacion->ubiClave != "CCH", static function($query) use ($cgt) {
    //                 return $query->where('gpoGrado', $cgt->cgtGradoSemestre);
    //             })


    //             ->leftJoin("optativas", "optativas.id", "=", "grupos.optativa_id")
    //             ->join("primaria_materias", "primaria_materias.id", "=", "primaria_grupos.materia_id")

    //             ->join("primaria_empleados", "primaria_empleados.id", "=", "primaria_grupos.empleado_id_docente")
                
    //         ->get();

    //         return response()->json($grupos);
    //     }
    // }



    public function getDepartamentos(Request $request, $id)
    {
        if($request->ajax()){
            // $departamentos = Departamento::with('ubicacion')->where('ubicacion_id','=',$id)
            // ->whereIn('depClave', ['SUP', 'POS'])->get();

            if (Auth::user()->empleado->escuela->departamento->depClave == "PRI") {
                $departamentos = MetodosDepartamentos::buscarSoloAcademicos($id, ['PRI']);
            }
            //$departamentos = MetodosDepartamentos::buscarSoloAcademicos($id, ['POS', 'SUP', 'PRE']);
            return response()->json($departamentos);
        }
    }

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

                    
                    if ($request->otro == "diplomados") {
                        $query->orWhere('escNombre', "like", "DIPLOMADOS%");
                    }
                })
            ->get();
           
            return response()->json($escuelas);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inscrito = Primaria_inscrito::with('curso.alumno.persona','primaria_grupo.primaria_materia')->findOrFail($id);
        return view('primaria.asignar_grupo.show',compact('inscrito'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return $inscrito = Preescolar_inscrito::with('curso.alumno.persona','grupo.materia')->findOrFail($id);
        $inscrito = Primaria_inscrito::select()->findOrFail($id);
        $periodos = Periodo::where('departamento_id',$inscrito->curso->cgt->plan->programa->escuela->departamento_id)->get();
        $programas = Programa::with('empleado','escuela')->where('escuela_id',$inscrito->curso->cgt->plan->programa->escuela_id)->get();
        $planes = Plan::with('programa')->where('programa_id',$inscrito->curso->cgt->plan->programa->id)->get();
        $cgts = Cgt::where([['plan_id', $inscrito->curso->cgt->plan_id],['periodo_id', $inscrito->curso->cgt->periodo_id]])->get();
        $cursos = Curso::with('alumno.persona')->where('cgt_id', '=', $inscrito->curso->cgt->id)->get();
        $cgt = $inscrito->curso->cgt;
        $grupos = Primaria_grupo::with('primaria_materia', 'primaria_empleado', 'plan.programa', 'periodo')
            ->where('gpoGrado', $cgt->cgtGradoSemestre)->where('plan_id',$cgt->plan_id)
            ->where('periodo_id',$cgt->periodo_id)->get();
        // //VALIDA PERMISOS EN EL PROGRAMA

            return view('primaria.asignar_grupo.edit',compact('inscrito','periodos','programas','planes','cgts','cursos','grupos', 'cgt'));
        

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
        $validator = Validator::make(
            $request->all(),
            [
                'curso_id' => 'required',
                'grupo_id' => 'required'
            ],
            [
                'curso_id.unique' => "El inscrito ya existe",
            ]
        );
        if ($validator->fails()) {
            return redirect('primaria_asignar_grupo/' . $id . '/edit')->withErrors($validator)->withInput();
        } else {
            try {
                $inscrito = Primaria_inscrito::findOrFail($id);
                $inscrito->curso_id = $request->input('curso_id');
                $inscrito->primaria_grupo_id = $request->input('grupo_id');
                $inscrito->save();


                alert('Escuela Modelo', 'El inscrito se ha actualizado con éxito', 'success')->showConfirmButton();
                return redirect()->route('primaria_asignar_grupo.index');
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
                return redirect('primaria_asignar_grupo/' . $id . '/edit')->withInput();
            }
        }
    }

    public function cambiarGrupo(Request $request)
    {
        $inscritoId = $request->inscritoId;
        $inscrito = Primaria_inscrito::with('primaria_grupo.plan.programa', 'curso.alumno.persona', 'curso.periodo')
        ->where("primaria_inscritos.id", "=", $inscritoId)->first();

        // $inscrito = Primaria_inscrito::select('primaria_inscritos.id', 'primaria_grupos.gpoGrado', 'primaria_grupos.gpoClave', 'periodos.perAnioPago',
        // 'personas.perNombre', 'personas.perApellido1', 'personas.perApellido2', 'programas.progNombre')
        // ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
        // ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
        // ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        // ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        // ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        // ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
        // ->join('programas', 'planes.programa_id', '=', 'programas.id')
        // ->where("primaria_inscritos.id", "=", $inscritoId)->first();


        $grupos = Primaria_grupo::with("primaria_materia")
            ->where('primaria_materia_id', "=", $inscrito->primaria_grupo->primaria_materia_id)
            ->where("periodo_id", "=", $inscrito->primaria_grupo->periodo_id)
        ->get();



        return view('primaria.asignar_grupo.cambiar-grupo', [
            "inscrito" => $inscrito,
            "grupos"   => $grupos
        ]);
    }

    public function postCambiarGrupo (Request $request)
    {
        //grupo nuevo
        $grupoId = $request->gpoId;
        $inscritoId = $request->inscritoId;

        $inscritoActual = Primaria_inscrito::where("id", "=", $inscritoId)->first();
        $grupoAnteriorId = $inscritoActual->primaria_grupo->id;


        $inscrito = Primaria_inscrito::findOrFail($inscritoId);
        $inscrito->primaria_grupo_id = $request->gpoId;
        
        if ($inscrito->save()) {
            $grupoAnterior = Primaria_grupo::findOrFail($grupoAnteriorId);
            $grupoAnterior->inscritos_gpo = $grupoAnterior->inscritos_gpo -1;
            $grupoAnterior->save();


            $grupoNuevo = Primaria_grupo::findOrFail($request->gpoId);
            $grupoNuevo->inscritos_gpo = $grupoNuevo->inscritos_gpo +1;
            $grupoNuevo->save();
        }

        alert('Escuela Modelo', 'El inscrito materia se ha actualizado con éxito','success')->showConfirmButton();
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $primaria_inscrito = Primaria_inscrito::findOrFail($id);

        $primaria_grupo = Primaria_grupo::find($primaria_inscrito->primaria_grupo_id);
        if ($primaria_grupo->inscritos_gpo > 0) {
            $primaria_grupo->inscritos_gpo = $primaria_grupo->inscritos_gpo - 1;
            $primaria_grupo->save();
        }

        try {
            if ($primaria_inscrito->delete()) {
                alert('Escuela Modelo', 'El inscrito materia se ha eliminado con éxito','success')->showConfirmButton();
            } else {
                alert()->error('Error...', 'No se puedo eliminar el inscrito materia')->showConfirmButton();
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode,$errorMessage)->showConfirmButton();
        }
        return redirect()->route('primaria_asignar_grupo.index');
    }
}