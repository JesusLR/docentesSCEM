<?php

namespace App\Http\Controllers\Secundaria;

use App\clases\departamentos\MetodosDepartamentos;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utils;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Empleado;
use App\Models\Estado;
use App\Models\Persona;
use App\Models\Municipio;
use App\Models\Pago;
use App\Models\Pais;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_actividades;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_conducta;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_desarrollo;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_familiares;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_habitos;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_heredo;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_medica;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_nacimiento;
use App\Models\Secundaria\Secundaria_alumnos_historia_clinica_sociales;
use App\Models\Ubicacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;


use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SecundariaAlumnosHistoriaClinicaController extends Controller
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

        $secundaria_alumnos_historia_clinica = Secundaria_alumnos_historia_clinica::get();

        return view('secundaria.historia_clinica.index', [
            'secundaria_alumnos_historia_clinica' => $secundaria_alumnos_historia_clinica
        ]);        
    }


    public function list()
    {
        if (Auth::user()->empleado->escuela->departamento->depClave == "SEC") {
            $secundaria_alumnos_historia_clinica = Secundaria_alumnos_historia_clinica::select(
                'secundaria_alumnos_historia_clinica.id as historia_id',
                'alumnos.aluClave',
                'alumnos.id as alumno_id',
                'alumnos.aluMatricula',
                'personas.perNombre',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perCurp'
            )
                ->join('alumnos', 'secundaria_alumnos_historia_clinica.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->latest('secundaria_alumnos_historia_clinica.created_at');
        }

        return Datatables::of($secundaria_alumnos_historia_clinica)
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


            ->addColumn('action', function ($query) {

                $btnMostrarAcciones = '';
                if (Auth::user()->empleado->escuela->departamento->depClave == "SEC") {
                    $btnMostrarAcciones = '
                    <a href="/secundaria_historia_clinica/'.$query->historia_id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                        <i class="material-icons">visibility</i>
                    </a>

                    <a href="/secundaria_historia_clinica/' . $query->historia_id . '/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                        <i class="material-icons">edit</i>
                    </a>';
                }

                return
                    $btnMostrarAcciones;
            })
            ->make(true);
    }
   
    public function create()
    {
        $departamentos = MetodosDepartamentos::buscarSoloAcademicos(1, ['SEC'])->unique("depClave");

        $municipios = Municipio::get();
        $estados = Estado::get();
        $paises = Pais::get();

        return view('secundaria.historia_clinica.create', [
            'paises' => $paises,
            'estados' => $estados,
            'municipios' => $municipios,
            'departamentos' => $departamentos
        ]);
    }

    public function store(Request $request)
    {
        # code...
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
                'sec_tipo_escuela' => 'required',
                'sec_nombre_ex_escuela' => 'required',
                'famNombresMadre' => 'required',
                'famApellido1Madre' => 'required',
                'municipioMadre_id' => 'required',
                'famCelularMadre' => 'required',
                'famEmailMadre' => 'required',
                'famNombresPadre' => 'required',
                'famApellido1Padre' => 'required',
                'municipioPadre_id' => 'required',
                'famCelularPadre' => 'required',
                'famEmailPadre' => 'required'  
    
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
                'perTelefono2.required' => 'El teléfono movil es obligatorio',
                'sec_tipo_escuela.required' => 'El campo Tipo de escuela es obligatorio',
                'sec_nombre_ex_escuela.required' => 'El campo Nombre escuela anterior es obligatorio',
                'famNombresMadre.required' => 'El campo Nombre de la madre obligatorio',
                'famApellido1Madre.required' => 'El campo Apellido 1 de la madre obligatorio',
                'municipioMadre_id.required' => 'El campo Municipio de la madre obligatorio',
                'famCelularMadre.required' => 'El campo Celular de la madre obligatorio',
                'famEmailMadre.required' => 'El campo Correo de la madre obligatorio',
                'famNombresPadre.required' => 'El campo Nombre del padre obligatorio',
                'famApellido1Padre.required' => 'El campo Apellido 1 del padre obligatorio',
                'municipioPadre_id.required' => 'El campo Municipio del padre obligatorio',
                'famCelularPadre.required' => 'El campo Celular del padre obligatorio',
                'famEmailPadre.required' => 'El campo Correo del padre obligatorio',
            ]
        );
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

        try {
            $persona = Persona::create([
                'perCurp'        => $perCurp,
                'perApellido1'   => $request->perApellido1,
                'perApellido2'   => $request->perApellido2 ? $request->perApellido2 : "",
                'perNombre'      => $request->perNombre,
                'perFechaNac'    => $request->perFechaNac,
                'municipio_id'   => Utils::validaEmpty($request->municipio_id),
                'perSexo'        => $request->perSexo,
            ]);

            $alumno = Alumno::create([
                'persona_id'      => $persona->id,
                'aluClave'        => (int) $claveAlu,
                'aluNivelIngr'    => Utils::validaEmpty($request->aluNivelIngr),
                'aluGradoIngr'    => Utils::validaEmpty($request->aluGradoIngr),
                'preparatoria_id' => 0,
                'candidato_id'    => null,
                'sec_tipo_escuela' => $request->sec_tipo_escuela,
                'sec_nombre_ex_escuela' => $request->sec_nombre_ex_escuela
            ]);

            if($persona){
                $secundaria_alumnos_historia_clinica = Secundaria_alumnos_historia_clinica::create([
                    'alumno_id' => $alumno->id,
                    'hisEdadActualMeses' => $request->hisEdadActualMeses,
                    'hisTipoSangre' => $request->hisTipoSangre,
                    'hisAlergias' => $request->hisAlergias,
                    'hisEscuelaProcedencia' => $request->hisEscuelaProcedencia,
                    'hisUltimoGrado' => $request->hisUltimoGrado,
                    'hisRecursado' => $request->hisRecursado,
                    'hisRecursadoDetalle' => $request->hisRecursadoDetalle,
                    'hisIngresoSecundaria' => $request->hisIngresoSecundaria,
                    'estatus_edicion' => 1
                ]);


                $familia = Secundaria_alumnos_historia_clinica_familiares::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'famNombresMadre' => $request->famNombresMadre,
                    'famApellido1Madre' => $request->famApellido1Madre,
                    'famApellido2Madre' => $request->famApellido2Madre,
                    'famFechaNacimientoMadre' => $request->famFechaNacimientoMadre,
                    'municipioMadre_id' => $request->municipioMadre_id,
                    'famOcupacionMadre' => $request->famOcupacionMadre,
                    'famEmpresaMadre' => $request->famEmpresaMadre,
                    'famCelularMadre' => $request->famCelularMadre,
                    'famTelefonoMadre' => $request->famTelefonoMadre,
                    'famEmailMadre' => $request->famEmailMadre,
                    'famRelacionMadre' => $request->famRelacionMadre,
                    'famRelacionFrecuenciaMadre' => $request->famRelacionFrecuenciaMadre,
                    'famNombresPadre' => $request->famNombresPadre,
                    'famApellido1Padre' => $request->famApellido1Padre,
                    'famApellido2Padre' => $request->famApellido2Padre,
                    'famFechaNacimientoPadre' => $request->famFechaNacimientoPadre,
                    'municipioPadre_id' => $request->municipioPadre_id,
                    'famOcupacionPadre' => $request->famOcupacionPadre,
                    'famEmpresaPadre' => $request->famEmpresaPadre,
                    'famCelularPadre' => $request->famCelularPadre,
                    'famTelefonoPadre' => $request->famTelefonoPadre,
                    'famEmailPadre' => $request->famEmailPadre,
                    'famRelacionPadre' => $request->famRelacionPadre,
                    'famRelacionFrecuenciaPadre' => $request->famRelacionFrecuenciaPadre,
                    'famEstadoCivilPadres' => $request->famEstadoCivilPadres,
                    'famSeparado' => $request->famSeparado,
                    'famReligion' => $request->famReligion,
                    'famExtraNombre' => $request->famExtraNombre,
                    'famTelefonoExtra' => $request->famTelefonoExtra,
                    'famAutorizado1' => $request->famAutorizado1,
                    'famAutorizado2' => $request->famAutorizado2,
                    'famIntegrante1' => $request->famIntegrante1,
                    'famParentesco1' => $request->famParentesco1,
                    'famEdadIntegrante1' => $request->famEdadIntegrante1,
                    'famEscuelaGrado1' => $request->famEscuelaGrado1,
                    'famIntegrante2' => $request->famIntegrante2,
                    'famParentesco2' => $request->famParentesco2,
                    'famEdadIntegrante2' => $request->famEdadIntegrante2,
                    'famEscuelaGrado2' => $request->famEscuelaGrado2,
                    'famIntregrante3' => $request->famIntregrante3,
                    'famParentesco3' => $request->famParentesco3,
                    'famEdadIntregrante3' => $request->famEdadIntregrante3,
                    'famEscuelaGrado3' => $request->famEscuelaGrado3,
                ]);                      
        
                $embarazo = Secundaria_alumnos_historia_clinica_nacimiento::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'nacNumEmbarazo' => $request->nacNumEmbarazo,
                    'nacEmbarazoPlaneado' => $request->nacEmbarazoPlaneado,
                    'nacEmbarazoTermino' => $request->nacEmbarazoTermino,
                    'nacEmbarazoDuracion' => $request->nacEmbarazoDuracion,
                    'NacParto' => $request->NacParto,
                    'nacPeso' => $request->nacPeso,
                    'nacMedia' => $request->nacMedia,
                    'nacApgar' => $request->nacApgar,
                    'nacComplicacionesEmbarazo' => $request->nacComplicacionesEmbarazo,
                    'nacCualesEmbarazo' => $request->nacCualesEmbarazo,
                    'nacComplicacionesParto' => $request->nacComplicacionesParto,
                    'nacCualesParto' => $request->nacCualesParto,
                    'nacComplicacionDespues' => $request->nacComplicacionDespues,
                    'nacCualesDespues' => $request->nacCualesDespues,
                    'nacLactancia' => $request->nacLactancia,
                    'nacActualmente' => $request->nacActualmente
                ]);
        
       
                $medica = Secundaria_alumnos_historia_clinica_medica::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'medIntervencionQuirurgicas' => $request->medIntervencionQuirurgicas,
                    'medMedicamentos' => $request->medMedicamentos,
                    'medConvulsiones' => $request->medConvulsiones,
                    'medAudicion' => $request->medAudicion,
                    'medFiebres' => $request->medFiebres,
                    'medProblemasCorazon' => $request->medProblemasCorazon,
                    'medDeficiencia' => $request->medDeficiencia,
                    'medAsma' => $request->medAsma,
                    'medDiabetes' => $request->medDiabetes,
                    'medGastrointestinales' => $request->medGastrointestinales,
                    'medAccidentes' => $request->medAccidentes,
                    'medEpilepsia' => $request->medEpilepsia,
                    'medRinion' => $request->medRinion,
                    'medPiel' => $request->medPiel,
                    'medCoordinacionMotriz' => $request->medCoordinacionMotriz,
                    'medEstrenimiento' => $request->medEstrenimiento,
                    'medDificultadesSuenio' => $request->medDificultadesSuenio,
                    'medAlergias' => $request->medAlergias,
                    'medEspesificar' => $request->medEspesificar,
                    'medOtro' => $request->medOtro,
                    'medGastoMedico' => $request->medGastoMedico,
                    'medNombreAsegurador' => $request->medNombreAsegurador,
                    'medVacunas' => $request->medVacunas,
                    'medTramiento' => $request->medTramiento,
                    'medTerapia' => $request->medTerapia,
                    'medMotivoTerapia' => $request->medMotivoTerapia,
                    'medSaludFisicaAct' => $request->medSaludFisicaAct,
                    'medSaludEmocialAct' => $request->medSaludEmocialAct
                ]);
        
       
                $habito = Secundaria_alumnos_historia_clinica_habitos::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'habBanio' => $request->habBanio,
                    'habVestimenta' => $request->habVestimenta,
                    'habLuz' => $request->habLuz,
                    'habZapatos' => $request->habZapatos,
                    'habCome' => $request->habCome,
                    'habHoraDormir' => $request->habHoraDormir,
                    'habHoraDespertar' => $request->habHoraDespertar,
                    'habEstadoLevantar' => $request->habEstadoLevantar,
                    'habRecipiente' => $request->habRecipiente
                ]);        
        
                $desarrollo = Secundaria_alumnos_historia_clinica_desarrollo::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'desMotricesGruesas' => $request->desMotricesGruesas,
                    'desMotricesGruCual' => $request->desMotricesGruCual,
                    'desMotricesFinas' => $request->desMotricesFinas,
                    'desMotricesFinCual' => $request->desMotricesFinCual,
                    'desHiperactividad' => $request->desHiperactividad,
                    'desHiperactividadCual' => $request->desHiperactividadCual,
                    'desSocializacion' => $request->desSocializacion,
                    'desSocializacionCual' => $request->desSocializacionCual,
                    'desLenguaje' => $request->desLenguaje,
                    'desLenguajeCual' => $request->desLenguajeCual,
                    'desPrimPalabra' => $request->desPrimPalabra,
                    'desEdadNombre' => $request->desEdadNombre,
                    'desLateralidad' => $request->desLateralidad
                ]);        
       
                $heredo = Secundaria_alumnos_historia_clinica_heredo::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'herEpilepsia' => $request->herEpilepsia,
                    'herEpilepsiaGrado' => $request->herEpilepsiaGrado,
                    'herDiabetes' => $request->herDiabetes,
                    'herDiabetesGrado' => $request->herDiabetesGrado,
                    'herHipertension' => $request->herHipertension,
                    'herHipertensionGrado' => $request->herHipertensionGrado,
                    'herCancer' => $request->herCancer,
                    'herCancerGrado' => $request->herCancerGrado,
                    'herNeurologicos' => $request->herNeurologicos,
                    'herNeurologicosGrado' => $request->herNeurologicosGrado,
                    'herPsicologicos' => $request->herPsicologicos,
                    'herPsicologicosGrado' => $request->herPsicologicosGrado,
                    'herLenguaje' => $request->herLenguaje,
                    'herLenguajeGrado' => $request->herLenguajeGrado,
                    'herAdicciones' => $request->herAdicciones,
                    'herAdiccionesGrado' => $request->herAdiccionesGrado,
                    'herOtro' => $request->herOtro,
                    'herOtroGrado' => $request->herOtroGrado
                ]);        
       
                $social = Secundaria_alumnos_historia_clinica_sociales::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'socAmigos' => $request->socAmigos,
                    'socActitud' => $request->socActitud,
                    'socNinioEdad' => $request->socNinioEdad,
                    'socNinioRazon' => $request->socNinioRazon,
                    'socActividadExtraescolar' => $request->socActividadExtraescolar,
                    'socActividadRazon' => $request->socActividadRazon,
                    'socSeparacion' => $request->socSeparacion,
                    'socSeparacionRazon' => $request->socSeparacionRazon,
                    'socRelacionFamilia' => $request->socRelacionFamilia
                ]);        
       
                $conducta = Secundaria_alumnos_historia_clinica_conducta::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'conAfectivoNervioso' => $request->conAfectivoNervioso,
                    'conAfectivoAgresivo' => $request->conAfectivoAgresivo,
                    'conAfectivoDestraido' => $request->conAfectivoDestraido,
                    'conAfectivoTimido' => $request->conAfectivoTimido,
                    'conAfectivoSensible' => $request->conAfectivoSensible,
                    'conAfectivoAmistoso' => $request->conAfectivoAmistoso,
                    'conAfectivoAmable' => $request->conAfectivoAmable,
                    'conAfectivoOtro' => $request->conAfectivoOtro,
                    'conVerbalRenuente' => $request->conVerbalRenuente,
                    'conVerbalTartamudez' => $request->conVerbalTartamudez,
                    'conVerbalVerbalizacion' => $request->conVerbalVerbalizacion,
                    'conVerbalExplicito' => $request->conVerbalExplicito,
                    'conVerbalSilencioso' => $request->conVerbalSilencioso,
                    'conVerbalRepetivo' => $request->conVerbalRepetivo,
                    'conConductual' => $request->conConductual,
                    'conBerrinches' => $request->conBerrinches,
                    'conAgresividad' => $request->conAgresividad,
                    'conMasturbacion' => $request->conMasturbacion,
                    'conMentiras' => $request->conMentiras,
                    'conRobo' => $request->conRobo,
                    'conPesadillas' => $request->conPesadillas,
                    'conEnuresis' => $request->conEnuresis,
                    'conEncopresis' => $request->conEncopresis,
                    'conExcesoAlimentacion' => $request->conExcesoAlimentacion,
                    'conRechazoAlimentario' => $request->conRechazoAlimentario,
                    'conLlanto' => $request->conLlanto,
                    'conTricotilomania' => $request->conTricotilomania,
                    'conOnicofagia' => $request->conOnicofagia,
                    'conMorderUnias' => $request->conMorderUnias,
                    'conSuccionPulgar' => $request->conSuccionPulgar,
                    'conExplicaciones' => $request->conExplicaciones,
                    'conPrivaciones' => $request->conPrivaciones,
                    'conCorporal' => $request->conCorporal,
                    'conAmenazas' => $request->conAmenazas,
                    'conTiempoFuera' => $request->conTiempoFuera,
                    'conOtros' => $request->conOtros,
                    'conAplica' => $request->conAplica,
                    'conRecompensa' => $request->conRecompensa
                ]);        
        
                $actividad = Secundaria_alumnos_historia_clinica_actividades::create([
                    'historia_id' => $secundaria_alumnos_historia_clinica->id,
                    'actJuguete' => $request->actJuguete,
                    'actCuento' => $request->actCuento,
                    'actPelicula' => $request->actPelicula,
                    'actHorasTelevision' => $request->actHorasTelevision,
                    'actTenologia' => $request->actTenologia,
                    'actTipoJuguetes' => $request->actTipoJuguetes,
                    'actApoyoTarea' => $request->actApoyoTarea,
                    'actCuidado' => $request->actCuidado,
                    'actObservacionExtra' => $request->actObservacionExtra,
                    'actGradoSugerido' => $request->actGradoSugerido,
                    'actGradoElegido' => $request->actGradoElegido,
                    'actNombreEntrevista' => $request->actNombreEntrevista
                ]);
        
            }

    
            /* Si el alumno registrado se repite como candidato */
            $nosoymexicano = $request->noSoyMexicano ? $perCurp : $request->input('perCurp');
            DB::update("update candidatos c, personas p set  c.candidatoPreinscrito = 'SI' where c.perCurp = p.perCurp
            and c.perCurp <> 'XEXX010101MNEXXXA8' and c.perCurp <> 'XEXX010101MNEXXXA4' and LENGTH(ltrim(rtrim(c.perCurp))) > 0
            and p.deleted_at is null and p.perCurp = ?", [$nosoymexicano]);

            alert('Escuela Modelo', 'El historial clinico se ha creado con éxito','success')->showConfirmButton()->autoClose('6000');
            return redirect()->route('secundaria.secundaria_historia_clinica.index');




        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('secundaria_historia_clinica/create')->withInput();
        }
    }

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


    public function show($id)
    {
        $departamentos = MetodosDepartamentos::buscarSoloAcademicos(1, ['SEC'])->unique("depClave");
        // obtiene los datos de la tabla Secundaria_alumnos_historia_clinica
        $historia = Secundaria_alumnos_historia_clinica::select('secundaria_alumnos_historia_clinica.id','secundaria_alumnos_historia_clinica.alumno_id',
        'secundaria_alumnos_historia_clinica.hisTipoSangre','secundaria_alumnos_historia_clinica.hisAlergias','secundaria_alumnos_historia_clinica.hisEscuelaProcedencia',
        'secundaria_alumnos_historia_clinica.hisUltimoGrado','secundaria_alumnos_historia_clinica.hisRecursado','secundaria_alumnos_historia_clinica.hisRecursadoDetalle',
        'secundaria_alumnos_historia_clinica.hisIngresoSecundaria',
        'secundaria_alumnos_historia_clinica.hisEdadActualMeses',
        'alumnos.persona_id','alumnos.aluClave','personas.perCurp', 'personas.perApellido1', 'personas.perApellido2', 'personas.perNombre',
        'personas.perFechaNac', 'municipios.id as municipio_id', 'municipios.munNombre', 'estados.edoNombre','paises.id as pais_id' ,'paises.paisNombre')
        ->join('alumnos', 'alumnos.id', '=', 'secundaria_alumnos_historia_clinica.alumno_id')
        ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
        ->join('municipios', 'municipios.id', '=', 'personas.municipio_id')
        ->join('estados', 'estados.id', '=', 'municipios.estado_id')
        ->join('paises', 'paises.id', '=', 'estados.pais_id')
        ->findOrFail($id);

        $alumno = Alumno::findOrFail($historia->alumno_id);
        $persona = Persona::findOrFail($alumno->persona_id);
        $municipio_alumno = Municipio::findOrFail($persona->municipio_id);
        $estado_alumno = Estado::findOrFail($municipio_alumno->estado_id);
        $pais_alumno = Pais::findOrFail($estado_alumno->pais_id);


        $paises = Pais::all();


        $familia = Secundaria_alumnos_historia_clinica_familiares::select()->where('historia_id', '=', $historia->id)->first();

        $embarazo = Secundaria_alumnos_historia_clinica_nacimiento::select()->where('historia_id', '=', $historia->id)->first();

        $medica = Secundaria_alumnos_historia_clinica_medica::select()->where('historia_id', '=', $historia->id)->first();

        $habitos = Secundaria_alumnos_historia_clinica_habitos::select()->where('historia_id', '=', $historia->id)->first();

        $desarrollo = Secundaria_alumnos_historia_clinica_desarrollo::select()->where('historia_id', '=', $historia->id)->first();

        $heredo = Secundaria_alumnos_historia_clinica_heredo::select()->where('historia_id', '=', $historia->id)->first();

        $social = Secundaria_alumnos_historia_clinica_sociales::select()->where('historia_id', '=', $historia->id)->first();

        $consucta = Secundaria_alumnos_historia_clinica_conducta::select()->where('historia_id', '=', $historia->id)->first();

        $actividad = Secundaria_alumnos_historia_clinica_actividades::select()->where('historia_id', '=', $historia->id)->first();

        $municipioMadre = Municipio::select('id', 'munNombre','estado_id')->where('id', '=', $familia->municipioMadre_id)->first();
        if ($municipioMadre)
        {
            $estadoMadre =  Estado::select('id', 'edoNombre', 'pais_id')->where('id', '=', $municipioMadre->estado_id)->first();
            $paisMadre = Pais::select('id', 'paisNombre')->where('id', '=', $estadoMadre->pais_id)->first();
        }

        $municipioPadre = Municipio::select('id', 'munNombre','estado_id')->where('id', '=', $familia->municipioPadre_id)->first();
        if($municipioPadre)
        {
            $estadoPadre =  Estado::select('id', 'edoNombre', 'pais_id')->where('id', '=', $municipioPadre->estado_id)->first();
            $paisPadre = Pais::select('id', 'paisNombre')->where('id', '=', $estadoPadre->pais_id)->first();
        }

        if(!$municipioMadre || !$municipioPadre)
        {
            alert()->warning('No existe información', 'Favor de capturar los datos de la historia clinica de este alumno(a).')->showConfirmButton();
            return back()->withInput();
        }

        return view('secundaria.historia_clinica.show', [
            'paises' => $paises,
            'historia' => $historia,
            'familia' => $familia,
            'municipioMadre' => $municipioMadre,
            'embarazo' => $embarazo,
            'medica' => $medica,
            'habitos' => $habitos,
            'desarrollo' => $desarrollo,
            'heredo' => $heredo,
            'social' => $social,
            'consucta' => $consucta,
            'actividad' => $actividad,
            'estadoMadre' => $estadoMadre,
            'paisMadre' => $paisMadre,
            'paisPadre' => $paisPadre,
            'estadoPadre' => $estadoPadre,
            'municipioPadre' => $municipioPadre,
            'departamentos' => $departamentos,
            'alumno' => $alumno,
            'persona' => $persona,
            'municipio_alumno' => $municipio_alumno,
            'estado_alumno' => $estado_alumno,
            'pais_alumno' => $pais_alumno
        ]);
        
    }

    public function getEstados(Request $request, $id)
    {
        if($request->ajax()){
            $estados = Estado::estados($id);
            return response()->json($estados);
        }
    }

    public function getmunicipios(Request $request, $id)
    {
        if ($request->ajax()) {
            $municipios = DB::table("municipios")
            ->where("estado_id","=", $id)
            ->whereNotIn('id', [268])
            ->orderBy("munNombre")->get();
            return response()->json($municipios);
        }
    }

    public function edit($id)
    {
        $departamentos = MetodosDepartamentos::buscarSoloAcademicos(1, ['SEC'])->unique("depClave");


        // obtiene los datos de la tabla Preescolar_alumnos_historia_clinica
        $historia = Secundaria_alumnos_historia_clinica::select('secundaria_alumnos_historia_clinica.id','secundaria_alumnos_historia_clinica.alumno_id',
        'secundaria_alumnos_historia_clinica.hisTipoSangre','secundaria_alumnos_historia_clinica.hisAlergias','secundaria_alumnos_historia_clinica.hisEscuelaProcedencia',
        'secundaria_alumnos_historia_clinica.hisUltimoGrado','secundaria_alumnos_historia_clinica.hisRecursado','secundaria_alumnos_historia_clinica.hisRecursadoDetalle',
        'secundaria_alumnos_historia_clinica.hisIngresoSecundaria',
        'secundaria_alumnos_historia_clinica.hisEdadActualMeses',
        'alumnos.persona_id','alumnos.aluClave','personas.perCurp', 'personas.perApellido1', 'personas.perApellido2', 'personas.perNombre',
        'personas.perFechaNac', 'municipios.id as municipio_id', 'municipios.munNombre', 'estados.edoNombre','paises.id as pais_id' ,'paises.paisNombre')
        ->join('alumnos', 'alumnos.id', '=', 'secundaria_alumnos_historia_clinica.alumno_id')
        ->join('personas', 'personas.id', '=', 'alumnos.persona_id')
        ->join('municipios', 'municipios.id', '=', 'personas.municipio_id')
        ->join('estados', 'estados.id', '=', 'municipios.estado_id')
        ->join('paises', 'paises.id', '=', 'estados.pais_id')
        ->findOrFail($id);


       $alumno = Alumno::findOrFail($historia->alumno_id);
       $persona = Persona::findOrFail($alumno->persona_id);

       $municipio_alumno = Municipio::findOrFail($persona->municipio_id);
       $estado_alumno = Estado::findOrFail($municipio_alumno->estado_id);
       $pais_alumno = Pais::findOrFail($estado_alumno->pais_id);

        $paises = Pais::get();

        


        $familia = Secundaria_alumnos_historia_clinica_familiares::select()->where('historia_id', '=', $historia->id)->first();

        if($familia->municipioMadre_id != ""){
            $idMadre = $familia->municipioMadre_id;
        }else{
            $idMadre = 0;
        }
        // estado de la madre 
        $estado_id_madre = Municipio::select('estados.id as estado_id')
        ->join('estados', 'municipios.estado_id', '=', 'estados.id')
        ->where('municipios.id', $idMadre)->first();
     
        // pais del madre 
        $pais_madre_id = Estado::select('paises.id as pais_id')
        ->join('paises', 'estados.pais_id', '=', 'paises.id')
        ->where('estados.id', $estado_id_madre->estado_id)->first();

        if($familia->municipioPadre_id != ""){
            $idPadre = $familia->municipioPadre_id;
        }else{
            $idPadre = 0;
        }

        // estado de la padre 
        $estado_id_padre = Municipio::select('estados.id as estado_id')
        ->join('estados', 'municipios.estado_id', '=', 'estados.id')
        ->where('municipios.id', $idPadre)->first();
     
        // pais del padre 
        $pais_padre_id = Estado::select('paises.id as pais_id')
        ->join('paises', 'estados.pais_id', '=', 'paises.id')
        ->where('estados.id', $estado_id_padre->estado_id)->first();

        

        $embarazo = Secundaria_alumnos_historia_clinica_nacimiento::select()->where('historia_id', '=', $historia->id)->first();

        $medica = Secundaria_alumnos_historia_clinica_medica::select()->where('historia_id', '=', $historia->id)->first();

        $habitos = Secundaria_alumnos_historia_clinica_habitos::select()->where('historia_id', '=', $historia->id)->first();

        $desarrollo = Secundaria_alumnos_historia_clinica_desarrollo::select()->where('historia_id', '=', $historia->id)->first();

        $heredo = Secundaria_alumnos_historia_clinica_heredo::select()->where('historia_id', '=', $historia->id)->first();

        $social = Secundaria_alumnos_historia_clinica_sociales::select()->where('historia_id', '=', $historia->id)->first();

        $consucta = Secundaria_alumnos_historia_clinica_conducta::select()->where('historia_id', '=', $historia->id)->first();

        $actividad = Secundaria_alumnos_historia_clinica_actividades::select()->where('historia_id', '=', $historia->id)->first();


        // $municipioMadre = Municipio::select('id', 'munNombre','estado_id')->where('id', '=', $familia->municipioMadre_id)->first();
        // $estadoMadre =  Estado::select('id', 'edoNombre', 'pais_id')->where('id', '=', $municipioMadre->estado_id)->first();
        // $paisMadre = Pais::select('id', 'paisNombre')->where('id', '=', $estadoMadre->pais_id)->first();


        // $municipioPadre = Municipio::select('id', 'munNombre','estado_id')->where('id', '=', $familia->municipioPadre_id)->first();
        // $estadoPadre =  Estado::select('id', 'edoNombre', 'pais_id')->where('id', '=', $municipioPadre->estado_id)->first();
        // $paisPadre = Pais::select('id', 'paisNombre')->where('id', '=', $estadoPadre->pais_id)->first();

        $municipios = Municipio::get();
        $estados = Estado::get();

        return view('secundaria.historia_clinica.edit', [
            'paises' => $paises,
            'historia' => $historia,
            'familia' => $familia,
            'embarazo' => $embarazo,
            'medica' => $medica,
            'habitos' => $habitos,
            'desarrollo' => $desarrollo,
            'heredo' => $heredo,
            'social' => $social,
            'consucta' => $consucta,
            'actividad' => $actividad,
            'estado_id_madre' => $estado_id_madre,
            'pais_madre_id' => $pais_madre_id,
            'municipios' => $municipios,
            'estados' => $estados,
            'estado_id_padre' => $estado_id_padre,
            'pais_padre_id' => $pais_padre_id,
            'departamentos' => $departamentos,
            'persona' => $persona,
            'alumno' => $alumno,
            'municipio_alumno' => $municipio_alumno,
            'estado_alumno' => $estado_alumno,
            'pais_alumno' => $pais_alumno
        ]);

    }

  
    public function update(Request $request, $id)
    {
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

        try {
           
        
            $historia_alumno = Secundaria_alumnos_historia_clinica::where('id', $id)->first();

            $historia_alumno->update([
                'hisEdadActualMeses' => $request->hisEdadActualMeses,
                'hisTipoSangre' => $request->hisTipoSangre,
                'hisAlergias' => $request->hisAlergias,
                'hisEscuelaProcedencia' => $request->hisEscuelaProcedencia,
                'hisUltimoGrado' => $request->hisUltimoGrado,
                'hisRecursado' => $request->hisRecursado,
                'hisRecursadoDetalle' => $request->hisRecursadoDetalle,
                'hisIngresoSecundaria' => $request->hisIngresoSecundaria,
                'estatus_edicion' => $request->estatus_edicion
    
            ]);
    
            $alumno = Alumno::findOrFail($historia_alumno->alumno_id);
    
            $alumno->update([
                'aluGradoIngr' => $request->aluGradoIngr,
                'sec_tipo_escuela' => $request->sec_tipo_escuela,
                'sec_nombre_ex_escuela' => $request->sec_nombre_ex_escuela
            ]);
    
            $persona = Persona::findOrFail($alumno->persona_id);
            $persona->update([
                'perCurp' => $perCurp
            ]);
    
            
    
            $familia = Secundaria_alumnos_historia_clinica_familiares::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            if($request->municipioMadre_id != ""){
                $municipioMadre = $request->municipioMadre_id;
            }else{
                $municipioMadre = 0;
            }

            if($request->municipioMadre_id != ""){
                $municipioPadre = $request->municipioMadre_id;
            }else{
                $municipioPadre = 0;
            }
    
            $familia->update([
                'historia_id' => $familia->historia_id,
                'famNombresMadre' => $request->famNombresMadre,
                'famApellido1Madre' => $request->famApellido1Madre,
                'famApellido2Madre' => $request->famApellido2Madre,
                'famFechaNacimientoMadre' => $request->famFechaNacimientoMadre,
                'municipioMadre_id' => $municipioMadre,
                'famOcupacionMadre' => $request->famOcupacionMadre,
                'famEmpresaMadre' => $request->famEmpresaMadre,
                'famCelularMadre' => $request->famCelularMadre,
                'famTelefonoMadre' => $request->famTelefonoMadre,
                'famEmailMadre' => $request->famEmailMadre,
                'famRelacionMadre' => $request->famRelacionMadre,
                'famRelacionFrecuenciaMadre' => $request->famRelacionFrecuenciaMadre,
                'famNombresPadre' => $request->famNombresPadre,
                'famApellido1Padre' => $request->famApellido1Padre,
                'famApellido2Padre' => $request->famApellido2Padre,
                'famFechaNacimientoPadre' => $request->famFechaNacimientoPadre,
                'municipioPadre_id' => $municipioPadre,
                'famOcupacionPadre' => $request->famOcupacionPadre,
                'famEmpresaPadre' => $request->famEmpresaPadre,
                'famCelularPadre' => $request->famCelularPadre,
                'famTelefonoPadre' => $request->famTelefonoPadre,
                'famEmailPadre' => $request->famEmailPadre,
                'famRelacionPadre' => $request->famRelacionPadre,
                'famRelacionFrecuenciaPadre' => $request->famRelacionFrecuenciaPadre,
                'famEstadoCivilPadres' => $request->famEstadoCivilPadres,
                'famSeparado' => $request->famSeparado,
                'famReligion' => $request->famReligion,
                'famExtraNombre' => $request->famExtraNombre,
                'famTelefonoExtra' => $request->famTelefonoExtra,
                'famAutorizado1' => $request->famAutorizado1,
                'famAutorizado2' => $request->famAutorizado2,
                'famIntegrante1' => $request->famIntegrante1,
                'famParentesco1' => $request->famParentesco1,
                'famEdadIntegrante1' => $request->famEdadIntegrante1,
                'famEscuelaGrado1' => $request->famEscuelaGrado1,
                'famIntegrante2' => $request->famIntegrante2,
                'famParentesco2' => $request->famParentesco2,
                'famEdadIntegrante2' => $request->famEdadIntegrante2,
                'famEscuelaGrado2' => $request->famEscuelaGrado2,
                'famIntregrante3' => $request->famIntregrante3,
                'famParentesco3' => $request->famParentesco3,
                'famEdadIntregrante3' => $request->famEdadIntregrante3,
                'famEscuelaGrado3' => $request->famEscuelaGrado3,
            ]);
    
           
            $embarazo = Secundaria_alumnos_historia_clinica_nacimiento::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $embarazo->update([
                'historia_id' => $embarazo->historia_id,
                'nacNumEmbarazo' => $request->nacNumEmbarazo,
                'nacEmbarazoPlaneado' => $request->nacEmbarazoPlaneado,
                'nacEmbarazoTermino' => $request->nacEmbarazoTermino,
                'nacEmbarazoDuracion' => $request->nacEmbarazoDuracion,
                'NacParto' => $request->NacParto,
                'nacPeso' => $request->nacPeso,
                'nacMedia' => $request->nacMedia,
                'nacApgar' => $request->nacApgar,
                'nacComplicacionesEmbarazo' => $request->nacComplicacionesEmbarazo,
                'nacCualesEmbarazo' => $request->nacCualesEmbarazo,
                'nacComplicacionesParto' => $request->nacComplicacionesParto,
                'nacCualesParto' => $request->nacCualesParto,
                'nacComplicacionDespues' => $request->nacComplicacionDespues,
                'nacCualesDespues' => $request->nacCualesDespues,
                'nacLactancia' => $request->nacLactancia,
                'nacActualmente' => $request->nacActualmente
            ]);
    
            $medica = Secundaria_alumnos_historia_clinica_medica::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $medica->update([
                'historia_id' => $medica->historia_id,
                'medIntervencionQuirurgicas' => $request->medIntervencionQuirurgicas,
                'medMedicamentos' => $request->medMedicamentos,
                'medConvulsiones' => $request->medConvulsiones,
                'medAudicion' => $request->medAudicion,
                'medFiebres' => $request->medFiebres,
                'medProblemasCorazon' => $request->medProblemasCorazon,
                'medDeficiencia' => $request->medDeficiencia,
                'medAsma' => $request->medAsma,
                'medDiabetes' => $request->medDiabetes,
                'medGastrointestinales' => $request->medGastrointestinales,
                'medAccidentes' => $request->medAccidentes,
                'medEpilepsia' => $request->medEpilepsia,
                'medRinion' => $request->medRinion,
                'medPiel' => $request->medPiel,
                'medCoordinacionMotriz' => $request->medCoordinacionMotriz,
                'medEstrenimiento' => $request->medEstrenimiento,
                'medDificultadesSuenio' => $request->medDificultadesSuenio,
                'medAlergias' => $request->medAlergias,
                'medEspesificar' => $request->medEspesificar,
                'medOtro' => $request->medOtro,
                'medGastoMedico' => $request->medGastoMedico,
                'medNombreAsegurador' => $request->medNombreAsegurador,
                'medVacunas' => $request->medVacunas,
                'medTramiento' => $request->medTramiento,
                'medTerapia' => $request->medTerapia,
                'medMotivoTerapia' => $request->medMotivoTerapia,
                'medSaludFisicaAct' => $request->medSaludFisicaAct,
                'medSaludEmocialAct' => $request->medSaludEmocialAct
            ]);
    
            $habito = Secundaria_alumnos_historia_clinica_habitos::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $habito->update([
                'historia_id' => $habito->historia_id,
                'habBanio' => $request->habBanio,
                'habVestimenta' => $request->habVestimenta,
                'habLuz' => $request->habLuz,
                'habZapatos' => $request->habZapatos,
                'habCome' => $request->habCome,
                'habHoraDormir' => $request->habHoraDormir,
                'habHoraDespertar' => $request->habHoraDespertar,
                'habEstadoLevantar' => $request->habEstadoLevantar,
                'habRecipiente' => $request->habRecipiente
            ]);
    
            $desarrollo = Secundaria_alumnos_historia_clinica_desarrollo::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $desarrollo->update([
                'historia_id' => $desarrollo->historia_id,
                'desMotricesGruesas' => $request->desMotricesGruesas,
                'desMotricesGruCual' => $request->desMotricesGruCual,
                'desMotricesFinas' => $request->desMotricesFinas,
                'desMotricesFinCual' => $request->desMotricesFinCual,
                'desHiperactividad' => $request->desHiperactividad,
                'desHiperactividadCual' => $request->desHiperactividadCual,
                'desSocializacion' => $request->desSocializacion,
                'desSocializacionCual' => $request->desSocializacionCual,
                'desLenguaje' => $request->desLenguaje,
                'desLenguajeCual' => $request->desLenguajeCual,
                'desPrimPalabra' => $request->desPrimPalabra,
                'desEdadNombre' => $request->desEdadNombre,
                'desLateralidad' => $request->desLateralidad
            ]);
    
            $heredo = Secundaria_alumnos_historia_clinica_heredo::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $heredo->update([
                'historia_id' => $heredo->historia_id,
                'herEpilepsia' => $request->herEpilepsia,
                'herEpilepsiaGrado' => $request->herEpilepsiaGrado,
                'herDiabetes' => $request->herDiabetes,
                'herDiabetesGrado' => $request->herDiabetesGrado,
                'herHipertension' => $request->herHipertension,
                'herHipertensionGrado' => $request->herHipertensionGrado,
                'herCancer' => $request->herCancer,
                'herCancerGrado' => $request->herCancerGrado,
                'herNeurologicos' => $request->herNeurologicos,
                'herNeurologicosGrado' => $request->herNeurologicosGrado,
                'herPsicologicos' => $request->herPsicologicos,
                'herPsicologicosGrado' => $request->herPsicologicosGrado,
                'herLenguaje' => $request->herLenguaje,
                'herLenguajeGrado' => $request->herLenguajeGrado,
                'herAdicciones' => $request->herAdicciones,
                'herAdiccionesGrado' => $request->herAdiccionesGrado,
                'herOtro' => $request->herOtro,
                'herOtroGrado' => $request->herOtroGrado
            ]);
    
            $social = Secundaria_alumnos_historia_clinica_sociales::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $social->update([
                'historia_id' => $social->historia_id,
                'socAmigos' => $request->socAmigos,
                'socActitud' => $request->socActitud,
                'socNinioEdad' => $request->socNinioEdad,
                'socNinioRazon' => $request->socNinioRazon,
                'socActividadExtraescolar' => $request->socActividadExtraescolar,
                'socActividadRazon' => $request->socActividadRazon,
                'socSeparacion' => $request->socSeparacion,
                'socSeparacionRazon' => $request->socSeparacionRazon,
                'socRelacionFamilia' => $request->socRelacionFamilia
            ]);
    
            $conducta = Secundaria_alumnos_historia_clinica_conducta::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $conducta->update([
                'historia_id' => $conducta->historia_id,
                'conAfectivoNervioso' => $request->conAfectivoNervioso,
                'conAfectivoAgresivo' => $request->conAfectivoAgresivo,
                'conAfectivoDestraido' => $request->conAfectivoDestraido,
                'conAfectivoTimido' => $request->conAfectivoTimido,
                'conAfectivoSensible' => $request->conAfectivoSensible,
                'conAfectivoAmistoso' => $request->conAfectivoAmistoso,
                'conAfectivoAmable' => $request->conAfectivoAmable,
                'conAfectivoOtro' => $request->conAfectivoOtro,
                'conVerbalRenuente' => $request->conVerbalRenuente,
                'conVerbalTartamudez' => $request->conVerbalTartamudez,
                'conVerbalVerbalizacion' => $request->conVerbalVerbalizacion,
                'conVerbalExplicito' => $request->conVerbalExplicito,
                'conVerbalSilencioso' => $request->conVerbalSilencioso,
                'conVerbalRepetivo' => $request->conVerbalRepetivo,
                'conConductual' => $request->conConductual,
                'conBerrinches' => $request->conBerrinches,
                'conAgresividad' => $request->conAgresividad,
                'conMasturbacion' => $request->conMasturbacion,
                'conMentiras' => $request->conMentiras,
                'conRobo' => $request->conRobo,
                'conPesadillas' => $request->conPesadillas,
                'conEnuresis' => $request->conEnuresis,
                'conEncopresis' => $request->conEncopresis,
                'conExcesoAlimentacion' => $request->conExcesoAlimentacion,
                'conRechazoAlimentario' => $request->conRechazoAlimentario,
                'conLlanto' => $request->conLlanto,
                'conTricotilomania' => $request->conTricotilomania,
                'conOnicofagia' => $request->conOnicofagia,
                'conMorderUnias' => $request->conMorderUnias,
                'conSuccionPulgar' => $request->conSuccionPulgar,
                'conExplicaciones' => $request->conExplicaciones,
                'conPrivaciones' => $request->conPrivaciones,
                'conCorporal' => $request->conCorporal,
                'conAmenazas' => $request->conAmenazas,
                'conTiempoFuera' => $request->conTiempoFuera,
                'conOtros' => $request->conOtros,
                'conAplica' => $request->conAplica,
                'conRecompensa' => $request->conRecompensa
            ]);
    
            $actividad = Secundaria_alumnos_historia_clinica_actividades::select()->where('historia_id', '=', $historia_alumno->id)->first();
    
            $actividad->update([
                'historia_id' => $actividad->historia_id,
                'actJuguete' => $request->actJuguete,
                'actCuento' => $request->actCuento,
                'actPelicula' => $request->actPelicula,
                'actHorasTelevision' => $request->actHorasTelevision,
                'actTenologia' => $request->actTenologia,
                'actTipoJuguetes' => $request->actTipoJuguetes,
                'actApoyoTarea' => $request->actApoyoTarea,
                'actCuidado' => $request->actCuidado,
                'actObservacionExtra' => $request->actObservacionExtra,
                'actGradoSugerido' => $request->actGradoSugerido,
                'actGradoElegido' => $request->actGradoElegido,
                'actNombreEntrevista' => $request->actNombreEntrevista
            ]);
    
            alert('Escuela Modelo', 'El historial clinico se ha actualizo con éxito','success')->showConfirmButton();
            // return redirect()->route('secundaria.secundaria_historia_clinica.index');
    
            return back();

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode,$errorMessage)->showConfirmButton();
            return redirect('secundaria_historia_clinica/' . $id . '/edit')->withInput();
        }

    }

    
}
