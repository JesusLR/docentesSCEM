<?php

namespace App\Models\Primaria;

use App\Models\Primaria\Primaria_empleado;
use App\Models\Primaria\Primaria_materia;
use App\Models\Primaria\Primaria_inscrito;
use App\Models\Periodo;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_grupo extends Model
{
    use SoftDeletes;

   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'primaria_grupos';


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
        'primaria_materia_id',
        'plan_id',
        'periodo_id',
        'gpoGrado',
        'gpoClave',
        'gpoTurno',
        'empleado_id_docente',
        'empleado_id_auxiliar',
        // 'gpoMatClaveComplementaria',
        'gpoMatComplementaria',        
        'gpoFechaExamenOrdinario',
        'gpoHoraExamenOrdinario',
        'gpoCupo',
        'gpoNumeroFolio',
        'gpoNumeroActa',
        'gpoNumeroLibro',
        'grupo_equivalente_id',
        'optativa_id',
        'estado_act',
        'fecha_mov_ord_act',
        'clave_actv',
        'inscritos_gpo',
        'nombreAlternativo',
        'gpoExtraCurr'    
        
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
            foreach ($table->primaria_inscritos()->get() as $inscrito) {
            $inscrito->delete();
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

    public function primaria_materia()
    {
        return $this->belongsTo(Primaria_materia::class);
    }

    public function primaria_materia_asignatura()
    {
        return $this->belongsTo(Primaria_materias_asignaturas::class);
    }

    public function primaria_empleado()
    {
        return $this->belongsTo(Primaria_empleado::class, 'empleado_id_docente');
    }

    public function primaria_inscritos()
    {
        return $this->hasMany(Primaria_inscrito::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }


}