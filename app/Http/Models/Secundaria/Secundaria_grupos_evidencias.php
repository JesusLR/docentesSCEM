<?php

namespace App\Http\Models\Secundaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Secundaria_grupos_evidencias extends Model
{
    use SoftDeletes;

   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'secundaria_grupos_evidencias';


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
        'secundaria_grupo_id',
        'secundaria_mes_evaluacion_id',
        // 'mes',        
        'numero_evidencias',
        'concepto_evidencia1',
        'concepto_evidencia2',
        'concepto_evidencia3',
        'concepto_evidencia4',
        'concepto_evidencia5',
        'concepto_evidencia6',
        'concepto_evidencia7',
        'concepto_evidencia8',
        'concepto_evidencia9',
        'concepto_evidencia10',
        'porcentaje_evidencia1',
        'porcentaje_evidencia2',
        'porcentaje_evidencia3',
        'porcentaje_evidencia4',    
        'porcentaje_evidencia5',
        'porcentaje_evidencia6',
        'porcentaje_evidencia7',
        'porcentaje_evidencia8',
        'porcentaje_evidencia9',
        'porcentaje_evidencia10',
        'porcentaje_total'        
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
            foreach ($table->secundaria_inscritos()->get() as $inscrito) {
            $inscrito->delete();
            }
        });
    }
   }

    public function secundaria_grupo()
    {
        return $this->belongsTo(Secundaria_grupos::class,'grupo_id');
    }

   

}