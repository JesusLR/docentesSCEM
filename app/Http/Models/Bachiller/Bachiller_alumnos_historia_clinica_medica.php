<?php

namespace App\Http\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_alumnos_historia_clinica_medica extends Model
{
    protected $table = 'bachiller_alumnos_historia_clinica_medica';

    protected $fillable = [
        'historia_id',
        'medIntervencionQuirurgicas',
        'medMedicamentos',
        'medConvulsiones',
        'medAudicion',
        'medFiebres',
        'medProblemasCorazon',
        'medDeficiencia',
        'medAsma',
        'medDiabetes',
        'medGastrointestinales',
        'medAccidentes',
        'medEpilepsia',
        'medRinion',
        'medPiel',
        'medCoordinacionMotriz',
        'medEstrenimiento',
        'medDificultadesSuenio',
        'medAlergias',
        'medEspesificar',
        'medOtro',
        'medGastoMedico',
        'medNombreAsegurador',
        'medVacunas',
        'medTramiento',
        'medTerapia',
        'medMotivoTerapia',
        'medSaludFisicaAct',
        'medSaludEmocialAct'
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
