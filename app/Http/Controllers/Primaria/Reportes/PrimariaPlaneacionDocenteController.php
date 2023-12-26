<?php

namespace App\Http\Controllers\Primaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Primaria\Primaria_empleado;
use App\Http\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaPlaneacionDocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ubicaciones = Ubicacion::sedes()->get();

        $primaria_empleado = Primaria_empleado::get();

        return view('primaria.reportes.planeacion_docente.create', [
            "ubicaciones" => $ubicaciones,
            "primaria_empleado" => $primaria_empleado
        ]);
    }

    public function imprimir(Request $request)
    {
        $programa_id = $request->programa_id;
        $plan_id = $request->plan_id;
        $periodo_id = $request->periodo_id;
        $mesAConsultar = $request->mesAConsultar;
        $grado = $request->gpoGrado;
        $grupo = $request->gpoClave;

        if($grado != ""){
            $resultado_array =  DB::select("call procPrimariaPlaneacionTodosLosGrupos(".$programa_id.",
            ".$plan_id.",
            ".$periodo_id.",        
            ".$grado.",
            '".$mesAConsultar."')");       
    
            if(empty($resultado_array)){
                alert()->warning('Sin coincidencias', 'No se han encontrado datos con la información proporcionada.')->showConfirmButton();
                return back()->withInput();
            }
        }

        // SP para motrar todo el grupo (todas las materias capturadas) 
        if($grado != "" && $grupo != ""){
            $resultado_array =  DB::select("call procPrimariaPlaneacionGradoGrupoTodos(".$programa_id.",
            ".$plan_id.",
            ".$periodo_id.",        
            ".$grado.",
            '".$grupo."',
            '".$mesAConsultar."')");       
    
            if(empty($resultado_array)){
                alert()->warning('Sin coincidencias', 'No se han encontrado datos con la información proporcionada.')->showConfirmButton();
                return back()->withInput();
            }
        }
        

        // SP para mostrar por grupo materia 
        $primaria_grupo_id = $request->primaria_grupo_id;
        if($primaria_grupo_id != ""){
            
            $resultado_array =  DB::select("call procPrimariaPlaneacionGradoGrupoMateria(".$programa_id.",
            ".$plan_id.",
            ".$periodo_id.",        
            ".$grado.",
            ".$primaria_grupo_id.",
            '".$mesAConsultar."')");       
    
            if(empty($resultado_array)){
                alert()->warning('Sin coincidencias', 'No se han encontrado datos con la información proporcionada.')->showConfirmButton();
                return back()->withInput();
            }
        }


        // SP para mostrar los que son del docente 
        $empleado_id = $request->empleado_id;
        if($empleado_id != ""){
            $resultado_array =  DB::select("call procPrimariaPlaneacionGradoGrupoDocente(".$programa_id.",
            ".$plan_id.",
            ".$periodo_id.",        
            '".$mesAConsultar."',
            ".$empleado_id.")");       
    
            if(empty($resultado_array)){
                alert()->warning('Sin coincidencias', 'No se han encontrado datos con la información proporcionada.')->showConfirmButton();
                return back()->withInput();
            }
        }


        $planeaciones = collect($resultado_array);
        $perAnioActual = $planeaciones[0]->perAnioPago;
        $perAnioSiguiente = $planeaciones[0]->perAnioPago + 1;
        $ciclo_escolar = $perAnioActual.'-'.$perAnioSiguiente;
        $mes = $planeaciones[0]->mes;
        $bimestre = $planeaciones[0]->bimestre;
        $trimestre = $planeaciones[0]->trimestre;

        $agruacion_de_id = $planeaciones->groupBy('id');


        $parametro_NombreArchivo = "pdf_primaria_planeacion_docente_grado_grupo";
        $pdf = PDF::loadView('reportes.pdf.primaria.planeacion_docente.' . $parametro_NombreArchivo, [
            "ciclo_escolar" => $ciclo_escolar,
            "mes" => $mes,
            "bimestre" => $bimestre,
            "planeaciones" => $planeaciones,
            "agruacion_de_id" => $agruacion_de_id,
            "trimestre" => $trimestre
        ]); 


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');

    }
    

}
