<?php

namespace App\Http\Models\Primaria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Models\Primaria\Primaria_grupo;
use Auth;

class Primaria_grupos_planeaciones extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'primaria_grupos_planeaciones';


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
        'primaria_grupo_id',
        'semana_inicio',
        'semana_fin',
        'bimestre',
        'trimestre',
        'mes',
        'frase_mes',
        'valor_mes',
        'norma_urbanidad',
        'objetivo_general',
        'bloque',
        'objetivo_particular',
        'notas_observaciones'
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

        if (Auth::check()) {
            static::saving(function ($table) {
                $table->usuario_at = Auth::user()->id;
            });

            static::updating(function ($table) {
                $table->usuario_at = Auth::user()->id;
            });

            static::deleting(function ($table) {
                $table->usuario_at = Auth::user()->id;
            });
        }
    }

    public function primaria_grupo()
    {
        return $this->belongsTo(Primaria_grupo::class, 'primaria_grupo_id');
    }
}
