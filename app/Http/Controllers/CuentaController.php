<?php

namespace App\Http\Controllers;

use URL;
use Auth;
use Debugbar;
use Validator;
use Carbon\Carbon;
use App\Http\Helpers\Utils;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;


use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class CuentaController extends Controller
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


  public function cambiarPassword()
  {
    return view("miCuenta.cuenta");
  }


    // Does string contain letters?
    function _s_has_letters( $string ) {
      return preg_match( '/[a-zA-Z]/', $string );
    }
    // Does string contain numbers?
    function _s_has_numbers( $string ) {
      return preg_match( '/\d/', $string );
    }
    // Does string contain special characters?
    function _s_has_special_chars( $string ) {
      return preg_match('/[^a-zA-Z\d]/', $string);
    }


    // There is one upper
    function _s_has_mayus ( $string ) {
      return !ctype_lower($string);
    }



  public function passwordUpdate(Request $request)
  {



      $validator = Validator::make($request->all(), [
        'oldPassword'       => 'required',
        'password'          =>  'required|min:8|max:20',

        // 'password'          =>  'required|min:8|max:20|regex:/^[a-zA-Z0-9]+$/',
        'confirmPassword'   =>  'required|same:password',
      ], [
        'oldPassword.required'     => 'La contraseña actual es requerida',
        'confirmPassword.same'     => 'La contraseña de verificacion no coincide con la nueva contraseña.',
        'password.required'        => "La contraseña nueva es requerida.",
        "confirmPassword.required" => "La contraseña de verificación es requerida."
      ]);



        if (!($this->_s_has_mayus($request->password) && $this->_s_has_letters($request->password)
          && $this->_s_has_numbers($request->password) && $this->_s_has_special_chars($request->password))) {
            
            alert('Escuela Modelo', 'La contraseña debe contener al menos una mayuscula, una letra, un numero, y un caracter especial', 'error')->showConfirmButton();
            return redirect()->back();

        }




      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
      }


    $user = DB::table("users_docentes")->where("id", "=", Auth::id())->first();


    if (!Hash::check($request->oldPassword, $user->password)) {
      alert('Escuela Modelo', 'Tu contraseña actual no coincide', 'warning')->showConfirmButton();
      return redirect()->back();
    }


    $user = DB::table("users_docentes")->where("id", "=", Auth::id())->update([
      "password" => Hash::make($request->password)
    ]);

    alert('Escuela Modelo', 'Contraseña guardada correctamente', 'success')->showConfirmButton();
    return redirect()->back();
  }
}