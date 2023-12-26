<?php

namespace App\Http\Models\Primaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_expediente_perfiles_contenidos extends Model
{
    use SoftDeletes;

    protected $table = 'primaria_expediente_perfiles_contenidos';

    protected $guarded = ['id'];

    protected $fillable = [
        'primaria_expediente_perfiles_id',
        'primaria_contenidos_fundamentales_id',
        'primaria_contenidos_calificadores_id',
        'observacion_contenido',
        'user_docente_id'  
    ];

    protected $dates = [
        'deleted_at',
    ];

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
            });
        }
    }

    // public function alumno()
    // {
    //     return $this->belongsTo(Alumno::class);
    // }
}
