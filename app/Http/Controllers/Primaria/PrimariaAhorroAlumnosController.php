<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Primaria\Primaria_empleado;
use App\Models\Primaria\Primaria_inscritos_ahorro;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Auth;

class PrimariaAhorroAlumnosController extends Controller
{
    // validacion del logueo 
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
        return view('primaria.ahorro_alumnos.show-list');
    }

    public function list()
    {
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;
        $perActual = Auth::user()->primaria_empleado->escuela->departamento->perActual;
        // $grupo = Primaria_grupo::where('empleado_id_docente', $primaria_empleado_id)->get();

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
            
            $alumno_entrevista = Primaria_inscritos_ahorro::select(
                'primaria_inscritos_ahorro.*',
                'alumnos.aluClave',
                'personas.perApellido1',
                'personas.perApellido2',
                'personas.perNombre',
                'periodos.perAnioPago',
                'cursos.curEstado',
                'cursos.curTipoBeca',
                'cursos.curPorcentajeBeca',
                'cgt.cgtGradoSemestre', 
                'cgt.cgtGrupo',
                'ubicacion.ubiNombre',
                'departamentos.depNombre', 
                'departamentos.depClave',
                'escuelas.escNombre', 
                'escuelas.escClave',
                'programas.progNombre', 
                'programas.progClave',
                'planes.planClave')
            ->join('cursos', 'primaria_inscritos_ahorro.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->whereIn('depClave', ['PRI'])
            ->where('periodos.id', '=', $perActual)
            ->where('cgt.cgtGradoSemestre', $value->gpoGrado)
            ->where('cgt.cgtGrupo', $value->gpoClave);
        }




        return DataTables::of($alumno_entrevista)

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
            $query->whereRaw("CONCAT(ubiNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('ubicacion',function($query){
            return $query->ubiNombre;
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
            return '<a href="primaria_ahorro_escolar/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            ';
        })->make(true);

  

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($empleado_id = null, $curso_id = null)
    {
        $primaria_empleados = Primaria_empleado::get();


        $usuario_logueado = Auth::user()->primaria_empleado->id;
        $perActual = Auth::user()->primaria_empleado->escuela->departamento->perActual;

        $primaria_empleado_id = Auth::user()->primaria_empleado->id;
        $perActual = Auth::user()->primaria_empleado->escuela->departamento->perActual;
        // $grupo = Primaria_grupo::where('empleado_id_docente', $primaria_empleado_id)->get();

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

        if($curso_id != ""){
            // $saldoDisponible = Primaria_inscritos_ahorro::select('')
            $resultado_array =  DB::select("call procPrimariaAhorroSaldoAlumnoCurso(" . $curso_id . ")");
            $resultado_collection = collect($resultado_array);
            $saldo_inicial = $resultado_collection[0]->_saldo;
        }else{
            $saldo_inicial = "";
        }


        return view('primaria.ahorro_alumnos.create', [
            'alumnoCurso' => $alumnoCurso,
            "primaria_empleados" => $primaria_empleados,
            "empleado_id" => $empleado_id,
            "curso_id" => $curso_id,
            "usuario_logueado" => $usuario_logueado,
            "saldo_inicial" => $saldo_inicial
        ]); 
    }

    public function mostrarSaldoEnCuenta(Request $request, $curso_id)
    {
        if($request->ajax()){


            $ultimoRegistro =  DB::select("SELECT primaria_inscritos_ahorro.* FROM primaria_inscritos_ahorro WHERE curso_id = $curso_id ORDER BY id DESC LIMIT 1");


            // return response()->json($alumnos);
            return response()->json($ultimoRegistro);
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
        $validator = Validator::make(
            $request->all(),
            [
                'curso_id'  => 'required',
                'fecha'  => 'required',
                'aplica_mes_nombre'  => 'required',
                'importe'  => 'required',
                'movimiento'  => 'required'
                
            ],
            [
                'curso_id.required' => 'El campo Alumno es obligatorio.',
                'fecha.required' => 'El campo Fecha de movimiento es obligatorio.',
                'aplica_mes_nombre.required' => 'El campo Mes de movimiento es obligatorio.',
                'importe.required' => 'El campo Importe es obligatorio.',
                'movimiento.required' => 'El Tipo de Moviento es obligatorio.'
            ]
        );

        $curso_id_desdepantalla = $request->curso_id_desdepantalla;
        $empleado_id_desdepantalla = $request->empleado_id_desdepantalla;


        if ($validator->fails()) {

            if($curso_id_desdepantalla == ""){
                return redirect('primaria_ahorro_escolar/create')->withErrors($validator)->withInput();
            }else{
                return redirect('primaria_ahorro_escolar/create/alumno/'.$empleado_id_desdepantalla.'/'.$curso_id_desdepantalla)->withErrors($validator)->withInput();
            }
        }else{
            try {

                              
                $curso_id = $request->curso_id;
                $primaria_empleado_id = $request->primaria_empleado_id;
                $fecha = $request->fecha;
                $aplica_mes_nombre = $request->aplica_mes_nombre;
                $importe = $request->importe;
                $movimiento = $request->movimiento;
                $observacion = $request->observacion;

             
                // manda el monto inicial 
                $resultado_array =  DB::select("call procPrimariaAhorroSaldoAlumnoCurso(" . $request->curso_id . ")");
                $resultado_collection = collect($resultado_array);
                $saldo_inicial = $resultado_collection[0]->_saldo;

              
                // si el movimiento es DEPOSITO se hace lo siguiente 
                if($movimiento == "DEPOSITO"){

                    $saldo_final = $importe + $saldo_inicial;

                }
                                        
                
                // si el movimiento es depositar se hace lo siguiente 
                if($movimiento == "RETIRO"){

                    if($saldo_inicial >= $importe){
                        $saldo_final = $saldo_inicial - $importe;

                    }else{
                        alert('Escuela Modelo', 'No es posible realizar el trámite por saldo insuficiente', 'info')->showConfirmButton()->autoClose('5000');

                        if($curso_id_desdepantalla == ""){
                            return redirect('primaria_ahorro_escolar/create')->withErrors($validator)->withInput();
                        }else{
                            return redirect('primaria_ahorro_escolar/create/alumno/'.$empleado_id_desdepantalla.'/'.$curso_id_desdepantalla)->withErrors($validator)->withInput();
                        }
                    }
                }
                



                $primaria_inscritos_ahorro = Primaria_inscritos_ahorro::create([
                    'curso_id' => $curso_id,
                    'primaria_empleado_id' => $primaria_empleado_id,
                    'fecha' => $fecha,
                    'aplica_mes_nombre' => $aplica_mes_nombre,
                    'importe' => $importe,
                    'movimiento' => $movimiento,
                    'saldo_inicial' => $saldo_inicial,
                    'saldo_final' => $saldo_final,
                    'observacion' => $observacion,
                ]);
            


                alert('Escuela Modelo', 'El trámite se realizo con éxito', 'success')->showConfirmButton()->autoClose('5000');
                // return redirect()->route('primaria.primaria_ahorro_escolar.index');
                return back();

            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                    ->error('Ups...' . $errorCode, $errorMessage)
                    ->showConfirmButton();
                return redirect('primaria_ahorro_escolar/create')->withInput();
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
        $primaria_inscritos_ahorro = Primaria_inscritos_ahorro::with('curso.alumno.persona', 
        'curso.periodo.departamento.ubicacion', 'curso.cgt.plan.programa.escuela', 'primaria_empleado')
        ->findOrFail($id);

        return view('primaria.ahorro_alumnos.show',[
            "primaria_inscritos_ahorro" => $primaria_inscritos_ahorro
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
        //
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
        //
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
