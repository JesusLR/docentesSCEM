<?php

namespace App\Http\Models\Secundaria;

use App\Http\Models\Departamento;
use App\Http\Models\Periodo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Secundaria_porcentajes extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'secundaria_porcentajes';


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
          'departamento_id',
          'periodo_id',
          'porc_septiembre',
          'porc_octubre',
          'porc_noviembre',
          'porc_periodo1',
          'porc_diciembre',
          'porc_enero',
          'porc_febrero',
          'porc_marzo',
          'porc_periodo2',
          'porc_abril',
          'porc_mayo',
          'porc_junio',
          'porc_periodo3'          
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

   public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }


    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }


}
