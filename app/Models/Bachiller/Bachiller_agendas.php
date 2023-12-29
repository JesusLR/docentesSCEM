<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;


class Bachiller_agendas extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'title', 
    'description', 
    'color', 
    'textColor', 
    'start', 
    'end',
    'usuario_at', 
    'created_at',
    'updated_at']; 

    protected $table = 'bachiller_agendas';


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
