<?php

namespace App\Http\Controllers\Primaria;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Curso;
use App\Http\Models\Primaria\Primaria_empleado;
use App\Http\Models\Primaria\Primaria_expediente_seguimiento_escolar;
use App\Http\Models\Primaria\Primaria_grupo;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;
use Validator;


class PrimariaSeguimientoEscolarController extends Controller
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
        return view('primaria.seguimiento_escolar.show-list');
    }

    public function list()
    {
        $primaria_empleado_id = Auth::user()->empleado_id;
        $perActual = Auth::user()->primaria_empleado->escuela->departamento->perActual; //obtener el periodo actual

       // recorrer todos los grupos y grados de acuerdo al ]id_docente y el año actual 
       $grados = DB::table('primaria_grupos')
       ->select(
           DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado'),
           DB::raw('count(*) as gpoClave, primaria_grupos.gpoClave')
       )
       ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
       ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
       ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
       ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
       ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
       ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
       ->join('programas', 'planes.programa_id', '=', 'programas.id')
       ->groupBy('primaria_grupos.gpoGrado')
       ->groupBy('primaria_grupos.gpoClave')
       ->where('primaria_grupos.periodo_id', $perActual)
       ->where('primaria_empleados.id', $primaria_empleado_id)
       ->get();


        foreach ($grados as $key => $value) {
            $primaria_expediente_seguimiento_escolar = Primaria_expediente_seguimiento_escolar::select(
                'primaria_expediente_seguimiento_escolar.id',
                'primaria_expediente_seguimiento_escolar.perAsistieronEntrevista',
                'primaria_expediente_seguimiento_escolar.entrevistaPeticion',
                'primaria_expediente_seguimiento_escolar.motivoEntrevista',
                'primaria_expediente_seguimiento_escolar.comentarioPadres',
                'primaria_expediente_seguimiento_escolar.acuerdosCompromisos',
                'primaria_expediente_seguimiento_escolar.observacionesEntrevista',
                'primaria_expediente_seguimiento_escolar.proximaEntrevista',
                'primaria_expediente_seguimiento_escolar.fechaEntrevista',
                'primaria_expediente_seguimiento_escolar.usuario_at',
                'cursos.id as curso_id',
                'cursos.curEstado',
                'cursos.curTipoBeca',
                'cursos.curPorcentajeBeca',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perNombre',
                'cgt.cgtGradoSemestre',
                'cgt.cgtGrupo',
                'periodos.perAnioPago',
                'planes.planClave',
                'departamentos.depClave',
                'departamentos.depNombre',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'programas.progClave',
                'escuelas.escClave')
            ->join('cursos', 'primaria_expediente_seguimiento_escolar.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->where('cgt.cgtGradoSemestre', $value->gpoGrado)
            ->where('cgt.cgtGrupo', $value->gpoClave)
            ->where('departamentos.depClave', 'PRI');
        }       



        return DataTables::of($primaria_expediente_seguimiento_escolar)

        

        // fecha entrevista 
        ->filterColumn('fecha_entrevista',function($query,$keyword){
            $query->whereRaw("CONCAT(DATE_FORMAT(fechaEntrevista, '%d-%m-%Y %H:%i')) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('fecha_entrevista',function($query){
            return \Carbon\Carbon::parse($query->fechaEntrevista)->format('d-m-Y H:i');          
        })

        // perAnioPAgo 
        ->filterColumn('anio_pago',function($query,$keyword){
            $query->whereRaw("CONCAT(perAnioPago) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('anio_pago',function($query){
            return $query->perAnioPago;
        })

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

        // estado del curso 
        ->filterColumn('estado_curso',function($query,$keyword){
            $query->whereRaw("CONCAT(curEstado) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('estado_curso',function($query){
            return $query->curEstado;
        })
        
        // cgtGradoSemestre
        ->filterColumn('grado_alumno',function($query,$keyword){
            $query->whereRaw("CONCAT(cgtGradoSemestre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('grado_alumno',function($query){
            return $query->cgtGradoSemestre;
        })

        // grupo 
        ->filterColumn('grupo_alumno',function($query,$keyword){
            $query->whereRaw("CONCAT(cgtGrupo) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('grupo_alumno',function($query){
            return $query->cgtGrupo;
        })

        // beca  
        ->filterColumn('tipo_beca',function($query,$keyword){
            $query->whereRaw("CONCAT(curTipoBeca, curPorcentajeBeca) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('tipo_beca',function($query){
            return $query->curTipoBeca . $query->curPorcentajeBeca;
        })

        // Ubicacion 
        ->filterColumn('ubicacion',function($query,$keyword){
            $query->whereRaw("CONCAT(ubiClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('ubicacion',function($query){
            return $query->ubiClave;
        })

        // departamento 
        ->filterColumn('departamento',function($query,$keyword){
            $query->whereRaw("CONCAT(depClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('departamento',function($query){
            return $query->depClave;
        })

        // escuela 
        ->filterColumn('escuela',function($query,$keyword){
            $query->whereRaw("CONCAT(escClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('escuela',function($query){
            return $query->escClave;
        })

        // programa 
        ->filterColumn('programa',function($query,$keyword){
            $query->whereRaw("CONCAT(progClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('programa',function($query){
            return $query->progClave;
        })

        // plan 
        ->filterColumn('plan',function($query,$keyword){
            $query->whereRaw("CONCAT(planClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('plan',function($query){
            return $query->planClave;
        })

  
        ->addColumn('action',function($query){
            return '<a href="primaria_seguimiento_escolar/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="primaria_seguimiento_escolar/'.$query->id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
            
            <a href="primaria_seguimiento_escolar/imprimir/' . $query->id . '" target="_blank" class="button button--icon js-button js-ripple-effect" title="Seguimiento escolar" >
                <i class="material-icons">picture_as_pdf</i>
            </a>

            <form id="delete_' . $query->id . '" action="primaria_seguimiento_escolar/' . $query->id . '" method="POST" style="display:inline;">
                 <input type="hidden" name="_method" value="DELETE">
               <input type="hidden" name="_token" value="' . csrf_token() . '">
                <a href="#" data-id="'  . $query->id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                     <i class="material-icons">delete</i>
                 </a>
            </form>';
        })->make(true);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $primaria_empleado_id = Auth::user()->empleado_id;
        $perActual = Auth::user()->empleado->escuela->departamento->perActual; //obtener el periodo actual

        $grados = DB::table('primaria_grupos')
        ->select(
            DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado'),
            DB::raw('count(*) as gpoClave, primaria_grupos.gpoClave')
        )
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
            ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->groupBy('primaria_grupos.gpoGrado')
            ->groupBy('primaria_grupos.gpoClave')
            ->where('primaria_grupos.periodo_id', $perActual)
            ->where('primaria_empleados.id', $primaria_empleado_id)
            ->get();

        $primaria_empleados = Primaria_empleado::get();

        $psicologos = Primaria_empleado::where('puesto_id', 21)->get();

        $director_docente = Primaria_empleado::where('puesto_id', 10)->get();

        foreach ($grados as $key => $value) {

            $alumnoCurso = Curso::select(
                'cursos.id as curso_id',
                'cursos.curEstado',
                'cursos.curTipoBeca',
                'cursos.curPorcentajeBeca',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perNombre',
                'cgt.cgtGradoSemestre',
                'cgt.cgtGrupo',
                'periodos.perAnioPago',
                'planes.planClave',
                'departamentos.depClave',
                'departamentos.depNombre',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'programas.progClave',
                'programas.progNombre',
                'escuelas.escClave'
            )
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'cgt.plan_id', '=', 'planes.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
                ->where('departamentos.depClave', 'PRI')
                ->where('cursos.curEstado', '!=', 'B')
                ->where('cgt.cgtGrupo', '!=', 'N')
                ->where('periodos.id', '=', $perActual)
                ->where('cgt.cgtGradoSemestre', $value->gpoGrado)
                ->where('cgt.cgtGrupo', $value->gpoClave)
                ->orderBy('periodos.perAnioPago', 'DESC')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('cgt.cgtGradoSemestre', 'DESC')
                ->orderBy('cgt.cgtGrupo', 'DESC')
                ->get();
        }



        return view('primaria.seguimiento_escolar.create', [
            'primaria_empleados' => $primaria_empleados,
            'alumnoCurso' => $alumnoCurso,
            'psicologos' => $psicologos,
            'director_docente' => $director_docente
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'curso_id'  => 'required',
                'fechaDelaEntrevista'  => 'required',
                'horaDeLaEntrevista'  => 'required',
                'entrevistaPeticion'  => 'required',
                'perAsistieronEntrevista'  => 'required',
                'motivoEntrevista'  => 'required',
                'primaria_empleado_id_docente'  => 'required',
                'primaria_empleado_id_psicologa'  => 'required',
            ],
            [
                'curso_id.required' => 'El campo Alumno es obligatorio.',
                'fechaDelaEntrevista.required' => 'El campo Fecha de la entrevista es obligatorio.',
                'fechaDelaEntrevista.required' => 'El campo Hora de la entrevista es obligatorio.',
                'entrevistaPeticion.required' => 'El campo Entrevista a petición de es obligatorio.',
                'perAsistieronEntrevista.required' => 'El campo Personas que asistieron a la entrevista es obligatorio.',
                'motivoEntrevista.required' => 'El campo Planteamiento (motivo de la entrevista) es obligatorio.',
                'primaria_empleado_id_docente.required' => 'El campo Empleado es obligatorio.',
                'primaria_empleado_id_docente.required' => 'El campo Psicóloga es obligatorio.',
            ]
        );


        if ($validator->fails()) {
            return redirect('primaria_seguimiento_escolar/create')->withErrors($validator)->withInput();
        } else {
            try {



                Primaria_expediente_seguimiento_escolar::create([
                    'curso_id' => $request->curso_id,
                    'fechaEntrevista' => $request->fechaDelaEntrevista . ' ' . $request->horaDeLaEntrevista,
                    'perAsistieronEntrevista' => $request->perAsistieronEntrevista,
                    'entrevistaPeticion' => $request->entrevistaPeticion,
                    'motivoEntrevista' => $request->motivoEntrevista,
                    'comentarioPadres' => $request->comentarioPadres,
                    'acuerdosCompromisos' => $request->acuerdosCompromisos,
                    'observacionesEntrevista' => $request->observacionesEntrevista,
                    'proximaEntrevista' => $request->proximaEntrevistaFecha . ' ' . $request->proximaEntrevistaHora,
                    'perAsistieron1NombreCompleto' => $request->perAsistieron1NombreCompleto,
                    'perAsistieron2NombreCompleto' => $request->perAsistieron2NombreCompleto,
                    'primaria_empleado_id_docente' => $request->primaria_empleado_id_docente,
                    'primaria_empleado_id_psicologa' => $request->primaria_empleado_id_psicologa,
                    'primaria_empleado_directora' => $request->primaria_empleado_directora,
                    'perNombreExtra' => $request->perNombreExtra

                ]);


                alert('Escuela Modelo', 'El seguimiento escolar se ha creado con éxito', 'success')->showConfirmButton()->autoClose('5000');
                return redirect()->route('primaria.primaria_seguimiento_escolar.index');
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                    ->error('Ups...' . $errorCode, $errorMessage)
                    ->showConfirmButton();
                return redirect('primaria_seguimiento_escolar/create')->withInput();
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
        $primaria_expediente_seguimiento_escolar = Primaria_expediente_seguimiento_escolar::select(
            'primaria_expediente_seguimiento_escolar.*',
            'cursos.id as curso_id',
            'cursos.curEstado',
            'cursos.curTipoBeca',
            'cursos.curPorcentajeBeca',
            'alumnos.id as alumno_id',
            'alumnos.aluClave',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'cgt.cgtGradoSemestre',
            'cgt.cgtGrupo',
            'periodos.perAnioPago',
            'planes.planClave',
            'departamentos.depClave',
            'departamentos.depNombre',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'programas.progClave',
            'escuelas.escClave',
            'primaria_empleados.empNombre',
            'primaria_empleados.empApellido1',
            'primaria_empleados.empApellido2',
            'empleadoPsicologa.empNombre as nombrePsi',
            'empleadoPsicologa.empApellido1 as apellido1Psi',
            'empleadoPsicologa.empApellido2 as apellido2Psi',
            'empleadoDirec.empNombre as nombreDirec',
            'empleadoDirec.empApellido1 as apellido1Direc',
            'empleadoDirec.empApellido2 as apellido2Direc')
        ->join('cursos', 'primaria_expediente_seguimiento_escolar.curso_id', '=', 'cursos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('primaria_empleados', 'primaria_expediente_seguimiento_escolar.primaria_empleado_id_docente', '=', 'primaria_empleados.id')
        ->join('primaria_empleados as empleadoPsicologa', 'primaria_expediente_seguimiento_escolar.primaria_empleado_id_psicologa', '=', 'empleadoPsicologa.id')
        ->join('primaria_empleados as empleadoDirec', 'primaria_expediente_seguimiento_escolar.primaria_empleado_directora', '=', 'empleadoDirec.id')

        ->where('primaria_expediente_seguimiento_escolar.id', $id)
        ->first();
    

        $alumnoCurso = Curso::select(
            'cursos.id as curso_id',
            'cursos.curEstado',
            'cursos.curTipoBeca',
            'cursos.curPorcentajeBeca',
            'alumnos.id as alumno_id',
            'alumnos.aluClave',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'cgt.cgtGradoSemestre',
            'cgt.cgtGrupo',
            'periodos.perAnioPago',
            'planes.planClave',
            'departamentos.depClave',
            'departamentos.depNombre',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'programas.progClave',
            'escuelas.escClave')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->where('departamentos.depClave', 'PRI')
        ->where('cursos.curEstado', '!=', 'B')
        ->orderBy('personas.perApellido1')
        ->orderBy('periodos.perAnioPago', 'DESC')

        ->get();


        return view('primaria.seguimiento_escolar.show', [
            'alumnoCurso' => $alumnoCurso,
            'primaria_expediente_seguimiento_escolar' => $primaria_expediente_seguimiento_escolar
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
        $primaria_empleado_id = Auth::user()->empleado_id;
        $perActual = Auth::user()->empleado->escuela->departamento->perActual; //obtener el periodo actual

        $primaria_expediente_seguimiento_escolar = Primaria_expediente_seguimiento_escolar::findOrFail($id);

        $primaria_empleados = Primaria_empleado::get();

        $psicologos = Primaria_empleado::where('puesto_id', 21)->get();

        $director_docente = Primaria_empleado::where('puesto_id', 10)->get();

        // recorrer todos los grupos y grados de acuerdo al ]id_docente y el año actual 
        $grados = DB::table('primaria_grupos')
        ->select(
            DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado'),
            DB::raw('count(*) as gpoClave, primaria_grupos.gpoClave')
        )
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
            ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->groupBy('primaria_grupos.gpoGrado')
            ->groupBy('primaria_grupos.gpoClave')
            ->where('primaria_grupos.periodo_id', $perActual)
            ->where('primaria_empleados.id', $primaria_empleado_id)
            ->get();

        foreach ($grados as $key => $value) {

            $alumnoCurso = Curso::select(
                'cursos.id as curso_id',
                'cursos.curEstado',
                'cursos.curTipoBeca',
                'cursos.curPorcentajeBeca',
                'alumnos.id as alumno_id',
                'alumnos.aluClave',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perNombre',
                'cgt.cgtGradoSemestre',
                'cgt.cgtGrupo',
                'periodos.perAnioPago',
                'planes.planClave',
                'departamentos.depClave',
                'departamentos.depNombre',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'programas.progClave',
                'programas.progNombre',
                'escuelas.escClave'
            )
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->join('planes', 'cgt.plan_id', '=', 'planes.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
                ->where('departamentos.depClave', 'PRI')
                ->where('cursos.curEstado', '!=', 'B')
                ->where('cgt.cgtGrupo', '!=', 'N')
                ->where('periodos.id', '=', $perActual)
                ->where('cgt.cgtGradoSemestre', $value->gpoGrado)
                ->where('cgt.cgtGrupo', $value->gpoClave)
                ->orderBy('periodos.perAnioPago', 'DESC')
                ->orderBy('personas.perApellido1', 'ASC')
                ->orderBy('cgt.cgtGradoSemestre', 'DESC')
                ->orderBy('cgt.cgtGrupo', 'DESC')
                ->get();
        }


        return view('primaria.seguimiento_escolar.edit', [
            'primaria_empleados' => $primaria_empleados,
            'alumnoCurso' => $alumnoCurso,
            'psicologos' => $psicologos,
            'primaria_expediente_seguimiento_escolar' => $primaria_expediente_seguimiento_escolar,
            'director_docente' => $director_docente
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
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'curso_id'  => 'required',
                    'fechaDelaEntrevista'  => 'required',
                    'horaDeLaEntrevista'  => 'required',
                    'entrevistaPeticion'  => 'required',
                    'perAsistieronEntrevista'  => 'required',
                    'motivoEntrevista'  => 'required',
                    'primaria_empleado_id_docente'  => 'required',
                    'primaria_empleado_id_psicologa'  => 'required',
                ],
                [
                    'curso_id.required' => 'El campo Alumno es obligatorio.',
                    'fechaDelaEntrevista.required' => 'El campo Fecha de la entrevista es obligatorio.',
                    'fechaDelaEntrevista.required' => 'El campo Hora de la entrevista es obligatorio.',
                    'entrevistaPeticion.required' => 'El campo Entrevista a petición de es obligatorio.',
                    'perAsistieronEntrevista.required' => 'El campo Personas que asistieron a la entrevista es obligatorio.',
                    'motivoEntrevista.required' => 'El campo Planteamiento (motivo de la entrevista) es obligatorio.',
                    'primaria_empleado_id_docente.required' => 'El campo Empleado es obligatorio.',
                    'primaria_empleado_id_docente.required' => 'El campo Psicóloga es obligatorio.',
                ]
            );
    
    
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                try {
    
                    $primaria_expediente_seguimiento_escolar = Primaria_expediente_seguimiento_escolar::findOrFail($id);
    
    
                    $primaria_expediente_seguimiento_escolar->update([
                        'curso_id' => $request->curso_id,
                        'fechaEntrevista' => $request->fechaDelaEntrevista . ' ' . $request->horaDeLaEntrevista,
                        'perAsistieronEntrevista' => $request->perAsistieronEntrevista,
                        'entrevistaPeticion' => $request->entrevistaPeticion,
                        'motivoEntrevista' => $request->motivoEntrevista,
                        'comentarioPadres' => $request->comentarioPadres,
                        'acuerdosCompromisos' => $request->acuerdosCompromisos,
                        'observacionesEntrevista' => $request->observacionesEntrevista,
                        'proximaEntrevista' => $request->proximaEntrevistaFecha . ' ' . $request->proximaEntrevistaHora,
                        'perAsistieron1NombreCompleto' => $request->perAsistieron1NombreCompleto,
                        'perAsistieron2NombreCompleto' => $request->perAsistieron2NombreCompleto,
                        'primaria_empleado_id_docente' => $request->primaria_empleado_id_docente,
                        'primaria_empleado_id_psicologa' => $request->primaria_empleado_id_psicologa,
                        'primaria_empleado_directora' => $request->primaria_empleado_directora,
                        'perNombreExtra' => $request->perNombreExtra
                    ]);
    
    
                    alert('Escuela Modelo', 'El seguimiento escolar se ha actualizado con éxito', 'success')->showConfirmButton()->autoClose('5000');
                    return redirect()->route('primaria.primaria_seguimiento_escolar.index');
                } catch (QueryException $e) {
                    $errorCode = $e->errorInfo[1];
                    $errorMessage = $e->errorInfo[2];
                    alert()
                        ->error('Ups...' . $errorCode, $errorMessage)
                        ->showConfirmButton();
                    return back()->withInput();
                }
            }
        }
    }


    public function imprimir(Request $request, $id)
    {
        $primaria_expediente_seguimiento_escolar = Primaria_expediente_seguimiento_escolar::select(
            'primaria_expediente_seguimiento_escolar.*',
            'cursos.id as curso_id',
            'cursos.curEstado',
            'cursos.curTipoBeca',
            'cursos.curPorcentajeBeca',
            'alumnos.id as alumno_id',
            'alumnos.aluClave',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'personas.perFechaNac',
            'cgt.cgtGradoSemestre',
            'cgt.cgtGrupo',
            'periodos.perAnioPago',
            'planes.planClave',
            'departamentos.depClave',
            'departamentos.depNombre',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'programas.progClave',
            'escuelas.escClave',
            'primaria_empleados.empNombre',
            'primaria_empleados.empApellido1',
            'primaria_empleados.empApellido2',
            'primaria_empleados.empSexo',
            'empleadoPsicologa.empNombre as nombrePsi',
            'empleadoPsicologa.empApellido1 as apellido1Psi',
            'empleadoPsicologa.empApellido2 as apellido2Psi',
            'empleadoPsicologa.empSexo')
        ->join('cursos', 'primaria_expediente_seguimiento_escolar.curso_id', '=', 'cursos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('primaria_empleados', 'primaria_expediente_seguimiento_escolar.primaria_empleado_id_docente', '=', 'primaria_empleados.id')
        ->join('primaria_empleados as empleadoPsicologa', 'primaria_expediente_seguimiento_escolar.primaria_empleado_id_psicologa', '=', 'empleadoPsicologa.id')
        ->where('primaria_expediente_seguimiento_escolar.id', $id)
        ->first();


        $perAnioPago = $primaria_expediente_seguimiento_escolar->perAnioPago;
        $perAnioPagoSiguiente = $perAnioPago+1;
        $ciclo_escolar = 'Ciclo escolar '.$perAnioPago.'-'.$perAnioPagoSiguiente;
        $diaNac = \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->perFechaNac)->format('d');
        $mesNac = \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->perFechaNac)->format('m');
        $yearNAc = \Carbon\Carbon::parse($primaria_expediente_seguimiento_escolar->perFechaNac)->format('Y');

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $hoy = $fechaActual->format('Y');

        $edad = $hoy - $yearNAc;

        if($mesNac == "01"){
            $mes = "enero";
        }
        if($mesNac == "02"){
            $mes = "febrero";
        }
        if($mesNac == "03"){
            $mes = "marzo";
        }
        if($mesNac == "04"){
            $mes = "abril";
        }
        if($mesNac == "05"){
            $mes = "mayo";
        }
        if($mesNac == "06"){
            $mes = "junio";
        }
        if($mesNac == "07"){
            $mes = "julio";
        }
        if($mesNac == "08"){
            $mes = "agosto";
        }
        if($mesNac == "09"){
            $mes = "septiembre";
        }
        if($mesNac == "10"){
            $mes = "octubre";
        }
        if($mesNac == "11"){
            $mes = "noviembre";
        }
        if($mesNac == "12"){
            $mes = "diciembre";
        }

        $parametro_NombreArchivo = "pdf_primaria_seguimiento_escolar";
        $pdf = PDF::loadView('reportes.pdf.primaria.seguimiento_escolar.' . $parametro_NombreArchivo, [
            "ciclo_escolar" => $ciclo_escolar,
            "primaria_expediente_seguimiento_escolar" => $primaria_expediente_seguimiento_escolar,
            "diaNac" => $diaNac,
            "mes" => $mes,
            "yearNAc" => $yearNAc,
            "edad" => $edad
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
        $primaria_expediente_seguimiento_escolar = Primaria_expediente_seguimiento_escolar::findOrFail($id);
        try {
            if ($primaria_expediente_seguimiento_escolar->delete()) {
                alert('Escuela Modelo', 'El seguimiento escolar se ha eliminado con éxito', 'success')->showConfirmButton()->autoClose('5000');
            } else {
                alert()->error('Error...', 'No se puedo eliminar el seguimiento escolar')->showConfirmButton()->autoClose('5000');
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }

        return redirect('primaria_seguimiento_escolar');
    }
}
