<?php

namespace App\Models\Primaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_alumnos_historia_clinica_familiares extends Model
{
    protected $table = 'primaria_alumnos_historia_clinica_familiares';


    protected $fillable = [
        'historia_primaria_id',
        'tiempoResidencia',
        'nombresPadre',
        'apellido1Padre',
        'apellido2Padre',
        'celularPadre',
        'edadPadre',
        'ocupacioPadre',
        'nombresMadre',
        'apellido1Madre',
        'apellido2Madre',
        'celularMadre',
        'edadMadre',
        'ocupacionMadre',
        'estadoCilvilPadres',
        'observaciones',
        'religion',
        'integrante1',
        'relacionIntegrante1',
        'edadIntegrante1',
        'ocupacionIntegrante1',
        'integrante2',
        'relacionIntegrante2',
        'edadIntegrante2',
        'ocupacionIntegrante2',
        'integrante3',
        'relacionIntegrante3',
        'edadIntegrante3',
        'ocupacionIntegrante3',
        'integrante4',
        'relacionIntegrante4',
        'edadIntegrante4',
        'ocupacionIntegrante4',
        'apoyoTareas',
        'deporteActividad'  
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
