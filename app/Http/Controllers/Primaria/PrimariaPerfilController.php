<?php

namespace App\Http\Controllers\Primaria;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Primaria\Primaria_contenidos_calificadores;
use App\Models\Primaria\Primaria_contenidos_fundamentales;
use App\Models\Primaria\Primaria_expediente_perfiles;
use App\Models\Primaria\Primaria_expediente_perfiles_contenidos;
use App\Models\Primaria\Primaria_grupo;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class PrimariaPerfilController extends Controller
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
        $primaria_empleado_id = Auth::user()->primaria_empleado->id;
        $perActual = Auth::user()->primaria_empleado->escuela->departamento->perActual;
        // return $grupo = Primaria_grupo::where('empleado_id_docente', $primaria_empleado_id)->where('periodo_id', $perActual)->get();
        $grados = DB::table('primaria_grupos')
        ->select(
            DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado')
        )
        ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('planes', 'primaria_materias.plan_id', '=', 'planes.id')
        ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->leftJoin('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->groupBy('primaria_grupos.gpoGrado')
        ->where('primaria_grupos.periodo_id', $perActual)
        ->where('primaria_empleados.id', $primaria_empleado_id)
        ->get();

        return view('primaria.perfil.show-list');
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
            $alumno_entrevista = Primaria_expediente_perfiles::select(
                'primaria_expediente_perfiles.*',
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
            ->join('cursos', 'primaria_expediente_perfiles.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('periodos.id', '=', $perActual)
            ->where('cgt.cgtGradoSemestre', $value->gpoGrado)
            ->where('cgt.cgtGrupo', $value->gpoClave)
            ->whereIn('cursos.curEstado', ['R', 'P'])
            ->whereIn('depClave', ['PRI']);
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
            return '<a href="primaria_perfil/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="primaria_perfil/'.$query->id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>

            <a href="primaria_perfil/imprimir/' . $query->id . '" target="_blank" class="button button--icon js-button js-ripple-effect" title="Imprimir perfil" >
                <i class="material-icons">picture_as_pdf</i>
            </a>';
        })->make(true);

        // <form id="delete_' . $query->id . '" action="primaria_perfil/' . $query->id . '" method="POST" style="display:inline;">
        //         <input type="hidden" name="_method" value="DELETE">
        //         <input type="hidden" name="_token" value="' . csrf_token() . '">
        //         <a href="#" data-id="'  . $query->id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
        //             <i class="material-icons">delete</i>
        //         </a>
        //     </form>

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contenido_fundamentales = Primaria_contenidos_fundamentales::get();

        $calificadores = Primaria_contenidos_calificadores::get();

        return view('primaria.perfil.create', [
            "contenido_fundamentales" => $contenido_fundamentales,
            "calificadores" => $calificadores
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
        $contenido_fundamentales = Primaria_contenidos_fundamentales::get();

        $expediente_perfil = Primaria_expediente_perfiles::where('id', $id)->first();

        $primaria_expediente_perfiles_contenidos = Primaria_expediente_perfiles_contenidos::select(
            'primaria_expediente_perfiles_contenidos.id',
            'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id',
            'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id',
            'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id',
            'primaria_expediente_perfiles_contenidos.observacion_contenido',
            'primaria_contenidos_fundamentales.contenido',
            'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id',
            'primaria_contenidos_calificadores.calificador',
            'primaria_contenidos_categorias.categoria')
        ->join('primaria_expediente_perfiles', 'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', '=', 'primaria_expediente_perfiles.id')
        ->join('primaria_contenidos_fundamentales', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id', '=', 'primaria_contenidos_fundamentales.id')
        ->leftJoin('primaria_contenidos_calificadores', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '=', 'primaria_contenidos_calificadores.id')
        ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
        ->where('primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', $expediente_perfil->id)
        ->where('primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '!=', 'NULL')
        ->orderBy('primaria_expediente_perfiles_contenidos.id', 'ASC')
        ->get();

        if ($primaria_expediente_perfiles_contenidos->isEmpty()) {
            alert('Sin coincidencias', 'Aun no se ha capturado datos para este alumno.', 'warning')->showConfirmButton();
            return back()->withInput();
            $parametro = "true";
        }else{
            $parametro = "false";
        }

        $calificadores = Primaria_contenidos_calificadores::get();


        $cursoAlumno =  DB::select("call procPrimariaCalificacionesCursoPerfil(".$expediente_perfil->curso_id.")");
        $alumnoDatos_collection = collect($cursoAlumno);
        $gradoAlumno = $alumnoDatos_collection[0]->semestre;
        $grupoAlumno = $alumnoDatos_collection[0]->grupo;
        $ciclo_escolar = $alumnoDatos_collection[0]->ciclo_escolar;
        $perSexo = $alumnoDatos_collection[0]->perSexo;
        $persona = $alumnoDatos_collection[0]->ape_paterno.' '.$alumnoDatos_collection[0]->ape_materno.' '.$alumnoDatos_collection[0]->nombres;
        $fechaNac = $alumnoDatos_collection[0]->perFechaNac;


        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $anioNacimiento = explode("-", $fechaNac);
        $anoHoy = $fechaActual->format('Y');
        // calcular edad (año actual - año nacimiento alumno)
        $edadCalculada = $anoHoy - $anioNacimiento[0];

        if($perSexo == "F"){
            $alumno = "Alumna: $persona";
        }else{
            $alumno = "Alumno: $persona";
        }


        return view('primaria.perfil.show', [
            "contenido_fundamentales" => $contenido_fundamentales,
            "calificadores" => $calificadores,
            "gradoAlumno" => $gradoAlumno,
            "grupoAlumno" => $grupoAlumno,
            "ciclo_escolar" => $ciclo_escolar,
            "alumno" => $alumno,
            "edadCalculada" => $edadCalculada,
            "expediente_perfil" => $expediente_perfil,
            "parametro" => $parametro,
            "primaria_expediente_perfiles_contenidos" => $primaria_expediente_perfiles_contenidos
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
        $primaria_contenidos_fundamentales = Primaria_contenidos_fundamentales::get();

        $expediente_perfil = Primaria_expediente_perfiles::where('id', $id)->first();

        $primaria_expediente_perfiles_contenidos = Primaria_expediente_perfiles_contenidos::select(
            'primaria_expediente_perfiles_contenidos.id',
            'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id',
            'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id',
            'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id',
            'primaria_expediente_perfiles_contenidos.observacion_contenido',
            'primaria_contenidos_fundamentales.contenido',
            'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id',
            'primaria_contenidos_categorias.categoria')
        ->join('primaria_expediente_perfiles', 'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', '=', 'primaria_expediente_perfiles.id')
        ->join('primaria_contenidos_fundamentales', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id', '=', 'primaria_contenidos_fundamentales.id')
        ->leftJoin('primaria_contenidos_calificadores', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '=', 'primaria_contenidos_calificadores.id')
        ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
        ->where('primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', $expediente_perfil->id)
        ->get();

        if ($primaria_expediente_perfiles_contenidos->isEmpty()) {
            $parametro = "true";
        }else{
            $parametro = "false";
        }

        $calificadores = Primaria_contenidos_calificadores::get();


        $cursoAlumno =  DB::select("call procPrimariaCalificacionesCursoPerfil(".$expediente_perfil->curso_id.")");
        $alumnoDatos_collection = collect($cursoAlumno);
        $gradoAlumno = $alumnoDatos_collection[0]->semestre;
        $grupoAlumno = $alumnoDatos_collection[0]->grupo;
        $ciclo_escolar = $alumnoDatos_collection[0]->ciclo_escolar;
        $perSexo = $alumnoDatos_collection[0]->perSexo;
        $persona = $alumnoDatos_collection[0]->ape_paterno.' '.$alumnoDatos_collection[0]->ape_materno.' '.$alumnoDatos_collection[0]->nombres;
        $fechaNac = $alumnoDatos_collection[0]->perFechaNac;


        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');
        $anioNacimiento = explode("-", $fechaNac);
        $anoHoy = $fechaActual->format('Y');
        // calcular edad (año actual - año nacimiento alumno)
        $edadCalculada = $anoHoy - $anioNacimiento[0];

        if($perSexo == "F"){
            $alumno = "Alumna: $persona";
        }else{
            $alumno = "Alumno: $persona";
        }


        return view('primaria.perfil.edit', [
            "primaria_contenidos_fundamentales" => $primaria_contenidos_fundamentales,
            "calificadores" => $calificadores,
            "gradoAlumno" => $gradoAlumno,
            "grupoAlumno" => $grupoAlumno,
            "ciclo_escolar" => $ciclo_escolar,
            "alumno" => $alumno,
            "edadCalculada" => $edadCalculada,
            "expediente_perfil" => $expediente_perfil,
            "parametro" => $parametro,
            "primaria_expediente_perfiles_contenidos" => $primaria_expediente_perfiles_contenidos
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $primaria_expediente_perfiles_contenidos = Primaria_expediente_perfiles_contenidos::where('primaria_expediente_perfiles_id', $request->perfil_id)->get();

        $perfil_id = $request->perfil_id;
        $contenido_fundamental_id = $request->contenido_fundamental_id;
        $calificador_id = $request->calificador_id;
        $observacion = $request->observacion;


        // campo de observaciones generales de un alumno
        $observacionAlumno = $request->observacionAlumno;
        $usaLentes = $request->usaLentes;

        $primaria_expediente_perfiles = Primaria_expediente_perfiles::where('id', $perfil_id)->first();

        $primaria_expediente_perfiles->update([
            'utiliza_lentes' => $usaLentes,
            'observaciones' => $observacionAlumno
        ]);

        if ($primaria_expediente_perfiles_contenidos->isEmpty()) {


            for ($i = 0; $i < count($contenido_fundamental_id); $i++) {

                $expediente_perfiles_contenido = array();
                $expediente_perfiles_contenido = new Primaria_expediente_perfiles_contenidos();
                $expediente_perfiles_contenido['primaria_expediente_perfiles_id'] = $perfil_id;
                $expediente_perfiles_contenido['primaria_contenidos_fundamentales_id'] = $contenido_fundamental_id[$i];
                $expediente_perfiles_contenido['primaria_contenidos_calificadores_id'] = $calificador_id[$i];
                $expediente_perfiles_contenido['observacion_contenido'] = $observacion[$i];
                $expediente_perfiles_contenido['user_docente_id'] =  auth()->user()->id;


                $expediente_perfiles_contenido->save();
            }

            alert('Escuela Modelo', 'El perfil del alumno se guardo con éxito', 'success')->showConfirmButton();

            echo "<script>window.open('primaria_perfil/imprimir/$primaria_expediente_perfiles->id', '_blank');</script>";
            echo "<script>window.location.href='primaria_perfil/$primaria_expediente_perfiles->id/edit'; </script>";

        }else{


            $id = $request->id;
            $fechaActual = Carbon::now('America/Merida');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            // En windows
            setlocale(LC_TIME, 'spanish');
            $hoy = $fechaActual->format('Y-m-d H:i:s');
            for ($i = 0; $i < count($id); $i++) {

                DB::table('primaria_expediente_perfiles_contenidos')
                ->where('id', $id[$i])
                    ->update([
                        'primaria_expediente_perfiles_id' => $perfil_id,
                        'primaria_contenidos_fundamentales_id' => $contenido_fundamental_id[$i],
                        'primaria_contenidos_calificadores_id' => $calificador_id[$i],
                        'observacion_contenido' => $observacion[$i],
                        'user_docente_id' => auth()->user()->id,
                        'updated_at' => $hoy
                    ]);
            }

            alert('Escuela Modelo', 'El perfil del alumno se actualizo con éxito', 'success')->showConfirmButton();

            echo "<script>window.open('primaria_perfil/imprimir/$primaria_expediente_perfiles->id', '_blank');</script>";
            echo "<script>window.location.href='primaria_perfil/$primaria_expediente_perfiles->id/edit'; </script>";



        }

    }

    public function imprimir($id)
    {
       $primaria_expediente_perfiles_contenidos = Primaria_expediente_perfiles_contenidos::select(
            'primaria_expediente_perfiles_contenidos.id',
            'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id',
            'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id',
            'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id',
            'primaria_contenidos_calificadores.calificador',
            'primaria_expediente_perfiles_contenidos.observacion_contenido',
            'primaria_contenidos_fundamentales.contenido',
            'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id',
            'primaria_expediente_perfiles.curso_id',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'cgt.cgtGradoSemestre',
            'cgt.cgtGrupo',
            'periodos.perAnioPago',
            'cursos.id as curso_id',
            'cursos.curEstado',
            'cursos.curPrimariaFoto',
            'primaria_expediente_perfiles.observaciones as observacionPerfil',
            'primaria_expediente_perfiles.utiliza_lentes',
            'primaria_contenidos_categorias.categoria',
            'programas.progClave',
            'programas.progNombre',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'alumnos.aluClave')
        ->join('primaria_expediente_perfiles', 'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', '=', 'primaria_expediente_perfiles.id')
        ->join('primaria_contenidos_fundamentales', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id', '=', 'primaria_contenidos_fundamentales.id')
        ->leftJoin('primaria_contenidos_calificadores', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '=', 'primaria_contenidos_calificadores.id')
        ->join('cursos', 'primaria_expediente_perfiles.curso_id', '=', 'cursos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
        ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
        ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
        ->join('planes', 'cgt.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', $id)
        ->orderBy('primaria_expediente_perfiles_contenidos.id')
        ->get();

        if ($primaria_expediente_perfiles_contenidos->isEmpty()) {
            alert('Sin coincidencias', 'Aun no se ha capturado datos para este alumno.', 'warning')->showConfirmButton();
            return back()->withInput();
        }

        $alumno = $primaria_expediente_perfiles_contenidos[0]->perApellido1.' '.$primaria_expediente_perfiles_contenidos[0]->perApellido2.' '.$primaria_expediente_perfiles_contenidos[0]->perNombre;
        $grado = $primaria_expediente_perfiles_contenidos[0]->cgtGradoSemestre;
        $grupo = $primaria_expediente_perfiles_contenidos[0]->cgtGrupo;
        $perAnioPago = $primaria_expediente_perfiles_contenidos[0]->perAnioPago;
        $perAnioSiguiente = $primaria_expediente_perfiles_contenidos[0]->perAnioPago + 1;
        $cilo_escolar = $perAnioPago .'-'.$perAnioSiguiente;
        $utiliza_lentes = $primaria_expediente_perfiles_contenidos[0]->utiliza_lentes;
        $estadoCurso = $primaria_expediente_perfiles_contenidos[0]->curEstado;
        $obsGeneral = $primaria_expediente_perfiles_contenidos[0]->observacionPerfil;
        $foto = $primaria_expediente_perfiles_contenidos[0]->curPrimariaFoto;
        $curso_id = $primaria_expediente_perfiles_contenidos[0]->curso_id;
        $programa = $primaria_expediente_perfiles_contenidos[0]->progClave.'-'.$primaria_expediente_perfiles_contenidos[0]->progNombre;
        $ubicacion = $primaria_expediente_perfiles_contenidos[0]->ubiClave.'-'.$primaria_expediente_perfiles_contenidos[0]->ubiNombre;
        $clave_pago = $primaria_expediente_perfiles_contenidos[0]->aluClave;


        // llamada procedure para mostrar promedio
        $resultado_array =  DB::select("call procPrimariaPromedioCurso(" . $curso_id . ")");
        $promedio_collection = collect($resultado_array);
        $promedioSep = $promedio_collection[0]->promedioCursoSEP;


        if($grado == "1"){
            $gradoLetra = "Primer Grado";
        }
        if($grado == "2"){
            $gradoLetra = "Segundo Grado";
        }
        if($grado == "3"){
            $gradoLetra = "Tercer Grado";
        }
        if($grado == "4"){
            $gradoLetra = "Cuarto Grado";
        }
        if($grado == "5"){
            $gradoLetra = "Quinto Grado";
        }
        if($grado == "6"){
            $gradoLetra = "Sexto Grado";
        }


        $parametro_NombreArchivo = "pdf_perfil_alumno";
        $pdf = PDF::loadView('primaria.pdf.perfil_alumnos.' . $parametro_NombreArchivo, [
            'perfiles_contenidos' => $primaria_expediente_perfiles_contenidos,
            'alumno' => $alumno,
            'grado' => $gradoLetra,
            'grupo' => $grupo,
            'cilo_escolar' => $cilo_escolar,
            "utiliza_lentes" => $utiliza_lentes,
            'estadoCurso' => $estadoCurso,
            'obsGeneral' => $obsGeneral,
            'foto' => $foto,
            'perAnioPago' => $perAnioPago,
            'promedioSep' => $promedioSep,
            'programa' => $programa,
            'ubicacion' => $ubicacion,
            'clave_pago' => $clave_pago
        ]);


        $pdf->setPaper('letter', 'landscape');
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


    }
}
