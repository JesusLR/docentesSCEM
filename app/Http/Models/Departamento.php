<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Departamento extends Model
{

    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departamentos';


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
        'ubicacion_id',
        'depNivel',
        'depClave',
        'depNombre',
        'depNombreCorto',
        'depClaveOficial',
        'depNombreOficial',
        'perActual',
        'perAnte',
        'perSig',
        'depCalMinAprob',
        'depCupoGpo',
        'depTituloDoc',
        'depNombreDoc',
        'depPuestoDoc',
        'depIncorporadoA',
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
            foreach ($table->escuelas()->get() as $escuela) {
                $escuela->delete();
            }
            foreach ($table->periodos()->get() as $periodo) {
                $periodo->delete();
            }
        });
    }
   }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function escuelas()
    {
        return $this->hasMany(Escuela::class);
    }

}