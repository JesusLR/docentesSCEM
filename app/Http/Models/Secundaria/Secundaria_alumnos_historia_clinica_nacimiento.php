<?php

namespace App\Http\Models\Secundaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Secundaria_alumnos_historia_clinica_nacimiento extends Model
{
    protected $table = 'secundaria_alumnos_historia_clinica_nacimiento';

    protected $fillable = [
        'historia_id',
        'nacNumEmbarazo',
        'nacEmbarazoPlaneado',
        'nacEmbarazoTermino',
        'nacEmbarazoDuracion',
        'NacParto',
        'nacPeso',
        'nacMedia',
        'nacApgar',
        'nacComplicacionesEmbarazo',
        'nacCualesEmbarazo',
        'nacComplicacionesParto',
        'nacCualesParto',
        'nacComplicacionDespues',
        'nacCualesDespues',
        'nacLactancia',
        'nacActualmente'
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
