<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Grupo extends Model
{
    use SoftDeletes;

   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grupos';


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
        'materia_id',
        'plan_id',
        'gpoSemestre',
        'gpoClave',
        'gpoTurno',
        'empleado_id',
        'empleado_sinodal',
        'gpoMatClaveComplementaria',
        'gpoFechaExamenOrdinario',
        'gpoHoraExamenOrdinario',
        'gpoCupo',
        'gpoInscritos',
        'gpoProgClaveEquivalente',
        'gpoPlanClaveEquivalente',
        'gpoMatClaveEquivalente',
        'gpoClaveEquivalente',
        'gpoEmpClaveSinodal',
        'gpoNumeroFolio',
        'gpoNumeroActa',
        'gpoNumeroLibro',
        'gpoNombreOficialMateria',
        'optativa_id'
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
            foreach ($table->inscritos()->get() as $inscrito) {
            $inscrito->delete();
            }
            foreach ($table->horarios()->get() as $horario) {
                $horario->delete();
            }
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

    public function optativa()
    {
        return $this->belongsTo(Optativa::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function inscritos()
    {
        return $this->hasMany(Inscrito::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function paquetes_detalle()
    {
        return $this->hasMany(Paquete_detalle::class);
    }
}

