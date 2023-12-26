<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Horario extends Model
{
    use SoftDeletes;
    
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'horarios';


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
        'grupo_id',
        'aula_id',
        'ghDia',
        'ghInicio',
        'ghFinal',
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
   
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
    

}