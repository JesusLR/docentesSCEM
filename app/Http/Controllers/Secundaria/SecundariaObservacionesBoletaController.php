<?php

namespace App\Http\Controllers\Secundaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Secundaria\Secundaria_calificaciones_observaciones;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SecundariaObservacionesBoletaController extends Controller
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

        return view('secundaria.observaciones_boleta.create', [
            "ubicaciones" => $ubicaciones
        ]);
    }
    public function obtenerObsBoleta(Request $request, $periodo_id, $plan_id, $cgt_id, $mes)
    {
        if($request->ajax()){

            $obsBoleta = Secundaria_calificaciones_observaciones::select('secundaria_calificaciones_observaciones.*')
            ->where('periodo_id', $periodo_id)
            ->where('plan_id', $plan_id)
            ->where('cgt_id', $cgt_id)
            // ->where('mes', $mes)
            ->get();
       
            return response()->json($obsBoleta);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {

        $fechaActual = Carbon::now('America/Merida');
        $mes_seleccionado = $request->mes_id;

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $fechaActual1 = $fechaActual->format('Y-m-d H:i:s');

        if (isset($_POST['observacionesCero'])) {

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "SEPTIEMBRE") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionSep' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "OCTUBRE") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionOct' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "NOVIEMBRE") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionNov' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "DICIEMBRE") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionDic' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "ENERO") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionEne' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "FEBRERO") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionFeb' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "MARZO") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionMar' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "ABRIL") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionAbr' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "MAYO") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionMay' => $request->observacionesCero
                ]);
            }

            // AGREGAR OBSERVACIÓN AL MES
            if ($mes_seleccionado === "JUNIO") {
                Secundaria_calificaciones_observaciones::create([
                    'plan_id' => $request->plan_id,
                    'periodo_id' => $request->periodo_id,
                    'cgt_id' => $request->cgt_id,
                    'observacionJun' => $request->observacionesCero
                ]);
            }


            alert('Escuela Modelo', 'La observación al grupo seleccionado se agrego con éxito', 'success')->showConfirmButton();
            return redirect()->route('secundaria.secundaria_obs_boleta.index');
        } else {

            // ACTUALIZAR EL MES 
            if ($mes_seleccionado === "SEPTIEMBRE") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionSep' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES 
            if ($mes_seleccionado === "OCTUBRE") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionOct' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "NOVIEMBRE") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionNov' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "DICIEMBRE") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionDic' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "ENERO") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionEne' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "FEBRERO") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionFeb' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "MARZO") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionMar' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "ABRIL") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionAbr' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "MAYO") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionMay' => $request->observaciones
                    ]);
            }

            // ACTUALIZAR EL MES
            if ($mes_seleccionado === "JUNIO") {
                DB::table('secundaria_calificaciones_observaciones')
                ->where('id', $request->id)
                    ->update([
                        'plan_id' => $request->plan_id,
                        'periodo_id' => $request->periodo_id,
                        'cgt_id' => $request->cgt_id,
                        'observacionJun' => $request->observaciones
                    ]);
            }


            alert('Escuela Modelo', 'La observación al grupo seleccionado se actualizo con éxito', 'success')->showConfirmButton();
            return redirect()->route('secundaria.secundaria_obs_boleta.index');
        }
    }


}
