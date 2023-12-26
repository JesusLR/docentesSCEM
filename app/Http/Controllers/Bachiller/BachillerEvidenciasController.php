<?php

namespace App\Http\Controllers\Bachiller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Bachiller\Bachiller_evidencias;
use App\Http\Models\Bachiller\Bachiller_grupos;
use App\Http\Models\Bachiller\Bachiller_inscritos_evidencias;
use App\Http\Models\Bachiller\Bachiller_materias;
use App\Http\Models\Ubicacion;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class BachillerEvidenciasController extends Controller
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
    public function index($bachiller_grupo_id)
    {
        $bachiller_grupo = Bachiller_grupos::select('bachiller_grupos.*',
        'bachiller_materias.matClave',
        'bachiller_materias.matNombre',
        'periodos.perNumero',
        'periodos.perAnio')
        ->join('bachiller_materias', 'bachiller_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
        ->where('bachiller_grupos.id', $bachiller_grupo_id)
        ->first();       


        return view('bachiller.evidencias.show-list',[
            "bachiller_grupo" => $bachiller_grupo
        ]);

      
    }

    public function list($periodo_id, $bachiller_materia_id, $bachiller_materia_acd_id)
    {
        if($bachiller_materia_acd_id != "NULL"){
            $bachiller_evidencias = Bachiller_evidencias::select(
                'bachiller_evidencias.id',
                'bachiller_evidencias.periodo_id',
                'bachiller_evidencias.bachiller_materia_id',
                'bachiller_evidencias.eviNumero',
                'bachiller_evidencias.eviDescripcion',
                'bachiller_evidencias.eviFechaEntrega',
                'bachiller_evidencias.eviPuntos',
                'bachiller_evidencias.eviTipo',
                'bachiller_evidencias.eviFaltas',
                'periodos.perNumero',
                'periodos.perAnio',
                'periodos.perAnioPago',
                'bachiller_materias.matNombre',
                'bachiller_materias.matClave',
                'bachiller_materias.matSemestre',
                'planes.id as plan_id',
                'planes.planClave',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'departamentos.depClave',
                'departamentos.depNombre',
                'escuelas.escClave',
                'escuelas.escNombre',
                'programas.progClave',
                'bachiller_materias_acd.gpoMatComplementaria'
            )
            ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('periodos', 'bachiller_evidencias.periodo_id', '=', 'periodos.id')
            ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->leftJoin('bachiller_materias_acd', 'bachiller_evidencias.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
            ->where('periodos.id', '=', $periodo_id)
            ->where('bachiller_evidencias.bachiller_materia_id', '=', $bachiller_materia_id)
            ->where('bachiller_materias_acd.id', '=', $bachiller_materia_acd_id)       
            ->whereNull('periodos.deleted_at')
            ->whereNull('bachiller_materias.deleted_at')
            ->whereNull('departamentos.deleted_at')
            ->whereNull('ubicacion.deleted_at')
            ->whereNull('bachiller_evidencias.deleted_at') 
            // ->orderBy('bachiller_evidencias.id', 'DESC')
            ->orderBy('bachiller_evidencias.eviNumero', 'ASC'); 
        }else{
            $bachiller_evidencias = Bachiller_evidencias::select(
                'bachiller_evidencias.id',
                'bachiller_evidencias.periodo_id',
                'bachiller_evidencias.bachiller_materia_id',
                'bachiller_evidencias.eviNumero',
                'bachiller_evidencias.eviDescripcion',
                'bachiller_evidencias.eviFechaEntrega',
                'bachiller_evidencias.eviPuntos',
                'bachiller_evidencias.eviTipo',
                'bachiller_evidencias.eviFaltas',
                'periodos.perNumero',
                'periodos.perAnio',
                'periodos.perAnioPago',
                'bachiller_materias.matNombre',
                'bachiller_materias.matClave',
                'bachiller_materias.matSemestre',
                'planes.id as plan_id',
                'planes.planClave',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'departamentos.depClave',
                'departamentos.depNombre',
                'escuelas.escClave',
                'escuelas.escNombre',
                'programas.progClave',
                'bachiller_materias_acd.gpoMatComplementaria'
            )
            ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('periodos', 'bachiller_evidencias.periodo_id', '=', 'periodos.id')
            ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->leftJoin('bachiller_materias_acd', 'bachiller_evidencias.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
            ->where('periodos.id', '=', $periodo_id)
            ->where('bachiller_evidencias.bachiller_materia_id', '=', $bachiller_materia_id)
            ->whereNull('periodos.deleted_at')
            ->whereNull('bachiller_materias.deleted_at')
            ->whereNull('departamentos.deleted_at')
            ->whereNull('ubicacion.deleted_at')
            ->whereNull('bachiller_evidencias.deleted_at') 
            // ->orderBy('bachiller_evidencias.id', 'DESC')
            ->orderBy('bachiller_evidencias.eviNumero', 'ASC'); 
        }
     


        
    

        return DataTables::of($bachiller_evidencias)

            ->filterColumn('materia_acd', function($query, $keyword) {
                $query->whereRaw("CONCAT(gpoMatComplementaria) like ?", ["%{$keyword}%"]);
            
            })
            ->addColumn('materia_acd',function($query) {
                return $query->gpoMatComplementaria;
            })
        
            ->filterColumn('numero_periodo', function($query, $keyword) {
              $query->whereRaw("CONCAT(perNumero) like ?", ["%{$keyword}%"]);
             
            })
            ->addColumn('numero_periodo',function($query) {
                return $query->perNumero;
            })

            ->filterColumn('anio_periodo', function($query, $keyword) {
                $query->whereRaw("CONCAT(perAnio) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('anio_periodo',function($query) {
                  return $query->perAnio;
            })

            ->filterColumn('clave_materia', function($query, $keyword) {
                $query->whereRaw("CONCAT(matClave) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('clave_materia',function($query) {
                  return $query->matClave;
            })

            ->filterColumn('nombre_materia', function($query, $keyword) {
                $query->whereRaw("CONCAT(matNombre) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('nombre_materia',function($query) {
                  return $query->matNombre;
            })

            ->filterColumn('grado_materia', function($query, $keyword) {
                $query->whereRaw("CONCAT(matSemestre) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('grado_materia',function($query) {
                  return $query->matSemestre;
            })

            ->filterColumn('ubicacion', function($query, $keyword) {
                $query->whereRaw("CONCAT(ubiClave) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('ubicacion',function($query) {
                  return $query->ubiClave;
            })

            ->filterColumn('departamento', function($query, $keyword) {
                $query->whereRaw("CONCAT(depClave) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('departamento',function($query) {
                  return $query->depClave;
            })

            ->filterColumn('escuela', function($query, $keyword) {
                $query->whereRaw("CONCAT(escClave) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('escuela',function($query) {
                  return $query->escClave;
            })

            ->filterColumn('programa_', function($query, $keyword) {
                $query->whereRaw("CONCAT(progClave) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('programa_',function($query) {
                  return $query->progClave;
            })

            ->filterColumn('plan', function($query, $keyword) {
                $query->whereRaw("CONCAT(planClave) like ?", ["%{$keyword}%"]);
               
            })
            ->addColumn('plan',function($query) {
                  return $query->planClave;
            })

           
            ->addColumn('action', function($query) {                
                

                $bachiller_grupo = Bachiller_grupos::select('bachiller_grupos.*',
                'bachiller_materias.matClave',
                'bachiller_materias.matNombre',
                'periodos.perNumero',
                'periodos.perAnio')
                ->join('bachiller_materias', 'bachiller_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
                ->where('bachiller_materias.id', $query->bachiller_materia_id)
                ->where('periodos.id', $query->periodo_id)
                ->where('bachiller_grupos.plan_id', $query->plan_id)
                ->where('bachiller_materias.matSemestre', $query->matSemestre)
                ->get();  

                foreach($bachiller_grupo as $gr){
                    $btnEditar = '<a href="' . route('bachiller.bachiller_evidencias.edit', ['id' => $query->id, 'grupo_id' => $gr->id]) . '" class="button button--icon js-button js-ripple-effect" title="Editar">
                    <i class="material-icons">edit</i>
                    </a>';

                    $btnVer ='<a href="' . route('bachiller.bachiller_evidencias.show', ['id' => $query->id, 'grupo_id' => $gr->id]) . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                        <i class="material-icons">visibility</i>
                    </a>';
                }                
             

  
                return $btnVer
                .$btnEditar.
                '<form id="delete_' . $query->id . '" action="'.route('bachiller.bachiller_evidencias.destroy', ['id' => $query->id]).'" method="POST" style="display:inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <a href="#" data-id="'  . $query->id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                        <i class="material-icons">delete</i>
                    </a>
                </form>';
            })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($bachiller_grupo_id)
    {
        $bachiller_grupo = Bachiller_grupos::select('bachiller_grupos.*',
        'bachiller_materias.matClave',
        'bachiller_materias.matNombre',
        'periodos.perNumero',
        'periodos.perAnio',
        'departamentos.id as departamento_id',
        'departamentos.depClave',
        'departamentos.depNombre',
        'ubicacion.id as ubicacion_id',
        'ubicacion.ubiClave',
        'ubicacion.ubiNombre',
        'planes.id as plane_id',
        'planes.planClave',
        'programas.id as programa_id',
        'programas.progClave',
        'programas.progNombre',
        'escuelas.id as escuela_id',
        'escuelas.escClave',
        'escuelas.escNombre'
        )
        ->join('bachiller_materias', 'bachiller_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('planes', 'bachiller_grupos.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->leftJoin('bachiller_materias_acd', 'bachiller_grupos.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
        ->where('bachiller_grupos.id', $bachiller_grupo_id)
        ->first();
        
        
        return view('bachiller.evidencias.create', [
            "bachiller_grupo" => $bachiller_grupo
        ]);
    }

  
    public function getMateriasEvidencias(Request $request, $plan_id, $programa_id, $matSemestre)
    {
        if ($request->ajax()) {
            $Materia = Bachiller_materias::select('bachiller_materias.*')
            ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->where('bachiller_materias.plan_id', $plan_id)
            ->where('programas.id', $programa_id)
            ->where('bachiller_materias.matSemestre', $matSemestre)
            ->get();

            return response()->json($Materia);
        }
    }

    public function getMateriasEvidenciasPeriodo(Request $request, $periodo_id, $bachiller_materia_id, $matSemestre)
    {
        if ($request->ajax()) {
            
            $Materia = Bachiller_evidencias::where('bachiller_evidencias.periodo_id', $periodo_id)
                ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
                ->where('bachiller_evidencias.bachiller_materia_id', $bachiller_materia_id)
                ->where('bachiller_materias.matSemestre', $matSemestre)
                ->get();

            return response()->json($Materia);
        }
    }

    public function getMateriasEvidenciasPeriodoACD(Request $request, $periodo_id, $bachiller_materia_id, $matSemestre, $bachiller_materia_acd_id)
    {
        if ($request->ajax()) {

            $Materia = Bachiller_evidencias::where('bachiller_evidencias.periodo_id', $periodo_id)
            ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->where('bachiller_evidencias.bachiller_materia_id', $bachiller_materia_id)
            ->where('bachiller_materias.matSemestre', $matSemestre)
            ->where('bachiller_evidencias.bachiller_materia_acd_id', $bachiller_materia_acd_id)
            ->get();

            return response()->json($Materia);
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

        DB::beginTransaction();
        try {
            
            $materias = $request->materiasEvidencias;

            foreach ($materias as $key => $materia) {
                $materia = explode('~',$materia);

                if($materia[11] == "NULL"){
                    $nuevoValorACD = NULL;
                }else{
                    $nuevoValorACD = $materia[11];
                }
                Bachiller_evidencias::create([
                    'periodo_id'                => $materia[0],
                    'bachiller_materia_id'      => $materia[1],
                    'bachiller_materia_acd_id'  => $nuevoValorACD,
                    'eviNumero'                 => $materia[4],
                    'eviDescripcion'            => $materia[5],
                    'eviFechaEntrega'           => \Carbon\Carbon::parse($materia[6])->format('Y-m-d'),
                    'eviPuntos'                 => $materia[7],
                    'eviTipo'                   => $materia[8],
                    'eviFaltas'                 => $materia[9],
                    'user_docente_id'           =>  auth()->user()->id           
                ]);
            }           

            
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...'.$errorCode,$errorMessage)->showConfirmButton();
            return back()->withInput();
        }
        DB::commit();
        alert('Escuela Modelo', 'La(s) evidencias materias se ha creado con éxito','success')->showConfirmButton()->autoClose(5000);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $grupo_id)
    {
        $bachiller_evidencias = Bachiller_evidencias::select(
            'bachiller_evidencias.id',
            'bachiller_evidencias.periodo_id',
            'bachiller_evidencias.bachiller_materia_id',
            'bachiller_evidencias.eviNumero',
            'bachiller_evidencias.eviDescripcion',
            'bachiller_evidencias.eviFechaEntrega',
            'bachiller_evidencias.eviPuntos',
            'bachiller_evidencias.eviTipo',
            'bachiller_evidencias.eviFaltas',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnio',
            'periodos.perAnioPago',
            'bachiller_materias.matNombre',
            'bachiller_materias.matClave',
            'planes.id as plan_id',
            'planes.planClave',
            'ubicacion.id as ubicacion_id',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'departamentos.id as departamento_id',
            'departamentos.depClave',
            'departamentos.depNombre',
            'escuelas.id as escuela_id',
            'escuelas.escClave',
            'escuelas.escNombre',
            'programas.id as programa_id',
            'programas.progClave',
            'programas.progNombre',
            'bachiller_materias_acd.gpoMatComplementaria'
        )
        ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->join('periodos', 'bachiller_evidencias.periodo_id', '=', 'periodos.id')
        ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->leftJoin('bachiller_materias_acd', 'bachiller_evidencias.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
        ->where('bachiller_evidencias.id', $id)
        ->first();

        return view('bachiller.evidencias.show', [
            "bachiller_evidencias" => $bachiller_evidencias,
            "grupo_id" => $grupo_id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $grupo_id)
    {
        $bachiller_evidencias = Bachiller_evidencias::select(
            'bachiller_evidencias.id',
            'bachiller_evidencias.periodo_id',
            'bachiller_evidencias.bachiller_materia_id',
            'bachiller_evidencias.bachiller_materia_acd_id',
            'bachiller_evidencias.eviNumero',
            'bachiller_evidencias.eviDescripcion',
            'bachiller_evidencias.eviFechaEntrega',
            'bachiller_evidencias.eviPuntos',
            'bachiller_evidencias.eviTipo',
            'bachiller_evidencias.eviFaltas',
            'periodos.id as periodo_id',
            'periodos.perNumero',
            'periodos.perAnio',
            'periodos.perAnioPago',
            'bachiller_materias.matNombre',
            'bachiller_materias.matClave',
            'bachiller_materias.matSemestre',
            'planes.id as plan_id',
            'planes.planClave',
            'ubicacion.id as ubicacion_id',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'departamentos.id as departamento_id',
            'departamentos.depClave',
            'departamentos.depNombre',
            'escuelas.id as escuela_id',
            'escuelas.escClave',
            'escuelas.escNombre',
            'programas.id as programa_id',
            'programas.progClave',
            'programas.progNombre',
            'bachiller_materias_acd.gpoMatComplementaria'
        )
        ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->join('periodos', 'bachiller_evidencias.periodo_id', '=', 'periodos.id')
        ->join('planes', 'bachiller_materias.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->leftJoin('bachiller_materias_acd', 'bachiller_evidencias.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
        ->where('bachiller_evidencias.id', $id)
        ->first();

        return view('bachiller.evidencias.edit', [
            "bachiller_evidencias" => $bachiller_evidencias,
            "grupo_id" => $grupo_id
        ]);
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
                'eviNumero'  => 'required',
                'eviDescripcion'  => 'required',
                'eviFechaEntrega'  => 'required',
                'eviTipo'  => 'required',
                'eviFaltas'  => 'required'                
            ],
            [
                'eviNumero.required' => 'El campo Número evidencia es obligatorio.',
                'eviDescripcion.required' => 'El campo Descripción evidencia es obligatorio.',
                'eviFechaEntrega.required' => 'El campo Fecha entrega es obligatorio.',
                'eviPuntos.required' => 'El campo Puntos evidencia es obligatorio.',
                'eviTipo.required' => 'El campo Tipo evidencia es obligatorio.',
                'eviFaltas.required' => 'El campo Faltas evidencia es obligatorio.'                
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            try {

                // validamos si los puntajes no revasan a 100 
                $suma = 0;

                $Bachiller_evidencias_contar = "";
                if($request->materia_acd_id != "NULL"){
                    $Bachiller_evidencias_contar = Bachiller_evidencias::where("bachiller_materia_id", "=", $request->materia_id)
                    ->where("bachiller_materia_acd_id", "=", $request->materia_acd_id)   
                    ->where("periodo_id", "=", $request->periodo_id)          
                    ->get();
                }else{
                    $Bachiller_evidencias_contar = Bachiller_evidencias::where("bachiller_materia_id", "=", $request->materia_id)
                    ->where("periodo_id", "=", $request->periodo_id)          
                    ->get();
                }
                
                if(count($Bachiller_evidencias_contar) > 0){
                    foreach($Bachiller_evidencias_contar as $values){
                        if($id != $values->id){
                            $suma = $suma + $values->eviPuntos;
                        }                    
                    }
                    $suma2 = $request->eviPuntos + $suma;
                    if ($suma2 > 100) {
                        alert()->error('Ups... Total de puntos con la información agregada actualmente "'.$suma2.'"', "La suma de los puntos no puede ser mayor a 100")->showConfirmButton()->autoClose(6000);
                        return back()->withInput();
                    }
                }     



                // Si los puntajes no revasan a 100 se puede actualizar 
                $bachiller_evidencias = Bachiller_evidencias::findOrFail($id);

                $bachiller_evidencias->update([
                    'eviNumero'       => $request->eviNumero,
                    'eviDescripcion'  => $request->eviDescripcion,
                    'eviFechaEntrega' => $request->eviFechaEntrega,
                    'eviPuntos'       => $request->eviPuntos,
                    'eviTipo'         => $request->eviTipo,
                    'eviFaltas'       => $request->eviFaltas,
                    'user_docente_id' => auth()->user()->id
                ]);
                

                alert('Escuela Modelo', 'La evidencia materia actualizado con éxito', 'success')->showConfirmButton()->autoClose('5000');
                return redirect('bachiller_evidencias/'.$request->bachiller_grupo_id)->withInput();

            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                    ->error('Ups...' . $errorCode, $errorMessage)
                    ->showConfirmButton();
                return redirect('bachiller_evidencias/'.$id.'/edit')->withInput();
            }
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
        $bachiller_evidencias = Bachiller_evidencias::findOrFail($id);

        $bachiller_inscritos_evidencias = Bachiller_inscritos_evidencias::where('evidencia_id', $id)->whereNotNull('ievPuntos')->get();

        if(count($bachiller_inscritos_evidencias) > 0){
            alert()->warning('Error...', 'No se puedo eliminar la evidencia materia debido que ya se han calificado algunos alumnos')->showConfirmButton();
            return redirect()->back();
        }else{
            try {
            
                if ($bachiller_evidencias->delete()) {
    
                    alert('Escuela Modelo', 'La evidencia materia se ha eliminado con éxito', 'success')->showConfirmButton();
                } else {
    
                    alert()->error('Error...', 'No se puedo eliminar el la evidencia materia')->showConfirmButton();
                }
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
            }
    
            return redirect()->back();
        }

       
    }
}
