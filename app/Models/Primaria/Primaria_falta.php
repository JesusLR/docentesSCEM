<?php

namespace App\Models\Primaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Primaria_falta extends Model
{
    protected $table = 'primaria_faltas';


    protected $fillable = [
        'curso_id',
        'retTotalSep',
        'retTotalOct',
        'retTotalNov',
        'retTotalDic',
        'retTotalEne',
        'retTotalFeb',
        'retTotalMar',
        'retTotalAbr',
        'retTotalMay',
        'retTotalJun',
        'falTotalSep',
        'falTotalOct',
        'falTotalNov',
        'falTotalDic',
        'falTotalEne',
        'falTotalFeb',
        'falTotalMar',
        'falTotalAbr',
        'falTotalMay',
        'falTotalJun'     
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
