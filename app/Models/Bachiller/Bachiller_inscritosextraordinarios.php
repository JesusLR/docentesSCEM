<?php

namespace App\Models\Bachiller;

use App\Models\Alumno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_inscritosextraordinarios extends Model
{
    use SoftDeletes;

   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_inscritosextraordinarios';


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
        'extraordinario_id',
        'iexFecha',
        'iexCalificacion',
        'iexEstado',
        'iexFolioHistorico'
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

    public function optativa()
    {
        return $this->belongsTo(Optativa::class);

    }

    public function bachiller_extraordinario()
    {
        return $this->belongsTo(Bachiller_extraordinarios::class, "extraordinario_id");
    }

}