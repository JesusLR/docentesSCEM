<?php

namespace App\Http\Controllers\Secundaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utils;
use App\Http\Models\Plan;
use App\Http\Models\Ubicacion;
use App\Models\User;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class SecundariaPlanesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('secundaria.planes.show-list');
    }

    /**
     * Show user list.
     *
     */
    public function list()
    {
        $planes = Plan::select('planes.id as plan_id','planes.planClave','programas.progNombre','escuelas.escNombre','departamentos.depNombre','ubicacion.ubiNombre')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('departamentos.depClave', 'SEC');


        return DataTables::of($planes)->addColumn('action',function($query){
            return '<a href="secundaria_plan/'.$query->plan_id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="secundaria_plan/'.$query->plan_id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
            <a href="#modalCambiarEstadoSecundaria" data-plan-id="'.$query->plan_id.'" class="btn-modal-estatus-plan-secundaria modal-trigger button button--icon js-button js-ripple-effect" title="Cambiar Estado">
                <i class="material-icons">unarchive</i>
            </a>
            
            <form id="delete_' . $query->plan_id . '" action="secundaria_plan/' . $query->plan_id . '" method="POST" style="display:inline;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <a href="#" data-id="'  . $query->plan_id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                    <i class="material-icons">delete</i>
                </a>
            </form>';
        }) ->make(true);
    }

     /**
     * Show planes.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPlanes(Request $request, $id)
    {
        if($request->ajax()){
            $planes = Plan::where('programa_id',$id)->orderBy('id', 'desc')->get();
            return response()->json($planes);
        }
    }

    /**
     * Show semestre.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSemestre(Request $request, $id)
    {
        if($request->ajax()){
            $plan = Plan::where('id',$id)->first();
            return response()->json($plan);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (User::permiso("plan") == "A" || User::permiso("plan") == "B") {
            $ubicaciones = Ubicacion::all();
            return view('secundaria.planes.create', [
                'ubicaciones' => $ubicaciones
            ]);
        } else {
            alert()->error('Ups...', 'Sin privilegios para esta acción!')->showConfirmButton()->autoClose(5000);
            return redirect('secundaria_plan');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'programa_id'   => 'required',
                'planClave'     => 'required',
                'planPeriodos'  => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect ('secundaria_plan/create')->withErrors($validator)->withInput();
        }

        $programa_id = $request->input('programa_id');
        if (Utils::validaPermiso('plan',$programa_id)) {
            alert()->error('Ups...', 'Sin privilegios en el programa!')->showConfirmButton()->autoClose(5000);
            return redirect()->to('secundaria_plan/create');
        }


        $existePlan = Plan::where("programa_id", "=", $request->programa_id)->where("planClave", "=", $request->planClave)->first();

        if ($existePlan) {
            alert()->error('Ups...', "La clave de plan ya existe. Favor de capturar otra clave de plan")->autoClose(5000);
            return back()->withInput();
        }
        
        try {
            $plan = Plan::create([
                'programa_id'   => $request->input('programa_id'),
                'planClave'     => $request->input('planClave'),
                'planPeriodos'  => Utils::validaEmpty($request->input('planPeriodos')),
                'planNumCreditos' => Utils::validaEmpty($request->input('planNumCreditos'))
            ]);

            alert('Escuela Modelo', 'El Plan se ha creado con éxito','success')->showConfirmButton();

            return redirect('secundaria_plan');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
            return back()->withInput();
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plan = Plan::with('programa')->findOrFail($id);

        return view('secundaria.planes.show', [
            'plan' => $plan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (User::permiso("plan") == "A" || User::permiso("plan") == "B") {
            $plan = Plan::with('programa')->findOrFail($id);

            return view('secundaria.planes.edit', [
                'plan' => $plan
            ]);

        }else{
            alert()->error('Ups...', 'Sin privilegios para esta acción!')->showConfirmButton()->autoClose(5000);
            return redirect('secundaria_plan');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            [
                'programa_id'   => 'required',
                'planClave'     => 'required',
                'planPeriodos'  => 'required'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $programa_id = $request->programa_id;
        if (Utils::validaPermiso('plan', $programa_id)) {
            alert()->error('Ups...', 'Sin privilegios en el programa!')->showConfirmButton()->autoClose(5000);
            return back();
        }

        $existePlan = Plan::where("programa_id", "=", $request->programa_id)->where("planClave", "=", $request->planClave)->first();
        if (($request->planClaveAnterior != $request->planClave) && $existePlan) {
            alert()->error('Ups...', "La clave de plan ya existe. Favor de capturar otra clave de plan")->autoClose(5000);
            return back()->withInput();
        }


        try {
            $plan = Plan::findOrFail($id);
            $plan->planClave    = $request->planClave;
            $plan->planPeriodos = Utils::validaEmpty($request->planPeriodos);
            $plan->planNumCreditos = Utils::validaEmpty($request->planNumCreditos);
            $plan->save();
            alert('Escuela Modelo', 'El Plan se ha actualizado con éxito','success')->showConfirmButton();

            return redirect('secundaria_plan');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...'.$errorCode,$errorMessage)->showConfirmButton();

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::permiso("plan") == "A" || User::permiso("plan") == "B") {
            $plan = Plan::findOrFail($id);
            try {
                $programa_id = $plan->programa_id;
                if(Utils::validaPermiso('plan',$programa_id)){
                    alert()
                    ->error('Ups...', 'Sin privilegios en el programa!')
                    ->showConfirmButton()
                    ->autoClose(5000);
                }
                if($plan->delete()){
                    alert('Escuela Modelo', 'El plan se ha eliminado con éxito','success')->showConfirmButton()
                    ->autoClose(5000);
                }else{
                    alert()
                    ->error('Error...', 'No se puedo eliminar el plan')
                    ->showConfirmButton()->autoClose(5000);
                }
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton()->autoClose(5000);
            }
        }else{
            alert()
            ->error('Ups...', 'Sin privilegios para esta acción!')
            ->showConfirmButton()
            ->autoClose(5000);
        }
        return redirect('secundaria_plan');
    }



    public function cambiarPlanEstado(Request $request) {
        if(!$request->planId || !$request->planEstado) {
            return response()->json(['res' => false, 'msg' => 'No se ingresaron los datos correctamente']);
        }

        $plan = Plan::findOrFail($request->planId)->update(['planEstado' => $request->planEstado]);

        if($plan) {
            return response()->json(['res' => $plan, 'msg' => 'Se actualizó correctamente el estado del plan.']);
        } else {
            return response()->json(['res' => false, 'msg' => 'Hubo un problema durante el proceso.']);
        }
    }//cambiarPlanEstado.

    public function getPlan(Request $request, $plan_id) {
        if($request->ajax()) {
            return response()->json(Plan::find($plan_id));
        }
    }//getPlan.

}
