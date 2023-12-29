<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cgt;
use App\Models\Departamento;
use App\Models\Primaria\Primaria_grupo;
use App\Models\Primaria\Primaria_materia;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Validator;

class PrimariaCGTMateriasController extends Controller
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

        return view('primaria.CGTMaterias.create', [
            'ubicaciones' => $ubicaciones,
            'departamento' => $departamento
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    
    public function obtenerMaterias(Request $request, $periodo_id, $programa_id, $plan_id, $cgt_id)
    {
        if($request->ajax()){


            $cgtGrado = Cgt::select(
                'cgt.id',
                'cgt.cgtGradoSemestre',
                'cgt.cgtGrupo',
                'cgt.cgtTurno',
                'periodos.id as periodo_id',
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

            $materias = Primaria_materia::select(
            'primaria_materias.id',
            'primaria_materias.matClave',
            'primaria_materias.matNombre', 
            'primaria_materias.matSemestre', 
            'primaria_materias.matPrerequisitos',
            'planes.planClave',
            'programas.progNombre',
            'escuelas.escNombre',
            'departamentos.depNombre',
            'ubicacion.ubiNombre',
            'cgt.cgtGradoSemestre',
			'cgt.cgtGrupo',
            'cgt.cgtTurno',
			'cgt.plan_id as cgt_plan_id',
			'planes.id as plan_id')
            ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
            ->join('cgt', 'planes.id', '=', 'cgt.plan_id')
            ->join('periodos', 'cgt.periodo_id', '=', 'periodos.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id') 
            ->where('departamentos.depClave', 'PRI')   
            ->where('primaria_materias.matSemestre', $cgtGrado[0]->cgtGradoSemestre) 
            ->where('cgt.cgtGrupo', $cgtGrado[0]->cgtGrupo)
            ->where('cgt.cgtTurno', $cgtGrado[0]->cgtTurno)
            ->where('periodos.id', $periodo_id)
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)     
            ->where('cgt.id', $cgt_id)
            ->get();

            return response()->json($materias);
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
            
            $cgtGrupo = $request->input("cgtGrupo");
            $cgtTurno = $request->input("cgtTurno");

            $primaria_materia = $request->input("primaria_materia");

            $periodo_id = $request->input('periodo_id');
            $plan_id = $request->input('plan_id');
            $cgt_id = $request->input('cgt_id');
            $matSemestre = $request->input('matSemestre');

            

            if($primaria_materia != ""){
                $total_id_materias = count($primaria_materia);
                for ($x=0; $x < $total_id_materias; $x++) { 
                    for ($i=0; $i < count($primaria_materia) ; $i++) { 
                        $grupo = DB::statement('call procPrimariaAgregaGruposInscritos(?, ?, ?, ?, ?, ?, ?)',[$primaria_materia[$i], $plan_id, $periodo_id, $cgt_id, $matSemestre, $cgtGrupo, $cgtTurno]);
                    }  
                    return response()->json([
                        'res' => true,
                        'grupo' => $grupo
                        ]);
                   
                }  
            }else{
                return response()->json([
                    'res' => 'error',
                    ]);
            }

            
                    
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
