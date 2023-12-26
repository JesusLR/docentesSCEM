<?php

namespace App\Http\Models\Primaria;

use App\Http\Models\Primaria\Primaria_inscrito;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_calificacione extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'primaria_calificaciones';


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
        'primaria_inscrito_id',
        'primaria_grupo_evidencia_id',
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

    public function primaria_inscritos()
    {
        return $this->belongsTo(Primaria_inscrito::class);
    }

}
