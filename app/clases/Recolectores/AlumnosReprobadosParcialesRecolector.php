<?php
namespace App\clases\Recolectores;

use Illuminate\Support\Collection;

use App\Http\Models\Ubicacion;
use App\Http\Models\Periodo;
use App\Http\Models\Curso;
use App\Http\Models\Inscrito;
use App\Http\Helpers\Utils;
use App\clases\calificaciones\MetodosCalificaciones;
use App\clases\cgts\MetodosCgt;

use RealRashid\SweetAlert\Facades\Alert;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

/**
 * Esta clase recolecta alumnos que han reprobado el parcial especificado en 'etapa_calificacion' 
 * o alguno de los parciales del 'periodo_id' especificado en $parametros.
 * 
 * Fue creada para el Reporte Alumnos Reprobados por Parciales, y para el envío de notificaciones
 * al realizar registro o cambio de calificaciones de grupos (CalificacionController::store)
 * Esta funcionalidad también se debe encontrar implementada y actualizada en el Portal Docente.
 * 
 * - $parametros['etapa_calificacion'] (opcional)
 * Obtendrá alumnos que reprobaron en la etapa especificada. Puede tener cualquiera de los siguientes valores:
 * ('Parcial1', 'Parcial2', 'Parcial3', 'PromedioParciales', 'Ordinario', 'Final')
 * 
 */
class AlumnosReprobadosParcialesRecolector
{
	protected $parametros;
	protected static $calificacionMinima;
	public $reprobados;
	public $nombreArchivoExcel;

	/**
	 * $parametros debe contener las siguientes key => value.
	 * 
	 * aluClave: Modelo Alumno->aluClave.
	 * matClave: Modelo Materia->matClave.
	 * plan_id: Modelo Plan->id.
	 * programa_id: Modelo Programa->id.
	 * escuela_id: Modelo Escuela->id.
	 * periodo_id: Modelo Periodo->id. (Obligatorio)
	 * semestre: int grado deseado.
	 * grupo: string grupo deseado.
	 * etapa_calificacion: string 
	 * 
	 * @param array $parametros
	 */
	public function __construct(array $parametros) {
		$this->parametros = $parametros;
		self::$calificacionMinima = Periodo::findOrFail($parametros['periodo_id'])->departamento->depCalMinAprob;
		$this->reprobados = $this->obtenerReprobados();
	}

	/**
	 * Se ejecuta en el constructor. Si desea acceder a la Collection generada desde otro archivo,
	 * acceda a la variable $this->reprobados, es una instancia de Collection.
	 */
	private function obtenerReprobados()
	{
		$reprobados = new Collection;
		self::buscarCursos($this->parametros)
		->chunk(100, function($cursos) use ($reprobados) {
		    if($cursos->isEmpty())
		        return false;

		    $inscritos = self::buscarInscritos($this->parametros, $cursos)
		    ->get()
		    ->map(static function($inscrito) {
		        return self::info_esencial_inscrito($inscrito);
		    })
		    ->filter(function($inscrito) {
		        return self::esReprobado($inscrito, $this->parametros['etapa_calificacion']);
		    })
		    ->groupBy('curso_id');

		    $cursos->each(static function($curso) use ($reprobados, $inscritos) {
		        $alumno_inscripciones = $inscritos->pull($curso->id);

		        if($alumno_inscripciones instanceof Collection)
		            $reprobados->push(self::info_esencial_curso($curso, $alumno_inscripciones));
		    });
		});

		return $reprobados;
	}

	/**
	 * @param array $parametros
	 */
	private static function buscarCursos($parametros) {

	    return Curso::with(['cgt.plan.programa', 'alumno.persona'])
	    ->where(static function($query) use ($parametros) {
	        $query->where('periodo_id', $parametros['periodo_id']);
	    })
	    ->whereHas('alumno', static function($query) use ($parametros) {
	        if(isset($parametros['aluClave']) && $parametros['aluClave'])
	            $query->where('aluClave', $parametros['aluClave']);
	    })
	    ->whereHas('cgt.plan.programa', static function($query) use ($parametros) {
	        if(isset($parametros['plan_id']) && $parametros['plan_id'])
	            $query->where('plan_id', $parametros['plan_id']);
	        if(isset($parametros['programa_id']) && $parametros['programa_id'])
	            $query->where('programa_id', $parametros['programa_id']);
	        if(isset($parametros['escuela_id']) && $parametros['escuela_id'])
	            $query->where('escuela_id', $parametros['escuela_id']);
	        // if(isset($parametros['semestre']) && $parametros['semestre'])
	        //     $query->where('cgtGradoSemestre', $parametros['semestre']);
	        // if(isset($parametros['grupo']) && $parametros['grupo'])
	        //     $query->where('cgtGrupo', $parametros['grupo']);
	    });
	}

	/**
	 * @param array $parametros
	 * @param Illuminate\Support\Collection $cursos
	 */
	private static function buscarInscritos($parametros, $cursos) {

	    return Inscrito::with(['grupo.materia', 'calificacion'])
	    ->whereIn('curso_id', $cursos->pluck('id'))
	    ->whereHas('grupo.materia', static function($query) use ($parametros) {
	        if(isset($parametros['matClave']) && $parametros['matClave'])
	            $query->where('matClave', $parametros['matClave']);
	        if(isset($parametros['semestre']) && $parametros['semestre'])
	            $query->where('gpoSemestre', $parametros['semestre']);
	        if(isset($parametros['grupo']) && $parametros['grupo'])
	            $query->where('gpoClave', $parametros['grupo']);
	    });
	}

	/**
	 * @param App\Http\Models\Curso $curso
	 * @param Illuminate\Support\Collection $inscripciones
	 */
	private static function info_esencial_curso($curso, $inscripciones) {
	    $alumno = $curso->alumno;
	    $nombreCompleto = $alumno->persona->nombreCompleto(true);
	    $cgt = $curso->cgt;
	    $plan = $cgt->plan;
	    $programa = $plan->programa;
	    $cgt_orden = MetodosCgt::stringOrden($cgt->cgtGradoSemestre, $cgt->cgtGrupo);

	    return [
	        'curso_id' => $curso->id,
	        'aluClave' => $alumno->aluClave,
	        'nombreCompleto' => $nombreCompleto,
	        'progClave' => $programa->progClave,
	        'planClave' => $plan->planClave,
	        'semestre' => $cgt->cgtGradoSemestre,
	        'grupo' => $cgt->cgtGrupo,
	        'inscripciones' => $inscripciones,
	        'orden' => $programa->progClave . $plan->planClave . $cgt_orden . $nombreCompleto,
	    ];
	}

	/**
	 * @param App\Http\Models\Inscrito
	 */
	private static function info_esencial_inscrito($inscrito) {
	    $materia = $inscrito->grupo->materia;
	    $calificacion = $inscrito->calificacion;
	    $esNumerica = $materia->esNumerica();

	    $calificacionParcial1  = $esNumerica ? MetodosCalificaciones::definirCalificacion($calificacion, $materia, 'P1') : null;
	    $calificacionParcial2  = $esNumerica ? MetodosCalificaciones::definirCalificacion($calificacion, $materia, 'P2') : null;
	    $calificacionParcial3  = $esNumerica ? MetodosCalificaciones::definirCalificacion($calificacion, $materia, 'P3') : null;
	    $promedioParciales     = $esNumerica ? MetodosCalificaciones::definirCalificacion($calificacion, $materia, 'PP') : null;
	    $calificacionOrdinario = $esNumerica ? MetodosCalificaciones::definirCalificacion($calificacion, $materia, 'OR') : null;
	    $calificacionFinal     = $esNumerica ? MetodosCalificaciones::definirCalificacion($calificacion, $materia, 'CF') : null;

	    return [
	        'curso_id' => $inscrito->curso_id,
	        'matClave' => $materia->matClave,
	        'matNombreOficial' => $materia->matNombreOficial,
	        'calificacionParcial1' => $calificacionParcial1,
	        'calificacionParcial2' => $calificacionParcial2,
	        'calificacionParcial3' => $calificacionParcial3,
	        'promedioParciales' => $promedioParciales,
	        'calificacionOrdinario' => $calificacionOrdinario,
	        'calificacionFinal' => $calificacionFinal,
	        'reprobadoParcial1' => MetodosCalificaciones::es_reprobada($calificacionParcial1, self::$calificacionMinima),
	        'reprobadoParcial2' => MetodosCalificaciones::es_reprobada($calificacionParcial2, self::$calificacionMinima),
	        'reprobadoParcial3' => MetodosCalificaciones::es_reprobada($calificacionParcial3, self::$calificacionMinima),
	        'reprobadoPromedioParciales' => MetodosCalificaciones::es_reprobada($promedioParciales, self::$calificacionMinima),
	        'reprobadoOrdinario' => MetodosCalificaciones::es_reprobada($calificacionOrdinario, self::$calificacionMinima),
	        'reprobadoFinal' => MetodosCalificaciones::es_reprobada($calificacionFinal, self::$calificacionMinima),
	    ];
	}

	/**
	 * @param Collection | array $inscrito
	 * @param string $etapa_calificacion
	 */
	private static function esReprobado($inscrito, $etapa_calificacion = null) {

	    if($etapa_calificacion)
	        return $inscrito["reprobado{$etapa_calificacion}"];

	    return (
	        $inscrito["reprobadoParcial1"]
	        || $inscrito["reprobadoParcial2"]
	        || $inscrito["reprobadoParcial3"]
	        || $inscrito["reprobadoPromedioParciales"]
	        || $inscrito["reprobadoOrdinario"]
	        || $inscrito["reprobadoFinal"]
	    );
	}

	/**
	 * Genera y guarda en storage la información en formato Excel, NO LO DESCARGA.
	 * Es opcional proporcionar el nombre con el que se desea que se guarde el archivo.
	 * 
	 * @param string $nombreArchivo
	 */
	public function generarExcel(string $nombreArchivo = null) {

		$nombrePorDefecto = 'AlumnosReprobadosParciales' . Carbon::now('America/Merida')->format('YmdHisu') . '.xlsx';
		
	    $this->nombreArchivoExcel = $nombreArchivo ?: $nombrePorDefecto;
	    $alumnosAgrupadosPorPrograma = $this->reprobados->groupBy('progClave')->sortKeys();
	    $info_reporte = self::obtenerInfoReporte($this->parametros);

	    $spreadsheet = new Spreadsheet();
	    foreach($alumnosAgrupadosPorPrograma as $key => $alumnos_programa) {
	        $newSheet = new Worksheet($spreadsheet, $key);
	        $spreadsheet->addSheet($newSheet);
	        $sheet = $spreadsheet->getSheetByName($key);
	        self::llenarDatosPorTab($sheet, $info_reporte, $alumnos_programa);
	    }
	    $spreadsheet->removeSheetByIndex(0); # Borrar la primer tab (está vacía).

	    $writer = new Xlsx($spreadsheet);
	    try {
	        $writer->save(storage_path($this->nombreArchivoExcel));
	    } catch (Exception $e) {
	        throw $e;
	    }

	    return $this;
	}

	/**
	 * Si desea utilizar esta función para descargar la información, primero debe ejecutar
	 * generarExcel() para que genere el archivo e inicialice el nombre del mismo.
	 */
	public function descargarExcel() {

		return response()->download(storage_path($this->nombreArchivoExcel));
	}

	/**
	 * @param array $parametros
	 */
	private static function obtenerInfoReporte($parametros) {
	    $periodo = Periodo::with('departamento.ubicacion')->findOrFail($parametros['periodo_id']);
	    $departamento = $periodo->departamento;
	    $fechas_periodo = Utils::fecha_string($periodo->perFechaInicial, 'mesCorto') . ' - ' . Utils::fecha_string($periodo->perFechaFinal, 'mesCorto');

	    return [
	        'ubicacion' => $departamento->ubicacion,
	        'departamento' => $departamento,
	        'periodo_descripcion' => "{$fechas_periodo} ({$periodo->perNumero}/{$periodo->perAnio})",
	    ];
	}

	/**
	 * @param array $info_reporte
	 * @param Illuminate\Support\Collection
	 */
	private function llenarDatosPorTab($sheet, $info_reporte, $alumnos) {

	    $sheet->getColumnDimension('A')->setAutoSize(true);
	    $sheet->getColumnDimension('B')->setAutoSize(true);
	    $sheet->getColumnDimension('C')->setAutoSize(true);
	    $sheet->getColumnDimension('D')->setAutoSize(true);
	    $sheet->getColumnDimension('E')->setAutoSize(true);
	    $sheet->getColumnDimension('F')->setAutoSize(true);
	    $sheet->getColumnDimension('G')->setAutoSize(true);
	    $sheet->getColumnDimension('H')->setAutoSize(true);
	    $sheet->getColumnDimension('I')->setAutoSize(true);
	    $sheet->getColumnDimension('J')->setAutoSize(true);
	    $sheet->getColumnDimension('K')->setAutoSize(true);
	    $sheet->getColumnDimension('L')->setAutoSize(true);
	    $sheet->getColumnDimension('M')->setAutoSize(true);
	    $sheet->getColumnDimension('N')->setAutoSize(true);
	    $sheet->mergeCells("A1:N1");
	    $sheet->getStyle('A1')->getFont()->setBold(true);
	    $sheet->setCellValue('A1', "{$info_reporte['ubicacion']->ubiClave} - {$info_reporte['departamento']->depClave} - {$info_reporte['periodo_descripcion']}");
	    $sheet->getStyle("A2:N2")->getFont()->setBold(true);

	    $sheet->setCellValueByColumnAndRow(1, 2, "Programa");
	    $sheet->setCellValueByColumnAndRow(2, 2, "Plan");
	    $sheet->setCellValueByColumnAndRow(3, 2, "Grado");
	    $sheet->setCellValueByColumnAndRow(4, 2, "Grupo");
	    $sheet->setCellValueByColumnAndRow(5, 2, "Clave Pago");
	    $sheet->setCellValueByColumnAndRow(6, 2, "Nombre del alumno");
	    $sheet->setCellValueByColumnAndRow(7, 2, "Clave materia");
	    $sheet->setCellValueByColumnAndRow(8, 2, "Nombre materia");
	    $sheet->setCellValueByColumnAndRow(9, 2, "Parcial 1");
	    $sheet->setCellValueByColumnAndRow(10, 2, "Parcial 2");
	    $sheet->setCellValueByColumnAndRow(11, 2, "Parcial 3");
	    $sheet->setCellValueByColumnAndRow(12, 2, "Promedio Parciales");
	    $sheet->setCellValueByColumnAndRow(13, 2, "Ordinario");
	    $sheet->setCellValueByColumnAndRow(14, 2, "Final");

	    $fila = 3;
	    foreach($alumnos->sortBy('orden') as $alumno) {
	        foreach($alumno['inscripciones'] as $inscripcion) {    
	            $sheet->setCellValue("A{$fila}", $alumno['progClave']);
	            $sheet->setCellValue("B{$fila}", $alumno['planClave']);
	            $sheet->setCellValueExplicit("C{$fila}", $alumno['semestre'], DataType::TYPE_STRING);
	            $sheet->setCellValueExplicit("D{$fila}", $alumno['grupo'], DataType::TYPE_STRING);
	            $sheet->setCellValueExplicit("E{$fila}", $alumno['aluClave'], DataType::TYPE_STRING);
	            $sheet->setCellValue("F{$fila}", $alumno['nombreCompleto']);
	            $sheet->setCellValueExplicit("G{$fila}", $inscripcion['matClave'], DataType::TYPE_STRING);
	            $sheet->setCellValue("H{$fila}", $inscripcion['matNombreOficial']);
	            $sheet->setCellValue("I{$fila}", $inscripcion['calificacionParcial1']);
	            $sheet->setCellValue("J{$fila}", $inscripcion['calificacionParcial2']);
	            $sheet->setCellValue("K{$fila}", $inscripcion['calificacionParcial3']);
	            $sheet->setCellValue("L{$fila}", $inscripcion['promedioParciales']);
	            $sheet->setCellValue("M{$fila}", $inscripcion['calificacionOrdinario']);
	            $sheet->setCellValue("N{$fila}", $inscripcion['calificacionFinal']);
	            $fila++;
	        }
	        $fila++;
	    }
	}

}