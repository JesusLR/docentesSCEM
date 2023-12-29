<?php

namespace App\Http\Controllers\Secundaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cgt;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Periodo;
use App\Models\Programa;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;

class SecundariaCambioDeProgramaController extends Controller
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
        $ubicaciones = Ubicacion::get();
        $departamento = Departamento::select()->findOrFail(13);

        $usuario_id = auth()->user()->id;        


        return view('secundaria.cambio_de_programa.create', [
            'ubicaciones' => $ubicaciones,
            'departamento' => $departamento,
            'usuario_id' => $usuario_id
        ]);
    }


    public function getAlumnoPrograma(Request $request, $periodo_id, $programa_id, $plan_id, $cgt_id, $aluClave)
    {
        if($request->ajax()){

            $alumnoPrograma = Curso::select(
                'cursos.id',
                'cursos.curEstado',
                'cgt.id as cgt_id',
                'cgt.cgtGradoSemestre',
                'cgt.cgtGrupo',
                'cgt.cgtTurno',
                'cursos.periodo_id',
                'periodos.perAnioPago',
                'cursos.alumno_id',
                'alumnos.aluClave',
                'alumnos.aluEstado',
                'alumnos.persona_id',
                'personas.perApellido1', 
                'personas.perApellido2',
                'personas.perNombre',
                'planes.id as plan_id',
                'programas.id as programa_id',
                'programas.progClave',
                'programas.progNombre',
                'secundaria_materias.matNombre'
            )
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('secundaria_inscritos', 'cursos.id', '=', 'secundaria_inscritos.curso_id')
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
            ->where('periodos.id', '=', $periodo_id)
            ->where('programas.id', '=', $programa_id)
            ->where('planes.id', '=', $plan_id)
            ->where('cgt.id', '=', $cgt_id)
            ->where('alumnos.aluClave', '=', $aluClave)
            ->where('cursos.curEstado', '!=', 'B')
            ->get();

            return response()->json($alumnoPrograma);
        }
    }

    public function getANombrePrograma(Request $request,$programa_id2)
    {
        if($request->ajax()){

            $programa = Programa::select(
                'programas.id',               
                'programas.progClave',
                'programas.progNombre'
            )            
            ->where('programas.id', '=', $programa_id2)
            ->get();

            return response()->json($programa);
        }
    }
    
 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->ajax()) {

            $curso_id = $request->input("curso_id");
            $plan_id = $request->input("plan_id");
            $cgt_id = $request->input("cgt_id");
            $periodo_id = $request->input("periodo_id");
            $cgt_destino = $request->input("cgt_id2");
            $plan_destino = $request->input("plan_id2");
            $usuario_at =  $request->input("usuario_at");
            $departamento_id =  $request->input("departamento_id");
            $programa_id = $request->input("programa_id");
            $programa_id2 = $request->input("programa_id2");

            // cgt actual (obtener el grado)
            $cgt = Cgt::select('cgtGradoSemestre')->where('id', $cgt_id)->first();

            // cgt destino (obtener el grado)
            $cgt_a_mover = Cgt::select('cgtGradoSemestre')->where('id', $cgt_destino)->first();

            if($programa_id == $programa_id2){
                return response()->json([
                    'res' => "programaIgual",
                ]);
            }else{
                

                if($cgt->cgtGradoSemestre == $cgt_a_mover->cgtGradoSemestre){

                    $departamentoActual = Departamento::select('perActual')->where('id', $departamento_id)->first();
                    $departamento = Periodo::select('departamentos.perActual')
                    ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                    ->where('departamentos.id', $departamento_id)
                    ->where('periodos.id', $periodo_id)
                    ->first();
    
                    // validamos si el el perActual de la tabla periodos es igual al departamento 
                    if($departamentoActual->perActual == $departamento->perActual){
    
                        $resultado_array =  DB::select("call procSecundariaAlumnoCambioPrograma(".$curso_id .",".$periodo_id.",". $plan_destino.", ".$cgt_destino.", ".$usuario_at.")");
                        $grupo_collection = collect($resultado_array);
            
                        $nuevoCurso_id = $grupo_collection[0]->id;
            
                          
            
                        // obtenemos los id de los grupos 
                        $secundaria_grupo_ids =  DB::select("SELECT id from secundaria_grupos where 
                        periodo_id = '.$periodo_id.'
                        and plan_id = '.$plan_destino.'
                        and gpoGrado = '.$cgt->cgtGradoSemestre.'
                        and secundaria_materia_id in
                        (SELECT id from secundaria_materias where plan_id = '.$plan_destino.' and matClave in ( 
                        SELECT matClave from secundaria_materias where id in (SELECT secundaria_materia_id from secundaria_grupos p where id 
                        in (SELECT grupo_id from secundaria_inscritos where curso_id = '.$nuevoCurso_id.'))))");
            
                        for ($i=0; $i < count($secundaria_grupo_ids); $i++) { 
                            DB::table('secundaria_inscritos')
                            ->where('curso_id', $nuevoCurso_id)
                            ->where('grupo_id', $secundaria_grupo_ids[$i]->id)
                            ->update(['grupo_id' => $secundaria_grupo_ids[$i]->id]);
                        }
            
                        return response()->json([
                            'res' => true,
                        ]);
                    }else{
                        return response()->json([
                            'res' => "perActualDiferente",
                        ]);
                    }
    
                }else{
                    return response()->json([
                        'res' => "GradoDiferente",
                    ]);
                }
            }


        }
    }


}
