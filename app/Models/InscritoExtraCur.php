<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\Http\Helpers\GenerarLogs;


class InscritoExtraCur extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inscritosextracur';


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
        'curso_id',
        'grupo_id'
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
        static::saving(function($table) {
            // GenerarLogs::crearLogs((Object) [
            //     "nombreTabla" => $table->getTable(),
            //     "registroId"  => $table->id,
            // ]);
            // $table->usuario_at = Auth::user()->id;
        });

        static::updating(function($table) {
            // GenerarLogs::crearLogs((Object) [
            //     "nombreTabla" => $table->getTable(),
            //     "registroId"  => $table->id,
            // ]);
            // $table->usuario_at = Auth::user()->id;
        });

        static::deleting(function($table) {
            // GenerarLogs::crearLogs((Object) [
            //     "nombreTabla" => $table->getTable(),
            //     "registroId"  => $table->id,
            // ]);
            // $table->usuario_at = Auth::user()->id;
            // $table->calificacionextracur->where("inscrito_id", "=", $table->id)->delete();
        });
    }
   }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function calificacionextracur()
    {
        return $this->hasOne(CalificacionExtraCur::class);
    }

    public function historico(){
        return $this->belongsTo(Historico::class);
    }


}