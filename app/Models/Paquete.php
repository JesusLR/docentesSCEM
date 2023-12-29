<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Paquete extends Model
{
    use SoftDeletes;
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paquetes';


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
        'periodo_id',
        'plan_id',
        'semestre',
        'consecutivo',
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
            foreach ($table->paquetes_detalle()->get() as $paquete_detalle) {
                $paquete_detalle->delete();
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

    public function paquetes_detalle()
    {
        return $this->hasMany(Paquete_detalle::class);
    }

}