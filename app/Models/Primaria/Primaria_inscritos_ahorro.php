<?php

namespace App\Models\Primaria;

use App\Models\Curso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_inscritos_ahorro extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'primaria_inscritos_ahorro';


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
        'primaria_empleado_id',
        'fecha',
        'aplica_mes_nombre',
        'importe',
        'movimiento',
        'saldo_inicial',
        'saldo_final',
        'observacion'
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

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function primaria_empleado()
    {
        return $this->belongsTo(Primaria_empleado::class);
    }
}
