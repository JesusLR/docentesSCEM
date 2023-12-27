<?php

namespace App\Models\Bachiller;

use App\Models\Curso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_cch_inscritos extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_cch_inscritos';


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
          'curso_id',
          'bachiller_grupo_id',
          'insCalificacionParcial1',
          'insFaltasParcial1',
          'insCalificacionParcial2',
          'insFaltasParcial2',
          'insCalificacionParcial3',
          'insFaltasParcial3',
          'insPromedioParcial',
          'insCalificacionOrdinario',
          'insCalificacionFinal',
          'preparatoria_historico_id',
          'inscCalificacionSep',
          'inscCalificacionOct',
          'inscCalificacionNov',
          'inscCalificacionDic',
          'inscCalificacionEne',
          'inscCalificacionFeb',
          'inscCalificacionMar',
          'inscCalificacionAbr',
          'inscCalificacionMay',
          'inscCalificacionJun',
          'inscFaltasInjSep',
          'inscFaltasInjOct',
          'inscFaltasInjNov',
          'inscFaltasInjDic',
          'inscFaltasInjEne',
          'inscFaltasInjFeb',
          'inscFaltasInjMar',
          'inscFaltasInjAbr',
          'inscFaltasInjMay',
          'inscFaltasInjJun',
          'inscFaltasJusSep',
          'inscFaltasJusOct',
          'inscFaltasJusNov',
          'inscFaltasJusDic',
          'inscFaltasJusEne',
          'inscFaltasJusFeb',
          'inscFaltasJusMar',
          'inscFaltasJusAbr',
          'inscFaltasJusMay',
          'inscFaltasJusJun',
          'inscConductaSep',
          'inscConductaOct',
          'inscConductaNov',
          'inscConductaDic',
          'inscConductaEne',
          'inscConductaFeb',
          'inscConductaMar',
          'inscConductaAbr',
          'inscConductaMay',
          'inscConductaJun'

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
            $haycalificaciones = Bachiller_cch_calificaciones::where('bachiller_cch_inscrito_id', "=", $table->id)->get();
            //dd($haycalificaciones->isEmpty(), !empty($haycalificaciones));
            if(!$haycalificaciones->isEmpty()) {
                $table->bachiller_calificacion->where("bachiller_cch_inscrito_id", "=", $table->id)->delete();
            }

        });
    }
   }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function bachiller_cch_grupo()
    {
        return $this->belongsTo(Bachiller_cch_grupos::class, 'bachiller_grupo_id');
    }

    public function bachiller_calificacion()
    {
        return $this->hasOne(Bachiller_cch_calificaciones::class, 'bachiller_cch_inscrito_id');
    }

    public function bachiller_materia()
    {
        return $this->hasOne(Bachiller_materias::class);
    }

}
