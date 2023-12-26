<?php

namespace App\Http\Controllers\Primaria;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Alumno;
use App\Http\Models\Pais;
use App\Http\Models\Primaria\Primaria_alumnos_historia_clinica;
use App\Http\Models\Primaria\Primaria_alumnos_historia_clinica_escolares;
use App\Http\Models\Primaria\Primaria_alumnos_historia_clinica_familiares;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;


class PrimariaAlumnosHistoriaClinicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $primaria_alumnos_historia_clinica = Primaria_alumnos_historia_clinica::get();

        return view('primaria.historia_clinica.show-list', [
        ]);
    }

    public function list()
    {
        if (Auth::user()->empleado->escuela->departamento->depClave == "PRI")
        {
            $primaria_alumnos_historia_clinica = Primaria_alumnos_historia_clinica::select(
                'primaria_alumnos_historia_clinica.id as historia_id',
                'alumnos.aluClave',
                'alumnos.id as alumno_id',
                'alumnos.aluMatricula',
                'personas.perNombre',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perCurp'
            )
                ->join('alumnos', 'primaria_alumnos_historia_clinica.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->latest('primaria_alumnos_historia_clinica.created_at');
        }

        $permisos = (User::permiso("curso") == "A" || User::permiso("curso") == "B");
        $permisoA = (User::permiso("curso") == "A");

        return DataTables::of($primaria_alumnos_historia_clinica)
            ->filterColumn('perNombre', function ($query, $keyword) {
                return $query->whereHas('persona', function ($query) use ($keyword) {
                    $query->whereRaw("CONCAT(perNombre) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('perNombre', function ($query) {
                return $query->perNombre;
            })
            ->filterColumn('perApellido1', function ($query, $keyword) {
                return $query->whereHas('persona', function ($query) use ($keyword) {
                    $query->whereRaw("CONCAT(perApellido1) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('perApellido1', function ($query) {
                return $query->perApellido1;
            })
            ->filterColumn('perApellido2', function ($query, $keyword) {
                return $query->whereHas('persona', function ($query) use ($keyword) {
                    $query->whereRaw("CONCAT(perApellido2) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('perApellido2', function ($query) {
                return $query->perApellido2;
            })


            ->addColumn('action', function ($query) use ($permisos, $permisoA) {

                $btnEliminarHistorial = "";
                if ($query->curEstado == "B") {
                    $btnEliminarHistorial = '<form style="display: inline-block;" id="delete_' . $query->historia_id . '" action="curso/' . $query->historia_id . '" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <a href="#" data-id="' . $query->historia_id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                            <i class="material-icons">delete</i>
                        </a>
                    </form>';
                }

                $btnMostrarAcciones = '';
                if (Auth::user()->empleado->escuela->departamento->depClave == "PRI") {
                    $btnMostrarAcciones = '
                    <a href="/primaria_historia_clinica/'.$query->historia_id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                        <i class="material-icons">visibility</i>
                    </a>

                    <a href="/primaria_historia_clinica/' . $query->historia_id . '/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                        <i class="material-icons">edit</i>
                    </a>';
                } else {
                    $btnMostrarAcciones = '

                    <a href="/curso/' . $query->curso_id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                        <i class="material-icons">visibility</i>
                    </a>'
                        .

                        '<a href="/curso/' . $query->curso_id . '/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                        <i class="material-icons">edit</i>
                    </a>'                    .

                        $btnEliminarHistorial;
                }

                return
                    $btnMostrarAcciones;
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // obtiene los datos de la tabla primaria_alumnos_historia_clinica
        $historia = Primaria_alumnos_historia_clinica::select('primaria_alumnos_historia_clinica.id','primaria_alumnos_historia_clinica.alumno_id',
        'primaria_alumnos_historia_clinica.gradoInscrito', 'primaria_alumnos_historia_clinica.edadAlumno',
        'alumnos.persona_id','alumnos.aluClave','personas.perCurp', 'personas.perApellido1', 'personas.perApellido2', 'personas.perNombre',
        'personas.perFechaNac', 'municipios.id as municipio_id', 'municipios.munNombre', 'estados.edoNombre','paises.id as pais_id' ,'paises.paisNombre')
        ->join('alumnos', 'alumnos.id', '=', 'primaria_alumnos_historia_clinica.alumno_id')
        ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
        ->join('municipios', 'municipios.id', '=', 'personas.municipio_id')
        ->join('estados', 'estados.id', '=', 'municipios.estado_id')
        ->join('paises', 'paises.id', '=', 'estados.pais_id')
        ->findOrFail($id);




        $familia = Primaria_alumnos_historia_clinica_familiares::select()->where('historia_primaria_id', '=', $historia->id)->first();



        $escolar = Primaria_alumnos_historia_clinica_escolares::select()->where('historia_primaria_id', '=', $historia->id)->first();



        return view('primaria.historia_clinica.show', [
            'historia' => $historia,
            'familia' => $familia,
            'escolar' => $escolar
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alumnos = Alumno::select('alumnos.id', 'personas.perNombre', 'personas.perApellido1', 'personas.perApellido2')
            ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
            ->get();

        // obtiene los datos de la tabla primaria_alumnos_historia_clinica
        $historia = Primaria_alumnos_historia_clinica::select('primaria_alumnos_historia_clinica.id','primaria_alumnos_historia_clinica.alumno_id',
        'primaria_alumnos_historia_clinica.gradoInscrito', 'primaria_alumnos_historia_clinica.edadAlumno',
        'alumnos.persona_id','alumnos.aluClave','personas.perCurp', 'personas.perApellido1', 'personas.perApellido2', 'personas.perNombre',
        'personas.perFechaNac', 'municipios.id as municipio_id', 'municipios.munNombre', 'estados.edoNombre','paises.id as pais_id' ,'paises.paisNombre')
        ->join('alumnos', 'alumnos.id', '=', 'primaria_alumnos_historia_clinica.alumno_id')
        ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
        ->join('municipios', 'municipios.id', '=', 'personas.municipio_id')
        ->join('estados', 'estados.id', '=', 'municipios.estado_id')
        ->join('paises', 'paises.id', '=', 'estados.pais_id')
        ->findOrFail($id);


        $paises = Pais::all();


        $familia = Primaria_alumnos_historia_clinica_familiares::select()->where('historia_primaria_id', '=', $historia->id)->first();

        $escolar = Primaria_alumnos_historia_clinica_escolares::select()->where('historia_primaria_id', '=', $historia->id)->first();

        return view('primaria.historia_clinica.edit', [
            'alumnos' => $alumnos,
            'paises' => $paises,
            'historia' => $historia,
            'familia' => $familia,
            'escolar' => $escolar

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Primaria_alumnos_historia_clinica $historia)
    {
        $historia->update([
            'gradoInscrito' => $request->gradoInscrito,
            'edadAlumno' => $request->edadAlumno
        ]);

        // Selecciona la fila de acuerdo al valor del id de hisria clinica
        $familiar = Primaria_alumnos_historia_clinica_familiares::select()->where('historia_primaria_id', '=', $historia->id)->first();

        // Actualiza la tabla primaria_alumnos_historia_clinica_familiares
        $familiar->update([
            'historia_primaria_id'  => $familiar->historia_primaria_id,
            'tiempoResidencia'      => $request->tiempoResidencia,
            'nombresPadre'          => $request->nombresPadre,
            'apellido1Padre'        => $request->apellido1Padre,
            'apellido2Padre'        => $request->apellido2Padre,
            'celularPadre'          => $request->celularPadre,
            'edadPadre'             => $request->edadPadre,
            'ocupacioPadre'         => $request->ocupacioPadre,
            'nombresMadre'          => $request->nombresMadre,
            'apellido1Madre'        => $request->apellido1Madre,
            'apellido2Madre'        => $request->apellido2Madre,
            'celularMadre'          => $request->celularMadre,
            'edadMadre'             => $request->edadMadre,
            'ocupacionMadre'        => $request->ocupacionMadre,
            'estadoCilvilPadres'    => $request->estadoCilvilPadres,
            'observaciones'         => $request->observacionesPadres,
            'religion'              => $request->religion,
            'integrante1'           => $request->integrante1,
            'relacionIntegrante1'   => $request->relacionIntegrante1,
            'edadIntegrante1'       => $request->edadIntegrante1,
            'ocupacionIntegrante1'  => $request->ocupacionIntegrante1,
            'integrante2'           => $request->integrante2,
            'relacionIntegrante2'   => $request->relacionIntegrante2,
            'edadIntegrante2'       => $request->edadIntegrante2,
            'ocupacionIntegrante2'  => $request->ocupacionIntegrante2,
            'integrante3'           => $request->integrante3,
            'relacionIntegrante3'   => $request->relacionIntegrante3,
            'edadIntegrante3'       => $request->edadIntegrante3,
            'ocupacionIntegrante3'  => $request->ocupacionIntegrante3,
            'integrante4'           => $request->integrante4,
            'relacionIntegrante4'   => $request->relacionIntegrante4,
            'edadIntegrante4'       => $request->edadIntegrante4,
            'ocupacionIntegrante4'  => $request->ocupacionIntegrante4,
            'apoyoTareas'           => $request->apoyoTareas,
            'deporteActividad'      => $request->deporteActividad
        ]);

        // Selecciona la fila de acuerdo al valor del id de hisria clinica
        $escolar = Primaria_alumnos_historia_clinica_escolares::select()->where('historia_primaria_id', '=', $historia->id)->first();

        // Actualiza la tabla primaria_alumnos_historia_clinica_escolares
        $escolar->update([
            'historia_primaria_id'     => $escolar->historia_primaria_id,
            'escuelaProcedencia'       => $request->escuelaProcedencia,
            'aniosEstudios'            => $request->aniosEstudios,
            'motivosCambio'            => $request->motivosCambio,
            'kinder'                   => $request->kinder,
            'observaciones'            => $request->observaciones,
            'primaria'                 => $request->primaria,
            'promedioGrado1'           => $request->promedioGrado1,
            'promedioGrado2'           => $request->promedioGrado2,
            'promedioGrado3'           => $request->promedioGrado3,
            'promedioGrado4'           => $request->promedioGrado4,
            'promedioGrado5'           => $request->promedioGrado5,
            'promedioGrado6'           => $request->promedioGrado6,
            'gradoRepetido'            => $request->gradoRepetido,
            'promedioRepetido'         => $request->promedioRepetido,
            'apoyoPedagogico'          => $request->apoyoPedagogico,
            'observacionesApoyo'       => $request->observacionesApoyo,
            'medico'                   => $request->medico,
            'observacionesMedico'      => $request->observacionesMedico,
            'neurologico'              => $request->neurologico,
            'observacionesNerologico'  => $request->observacionesNerologico,
            'psicologico'              => $request->psicologico,
            'observacionesPsicologico' => $request->observacionesPsicologico,
            'motivoInscripcion'        => $request->motivoInscripcion,
            'familiar1'                => $request->familiar1,
            'familiar2'                => $request->familiar2,
            'familiar3'                => $request->familiar3,
            'referencia1'              => $request->referencia1,
            'celularReferencia1'       => $request->celularReferencia1,
            'referencia2'              => $request->referencia2,
            'celularReferencia2'       => $request->celularReferencia2,
            'observacionesGenerales'   => $request->observacionesGenerales,
            'entrevisto'               => $request->entrevisto,
            'ubicacion'                => $request->ubicacion
        ]);


        alert('Escuela Modelo', 'El historial clinico se ha actualizo con éxito','success')->showConfirmButton();
        return redirect()->route('primaria_historia_clinica.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
