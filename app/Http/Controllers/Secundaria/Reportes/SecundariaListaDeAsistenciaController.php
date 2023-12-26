<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Secundaria\Secundaria_asistencia;
use App\Http\Models\Secundaria\Secundaria_inscritos;
use App\Http\Models\Ubicacion;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use PDF;

class SecundariaListaDeAsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporte()
    {
        $ubicaciones = Ubicacion::sedes()->get();
        return view('secundaria.reportes.lista_de_asistencia.create', [
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
        $agrupados = DB::table('secundaria_inscritos')
        ->select(
            'secundaria_grupos.gpoGrado',
            DB::raw('count(*) as gpoGrado, secundaria_grupos.gpoGrado'),
            'secundaria_grupos.gpoClave',
            DB::raw('count(*) as gpoClave, secundaria_grupos.gpoClave'),
            'secundaria_inscritos.grupo_id',
            DB::raw('count(*) as grupo_id, secundaria_inscritos.grupo_id'),
            'secundaria_materias.matNombre',
            DB::raw('count(*) as matNombre, secundaria_materias.matNombre'),
            'secundaria_materias.matClave',
            DB::raw('count(*) as matClave, secundaria_materias.matClave'),
            'secundaria_grupos.empleado_id_docente',
            DB::raw('count(*) as empleado_id_docente, secundaria_grupos.empleado_id_docente'),
            'secundaria_empleados.empNombre',
            DB::raw('count(*) as empNombre, secundaria_empleados.empNombre'),
            'secundaria_empleados.empApellido1',
            DB::raw('count(*) as empApellido1, secundaria_empleados.empApellido1'),
            'secundaria_empleados.empApellido2',
            DB::raw('count(*) as empApellido2, secundaria_empleados.empApellido2'),
            'secundaria_empleados.empSexo',
            DB::raw('count(*) as empSexo, secundaria_empleados.empSexo'),
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
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
            ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
            ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->groupBy('secundaria_grupos.gpoGrado')
            ->groupBy('secundaria_grupos.gpoClave')
            ->groupBy('secundaria_inscritos.grupo_id')
            ->groupBy('secundaria_materias.matNombre')
            ->groupBy('secundaria_materias.matClave')
            ->groupBy('secundaria_grupos.empleado_id_docente')
            ->groupBy('secundaria_empleados.empNombre')
            ->groupBy('secundaria_empleados.empApellido1')
            ->groupBy('secundaria_empleados.empApellido2')
            ->groupBy('secundaria_empleados.empSexo')
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
            ->where('secundaria_grupos.gpoGrado', $gpoGrado)
            ->where('secundaria_grupos.gpoClave', $gpoGrupo)
            //->orderBy('secundaria_grupos.gpoGrado', 'asc')
            ->orderBy('secundaria_materias.matNombre', 'asc')
            ->get();

        if ($agrupados->isEmpty()) {
            alert()->warning('Sin coincidencias', 'No hay calificaciones capturadas para este grupo. Favor de verificar.')->showConfirmButton();
            return back()->withInput();
        }
        $alumnos_grupo =  Secundaria_inscritos::select(
            'secundaria_inscritos.id',
            'secundaria_grupos.id as secundaria_grupo_id',
            'secundaria_grupos.gpoGrado',
            'secundaria_grupos.gpoClave',
            'cursos.id as curso_id',
            'secundaria_materias.id as secundaria_materia_id',
            'secundaria_materias.matClave',
            'secundaria_materias.matNombre',
            'secundaria_materias.matNombreCorto',
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
            'secundaria_empleados.id as empleados_id',
            'secundaria_empleados.empApellido1',
            'secundaria_empleados.empApellido2',
            'secundaria_empleados.empNombre',
            'secundaria_empleados.empSexo',
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
            ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
            ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
            ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
            ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
            ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('programas.id', $programa_id)
            ->where('planes.id', $plan_id)
            ->where('periodos.perAnioPago', $perAnioPago)
            ->where('secundaria_grupos.gpoGrado', $gpoGrado)
            ->where('secundaria_grupos.gpoClave', $gpoGrupo)
            ->orderBy('personas.perApellido1', 'asc')
            ->orderBy('secundaria_materias.matNombre', 'asc')
            ->get();

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        if ($tipoVista == "listaVacia") {

            $parametro_NombreArchivo = "pdf_secundaria_lista_de_asistencia_ciclo";
            $pdf = PDF::loadView('reportes.pdf.secundaria.lista_de_asistencia.' . $parametro_NombreArchivo, [
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

            $asistencia_fechas = Secundaria_asistencia::select(
                'secundaria_asistencia.id',
                'secundaria_asistencia.estado',
                'secundaria_asistencia.fecha_asistencia',
                'secundaria_inscritos.id as secundaria_inscrito_id',
                'secundaria_grupos.id as secundaria_grupo_id',
                'cursos.id as curso_id',
                'secundaria_materias.id as secundaria_materia_id',
                'secundaria_materias.matClave',
                'secundaria_materias.matNombre',
                'secundaria_materias.matNombreCorto',
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
                ->join('secundaria_inscritos', 'secundaria_asistencia.asistencia_inscrito_id', '=', 'secundaria_inscritos.id')
                ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
                ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
                ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
                ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
                ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
                ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
                ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.perAnioPago', $perAnioPago)
                ->where('secundaria_grupos.gpoGrado', $gpoGrado)
                ->where('secundaria_grupos.gpoClave', $gpoGrupo)
                ->whereBetween('secundaria_asistencia.fecha_asistencia', [$fechaInicio, $fechaFin])
                ->orderBy('personas.perApellido1', 'asc')
                ->orderBy('secundaria_materias.matNombre', 'asc')
                ->orderBy('secundaria_asistencia.fecha_asistencia', 'asc')


                ->get();

            $agruparFechas = $asistencia_fechas->groupBy('fecha_asistencia');
            $agruparMaterias = $asistencia_fechas->groupBy('matNombre');





            $parametro_NombreArchivo = "pdf_secundaria_lista_de_asistencia_con_fechas";
            $pdf = PDF::loadView('reportes.pdf.secundaria.lista_de_asistencia.' . $parametro_NombreArchivo, [
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
        $alumnos_grupo =  Secundaria_inscritos::select(
            'secundaria_inscritos.id',
            'secundaria_inscritos.inscCalificacionSep as septiembre',
            'secundaria_inscritos.inscCalificacionOct as octubre',
            'secundaria_inscritos.inscCalificacionNov as noviembre',
            'secundaria_inscritos.inscCalificacionDic as diciembre',
            'secundaria_inscritos.inscCalificacionEne as enero',
            'secundaria_inscritos.inscCalificacionFeb as febrero',
            'secundaria_inscritos.inscCalificacionMar as marzo',
            'secundaria_inscritos.inscCalificacionAbr as abril',
            'secundaria_inscritos.inscCalificacionMay as mayo',
            'secundaria_inscritos.inscCalificacionJun as junio',
            'secundaria_inscritos.inscPromedioBimestre1 as bimestre1',
            'secundaria_inscritos.inscPromedioBimestre2 as bimestre2',
            'secundaria_inscritos.inscPromedioBimestre3 as bimestre3',
            'secundaria_inscritos.inscPromedioBimestre4 as bimestre4',
            'secundaria_inscritos.inscPromedioBimestre5 as bimestre5',
            'secundaria_inscritos.inscTrimestre1 as trimestre1',
            'secundaria_inscritos.inscTrimestre2 as trimestre2',
            'secundaria_inscritos.inscTrimestre3 as trimestre3',
            'secundaria_grupos.id as secundaria_grupo_id',
            'secundaria_grupos.gpoGrado',
            'secundaria_grupos.gpoClave',
            'cursos.id as curso_id',
            'secundaria_materias.id as secundaria_materia_id',
            'secundaria_materias.matClave',
            'secundaria_materias.matNombre',
            'secundaria_materias.matNombreCorto',
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
            'secundaria_empleados.id as empleados_id',
            'secundaria_empleados.empApellido1',
            'secundaria_empleados.empApellido2',
            'secundaria_empleados.empNombre',
            'secundaria_empleados.empSexo',
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
        ->join('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
        ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
        ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
        ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('secundaria_empleados', 'secundaria_grupos.empleado_id_docente', '=', 'secundaria_empleados.id')
        ->join('planes', 'secundaria_grupos.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('secundaria_inscritos.grupo_id', $grupo_id)
        ->get();

        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');



        $parametro_NombreArchivo = "pdf_secundaria_lista_de_asistencia";
        $pdf = PDF::loadView('secundaria.pdf.lista_de_asistencia.' . $parametro_NombreArchivo, [
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
        return view('secundaria.reportes.lista_de_asistencia.createACD', [
            'ubicaciones' => $ubicaciones
        ]);
    }



    // ajax para recuperar grupos de ACD
    public function getGruposACD(Request $request, $programa_id, $plan_id, $id_periodo)
    {
        if($request->ajax()){


            // llama al procedure de los alumnos a buscar 
            $resultado_array =  DB::select("call procSecundariaClavesGruposACD(".$id_periodo.", ".$programa_id.", ".$plan_id.")");

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
            $resultado_array =  DB::select("call procSecundariaListaAlumnosACD(" . $perAnioPago . "," . $gpoGrado . ",'" . $gpoGrupo . "', ".$programa_id.", ".$plan_id.")");
            $grupo_collection = collect($resultado_array);

            if ($grupo_collection->isEmpty()) {
                alert()->warning('Sin coincidencias', 'No hay grupos capturados con la información proporcionada. Favor de verificar.')->showConfirmButton();
                return back()->withInput();
            }
            $parametro_NombreArchivo = "pdf_secundaria_lista_de_asistencia_ACD";
            $pdf = PDF::loadView('reportes.pdf.secundaria.lista_de_asistencia.' . $parametro_NombreArchivo, [
                "inscritos" => $grupo_collection,
            
                "parametro_NombreArchivo" => $parametro_NombreArchivo,
                
            ]);


            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        }else{
            $resultado_array =  DB::select("call procSecundariaListaAlumnosACDCualquierGrado(" . $perAnioPago . ",'" . $gpoGrupo . "', ".$programa_id.", ".$plan_id.")");
            $grupo_collection = collect($resultado_array);

  
         
            $parametro_NombreArchivo = "pdf_secundaria_lista_de_asistencia_ACD_sin_grado";
            $pdf = PDF::loadView('secundaria.pdf.lista_de_asistencia.' . $parametro_NombreArchivo, [
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
