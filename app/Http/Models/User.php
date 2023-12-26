<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'empleado_id',
        'username',
        'password',
        'token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'token',
    ];

    protected $dates = [
        'deleted_at',
    ];


     /**
   * Override parent boot and Call deleting event
   *
   * @return void
   */
   protected static function boot()
   {
     parent::boot();
     if(Auth::check()){

        static::deleting(function($table) {
            foreach ($table->permisos()->get() as $permiso) {
                $permiso->delete();
            }
        });
      }
   }

    public function empleado()
    {
        return $this->belongsTo('App\Http\Models\Empleado');
    }

    public function permisos()
    {
        return $this->hasMany('App\Http\Models\Permiso_programa_user');
    }


    public static function permiso($controlador)
    {
        $user = Auth::user();
        $modulo = Modules::where('slug',$controlador)->first();
        $permisos = Permission_module_user::where('user_id',$user->id)->where('module_id',$modulo->id)->first();
        $permiso = Permission::find($permisos->permission_id)->name;
        return $permiso;
    }
}
