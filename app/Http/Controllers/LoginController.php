<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Utils;
use Illuminate\Database\QueryException;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use URL;
use Validator;
use Debugbar;

use Illuminate\Support\Facades\Session;
use App\Models\Empleado;
use App\Models\Portal_configuracion;

class LoginController extends Controller
{

    public function index(){
        return view('auth.login');
    }

     /**
     * Esta funciona es creada para validar el usuario y contraseña.
     *
     * @param \Illuminate\Http\Request $request
     *
     */
    public function auth(Request $request)
    {

        $configMantenimiento = Portal_configuracion::Where('pcClave', 'EN_MANTENIMIENTO')->first();
        $mantenimientoActivo = $configMantenimiento->pcEstado == 'A' ? true: false;
        $EN_MANTENIMIENTO = $mantenimientoActivo;
        if($EN_MANTENIMIENTO)
        {
            alert()
                ->error('Escuela Modelo', 'Estamos en labores de mantenimiento hasta el 29 de Diciembre de 2021 a las 9 a.m. Para cualquier solicitud, favor de comunicarse a las oficinas de Escuela Modelo.')
                ->autoClose(15000);
            return redirect()->route('login')->withInput();
        }



        $this->validate($request,
            [
                'empleado_id' => 'required|string',
                'password' => 'required|string',
            ]
        );
        $credentials = $request->only('empleado_id','password');

        // dd($credentials);
        if (Auth::attempt($credentials))
        {
            $ruta = '/home';

            if ( (Auth::user()->maternal == 1 ) || (Auth::user()->preescolar == 1) )
            {
                $ruta = '/preescolar_grupo';
            }
            if (Auth::user()->primaria == 1)
            {
                $ruta = '/primaria_grupo';
            }
            if (Auth::user()->secundaria == 1)
            {
                $ruta = '/secundaria_grupo';
            }

             // para valladolid y mérida
            if (Auth::user()->bachiller == 1 && (Auth::user()->campus_cme == 1 || Auth::user()->campus_cva == 1) )
            {
                $ruta = '/bachiller_grupo_yucatan';
            }

            if (Auth::user()->bachiller == 1 && Auth::user()->campus_cch == 1)
            {
                $ruta = '/bachiller_grupo_chetumal';
            }

            if (   (Auth::user()->superior == 1)  || (Auth::user()->posgrado == 1)
                || (Auth::user()->educontinua == 1) )
            {
                $ruta = '/home';
            }

            //return redirect()->intended('home');
            return redirect($ruta);
        }else{
            alert()
            ->error('Ups...', 'Usuario y/o contraseña invalidos')
            ->showConfirmButton()
            ->autoClose(5000);

            return redirect()->route('login')->withInput();
        }

    }

    /**
     * Esta funcion es para salir y eliminar la sesion
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }


}
