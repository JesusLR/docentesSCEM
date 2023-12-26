<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Curso;
use App\Http\Models\Pago;
use App\Http\Models\Ubicacion;
use App\clases\cgts\MetodosCgt;
use App\Http\Models\Departamento;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class SecundariaInscritosPreinscritosController extends Controller
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

    public function reporte()
    {
        $tiposIngreso = array(
            'NI' => 'NUEVO INGRESO',
            'PI' => 'PRIMER INGRESO',
            'RO' => 'REPETIDOR',
            'RI' => 'REINSCRIPCIÓN',
            'RE' => 'REINGRESO',
            'EQ' => 'REVALIDACIÓN',
            'OY' => 'OYENTE',
            'XX' => 'OTRO',
            '' => 'TODOS'
        );
        $alumnos_curso = array(
            'P' => 'PREINSCRITOS',
            'R' => 'INSCRITOS',
            'C' => 'CONDICIONADO',
            'A' => 'CONDICIONADO 2',
            '' => 'TODOS',
        );
        $alumnos_estado = array(
            'N' => 'NUEVO INGRESO',
            'R' => 'REINGRESO',
            '' => 'TODOS',
        );
        $tipo_reporte = array(
            'R' => 'SOLO RAYAS PARA FIRMA',
            'N' => 'NORMAL(SE IMPRIMEN TODOS LOS DATOS)',
            // '' => 'SELECCIONE UNA OPCIÓN',
        );
        $orden_reporte = array(
            'N' => 'NOMBRE(EMPEZANDO POR APELLIDOS)',
            'F' => 'FECHA DE INSCRIPCIÓN(SE ACTIVA SÓLO SI ELIGE TIPO DE REPORTE NORMAL)',
            // '' => 'SELECCIONE UNA OPCIÓN',
        );
        $espaciado = array(
            '1' => 'SENCILLO',
            '2' => 'DOBLE',
            // '' => 'SELECCIONE UNA OPCIÓN',
        );

        $ubicaciones = Ubicacion::where('ubiClave', '<>', '000')->get();

        $departamentos = Departamento::where('depClave', '=', 'SEC')->get();

        //dd($departamentos);

        return view('secundaria.reportes.inscritos_preinscritos.create',compact('tiposIngreso',
            'alumnos_curso','alumnos_estado','tipo_reporte','orden_reporte','espaciado', 'ubicaciones', 'departamentos'));
    }

    public function imprimir(Request $request)
    {
        $cursos = Curso::with([
            'cgt' => function($query){
                $query->select('id', 'plan_id', 'cgtGradoSemestre', 'cgtGrupo')
                ->with(['plan' => function($query){
                    $query->select('id', 'planClave', 'programa_id')
                    ->with(['programa' => function($query){
                        $query->select('id', 'escuela_id','progClave', 'progNombre')
                        ->with(['escuela' => function($query){
                            $query->select('id', 'departamento_id', 'escClave', 'escNombre')
                            ->with(['departamento' => function($query){
                                $query->select('id', 'ubicacion_id', 'depClave', 'depNombre')
                                ->with(['ubicacion' => function($query){
                                    $query->select('id', 'ubiClave', 'ubiNombre');
                                }]);
                            }]);
                        }]);
                    }]);
                }]);
            },
            'periodo' => function($query){
                $query->select('id', 'perNumero', 'perAnio',  'perAnioPago', 'perFechaInicial', 'perFechaFinal');
            }
        ])
        ->whereHas('periodo', function($query) use($request){
            $query->where('periodo_id', $request->periodo_id);
        })
        ->whereHas('alumno', function($query) use($request){
            $query->where('aluClave', 'like', '%'.$request->input('aluClave').'%')
            ->where('aluEstado', 'like', '%'.$request->input('aluEstado').'%');
        })
        ->whereHas('alumno.persona', function($query) use($request){
            $query->where('perApellido1', 'like', '%'.$request->input('perApellido1').'%')
                ->where('perApellido2', 'like', '%'.$request->input('perApellido2').'%')
                ->where('perNombre', 'like', '%'.$request->input('perNombre').'%');
        })
        ->whereHas('cgt.plan.programa.escuela.departamento.ubicacion', function($query) use($request){
            $query->where('departamento_id', $request->departamento_id);
            if($request->escuela_id) {
                $query->where('escuela_id', $request->escuela_id);
            }
            if($request->programa_id) {
                $query->where('programa_id', $request->programa_id);
            }
            if($request->plan_id) {
                $query->where('plan_id', $request->plan_id);
            }
            if($request->cgtGradoSemestre) {
                $query->where('cgtGradoSemestre', $request->cgtGradoSemestre);
            }
            if($request->cgtGrupo) {
                $query->where('cgtGrupo', $request->cgtGrupo);
            }
        })
        ->where(function($query) use ($request) {
            if($request->curTipoIngreso) {
                $query->where('curTipoIngreso', $request->curTipoIngreso);
            }
        })
        ->distinct()
        ->select('cgt_id', 'periodo_id', 'curEstado')
        ->get();

        if($cursos->isEmpty()) {
            alert()->warning('Sin Información', 'No hay datos que coincidan con la información proporcionada. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }

        $cgts = $cursos->sortBy(static function($curso) {
            $cgt = $curso->cgt;
            return MetodosCgt::stringOrden($cgt->cgtGradoSemestre, $cgt->cgtGrupo);
        })->unique("cgt.id");




            // $cgts->get();
        if (count($cgts)) {
            //elegir el título que va a tener el reporte
            switch ($request->input('curEstado')) {
                case 'P':
                    $textoTitulo = "ALUMNOS PREINSCRITOS";
                    break;
                case 'R':
                    $textoTitulo = "ALUMNOS INSCRITOS";
                    break;
                case 'C':
                    $textoTitulo = "ALUMNOS CONDICIONADOS";
                    break;
                case 'A':
                    $textoTitulo = "ALUMNOS CONDICIONADOS";
                    break;
                default:
                    $textoTitulo = "ALUMNOS PREINSCRITOS, INSCRITOS Y CONDICIONADOS";
                    break;
            }

            $pdf = new PDF('P', 'pt', 'Letter');
            $pdf->AliasNbPages();
            $pdf->SetTitle('Relacion de Secundaria Inscritos');
            $pdf->SetFont('Times', '', 9);

            $dibujarLinea = 0;
            $dibujarLineaEncabezado = 1;
            $anchoTituloIzquierdo = 390;
            $altoTitulo = 15;
            $fechaActual = date("d/m/y");
            $horaActual = date("h:i:s");

            $GLOBALS['dibujarLinea'] = $dibujarLinea;
            $GLOBALS['dibujarLineaEncabezado'] = $dibujarLineaEncabezado;
            $GLOBALS['anchoTituloIzquierdo'] = $anchoTituloIzquierdo;
            $GLOBALS['altoTitulo'] = $altoTitulo;
            $GLOBALS['fechaActual'] = $fechaActual;
            $GLOBALS['horaActual'] = $horaActual;
            $GLOBALS['textoTitulo'] = $textoTitulo;

            //valores para celdas de datos
            $altoCelda = 15;
            $anchoNumero = 25;
            $anchoClavePago = 50;
            $anchoCurp = 120;
            $anchoNombre = 230;
            $anchoGrado = 20;
            $anchoGrupo = 20;
            $anchoIngreso = 25;
            $anchoPagoInscripcion = 55;
            $anchoBeca = 0;
            $anchoLineaFirma = 0;

            $GLOBALS['altoCelda'] = $altoCelda;
            $GLOBALS['anchoNumero'] = $anchoNumero;
            $GLOBALS['anchoClavePago'] = $anchoClavePago;
            $GLOBALS['anchoCurp'] = $anchoCurp;
            $GLOBALS['anchoNombre'] = $anchoNombre;
            $GLOBALS['anchoGrado'] = $anchoGrado;
            $GLOBALS['anchoGrupo'] = $anchoGrupo;
            $GLOBALS['anchoIngreso'] = $anchoIngreso;
            $GLOBALS['anchoPagoInscripcion'] = $anchoPagoInscripcion;
            $GLOBALS['anchoBeca'] = $anchoBeca;
            $GLOBALS['anchoLineaFirma'] = $anchoLineaFirma;


            $GLOBALS['tipoReporte'] = $request->input('tipoReporte');
            $GLOBALS["saltoLinea"] = $altoCelda * $request->input('espaciadoLinea');



            //textos de encabezados
            $encNumero = "Num";
            $encClavePago = "Cve Pago";
            $encCurp = "C.U.R.P";
            $encNombre = "Nombre del alumno";
            $encGrado = "Gra";
            $encGrupo = "Gru";
            $encIngreso = "Ingr";
            $encPagoInscripcion = utf8_decode("Pagó Inscr.");
            $encBeca = "Beca";
            $encLineaFirma = "Firma";


            foreach ($cgts as $cgt) {
                //periodo
                $periodo = sprintf("Período: %s - %s (%s %s)", $cgt->periodo->perFechaInicial, $cgt->periodo->perFechaFinal, $cgt->periodo->perNumero, $cgt->periodo->perAnio);
                $periodo = utf8_decode($periodo);
                //grado y grupo
                $gradoGrupo = sprintf("Grado: %s   Grupo: %s", $cgt->cgt->cgtGradoSemestre, $cgt->cgt->cgtGrupo);
                $gradoGrupo = utf8_decode($gradoGrupo);
                //ubicación
                $ubicacion = sprintf("Ubicación: %s %s", $cgt->cgt->plan->programa->escuela->departamento->ubicacion->ubiClave, $cgt->cgt->plan->programa->escuela->departamento->ubicacion->ubiNombre);
                $ubicacion = utf8_decode($ubicacion);
                //Programa y plan
                $programaPlan = sprintf("Nivel/Carrera: %s(%s) %s", $cgt->cgt->plan->programa->progClave, $cgt->cgt->plan->planClave, $cgt->cgt->plan->programa->progNombre);
                $programaPlan = utf8_decode($programaPlan);


                $GLOBALS['periodo'] = $periodo;
                $GLOBALS['gradoGrupo'] = $gradoGrupo;
                $GLOBALS['ubicacion'] = $ubicacion;
                $GLOBALS['programaPlan'] = $programaPlan;

                $pdf->SetMargins(18, 18, 18);
                $pdf->AddPage();



                // dd($cgt);

                //consulta para cada alumno del grupo
                $cgt_id = $cgt->cgt_id;
                $alumno = Curso::select('cursos.id as curso', 'cursos.curTipoIngreso', 'cursos.curTipoBeca',
                    'cursos.curPorcentajeBeca', 'alumnos.aluClave', 'alumnos.aluEstado', 'personas.perApellido1',
                    'personas.perApellido2', 'personas.perNombre', 'personas.perCurp', 'cursos.curEstado')
                    ->leftJoin('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                    ->leftJoin('personas', 'alumnos.persona_id', '=', 'personas.id')
                    ->where('cursos.cgt_id', '=', $cgt_id)
                    ->where('cursos.curEstado', 'like', '%'.$request->input('curEstado').'%')
                    ->where('personas.perApellido1', 'like', '%'.$request->input('perApellido1').'%')
                    ->where('personas.perApellido2', 'like', '%'.$request->input('perApellido2').'%')
                    ->where('personas.perNombre', 'like', '%'.$request->input('perNombre').'%')
                    ->where('alumnos.aluClave', 'like', '%'.$request->input('aluClave').'%')
                    ->where('alumnos.aluEstado', 'like', '%'.$request->input('aluEstado').'%');


                if ($request->input('ordenReporte') == 'N') {
                    $alumno = $alumno->orderBy('perApellido1', 'asc')
                        ->orderBy('perApellido2', 'asc')
                        ->orderBy('perNombre', 'asc');
                }

                if($request->curTipoIngreso) {
                    $alumno = $alumno->where('curTipoIngreso', $request->curTipoIngreso);
                }

                $alumno = $alumno->get();


                if (!$request->curEstado) {
                    $alumno = $alumno->where("curEstado", "!=", "B");
                }

                //imprimir los datos de los alumnos
                $datoNumero = 0;

                foreach ($alumno as $fila) {
                    $datoNumero++;
                    $datoClavePago = $fila->aluClave;
                    $datoClavePago = utf8_decode($datoClavePago);
                    $datoCurp = $fila->perCurp;
                    $datoCurp = utf8_decode($datoCurp);
                    $datoNombre = sprintf("%s %s %s", $fila->perApellido1, $fila->perApellido2, $fila->perNombre);
                    $datoNombre = utf8_decode($datoNombre);
                    $datoGrado = $cgt->cgt->cgtGradoSemestre;
                    $datoGrupo = $cgt->cgt->cgtGrupo;
                    $datoIngreso = sprintf("%s %s", $fila->curTipoIngreso, $fila->aluEstado);

                    $conceptoInscripcion = $cgt->periodo->perNumero == 1 ? '00': '99';
                    $datoPagoInscripcion = Pago::where(["pagClaveAlu" => $fila->aluClave, "pagAnioPer" => $cgt->periodo->perAnioPago, "pagConcPago" => $conceptoInscripcion])->first();
                    if ($datoPagoInscripcion) {
                        $datoPagoInscripcion = Carbon::parse($datoPagoInscripcion->pagFechaPago)->format("d-m-Y");
                    } else {
                        $datoPagoInscripcion = "";
                    }

                    if ($fila->curPorcentajeBeca > 0) {
                        $datoBeca = sprintf("%s %s%%", $fila->curTipoBeca, $fila->curPorcentajeBeca);
                    } else {
                        $datoBeca = "";
                    }

                    $pdf->Cell($anchoNumero, $altoCelda, $datoNumero, $dibujarLinea, 0, 'R');
                    $pdf->Cell($anchoClavePago, $altoCelda, $datoClavePago, $dibujarLinea, 0);
                    $pdf->Cell($anchoCurp, $altoCelda, $datoCurp, $dibujarLinea, 0);
                    $pdf->Cell($anchoNombre, $altoCelda, $datoNombre, $dibujarLinea, 0);
                    $pdf->Cell($anchoGrado, $altoCelda, $datoGrado, $dibujarLinea, 0, 'R');
                    $pdf->Cell($anchoGrupo, $altoCelda, $datoGrupo, $dibujarLinea, 0, 'C');
                    //verificar si se imprime completo o solo líneas
                    if ($GLOBALS['tipoReporte'] == "N") {
                        //imprimir todos los datos
                        $pdf->Cell($anchoIngreso, $altoCelda, $datoIngreso, $dibujarLinea, 0, 'C');
                        $pdf->Cell($anchoPagoInscripcion, $altoCelda, $datoPagoInscripcion, $dibujarLinea, 0, 'C');
                        $pdf->Cell($anchoBeca, $altoCelda, $datoBeca, $dibujarLinea, 0, 'C');
                    }

                    if ($GLOBALS['tipoReporte'] == "R") {


                        $pdf->Cell($anchoLineaFirma, $altoCelda, "", "B", 0);
                    }

                    $pdf->Ln($GLOBALS["saltoLinea"] );
                }
            }
        } else {
            alert()->error('Error...', 'No se encontraron datos')->showConfirmButton();
            return redirect('secundaria_reporte/secundaria_inscrito_preinscrito')->withInput();
        }

        // return response()->json($alumno);
        $pdf->Output();
        exit;
    }

}

class PDF extends Fpdf
{
    function Header()
    {
        $dibujarLinea = $GLOBALS['dibujarLinea'];
        $dibujarLineaEncabezado = $GLOBALS['dibujarLineaEncabezado'];
        $anchoTituloIzquierdo   = $GLOBALS['anchoTituloIzquierdo'];
        $altoTitulo   = $GLOBALS['altoTitulo'];
        $textoTitulo  = $GLOBALS['textoTitulo'];
        $fechaActual  = $GLOBALS['fechaActual'];
        $horaActual   = $GLOBALS['horaActual'];
        $periodo      = $GLOBALS['periodo'];
        $programaPlan = $GLOBALS['programaPlan'];
        $gradoGrupo   = $GLOBALS['gradoGrupo'];
        $ubicacion    = $GLOBALS['ubicacion'];
        $altoCelda    = $GLOBALS['altoCelda'];
        $anchoNumero  = $GLOBALS['anchoNumero'];
        $anchoClavePago = $GLOBALS['anchoClavePago'];
        $anchoCurp      = $GLOBALS['anchoCurp'];
        $anchoNombre    = $GLOBALS['anchoNombre'];
        $anchoGrado     = $GLOBALS['anchoGrado'];
        $anchoGrupo     = $GLOBALS['anchoGrupo'];
        $anchoIngreso   = $GLOBALS['anchoIngreso'];
        $anchoPagoInscripcion = $GLOBALS['anchoPagoInscripcion'];
        $anchoBeca  = $GLOBALS['anchoBeca'];


        $tipoReporte = $GLOBALS["tipoReporte"];

        //textos de encabezados
        $encNumero = "Num";
        $encClavePago = "Cve Pago";
        $encCurp = "C.U.R.P";
        $encNombre = "Nombre del alumno";
        $encGrado = "Gra";
        $encGrupo = "Gru";
        $encIngreso = "Ingr";
        $encPagoInscripcion = utf8_decode("Pagó Inscr.");
        $encBeca = "Beca";
        $encLineaFirma = "Firma";

        $this->Cell($anchoTituloIzquierdo, $altoTitulo, 'ESCUELA MODELO S.C.P.', $dibujarLinea, 0);
        $this->Cell(0, $altoTitulo, $fechaActual, $dibujarLinea, 0, 'R');
        $this->Ln($altoTitulo);
        $this->Cell($anchoTituloIzquierdo, $altoTitulo, $textoTitulo, $dibujarLinea, 0);
        $this->Cell(0, $altoTitulo, $horaActual, $dibujarLinea, 0, 'R');
        $this->Ln($altoTitulo);
        $this->Cell(0, $altoTitulo, "SecundariaInscritosPreinscritosController.php", $dibujarLinea, 0, 'R');
        $this->Ln($altoTitulo);
        $this->Cell($anchoTituloIzquierdo, $altoTitulo, $periodo, $dibujarLinea, 0);
        $this->Ln($altoTitulo);
        $this->Cell($anchoTituloIzquierdo, $altoTitulo, $programaPlan, $dibujarLinea, 0);
        $this->Cell(0, $altoTitulo, $gradoGrupo, $dibujarLinea, 0);
        $this->Ln($altoTitulo);
        $this->Cell(0, $altoTitulo, $ubicacion, $dibujarLinea, 0);
        $this->Ln($altoTitulo);
        $this->Cell($anchoNumero, $altoCelda, $encNumero, $dibujarLineaEncabezado, 0, 'C');
        $this->Cell($anchoClavePago, $altoCelda, $encClavePago, $dibujarLineaEncabezado, 0, 'C');
        $this->Cell($anchoCurp, $altoCelda, $encCurp, $dibujarLineaEncabezado, 0, 'C');
        $this->Cell($anchoNombre, $altoCelda, $encNombre, $dibujarLineaEncabezado, 0, 'C');
        $this->Cell($anchoGrado, $altoCelda, $encGrado, $dibujarLineaEncabezado, 0, 'C');
        $this->Cell($anchoGrupo, $altoCelda, $encGrupo, $dibujarLineaEncabezado, 0, 'C');


        //imprimir todos los datos o solo las líneas para firma
        if ($tipoReporte == "N") {
            //imprimir todos los datos
            $this->Cell($anchoIngreso, $altoCelda, $encIngreso, $dibujarLineaEncabezado, 0);
            $this->Cell($anchoPagoInscripcion, $altoCelda, $encPagoInscripcion, $dibujarLineaEncabezado, 0);
            $this->Cell($anchoBeca, $altoCelda, $encBeca, $dibujarLineaEncabezado, 0);
        }

        if ($tipoReporte == "R") {
            //imprimir sólo líneas para firma
            $this->Cell($GLOBALS['anchoLineaFirma'], $altoCelda, $encLineaFirma, $dibujarLineaEncabezado, 0);
        }

        $this->Ln($GLOBALS["saltoLinea"] );
    }

    function Footer()
    {
        $this->setY(-30);
        $this->setFont('Times', 'I', '9');
        $this->Cell(0, 19, "Pag. ".$this->PageNo()." de {nb}", 0, 0, 'C');
    }
}
