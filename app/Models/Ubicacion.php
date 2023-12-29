<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Ubicacion extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ubicacion';


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
        'ubiClave',
        'ubiNombre',
        'ubiCalle',
        'ubiCP',
        'municipio_id',
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
            foreach ($table->departamentos()->get() as $departamento) {
            $departamento->delete();
            }
            foreach ($table->aulas()->get() as $aula) {
                $aula->delete();
            }
        });
    }
   }

   public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }

}