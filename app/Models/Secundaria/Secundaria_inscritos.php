<?php

namespace App\Models\Secundaria;

use App\Models\Curso;
use App\Models\Secundaria\Secundaria_grupos;
use App\Models\Secundaria\Secundaria_calificaciones;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Secundaria_inscritos extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'secundaria_inscritos';


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
          'grupo_id',
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
          'inscPromedioPorMeses',
          'inscPromedioBimestre1',
          'inscPromedioBimestre2',
          'inscPromedioBimestre3',
          'inscPromedioBimestre4',
          'inscPromedioBimestre5',
          'inscPromedioPorBimestre',
          'inscTrimestre1',
          'inscTrimestre2',
          'inscTrimestre3',
          'inscPromedioTrim',
          'inscFaltasInjSep',
          'inscFaltasInjOct',
          'inscFaltasInjOct',
          'inscFaltasInjNov',
          'inscFaltasInjDic',
          'inscFaltasJusEne',
          'inscFaltasJusFeb',
          'inscFaltasJusMar',
          'inscFaltasJusAbr',
          'inscFaltasJusMay',
          'inscFaltasJusJun',
          'inscConductaSep',
          'inscConductaOct',
          'inscConductaNov',
          'inscConductaNov',
          'inscConductaDic',
          'inscConductaEne',
          'inscConductaFeb',
          'inscConductaMar',
          'inscConductaAbr',
          'inscConductaMay',
          'inscConductaJun',
          'inscParticipacionSep',
          'inscParticipacionOct',
          'inscParticipacionNov',
          'inscParticipacionDic',
          'inscParticipacionEne',
          'inscParticipacionFeb',
          'inscParticipacionMar',
          'inscParticipacionAbr',
          'inscParticipacionMay',
          'inscParticipacionJun',
          'inscConvivenciaSep',
          'inscConvivenciaOct',
          'inscConvivenciaNov',
          'inscConvivenciaDic',
          'inscConvivenciaEne',
          'inscConvivenciaFeb',
          'inscConvivenciaMar',
          'inscConvivenciaAbr',
          'inscConvivenciaMay',
          'inscConvivenciaJun',
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

        // static::deleting(function($table) {
        //     $table->usuario_at = Auth::user()->id;
        //     $table->preescolar_calificacion->delete();
        // });
    }
   }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function secundaria_grupo()
    {
        return $this->belongsTo(Secundaria_grupos::class, 'grupo_id');
    }

    public function secundaria_calificacion()
    {
        return $this->hasOne(Secundaria_calificaciones::class);
    }

    public function secundaria_materia()
    {
        return $this->hasOne(Secundaria_materias::class);
    }

}
