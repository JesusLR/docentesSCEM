<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Empleado extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados';


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
        'persona_id',
        'escuela_id',
        'empHorasCon',
        'empPassword',
        'empCredencial',
        'empNomina',
        'empRfc',
        'empImss',
        'empEstado',
        'empFechaBaja',
        'empCausaBaja',
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
            $table->persona()->delete();
            foreach ($table->grupos()->get() as $grupo) {
                $grupo->delete();
            }
            foreach ($table->escuelas()->get() as $escuela) {
                $escuela->delete();
            }
            foreach ($table->programas()->get() as $programa) {
                $programa->delete();
            }
            foreach ($table->cgts()->get() as $cgt) {
                $cgt->delete();
            }
        });
    }
   }

    public function escuela()
    {
        return $this->belongsTo(Escuela::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function user_docente()
    {
        return $this->hasOne(User_docente::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function escuelas()
    {
        return $this->hasMany(Escuela::class);
    }

    public function programas()
    {
        return $this->hasMany(Programa::class);
    }

    public function cgts()
    {
        return $this->hasMany(Cgt::class);
    }


}