<?php

namespace App\Http\Models\Primaria;

use App\Http\Models\Alumno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Primaria_expediente_entrevista_inicial extends Model
{
    use SoftDeletes;

    protected $table = 'primaria_expediente_entrevista_inicial';

    protected $guarded = ['id'];

    protected $fillable = [
        'alumno_id',
        'gradoInscrito',
        'tiempoResidencia',
        'apellido1Padre',
        'apellido2Padre',
        'nombrePadre',
        'celularPadre',
        'edadPadre',
        'ocupacionPadre',
        'empresaPadre',
        'correoPadre',
        'apellido1Madre',
        'apellido2Madre',
        'nombreMadre',
        'celularMadre',
        'edadMadre',
        'ocupacionMadre',
        'empresaMadre',
        'correoMadre',
        'estadoCivilPadres',
        'religion',
        'observaciones',
        'condicionFamiliar',
        'tutorResponsable',
        'celularTutor',
        'accidenteLlamar',
        'celularAccidente',
        'integrante1',
        'relacionIntegrante1',
        'edadintegrante1',
        'ocupacionIntegrante1',
        'integrante2',
        'relacionIntegrante2',
        'edadintegrante2',
        'ocupacionIntegrante2',
        'conQuienViveAlumno',
        'direccionViviendaAlumno',
        'situcionLegal',
        'descripcionNinio',
        'apoyoTarea',
        'escuelaAnterior',
        'aniosEstudiados',
        'motivosCambioEscuela',
        'kinder',
        'observacionEscolar',
        'promedio1',
        'promedio2',
        'promedio3',
        'promedio4',
        'promedio5',
        'promedio6',
        'recursamientoGrado',
        'deportes',
        'apoyoPedagogico',
        'obsPedagogico',
        'terapiaLenguaje',
        'obsTerapiaLenguaje',
        'tratamientoMedico',
        'obsTratamientoMedico',
        'hemofilia',
        'epilepsia',
        'kawasaqui',
        'asma',
        'diabetes',
        'cardiaco',
        'dermatologico',
        'alergias',
        'otroTratamiento',
        'tomaMedicamento',
        'cuidadoEspecifico',
        'tratimientoNeurologico',
        'obsTratimientoNeurologico',
        'tratamientoPsicologico',
        'obsTratimientoPsicologico',
        'motivoInscripcionEscuela',
        'conocidoEscuela1',
        'conocidoEscuela2',
        'conocidoEscuela3',
        'referencia1',
        'celularReferencia1',
        'referencia2',
        'celularReferencia2',
        'referencia3',
        'celularReferencia3',
        'obsGenerales',
        'entrevistador'
    ];

    protected $dates = [
        'deleted_at',
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

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}
