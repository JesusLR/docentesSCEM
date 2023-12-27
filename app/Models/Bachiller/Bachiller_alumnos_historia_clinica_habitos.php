<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_alumnos_historia_clinica_habitos extends Model
{

    protected $table = 'bachiller_alumnos_historia_clinica_habitos';

    protected $fillable = [
        'historia_id',
        'habBanio',
        'habVestimenta',
        'habLuz',
        'habZapatos',
        'habCome',
        'habHoraDormir',
        'habHoraDespertar',
        'habEstadoLevantar',
        'habRecipiente'
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
