<?php

namespace App\Http\Controllers\Primaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Primaria\Primaria_asistencia;
use App\Http\Models\Primaria\Primaria_inscrito;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaListaDeAsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporte()
    {
        $ubicaciones = Ubicacion::sedes()->get();
        return view('primaria.reportes.lista_de_asistencia.create', [
            'ubicaciones' => $ubicaciones
        ]);
    }

    public function imprimir(Request $request)
    {
        $gpoGrado = $request->gpoGrado;
        $gpoGrupo = $request->gpoGrupo;
        $programa_id = $request->programa_id;
        $plan_id = $request->plan_id;
        $perAnioPago = $request->periodo_id;
        $tipoVista = $request->tipoVista; //variable que define el tipo de vista del pdf

        // llamada procedure 
        // $resultado_array =  DB::select("call procPrimariaListaAlumnosPorMaterias(" . $perAnioPago . "," . $gpoGrado . ",'" . $gpoGrupo . "', ".$programa_id.", ".$plan_id.")");
        // $grupo_collection = collect($resultado_array);

        // if ($grupo_collection->isEmpty()) {
        //     alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
        //     return back()->withInput();
        // }

        // Agrupados
        $agrupados = DB::table('primaria_inscritos')
        ->select(
            'primaria_grupos.gpoGrado',
            DB::raw('count(*) as gpoGrado, primaria_grupos.gpoGrado'),
            'primaria_grupos.gpoClave',
            DB::raw('count(*) as gpoClave, primaria_grupos.gpoClave'),
            'primaria_inscritos.primaria_grupo_id',
            DB::raw('count(*) as primaria_grupo_id, primaria_inscritos.primaria_grupo_id'),
            'primaria_materias.matNombre',
            DB::raw('count(*) as matNombre, primaria_materias.matNombre'),
            'primaria_materias.matClave',
            DB::raw('count(*) as matClave, primaria_materias.matClave'),
            'primaria_grupos.empleado_id_docente',
            DB::raw('count(*) as empleado_id_docente, primaria_grupos.empleado_id_docente'),
            'primaria_empleados.empNombre',
            DB::raw('count(*) as empNombre, primaria_empleados.empNombre'),
            'primaria_empleados.empApellido1',
            DB::raw('count(*) as empApellido1, primaria_empleados.empApellido1'),
            'primaria_empleados.empApellido2',
            DB::raw('count(*) as empApellido2, primaria_empleados.empApellido2'),
            'primaria_empleados.empSexo',
            DB::raw('count(*) as empSexo, primaria_empleados.empSexo'),
            'planes.planClave',
            DB::raw('count(*) as planClave, planes.planClave'),
            'ubicacion.ubiClave',
            DB::raw('count(*) as ubiClave, ubicacion.ubiClave'),
            'ubicacion.ubiNombre',
            DB::raw('count(*) as ubiNombre, ubicacion.ubiNombre'),
            'escuelas.escClave',
            DB::raw('count(*) as escClave, escuelas.escClave'),
            'escuelas.escNombre',
            DB::raw('count(*) as escNombre, escuelas.escNombre'),
            'periodos.perFechaInicial',
            DB::raw('count(*) as perFechaInicial, periodos.perFechaInicial'),
            'periodos.perFechaFinal',
            DB::raw('count(*) as perFechaFinal, periodos.perFechaFinal'),
            'programas.progClave',
            DB::raw('count(*) as progClave, programas.progClave'),
            'programas.progNombre',
            DB::raw('count(*) as progNombre, programas.progNombre')

        )
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
            ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->groupBy('primaria_grupos.gpoGrado')
            ->groupBy('primaria_grupos.gpoClave')
            ->groupBy('primaria_inscritos.primaria_grupo_id')
            ->groupBy('primaria_materias.matNombre')
            ->groupBy('primaria_materias.matClave')
            ->groupBy('primaria_grupos.empleado_id_docente')
            ->groupBy('primaria_empleados.empNombre')
            ->groupBy('primaria_empleados.empApellido1')
            ->groupBy('primaria_empleados.empApellido2')
            ->groupBy('primaria_empleados.empSexo')
            ->groupBy('planes.planClave')
            ->groupBy('ubicacion.ubiClave')
            ->groupBy('escuelas.escClave')
            ->groupBy('escuelas.escNombre')
            ->groupBy('periodos.perFechaInicial')
            ->groupBy('periodos.perFechaFinal')
            ->groupBy('programas.progClave')
            ->groupBy('programas.progNombre')
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('periodos.perAnioPago', $perAnioPago)
            ->where('primaria_grupos.gpoGrado', $gpoGrado)
            ->where('primaria_grupos.gpoClave', $gpoGrupo)
            //->orderBy('primaria_grupos.gpoGrado', 'asc')
            ->orderBy('primaria_materias.matNombre', 'asc')
            ->get();

        if ($agrupados->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }
        $alumnos_grupo =  Primaria_inscrito::select(
            'primaria_inscritos.id',
            'primaria_grupos.id as primaria_grupo_id',
            'primaria_grupos.gpoGrado',
            'primaria_grupos.gpoClave',
            'cursos.id as curso_id',
            'primaria_materias.id as primaria_materia_id',
            'primaria_materias.matClave',
            'primaria_materias.matNombre',
            'primaria_materias.matNombreCorto',
            'periodos.id as periodo_id',
            'periodos.perAnioPago',
            'periodos.perFechaInicial as fecha_inicio',
            'periodos.perFechaFinal as fecha_fin',
            'alumnos.id as alumno_id',
            'alumnos.aluClave as clavePago',
            'personas.id as persona_id',
            'personas.perApellido1 as ape_paterno',
            'personas.perApellido2 as ape_materno',
            'personas.perNombre as nombres',
            'primaria_empleados.id as empleados_id',
            'primaria_empleados.empApellido1',
            'primaria_empleados.empApellido2',
            'primaria_empleados.empNombre',
            'primaria_empleados.empSexo',
            'escuelas.id as escuela_id',
            'escuelas.escClave',
            'escuelas.escNombre',
            'departamentos.id as departamento_id',
            'planes.id as plan_id',
            'planes.planClave',
            'ubicacion.id as ubicacion_id',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre',
            'programas.progClave',
            'programas.progNombre'
        )
            ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
            ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
            ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
            ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('periodos.perAnioPago', $perAnioPago)
            ->where('primaria_grupos.gpoGrado', $gpoGrado)
            ->where('primaria_grupos.gpoClave', $gpoGrupo)
            ->orderBy('personas.perApellido1', 'asc')
            ->orderBy('primaria_materias.matNombre', 'asc')
            ->get();

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        if ($tipoVista == "listaVacia") {

            $parametro_NombreArchivo = "pdf_primaria_lista_de_asistencia_ciclo";
            $pdf = PDF::loadView('primaria.pdf.lista_de_asistencia.' . $parametro_NombreArchivo, [
                "inscritos" => $alumnos_grupo,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "parametro_NombreArchivo" => $parametro_NombreArchivo,
                "agrupados" => $agrupados
            ]);


            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        } else {

            $fechaInicio = $request->fechaInicio;
            $fechaFin = $request->fechaFin;


            // validamos cuantos dias se esta buscando 
            $fecha1 = new DateTime($fechaInicio);
            $fecha2 = new DateTime($fechaFin);
            $diff = $fecha1->diff($fecha2);


            if ($diff->days > 30) {
                alert()->warning('Número de días "' . $diff->days . '"', 'Solo puede consultar en un rango menor o igual a 30 días')->showConfirmButton();
                return back()->withInput();
            }

            $asistencia_fechas = Primaria_asistencia::select(
                'primaria_asistencia.id',
                'primaria_asistencia.estado',
                'primaria_asistencia.fecha_asistencia',
                'primaria_inscritos.id as primaria_inscrito_id',
                'primaria_grupos.id as primaria_grupo_id',
                'cursos.id as curso_id',
                'primaria_materias.id as primaria_materia_id',
                'primaria_materias.matClave',
                'primaria_materias.matNombre',
                'primaria_materias.matNombreCorto',
                'periodos.id as periodo_id',
                'periodos.perAnioPago',
                'periodos.perFechaInicial as fecha_inicio',
                'periodos.perFechaFinal as fecha_fin',
                'alumnos.id as alumno_id',
                'alumnos.aluClave as clavePago'
                // 'personas.id as persona_id',
                // 'personas.perApellido1 as ape_paterno',
                // 'personas.perApellido2 as ape_materno',
                // 'personas.perNombre as nombres',
                // 'escuelas.id as escuela_id',
                // 'escuelas.escClave',
                // 'escuelas.escNombre',
                // 'departamentos.id as departamento_id',
                // 'planes.id as plan_id',
                // 'planes.planClave',
                // 'ubicacion.id as ubicacion_id',
                // 'ubicacion.ubiClave',
                // 'ubicacion.ubiNombre',
                // 'programas.progClave',
                // 'programas.progNombre'
            )
                ->join('primaria_inscritos', 'primaria_asistencia.asistencia_inscrito_id', '=', 'primaria_inscritos.id')
                ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
                ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
                ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
                ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.perAnioPago', $perAnioPago)
                ->where('primaria_grupos.gpoGrado', $gpoGrado)
                ->where('primaria_grupos.gpoClave', $gpoGrupo)
                ->whereBetween('primaria_asistencia.fecha_asistencia', [$fechaInicio, $fechaFin])
                ->orderBy('personas.perApellido1', 'asc')
                ->orderBy('primaria_materias.matNombre', 'asc')
                ->orderBy('primaria_asistencia.fecha_asistencia', 'asc')


                ->get();

            $agruparFechas = $asistencia_fechas->groupBy('fecha_asistencia');
            $agruparMaterias = $asistencia_fechas->groupBy('matNombre');





            $parametro_NombreArchivo = "pdf_primaria_lista_de_asistencia_con_fechas";
            $pdf = PDF::loadView('primaria.pdf.lista_de_asistencia.' . $parametro_NombreArchivo, [
                "inscritos" => $alumnos_grupo,
                "fechaActual" => $fechaActual->format('d/m/Y'),
                "horaActual" => $fechaActual->format('H:i:s'),
                "parametro_NombreArchivo" => $parametro_NombreArchivo,
                "agrupados" => $agrupados,
                "asistencia_fechas" => $asistencia_fechas,
                "agruparFechas" => $agruparFechas,
                "agruparMaterias" => $agruparMaterias,
                "fechaInicio" => $fechaInicio,
                "fechaFin" => $fechaFin
            ]);


            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        }
    }

    public function imprimirListaAsistencia($grupo_id)
    {
        $alumnos_grupo =  Primaria_inscrito::select(
            'primaria_inscritos.id',
            'primaria_inscritos.inscCalificacionSep as septiembre',
            'primaria_inscritos.inscCalificacionOct as octubre',
            'primaria_inscritos.inscCalificacionNov as noviembre',
            'primaria_inscritos.inscCalificacionDic as diciembre',
            'primaria_inscritos.inscCalificacionEne as enero',
            'primaria_inscritos.inscCalificacionFeb as febrero',
            'primaria_inscritos.inscCalificacionMar as marzo',
            'primaria_inscritos.inscCalificacionAbr as abril',
            'primaria_inscritos.inscCalificacionMay as mayo',
            'primaria_inscritos.inscCalificacionJun as junio',
            'primaria_inscritos.inscBimestre1 as bimestre1',
            'primaria_inscritos.inscBimestre2 as bimestre2',
            'primaria_inscritos.inscBimestre3 as bimestre3',
            'primaria_inscritos.inscBimestre4 as bimestre4',
            'primaria_inscritos.inscBimestre5 as bimestre5',
            'primaria_inscritos.inscTrimestre1 as trimestre1',
            'primaria_inscritos.inscTrimestre2 as trimestre2',
            'primaria_inscritos.inscTrimestre3 as trimestre3',
            'primaria_grupos.id as primaria_grupo_id',
            'primaria_grupos.gpoGrado',
            'primaria_grupos.gpoClave',
            'cursos.id as curso_id',
            'primaria_materias.id as primaria_materia_id',
            'primaria_materias.matClave',
            'primaria_materias.matNombre',
            'primaria_materias.matNombreCorto',
            'periodos.id as periodo_id',
            'periodos.perAnioPago',
            'periodos.perFechaInicial as fecha_inicio',
            'periodos.perFechaFinal as fecha_fin',
            'alumnos.id as alumno_id',
            'alumnos.aluClave as clavePago',
            'personas.id as persona_id',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'primaria_empleados.id as empleados_id',
            'primaria_empleados.empApellido1',
            'primaria_empleados.empApellido2',
            'primaria_empleados.empNombre',
            'primaria_empleados.empSexo',
            'escuelas.id as escuela_id',
            'escuelas.escClave',
            'escuelas.escNombre',
            'departamentos.id as departamento_id',
            'planes.id as plan_id',
            'planes.planClave',
            'ubicacion.id as ubicacion_id',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre'
        )
        ->join('primaria_grupos', 'primaria_inscritos.primaria_grupo_id', '=', 'primaria_grupos.id')
        ->join('cursos', 'primaria_inscritos.curso_id', '=', 'cursos.id')
        ->join('primaria_materias', 'primaria_grupos.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('periodos', 'primaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('primaria_empleados', 'primaria_grupos.empleado_id_docente', '=', 'primaria_empleados.id')
        ->join('planes', 'primaria_grupos.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('primaria_inscritos.primaria_grupo_id', $grupo_id)
        ->get();

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');



        $parametro_NombreArchivo = "pdf_primaria_lista_de_asistencia";
        $pdf = PDF::loadView('primaria.pdf.lista_de_asistencia.' . $parametro_NombreArchivo, [
            "inscritos" => $alumnos_grupo,
            "fechaActual" => $fechaActual->format('d/m/Y'),
            "horaActual" => $fechaActual->format('H:i:s'),
            "parametro_NombreArchivo" => $parametro_NombreArchivo,

        ]);


        $pdf->setPaper('letter', 'landscape');
        $pdf->defaultFont = 'Times Sans Serif';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

    public function reporteACD()
    {
        $ubicaciones = Ubicacion::sedes()->get();
        return view('primaria.reportes.lista_de_asistencia.createACD', [
            'ubicaciones' => $ubicaciones
        ]);
    }



    // ajax para recuperar grupos de ACD
    public function getGruposACD(Request $request, $programa_id, $plan_id, $id_periodo)
    {
        if($request->ajax()){


            // llama al procedure de los alumnos a buscar 
            $resultado_array =  DB::select("call procPrimariaClavesGruposACD(".$id_periodo.", ".$programa_id.", ".$plan_id.")");

            $grupos = collect($resultado_array);

            return response()->json($grupos);
        }
    }




    public function imprimirACD(Request $request)
    {
        $gpoGrado = $request->gpoGrado;
        $gpoGrupo = $request->gpoGrupo;
        $programa_id = $request->programa_id;
        $plan_id = $request->plan_id;
        $perAnioPago = $request->periodo_id;


        if($gpoGrado != ""){
            // llamada procedure 
            $resultado_array =  DB::select("call procPrimariaListaAlumnosACD(" . $perAnioPago . "," . $gpoGrado . ",'" . $gpoGrupo . "', ".$programa_id.", ".$plan_id.")");
            $grupo_collection = collect($resultado_array);

            if ($grupo_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay grupos capturados con la información proporcionada. Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }
            $parametro_NombreArchivo = "pdf_primaria_lista_de_asistencia_ACD";
            $pdf = PDF::loadView('reportes.pdf.primaria.lista_de_asistencia.' . $parametro_NombreArchivo, [
                "inscritos" => $grupo_collection,
            
                "parametro_NombreArchivo" => $parametro_NombreArchivo,
                
            ]);


            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        }else{
            $resultado_array =  DB::select("call procPrimariaListaAlumnosACDCualquierGrado(" . $perAnioPago . ",'" . $gpoGrupo . "', ".$programa_id.", ".$plan_id.")");
            $grupo_collection = collect($resultado_array);

  
         
            $parametro_NombreArchivo = "pdf_primaria_lista_de_asistencia_ACD_sin_grado";
            $pdf = PDF::loadView('primaria.pdf.lista_de_asistencia.' . $parametro_NombreArchivo, [
                "inscritos" => $grupo_collection,            
                "parametro_NombreArchivo" => $parametro_NombreArchivo,
                "gpoGrupo" => $gpoGrupo
            ]);

            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        }


    }
}
