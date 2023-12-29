<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Programa extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'programas';


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
        'escuela_id',
        'empleado_id',
        'progClave',
        'progNombre',
        'progNombreCorto',
        'progClaveSegey',
        'progClaveEgre',
        'progTituloOficial',
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
            foreach ($table->planes()->get() as $plan) {
                $plan->delete();
            }
            foreach ($table->permisos()->get() as $permiso) {
                $permiso->delete();
            }
            foreach ($table->referencias()->get() as $referencia) {
                $referencia->delete();
            }
        });
    }
   }

   public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function escuela()
    {
        return $this->belongsTo(Escuela::class);
    }

    public function planes()
    {
        return $this->hasMany(Plan::class);
    }

    public function permisos()
    {
        return $this->hasMany(Permiso_programa_user::class);
    }

    public function referencias()
    {
        return $this->hasMany(Referencia::class);
    }

}