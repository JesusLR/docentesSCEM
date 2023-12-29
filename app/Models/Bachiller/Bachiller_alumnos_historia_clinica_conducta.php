<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_alumnos_historia_clinica_conducta extends Model
{
    protected $table = 'bachiller_alumnos_historia_clinica_conducta';


    protected $fillable = [
        'historia_id',
        'conAfectivoNervioso',
        'conAfectivoAgresivo',
        'conAfectivoDestraido',
        'conAfectivoTimido',
        'conAfectivoSensible',
        'conAfectivoAmistoso',
        'conAfectivoAmable',
        'conAfectivoOtro',
        'conVerbalRenuente',
        'conVerbalTartamudez',
        'conVerbalVerbalizacion',
        'conVerbalExplicito',
        'conVerbalSilencioso',
        'conVerbalRepetivo',
        'conConductual',
        'conBerrinches',
        'conAgresividad',
        'conMasturbacion',
        'conMentiras',
        'conRobo',
        'conPesadillas',
        'conEnuresis',
        'conEncopresis',
        'conExcesoAlimentacion',
        'conRechazoAlimentario',
        'conLlanto',
        'conTricotilomania',
        'conOnicofagia',
        'conMorderUnias',
        'conSuccionPulgar',
        'conExplicaciones',
        'conPrivaciones',
        'conCorporal',
        'conAmenazas',
        'conTiempoFuera',
        'conOtros',
        'conAplica',
        'conRecompensa'
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
