<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_alumnos_historia_clinica_familiares extends Model
{
    protected $table = 'bachiller_alumnos_historia_clinica_familiares';


    protected $fillable = [
        'historia_id',
        'famNombresMadre',
        'famApellido1Madre',
        'famApellido2Madre',
        'famFechaNacimientoMadre',
        'municipioMadre_id',
        'famOcupacionMadre',
        'famEmpresaMadre',
        'famCelularMadre',
        'famTelefonoMadre',
        'famEmailMadre',
        'famRelacionMadre',
        'famRelacionFrecuenciaMadre',
        'famNombresPadre',
        'famApellido1Padre',
        'famApellido2Padre',
        'famFechaNacimientoPadre',
        'municipioPadre_id',
        'famOcupacionPadre',
        'famEmpresaPadre',
        'famCelularPadre',
        'famTelefonoPadre',
        'famEmailPadre',
        'famRelacionPadre',
        'famRelacionFrecuenciaPadre',
        'famEstadoCivilPadres',
        'famSeparado',
        'famReligion',
        'famExtraNombre',
        'famTelefonoExtra',
        'famAutorizado1',
        'famAutorizado2',
        'famIntegrante1',
        'famParentesco1',
        'famEdadIntegrante1',
        'famEscuelaGrado1',
        'famIntegrante2',
        'famParentesco2',
        'famEdadIntegrante2',
        'famEscuelaGrado2',
        'famIntregrante3',
        'famParentesco3',
        'famEdadIntregrante3',
        'famEscuelaGrado3'
    ];

    protected static function boot()
    {
        parent::boot();

        if (Auth::check()) {
            static::saving(function ($table) {
                $table->usuario_at = Auth::user()->id;
            });

            static::updating(function ($table) {
                $table->usuario_at = Auth::user()->id;
            });

            static::deleting(function ($table) {
                $table->usuario_at = Auth::user()->id;
            });
        }
    }
}
