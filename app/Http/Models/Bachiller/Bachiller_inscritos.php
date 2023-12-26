<?php

namespace App\Http\Models\Bachiller;

use App\Http\Models\Curso;
use App\Http\Models\Bachiller\Bachiller_grupos;
use App\Http\Models\Bachiller\Bachiller_calificaciones;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_inscritos extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_inscritos';


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
        //     $haycalificaciones = Bachiller_calificaciones::where('bachiller_inscrito_id', "=", $table->id)->get();
        //     //dd($haycalificaciones->isEmpty(), !empty($haycalificaciones));
        //     if(!$haycalificaciones->isEmpty()) {
        //         $table->bachiller_calificacion->where("bachiller_inscrito_id", "=", $table->id)->delete();
        //     }

        // });
    }
   }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function bachiller_grupo()
    {
        return $this->belongsTo(Bachiller_grupos::class, 'bachiller_grupo_id');
    }

    // public function bachiller_calificacion()
    // {
    //     return $this->hasOne(Bachiller_calificaciones::class, 'bachiller_inscrito_id');
    // }

    public function bachiller_materia()
    {
        return $this->hasOne(Bachiller_materias::class);
    }

}
