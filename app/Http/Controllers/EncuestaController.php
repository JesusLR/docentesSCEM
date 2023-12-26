<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Portal_configuracion;
use Exception;

class EncuestaController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }

    public function make() 
    {
        // 
        $configEncuestaActiva = Portal_configuracion::Where('pcClave', 'ENCUESTA_ACTIVA')->first();
        $ENCUESTA_ACTIVA = $configEncuestaActiva->pcEstado == 'A' ? true: false;
        //
        if(!$ENCUESTA_ACTIVA) {
            alert('Encuesta no disponible', 'Las encuestas no están disponibles por el momento.', 'warning')->showConfirmButton();
            return back()->withInput();
        }

        $encuesta = self::buscarEncuesta(auth()->user()->empleado->id)->first();
        if($encuesta && $encuesta->encValidado == 'S') {
            alert('Encuesta realizada', 'Ya has realizado la encuesta', 'warning')->showConfirmButton();
            return back()->withInput();
        }

        return view('encuesta.create', [
            'encuesta' => $encuesta,
        ]);
    }

    /**
     * @param Illuminate\Http\Request
     */
    public function verificar_codigo(Request $request) {
        $encuesta = self::buscarEncuesta(auth()->user()->empleado->id)->first();
        // 
        $configEncuestaActiva = Portal_configuracion::Where('pcClave', 'ENCUESTA_ACTIVA')->first();
        $ENCUESTA_ACTIVA = $configEncuestaActiva->pcEstado == 'A' ? true: false;
        //
        if(!$ENCUESTA_ACTIVA || !$encuesta) {
            alert('Código incorrecto', 'No hay encuesta disponible', 'warning')->showConfirmButton();
            return back()->withInput();
        }
        
        try {
            $this->actualizarEncuestas(auth()->user()->empleado->id);
        } catch (Exception $e) {
            alert('Ha ocurrido un problema', $e->getMessage(), 'error')->showConfirmButton();
            return back()->withInput();
        }

        alert('Validado', 'Has realizado la encuesta. Gracias.', 'success')->showConfirmButton();
        return redirect('/');
    }


    /**
     * @param int $empleado_id
     */
    private static function buscarEncuesta($empleado_id) {
        return DB::table('validaencuestadocente')->where('empleado_id', $empleado_id);
    }

    /**
     * @param int $empleado_id
     */
    private function actualizarEncuestas($empleado_id) {
        try {
            self::buscarEncuesta($empleado_id)->update(['encValidado' => 'S']);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
