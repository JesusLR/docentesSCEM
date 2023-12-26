<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Preescolar_inscrito extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'preescolar_inscritos';


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
        'trimestre1_faltas',
        'trimestre2_faltas',
        'trimestre3_faltas',
        'trimestre1_observaciones',
        'trimestre2_observaciones',
        'trimestre3_observaciones'
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
            $table->preescolar_calificacion->delete();
        });
    }
   }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function preescolar_grupos()
    {
        return $this->belongsTo(Preescolar_grupo::class);
    }

    public function preescolar_calificacion()
    {
        return $this->hasOne(Preescolar_calificacion::class);
    }


}
