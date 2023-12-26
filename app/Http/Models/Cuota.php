<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Cuota extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cuotas';


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
        'cuoTipo',
        'dep_esc_prog_id',
        'cuoAnio',
        'cuoImportePadresFamilia',
        'cuoImporteOrdinarioUady',
        'cuoImporteMensualidad10',
        'cuoImporteMensualidad11',
        'cuoImporteMensualidad12',
        'cuoImporteInscripcion1',
        'cuoFechaLimiteInscripcion1',
        'cuoImporteInscripcion2',
        'cuoFechaLimiteInscripcion2',
        'cuoImporteInscripcion3',
        'cuoFechaLimiteInscripcion3',
        'cuoImporteVencimiento',
        'cuoImporteProntoPago',
        'cuoDiasProntoPago',
        'cuoNumeroCuenta',
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
        });
    }
   }

}
