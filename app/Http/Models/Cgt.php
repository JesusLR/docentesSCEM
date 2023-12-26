<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Cgt extends Model
{

    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cgt';


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
        'plan_id',
        'periodo_id',
        'cgtGradoSemestre',
        'cgtGrupo',
        'cgtTurno',
        'cgtDescripcion',
        'cgtEstado',
        'cgtCupo',
        'empleado_id',
        'cgtTotalRegistrados',
        'cgtInscritos',
        'cgtPreinscritos',
        'cgtBaja',
        'cgtOtros'
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
            foreach ($table->cursos()->get() as $curso) {
                $curso->delete();
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

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }


}

