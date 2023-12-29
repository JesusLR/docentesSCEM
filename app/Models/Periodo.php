<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Periodo extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'periodos';


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
        'perNumero',
        'perAnio',
        'perFechaInicial',
        'perFechaFinal',
        'perEstado',
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
            $table->departamento->delete();
            foreach ($table->grupos()->get() as $grupo) {
                $grupo->delete();
            }
            foreach ($table->cgts()->get() as $cgt) {
                $cgt->delete();
            }
            foreach ($table->cursos()->get() as $curso) {
                $curso->delete();
            }
            foreach ($table->paquetes()->get() as $paquete) {
                $paquete->delete();
            }
        });
    }
   }


   public function grupos()
   {
       return $this->hasMany(Grupo::class);
   }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }

    public function cgts()
    {
        return $this->hasMany(Cgt::class);
    }

    public function paquetes()
    {
        return $this->hasMany(Paquete::class);
    }


}