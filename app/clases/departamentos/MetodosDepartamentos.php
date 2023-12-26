<?php
namespace App\clases\departamentos;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Http\Models\Departamento;

class MetodosDepartamentos
{
    /**
    * Retorna un array de niveles académicos,
    * key = depClave, value = nivel descrito
    * @param array $depClaves (opcional)
    */
    public static function nivelesAcademicos($depClaves = []): Collection
    {
        $departamentos = self::buscarSoloAcademicos(null, $depClaves)->unique('depClave');
        $departamentos->each(static function($departamento) {
            $departamento->descripcionNivel = self::describirNivel($departamento);
        });

        return $departamentos->pluck('descripcionNivel', 'depClave');
    }

    /**
    * @param int $ubicacion_id (opcional)
    * @param array $depClaves (opcional)
    */
    public static function buscarSoloAcademicos($ubicacion_id = null, $depClaves = []): Collection
    {
        return Departamento::where(static function($query) use ($ubicacion_id, $depClaves) {
            if($ubicacion_id) {
                $query->where('ubicacion_id', $ubicacion_id);
            }
            if(empty($depClaves)) {
                $query->whereIn('depClave', ['DIP', 'POS', 'SUP', 'BAC', 'SEC', 'PRI', 'PRE', 'MAT']);
            } else {
                $query->whereIn('depClave', $depClaves);
            }
        })->get();
    }

    public static function buscarSoloAcademicosMigrarACD($ubiClave = null, $depClaves = []): Collection
    {
        return Departamento::select('departamentos.*')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where(static function($query) use ($ubiClave, $depClaves) {
            if($ubiClave) {
                $query->where('ubicacion.ubiClave', $ubiClave);
            }
            if(empty($depClaves)) {
                $query->whereIn('departamentos.depClave', ['DIP', 'POS', 'SUP', 'BAC', 'SEC', 'PRI', 'PRE', 'MAT']);
            } else {
                $query->whereIn('departamentos.depClave', $depClaves);
            }
        })->get();
    }

    /**
    * @param App\Http\Models\Departamento
    */
    public static function describirNivel($departamento): string
    {
        $descripcion = '';
        switch ($departamento->depClave) {
            case 'SUP':
                $descripcion =  'SUPERIOR';
                break;
            case 'POS':
                $descripcion =  'POSGRADO';
                break;
            case 'BAC':
                $descripcion =  'BACHILLERATO';
                break;
            case 'PRI':
                $descripcion =  'PRIMARIA';
                break;
            case 'SEC':
                $descripcion =  'SECUNDARIA';
                break;
            case 'PRE':
                $descripcion =  'PRESCOLAR';
                break;
            case 'MAT':
                $descripcion =  'MATERNAL';
                break;
            case 'DIP':
                $descripcion =  'EDUCACION CONTINUA';
                break;
        }

        return $descripcion;
    }

}
