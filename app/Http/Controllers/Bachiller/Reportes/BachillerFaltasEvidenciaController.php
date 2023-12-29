<?php

namespace App\Http\Controllers\Bachiller\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bachiller\Bachiller_inscritos_evidencias;
use Illuminate\Support\Facades\DB;
use PDF;

class BachillerFaltasEvidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function imprimir_reporte($bachiller_grupo_id)
    {

        $inscritos_evidencia = Bachiller_inscritos_evidencias::select(
            'bachiller_inscritos_evidencias.id',
            'bachiller_inscritos_evidencias.bachiller_inscrito_id',
            'bachiller_inscritos_evidencias.ievPuntos',
            'bachiller_inscritos_evidencias.ievFaltas',
            'bachiller_evidencias.eviNumero',
            'bachiller_evidencias.eviDescripcion',
            'bachiller_materias.matNombre',
            'bachiller_materias.matClave',
            'bachiller_materias_acd.gpoMatComplementaria',
            'alumnos.aluClave',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'bachiller_grupos.gpoGrado',
            'bachiller_grupos.gpoClave',
            'periodos.perNumero',
            'periodos.perAnio',
            'periodos.perFechaInicial',
            'periodos.perFechaFinal',
            'departamentos.depClave',
            'departamentos.depNombre',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'planes.planClave',
            'programas.progClave',
            'programas.progNombre',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_empleados.empNombre'
        )
        ->join('bachiller_evidencias', 'bachiller_inscritos_evidencias.evidencia_id', '=', 'bachiller_evidencias.id')
        ->join('bachiller_materias', 'bachiller_evidencias.bachiller_materia_id', '=', 'bachiller_materias.id')
        ->leftJoin('bachiller_materias_acd', 'bachiller_evidencias.bachiller_materia_acd_id', '=', 'bachiller_materias_acd.id')
        ->join('bachiller_inscritos', 'bachiller_inscritos_evidencias.bachiller_inscrito_id', '=', 'bachiller_inscritos.id')
        ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
        ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('bachiller_empleados', 'bachiller_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
        ->join('planes', 'bachiller_grupos.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->where('bachiller_evidencias.eviFaltas', '=', 'S')
        ->where('bachiller_grupos.id', '=', $bachiller_grupo_id)
        ->whereNull('cursos.deleted_at')
        ->whereNull('bachiller_inscritos_evidencias.deleted_at')
        ->whereNull('alumnos.deleted_at')
        ->whereNull('bachiller_grupos.deleted_at')
        ->whereNull('bachiller_inscritos.deleted_at')
        ->orderBy('personas.perApellido1', 'ASC')
        ->orderBy('personas.perApellido2', 'ASC')
        ->orderBy('personas.perNombre', 'ASC')
        ->orderBy('bachiller_evidencias.eviNumero', 'ASC')
        ->get();


        // $inscritos_evidencia = collect($inscritos_evidencia);

        if ($inscritos_evidencia->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay evidencias capturadas para este grupo. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }

        $totalEvidecias = $inscritos_evidencia->groupBy('eviNumero');
        $totalEvideciasClave = $inscritos_evidencia->groupBy('aluClave');

       
        $parametro_NombreArchivo = "pdf_bachiller_faltas_evidencias";
        


        
        $pdf = PDF::loadView('bachiller.pdf.faltas_inscritos.' . $parametro_NombreArchivo, [          
            "alumnos" => $inscritos_evidencia,
            // "periodoVigente" => $periodoVigente,
            "totalEvidecias" => $totalEvidecias,
            "totalEvideciasClave" => $totalEvideciasClave
        ]);


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');

    }

}
