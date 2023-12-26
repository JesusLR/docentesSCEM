<?php

namespace App\Http\Controllers\Primaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;



use App\Http\Models\Curso;
use App\Http\Models\Alumno;
use App\Http\Models\Cuota;
use App\Http\Models\ConceptoPago;
use App\Http\Models\Pago;
use App\Http\Models\Ficha;
use App\Http\Models\Periodo;
use App\Http\Models\Referencia;
use App\Http\Models\InscritosEduCont;
use App\Http\Helpers\GenerarReferencia;
use App\Http\Helpers\Utils;
use App\clases\SCEM\Mailer as ScemMailer;
use App\clases\personas\MetodosPersonas;
use Exception;

class PrimariaAplicarPagosController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
      // $this->middleware('permisos:pago',['except' => ['index','show','list']]);
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return View('primaria.aplicar_pagos.show-list');
  }



  /**
   * Show user list.
   *
   */
  public function list()
  {
      /*
      $pagos = Pago::join('alumnos', 'alumnos.aluClave', '=', 'pagos.pagClaveAlu')
          ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
          ->join('cursos', 'alumnos.id', '=', 'cursos.alumno_id')
          ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
          ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
          ->join('planes', 'cgt.plan_id', '=', 'planes.id')
          ->join('programas', 'planes.programa_id', '=', 'programas.id')
          ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
          ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
          ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
          ->select("pagos.id", "pagos.pagClaveAlu", "pagos.pagAnioPer", "pagos.pagConcPago", "pagos.pagFechaPago", "pagos.pagImpPago","pagos.pagFormaAplico",
              "alumnos.persona_id", "personas.perApellido1", "personas.perApellido2", "personas.perNombre")
          ->whereIn('departamentos.depClave', ['PRE'])
          ->whereNull('alumnos.deleted_at')
          ->whereNull('pagos.deleted_at');
      */

    $pagos = Pago::join('alumnos', 'alumnos.aluClave', '=', 'pagos.pagClaveAlu')
      ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
      ->select("pagos.id", "pagos.pagClaveAlu", "pagos.pagAnioPer", "pagos.pagConcPago", "pagos.pagFechaPago", "pagos.pagImpPago","pagos.pagFormaAplico",
        "alumnos.persona_id", "personas.perApellido1", "personas.perApellido2", "personas.perNombre")
        //->whereIn('pagos.pagConcPago', ["99", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"])
        ->whereNull('alumnos.deleted_at')
        ->whereNull('pagos.deleted_at');

    return Datatables::of($pagos)
      ->filterColumn('nombreCompleto', function($query, $keyword) {
        return $query->whereRaw("CONCAT(perNombre, ' ', perApellido1, ' ', perApellido2) like ?", ["%{$keyword}%"]);
      })
      ->addColumn('nombreCompleto', function($query) {
        return $query->perNombre . " " . $query->perApellido1 . " " . $query->perApellido2;
      })
      ->addColumn('pagImpPago', function($query) {
        return "$" . $query->pagImpPago;
      })
      ->addColumn('action', function($query) {

        $btn_editar = null;
        $btn_borrar = null;
        if($query->pagFormaAplico == "M" || in_array(auth()->user()->permiso('registro_cuotas'), ['A', 'B'])) {
          $btn_editar = '<div class="col s1">
                              <a href="primaria/pagos/aplicar_pagos/edit/' . $query->id . '" class="button button--icon js-button js-ripple-effect" title="Editar">
                                  <i class="material-icons">edit</i>
                              </a>
                        </div>';
          $btn_borrar = '<div class="col s1">
                            <form id="delete_' . $query->id . '" action="primaria/pagos/aplicar_pagos/delete/' . $query->id . '" method="POST">
                              <input type="hidden" name="_method" value="DELETE">
                              <input type="hidden" name="_token" value="' . csrf_token() . '">
                              <a href="#" data-id="' . $query->id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                                <i class="material-icons">delete</i>
                              </a>
                            </form>
                        </div>';
        }
        return '<div class="row">'
                    .Utils::btn_show($query->id, 'primaria/pagos/aplicar_pagos/detalle')
                    .$btn_editar
                    .$btn_borrar.
               '</div>';
      })
    ->make(true);
  }


  public function create(Request $request)
  {
    $conceptosPago = ConceptoPago::whereBetween('id',[2,12])
        ->orWhere('id',91)
        ->get();

    //dd($conceptosPago);

    return View('primaria.aplicar_pagos.create', [
      "conceptosPago" => $conceptosPago
    ]);
  }


  public function edit(Request $request)
  {
    $conceptosPago = ConceptoPago::get();

    $pago = Pago::where("id", "=", $request->id)->first();


    return View('primaria.aplicar_pagos.edit', [
      "conceptosPago" => $conceptosPago,
      "pago" => $pago
    ]);
  }

  public function existeAlumnoByClavePago(Request $request) {
    $clavePago = $request->aluClave;
    $existeAlumno = Alumno::where("aluClave", "=", $clavePago)->first();

    return response()->json(["existe" => $existeAlumno]);
  }


  public function update(Request $request)
  {

    $pagAnioPer = (int) $request->pagAnioPer;
    if ($request->pagConcPago == "00") {
      $pagAnioPer = $pagAnioPer + 1;
    }


    $pagFechaPago = Carbon::now()->year . "-" . sprintf('%02d', Carbon::now()->month) . "-" . sprintf('%02d', Carbon::now()->day);
    if ($request->pagFechaPago) {
      $pagFechaPago = $request->pagFechaPago;
    }


    if ($request->pagConcPago == "99")
    {

            $curso = Curso::with("alumno", "periodo")
              ->whereHas('alumno', function($query) use ($request) {
                $query->where("aluClave", "=", $request->pagClaveAlu);
              })
              ->whereHas('periodo', function($query) use ($request, $pagAnioPer) {
                $query->where("perAnioPago", "=", $pagAnioPer);
                $query->where("perNumero", "=", 0);
              })
            ->update(['curEstado' => "R"]);
    }

    $pago = Pago::findOrFail($request->id);
    $cambioFormaAplico = $pago->pagFormaAplico == "A";

    try {
      $pago->update([
        'pagClaveAlu'    => $request->pagClaveAlu,
        'pagAnioPer'     => $request->pagAnioPer,
        'pagConcPago'    => $request->pagConcPago,
        'pagFechaPago'   => $pagFechaPago,
        'pagImpPago'     => $request->pagImpPago,
        'pagRefPago'     => $request->pagRefPago,
        'pagDigVer'      => NULL,
        'pagEstado'      => 'A',
        'pagObservacion' => $request->pagObservacion,
        'pagFormaAplico' => "M",
        'pagComentario'  => $request->pagComentario,
      ]);


        $this->enviarNotificacion('flopezh@modelo.edu.mx', 'Francisco Lopez', $pago, $cambioFormaAplico);

    } catch (QueryException $e) {
      $errorCode = $e->errorInfo[1];
      $errorMessage = $e->errorInfo[2];

      alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
      return back()->withInput();
    }
    alert('Escuela Modelo', 'El pago se ha modificado con éxito','success')->showConfirmButton();
    return back()->withInput();
  }


  public function store(Request $request)
  {

    $pagAnioPer = (int) $request->pagAnioPer;
    if ($request->pagConcPago == "00") {
      $pagAnioPer = $pagAnioPer + 1;
    }


    $pagFechaPago = Carbon::now()->year . "-" . sprintf('%02d', Carbon::now()->month) . "-" . sprintf('%02d', Carbon::now()->day);
    if ($request->pagFechaPago) {
      $pagFechaPago = $request->pagFechaPago;
    }

      if ($request->pagConcPago == "99")
      {

              $curso = Curso::with("alumno", "periodo")
                ->whereHas('alumno', function($query) use ($request) {
                  $query->where("aluClave", "=", $request->pagClaveAlu);
                })
                ->whereHas('periodo', function($query) use ($request, $pagAnioPer) {
                  $query->where("perAnioPago", "=", $pagAnioPer);
                  $query->where("perNumero", "=", 0);
                })
              ->update(['curEstado' => "R"]);

      }


      try {
        Pago::create([
          'pagClaveAlu'    => $request->pagClaveAlu,
          'pagAnioPer'     => $request->pagAnioPer,
          'pagConcPago'    => $request->pagConcPago,
          'pagFechaPago'   => $pagFechaPago,
          'pagImpPago'     => $request->pagImpPago,
          'pagRefPago'     => $request->pagRefPago,
          'pagDigVer'      => NULL,
          'pagEstado'      => 'A',
          'pagObservacion' => $request->pagObservacion,
          'pagFormaAplico' => "M",
          'pagComentario'  => $request->pagComentario,
        ]);


      } catch (QueryException $e) {
        $errorCode = $e->errorInfo[1];
        $errorMessage = $e->errorInfo[2];

        alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        return back()->withInput();
      }
      alert('Escuela Modelo', 'El pago se ha creado con éxito','success')->showConfirmButton();
      return back()->withInput();
  }


  public function detalle(Request $request)
  {
    $pago = Pago::where("id", "=", $request->pagoId)->first();
    // if (Str::contains($pago->pagRefPago, '#'))
    // dd(User::with("empleado.persona")->where("id", "=", $pago->usuario_at)->first());

    $usuario = DB::table("users")->where("users.id", "=", $pago->usuario_at)
      ->leftJoin("empleados", "empleados.id", "=", "users.empleado_id")
      ->leftJoin("personas", "personas.id", "=", "empleados.persona_id")
    ->first();

    $periodos = Periodo::where("perAnioPago", "=", $pago->pagAnioPer)->get();
    $periodoIds = $periodos->map(function ($item, $key) {
      return $item->id;
    })->all();



    $cursos = Curso::with("alumno", "periodo", "cgt.plan.programa")
      ->whereIn("periodo_id", $periodoIds)
      ->whereHas("alumno", function($query) use ($pago) {
        $query->where("aluClave", "=", $pago->pagClaveAlu);
      })
    ->get();


    $incluyeInscripcionEnero = "";
    $alumno = Alumno::with("persona")->where("aluClave", "=", $pago->pagClaveAlu)->first();

    if (Str::contains($pago->pagRefPago, '#')) {


      $pagRefPago = trim(str_replace('#', '', $pago->pagRefPago));

      $referencia = Referencia::where("alumno_id", "=", $alumno->id)
        ->where("refNum", "=", $pagRefPago)->first();

      $incluyeInscripcionEnero = $referencia->refImpAntCred;
    }



    return view("primaria.aplicar_pagos.detalle", [
      "pago" => $pago,
      "cursos" => $cursos,
      "usuario" => $usuario,
      "incluyeInscripcionEnero" => $incluyeInscripcionEnero,
      "alumno" => $alumno
    ]);
  }


  public function verificarExistePago(Request $request)
  {
    $pagos = Pago::join('alumnos', 'alumnos.aluClave', '=', 'pagos.pagClaveAlu')
      ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
      ->select("pagos.id", "pagos.pagClaveAlu", "pagos.pagAnioPer", "pagos.pagConcPago", "pagos.pagFechaPago", "pagos.pagImpPago",
        "alumnos.persona_id", "personas.perApellido1", "personas.perApellido2", "personas.perNombre")
      ->where("pagos.pagClaveAlu", "=", $request->pagClaveAlu)
      ->where("pagos.pagAnioPer", "=", $request->pagAnioPer);



    return Datatables::of($pagos)
      ->filterColumn('nombreCompleto', function($query, $keyword) {
        return $query->whereRaw("CONCAT(perNombre, ' ', perApellido1, ' ', perApellido2) like ?", ["%{$keyword}%"]);
      })
      ->addColumn('nombreCompleto', function($query) {
        return $query->perNombre . " " . $query->perApellido1 . " " . $query->perApellido2;
      })
      ->addColumn('pagImpPago', function($query) {
        return "$" . $query->pagImpPago;
      })
    ->make(true);


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
    $pago = Pago::findOrFail($id);

    if ($pago->delete()) {
      alert('Escuela Modelo', 'El pago se ha eliminado con éxito','success')->showConfirmButton();;
    } else {
      alert()->error('Error...', 'No se pudo eliminar el pago')->showConfirmButton();
    }

    return back();
  }

  /**
  * @param string $correo
  * @param string $nombre_destinatario
  * @param App\Http\Models\Pago $pago
  * @param boolean $cambioFormaAplico
  */
  public function enviarNotificacion($correo, $nombre_destinatario, $pago, $cambioFormaAplico)
  {
    // cambio de correo y contraseña 
    // $info['username_email'] = 'cobranza@modelo.edu.mx';
    // $info['password_email'] = 'cFULf33Qvk';


    // cobranza@unimodelo.com
    // GKxwm788

    $info['username_email'] = 'cobranza@modelo.edu.mx';
    $info['password_email'] = 'l6Ik38NruWSc'; // 'cFULf33Qvk';
    $info['to_email'] = $correo;
    $info['to_name'] = $nombre_destinatario;
    $info['cc_email'] = "";
    $info['subject'] = 'Importante! Modificación de pago';
    $info['body'] = $this->mensaje_modificacion_pago($pago, $cambioFormaAplico);

    try {
      $mail = new ScemMailer($info);
      $mail->agregar_destinatario('cesauri@modelo.edu.mx');
      $mail->agregar_destinatario('gascor@modelo.edu.mx');
      $mail->agregar_destinatario('eail@modelo.edu.mx');
      $mail->agregar_destinatario('luislara@modelo.edu.mx');
      $mail->enviar();
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
  * @param App\Http\Models\Pago $pago
  * @param boolean $cambioFormaAplico
  */
    private function mensaje_modificacion_pago($pago, $cambioFormaAplico) {
      $usuario = auth()->user();
      $nombre_empleado = MetodosPersonas::nombreCompleto($usuario->empleado->persona);
      $alumno = $pago->alumno;
      $nombre_alumno = MetodosPersonas::nombreCompleto($alumno->persona);
      $concepto = $pago->concepto;
      $cambioAutomaticoManual = $cambioFormaAplico ? 'Este pago había sido aplicado de forma automática.' : '';

      return "<p>{$nombre_empleado} ({$usuario->username}) ha modificado el siguiente pago:</p>
      <br>
      <p>
      Fecha de actualización: ".Utils::fecha_string($pago->updated_at)."
      </p>
      <br>
      <p><b>Alumno: </b> {$alumno->aluClave} - {$nombre_alumno}</p>
      <p><b>Concepto de pago: </b> {$concepto->conpClave} - {$concepto->conpNombre}</p>
      <p><b>Importe: </b> ".number_format($pago->pagImpPago, 2, '.', ',')."</p>
      <p><b>Año de curso: </b> {$pago->pagAnioPer}</p>
      <p><b>Fecha de pago: </b> ".Utils::fecha_string($pago->pagFechaPago)."</p>
      <br>
      <p style='text-align: center;'>
      <b>{$cambioAutomaticoManual}</b>
      </p>
      ";
    } # mensaje_modificacion_pago

    /**
    * @param Illuminate\Http\Request $request
    * @param int $pagClaveAlu
    */
    public function getInscripcionesEducacionContinua(Request $request, $pagClaveAlu)
    {
      $programas = InscritosEduCont::with('educacioncontinua.periodo')
      ->whereHas('alumno', static function($query) use ($pagClaveAlu) {
        $query->where('aluClave', $pagClaveAlu);
      })->get()->pluck('educacioncontinua')->unique();

      if($request->ajax())
        return $programas;
    }
}


