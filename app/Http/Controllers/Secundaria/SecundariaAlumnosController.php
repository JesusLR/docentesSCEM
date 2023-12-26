<?php

namespace App\Http\Controllers\Secundaria;

use Auth;
use App\clases\alumnos\MetodosAlumnos;
use App\clases\cgts\MetodosCgt;
use App\clases\departamentos\MetodosDepartamentos;
use App\clases\personas\MetodosPersonas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utils;
use App\Http\Models\Alumno;
use App\Http\Models\Baja;
use App\Http\Models\Beca;
use App\Http\Models\Candidato;
use App\Http\Models\ConceptoBaja;
use App\Http\Models\Curso;
use App\Http\Models\Departamento;
use App\Http\Models\Empleado;
use App\Http\Models\Estado;
use App\Http\Models\MatriculaAnterior;
use App\Http\Models\Minutario;
use App\Http\Models\Municipio;
use App\Http\Models\Pago;
use App\Http\Models\Pais;
use App\Http\Models\Persona;
use App\Http\Models\PreparatoriaProcedencia;
use App\Http\Models\Programa;
use App\Http\Models\Tutor;
use App\Http\Models\Ubicacion;
use App\Models\Modules;
use App\Models\Permission;
use App\Models\Permission_module_user;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Illuminate\Support\Str;
use PDF;

class SecundariaAlumnosController extends Controller
{
   
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $registroUltimoPago = Pago::where("pagFormaAplico", "=", "A")->latest()->first();
        $registroUltimoPago = Carbon::parse($registroUltimoPago->pagFechaPago)->day
        . "/" . Utils::num_meses_corto_string(Carbon::parse($registroUltimoPago->pagFechaPago)->month)
        . "/" . Carbon::parse($registroUltimoPago->pagFechaPago)->year;

        return View('secundaria.alumnos.show-list', [
            "registroUltimoPago" => $registroUltimoPago
        ]);
    }


    public function cambiarMatricula(Request $request)
    {
        $departamentos = Departamento::get();
        $alumno = Alumno::with('persona.municipio')->findOrFail($request->alumnoId);
        $planes = Curso::with("cgt.plan.programa")->where("cursos.alumno_id", "=", $request->alumnoId)->get()->unique("cgt.plan.id");


        if (User::permiso("alumno") == "A" || User::permiso("alumno") == "B" || User::permiso("alumno") == "E") {
            return view('secundaria.alumnos.cambiar-matricula-secundaria', compact('alumno', 'departamentos', "planes"));
        } else {
            alert()->error('Ups...', 'Sin privilegios para esta acción!')->showConfirmButton()->autoClose(2000);
            return redirect('secundaria_alumno');
        }
    }


    public function postCambiarMatricula (Request $request)
    {
        $alumno = new Alumno;
        $alumno = $alumno->where("id", "=", $request->alumnoId)->first();
        $alumno->aluMatricula = $request->aluMatricula;
        $alumno->save();

        if ($alumno->save()) {
            $matriculasanteriores = new MatriculaAnterior();
            $matriculasanteriores->alumno_id = $request->alumnoId;
            $matriculasanteriores->matricNueva = $request->aluMatricula;
            $matriculasanteriores->matricAnterior = $request->matricAnterior;
            $matriculasanteriores->programa_id = $request->plan_id;
            $matriculasanteriores->save();

            alert('Escuela Modelo', 'La matrícula se ha actualizado con éxito', 'success')->showConfirmButton();
            return redirect('secundaria_alumno')->withInput();
        } else {
            alert()->error('Ups...', 'La matrícula no se ha actualizado correctamente')->showConfirmButton();
            return back();
        }
    }

    /**
     * Show alumno list.
     *
     */
    public function list()
    {
        $alumnos = DB::table('alumnos')
            ->select('alumnos.id as alumno_id','alumnos.aluClave','alumnos.aluEstado', 'alumnos.aluFechaIngr',
                'personas.perNombre','personas.perApellido1','personas.perApellido2',
                'personas.perTelefono1','personas.perCurp',
                'resumenacademico.resFechaBaja')
            ->leftJoin('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->leftJoin('resumenacademico', 'alumnos.id','=','resumenacademico.alumno_id')
            ->distinct("alumnos.id")
            ->whereNull('alumnos.deleted_at')
            ->orderBy("alumnos.id", "desc");


        return DataTables::of($alumnos)
            ->filterColumn('perNombre', function($query, $keyword) {
                return $query->whereHas('persona', function($query) use ($keyword) {
                    $query->whereRaw("CONCAT(perNombre) like ?", ["%{$keyword}%"]);
                });
            })
            ->editColumn('aluFechaIngr', static function($query) {
                return Carbon::parse($query->aluFechaIngr)->format('Y-m-d');
            })
            ->addColumn('perNombre', function($query) {
                return $query->perNombre;
            })
            ->filterColumn('perApellido1', function($query, $keyword) {
                return $query->whereHas('persona', function($query) use ($keyword) {
                    $query->whereRaw("CONCAT(perApellido1) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('perApellido1', function($query) {
                return $query->perApellido1;
            })
            ->filterColumn('perApellido2', function($query, $keyword) {
                return $query->whereHas('persona', function($query) use ($keyword) {
                    $query->whereRaw("CONCAT(perApellido2) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('perApellido2', function($query) {
                return $query->perApellido2;
            })

            ->addColumn('aluEstado', function($query) {
                switch ($query->aluEstado) {
                    case 'B':
                        return "BAJA";
                        break;
                    case 'R':
                        return "REGULAR";
                        break;
                    case 'E':
                        return "EGRESADO";
                        break;
                    case 'N':
                        return "NUEVO INGRESO";
                        break;
                }
            })
            ->filterColumn('aluEstado', function ($query, $keyword) {

                $estado = "";
                switch ($keyword) {
                    case 'BAJA':
                        $estado = "B";
                        break;
                    case 'REGULAR':
                        $estado = "R";
                        break;
                    case 'EGRESADO':
                        $estado = "E";
                        break;
                    case 'NUEVO INGRESO':
                        $estado = "N";
                        break;
                }

                return $query->where('aluEstado','=',$estado);
            })

            ->filterColumn('resFechaBaja', function ($query, $keyword) {
                return $query->where('resFechaBaja','like','%'.$keyword.'%');
            })
            ->addColumn('resFechaBaja', function ($query) {
                return ($query->resFechaBaja) ? $query->resFechaBaja : '';
            })
            ->addColumn('action', function($query) {
                $btnBorrar = "";
                $btnModificarEstatus = "";
                $btnHistorialPagos   = "";
                $btnAlumnoPagos = "";
                $btn_inscribirse_extraordinario = '';


                if (User::permiso("alumno") == "A") {
                    $btnBorrar = '<form id="delete_' . $query->alumno_id . '" action="secundaria_alumno/' . $query->alumno_id . '" method="POST" style="display:inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <a href="#" data-id="' . $query->alumno_id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                            <i class="material-icons">delete</i>
                        </a>
                    </form>';

                    $btnModificarEstatus = '<a href="#modalEstatusAlumno-secundaria" data-alumno-id="'. $query->alumno_id . '" class="btn-modal-estatus-alumno modal-trigger button button--icon js-button js-ripple-effect" title="Cambiar Estatus Del Alumno">
                        <i class="material-icons">unarchive</i>
                    </a>';
                }

                if (User::permiso("alumno") == "A" || User::permiso("alumno") == "B" || User::permiso("alumno") == "C") {
                    $btnHistorialPagos = '<a href="#modalHistorialPagosAluSecundaria" data-nombres="' . $query->perNombre . " " . $query->perApellido1 . " " . $query->perApellido2 .
                        '" data-aluClave="' . $query->aluClave . '" data-alumno-id="'.$query->alumno_id.'" class="modal-trigger btn-modal-historial-pagos-secundaria button button--icon js-button js-ripple-effect" title="Historial Pagos">
                        <i class="material-icons">attach_money</i>
                    </a>';

                }

                return '
                <a href="secundaria_alumno/' . $query->alumno_id . '" class="button button--icon js-button js-ripple-effect" title="Ver">
                    <i class="material-icons">visibility</i>
                </a>
                <a href="secundaria_alumno/' . $query->alumno_id . '/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                    <i class="material-icons">edit</i>
                </a>'
                . $btnHistorialPagos
                . $btnModificarEstatus
                . $btnBorrar;
            })
        ->make(true);
    }





    public function preparatoriaProcedencia (Request $request)
    {
        return PreparatoriaProcedencia::where("municipio_id", "=", $request->municipio_id)
            ->where("prepHomologada", "=", "SI")
            ->orderBy("prepNombre")
        ->get();
    }


    public function listHistorialPagosAluclave(Request $request, $aluClave) {

        $pagos = Pago::with('concepto')
        ->where('pagClaveAlu', $request->aluClave)->where('pagEstado', 'A')
        ->whereIn('pagConcPago', ["99", "01", "02", "03", "04", "05", "00", "06", "07", "08", "09", "10", "11", "12"])->get()
        ->sortByDesc(static function($pago, $key) {
            return $pago->pagAnioPer.' '.$pago->concepto->ordenReportes;
        });

        return DataTables::of($pagos)
        ->addColumn('conpNombre', static function(Pago $pago) {
            return $pago->pagConcPago.' '.$pago->concepto->conpNombre;
        })
        ->addColumn('pagImpPago', static function(Pago $pago) {
            return '$'.$pago->pagImpPago;
        })
        ->addColumn('pagFechaPago', static function(Pago $pago) {
            return Utils::fecha_string($pago->pagFechaPago, 'mesCorto');
        })->toJson();
    }//listHistorialPagosAluclave.

     /**
     * Show cgts semestre.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAlumnos(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 180);
        if($request->ajax()){
            $alumnos = Alumno::select('alumnos.id as alumno_id', 'alumnos.aluClave', 'alumnos.aluEstado',
                'personas.perNombre', 'personas.perApellido1', 'personas.perApellido2', 'personas.perTelefono1')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->where('aluEstado', '!=', 'B')
                ->whereIn('aluEstado', ['E','R', 'N'])
            ->get();

            //$alumnos = Alumno::with('persona:id,perNombre,perApellido1,perApellido2')->where('aluEstado','!=','B')->where('aluEstado','!=','E')->get();
            return response()->json($alumnos);
        }
    }

    public function getMultipleAlumnosByFilter(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 180);
        if($request->ajax()){
            $alumnos = Alumno::with("persona")
                ->where('aluEstado', '!=', 'B')
                ->whereIn('aluEstado', ['E','R', 'N'])
                ->whereHas('persona', function($query) use ($request) {
                    $query->whereRaw("CONCAT(perNombre, ' ', perApellido1, ' ', perApellido2) like ?", ["%{$request->nombreAlumno}%"]);
                });

            if ($request->aluClave) {
                $alumnos = $alumnos->where('aluClave', '=', $request->aluClave);
            }

            $alumnos = $alumnos->get();


            //$alumnos = Alumno::with('persona:id,perNombre,perApellido1,perApellido2')->where('aluEstado','!=','B')->where('aluEstado','!=','E')->get();
            return response()->json($alumnos);
        }
    }
    public function getAlumnosByFilter(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 180);
        if($request->ajax()){
            $alumnos = Alumno::select('alumnos.id as alumno_id', 'alumnos.aluClave', 'alumnos.aluEstado',
                'personas.perNombre', 'personas.perApellido1', 'personas.perApellido2', 'personas.perTelefono1')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->where('aluEstado', '!=', 'B')
                ->whereIn('aluEstado', ['E','R', 'N'])
                ->whereRaw("CONCAT(aluClave, ' ', perNombre, ' ', perApellido1, ' ', perApellido2) like ?", ["%{$request->nombreAlumno}%"])
            ->first();

            //$alumnos = Alumno::with('persona:id,perNombre,perApellido1,perApellido2')->where('aluEstado','!=','B')->where('aluEstado','!=','E')->get();
            return response()->json($alumnos);
        }
    }

    public function getAlumnoByClave(Request $request, $aluClave)
    {
        $alumno = Alumno::with('persona')->where('aluClave', $aluClave)
        ->where('aluEstado', '<>', 'B')
        ->whereIn('aluEstado', ['E', 'R', 'N'])->first();
        if($request->ajax()) {
            return response()->json($alumno);
        }
    }

    public function getAlumnoById(Request $request)
    {
        if($request->ajax()){
            $alumno = Alumno::with("persona.municipio.estado.pais", "preparatoria.municipio.estado.pais")->where('id', '=', $request->alumnoId)->first();
            return response()->json($alumno);
        }
    }

    public function conceptosBaja(Request $request)
    {
        $conceptoBaja = ConceptoBaja::all();
        return response()->json($conceptoBaja);
    }

    public function cambiarEstatusAlumno(Request $request)
    {
        $alumnoId = $request->alumnoId;
        $aluEstado = $request->aluEstado;

        $alumno = Alumno::where("id", "=", $alumnoId)->first();

        $curso = Curso::with(['cgt.plan.programa.escuela.departamento.ubicacion','periodo'])->where('alumno_id',$alumno->id)
        ->where('curEstado','<>','B')->latest('curFechaRegistro')->first();
        //Si se selecciona baja se creará o actualizará el alumno en resumen académico
        if($aluEstado == 'B'){
            if(!is_null($curso)){

                    try {
                        Baja::create([
                            'curso_id'             => $curso->id,
                            'bajTipoBeca'          => $curso->curTipoBeca ? $curso->curTipoBeca: "",
                            'bajPorcentajeBeca'    => $curso->curPorcentajeBeca,
                            'bajObservacionesBeca' => $curso->curObservacionesBeca,
                            'bajFechaRegistro'     => $curso->curFechaRegistro,
                            'bajFechaBaja'         => $request->resFechaBaja,
                            'bajEstadoCurso'       => $curso->curEstado,
                            'bajBajaTotal'         => 'C',
                            'bajRazonBaja'         => $request->conceptosBaja,
                            'bajObservaciones'     => $request->resObservaciones,
                        ]);

                        // alert('Escuela Modelo', 'Alumno dado de baja con éxito','success')->showConfirmButton();
                        // return back();
                    } catch (QueryException $e) {
                        $errorCode = $e->errorInfo[1];
                        $errorMessage = $e->errorInfo[2];
                        alert()->error('Error...'.$errorCode, $errorMessage)->showConfirmButton();

                        return back()->withInput();
                    }
            }else{
                return response()->json(["res" => 0 , "msg" => "El alumno no esta registrado a un curso."]);
            }


        }

        if (!$alumno->aluClave) {
            return response()->json(["res" => 0 , "msg" => "No se puede cambiar estatus del alumno porque no existe clave de pago. Favor de crear un nuevo alumno."]);
        }

        if ($alumno->aluEstado == "E" && $aluEstado == "B") {
            return response()->json(["res" => 0, "msg" => "no se puede dar de baja a un alumno egresado."]);
        }

        $res = Alumno::where("id", "=", $alumnoId)->update(['aluEstado' => $aluEstado]);

        return response()->json(["res" => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $departamentos = Departamentos::buscarSoloAcademicos(1, ['SUP', 'POS', 'DIP', 'PRE', 'PRI'])->unique("depClave");
        $departamentos = MetodosDepartamentos::buscarSoloAcademicos(1, ['SEC'])->unique("depClave");

        $paises = Pais::get();

        return view('secundaria.alumnos.create', compact('departamentos', 'paises'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $fechaActual = Carbon::now('CDT')->format('Y-m-d');
        $esCurpValida = "accepted";
        $perCurpValida = 'required|max:18|unique:personas';
        if ($request->paisId != "1") {
            $esCurpValida = "";
            $perCurpValida  = 'max:18';
        }


        $alumno = Alumno::with("persona")
        ->whereHas('persona', function ($query) use ($request) {
            if ($request->perCurp) {
                $query->where('perCurp', $request->perCurp);
            }
        })
        ->first();

        $aluClave = "";
        if ($alumno) {
            $aluClave = $alumno->aluClave;
        }



        $validator = Validator::make(
            $request->all(),
            [
                'aluClave'      => 'unique:alumnos,aluClave,NULL,id,deleted_at,NULL',
                'persona_id'    => 'unique:alumnos,persona_id,NULL,id,deleted_at,NULL',
                'aluNivelIngr'  => 'required|max:4',
                'aluGradoIngr'  => 'required|max:4',
                'perNombre'     => ['required', 'max:40', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
                'perApellido1'  => ['required', 'max:30', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
                'perApellido2'  => ['nullable', 'max:30', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
                'perCurp'       =>  $perCurpValida,
                'esCurpValida'  => $esCurpValida,
                'perFechaNac'   => 'required|before_or_equal:' . $fechaActual,
                'municipio_id'  => 'required',
                'perSexo'       => 'required',
                'perDirCP'      => 'max:5',
                'perDirCalle'   => 'max:25',
                'perDirNumExt'  => 'max:6',
                'perDirColonia' => 'max:60',
                'perCorreo1'    => 'nullable|email',
                'perTelefono2'  => 'required'
            ],
            [
                'aluClave.unique'   => "El alumno ya existe",
                'persona_id.unique' => "La persona ya existe",
                'perCurp.unique'    => "Ya existe registrado un alumno con esta misma clave CURP. "
                . "Favor de consultar los datos del alumno existente, con su clave registrada: "
                . $aluClave,
                'perCurp.max' => 'El campo de CURP no debe contener más de 18 caracteres',
                'esCurpValida.accepted' => 'La CURP proporcionada no es válida. Favor de verificarla.',
                'perCorreo1.email' => 'Debe proporcionar una dirección de correo válida, Favor de verificar.',
                'perFechaNac.before_or_equal' => 'La fecha de Nacimiento no puede ser mayor a la fecha actual.',

                'perFechaNac.required' => 'La fecha de nacimiento es obligatoria.',
                'aluNivelIngr.required' => 'El nivel de ingreso es obligatorio',
                'aluGradoIngr.required' => 'El grado de ingreso es obligatorio',
                'perNombre.required' => 'El nombre es obligatorio',
                'perNombre.regex' => 'Los nombres no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
                'perApellido1.required' => 'El apellido paterno es obligatorio',
                'perApellido1.regex' => 'El apellido paterno no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
                'perApellido2.regex' => 'El apellido materno no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
                'municipio_id.required' => 'El municipio es obligatorio',
                'perSexo.required' => 'El sexo es obligatorio',
                // 'perDirCP.required' => 'El codigo postal es obligatorio',
                // 'perDirCalle.required' => 'La calle del domicilio es obligatoria',
                // 'perDirNumExt.required' => 'El numero exterior del domicilio es obligatorio',
                // 'perDirColonia.required' => 'La colonia del domicilio es obligatoria',
                // 'perCorreo1.required' => 'El email es obligatorio',
                'perTelefono2.required' => 'El teléfono movil es obligatorio',
                'sec_tipo_escuela.required' => 'El campo Tipo de escuela es obligatorio',
                'sec_nombre_ex_escuela.required' => 'El campo Nombre escuela anterior es obligatorio'



            ]
        );
        // return redirect ('alumno/create')->withErrors($validator)->withInput();

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $existeNombre = Persona::where("perApellido1", "=", $request->perApellido1)
        ->where("perApellido2", "=", $request->perApellido2)
        ->where("perNombre", "=", $request->perNombre)
        ->first();
        if ($existeNombre) {
            alert()->error('Ups ...', 'El nombre y apellidos coincide con nuestra base de datos. Favor de verificar que exista el alumno o empleado')->showConfirmButton();
            return redirect()->back()->withInput();
        }

        $claveAlu = $this->generarClave($request->aluNivelIngr, $request->aluGradoIngr);
        $perCurp = $request->perCurp;
        if ($request->paisId != "1" && $request->perSexo == "M") {
            $perCurp = "XEXX010101MNEXXXA4";
        }
        if ($request->paisId != "1" && $request->perSexo == "F") {
            $perCurp = "XEXX010101MNEXXXA8";
        }

        DB::beginTransaction();
        try {
            $persona = Persona::create([
                'perCurp'        => $perCurp,
                'perApellido1'   => $request->perApellido1,
                'perApellido2'   => $request->perApellido2 ? $request->perApellido2 : "",
                'perNombre'      => $request->perNombre,
                'perFechaNac'    => $request->perFechaNac,
                'municipio_id'   => Utils::validaEmpty($request->municipio_id),
                'perSexo'        => $request->perSexo,
                'perCorreo1'     => $request->perCorreo1,
                'perTelefono1'   => $request->perTelefono1,
                'perTelefono2'   => $request->perTelefono2,
                'perDirCP'       => Utils::validaEmpty($request->perDirCP),
                'perDirCalle'    => $request->perDirCalle,
                'perDirNumInt'   => $request->perDirNumInt,
                'perDirNumExt'   => $request->perDirNumExt,
                'perDirColonia'  => $request->perDirColonia
            ]);

            $alumno = Alumno::create([
                'persona_id'      => $persona->id,
                'aluClave'        => (int) $claveAlu,
                'aluNivelIngr'    => Utils::validaEmpty($request->aluNivelIngr),
                'aluGradoIngr'    => Utils::validaEmpty($request->aluGradoIngr),
                'aluMatricula'    => $request->aluMatricula,
                'preparatoria_id' => 0,
                'candidato_id'    => $request->candidato_id ? $request->candidato_id : null,
                'sec_tipo_escuela' => $request->sec_tipo_escuela,
                'sec_nombre_ex_escuela' => $request->sec_nombre_ex_escuela
            ]);

            if ($request->candidato_id) {
                $candidato = Candidato::findOrFail($request->candidato_id);
                $candidato->update([
                    "candidatoPreinscrito" => "SI",
                ]);
            }

            /* Si el alumno registrado se repite como candidato */
            $nosoymexicano = $request->noSoyMexicano ? $perCurp : $request->input('perCurp');
            DB::update("update candidatos c, personas p set  c.candidatoPreinscrito = 'SI' where c.perCurp = p.perCurp
            and c.perCurp <> 'XEXX010101MNEXXXA8' and c.perCurp <> 'XEXX010101MNEXXXA4' and LENGTH(ltrim(rtrim(c.perCurp))) > 0
            and p.deleted_at is null and p.perCurp = ?", [$nosoymexicano]);



            /*
            * Si existen tutores, se realiza la vinculación a este alumno.
            */
            if ($request->tutores) {
                $tutores = $request->tutores;
                $dataTutores = collect([]);
                foreach ($tutores as $key => $tutor) {
                    $tutor = explode('~', $tutor);
                    $tutNombre = $tutor[0];
                    $tutTelefono = $tutor[1];
                    $tutor = Tutor::where('tutNombre', 'like', '%' . $tutNombre . '%')
                        ->where('tutTelefono', $tutTelefono)->first();
                    if ($tutor) {
                        $dataTutores->push($tutor);
                    }
                }
                MetodosAlumnos::vincularTutores($dataTutores, $alumno);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('secundaria_alumno/create')->withInput();
        }

        DB::commit(); #TEST.

        //datos para la vista de curso.create --------------------------------

        //OBTENER PERMISO DE USUARIO
        $user = Auth::user();
        $modulo = Modules::where('slug', 'curso')->first();
        $permisos = Permission_module_user::where('user_id', $user->id)->where('module_id', $modulo->id)->first();
        $permiso = Permission::find($permisos->permission_id)->name;


        $ubicaciones = Ubicacion::all();

        $tiposIngreso =  [
            'PI' => 'PRIMER INGRESO',
            // 'RO' => 'REPETIDOR',
            'RI' => 'REINSCRIPCIÓN',
            'RE' => 'REINGRESO',
        //     'EQ' => 'REVALIDACIÓN',
        //     'OY' => 'OYENTE',
        //     'XX' => 'OTRO',
        ];

        $planesPago = PLANES_PAGO;
        $estadoCurso = ESTADO_CURSO;
        $opcionTitulo = SI_NO;
        $tiposBeca = Beca::get();

        $campus = $request->campus;
        $departamento = $request->departamento;
        $programa = $request->programa;
        $programaData = Programa::where("id", "=", $programa)->first();

        $escuela = null;
        if ($programaData) {
            $escuela = $programaData->escuela->id;
        }


        $candidato = null;
        if ($request->candidato_id) {
            $candidato = Candidato::where("id", "=", $request->candidato_id)->first();
        }


        return view('secundaria.cursos.create', compact(
            'ubicaciones',
            'planesPago',
            'tiposIngreso',
            'tiposBeca',
            'estadoCurso',
            'permiso',
            'alumno',
            'campus',
            'departamento',
            'programa',
            'escuela',
            'candidato'
        ));
    }//function store.

    private function generarClave($nivel,$grado)
    {
        $now = Carbon::now();
        $sufijo = sprintf("%04d",$this->nuevoSufijo());
        $añoActual = Str::substr($now->year, -2);

        // dd($nivel.$grado.$añoActual.$sufijo);
        return $grado.$nivel.$añoActual.$sufijo;
    }

    private function nuevoSufijo()
    {
        // // BLOQUEA LA TABLA
        DB::connection()->getpdo()->exec("LOCK TABLES clavepagosufijos WRITE");
        // AUMENTA EL PREFIJO
        DB::update("UPDATE clavepagosufijos SET cpsSufijo = cpsSufijo + 1 WHERE cpsIdentificador = 1");
        // VALIDA SI LLEGA A MIL LO REINICIA
        DB::update("UPDATE clavepagosufijos SET cpsSufijo = cpsSufijo % 10000 WHERE cpsIdentificador = 1");
        // SELECCIONA EL PREFIJO
        $sufijo = DB::table('clavepagosufijos')->first()->cpsSufijo;
        // DESBLOQUEA TABLA
        DB::connection()->getpdo()->exec("UNLOCK TABLES");

		return $sufijo;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $departamentos = Departamento::get();
        $alumno = Alumno::with('persona')->findOrFail($id);
        $ultimoCurso = Curso::with('cgt.plan.programa')
        ->where('alumno_id', $alumno->id)->latest('curFechaRegistro')->first();

        $preparatoriaProcedencia = PreparatoriaProcedencia::where("id", "=", $alumno->preparatoria_id)->first();


        return view('secundaria.alumnos.show', compact('alumno', 'departamentos', 'preparatoriaProcedencia', 'ultimoCurso'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paises = Pais::get();
        $departamentos = MetodosDepartamentos::buscarSoloAcademicos()->unique("depClave");
        $alumno = Alumno::with('persona.municipio.estado.pais')->findOrFail($id);
        $pais_id = $alumno->persona->municipio->estado->pais->id;
        $estado_id = $alumno->persona->municipio->estado->id;
        $estados = Estado::where('pais_id',$pais_id)->get();
        $municipios = Municipio::where('estado_id',$estado_id)->get();

        $preparatoriaProcedencia = PreparatoriaProcedencia::where("id", "=", $alumno->preparatoria_id)->first();

        $preparatoria_municipio_id = "";
        $preparatoria_estado_id = "";
        $preparatoria_pais_id = "";
        if ($preparatoriaProcedencia) {
            $preparatoria_municipio_id = $preparatoriaProcedencia->municipio->id;
            $preparatoria_estado_id    = $preparatoriaProcedencia->municipio->estado->id;
            $preparatoria_pais_id      = $preparatoriaProcedencia->municipio->estado->pais->id;
        }

        // dd($preparatoria_estado_id);


        if (User::permiso("alumno") == "A" || User::permiso("alumno") == "B" || User::permiso("alumno") == "C" || User::permiso("alumno") == "E") {
            return view('secundaria.alumnos.edit', compact(
                'alumno', 'departamentos', 'paises', 'estados', 'municipios', 'estado_id',
                'preparatoria_municipio_id', 'preparatoria_estado_id', 'preparatoria_pais_id'));
        }

        alert()->error('Ups...', 'Sin privilegios para esta acción!')->showConfirmButton()->autoClose(5000);
        return redirect()->route('secundaria.secundaria_alumno.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // dd($request->preparatoria_id);

        $esCurpValida = "accepted";
        $perCurpValida = 'required|max:18|unique:personas';
        if ($request->paisId != "1" || $request->perCurpOld == $request->perCurp) {// si pais es diferente de mexico
            $esCurpValida = "";
            $perCurpValida  = 'max:18';
        }

        if ($request->paisId == "1" && ($request->perCurp == "XEXX010101MNEXXXA4" || $request->perCurp == "XEXX010101MNEXXXA8" )) {
            $esCurpValida = "accepted";
            $perCurpValida = 'required|max:18|unique:personas';
        }


        $validator = Validator::make($request->all(), [
            'aluNivelIngr' => 'required|max:4',
            'aluGradoIngr' => 'required|max:4',
            'perNombre' => ['required','max:40', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
            'perApellido1'  => ['required','max:30', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
            'perApellido2'  => ['nullable','max:30', 'regex:/^[A-ZÄËÏÖÜÑ ]+$/'],
            'perCurp'   => $perCurpValida,
            'esCurpValida' => $esCurpValida,
            'perFechaNac'   => 'required',
            'municipio_id' => 'required',
            'perSexo'   => 'required',
            // 'perDirCP'  => 'required|max:5',
            // 'perDirCalle'   => 'required|max:25',
            // 'perDirNumExt'  => 'required|max:6',
            // 'perDirColonia' => 'required|max:60',
            'perCorreo1' => 'nullable|email',
            'perTelefono2' => 'required',
            'sec_tipo_escuela' => 'required',
            'sec_nombre_ex_escuela' => 'required'
        ], [
            'aluNivelIngr.required' => 'El nivel de ingreso es obligatorio',
            'aluGradoIngr.required' => 'El grado de ingreso es obligatorio',
            'perNombre.required' => 'El nombre es obligatorio',
            'perNombre.regex' => 'Los nombres no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
            'perApellido1.required' => 'El apellido paterno es obligatorio',
            'perApellido1.regex' => 'Los apellido paterno no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
            'perApellido2.regex' => 'Los apellido materno no deben contener tildes ni caracteres especiales, solo se permiten Ñ,Ä,Ë,Ï,Ö,Ü',
            'perFechaNac.required' => 'La fecha de nacimiento es obligatoria.',
            'municipio_id.required' => 'El municipio es obligatorio',
            'perSexo.required' => 'El sexo es obligatorio',
            'perTelefono2.required' => 'El teléfono móvil es obligatorio',
            'perCorreo1.email' => 'Debe proporcionar una dirección de correo válida, Favor de verificar.',
            'sec_tipo_escuela.required' => 'El campo Tipo de escuela es obligatorio',
            'sec_nombre_ex_escuela.required' => 'El campo Nombre escuela anterior es obligatorio'

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $perCurp = $request->perCurp;
        if ($request->paisId != "1" && $request->perSexo == "M") {
            $perCurp = "XEXX010101MNEXXXA4";
        }
        if ($request->paisId != "1" && $request->perSexo == "F") {
            $perCurp = "XEXX010101MNEXXXA8";
        }

        if ($request->perCurp != "") {
            $perCurp = $request->perCurp;
        }

        DB::beginTransaction();
        try {
            $alumno = Alumno::with('persona')->findOrFail($id);
            $persona = $alumno->persona;

            $persona->update([
                'perCurp'       => $perCurp,
                'perApellido1'  => $request->perApellido1,
                'perApellido2'  => $request->perApellido2 ? $request->perApellido2: "",
                'perNombre'     => $request->perNombre,
                'perFechaNac'   => $request->perFechaNac,
                'municipio_id'  => Utils::validaEmpty($request->municipio_id),
                'perSexo'       => $request->perSexo,
                'perCorreo1'    => $request->perCorreo1,
                'perTelefono1'  => $request->perTelefono1,
                'perTelefono2'  => $request->perTelefono2,
                'perDirCP'      => Utils::validaEmpty($request->perDirCP),
                'perDirCalle'   => $request->perDirCalle,
                'perDirNumInt'  => $request->perDirNumInt,
                'perDirNumExt'  => $request->perDirNumExt,
                'perDirColonia' => $request->perDirColonia
            ]);

            
            $alumno->update([
                'aluNivelIngr'    => Utils::validaEmpty($request->aluNivelIngr),
                'aluGradoIngr'    => Utils::validaEmpty($request->aluGradoIngr),
                // 'aluMatricula'    => $request->aluMatricula,
                'preparatoria_id' => 0,
                'sec_tipo_escuela' => $request->sec_tipo_escuela,
                'sec_nombre_ex_escuela' => $request->sec_nombre_ex_escuela
            ]);
            if ($request->aluMatricula) {
                $alumno->update([
                    'aluMatricula'    => $request->aluMatricula,
                ]);

            }

            /*
            * Si existen tutores, se realiza la vinculación a este alumno.
            */
            if($request->tutores) {
                $tutores = $request->tutores;
                $dataTutores = collect([]);
                foreach($tutores as $key => $tutor) {
                    $tutor = explode('~', $tutor);
                    $tutNombre = $tutor[0];
                    $tutTelefono = $tutor[1];
                    $tutor = Tutor::where('tutNombre','like', '%'.$tutNombre.'%')
                        ->where('tutTelefono', $tutTelefono)->first();
                    if($tutor) {
                        $dataTutores->push($tutor);
                    }
                }
                MetodosAlumnos::vincularTutores($dataTutores->unique('id'), $alumno);
            }

        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode,$errorMessage)->showConfirmButton();
            return redirect('secundaria_alumno/' . $id . '/edit')->withInput();
        }

        DB::commit();
        alert()->success('Actualizado', 'Se ha actualizado correctamente la información del alumno.')->showConfirmButton();
        return back();

    }//update.

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alumno = Alumno::with('persona')->findOrFail($id);
        try {
            if (User::permiso("alumno") == "A" || User::permiso("alumno") == "B") {
                if ($alumno->delete()) {
                    alert('Escuela Modelo', 'El alumno se ha eliminado con éxito','success');
                } else {
                    alert()->error('Error...', 'No se puedo eliminar el alumno')->showConfirmButton();
                }
            } else {
                alert()->error('Ups...', 'Sin privilegios para esta acción!')->showConfirmButton()->autoClose(5000);
                return redirect('secundaria_alumno');
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...'.$errorCode,$errorMessage)->showConfirmButton();
        }
        return redirect('secundaria_alumno');
    }


    public function buenaConducta(Request $request, $id)
    {

        $curso = Curso::with('alumno.persona', 'periodo', 'cgt.plan.programa.escuela.departamento.ubicacion.municipio.estado')->where("cursos.alumno_id", "=", $id)->where("cursos.curEstado", "=", "R")->first();
        if(!$curso){
            alert()->error('Error...', " El alumno no se encuentra registrado en un curso.")->showConfirmButton();
            return back()->withInput();
        }
        if($curso->alumno->aluEstado == 'B' ){
            alert()->error('Error...', " El alumno esta dado de baja.")->showConfirmButton();
            return back()->withInput();
        }
        $pago = Pago::where('pagClaveAlu',$curso->alumno->aluClave)->where('pagAnioPer',$curso->periodo->perAnioPago)
            ->where(function($query){
                $query->where('pagConcPago','00')->orWhere('pagConcPago','99');
            })->first();

        //dd($pago);

        if(!$pago){
            alert()->error('Error...', " El alumno no ha pagado su inscripción.")->showConfirmButton();
            return back()->withInput();
        }
        $minutario = Minutario::select('id')->where('minClavePago',$curso->alumno->aluClave)->where('minTipo','CB')->first();
        $departamento = Departamento::select('depTituloDoc','depNombreDoc','depPuestoDoc','depNombreOficial')->where('id',$curso->periodo->departamento->id)->first();

        if($minutario ==  NULL){

            $minutario = Minutario::create([
                "minAnio"         => $curso->periodo->perAnioPago,
                "minClavePago"    => $request->aluClave,
                "minDepartamento" => $curso->periodo->departamento->depClave,
                "minTipo"         => "CB",
                "minFecha"        => Carbon::now()->format("Y-m-d"),
            ]);
        }

        $fechaActual = Carbon::now();

        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $nombreArchivo = 'pdf_buena_conducta';
        $pdf = PDF::loadView('reportes.pdf.secundaria.'. $nombreArchivo, [

            "curso" => $curso,
            "fechaActual" => $fechaActual->toDateString(),
            "horaActual" => $fechaActual->toTimeString(),
            "nombreArchivo" => $nombreArchivo,
            "departamento" => $departamento,
            "minutario" => $minutario,
            "perAnio" => $curso->periodo->perAnio
            /*
            "nombreArchivo" => $nombreArchivo,
            "aluEstado" => $request->aluEstado,
            "fechaActual" => $fechaActual->toDateString(),
            "horaActual" => $fechaActual->toTimeString()
            */
        ]);


        $pdf->setPaper('letter', 'portrait');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($nombreArchivo);
        return $pdf->download($nombreArchivo);
        return redirect('secundaria_alumno');
    }


    public function buscarTutor($tutNombre, $tutTelefono) {
        $tutor = Tutor::where('tutNombre','like','%'.$tutNombre.'%')
            ->where('tutTelefono', $tutTelefono)->first();

        return json_encode($tutor);
    }//buscarTutor.


    public function crearTutor(Request $request){
        $datos = $request->datos;
        $tutor = Tutor::where('tutNombre','like','%'.$datos['tutNombre'].'%')
                  ->where('tutTelefono',$datos['tutTelefono'])->first();

        if(!$tutor){
            DB::beginTransaction();
            try {
                $tutor = Tutor::create([
                    'tutNombre' => $datos['tutNombre'],
                    'tutCalle' => $datos['tutCalle'],
                    'tutColonia' => $datos['tutColonia'],
                    'tutCodigoPostal' => $datos['tutCodigoPostal'],
                    'tutPoblacion' => $datos['tutPoblacion'],
                    'tutEstado' => $datos['tutEstado'],
                    'tutTelefono' => $datos['tutTelefono'],
                    'tutCorreo' => $datos['tutCorreo']
                ]);

            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }

            DB::commit();
            return json_encode($tutor);

        }else{
            return json_encode(null);
        }

    }//crearTutor.

    public function tutores_alumno($id) {
        $alumno = Alumno::findOrFail($id);
        $tutores = $alumno->tutores()->get()
            ->map(static function ($item, $key) {
                return $item->tutor;
            });

        return json_encode($tutores);
    }//tutores_alumno.

    public function verificarExistenciaPersona(Request $request) {

        $alumno = MetodosPersonas::existeAlumno($request);
        $empleado = MetodosPersonas::existeEmpleado($request);

        $data = [
            'alumno' => $alumno,
            'empleado' => $empleado,
        ];

        if($request->ajax()){
            return json_encode($data);
        }else{
            return $data;
        }
    }//verificarExistenciaPersona.

    public function rehabilitarAlumno($alumno_id) {
        $alumno = Alumno::findOrFail($alumno_id);

        if($alumno->aluEstado == 'B') {
            $alumno->update([
                'aluEstado' => 'R'
            ]);
        }

        return json_encode($alumno);
    }//rehabilitarAlumno.

    public function empleado_crearAlumno(Request $request,$empleado_id){

        $validator = Validator::make($request->all(), [
                'aluClave'      => 'unique:alumnos,aluClave,NULL,id,deleted_at,NULL',
                'aluNivelIngr'  => 'required|max:4',
                'aluGradoIngr'  => 'required|max:4'
            ], [
                'aluClave.unique'   => "El alumno ya existe",
            ]
        );

        if ($validator->fails()) {
            return redirect ('secundaria_alumno/create')->withErrors($validator)->withInput();
        }

        $empleado = Empleado::findOrFail($empleado_id);
        $persona = $empleado->persona;

        $claveAlu = $this->generarClave($request->aluNivelIngr, $request->aluGradoIngr);
        DB::beginTransaction();
        try {
            $alumno = Alumno::create([
                'persona_id'      => $persona->id,
                'aluClave'        => (int) $claveAlu,
                'aluNivelIngr'    => Utils::validaEmpty($request->aluNivelIngr),
                'aluGradoIngr'    => Utils::validaEmpty($request->aluGradoIngr),
                'aluMatricula'    => $request->aluMatricula,
                'preparatoria_id' => $request->preparatoria_id
            ]);

            /*
            * Si existen tutores, se realiza la vinculación a este alumno.
            */
            if($request->tutores) {
                $tutores = $request->tutores;
                $dataTutores = collect([]);
                foreach($tutores as $key => $tutor) {
                    $tutor = explode('~', $tutor);
                    $tutNombre = $tutor[0];
                    $tutTelefono = $tutor[1];
                    $tutor = Tutor::where('tutNombre','like', '%'.$tutNombre.'%')
                        ->where('tutTelefono', $tutTelefono)->first();
                    $dataTutores->push($tutor);
                }
                MetodosAlumnos::vincularTutores($dataTutores, $alumno);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...'.$errorCode,$errorMessage)->showConfirmButton();
            return redirect('secundaria_alumno/create')->withInput();
        }
        DB::commit(); #TEST.

        if($request->ajax()) {
            return json_encode($alumno);
        }else{
            return $alumno;
        }
    }//empleado_crearAlumno.

    /*
    * Creada para la vista curso.create.
    * retorna si el alumno tiene últimoCurso.
    */
    public function ultimoCurso(Request $request, $alumno_id) {

        $curso = Curso::with(['cgt.plan.programa.escuela.departamento.ubicacion', 'periodo'])
         ->where('alumno_id', $alumno_id)
         ->where('curEstado', '<>', 'B')
         ->latest('curFechaRegistro')
         ->first();

         $data = null;
         if($curso) {

            $cgtSiguiente = MetodosCgt::cgt_siguiente($curso->cgt);

            $data = [
                'curso' => $curso,
                'cgt' => $curso->cgt,
                'plan' => $curso->cgt->plan,
                'programa' => $curso->cgt->plan->programa,
                'escuela' => $curso->cgt->plan->programa->escuela,
                'departamento' => $curso->cgt->plan->programa->escuela->departamento,
                'ubicacion' => $curso->cgt->plan->programa->escuela->departamento->ubicacion,
                'periodo' => $curso->periodo,
                'periodoSiguiente' => $curso->cgt->plan->programa->escuela->departamento->periodoSiguiente,
                'cgtSiguiente' => $cgtSiguiente
            ];
         }
         return json_encode($data);
    }//ultimocCurso.


}
