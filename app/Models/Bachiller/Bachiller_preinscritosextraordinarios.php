<?php

namespace App\Models\Bachiller;

use App\Models\Alumno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Bachiller_preinscritosextraordinarios extends Model
{
    //
	use SoftDeletes;

	protected $table = 'bachiller_preinscritosextraordinarios';

	protected $fillable = [
		'alumno_id',
		'bachiller_extraordinario_id',
		'bachiller_empleado_id',
		'bachiller_materia_id',
		'aluClave',
		'aluNombre',
		'empNombre',
		'ubiClave',
		'ubiNombre',
		'progClave',
		'progNombre',
		'matClave',
		'matNombre',
		'extFecha',
		'extHora',
		'extPago',
		'folioFichaPago',
		'folioFichaPagoBBVA',
		'folioFichaPagoHSBC',
		'pexEstado',
	];

	protected $dates = [
		'deleted_at'
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

   public function alumno() {
   	return $this->belongsTo(Alumno::class);
   }

   public function bachiller_extraordinario() {
   	return $this->belongsTo(Bachiller_extraordinarios::class);
   }

   public function bachiller_empleado() {
   	return $this->belongsTo(Bachiller_empleados::class);
   }

   public function bachiller_materia() {
   	return $this->belongsTo(Bachiller_materias::class);
   }



}
