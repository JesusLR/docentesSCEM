<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Materia extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'materias';


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
        'matClave',
        'matNombre',
        'matNombreCorto',
        'matSemestre',
        'matCreditos',
        'matClasificacion',
        'matEspecialidad',
        'matTipoAcreditacion',
        'matPorcentajeParcial',
        'matPorcentajeOrdinario',
        'matNombreOficial',
        'matOrdenVisual',
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
            foreach ($table->optativas()->get() as $optativa) {
                $optativa->delete();
            }
            foreach ($table->prerequisitos()->get() as $prerequisito) {
                $prerequisito->delete();
            }
        });
    }
   }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function extraordinarios()
    {
        return $this->hasMany(Extraordinario::class);
    }

    public function optativas()
    {
        return $this->hasMany(Optativa::class);
    }

    public function prerequisitos()
    {
        return $this->hasMany(Prerequisito::class);
    }

    public function historico()
    {
        return $this->hasMany(Historico::class);
    }

    //AUXILIARES

    public function esAlfabetica() {
        return $this->matTipoAcreditacion == 'A';
    }

    public function esNumerica() {
        return $this->matTipoAcreditacion == 'N';
    }

}