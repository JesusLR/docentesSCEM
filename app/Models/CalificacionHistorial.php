<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class CalificacionHistorial extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calificaciones_historial';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'calificacion_id',
        'parcial1',
        'parcial1_anterior',
        'faltas1',
        'faltas1_anterior',
        'parcial2',
        'parcial2_anterior',
        'faltas2',
        'faltas2_anterior',
        'parcial3',
        'parcial3_anterior',
        'faltas3',
        'faltas3_anterior',
        'ordinario',
        'ordinario_anterior',
        'fecha_cambio',
        'motivofalta_id',
        'motivofalta_anterior_id',
        'admin_id',
        'docente_id',
    ];


    public function calificacion()
    {
        return $this->belongsTo(Calificacion::class);
    }
}
