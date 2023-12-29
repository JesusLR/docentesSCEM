<?php

namespace App\Models\Bachiller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

use App\Http\Helpers\GenerarLogs;
use App\Models\Municipio;

class SecundariaProcedencia extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'secundarias';


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
        'municipio_id',
        'secNombre',
        'secHomologada',
        'usuario_at'
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'aluClave' => 'integer',
    ];

    /**
   * Override parent boot and Call deleting event
   *
   * @return void
   */
   protected static function boot()
   {
      parent::boot();

      if (Auth::check()) {
        static::saving(function($table) {
            
          GenerarLogs::crearLogs((Object) [
            "nombreTabla" => $table->getTable(),
            "registroId"  => $table->id,
          ]);

          $table->usuario_at = Auth::user()->id;
        });

        static::updating(function($table) {
          GenerarLogs::crearLogs((Object) [
            "nombreTabla" => $table->getTable(),
            "registroId"  => $table->id,
          ]);

          $table->usuario_at = Auth::user()->id;
        });

        static::deleting(function($table) {
          GenerarLogs::crearLogs((Object) [
            "nombreTabla" => $table->getTable(),
            "registroId"  => $table->id,
          ]);
        });
      }
   }


       
   public function municipio()
   {
       return $this->belongsTo(Municipio::class);
   }


   // SCOPES ------------------------------------------------------

    public function scopeHomologadas($query)
    {
      return $query->where('prepHomologada', 'SI');
    }

}