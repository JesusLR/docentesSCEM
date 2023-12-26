<?php

namespace App\Http\Helpers;

use App\Http\Models\Curso;
use App\Http\Models\Alumno;
use App\Http\Models\Cuota;


class GenerarReferencia{

    //conceptos de pagos
    private $conceptos = array(
    "00" => "Inscripción Enero",
    "01" => "Septiembre",
    "02" => "Octubre",
    "03" => "Noviembre",
    "04" => "Diciembre",
    "05" => "Enero",
    "06" => "Febrero",
    "07" => "Marzo",
    "08" => "Abril",
    "09" => "Mayo",
    "10" => "Junio",
    "99" => "Inscripción Agosto",
    );

    private $meses = array(
    "01" => "09",
    "02" => "10",
    "03" => "11",
    "04" => "12",
    "05" => "01",
    "06" => "02",
    "07" => "03",
    "08" => "04",
    "09" => "05",
    "10" => "06",
    "11" => "07",
    "12" => "08",
    );

    private $mesIngles = array(
    "01" => "January",
    "02" => "February",
    "03" => "March",
    "04" => "April",
    "05" => "May",
    "06" => "June",
    "07" => "July",
    "08" => "August",
    "09" => "September",
    "10" => "October",
    "11" => "November",
    "12" => "December",
    );

    //relación de nombres cortos de los meses
    private $mesCorto = array(
    "01" => "Ene",
    "02" => "Feb",
    "03" => "Mar",
    "04" => "Abr",
    "05" => "May",
    "06" => "Jun",
    "07" => "Jul",
    "08" => "Ago",
    "09" => "Sep",
    "10" => "Oct",
    "11" => "Nov",
    "12" => "Dic",
    );

    private $conceptoInicial = array(
    "00",
    "01",
    "02",
    "03",
    "04",
    "05",
    "06",
    "07",
    "08",
    "09",
    "10",
    "99",
    );

    //Importes de pagos
    private $importes = array(
    "BAC" => "3575.00",
    "SUP" => "4700.00"
    );

    //conceptos que son de inscripción
    private $concInscripcion = array(
    "00",
    "99",
    );

    private $prontoPago = 225;
    private $recargo = 150;

    public static function crear($concepto, $fecha, $importe) {
        //separar fecha
        $arrayDate = explode('-',$fecha);
        $dia = $arrayDate[2];
        $mes = $arrayDate[1];
        $anio = $arrayDate[0];
        //valores fijos para concentrado de importe
        $fijosImporte = array (7, 3, 1);
        $fijosVerifica = array (11, 13, 17, 19, 23);

        //concentrado de fecha
        $conAnio = ($anio - 2014) * 372;
        $conMes = ($mes - 1) * 31;
        $conDia = $dia -1;
        $conFecha = $conAnio + $conMes + $conDia;
        $conFecha = sprintf ("%04d", $conFecha);

        //concentrado de importe
        $importeSeparado = explode (".", $importe);
        $importeSinPunto = $importeSeparado[0].$importeSeparado[1];
        $arregloImporte = str_split ($importeSinPunto);
        $arregloImporte = array_reverse ($arregloImporte);
        $conImporte = 0;
        foreach ($arregloImporte as $k => $v) {
          $conImporte += $v * $fijosImporte[$k % 3];
        }
        $conImporte = $conImporte % 10;

        //resultado final
        $referencia = $concepto . $conFecha . $conImporte . 2; //el 2 al final es fijo
        $arregloReferencia = str_split ($referencia);
        $arregloReferencia = array_reverse ($arregloReferencia);
        $verificador = 0;
        foreach ($arregloReferencia as $k => $v) {
          $verificador += $v * $fijosVerifica[$k % 5];
        }
        $verificador = $verificador % 97;
        $verificador++;
        $verificador = sprintf ("%02d", $verificador);
        $referencia .= $verificador;
        return $referencia;
      }

      public static function obtenerFecha($fecha = "now") {
        //sumarle 7 días a la fecha
        $fecha = strtotime($fecha);
        $fechaFinal = strtotime ("+7 days", $fecha);

        //verificar quincena
        $diaActual = date ("d", $fecha);
        $diaFinal = date ("d", $fechaFinal);
        if ($diaActual <= 15) {
          if ($diaFinal > 15) {
            $fechaFinal = strtotime ("first day of this month", $fecha);
            $fechaFinal = strtotime ("+14 days", $fechaFinal);
          }
        } else {
          if ($diaFinal < $diaActual) {
            $fechaFinal = strtotime ("last day of this month", $fecha);
          }
        }

        //ya el final
        $fechaFinal = date ("Y-m-d", $fechaFinal);
        return $fechaFinal;
      }

      //función para obtener la diferencia entre dos meses
      public static function diferenciaMeses ($fechaInicial, $fechaFinal) {
        $fechaInicial = strtotime ($fechaInicial);
        $fechaFinal = strtotime ($fechaFinal);
        $anioInicial = date ("Y", $fechaInicial);
        $anioFinal = date ("Y", $fechaFinal);
        $mesInicial = date ("m", $fechaInicial);
        $mesFinal = date ("m", $fechaFinal);
        $mesesInicial = ($anioInicial * 12) + $mesInicial;
        $mesesFinal = ($anioFinal * 12) + $mesFinal;
        $mesesTotal = $mesesFinal - $mesesInicial;
        $mesesTotal = $mesesTotal < 0 ? 0 : $mesesTotal;
        return $mesesTotal;
      }

      //función para obtener el período actual
      //a partir de agosto se considera que se esto en el año actual
      public static function periodoActual() {
        $anio = date ("Y");
        $mes = date ("m");
        $anioPeriodo = $mes < "08" ? $anio - 1 : $anio;
      }

      public static function generarImportes($curso, $concepto, $cuoAnio, $fechaVencimiento)
      {
          $tipoPagoTabla = [
              "N" => "cuoImporteMensualidad10",
              "A" => "cuoImporteMensualidad10",
              "O" => "cuoImporteMensualidad11",
              "D" => "cuoImporteMensualidad12",
          ];

          $conceptoMes = [
              1 => 9,
              2 => 10,
              3 => 11,
              4 => 12,
              5 => 1,
              6 => 2,
              7 => 3,
              8 => 4,
              9 => 5,
              10 => 6,
              11 => 7,
              12 => 8,
          ];

          $tiposBecaAnual = ['S', 'Y', 'J'];


          //datos individuales de fecha de vencimiento
          $arregloVencimiento = explode('-', $fechaVencimiento);
          $anioVencimiento = (int)$arregloVencimiento[0];
          $mesVencimiento = (int)$arregloVencimiento[1];
          $diaVencimiento = (int)$arregloVencimiento[2];

          //datos individuales de mes concepto
          $diaConcepto = 1;
          $mesConcepto = $conceptoMes[(int)$concepto];
          $anioConcepto = $cuoAnio;
          if ($concepto > 4) {
              $anioConcepto++;
          }
          $fechaConcepto = "$anioConcepto-$mesConcepto-$diaConcepto";

          //día de vencimiento para pronto pago
          $diaLimiteProntoPago = 15;
          if ($concepto == "04") {
              $diaLimiteProntoPago = 17;
          }


          //datos del Alumno y método de pago
          $curso = Curso::where([['id', $curso]])->first();

          $dep_id = $curso->cgt->plan->programa->escuela->departamento->id;
          $esc_id = $curso->cgt->plan->programa->escuela->id;
          $prog_id = $curso->cgt->plan->programa->id;
          $cuoAnioGeneracion = $curso->curAnioCuotas;
          $periodoCurso = $curso->cgt->periodo->perNumero;
          $anioCurso = $curso->cgt->periodo->perAnio;

          if ($cuoAnioGeneracion == "") {
            $cuoAnioGeneracion = $anioCurso;
            if ($periodoCurso == 1) {
              $cuoAnioGeneracion--;
            }
          }

          $curPlanPago = $curso->curPlanPago;
          $tipoBeca = $curso->curTipoBeca;

          //cuota del año actual
          $tipoCuotaProvisional = "P";
          $idProvisional = $prog_id;
          $cuentaCuotaActual = Cuota::where([['cuoTipo', 'P'], ['dep_esc_prog_id', $prog_id], ['cuoAnio', $cuoAnio]])->count();
          if ($cuentaCuotaActual == 0) {
              $tipoCuotaProvisional = "E";
              $idProvisional = $esc_id;
              $cuentaCuotaActual = Cuota::where([['cuoTipo', 'E'], ['dep_esc_prog_id', $esc_id], ['cuoAnio', $cuoAnio]])->count();
              if ($cuentaCuotaActual == 0) {
                  $tipoCuotaProvisional = "D";
                  $idProvisional = $dep_id;
              }
          }
          $cuotaActualObjeto = Cuota::where([['cuoTipo', $tipoCuotaProvisional], ['dep_esc_prog_id', $idProvisional], ['cuoAnio', $cuoAnio]])->first();
          $cuotaActual = $cuotaActualObjeto->toArray();

          //cuota generacional
          $tipoCuotaProvisional = "P";
          $idProvisional = $prog_id;
          $cuentaCuotaActual = Cuota::where([['cuoTipo', 'P'], ['dep_esc_prog_id', $prog_id], ['cuoAnio', $cuoAnioGeneracion]])->count();
          if ($cuentaCuotaActual == 0) {
              $tipoCuotaProvisional = "E";
              $idProvisional = $esc_id;
              $cuentaCuotaActual = Cuota::where([['cuoTipo', 'E'], ['dep_esc_prog_id', $esc_id], ['cuoAnio', $cuoAnioGeneracion]])->count();
              if ($cuentaCuotaActual == 0) {
                  $tipoCuotaProvisional = "D";
                  $idProvisional = $dep_id;
              }
          }
          $cuotaGeneracionalObjeto = Cuota::where([['cuoTipo', $tipoCuotaProvisional], ['dep_esc_prog_id', $idProvisional], ['cuoAnio', $cuoAnioGeneracion]])->first();
          $datos = "$tipoCuotaProvisional $idProvisional $cuoAnioGeneracion";
          $cuotaGeneracional = $cuotaGeneracionalObjeto->toArray();

          //importe mensual generacional
          $mensualidad = $cuotaGeneracional[$tipoPagoTabla[$curPlanPago]];

          

          //sacar pronto pago
          $prontoPago = $cuotaGeneracionalObjeto->cuoImporteProntoPago;

          //sacar porcentaje de beca
          $becaDecimal = 0;
          if (($curPorcentajeBeca = trim($curso->curPorcentajeBeca)) != "") {
              $becaDecimal = $curPorcentajeBeca / 100;
          }

          //quitar la beca para los pagos de meses del periodo enero-agosto

          if (in_array($tipoBeca, $tiposBecaAnual)) {
              if (((int)$concepto > 5) && ($periodoCurso == 3)) {
                  $becaDecimal = 0;
              }
          }

          //cambiar el dia de pronto pago si tiene uno personal
          if (($curDiasProntoPago = $curso->curDiasProntoPago) != "") {
              $diaLimiteProntoPago = (int)$curDiasProntoPago;
          }

          //quitar los descuentos de beca, pronto pago y cuota generacional cuando el día es mayor a 15
          if ($diaVencimiento > $diaLimiteProntoPago ) {
              $becaDecimal = 0;
              $prontoPago = 0;
              $mensualidad = $cuotaActual[$tipoPagoTabla[$curPlanPago]];
          }

          //calcular los meses de vencimiento
          $dateVencimiento = new \DateTime($fechaVencimiento);
          $dateConcepto = new \DateTime($fechaConcepto);
          $diferenciaFechas = date_diff($dateVencimiento, $dateConcepto);
          $diferenciaMeses = $diferenciaFechas->m;

          if ($diferenciaMeses > 0){
              $becaDecimal = 0;
              $prontoPago = 0;
              $mensualidad = $cuotaActual[$tipoPagoTabla[$curPlanPago]];
          }

          $cuoImporteVencimiento = $cuotaActualObjeto->cuoImporteVencimiento;
          $inscripcion = $cuotaActualObjeto->cuoImporteInscripcion3;

          $importeVencimiento = $cuoImporteVencimiento;

          //asignar cuotas personales
          if (($curImporteInscripcion = $curso->curImporteInscripcion) != ""){
              $inscripcion = $curImporteInscripcion;
          }

          if (($curImporteMensualidad = $curso->curImporteMensualidad) != "") {
              $mensualidad = $curImporteMensualidad;
          }

          if (($curImporteDescuento = $curso->curImporteDescuento) != "") {
              $prontoPago = $curImporteDescuento;
          }

          if (($curImporteVencimiento = $curso->curImporteVencimiento) != "") {
              $importeVencimiento = $curImporteVencimiento;
          }
          
          //calcular prorrateo para plan de anticipo crédito.
          //el importe de la inscripcion siempre es del año actual, no del generacional
          $inscripcionProrrateada = 0;
          if ($curPlanPago == "A") {
              $inscripcionProrrateada = $inscripcion / 10;
          }



          $descuentoImporte = $mensualidad * $becaDecimal;
          $descuentoProntoPago = $prontoPago * $becaDecimal;
          $recargo = $diferenciaMeses*$importeVencimiento;

          $mensualidad = $mensualidad;
          $prontoPago = $prontoPago - $descuentoProntoPago;

          $importeTotal = $mensualidad + $recargo + $inscripcionProrrateada - $prontoPago - $descuentoImporte;
          $importeTotalDecimal = number_format($importeTotal, 2, ".", "");


        $importes = [
            'mensualidad' => $mensualidad,
            'inscripcionProrrateada' => $inscripcionProrrateada,
            'prontoPago' => $prontoPago,
            'descuentoImporte' => $descuentoImporte,
            'recargo' => $recargo,
            'importeTotalDecimal' => $importeTotalDecimal,
            'diferenciaMeses' => $diferenciaMeses,
            'curPorcentajeBeca' => $curPorcentajeBeca,
        ];



        return $importes;

      }

        
}