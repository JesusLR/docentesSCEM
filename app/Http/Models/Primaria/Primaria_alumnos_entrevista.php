<?php

namespace App\Http\Models\Primaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_alumnos_entrevista extends Model
{
    protected $table = 'primaria_alumnos_entrevista';


    protected $fillable = [
        'expNombreAlumno',
        'expApellidoAlumno1',
        'expApellidoAlumno2',
        'expFechaNacAlumno',
        'expMunicioAlumno_id',
        'expCurpAlumno',
        'expEdadAlumno',
        'expTipoSangre',
        'expAlergias',
        'expEscuelaProcedencia',
        'expGradosCursados',
        'expAnioRecursado',
        'expTutorMadre',
        'expEdadTutorMadre',
        'expFechaNacimientoTutorMadre',
        'exMunicipioMadre_id',
        'expOcupacionTutorMadre',
        'expEmpresaLaboralTutorMadre',
        'expCelularTutorMadre',
        'expTelefonoCasaTutorMadre',
        'expEmailTutorMadre',
        'expTutorPadre',
        'expEdadTutorPadre',
        'expFechaNacimientoTutorPadre',
        'expMunicipioPadre_id',
        'expOcupacionTutorPadre',
        'expEmpresaLaboralTutorPadre',
        'expCelularTutorPadre',
        'expTelefonoCasaTutorPadre',
        'expEmailTutorPadre',
        'expEstadoCivilPadres',
        'expEstadoCivilOtro',
        'expReligonPadres',
        'expNombreFamiliar',
        'expTelefonoFamiliar',
        'expPersona1Autorizada',
        'expPersona2Autorizada',
        'expPadresEgresados',
        'expFamiliarModelo',
        'expNombreFamiliarModelo'
    ];

    protected static function boot()
    {
        parent::boot();

        if(Auth::check()){
            static::saving(function($table) {
                $table->usuario_at = Auth::user()->id;
            });

            static::updating(function($table) {
                $table->usuario_at = Auth::user()->id;
            });

            static::deleting(function($table) {
                $table->usuario_at = Auth::user()->id;
            });
        }
    }
}
