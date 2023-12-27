<?php

namespace App\Models\Secundaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;


class Secundaria_agendas extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'color', 'textColor', 'start', 'usuario_at', 'created_at']; 

    protected $table = 'secundaria_agendas';


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
}
