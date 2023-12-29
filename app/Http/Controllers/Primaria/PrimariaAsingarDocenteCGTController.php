<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Primaria\Primaria_empleado;
use App\Models\Primaria\Primaria_grupo;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;

class PrimariaAsingarDocenteCGTController extends Controller
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

        $empleados = Primaria_empleado::select('primaria_empleados.*')
        ->where('empEstado', '!=', 'B')
        ->get();

        return view('primaria.asignarDocenteCGT.create', [
            'ubicaciones' => $ubicaciones,
            'departamento' => $departamento,
            'empleados' => $empleados
        ]);
    }

    public function obtenerGrupos(Request $request)
    {
        if($request->ajax()){


            // llama al procedure de los grupos a buscar
            $resultado_array =  DB::select("call procPrimariaGruposMaterias(".$request->plan_id.", ".$request->periodo_id.", ".$request->gpoGrado.", '".$request->gpoGrupo."')");

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
            $primaria_grupo_id = $request->input("primaria_grupo_id");
            $empleado_id = $request->input('empleado_id');


            // si el input es diferente de vacio entra 
            if ($primaria_grupo_id != "") {

                // si el input es diferente de vacio entra 
                if ($empleado_id != "") {

                    $total_id_grupos = count($primaria_grupo_id);

                    // ciclo para actualizar los id de empleado en la tabla grupos 
                    for ($x = 0; $x < $total_id_grupos; $x++) {
                        for ($i = 0; $i < count($primaria_grupo_id); $i++) {
                            $grupo = DB::statement('call procPrimariaActualizaGruposEmpleado(?, ?)', [$primaria_grupo_id[$i], $empleado_id]);
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
