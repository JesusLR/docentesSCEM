<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cgt;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Primaria\Primaria_inscrito;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrimariaAsignarCGTController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $ubicaciones = Ubicacion::get();
        $departamento = Departamento::select()->findOrFail(13);

        return view('primaria.asignar_cgt.create', [
            'ubicaciones' => $ubicaciones,
            'departamento' => $departamento
        ]);
    }

    public function getGradoGrupo(Request $request, $periodo_id, $programa_id, $plan_id, $cgt_id)
    {
        if($request->ajax()){


            $gadroGrupo = Cgt::select(
                'cgt.id', 
                'cgt.cgtGradoSemestre', 
                'cgt.cgtGrupo', 'periodos.id as periodo_id', 
                'programas.id as programa_id', 
                'planes.id as plan_id')       
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('periodos', 'cgt.periodo_id', '=', 'periodos.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id') 
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')            
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')            
            ->where('departamentos.depClave', 'PRI')
            ->where('periodos.id', $periodo_id)
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('cgt.id', $cgt_id)
            ->get();

            $gruposClave = Cgt::select(
                'cgt.id', 
                'cgt.cgtGradoSemestre', 
                'cgt.cgtGrupo', 'periodos.id as periodo_id', 
                'programas.id as programa_id', 
                'planes.id as plan_id')       
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('periodos', 'cgt.periodo_id', '=', 'periodos.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id') 
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')            
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id') 
            ->where('departamentos.depClave', 'PRI')              
            ->where('periodos.id', $periodo_id)
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('cgt.cgtGradoSemestre', $gadroGrupo[0]->cgtGradoSemestre)
            ->get();

            return response()->json($gruposClave);
        }
    }

    public function getAlumnosGrado(Request $request, $periodo_id, $programa_id, $plan_id, $cgt_id)
    {
        if($request->ajax()){


            $gadroGrupo = Curso::select(
                'cursos.id', 
                'cgt.cgtGradoSemestre', 
                'cgt.cgtGrupo', 
                'periodos.id as periodo_id', 
                'planes.id as plan_id',
                'programas.id as programa_id',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.id as persona_id',
                'personas.perApellido1 as apellido_paterno',
                'personas.perApellido2 as apellido_materno',
                'personas.perNombre as nombres')       
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')    
            ->join('programas', 'planes.programa_id', '=', 'programas.id')    
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')            
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')            
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')  
            ->where('departamentos.depClave', 'PRI')     
            ->where('periodos.id', $periodo_id)
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('cgt.id', $cgt_id)
            ->get();

            $gruposClave = Curso::select(
                'cursos.id', 
                'cgt.cgtGradoSemestre', 
                'cgt.cgtGrupo', 
                'periodos.id as periodo_id', 
                'planes.id as plan_id',
                'programas.id as programa_id',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.id as persona_id',
                'personas.perApellido1 as apellido_paterno',
                'personas.perApellido2 as apellido_materno',
                'personas.perNombre as nombres')       
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')    
            ->join('programas', 'planes.programa_id', '=', 'programas.id')    
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')            
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')  
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')            
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')  
            ->where('departamentos.depClave', 'PRI')          
            ->where('periodos.id', $periodo_id)
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('cgt.cgtGradoSemestre', $gadroGrupo[0]->cgtGradoSemestre)
            ->where('cgt.cgtGrupo', $gadroGrupo[0]->cgtGrupo)
            ->where('cursos.curEstado', '!=', 'B')
            ->orderBy('personas.perApellido1', 'asc')
            ->get();
            

            return response()->json($gruposClave);
        }
    }

    public function getPrimariaInscritoCursos(Request $request, $periodo_id, $programa_id, $plan_id, $cgt_id)
    {
        if($request->ajax()){


            $gadroGrupo = Curso::select(
                'cursos.id', 
                'cgt.cgtGradoSemestre', 
                'cgt.cgtGrupo', 
                'periodos.id as periodo_id', 
                'planes.id as plan_id',
                'programas.id as programa_id',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.id as persona_id',
                'personas.perApellido1 as apellido_paterno',
                'personas.perApellido2 as apellido_materno',
                'personas.perNombre as nombres')       
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')    
            ->join('programas', 'planes.programa_id', '=', 'programas.id')    
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')            
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')  
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')            
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')  
            ->where('departamentos.depClave', 'PRI')          
            ->where('periodos.id', $periodo_id)
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('cgt.id', $cgt_id)
            ->get();

        
            // llama al procedure 
            $resultado_array =  DB::select("call procPrimariaInscritoHayDatos(".$periodo_id.", ".$programa_id.", '".$plan_id."', ".$cgt_id.",".$gadroGrupo[0]->cgtGradoSemestre.",'".$gadroGrupo[0]->cgtGrupo."')");

            $resultado_collection = collect($resultado_array);
            

            return response()->json($resultado_collection);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request->cgt_id;
        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $fecha = $fechaActual->format('Y-m-d');
        $hora = $fechaActual->format('H:i:s');

        $fecha_hora = $fecha . ' ' . $hora;

        $curso_id = $request->curso_id;
        $grupo_perteneciente = $request->grupo_perteneciente;
        $collectionRespuesta = collect($grupo_perteneciente);
        $cgt_id = $collectionRespuesta->values();

        if (!empty($cgt_id)) {
            $contar = count($cgt_id);
            for ($i = 0; $i < $contar; $i++) {

                // $resultado_array =  DB::select("call procPrimariaInscritoHayDatos(".intval($curso_id).")");

                // $resultado_collection = collect($resultado_array);
                

                DB::table('cursos')
                ->where('id', $curso_id[$i])
                    ->update([
                        'cgt_id' => $cgt_id[$i],
                        'updated_at' => $fecha_hora
                    ]);
            }

            alert('Escuela Modelo', 'Se asigno CGT con éxito', 'success')->showConfirmButton();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
