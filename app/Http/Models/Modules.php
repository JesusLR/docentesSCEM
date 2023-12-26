<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'modules';

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
        'name',
        'slug',
        'class',
    ];

    public function permissions_module_user()
    {
        return $this->hasMany(Permission_module_user::class);
    }
}