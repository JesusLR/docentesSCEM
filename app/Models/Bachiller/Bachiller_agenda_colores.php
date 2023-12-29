<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_agenda_colores extends Model
{

    protected $table = 'bachiller_agenda_colores';


    protected $fillable = [
        'empleado_id',
        'preesColor'  
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     if(Auth::check()){
    //         static::saving(function($table) {
    //             $table->usuario_at = Auth::user()->id;
    //         });

    //         static::updating(function($table) {
    //             $table->usuario_at = Auth::user()->id;
    //         });

    //         static::deleting(function($table) {
    //             $table->usuario_at = Auth::user()->id;
    //         });
    //     }
    // }
}
