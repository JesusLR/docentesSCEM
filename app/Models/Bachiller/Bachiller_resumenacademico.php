<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Helpers\GenerarLogs;
use App\Models\Alumno;
use App\Models\Periodo;
use App\Models\Plan;

class Bachiller_resumenacademico extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_resumenacademico';


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
        'plan_id',
        'resClaveEspecialidad',
        'resPeriodoIngreso',
        'resPeriodoEgreso',
        'resPeriodoUltimo',
        'resUltimoGrado',
        'resCreditosCursados',
        'resCreditosAprobados',
        'resAvanceAcumulado',
        'resPromedioAcumulado',
        'resEstado',
        'resFechaIngreso',
        'resFechaEgreso',
        'resFechaBaja',
        'resRazonBaja',
        'resObservaciones',
        'usuario_id',
        'created_at',
        'updated_at'
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
            GenerarLogs::crearLogs((Object) [
                "nombreTabla" => $table->getTable(),
                "registroId"  => $table->id,
            ]);
            $table->usuario_id = Auth::user()->id;
        });

        static::updating(function($table) {
            GenerarLogs::crearLogs((Object) [
                "nombreTabla" => $table->getTable(),
                "registroId"  => $table->id,
            ]);
            $table->usuario_id = Auth::user()->id;
        });

        static::deleting(function($table) {
            GenerarLogs::crearLogs((Object) [
                "nombreTabla" => $table->getTable(),
                "registroId"  => $table->id,
            ]);
            $table->usuario_id = Auth::user()->id;
        });
    }
   }


    public function alumno()
    {
      return $this->belongsTo(Alumno::class);
    }

    public function plan()
    {
      return $this->belongsTo(Plan::class);
    }

    public function periodoIngreso(){
        return $this->belongsTo(Periodo::class, 'resPeriodoIngreso');
    }

    public function periodoEgreso(){
        return $this->belongsTo(Periodo::class,'resPeriodoEgreso');
    }

    public function periodoUltimo(){
        return $this->belongsTo(Periodo::class,'resPeriodoUltimo');
    }

}