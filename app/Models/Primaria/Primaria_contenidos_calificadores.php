<?php

namespace App\Models\Primaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_contenidos_calificadores extends Model
{
    use SoftDeletes;

    protected $table = 'primaria_contenidos_calificadores';

    protected $guarded = ['id'];

    protected $fillable = [
        'calificador'        
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
