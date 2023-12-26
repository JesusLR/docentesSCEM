<?php

namespace App\Http\Controllers\Primaria;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Alumno;
use App\Http\Models\Pais;
use App\Http\Models\Primaria\Primaria_alumnos_entrevista;
use App\Http\Models\Primaria\Primaria_expediente_entrevista_inicial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class PrimariaAlumnosEntrevistaInicialController extends Controller
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
        return view('primaria.entrevista_inicial.show-list');
    }

    public function list()
    {
        $alumno_entrevista = Primaria_expediente_entrevista_inicial::select('primaria_expediente_entrevista_inicial.*',
        'alumnos.aluClave', 'personas.perApellido1', 'personas.perApellido2', 'personas.perNombre', 'personas.perCurp')
        ->join('alumnos', 'primaria_expediente_entrevista_inicial.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id');


        return DataTables::of($alumno_entrevista)
        ->filterColumn('clave_pago',function($query,$keyword){
                $query->whereRaw("CONCAT(aluClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('clave_pago',function($query){
            return $query->aluClave;
        })

        // apellido paterno 
        ->filterColumn('apellido_paterno',function($query,$keyword){
            $query->whereRaw("CONCAT(perApellido1) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('apellido_paterno',function($query){
            return $query->perApellido1;
        })

        // apellido materno 
        ->filterColumn('apellido_materno',function($query,$keyword){
            $query->whereRaw("CONCAT(perApellido2) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('apellido_materno',function($query){
            return $query->perApellido2;
        })

        // nombres 
        ->filterColumn('nombres_alumno',function($query,$keyword){
            $query->whereRaw("CONCAT(perNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('nombres_alumno',function($query){
            return $query->perNombre;
        })

        // curp alumno 
        ->filterColumn('curp_alumno',function($query,$keyword){
            $query->whereRaw("CONCAT(perCurp) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('curp_alumno',function($query){
            return $query->perCurp;
        })

        ->addColumn('action',function($query){
            return '<a href="primaria_entrevista_inicial/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="primaria_entrevista_inicial/'.$query->id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
            
            <a href="primaria_entrevista_inicial/imprimir/' . $query->id . '" target="_blank" class="button button--icon js-button js-ripple-effect" title="Entrevista inicial" >
                <i class="material-icons">picture_as_pdf</i>
            </a>

            <form id="delete_' . $query->id . '" action="primaria_entrevista_inicial/' . $query->id . '" method="POST" style="display:inline; display:none;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <a href="#" data-id="'  . $query->id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                    <i class="material-icons">delete</i>
                </a>
            </form>
            ';
        })->make(true);

    }

    public function agregarEntrevista()
    {
        // obtiene alumnos pertenecientes al departamento primaria 
        $alumnos =  DB::select("SELECT DISTINCT alumnos.id as alumno_id,
        alumnos.aluClave,
        personas.perNombre,
        personas.perApellido1,
        personas.perApellido2
        FROM cursos as cursos
        INNER JOIN alumnos as alumnos on alumnos.id = cursos.alumno_id
        INNER JOIN periodos as periodos on periodos.id = cursos.periodo_id
        INNER JOIN departamentos as departamentos on departamentos.id = periodos.departamento_id
        INNER JOIN personas as personas on personas.id = alumnos.persona_id
        WHERE departamentos.depClave = 'PRI'
        ORDER BY personas.perApellido1 ASC");

        $paises = Pais::get();

        $user_empleado = User::with('empleado.persona')->where('id', auth()->id())->first();
        $empleado = $user_empleado->empleado->persona->perNombre.' '.$user_empleado->empleado->persona->perApellido1.' '.$user_empleado->empleado->persona->perApellido2;


        return view('primaria.entrevista_inicial.crear-entrevista', [
            'alumnos' => $alumnos,
            'paises'  => $paises,
            'empleado' => $empleado
        ]);
    }

    public function getDatosAlumno(Request $request, $id)
    {
        if($request->ajax()){

            $alumnos = Alumno::select('alumnos.id', 'personas.perNombre', 'personas.perApellido1', 
            'personas.perApellido2', 'personas.perFechaNac', 'personas.municipio_id', 'municipios.munNombre',
            'estados.edoNombre', 'paises.paisNombre')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('municipios', 'personas.municipio_id', '=', 'municipios.id')
            ->join('estados', 'municipios.estado_id', '=', 'estados.id')
            ->join('paises', 'estados.pais_id', '=', 'paises.id')
            ->where('alumnos.id', '=', $id)
            ->get();

            // return response()->json($alumnos);
            return response()->json($alumnos);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paises = Pais::get();
  
        return view('primaria.entrevista_inicial.create', [
            'paises' => $paises
        ]);
    }

    public function guardarEntrevista(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'celularPadre'          => 'min:10',
            'celularMadre'          => 'min:10',
            'celularTutor'          => 'min:10',
            'cecularReferencia1'    => 'nullable|min:10',
            'cecularReferencia2'    => 'nullable|min:10',
            'cecularReferencia2'    => 'nullable|min:10',

        ],
        [
            // 'expCurpAlumno.unique'          => "La CURP ya se encuentra registrada",
            // 'expCelularTutorMadre.min'      => "El celular de la madre debe contener al menos 10 dígitos",
            // 'expTelefonoCasaTutorMadre.min' => "El télefono de la madre debe contener al menos 7 dígitos",
            'celularPadre.min'      => "El celular del padre debe contener al menos 10 dígitos",
            'celularMadre.min'      => "El celular de la madre debe contener al menos 10 dígitos",
            'celularTutor.min'      => "El celular del tutor debe contener al menos 10 dígitos",
            'cecularReferencia1.min'      => "El celular de la referencia 1 debe contener al menos 10 dígitos",
            'cecularReferencia2.min'      => "El celular de la referencia 2 debe contener al menos 10 dígitos",
            'cecularReferencia3.min'      => "El celular de la referencia 3 debe contener al menos 10 dígitos",


            // 'celularPadre.max'      => "El celular del padre debe contener 10 dígitos"
            // 'expTelefonoCasaTutorPadre'     => "El télefono del padre debe contener al menos 7 dígitos"
        ]
        );

        if ($validator->fails()) {
            return redirect ('primaria_entrevista_inicial/create')->withErrors($validator)->withInput();
        }else{
            try {
                Primaria_expediente_entrevista_inicial::create([
                'alumno_id' => $request->alumno_id, 
                'gradoInscrito' => $request->gradoInscrito, 
                'tiempoResidencia' => $request->tiempoResidencia, 
                'apellido1Padre' => $request->apellido1Padre, 
                'apellido2Padre' => $request->apellido2Padre, 
                'nombrePadre' => $request->nombrePadre, 
                'celularPadre' => $request->celularPadre, 
                'edadPadre' => $request->edadPadre, 
                'ocupacionPadre' => $request->ocupacionPadre, 
                'empresaPadre' => $request->empresaPadre, 
                'correoPadre' => $request->correoPadre, 
                'apellido1Madre' => $request->apellido1Madre, 
                'apellido2Madre' => $request->apellido2Madre, 
                'nombreMadre' => $request->nombreMadre, 
                'celularMadre' => $request->celularMadre, 
                'edadMadre' => $request->edadMadre, 
                'ocupacionMadre' => $request->ocupacionMadre, 
                'empresaMadre' => $request->empresaMadre, 
                'correoMadre' => $request->correoMadre, 
                'estadoCivilPadres' => $request->estadoCivilPadres, 
                'religion' => $request->religion, 
                'observaciones' => $request->observaciones, 
                'condicionFamiliar' => $request->condicionFamiliar, 
                'tutorResponsable' => $request->tutorResponsable, 
                'celularTutor' => $request->celularTutor, 
                'accidenteLlamar' => $request->accidenteLlamar, 
                'celularAccidente' => $request->celularAccidente, 
                'integrante1' => $request->integrante1, 
                'relacionIntegrante1' => $request->relacionIntegrante1, 
                'edadintegrante1' => $request->edadintegrante1, 
                'ocupacionIntegrante1' => $request->ocupacionIntegrante1, 
                'integrante2' => $request->integrante2, 
                'relacionIntegrante2' => $request->relacionIntegrante2, 
                'edadintegrante2' => $request->edadintegrante2, 
                'ocupacionIntegrante2' => $request->ocupacionIntegrante2, 
                'conQuienViveAlumno' => $request->conQuienViveAlumno, 
                'direccionViviendaAlumno' => $request->direccionViviendaAlumno,
                'situcionLegal' => $request->situcionLegal, 
                'descripcionNinio' => $request->descripcionNinio, 
                'apoyoTarea' => $request->apoyoTarea, 
                'escuelaAnterior' => $request->escuelaAnterior, 
                'aniosEstudiados' => $request->aniosEstudiados, 
                'motivosCambioEscuela' => $request->motivosCambioEscuela,
                'kinder' => $request->kinder,
                'observacionEscolar' => $request->observacionEscolar,
                'promedio1' => $request->promedio1,
                'promedio2' => $request->promedio2,
                'promedio3' => $request->promedio3,
                'promedio4' => $request->promedio4,
                'promedio5' => $request->promedio5,
                'promedio6' => $request->promedio6,
                'recursamientoGrado' => $request->recursamientoGrado,
                'deportes' => $request->deportes,
                'apoyoPedagogico' => $request->apoyoPedagogico,
                'obsPedagogico' => $request->obsPedagogico,
                'terapiaLenguaje' => $request->terapiaLenguaje,
                'obsTerapiaLenguaje' => $request->obsTerapiaLenguaje,
                'tratamientoMedico' => $request->tratamientoMedico,
                'obsTratamientoMedico' => $request->obsTratamientoMedico,
                'hemofilia' => $request->hemofilia,
                'epilepsia' => $request->epilepsia,
                'kawasaqui' => $request->kawasaqui,
                'asma' => $request->asma,
                'diabetes' => $request->diabetes,
                'cardiaco' => $request->cardiaco,
                'dermatologico' => $request->dermatologico,
                'alergias' => $request->alergias,
                'otroTratamiento' => $request->otroTratamiento,
                'tomaMedicamento' => $request->tomaMedicamento,
                'cuidadoEspecifico' => $request->cuidadoEspecifico,
                'tratimientoNeurologico' => $request->tratimientoNeurologico,
                'obsTratimientoNeurologico' => $request->obsTratimientoNeurologico,
                'tratamientoPsicologico' => $request->tratamientoPsicologico,
                'obsTratimientoPsicologico' => $request->obsTratimientoPsicologico,
                'motivoInscripcionEscuela' => $request->motivoInscripcionEscuela,
                'conocidoEscuela1' => $request->conocidoEscuela1,
                'conocidoEscuela2' => $request->conocidoEscuela2,
                'conocidoEscuela3' => $request->conocidoEscuela3,
                'referencia1' => $request->referencia1,
                'celularReferencia1' => $request->celularReferencia1,
                'referencia2' => $request->referencia2,
                'celularReferencia2' => $request->celularReferencia2,
                'referencia3' => $request->referencia3,
                'celularReferencia3' => $request->celularReferencia3,
                'obsGenerales' => $request->obsGenerales,
                'entrevistador' => $request->entrevistador
                ]);
    
                alert('Escuela Modelo', 'La entrevista se ha creado con éxito','success')->showConfirmButton();
                return redirect('primaria_entrevista_inicial');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_entrevista_inicial/create')->withInput();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'expNombreAlumno'               => 'required',
                'expApellidoAlumno1'            => 'required',
                'expApellidoAlumno2'            => 'required',
                'expFechaNacAlumno'             => 'required',
                'expMunicioAlumno_id'           => 'required',
                'expCurpAlumno'                 => 'required',
                'expEdadAlumno'                 => 'required',
                'expTipoSangre'                 => 'required',
                'expAlergias'                   => 'nullable',
                'expEscuelaProcedencia'         => 'required',
                'expGradosCursados'             => 'required',
                'expAnioRecursado'              => 'required',
                'expTutorMadre'                 => 'required',
                'expEdadTutorMadre'             => 'required',
                'expFechaNacimientoTutorMadre'  => 'required',
                'exMunicipioMadre_id'           => 'required',
                'expOcupacionTutorMadre'        => 'required',
                'expEmpresaLaboralTutorMadre'   => 'nullable',
                'expCelularTutorMadre'          => 'required|min:10',
                'expTelefonoCasaTutorMadre'     => 'nullable|min:7',
                'expEmailTutorMadre'            => 'required',
                'expTutorPadre'                 => 'required',
                'expEdadTutorPadre'             => 'required',
                'expFechaNacimientoTutorPadre'  => 'required',
                'expMunicipioPadre_id'          => 'required',
                'expOcupacionTutorPadre'        => 'required',
                'expEmpresaLaboralTutorPadre'   => 'nullable',    
                'expCelularTutorPadre'          => 'required|min:10',
                'expTelefonoCasaTutorPadre'     => 'nullable|min:7',
                'expEmailTutorPadre'            => 'required',
                'expEstadoCivilPadres'          => 'required',
                'expEstadoCivilOtro'            => 'nullable',
                'expReligonPadres'              => 'required',
                'expNombreFamiliar'             => 'required',
                'expTelefonoFamiliar'           => 'required|min:10',
                'expPersona1Autorizada'         => 'required',
                'expPersona2Autorizada'         => 'nullable',
                'expPadresEgresados'            => 'required',
                'expFamiliarModelo'             => 'required',
                'expNombreFamiliarModelo'       => 'nullable'
            ],
            [
                'expCurpAlumno.unique'          => "La CURP ya se encuentra registrada",
                'expCelularTutorMadre.min'      => "El celular de la madre debe contener al menos 10 dígitos",
                'expTelefonoCasaTutorMadre.min' => "El télefono de la madre debe contener al menos 7 dígitos",
                'expCelularTutorPadre.min'      => "El celular del madre debe contener al menos 10 dígitos",
                'expTelefonoCasaTutorPadre'     => "El télefono del padre debe contener al menos 7 dígitos"
            ]
        );
 
        if ($validator->fails()) {
            return redirect ('primaria_entrevista_inicial')->withErrors($validator)->withInput();
        }else{
            try {
                Primaria_alumnos_entrevista::create([
                'expNombreAlumno' => $request->expNombreAlumno, 
                'expApellidoAlumno1' => $request->expApellidoAlumno1, 
                'expApellidoAlumno2' => $request->expApellidoAlumno2, 
                'expFechaNacAlumno' => $request->expFechaNacAlumno, 
                'expMunicioAlumno_id' => $request->expMunicioAlumno_id, 
                'expCurpAlumno' => $request->expCurpAlumno, 
                'expEdadAlumno' => $request->expEdadAlumno, 
                'expTipoSangre' => $request->expTipoSangre, 
                'expAlergias' => $request->expAlergias, 
                'expEscuelaProcedencia' => $request->expEscuelaProcedencia, 
                'expGradosCursados' => $request->expGradosCursados, 
                'expAnioRecursado' => $request->expAnioRecursado, 
                'expTutorMadre' => $request->expTutorMadre, 
                'expEdadTutorMadre' => $request->expEdadTutorMadre, 
                'expFechaNacimientoTutorMadre' => $request->expFechaNacimientoTutorMadre, 
                'exMunicipioMadre_id' => $request->exMunicipioMadre_id, 
                'expOcupacionTutorMadre' => $request->expOcupacionTutorMadre, 
                'expEmpresaLaboralTutorMadre' => $request->expEmpresaLaboralTutorMadre, 
                'expCelularTutorMadre' => $request->expCelularTutorMadre, 
                'expTelefonoCasaTutorMadre' => $request->expTelefonoCasaTutorMadre, 
                'expEmailTutorMadre' => $request->expEmailTutorMadre, 
                'expTutorPadre' => $request->expTutorPadre, 
                'expEdadTutorPadre' => $request->expEdadTutorPadre, 
                'expFechaNacimientoTutorPadre' => $request->expFechaNacimientoTutorPadre, 
                'expMunicipioPadre_id' => $request->expMunicipioPadre_id, 
                'expOcupacionTutorPadre' => $request->expOcupacionTutorPadre, 
                'expEmpresaLaboralTutorPadre' => $request->expEmpresaLaboralTutorPadre, 
                'expCelularTutorPadre' => $request->expCelularTutorPadre, 
                'expTelefonoCasaTutorPadre' => $request->expTelefonoCasaTutorPadre, 
                'expEmailTutorPadre' => $request->expEmailTutorPadre, 
                'expEstadoCivilPadres' => $request->expEstadoCivilPadres, 
                'expEstadoCivilOtro' => $request->expEstadoCivilOtro, 
                'expReligonPadres' => $request->expReligonPadres, 
                'expNombreFamiliar' => $request->expNombreFamiliar, 
                'expTelefonoFamiliar' => $request->expTelefonoFamiliar, 
                'expPersona1Autorizada' => $request->expPersona1Autorizada, 
                'expPersona2Autorizada' => $request->expPersona2Autorizada, 
                'expPadresEgresados' => $request->expPadresEgresados, 
                'expFamiliarModelo' => $request->expFamiliarModelo, 
                'expNombreFamiliarModelo' => $request->expNombreFamiliarModelo
                ]);
    
                alert('Escuela Modelo', 'La entrevista se ha creado con éxito','success');
                return redirect()->route('primaria_entrevista_inicial.create');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_entrevista_inicial')->withInput();
            }
        }

        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alumnoEntrevista = Primaria_expediente_entrevista_inicial::where('id', $id)->first();
        // obtiene alumnos pertenecientes al departamento primaria 
        $alumnos =  DB::select("SELECT DISTINCT alumnos.id as alumno_id,
        alumnos.aluClave,
        personas.perNombre,
        personas.perApellido1,
        personas.perApellido2
        FROM cursos as cursos
        INNER JOIN alumnos as alumnos on alumnos.id = cursos.alumno_id
        INNER JOIN periodos as periodos on periodos.id = cursos.periodo_id
        INNER JOIN departamentos as departamentos on departamentos.id = periodos.departamento_id
        INNER JOIN personas as personas on personas.id = alumnos.persona_id
        WHERE departamentos.depClave = 'PRI'
        ORDER BY personas.perApellido1 ASC");

        $paises = Pais::get();

        return view('primaria.entrevista_inicial.show', [
            'alumnos' => $alumnos,
            'paises'  => $paises,
            'alumnoEntrevista' => $alumnoEntrevista
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
        $alumnoEntrevista = Primaria_expediente_entrevista_inicial::where('id', $id)->first();
        // obtiene alumnos pertenecientes al departamento primaria 
        $alumnos =  DB::select("SELECT DISTINCT alumnos.id as alumno_id,
        alumnos.aluClave,
        personas.perNombre,
        personas.perApellido1,
        personas.perApellido2
        FROM cursos as cursos
        INNER JOIN alumnos as alumnos on alumnos.id = cursos.alumno_id
        INNER JOIN periodos as periodos on periodos.id = cursos.periodo_id
        INNER JOIN departamentos as departamentos on departamentos.id = periodos.departamento_id
        INNER JOIN personas as personas on personas.id = alumnos.persona_id
        WHERE departamentos.depClave = 'PRI'
        ORDER BY personas.perApellido1 ASC");

        $paises = Pais::get();

        $user_empleado = User::with('empleado.persona')->where('id', auth()->id())->first();
        $empleado = $user_empleado->empleado->persona->perNombre.' '.$user_empleado->empleado->persona->perApellido1.' '.$user_empleado->empleado->persona->perApellido2;

        return view('primaria.entrevista_inicial.edit', [
            'alumnos' => $alumnos,
            'paises'  => $paises,
            'alumnoEntrevista' => $alumnoEntrevista,
            'empleado' => $empleado
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'celularPadre'          => 'min:10',
            'celularMadre'          => 'min:10',
            'celularTutor'          => 'min:10',
            'cecularReferencia1'    => 'nullable|min:10',
            'cecularReferencia2'    => 'nullable|min:10',
            'cecularReferencia2'    => 'nullable|min:10',

        ],
        [
            // 'expCurpAlumno.unique'          => "La CURP ya se encuentra registrada",
            // 'expCelularTutorMadre.min'      => "El celular de la madre debe contener al menos 10 dígitos",
            // 'expTelefonoCasaTutorMadre.min' => "El télefono de la madre debe contener al menos 7 dígitos",
            'celularPadre.min'      => "El celular del padre debe contener al menos 10 dígitos",
            'celularMadre.min'      => "El celular de la madre debe contener al menos 10 dígitos",
            'celularTutor.min'      => "El celular del tutor debe contener al menos 10 dígitos",
            'cecularReferencia1.min'      => "El celular de la referencia 1 debe contener al menos 10 dígitos",
            'cecularReferencia2.min'      => "El celular de la referencia 2 debe contener al menos 10 dígitos",
            'cecularReferencia3.min'      => "El celular de la referencia 3 debe contener al menos 10 dígitos",


            // 'celularPadre.max'      => "El celular del padre debe contener 10 dígitos"
            // 'expTelefonoCasaTutorPadre'     => "El télefono del padre debe contener al menos 7 dígitos"
        ]
        );

        if ($validator->fails()) {
            return redirect ('primaria_entrevista_inicial/create')->withErrors($validator)->withInput();
        }else{
            try {

                $entrevistaAlumno = Primaria_expediente_entrevista_inicial::where('id', $id)->first();

                $entrevistaAlumno->update([
                'alumno_id' => $entrevistaAlumno->alumno_id, 
                'gradoInscrito' => $request->gradoInscrito, 
                'tiempoResidencia' => $request->tiempoResidencia, 
                'apellido1Padre' => $request->apellido1Padre, 
                'apellido2Padre' => $request->apellido2Padre, 
                'nombrePadre' => $request->nombrePadre, 
                'celularPadre' => $request->celularPadre, 
                'edadPadre' => $request->edadPadre, 
                'ocupacionPadre' => $request->ocupacionPadre, 
                'empresaPadre' => $request->empresaPadre, 
                'correoPadre' => $request->correoPadre, 
                'apellido1Madre' => $request->apellido1Madre, 
                'apellido2Madre' => $request->apellido2Madre, 
                'nombreMadre' => $request->nombreMadre, 
                'celularMadre' => $request->celularMadre, 
                'edadMadre' => $request->edadMadre, 
                'ocupacionMadre' => $request->ocupacionMadre, 
                'empresaMadre' => $request->empresaMadre, 
                'correoMadre' => $request->correoMadre, 
                'estadoCivilPadres' => $request->estadoCivilPadres, 
                'religion' => $request->religion, 
                'observaciones' => $request->observaciones, 
                'condicionFamiliar' => $request->condicionFamiliar, 
                'tutorResponsable' => $request->tutorResponsable, 
                'celularTutor' => $request->celularTutor, 
                'accidenteLlamar' => $request->accidenteLlamar, 
                'celularAccidente' => $request->celularAccidente, 
                'integrante1' => $request->integrante1, 
                'relacionIntegrante1' => $request->relacionIntegrante1, 
                'edadintegrante1' => $request->edadintegrante1, 
                'ocupacionIntegrante1' => $request->ocupacionIntegrante1, 
                'integrante2' => $request->integrante2, 
                'relacionIntegrante2' => $request->relacionIntegrante2, 
                'edadintegrante2' => $request->edadintegrante2, 
                'ocupacionIntegrante2' => $request->ocupacionIntegrante2, 
                'conQuienViveAlumno' => $request->conQuienViveAlumno, 
                'direccionViviendaAlumno' => $request->direccionViviendaAlumno,
                'situcionLegal' => $request->situcionLegal, 
                'descripcionNinio' => $request->descripcionNinio, 
                'apoyoTarea' => $request->apoyoTarea, 
                'escuelaAnterior' => $request->escuelaAnterior, 
                'aniosEstudiados' => $request->aniosEstudiados, 
                'motivosCambioEscuela' => $request->motivosCambioEscuela,
                'kinder' => $request->kinder,
                'observacionEscolar' => $request->observacionEscolar,
                'promedio1' => $request->promedio1,
                'promedio2' => $request->promedio2,
                'promedio3' => $request->promedio3,
                'promedio4' => $request->promedio4,
                'promedio5' => $request->promedio5,
                'promedio6' => $request->promedio6,
                'recursamientoGrado' => $request->recursamientoGrado,
                'deportes' => $request->deportes,
                'apoyoPedagogico' => $request->apoyoPedagogico,
                'obsPedagogico' => $request->obsPedagogico,
                'terapiaLenguaje' => $request->terapiaLenguaje,
                'obsTerapiaLenguaje' => $request->obsTerapiaLenguaje,
                'tratamientoMedico' => $request->tratamientoMedico,
                'obsTratamientoMedico' => $request->obsTratamientoMedico,
                'hemofilia' => $request->hemofilia,
                'epilepsia' => $request->epilepsia,
                'kawasaqui' => $request->kawasaqui,
                'asma' => $request->asma,
                'diabetes' => $request->diabetes,
                'cardiaco' => $request->cardiaco,
                'dermatologico' => $request->dermatologico,
                'alergias' => $request->alergias,
                'otroTratamiento' => $request->otroTratamiento,
                'tomaMedicamento' => $request->tomaMedicamento,
                'cuidadoEspecifico' => $request->cuidadoEspecifico,
                'tratimientoNeurologico' => $request->tratimientoNeurologico,
                'obsTratimientoNeurologico' => $request->obsTratimientoNeurologico,
                'tratamientoPsicologico' => $request->tratamientoPsicologico,
                'obsTratimientoPsicologico' => $request->obsTratimientoPsicologico,
                'motivoInscripcionEscuela' => $request->motivoInscripcionEscuela,
                'conocidoEscuela1' => $request->conocidoEscuela1,
                'conocidoEscuela2' => $request->conocidoEscuela2,
                'conocidoEscuela3' => $request->conocidoEscuela3,
                'referencia1' => $request->referencia1,
                'celularReferencia1' => $request->celularReferencia1,
                'referencia2' => $request->referencia2,
                'celularReferencia2' => $request->celularReferencia2,
                'referencia3' => $request->referencia3,
                'celularReferencia3' => $request->celularReferencia3,
                'obsGenerales' => $request->obsGenerales,
                'entrevistador' => $request->entrevistador
                ]);
    
                alert('Escuela Modelo', 'La entrevista se ha actualizado con éxito','success')->showConfirmButton();
                return redirect('primaria_entrevista_inicial');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_entrevista_inicial')->withInput();
            }
        }

    }

    public function imprimir($id)
    {

        $alumnoEntrevista = Primaria_expediente_entrevista_inicial::where('id', $id)->first();

        $alumno = Alumno::select('alumnos.aluClave','personas.perNombre', 'personas.perApellido1', 'personas.perApellido2',
        'personas.perFechaNac', 'personas.perCurp', 'municipios.munNombre', 'estados.edoNombre', 'paises.paisNombre')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('municipios', 'personas.municipio_id', '=', 'municipios.id')
        ->join('estados', 'municipios.estado_id', '=', 'estados.id')
        ->join('paises', 'estados.pais_id', '=', 'paises.id')
        ->first();

        


        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $anioNacimiento = explode("-", $alumno->perFechaNac);        
        $anoHoy = $fechaActual->format('Y');

        // calcular edad (año actual - año nacimiento alumno)
        $edadCalculada = $anoHoy - $anioNacimiento[0];

        $parametro_NombreArchivo = "pdf_primaria_entrevista_inicial";
        $pdf = PDF::loadView('reportes.pdf.primaria.entrevista_inicial.' . $parametro_NombreArchivo, [
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "alumno" => $alumno,
            "edadCalculada" => $edadCalculada,
            "alumnoEntrevista" => $alumnoEntrevista
        ]);


        // $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }


    public function imprimirBlanco()
    {

        $parametro_NombreArchivo = "pdf_primaria_entrevista_inicial_formato_blanco";
        $pdf = PDF::loadView('reportes.pdf.primaria.entrevista_inicial.' . $parametro_NombreArchivo, [
           
        ]);


        // $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entrevistaAlumno = Primaria_expediente_entrevista_inicial::findOrFail($id);
        try {
            if ($entrevistaAlumno->delete()) {
                alert('Escuela Modelo', 'La entrevista inicial se ha eliminado con éxito', 'success')->showConfirmButton();
                return redirect('primaria_entrevista_inicial');
            } else {
                alert()->error('Error...', 'No se puedo eliminar la entrevista inicial')->showConfirmButton();
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }
    }
}
