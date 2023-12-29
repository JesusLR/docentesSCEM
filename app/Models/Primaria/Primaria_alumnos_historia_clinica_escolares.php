<?php

namespace App\Models\Primaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_alumnos_historia_clinica_escolares extends Model
{

    protected $table = 'primaria_alumnos_historia_clinica_escolares';


    protected $fillable = [
        'historia_primaria_id',
        'escuelaProcedencia',
        'aniosEstudios',
        'motivosCambio',
        'kinder',
        'observaciones',
        'primaria',
        'promedioGrado1',
        'promedioGrado2',
        'promedioGrado3',
        'promedioGrado4',
        'promedioGrado5',
        'promedioGrado6',       
        'gradoRepetido',
        'promedioRepetido',
        'apoyoPedagogico',
        'observacionesApoyo',
        'medico',
        'observacionesMedico',
        'neurologico',
        'observacionesNerologico',
        'psicologico',
        'observacionesPsicologico',
        'motivoInscripcion',
        'familiar1',
        'familiar2',
        'familiar3',
        'referencia1',
        'celularReferencia1',
        'referencia2',
        'celularReferencia2',
        'observacionesGenerales',
        'entrevisto',
        'ubicacion'
  
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
