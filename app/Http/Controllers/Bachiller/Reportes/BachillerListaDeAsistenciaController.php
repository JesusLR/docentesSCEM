<?php

namespace App\Http\Controllers\Bachiller\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utils;
use App\Models\Bachiller\Bachiller_inscritos;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class BachillerListaDeAsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporte($grupo_id)
    {
        $bachiller_grupo = DB::select("SELECT 
        bachiller_inscritos.id as bachiller_inscrito_id,
        alumnos.aluClave,
        CONCAT_WS(' ',personas.perApellido1,personas.perApellido2,personas.perNombre) AS alumno,
        bachiller_grupos.plan_id,
        bachiller_grupos.periodo_id
        FROM bachiller_inscritos
        INNER JOIN bachiller_grupos as bachiller_grupos on bachiller_grupos.id = bachiller_inscritos.bachiller_grupo_id
        INNER JOIN cursos as cursos ON cursos.id = bachiller_inscritos.curso_id
        INNER JOIN alumnos as alumnos on alumnos.id = cursos.alumno_id
        INNER JOIN personas as personas on personas.id = alumnos.persona_id
        WHERE bachiller_grupos.id = $grupo_id
        AND bachiller_inscritos.deleted_at IS NULL
        AND cursos.deleted_at IS NULL
        AND alumnos.deleted_at IS NULL
        AND personas.deleted_at IS NULL");

        return view('bachiller.reportes.lista_de_asistencia.create', [
            'grupo_id' => $grupo_id
        ]);
    }

    public function imprimirFaltas(Request $request)
    {
        $bachiller_grupo = DB::select("SELECT 
        bachiller_inscritos.id as bachiller_inscrito_id,
        alumnos.aluClave,
        CONCAT_WS(' ',personas.perApellido1,personas.perApellido2,personas.perNombre) AS alumno,
        bachiller_grupos.plan_id,
        bachiller_grupos.periodo_id,
        bachiller_grupos.gpoMatComplementaria,
        periodos.perAnio,
        periodos.perNumero,
        periodos.perFechaInicial,
        periodos.perFechaFinal,
        departamentos.depClave,
        departamentos.depNombre,
        ubicacion.ubiClave,
        ubicacion.ubiNombre,
        bachiller_grupos.gpoGrado,
        bachiller_grupos.gpoClave,
        planes.planClave,
        programas.progClave,
        programas.progNombre,
        bachiller_empleados.empApellido1,
        bachiller_empleados.empApellido2,
        bachiller_empleados.empNombre,
        bachiller_materias.matClave,
        bachiller_materias.matNombre
        FROM bachiller_inscritos
        INNER JOIN bachiller_grupos as bachiller_grupos on bachiller_grupos.id = bachiller_inscritos.bachiller_grupo_id
        INNER JOIN cursos as cursos ON cursos.id = bachiller_inscritos.curso_id
        INNER JOIN alumnos as alumnos on alumnos.id = cursos.alumno_id
        INNER JOIN personas as personas on personas.id = alumnos.persona_id
        INNER JOIN periodos as periodos on periodos.id = bachiller_grupos.periodo_id
        INNER JOIN departamentos AS departamentos on departamentos.id = periodos.departamento_id
        INNER JOIN ubicacion AS ubicacion on ubicacion.id = departamentos.ubicacion_id
        INNER JOIN planes AS planes ON planes.id = bachiller_grupos.plan_id
        INNER JOIN programas AS programas ON programas.id = planes.programa_id
        INNER JOIN bachiller_empleados AS bachiller_empleados ON bachiller_empleados.id = bachiller_grupos.empleado_id_docente
        INNER JOIN bachiller_materias AS bachiller_materias ON bachiller_materias.id = bachiller_grupos.bachiller_materia_id
        WHERE bachiller_grupos.id = $request->bachiller_grupo_id
        AND bachiller_inscritos.deleted_at IS NULL
        AND cursos.deleted_at IS NULL
        AND alumnos.deleted_at IS NULL
        AND personas.deleted_at IS NULL
        ORDER BY personas.perApellido1 ASC, personas.perApellido2 ASC, personas.perNombre ASC");

        // $request->bachiller_grupo_id

        if (collect($bachiller_grupo)->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay evidencias capturadas para este grupo. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }


        $parametro_NombreArchivo = "pdf_bachiller_faltas_generales";




        $pdf = PDF::loadView('bachiller.pdf.faltas.' . $parametro_NombreArchivo, [
            "alumnos" =>collect($bachiller_grupo),
            "fechaInicio" => $request->fechaInicio,
            "fechaFin" => $request->fechaFin,
            "bachiller_grupo_id" => $request->bachiller_grupo_id
        ]);


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

    public function imprimirListaAsistenciaYuc($grupo_id)
    {
        $alumnos_grupo =  Bachiller_inscritos::select(
            'bachiller_inscritos.id',
            'bachiller_inscritos.inscCalificacionSep as septiembre',
            'bachiller_inscritos.inscCalificacionOct as octubre',
            'bachiller_inscritos.inscCalificacionNov as noviembre',
            'bachiller_inscritos.inscCalificacionDic as diciembre',
            'bachiller_inscritos.inscCalificacionEne as enero',
            'bachiller_inscritos.inscCalificacionFeb as febrero',
            'bachiller_inscritos.inscCalificacionMar as marzo',
            'bachiller_inscritos.inscCalificacionAbr as abril',
            'bachiller_inscritos.inscCalificacionMay as mayo',
            'bachiller_inscritos.inscCalificacionJun as junio',
            'bachiller_grupos.id as bachiller_grupo_id',
            'bachiller_grupos.gpoGrado',
            'bachiller_grupos.gpoClave',
            'bachiller_grupos.gpoMatComplementaria',
            'cursos.id as curso_id',
            'bachiller_materias.id as bachiller_materia_id',
            'bachiller_materias.matClave',
            'bachiller_materias.matNombre',
            'bachiller_materias.matNombreCorto',
            'periodos.id as periodo_id',
            'periodos.perAnioPago',
            'periodos.perFechaInicial as fecha_inicio',
            'periodos.perFechaFinal as fecha_fin',
            'alumnos.id as alumno_id',
            'alumnos.aluClave as clavePago',
            'personas.id as persona_id',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'bachiller_empleados.id as empleados_id',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_empleados.empNombre',
            'bachiller_empleados.empSexo',
            'escuelas.id as escuela_id',
            'escuelas.escClave',
            'escuelas.escNombre',
            'departamentos.id as departamento_id',
            'planes.id as plan_id',
            'planes.planClave',
            'ubicacion.id as ubicacion_id',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre'
        )
            ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
            ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
            ->join('bachiller_materias', 'bachiller_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('bachiller_empleados', 'bachiller_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
            ->join('planes', 'bachiller_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('bachiller_inscritos.bachiller_grupo_id', $grupo_id)
            ->whereNull('bachiller_inscritos.deleted_at')
            ->whereNull('bachiller_grupos.deleted_at')
            ->whereNull('alumnos.deleted_at')
            ->whereNull('personas.deleted_at')
            ->whereNull('cursos.deleted_at')
            // ->where('cursos.curEstado', '=', 'R')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

        if (count($alumnos_grupo) < 1) {
            alert()->warning('Sin coincidencias', 'Los alumnos se deben encontrar en estado de curso REGULAR')->showConfirmButton();
            return back();
        }
        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');



        $parametro_NombreArchivo = "pdf_bachiller_lista_de_asistencia";
        // view('reportes.pdf.bachiller.lista_de_asistencia.pdf_bachiller_lista_de_asistencia');
        $pdf = PDF::loadView('bachiller.pdf.lista_de_asistencia.' . $parametro_NombreArchivo, [
            "inscritos" => $alumnos_grupo,
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "horaActual" => $fechaActual->format('H:i:s'),
            "parametro_NombreArchivo" => $parametro_NombreArchivo,

        ]);

        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

    public function imprimirListaAsistenciaYucExcel($grupo_id)
    {
        $alumnos_grupo =  Bachiller_inscritos::select(
            'bachiller_inscritos.id',
            'bachiller_inscritos.inscCalificacionSep as septiembre',
            'bachiller_inscritos.inscCalificacionOct as octubre',
            'bachiller_inscritos.inscCalificacionNov as noviembre',
            'bachiller_inscritos.inscCalificacionDic as diciembre',
            'bachiller_inscritos.inscCalificacionEne as enero',
            'bachiller_inscritos.inscCalificacionFeb as febrero',
            'bachiller_inscritos.inscCalificacionMar as marzo',
            'bachiller_inscritos.inscCalificacionAbr as abril',
            'bachiller_inscritos.inscCalificacionMay as mayo',
            'bachiller_inscritos.inscCalificacionJun as junio',
            'bachiller_grupos.id as bachiller_grupo_id',
            'bachiller_grupos.gpoGrado',
            'bachiller_grupos.gpoClave',
            'bachiller_grupos.gpoMatComplementaria',
            'cursos.id as curso_id',
            'bachiller_materias.id as bachiller_materia_id',
            'bachiller_materias.matClave',
            'bachiller_materias.matNombre',
            'bachiller_materias.matNombreCorto',
            'periodos.id as periodo_id',
            'periodos.perAnioPago',
            'periodos.perFechaInicial as fecha_inicio',
            'periodos.perFechaFinal as fecha_fin',
            'alumnos.id as alumno_id',
            'alumnos.aluClave as clavePago',
            'personas.id as persona_id',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'bachiller_empleados.id as empleados_id',
            'bachiller_empleados.empApellido1',
            'bachiller_empleados.empApellido2',
            'bachiller_empleados.empNombre',
            'bachiller_empleados.empSexo',
            'escuelas.id as escuela_id',
            'escuelas.escClave',
            'escuelas.escNombre',
            'departamentos.id as departamento_id',
            'departamentos.depClave',
            'planes.id as plan_id',
            'planes.planClave',
            'ubicacion.id as ubicacion_id',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'programas.progClave',
            'programas.progNombre'
        )
            ->join('bachiller_grupos', 'bachiller_inscritos.bachiller_grupo_id', '=', 'bachiller_grupos.id')
            ->join('cursos', 'bachiller_inscritos.curso_id', '=', 'cursos.id')
            ->join('bachiller_materias', 'bachiller_grupos.bachiller_materia_id', '=', 'bachiller_materias.id')
            ->join('periodos', 'bachiller_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('bachiller_empleados', 'bachiller_grupos.empleado_id_docente', '=', 'bachiller_empleados.id')
            ->join('planes', 'bachiller_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('bachiller_inscritos.bachiller_grupo_id', $grupo_id)
            ->whereNull('bachiller_inscritos.deleted_at')
            ->whereNull('bachiller_grupos.deleted_at')
            ->whereNull('alumnos.deleted_at')
            ->whereNull('personas.deleted_at')
            ->whereNull('cursos.deleted_at')
            // ->where('cursos.curEstado', '=', 'R')
            ->orderBy('personas.perApellido1', 'ASC')
            ->orderBy('personas.perApellido2', 'ASC')
            ->orderBy('personas.perNombre', 'ASC')
            ->get();

        if (count($alumnos_grupo) < 1) {
            alert()->warning('Sin coincidencias', 'Los alumnos se deben encontrar en estado de curso REGULAR')->showConfirmButton();
            return back();
        }
        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');



        return $this->generarExcel($alumnos_grupo);
    }

    public function generarExcel($info_reporte)
    {

        // return $info_reporte;

        $periodo = "Período: " . Utils::fecha_string($info_reporte[0]->fecha_inicio, $info_reporte[0]->fecha_inicio) . ' al ' . Utils::fecha_string($info_reporte[0]->fecha_fin, $info_reporte[0]->fecha_fin);
        $nivel = "Niv/Carr: " . $info_reporte[0]->depClave . ' (' . $info_reporte[0]->planClave . ') ' . $info_reporte[0]->progNombre;
        $ubicacion = "Ubicación: " . $info_reporte[0]->ubiClave . '-' . $info_reporte[0]->ubiNombre;
        $materia = "Materia: " . $info_reporte[0]->matClave . '-' . $info_reporte[0]->matNombre;
        $semestre = "Semestre: " . $info_reporte[0]->gpoGrado;
        $grupo = "Grupo: " . $info_reporte[0]->gpoClave;
        $materiaComplementaria = "Materia complementaria: " . $info_reporte[0]->gpoMatComplementaria;
        $docente = "Docente: " . $info_reporte[0]->empNombre . ' ' . $info_reporte[0]->empApellido1 . ' ' . $info_reporte[0]->empApellido2;


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->mergeCells("D1:E1");
        $sheet->getStyle('D1')->getFont()->setBold(true);
        // $sheet->setCellValue('A1', "{$info_reporte['ubicacion']} - {$info_reporte['departamento']} - {$info_reporte['periodo']}");
        $sheet->setCellValue('D1', 'Preparatoria "ESCUELA MODELO"');

        $sheet->getStyle('D2')->getFont()->setBold(true);
        $sheet->setCellValue('D2', 'LISTA DE ASISTENCIA POR GRUPO-MATERIA');

        $sheet->getStyle('D4')->getFont()->setBold(true);
        $sheet->setCellValue('D4', "{$periodo}");
        $sheet->getStyle('D5')->getFont()->setBold(true);
        $sheet->setCellValue('D5', "{$nivel}");
        $sheet->getStyle('D6')->getFont()->setBold(true);
        $sheet->setCellValue('D6', "{$ubicacion}");
        $sheet->getStyle('D7')->getFont()->setBold(true);
        $sheet->setCellValue('D7', "{$materia}");
        $sheet->getStyle('E7')->getFont()->setBold(true);
        $sheet->setCellValue('E7', "{$semestre}");
        $sheet->getStyle('F7')->getFont()->setBold(true);
        $sheet->setCellValue('F7', "{$grupo}");
        $sheet->getStyle('G7')->getFont()->setBold(true);
        $sheet->setCellValue('G7', "Fecha:");

        if ($info_reporte[0]->gpoMatComplementaria != "") {
            $sheet->getStyle('D8')->getFont()->setBold(true);
            $sheet->setCellValue('D8', "{$materiaComplementaria}");

            $sheet->getStyle('D9')->getFont()->setBold(true);
            $sheet->setCellValue('D9', "{$docente}");
        } else {
            $sheet->getStyle('D8')->getFont()->setBold(true);
            $sheet->setCellValue('D8', "{$docente}");
        }

        $sheet->getStyle("A11:Z11")->getFont()->setBold(true);

        $sheet->setCellValueByColumnAndRow(1, 11, "Núm");
        $sheet->setCellValueByColumnAndRow(2, 11, "Clave Pago");
        $sheet->setCellValueByColumnAndRow(3, 11, "Nombre del Alumno");
        $sheet->setCellValueByColumnAndRow(4, 11, "      ");
        $sheet->setCellValueByColumnAndRow(5, 11, "      ");
        $sheet->setCellValueByColumnAndRow(6, 11, "      ");
        $sheet->setCellValueByColumnAndRow(7, 11, "      ");
        $sheet->setCellValueByColumnAndRow(8, 11, "      ");
        $sheet->setCellValueByColumnAndRow(9, 11, "      ");
        $sheet->setCellValueByColumnAndRow(10, 11, "      ");
        $sheet->setCellValueByColumnAndRow(11, 11, "      ");
        $sheet->setCellValueByColumnAndRow(12, 11, "      ");
        $sheet->setCellValueByColumnAndRow(13, 11, "      ");
        $sheet->setCellValueByColumnAndRow(14, 11, "      ");
        $sheet->setCellValueByColumnAndRow(15, 11, "      ");
        $sheet->setCellValueByColumnAndRow(16, 11, "      ");
        $sheet->setCellValueByColumnAndRow(17, 11, "      ");
        $sheet->setCellValueByColumnAndRow(18, 11, "      ");
        $sheet->setCellValueByColumnAndRow(19, 11, "      ");
        $sheet->setCellValueByColumnAndRow(20, 11, "      ");
        $sheet->setCellValueByColumnAndRow(21, 11, "      ");
        $sheet->setCellValueByColumnAndRow(22, 11, "      ");
        $sheet->setCellValueByColumnAndRow(23, 11, "      ");
        $sheet->setCellValueByColumnAndRow(24, 11, "      ");
        $sheet->setCellValueByColumnAndRow(25, 11, "Calif");
        $sheet->setCellValueByColumnAndRow(26, 11, "Falt");

        $fila = 12;
        $total = 1;
        foreach ($info_reporte as $key => $alumno) {
            $sheet->setCellValue("A{$fila}", $total++);
            $sheet->setCellValue("B{$fila}", $alumno['clavePago']);
            $sheet->setCellValueExplicit("C{$fila}", $alumno['perApellido1'] . ' ' . $alumno['perApellido2'] . ' ' . $alumno['perNombre'], DataType::TYPE_STRING);
            // $sheet->setCellValue("D{$fila}", $alumno['bachiller_inscritos']->count());
            // $sheet->setCellValue("E{$fila}", $alumno['bachiller_preinscritos']);
            $fila++;
        }

        $writer = new Xlsx($spreadsheet);
        try {
            $writer->save(storage_path("Bachiller_lista_clasica.xlsx"));
        } catch (Exception $e) {
            alert('Ha ocurrido un problema', $e->getMessage(), 'error')->showConfirmButton();
            return back()->withInput();
        }

        return response()->download(storage_path("Bachiller_lista_clasica.xlsx"));
    }
}
