<?php

namespace App\Http\Models\Secundaria;

use App\Http\Models\Secundaria\Secundaria_inscritos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Secundaria_calificaciones extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'secundaria_calificaciones';


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'secundaria_inscrito_id',
        'secundaria_grupo_evidencia_id',
        'numero_evaluacion',
        'mes_evaluacion',
        'calificacion_evidencia1',
        'calificacion_evidencia2',
        'calificacion_evidencia3',
        'calificacion_evidencia4',
        'calificacion_evidencia5',
        'calificacion_evidencia6',
        'calificacion_evidencia7',
        'calificacion_evidencia8',
        'calificacion_evidencia9',
        'calificacion_evidencia10',
        'promedio_mes',
        'user_docente_id' 
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
   * Override parent boot and Call deleting event
   *
   * @return void
   */
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

    public function secundaria_inscritos()
    {
        return $this->belongsTo(Secundaria_inscritos::class, 'secundaria_inscrito_id');
    }

}
