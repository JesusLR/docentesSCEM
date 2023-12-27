<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Plan extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planes';


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
        'programa_id',
        'planClave',
        'planPeriodos',
        'planNumCreditos',
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
            foreach ($table->grupos()->get() as $grupo) {
                $grupo->delete();
            }
            foreach ($table->cgts()->get() as $cgt) {
                $cgt->delete();
            }
            foreach ($table->materias()->get() as $materia) {
                $materia->delete();
            }
            foreach ($table->paquetes()->get() as $paquete) {
                $paquete->delete();
            }
            foreach ($table->acuerdos()->get() as $acuerdo) {
                $acuerdo->delete();
            }
        });
    }
   }

   public function grupos()
   {
       return $this->hasMany(Grupo::class);
   }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function cgts()
    {
        return $this->hasMany(Cgt::class);
    }

    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

    public function paquetes()
    {
        return $this->hasMany(Paquete::class);
    }

    public function acuerdos()
    {
        return $this->hasMany(Acuerdo::class);
    }

}