<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Curso extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cursos';


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
        'cgt_id',
        'periodo_id',
        'curTipoBeca',
        'curPorcentajeBeca',
        'curObservacionesBeca',
        'curFechaRegistro',
        'curFechaBaja',
        'curEstado',
        'curTipoIngreso',
        'curImporteInscripcion',
        'curImporteMensualidad',
        'curImporteVencimiento',
        'curImporteDescuento',
        'curDiasProntoPago',
        'curPlanPago',
        'curUsuarioCuotas',
        'curFechaCuotas',
        'curOpcionTitulo',
        'curAnioCuotas',
        'usuario_at'
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
            foreach ($table->inscritos()->get() as $inscrito) {
            $inscrito->delete();
            }
        });
    }
   }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }
    
    public function cgt()
    {
        return $this->belongsTo(Cgt::class);
    }

    public function inscritos()
    {
        return $this->hasMany(Inscrito::class);
    }


}