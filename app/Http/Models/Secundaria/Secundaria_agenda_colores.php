<?php

namespace App\Http\Models\Secundaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Secundaria_agenda_colores extends Model
{

    protected $table = 'secundaria_agenda_colores';


    protected $fillable = [
        'users_id',
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
