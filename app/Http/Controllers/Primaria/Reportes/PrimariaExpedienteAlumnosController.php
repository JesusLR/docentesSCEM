<?php

namespace App\Http\Controllers\Primaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Conceptoscursoestado;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaExpedienteAlumnosController extends Controller
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
        $ubicaciones = Ubicacion::sedes()->get();

        $conceptos = Conceptoscursoestado::get();

        return view('primaria.reportes.expediente_alumnos.create', [
            "ubicaciones" => $ubicaciones,
            "conceptos" => $conceptos
        ]);
    }

    // function para imprimir expedientes 
    public function imprimirExpediente(Request $request)
    {
        // para obtener el id del alumnos abuscar
        $aluClave = $request->aluClave;
        $periodo_id = $request->periodo_id;
        $grado = $request->gpoGrado;
        $grupo = $request->gpoClave;
        $concepto = $request->conceptos;
        $tipoReporte = $request->tipoReporte;

        if($tipoReporte == 3){
            $parametro_NombreArchivo = "pdf_primaria_expediente_blanco_nuevo";
            $pdf = PDF::loadView('reportes.pdf.primaria.expediente.' . $parametro_NombreArchivo, [ 
                    
            ]);
    
            $pdf->defaultFont = 'Times Sans Serif';
    
            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        }else{
            if($aluClave != ""){
                $alumno = Alumno::where("aluClave", $aluClave)->first();
    
                // llama al procedure de los alumnos a buscar 
                $unicoAlumno =  DB::select("call procPrimariaUnAlumnoInscritoGrupo(". $periodo_id . "," . $grado . ",'" . $grupo . "','" . $concepto . "', ".$alumno->id.")");
                $alumno_collection = collect($unicoAlumno);
                // si no hay datos muestra alerta 
                if ($alumno_collection->isEmpty()) {
                    alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este alumno. Favor de verificar.')->showConfirmButton();
                    return back()->withInput();
                }
    
                // nombre, apellido, etc 
                $unicoAlumnoDatos =  DB::select("call procPrimerCursoIngreso(".$alumno->id.")");
                $alumnoDatos_collection = collect($unicoAlumnoDatos);
    
    
                $unicoAlumnoHistoria =  DB::select("call procPrimariaDatosHistoria(".$alumno->id.")");
                // return $alumnoHistoria_collection = collect($unicoAlumnoHistoria);
                $alumnoHistoria_collection = collect($unicoAlumnoHistoria);

    
                // Obtener tutores 
                $unicoAlumnoTutores =  DB::select("call procPrimariaDatosTutores(".$alumno->id.")");
                $alumnoTutor_collection = collect($unicoAlumnoTutores);
                
        
                
    
                
                $parametro_NombreArchivo = "pdf_primaria_expediente_uno_nuevo";
                $pdf = PDF::loadView('reportes.pdf.primaria.expediente.' . $parametro_NombreArchivo, [
                    "escuela" => $alumno_collection,
                    "alumno" => $alumnoDatos_collection,
                    "historiaAlumno" => $alumnoHistoria_collection,
                    "tutores" => $alumnoTutor_collection,
                    "aluClave" => $aluClave,
                    "tipoReporte" => $tipoReporte
                ]);
    
                $pdf->defaultFont = 'Times Sans Serif';
    
                return $pdf->stream($parametro_NombreArchivo . '.pdf');
                return $pdf->download($parametro_NombreArchivo  . '.pdf');
    
            }else{
                
    
                // buscar grupo de alumnos 
                // llama al procedure de los alumnos a buscar 
                $grupoAlumnos =  DB::select("call procPrimariaAlumnosInscritosGrupo(". $periodo_id . "," . $grado . ",'" . $grupo . "','" . $concepto . "')");
                $alumnogrupo_collection = collect($grupoAlumnos);
    
                 // si no hay datos muestra alerta 
                if ($alumnogrupo_collection->isEmpty()) {
                    alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
                    return back()->withInput();
                }
                $alumno_id = $alumnogrupo_collection->pluck('alumno_id');
    
                
    
                $parametro_NombreArchivo = "pdf_primaria_expediente_todogrupo_nuevo";
                $pdf = PDF::loadView('reportes.pdf.primaria.expediente.' . $parametro_NombreArchivo, [ 
                    "alumnogrupo_collection" => $alumnogrupo_collection,
                    "alumno_id" => $alumno_id,
                    "tipoReporte" => $tipoReporte
               
                ]);
    
                $pdf->defaultFont = 'Times Sans Serif';
    
                return $pdf->stream($parametro_NombreArchivo . '.pdf');
                return $pdf->download($parametro_NombreArchivo  . '.pdf');
    
            }
        }
        
        
        

    }

   


}
