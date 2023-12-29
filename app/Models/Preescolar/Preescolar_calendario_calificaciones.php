<?php

namespace App\Models\Preescolar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Preescolar_calendario_calificaciones extends Model
{
    protected $table = 'preescolar_calendario_calificaciones';


    protected $fillable = [
        'plan_id',
        'periodo_id',
        'trimestre1_docente_inicio',
        'trimestre1_docente_fin',
        'trimestre1_administrativo_edicion',
        'trimestre1_alumnos_publicacion',
        'trimestre2_docente_inicio',
        'trimestre2_docente_fin',
        'trimestre2_administrativo_edicion',
        'trimestre2_alumnos_publicacion',
        'trimestre3_docente_inicio',
        'trimestre3_docente_fin',
        'trimestre3_administrativo_edicion',
        'trimestre3_alumnos_publicacion'
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
