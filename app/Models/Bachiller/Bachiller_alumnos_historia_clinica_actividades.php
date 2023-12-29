<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_alumnos_historia_clinica_actividades extends Model
{

    protected $table = 'bachiller_alumnos_historia_clinica_actividades';

    protected $fillable = [
        'historia_id',
        'actJuguete',
        'actCuento',
        'actPelicula',
        'actHorasTelevision',
        'actTenologia',
        'actTipoJuguetes',
        'actApoyoTarea',
        'actCuidado',
        'actObservacionExtra',
        'actGradoSugerido',
        'actGradoElegido',
        'actNombreEntrevista'
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
