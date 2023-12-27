<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Referencia extends Model
{

    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referencias';


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
        'programa_id',
        'refNum',
        'refAnioPer',
        'refConcPago',
        'refFechaVenc',
        'refImpTotal',
        'refImpConc',
        'refImpBeca',
        'refImpPP',
        'refImpAntCred',
        'refImpRecar',
        'refUsuarioAplico',
        'refFechaAplico',
        'refEstado'
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

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }


}