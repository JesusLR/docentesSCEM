<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_module_user extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_module_user';

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
        'permission_id',
        'module_id',
        'user_id',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
    public function module()
    {
        return $this->belongsTo(Modules::class);
    }
}