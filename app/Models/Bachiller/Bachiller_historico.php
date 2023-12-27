<?php

namespace App\Models\Bachiller;

use App\Models\Bachiller\Bachiller_cch_inscritos;
use App\Models\Bachiller\Bachiller_materias;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_historico extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_historico';


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
        'alumno_id',
        'plan_id',
        'bachiller_materia_id',
        'periodo_id',
        'histComplementoNombre',
        'histPeriodoAcreditacion',
        'histTipoAcreditacion',
        'histFechaExamen',
        'histCalificacion',
        'histFolio',
        'hisActa',
        'histLibro',
        'histNombreOficial'
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
          if ($table->bachiller_cch_calificacion) {
            $table->usuario_at = Auth::user()->id;
            $table->bachiller_cch_calificacion->delete();
          }
        });
      }
   }

    public function alumno()
    {
      return $this->belongsTo(Alumno::class);
    }

    public function plan()
    {
      return $this->belongsTo(Plan::class);
    }

    public function bachiller_materia()
    {
      return $this->belongsTo(Bachiller_materias::class);
    }

    public function periodo()
    {
      return $this->belongsTo(Periodo::class);
    }

    public function bachiller_cch_inscrito(){
      return $this->hasOne(Bachiller_cch_inscritos::class);
    }

    // SCOPES -----------------------------------------------

    public function scopeExtraordinarios($query)
    {
        return $query->where('histPeriodoAcreditacion', 'EX');
    }

}