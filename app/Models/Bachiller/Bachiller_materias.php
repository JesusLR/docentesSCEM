<?php

namespace App\Models\Bachiller;

use App\Models\Plan;
use App\Models\Bachiller\Bachiller_grupos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_materias extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bachiller_materias';


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
        'matVigentePlanPeriodoActual',
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
            foreach ($table->bachiller_grupos()->get() as $grupo) {
            $grupo->delete();
            }
        });
    }
   }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function bachiller_grupos()
    {
        return $this->hasMany(Bachiller_grupos::class, 'empleado_id_docente');
    }

    //AUXILIARES

    public function esAlfabetica() {
        return $this->matTipoAcreditacion == 'A';
    }

    public function esNumerica() {
        return $this->matTipoAcreditacion == 'N';
    }

}
