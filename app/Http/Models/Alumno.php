<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Alumno extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alumnos';


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
        'aluClave',
        'aluEstado',
        'aluFechaIngr',
        'aluNivelIngr',
        'aluGradoIngr',
        'aluMatricula',
        'usuario_at'
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'aluClave' => 'integer',
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
            foreach ($table->cursos()->get() as $curso) {
                $curso->delete();
            }
            foreach ($table->referencias()->get() as $referencia) {
                $referencia->delete();
            }
            foreach ($table->usuarios_gim()->get() as $usuario_gim) {
                $usuario_gim->delete();
            }
        });
    }
   }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }

    public function referencias()
    {
        return $this->hasMany(Referencia::class);
    }

    public function usuarios_gim()
    {
        return $this->hasMany(UsuaGim::class);
    }

}