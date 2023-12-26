<?php

namespace App\Http\Helpers;
use Carbon\Carbon;
use Akaunting\Money\Money;
use Auth;

use App\Models\Modules;
use App\Models\Permission_module_user;
use App\Models\Permission;
use App\Http\Models\Permiso_programa_user;


class Utils
{
    public static function validaPermiso($controlador,$programa_id){
        $user = Auth::user();
        $modulo = Modules::where('slug',$controlador)->first();
        //OBTENER EL PERMISO DEL MODULO
        $permisos = Permission_module_user::where('user_id',$user->id)->where('module_id',$modulo->id)->first();
        //OBTENER EL NOMBRE DEL PERMISO
        $permiso = Permission::find($permisos->permission_id)->name;
        if($permiso == 'C'){ //permiso de coordinadores y directores
            //VALIDA SI TIENE PERMISOS SOBRE EL PROGRAMA
            $programa = Permiso_programa_user::where([['user_id',$user->id],['programa_id',$programa_id]])->exists();
            if($programa){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    public static function validaEmpty($input){
        if($input == ''){
            return null;
        }else{
            return $input;
        }
    }

    public static function diaSemana($dia){
        switch($dia){
            case 1:
                return "Lunes";
            break;
            case 2:
                return "Martes";
            break;
            case 3:
                return "Miércoles";
            break;
            case 4:
                return "Jueves";
            break;
            case 5:
                return "Viernes";
            break;
            case 6:
                return "Sábado";
            break;
            case 7:
                return "Domingo";
            break;
            default:
                return $dia;
            break;
        }
    }

    public static function convertMoney($number){
        return Money::MXN($number,true);
    }

    public static function convertNumber($number){
        if($number == ''){
            return 0;
        }else{
            $number = str_replace(',', '', $number);
            return $number;
        }
    }

    public static function convertDateMonth($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/M/Y');
    }

    public static function estadoGrupo($estado){
        switch($estado){
            case 'A':
                return "ABIERTO SIN CALIFICAR";
            break;
            case 'B':
                return "ABIERTO CON CALIFICACIÓN";
            break;
            case 'C':
                return "CERRADO";
            break;
            default:
                return $estado;
            break;
        }
    }

    public static function nivel_profesion($valor) {
        switch ($valor) {
            case 'L':
                return 'LICENCIATURA';
                break;
            case 'E':
                return 'ESPECIALIDAD';
                break;
            case 'M':
                return 'MAESTRIA';
                break;
            case 'D':
                return 'DOCTORADO';
                break;
            default:
                return 'NINGUNO';
        }
    }

    public static function semestres_numeracion_ordinal($number) {
        switch ($number) {
            case 1:
                return 'PRIMERO';
                break;
            case 2:
                return 'SEGUNDO';
                break;
            case 3:
                return 'TERCERO';
                break;
            case 4:
                return 'CUARTO';
                break;
            case 5:
                return 'QUINTO';
                break;
            case 6:
                return 'SEXTO';
                break;
            case 7:
                return 'SEPTIMO';
                break;
            case 8:
                return 'OCTAVO';
                break;
            case 9:
                return 'NOVENO';
                break;
            case 10:
                return 'DECIMO';
                break;
            case 11:
                return 'ONCEAVO';
                break;
            case 12:
                return 'DOCEAVO';
                break;
        }
    }

    public static function num_meses_corto_string($number) {
        switch ($number) {
            case 1:
                return 'Ene';
                break;
            case 2:
                return 'Feb';
                break;
            case 3:
                return 'Mar';
                break;
            case 4:
                return 'Abr';
                break;
            case 5:
                return 'May';
                break;
            case 6:
                return 'Jun';
                break;
            case 7:
                return 'Jul';
                break;
            case 8:
                return 'Ago';
                break;
            case 9:
                return 'Sep';
                break;
            case 10:
                return 'Oct';
                break;
            case 11:
                return 'Nov';
                break;
            case 12:
                return 'Dic';
                break;
        }
    }

    
    public static function num_meses_string($number) {
        switch ($number) {
            case 1:
                return 'enero';
                break;
            case 2:
                return 'febrero';
                break;
            case 3:
                return 'marzo';
                break;
            case 4:
                return 'abril';
                break;
            case 5:
                return 'mayo';
                break;
            case 6:
                return 'junio';
                break;
            case 7:
                return 'julio';
                break;
            case 8:
                return 'agosto';
                break;
            case 9:
                return 'septiembre';
                break;
            case 10:
                return 'octubre';
                break;
            case 11:
                return 'noviembre';
                break;
            case 12:
                return 'diciembre';
                break;
        }
    }

    public static function fecha_string($fecha,$tipoMes = null, $tipoAnio = null){
        $fechaString = null;
        if($fecha){
            $dia = Carbon::parse($fecha)->format('d');
            $mes = Carbon::parse($fecha)->format('m');
            if($tipoMes == NULL){
            $mes = ucwords(Utils::num_meses_string($mes));
            $anio = Carbon::parse($fecha)->format('Y');
            $fechaString = $dia.' de '.$mes.' del '.$anio;
            }else{
            $mes = ucwords(Utils::num_meses_corto_string($mes));
            $anio = Carbon::parse($fecha)->format('Y');
            $fechaString = $dia.'/'.$mes.'/'.$anio;
            }
            if($tipoAnio == 'y') {
                $anio = substr($anio, 2);
                $fechaString = $dia.'/'.$mes.'/'.$anio;
            }
        }

        return $fechaString;
    }

}