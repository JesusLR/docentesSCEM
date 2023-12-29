<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_alumnos_historia_clinica extends Model
{
    protected $table = 'bachiller_alumnos_historia_clinica';

    protected $fillable = [
        'alumno_id',
        'hisEdadActualMeses',
        'hisTipoSangre',
        'hisAlergias',
        'hisEscuelaProcedencia',
        'hisUltimoGrado',
        'hisRecursado',
        'hisRecursadoDetalle',
        'hisIngresoSecundaria',
        'estatus_edicion'
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
