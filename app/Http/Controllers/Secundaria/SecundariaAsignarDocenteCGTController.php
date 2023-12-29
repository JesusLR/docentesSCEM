<?php

namespace App\Http\Controllers\Secundaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Secundaria\Secundaria_empleados;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;

class SecundariaAsignarDocenteCGTController extends Controller
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

        $empleados = Secundaria_empleados::select('secundaria_empleados.*')
        ->where('empEstado', '!=', 'B')
        ->get();

        return view('secundaria.asignarDocenteCGT.create', [
            'ubicaciones' => $ubicaciones,
            'departamento' => $departamento,
            'empleados' => $empleados
        ]);
    }

    public function obtenerGrupos(Request $request)
    {
        if($request->ajax()){


            // llama al procedure de los grupos a buscar
            $resultado_array =  DB::select("call procSecundariaGruposMaterias(".$request->plan_id.", ".$request->periodo_id.", ".$request->gpoGrado.", '".$request->gpoGrupo."')");

            $grupos = collect($resultado_array);


            return response()->json($grupos);
            
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
        // validar si hay envio de datos 
        if ($request->ajax()) {

            // variables 
            $secundaria_grupo_id = $request->input("secundaria_grupo_id");
            $empleado_id = $request->input('empleado_id');


            // si el input es diferente de vacio entra 
            if ($secundaria_grupo_id != "") {

                // si el input es diferente de vacio entra 
                if ($empleado_id != "") {

                    $total_id_grupos = count($secundaria_grupo_id);

                    // ciclo para actualizar los id de empleado en la tabla grupos 
                    for ($x = 0; $x < $total_id_grupos; $x++) {
                        for ($i = 0; $i < count($secundaria_grupo_id); $i++) {
                            $grupo = DB::statement('call procSecundariaActualizaGruposEmpleado(?, ?)', [$secundaria_grupo_id[$i], $empleado_id]);
                        }
                        return response()->json([
                            'res' => $empleado_id,
                            'grupo' => $grupo
                        ]);
                    }
                } else {
                    // en caso que no hay empleado seleccionado lo siguiente 
                    return response()->json([
                        'res' => 'sinEmpleado',
                    ]);
                }
            } else {
                // en caso que no hay id de grupos, es decir no hay ningun check activo retorna lo siguiente 
                return response()->json([
                    'res' => 'error',
                ]);
            }
        }
    }

    

}
