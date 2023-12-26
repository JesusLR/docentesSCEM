<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Helpers\GenerarLogs;


class Extraordinario extends Model
{
    use SoftDeletes;

   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'extraordinarios';


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
        'aula_id',
        'optativa_id',
        'empleado_id',
        'empleado_sinodal_id',
        'extFecha',
        'extHora',
        'extNumeroFolio',
        'extNumeroActa',
        'extNumeroLibro',
        'extPago',
        'extAlumnosInscritos',
        'extGrupo'
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
            $table->usuario_at = Auth::user()->id;
        });

        static::updating(function($table) {
            GenerarLogs::crearLogs((Object) [
                "nombreTabla" => $table->getTable(),
                "registroId"  => $table->id,
            ]);
            $table->usuario_at = Auth::user()->id;
        });

        static::deleting(function($table) {
            GenerarLogs::crearLogs((Object) [
                "nombreTabla" => $table->getTable(),
                "registroId"  => $table->id,
            ]);
            $table->usuario_at = Auth::user()->id;
        });
    }
   }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function optativa()
    {
        return $this->belongsTo(Optativa::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function empleadoSinodal()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function inscritos(){
        return $this->hasMany(InscritoExtraordinario::class);
    }
}