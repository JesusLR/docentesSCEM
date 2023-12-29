<?php

namespace App\Models\Bachiller;

use App\Models\Bachiller\Bachiller_materias;
use App\Models\Bachiller\Bachiller_cch_inscritos;
use App\Models\Periodo;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_cch_grupos extends Model
{
    use SoftDeletes;

   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_cch_grupos';


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
        'bachiller_materia_id',
        'plan_id',
        'periodo_id',
        'gpoGrado',
        'gpoClave',
        'gpoTurno',
        'empleado_id_docente',
        'empleado_id_auxiliar',
        'gpoMatComplementaria',        
        'gpoFechaExamenOrdinario',
        'gpoHoraExamenOrdinario',
        'gpoCupo',
        'gpoNumeroFolio',
        'gpoNumeroActa',
        'gpoNumeroLibro',
        'grupo_equivalente_id',
        'optativa_id',
        'estado_act',
        'fecha_mov_ord_act',
        'clave_actv',
        'inscritos_gpo',
        'nombreAlternativo',
        'gpoExtraCurr',
        'gpoACD'    
        
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
            foreach ($table->bachiller_cch_inscrito()->get() as $inscrito) {
            $inscrito->delete();
            }
        });
    }
   }

   public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function bachiller_materia()
    {
        return $this->belongsTo(Bachiller_materias::class);
    }

    public function bachiller_empleado()
    {
        return $this->belongsTo(Bachiller_empleados::class, 'empleado_id_docente');
    }

    public function bachiller_cch_inscrito()
    {
        return $this->hasMany(Bachiller_cch_inscritos::class, 'bachiller_grupo_id');
    }

    public function bachiller_horarios()
    {
        return $this->hasMany(Bachiller_horarios::class);
    }


}