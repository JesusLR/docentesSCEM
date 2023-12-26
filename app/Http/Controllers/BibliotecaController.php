<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Models\Pago;
use App\Http\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Yajra\DataTables\Facades\DataTables;
use App\Http\Models\Portal_configuracion;

class BibliotecaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permisos:alumno');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $configEncuestaActiva = Portal_configuracion::where('pcClave', 'BIBLIOTECA_DOCENTE_ACTIVA')->first();
        $BIBLIOTECA_DOCENTE_ACTIVA = $configEncuestaActiva->pcEstado == 'A' ? true: false;
        if(!$BIBLIOTECA_DOCENTE_ACTIVA){
            return redirect('home');
        }
        return view('biblioteca.create');
    }

    public function action()
    {
        // Se crea un manejador CURL
        $curl = curl_init();

        // Se establece la URL y algunas opciones
        $solicitud = 'http://www.tirantonline.com.mx/ticket/get?user=UMODELO&password=PRT1wmg';

        curl_setopt($curl, CURLOPT_URL, $solicitud);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Se obtiene la respuesta del servidor
        $ticket = curl_exec($curl);

        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($curl);
        
        // Comprobamos si existe la respuesta
        if ($ticket == '') {
            echo 'TOL no ha devuelto nada.';
        } else {
            // Comprobamos si la respuesta es erronea
            if (strpos(trim($ticket),"http") == 0 || $ticket != -1) {
                // Redireccionamos al usuario con la URL valida
                header('Location: '.$ticket);
                exit;
            } else {
                echo 'TOL ha devuelto un valor erroneo. Revisar usuario y password e IP del servidor.';
            }
        }
    }
}