<?php

namespace App\Http\Controllers;

use PDF;
use URL;
use Auth;
use Debugbar;
use Validator;
use Carbon\Carbon;
use App\Http\Models\Cgt;
use App\Http\Models\Aula;
use App\Http\Models\Plan;
use App\Http\Models\Curso;
use App\Http\Models\Grupo;
use App\Http\Helpers\Utils;
use App\Http\Models\Alumno;
use Illuminate\Support\Str;
use App\Http\Models\Materia;
use App\Http\Models\Periodo;
use Illuminate\Http\Request;
use App\Http\Models\Empleado;
use App\Http\Models\Inscrito;
use App\Http\Models\Optativa;
use App\Http\Models\Programa;
use App\Http\Models\Historico;
use App\Http\Models\Ubicacion;
use App\Http\Models\Calificacion;
use App\Http\Models\Prerequisito;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Extraordinario;
use App\Http\Models\Paquete_detalle;
use App\Http\Models\ResumenAcademico;
use Illuminate\Database\QueryException;
use Luecano\NumeroALetras\NumeroALetras;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Models\InscritoExtraordinario;
//use DB;


class ExtraordinarioController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permisos:extraordinario', ['except' => ['index', 'show', 'list', 'getExtraordinario', 'list_solicitudes', 'solicitudes']]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('extraordinario.show-list');
    }

    /**
     * Show list.
     *
     */
    public function list()
    {
        $empleadoId =  Auth::user()->empleado_id;
        $empleado = Auth::user()->empleado->escuela->departamento->perActual;

        $extraordinarios = Extraordinario::select(
            'extraordinarios.id as extraordinario_id','extraordinarios.extAlumnosInscritos',
            'extraordinarios.periodo_id',
            'extraordinarios.extPago','extraordinarios.extFecha','extraordinarios.extHora',
            'periodos.perNumero','periodos.perAnio','materias.matClave','materias.matNombre',
            'personas.perNombre','personas.perApellido1','personas.perApellido2','planes.planClave',
            'programas.progNombre','ubicacion.ubiNombre','optativas.optNombre', 'empleados.id',
            'departamentos.perActual')
            ->join('periodos', 'extraordinarios.periodo_id', '=', 'periodos.id')
            ->join('materias', 'extraordinarios.materia_id', '=', 'materias.id')
            ->join('planes', 'materias.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->leftJoin('aulas', 'extraordinarios.aula_id', '=', 'aulas.id')
            ->join('empleados', 'extraordinarios.empleado_id', '=', 'empleados.id')
            ->join('personas', 'empleados.persona_id', '=', 'personas.id')
            ->leftjoin('optativas','extraordinarios.optativa_id','optativas.id')
            ->where("empleados.id", "=", $empleadoId)
            ->where("periodo_id", "=", $empleado)
            ->where("extAlumnosInscritos", ">", 0);
            // dd($extraordinarios->get());

            // dd($empleado);
            // dd($extraordinarios->first());


        return Datatables::of($extraordinarios)
            ->filterColumn('nombreCompleto',function($query, $keyword) {
                return $query->whereHas('empleado.persona', function($query) use($keyword) {
                    $query->whereRaw("CONCAT(perNombre, ' ', perApellido1, ' ', perApellido2) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('nombreCompleto',function($query) {
                return $query->perNombre . " " . $query->perApellido1 . " " . $query->perApellido2;
            })
            ->addColumn('action',function($query) {
                return '<a href="calificacion/agregarextra/'
                . $query->extraordinario_id
                . '" class="button button--icon js-button js-ripple-effect" title="Calificaciones">
                    <i class="material-icons">assignment_turned_in</i>
                </a>
                <form class="form-acta-examen'.$query->extraordinario_id.'" target="_blank" action="extraordinario/actaexamen/'.$query->extraordinario_id.'" method="POST" style="display: inline;">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <a href="#" data-id="'.$query->extraordinario_id.'" class="button button--icon js-button js-ripple-effect confirm-acta-examen" title="Acta de examen extraordinario">
                        <i class="material-icons">assignment</i>
                    </a>
                </form>';
            })
        ->make(true);
    }



    public function agregarExtra($extraordinario_id)
    {
        //OBTENER Extraordinario e inscritos
        $extraordinario  = Extraordinario::with('materia.plan.programa','periodo','empleado.persona')->find($extraordinario_id);
        $inscritoextra  = InscritoExtraordinario::with('alumno.persona')->where('extraordinario_id',$extraordinario_id)->where('iexEstado','!=','C')->get();

        $inscritos = $inscritoextra->map(function ($item, $key) {
            $item->sortByNombres = $item->alumno->persona->perApellido1 . "-" .
            $item->alumno->persona->perApellido2  . "-" .
            $item->alumno->persona->perNombre;
            $item->iexEstado;

            return $item;
        })->sortBy("sortByNombres");

        $motivosFalta = DB::table("motivosfalta")->get()->sortByDesc("id");


        if ($inscritos->count() == 0){
          alert('Escuela Modelo', 'No hay inscritos para este extraordinario.', 'warning')->showConfirmButton();
          return redirect()->back()->withInput();
        }

        return view('calificacion.extraordinario.create',compact('extraordinario','inscritos', 'motivosFalta'));

    }





    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function extraStore(Request $request)
    {
        $extraordinario_id = $request->extraordinario_id;
        //OBTENER Inscritos Extraordinarios
        $extraordinario  = Extraordinario::with('materia.plan.programa','periodo','empleado.persona')->find($extraordinario_id);
        $inscritoextra  = InscritoExtraordinario::with('alumno.persona')
            ->where('extraordinario_id',$extraordinario_id)
            ->where('iexEstado','!=','C')->get();

        /*
        if (!$request->has("calificacion.inscEx"))
        {
            alert()->error('Error...NO ESCRIBISTE NINGUNA CALIFICACION, FIJATE!!!!!!!!!!' )->showConfirmButton();
            return redirect('calificacion/agregarextra/' . $extraordinario_id)->withInput();
        }
        */

        try {

            $calificacion = $request->calificacion;

            $inscEx  = $request->has("calificacion.inscEx")  ? collect($calificacion["inscEx"])  : collect();
            $asistencia = $request->has("calificacion.asistencia")  ? collect($calificacion["asistencia"])  : collect();


            foreach ($inscritoextra as $inscrito) {
                $inscritoEx = InscritoExtraordinario::find($inscrito->id);

                $calificacionEx = $inscEx->filter(function ($value, $key) use ($inscrito) {
                    return $key == $inscrito->id;
                })->first();

                if (!is_null($calificacionEx)) {

                    $miAsistencia = $asistencia->filter(function ($value, $key) use ($inscrito) {
                        return $key == $inscrito->id;
                    })->first();

                    //dd($inscEx, $asistencia, $calificacionEx, $miAsistencia);

                    if (is_null($miAsistencia)) {

                        //dd($inscEx, $asistencia, $calificacionEx, $miAsistencia);

                        alert('Escuela Modelo', 'No ha seleccionado el motivo de falta para una de las calificaciones. Favor de verificar.', 'error')->showConfirmButton();
                        return redirect('calificacion/agregarextra/' . $extraordinario_id)->withInput();
                    }
                    else
                    {
                        if ((int)$miAsistencia != 10) {
                            $calificacionEx = 0;
                        }

                        if ($inscritoEx) {
                            $inscritoEx->iexCalificacion = !is_null($calificacionEx) ? $calificacionEx : $inscritoEx->iexCalificacion;
                            $inscritoEx->motivofalta_id = $miAsistencia;
                            $inscritoEx->save();
                        }
                    }

                }
            }

            alert('Escuela Modelo', 'Se registraron las calificaciones con éxito', 'success')->showConfirmButton();
            return redirect('calificacion/agregarextra/' . $extraordinario_id)->withInput();

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];

            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return redirect('calificacion/agregarextra/' . $extraordinario_id)->withInput();

        }
    }




    public function actaExamen($id){

        $extraordinario = Extraordinario::find($id);
        $inscritos = InscritoExtraordinario::where('extraordinario_id',$extraordinario->id)->get();

        if(count($inscritos) == 0){
            alert()->error('Error', 'No existen registros con la información proporcionada');
                return back()->withInput();
          }

        $inscritoIds = $inscritos->map(function($item,$key){
            return $item->id;
        });

        $inscritoEx = collect();
        $fechaActual = Carbon::now();
        $periodo = '';

        foreach($inscritoIds as $inscrito_id){
            $inscrito = InscritoExtraordinario::where('id', '=', $inscrito_id)->first();
            $idExtra = $inscrito->extraordinario->id;
            //Datos del alumno
            $aluClave = $inscrito->alumno->aluClave;
            $perApellido1 = $inscrito->alumno->persona->perApellido1;
            $perApellido2 = $inscrito->alumno->persona->perApellido2;
            $perNombre = $inscrito->alumno->persona->perNombre;
            $alumnoNombre = $perApellido1.' '.$perApellido2.' '.$perNombre;
            //Datos del empleado (maestro)
            $perApellido1Emp = $inscrito->extraordinario->empleado->persona->perApellido1;
            $perApellido2Emp = $inscrito->extraordinario->empleado->persona->perApellido2;
            $perNombreEmp = $inscrito->extraordinario->empleado->persona->perNombre;
            $empleadoNombre = $perNombreEmp.' '.$perApellido1Emp.' '.$perApellido2Emp;
            $empleadoId = $inscrito->extraordinario->empleado_id;
            //Datos de la secretaria administrativa
            $depTituloDoc = $inscrito->extraordinario->materia->plan->programa->escuela->departamento->depTituloDoc;
            $depNombreDoc = $inscrito->extraordinario->materia->plan->programa->escuela->departamento->depNombreDoc;
            $depPuestoDoc = $inscrito->extraordinario->materia->plan->programa->escuela->departamento->depPuestoDoc;

            $iexCalificacion = $inscrito->iexCalificacion;
            $planClave = $inscrito->extraordinario->materia->plan->planClave;
            $progClave = $inscrito->extraordinario->materia->plan->programa->progClave;
            $progNombre = $inscrito->extraordinario->materia->plan->programa->progNombre;
            $ubiClave = $inscrito->extraordinario->materia->plan->programa->escuela->departamento->ubicacion->ubiClave;
            $ubiNombre = $inscrito->extraordinario->materia->plan->programa->escuela->departamento->ubicacion->ubiNombre;
            $matClave = $inscrito->extraordinario->materia->matClave;
            $matNombre = $inscrito->extraordinario->materia->matNombre;
            $extClave = $inscrito->extraordinario->id;
            $extFecha = $inscrito->extraordinario->extFecha;
            $extHora = $inscrito->extraordinario->extHora;
            $extGrupo = $inscrito->extraordinario->extGrupo;

            // $califLetras = strstr(NumeroALetras::convert($iexCalificacion),0,-11);
            $califLetras = str_replace(" CON 00/100","",NumeroALetras::convert($iexCalificacion));

            $optativa = Optativa::where('id',$inscrito->extraordinario->optativa_id)->first();

            $inscritoEx->push([
              'idExtra'=>$idExtra,
              'aluClave'=>$aluClave,
              'perApellido1'=>$perApellido1,
              'alumnoNombre'=>$alumnoNombre,
              'empleadoNombre'=>$empleadoNombre,
              'empleadoId'=>$empleadoId,
              'depTituloDoc'=>$depTituloDoc,
              'depNombreDoc'=>$depNombreDoc,
              'depPuestoDoc'=>$depPuestoDoc,
              'iexCalificacion'=>$iexCalificacion,
              'califLetras'=>$califLetras,
              'progClave'=>$progClave,
              'progNombre'=>$progNombre,
              'matClave'=>$matClave,
              'planClave'=>$planClave,
              'matNombre'=>$matNombre,
              'extClave'=>$extClave,
              'extFecha'=>$extFecha,
              'extHora'=>$extHora,
              'extGrupo'=>$extGrupo,
              'ubiClave'=>$ubiClave,
              'optativa'=>$optativa,
              'ubiNombre'=>$ubiNombre
            ]);

          }

          $inscritoEx = $inscritoEx->sortBy('perApellido1');
          $inscritoEx = $inscritoEx->groupBy('idExtra');

          setlocale(LC_TIME, 'es_ES.UTF-8');
          // En windows
          setlocale(LC_TIME, 'spanish');

          $nombreArchivo = 'pdf_acta_extraordinario';
          $pdf = PDF::loadView('pdf.'. $nombreArchivo, [

            "inscritoEx" => $inscritoEx,
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "horaActual" => $fechaActual->format('H:i:s'),
            "nombreArchivo" => $nombreArchivo,
            "periodo" => $periodo
            /*
            "nombreArchivo" => $nombreArchivo,
            "aluEstado" => $request->aluEstado,
            "fechaActual" => $fechaActual->toDateString(),
            "horaActual" => $fechaActual->toTimeString()
            */
          ]);


          $pdf->setPaper('letter', 'portrait');
          $pdf->defaultFont = 'Times Sans Serif';

          return $pdf->stream($nombreArchivo . '.pdf');
          return $pdf->download($nombreArchivo  . '.pdf');

    }

}
