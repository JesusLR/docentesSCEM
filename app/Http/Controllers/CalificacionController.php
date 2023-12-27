<?php

namespace App\Http\Controllers;

use URL;
use Auth;
use Debugbar;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Modules;
use App\Models\Curso;
use App\Models\Grupo;
use App\Models\Permission;
use App\Models\Portal_configuracion;
use App\Http\Helpers\Utils;

use Illuminate\Support\Str;
use App\Models\Escuela;
use App\Models\Materia;
use Illuminate\Http\Request;
use App\Models\Inscrito;
use App\Models\Calificacion;
use Illuminate\Support\Facades\DB;
use App\Models\Permission_module_user;
use Illuminate\Database\QueryException;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
// use App\clases\calificaciones\NotificacionReprobadosParciales;
use App\clases\Recolectores\AlumnosReprobadosParcialesRecolector;
use App\clases\calificaciones\MetodosCalificaciones;
use Illuminate\Support\Facades\Log;

class CalificacionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permisos:calificacion',['except' => ['index','show','list']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show user list.
     *
     */
    public function list()
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function agregar ($nivel,$grupo_id)
    {
        //OBTENER GRUPO SELECCIONADO
        $grupo = Grupo::with('plan.programa', 'materia', 'empleado.persona')->find($grupo_id);

        //OBTENER PERMISO DE USUARIO
        $user = Auth::user();
        // $modulo = Modules::where('slug', 'calificacion')->first();

        //OBTENER PROMEDIO PONDERADO EN MATERIA
        $materia = Materia::where('id', $grupo->materia_id)->first();
        $escuela = Escuela::where('id', $grupo->plan->programa->escuela_id)->first();


        $matPorcentajeParcial   = 70;
        $matPorcentajeOrdinario = 30;


        if ($escuela->escPorcExaPar != null) {
            $matPorcentajeParcial = $escuela->escPorcExaPar;
        }
        if ($materia->matPorcentajeParcial != null) {
            $matPorcentajeParcial = $materia->matPorcentajeParcial;
        }


        if ($escuela->escPorcExaOrd != null) {
            $matPorcentajeOrdinario = $escuela->escPorcExaOrd;
        }

        if ($materia->matPorcentajeOrdinario != null) {
            $matPorcentajeOrdinario = $materia->matPorcentajeOrdinario;
        }


    
        $calendarioExamen = DB::table("calendarioexamen")->where("periodo_id", "=", $grupo->periodo_id)->first();
        $fechaActual = Carbon::now()->setTime(0, 0, 0);

        if ($calendarioExamen) {
            $finParcial1  = $fechaActual->gt(($calendarioExamen->calexFinParcial1));
            $finParcial2  = $fechaActual->gt(($calendarioExamen->calexFinParcial2));
            $finParcial3  = $fechaActual->gt(($calendarioExamen->calexFinParcial3));
            $finOrdinario = $fechaActual->gt(($calendarioExamen->calexFinOrdinario));
        } else {
            $finParcial1  = false;
            $finParcial2  = false;
            $finParcial3  = false;
            $finOrdinario = false;
        }

        $calificacionPermitida = "";
        $puedeOrdinario = false;
        $puedeParcial3  = false;
        $puedeParcial2  = false;
        $puedeParcial1  = false;

        if (!$finOrdinario) {//si fecha actual es menor que examen parcial 1
            $calificacionPermitida = "Captura de calificaciones del ordinario se encuentran activas.";
            $puedeOrdinario = true;
            $puedeParcial3  = false;
            $puedeParcial2  = false;
            $puedeParcial1  = false;
        }
        if (!$finParcial3) {//si fecha actual es menor que examen parcial 2
            $calificacionPermitida = "Captura de calificaciones del tercer parcial se encuentran activas.";
            $puedeOrdinario = false;
            $puedeParcial3  = true;
            $puedeParcial2  = false;
            $puedeParcial1  = false;
        }
        if (!$finParcial2) {//si fecha actual es menor que examen parcial 3
            $calificacionPermitida = "Captura de calificaciones del segundo parcial se encuentran activas.";
            $puedeOrdinario = false;
            $puedeParcial3  = false;
            $puedeParcial2  = true;
            $puedeParcial1  = false;
        }
        if (!$finParcial1) {//si fecha actual es menor que examen ordinario
            $calificacionPermitida = "Captura de calificaciones del primer parcial se encuentran activas.";
            $puedeOrdinario = false;
            $puedeParcial3  = false;
            $puedeParcial2  = false;
            $puedeParcial1  = true;
        }
   
        // dd($fechaActual, ">", Carbon::parse($calendarioExamen->calexFinParcial3) , $finParcial3);

        if (!env("TOGGLE_VALIDACION_FECHAS")) {
            $calificacionPermitida = "Captura de calificaciones abiertas";
            $puedeOrdinario = true;
            $puedeParcial3  = true;
            $puedeParcial2  = true;
            $puedeParcial1  = true;
        }

        switch ($nivel) {
            case 'SUP':
                //OBTENER INSCRITOS DE UNIVERSIDAD AL GRUPO
                $inscritos = Inscrito::with('curso.cgt.periodo', 'curso.cgt.plan.programa', 'curso.alumno.persona', 'calificacion')
                    ->where('grupo_id', $grupo_id)
                ->get();
                
                $inscritos = $inscritos->map(function ($item, $key) {
                    $alumno = $item->curso->alumno->persona->perApellido1 . "-" . 
                        $item->curso->alumno->persona->perApellido2  . "-" . 
                        $item->curso->alumno->persona->perNombre;

                    $item->sortByNombres = Str::slug($alumno, "-");

                    return $item;
                });


            // dd($inscritos->map(function ($item,$key) {
            //     return $item->id;
            // })->all());



                $inscritos = $inscritos->sortBy("sortByNombres");


                // dd($inscritos->first());
                $motivosFalta = DB::table("motivosfalta")->get()->sortByDesc("id");


                // $matPorcentajeParcial = 0;
                // $matPorcentajeOrdinario = 100;

                return view('calificacion.universidad.create', [
                    'grupo' => $grupo,
                    'inscritos' => $inscritos,
                    'matPorcentajeParcial' => $matPorcentajeParcial,
                    'matPorcentajeOrdinario' => $matPorcentajeOrdinario,
                    'calificacionPermitida' => $calificacionPermitida,
                    "motivosFalta"  => $motivosFalta,
                    "puedeParcial1" => $puedeParcial1,
                    "puedeParcial2" => $puedeParcial2,
                    "puedeParcial3" => $puedeParcial3,
                    "puedeOrdinario" => $puedeOrdinario,
                    "finParciales" => (Object) [
                        "finParcial1"  => $finParcial1,
                        "finParcial2"  => $finParcial2,
                        "finParcial3"  => $finParcial3,
                        "finOrdinario" => $finOrdinario
                    ]
                ] );
                break;
            default:
                return view('grupo.show-list');
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $configTercerParcial = Portal_configuracion::select('pcEstado')
        ->where('pcClave', 'TERCER_PARCIAL')
        ->where('pcPortal', 'D')
        ->first();
        $TERCER_PARCIAL = ($configTercerParcial->pcEstado == 'A');

        $grupo_id = $request->grupo_id;
        //OBTENER GRUPO SELECCIONADO
        $grupo = Grupo::with('plan', 'materia', 'empleado.persona')->find($grupo_id);
        $programa_id = $grupo->plan->programa_id;


        if ($grupo->estado_act == "B") {
            alert('Escuela Modelo', 'El estado actual del grupo no permite modificación de calificaciones', 'warning')->showConfirmButton();
            return redirect()->back()->withInput();
        }

    
        if ($grupo->estado_act == "C") {
            alert('Escuela Modelo', 'El estado actual del grupo no permite modificación de calificaciones', 'warning')->showConfirmButton();
            return redirect()->back()->withInput();
        }


        // $calendarioExamen = DB::table("calendarioexamen")->where("periodo_id", "=", $grupo->periodo_id)->first();
        // $fechaActual = Carbon::now()->setTime(0, 0, 0);
        // $finParcial1  = $fechaActual->gt(($calendarioExamen->calexFinParcial1));
        // $finParcial2  = $fechaActual->gt(($calendarioExamen->calexFinParcial2));
        // $finParcial3  = $fechaActual->gt(($calendarioExamen->calexFinParcial3));
        // $finOrdinario = $fechaActual->gt(($calendarioExamen->calexFinOrdinario));



        
       try {
            $calificaciones = $request->calificaciones;

            $inscCalificacionParcial1Col  = $request->has("calificaciones.inscCalificacionParcial1")  ? collect($calificaciones["inscCalificacionParcial1"])  : collect();
            $inscFaltasParcial1Col        = $request->has("calificaciones.inscFaltasParcial1")        ? collect($calificaciones["inscFaltasParcial1"])        : collect();
            $inscCalificacionParcial2Col  = $request->has("calificaciones.inscCalificacionParcial2")  ? collect($calificaciones["inscCalificacionParcial2"])  : collect();
            $inscFaltasParcial2Col        = $request->has("calificaciones.inscFaltasParcial2")        ? collect($calificaciones["inscFaltasParcial2"])        : collect();
            if ($TERCER_PARCIAL) {
                $inscCalificacionParcial3Col  = $request->has("calificaciones.inscCalificacionParcial3")  ? collect($calificaciones["inscCalificacionParcial3"])  : collect();
            }
            $inscFaltasParcial3Col        = $request->has("calificaciones.inscFaltasParcial3")        ? collect($calificaciones["inscFaltasParcial3"])        : collect();
            $inscPromedioParcialesCol     = $request->has("calificaciones.inscPromedioParciales")     ? collect($calificaciones["inscPromedioParciales"])     : collect();
            $inscCalificacionOrdinarioCol = $request->has("calificaciones.inscCalificacionOrdinario") ? collect($calificaciones["inscCalificacionOrdinario"]) : collect();
            $incsCalificacionFinalCol     = $request->has("calificaciones.incsCalificacionFinal")     ? collect($calificaciones["incsCalificacionFinal"])     : collect();
            $inscMotivoFaltaCol           = $request->has("calificaciones.inscMotivoFalta")           ? collect($calificaciones["inscMotivoFalta"])           : collect();

            /**
             * Verificar que en los parciales:
             * - Si ponen faltas a un alumno, la calificacion es obligatoria.
             * - Si ponen faltas, que no excedan las 30, es el número máximo de faltas.
             */
            $datosIncorrectosParcial1 = $inscFaltasParcial1Col->filter(static function($faltas, $key) use ($inscCalificacionParcial1Col) {
                $calificacion = $inscCalificacionParcial1Col->get($key);
                return ($faltas && intval($faltas) > 30) || ($faltas && is_null($calificacion));
            });
            $datosIncorrectosParcial2 = $inscFaltasParcial2Col->filter(static function($faltas, $key) use ($inscCalificacionParcial2Col) {
                $calificacion = $inscCalificacionParcial2Col->get($key);
                return ($faltas && intval($faltas) > 30) || ($faltas && is_null($calificacion));
            });
            if ($TERCER_PARCIAL) {
                $datosIncorrectosParcial3 = $inscFaltasParcial3Col->filter(static function($faltas, $key) use ($inscCalificacionParcial3Col) {
                    $calificacion = $inscCalificacionParcial3Col->get($key);
                    return ($faltas && intval($faltas) > 30) || ($faltas && is_null($calificacion));
                });
            }

            $condicion = ($datosIncorrectosParcial1->isNotEmpty() || $datosIncorrectosParcial2->isNotEmpty());

            if ($TERCER_PARCIAL) {
                $condicion = ($datosIncorrectosParcial1->isNotEmpty() || $datosIncorrectosParcial2->isNotEmpty() || $datosIncorrectosParcial3->isNotEmpty());
            }

            if($condicion)
            {
                alert('No se puede proceder con la acción', 'No se puede registrar faltas para un alumno sin proporcionar una calificación. Un alumno no puede tener más de 30 faltas en un parcial.', 'warning')->showConfirmButton();
                return back()->withInput();
            }


            //OBTENER INSCRITOS DE UNIVERSIDAD AL GRUPO
            $inscritos = Inscrito::with('curso.cgt.periodo', 'curso.cgt.plan.programa', 'curso.alumno', 'calificacion')->where('grupo_id', $grupo_id)->get();

            // dd($inscritos->map(function ($item, $key) {
            //     return $item->id;
            // })->all());

            foreach ($inscritos as $inscrito) {
                $calificacion = Calificacion::where('inscrito_id', $inscrito->id)->first();
                $calificacion_anterior = clone $calificacion;

                if (env("TOGGLE_VALIDACION_FECHAS")) {
                    if ($request->puedeParcial2
                        && is_null($calificacion->inscCalificacionParcial1)) {
                        alert('Escuela Modelo', 'Debido a que no se completo la captura de calificaciones en las fechas limite indicada por la escuela, deberá dirigirse personalmente con la coordinación para solicitar la captura de calificaciones pertinentes.', 'warning')->showConfirmButton();
                        return redirect()->back();
                    }
                    if ($request->puedeParcial3
                        && (is_null($calificacion->inscCalificacionParcial1)
                        || is_null($calificacion->inscCalificacionParcial2))) {
                        alert('Escuela Modelo', 'Debido a que no se completo la captura de calificaciones en las fechas limite indicada por la escuela, deberá dirigirse personalmente con la coordinación para solicitar la captura de calificaciones pertinentes.', 'warning')->showConfirmButton();
                        return redirect()->back();
                    }
                    $chckNllCalP3 = ($TERCER_PARCIAL) ? (is_null($calificacion->inscCalificacionParcial1) || is_null($calificacion->inscCalificacionParcial2) || is_null($calificacion->inscCalificacionParcial3)) : (is_null($calificacion->inscCalificacionParcial1) || is_null($calificacion->inscCalificacionParcial2));
                    if ($request->puedeOrdinario
                        && ($chckNllCalP3)) {
                        alert('Escuela Modelo', 'Debido a que no se completo la captura de calificaciones en las fechas limite indicada por la escuela, deberá dirigirse personalmente con la coordinación para solicitar la captura de calificaciones pertinentes.', 'warning')->showConfirmButton();
                        return redirect()->back();
                    }
                }



                $inscCalificacionParcial1 = $inscCalificacionParcial1Col->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();
                $inscFaltasParcial1 = $inscFaltasParcial1Col->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();

                $inscCalificacionParcial2 = $inscCalificacionParcial2Col->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();
                $inscFaltasParcial2 = $inscFaltasParcial2Col->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();

                if ($TERCER_PARCIAL) {
                    $inscCalificacionParcial3 = $inscCalificacionParcial3Col->filter(function ($value, $key) use ($inscrito) {
                        return $key == $inscrito->id;
                    })->first();
                }

                $inscFaltasParcial3 = $inscFaltasParcial3Col->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();

                $inscPromedioParciales = $inscPromedioParcialesCol->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();
                $inscCalificacionOrdinario = $inscCalificacionOrdinarioCol->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();
                $incsCalificacionFinal = $incsCalificacionFinalCol->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();
                $inscMotivoFalta = $inscMotivoFaltaCol->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();


                $motivoFalta = DB::table("motivosfalta")->where("id", "=", $inscMotivoFalta)->first();
                $motivoFalta = $motivoFalta ? $motivoFalta->mfAbreviatura: "";

                Log::info('Antes de actualizar calificacion');

                if ($calificacion) {
                    $calificacion->inscCalificacionParcial1  = $inscCalificacionParcial1  != null ? $inscCalificacionParcial1  : $calificacion->inscCalificacionParcial1;
                    $calificacion->inscFaltasParcial1        = $inscFaltasParcial1        != null ? $inscFaltasParcial1        : $calificacion->inscFaltasParcial1;
                    $calificacion->inscCalificacionParcial2  = $inscCalificacionParcial2  != null ? $inscCalificacionParcial2  : $calificacion->inscCalificacionParcial2;
                    $calificacion->inscFaltasParcial2        = $inscFaltasParcial2        != null ? $inscFaltasParcial2        : $calificacion->inscFaltasParcial2;
                    if ($TERCER_PARCIAL) {
                        $calificacion->inscCalificacionParcial3  = $inscCalificacionParcial3  != null ? $inscCalificacionParcial3  : $calificacion->inscCalificacionParcial3;
                    }
                    $calificacion->inscFaltasParcial3        = $inscFaltasParcial3        != null ? $inscFaltasParcial3        : $calificacion->inscFaltasParcial3;
                    $calificacion->inscPromedioParciales     = $inscPromedioParciales     != null ? $inscPromedioParciales     : $calificacion->inscPromedioParciales;
                    if ($motivoFalta != "NPE") {
                        $calificacion->inscCalificacionOrdinario = $inscCalificacionOrdinario != null ? $inscCalificacionOrdinario : $calificacion->inscCalificacionOrdinario;
                        $calificacion->incsCalificacionFinal     = $incsCalificacionFinal     != null ? $incsCalificacionFinal     : $calificacion->incsCalificacionFinal;
                    } else {
                        $calificacion->inscCalificacionOrdinario = 0;
                        $calificacion->incsCalificacionFinal     = 0;
                    }


                    $calificacion->motivofalta_id           = $inscMotivoFalta           != null ? $inscMotivoFalta           : $calificacion->motivofalta_id;

                    /**
                     * Si el modelo sufrió cambios, registrará un App\Models\CalificacionHistorial
                     */
                    if($calificacion->isDirty()) {
                        MetodosCalificaciones::crearHistorial($calificacion_anterior, $calificacion);
                    }

                    $calificacion->save();

                    Log::info('Despues del Save y antes del SP');

                    $result =  DB::select("call procInscritoPromedioParcialUniversidad("
                    ." ".$inscrito->id." )");
                    
                    
                    Log::info('Despues del SP');

                    Log::debug($result);
                }
            }






            //VERIFICAR QUE TODOS LOS ALUMNOS TENGAN CAL1, CAL2, CAL3, CAL-ORDINARIO CAPTURADOS
            //SI ESTAN TODOS CAPTURADOS DE TODOS LOS ALUMNOS CAMBIAR ESTATUS DEL GRUPO A "B"
            $grupoCambiaEstatus = Calificacion::whereIn('inscrito_id', $inscritos->pluck('id'))
            ->where(static function($query) use($TERCER_PARCIAL) {
                $query->whereNull('inscCalificacionParcial1')
                ->orWhereNull('inscCalificacionParcial2')
                ->orWhereNull('inscCalificacionOrdinario')
                ->orWhereNull('incsCalificacionFinal');
                if ($TERCER_PARCIAL) $query->orWhereNull('inscCalificacionParcial3');
            })
            ->exists();

            if (!$grupoCambiaEstatus) {
                $grupo->estado_act = "B";
                $grupo->save();
            }

            /**
             * Si el Recolector encuentra alumnos reprobados, envía una notificación a través de
             * la clase NotificacionReprobadosParciales.
             */
            $recolector = new AlumnosReprobadosParcialesRecolector([
                'periodo_id' => $grupo->periodo_id,
                'matClave' => $grupo->materia->matClave,
                'plan_id' => $grupo->plan_id,
                'semestre' => $grupo->gpoSemestre,
                'grupo' => $grupo->gpoClave,
                'etapa_calificacion' => $grupo->estado_act == 'B' ? 'Final' : null,
            ]);

            # en caso de estar haciendo test, poner la siguiente variable en false.
            $notificacion_activada = true;
            if($recolector->reprobados->isNotEmpty() && $notificacion_activada) {
                $recolector->generarExcel();
                // $notificacion = new NotificacionReprobadosParciales($grupo, $recolector);
                // $notificacion->enviar();
            }

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();

            return redirect('calificacion/agregar/SUP/' . $grupo_id)->withInput();
        }
        alert('Escuela Modelo', 'Se registraron las calificaciones con éxito.', 'success')->showConfirmButton();
        return redirect('calificacion/agregar/SUP/' . $grupo_id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        //
    }
}