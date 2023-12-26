<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso_programa_user extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permisos_programas_user';

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
        'user_id',
        'programa_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }
}