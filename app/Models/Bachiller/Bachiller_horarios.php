<?php

namespace App\Models\Bachiller;

use App\Models\Aula;
use App\Models\Bachiller\Bachiller_grupos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_horarios extends Model
{
    use SoftDeletes;
    
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_horarios';


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
        'gMinFinal',
        'gMinInicio',
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
 

    public function bachiller_grupo_merida()
    {
        return $this->belongsTo(Bachiller_grupos::class, "grupo_id");
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
    

}