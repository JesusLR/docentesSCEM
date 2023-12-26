<?php

namespace App\Http\Controllers\Primaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Primaria\Primaria_expediente_perfiles_contenidos;
use App\Http\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use PDF;

class PrimariaPerfilesController extends Controller
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
        $ubicaciones = Ubicacion::sedes()->get();

        return view('primaria.reportes.perfiles_alumnos.create', [
            'ubicaciones' => $ubicaciones
        ]);
    }

    public function imprimir(Request $request)
    {
        $programa_id = $request->programa_id;
        $plan_id = $request->plan_id;
        $periodo_id = $request->periodo_id;
        $gpoGrado = $request->gpoGrado;
        $gpoClave = $request->gpoClave;
        $aluClave = $request->aluClave;

        if ($aluClave == "") {
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
                'alumnos.aluClave',
                'primaria_contenidos_categorias.categoria',
                'programas.progClave',
                'programas.progNombre',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'alumnos.aluClave'
            )
            ->join('primaria_expediente_perfiles', 'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', '=', 'primaria_expediente_perfiles.id')
            ->join('primaria_contenidos_fundamentales', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id', '=', 'primaria_contenidos_fundamentales.id')
            ->leftJoin('primaria_contenidos_calificadores', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '=', 'primaria_contenidos_calificadores.id')
            ->join('cursos', 'primaria_expediente_perfiles.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.id', $periodo_id)
                ->where('cgt.cgtGradoSemestre', $gpoGrado)
                ->where('cgt.cgtGrupo', $gpoClave)
                ->orderBy('primaria_expediente_perfiles_contenidos.id')
                ->get();


                if ($primaria_expediente_perfiles_contenidos->isEmpty()) {
                    alert('Sin coincidencias', 'Aun no se ha capturado datos para este grupo.', 'warning')->showConfirmButton();
                    return back()->withInput();
                }


            $alumnos = DB::table('primaria_expediente_perfiles_contenidos')
            ->select(
                'alumnos.aluClave',
                DB::raw('count(*) as aluClave, alumnos.aluClave'),
                'personas.perApellido1',
                DB::raw('count(*) as perApellido1, personas.perApellido1'),
                'personas.perApellido2',
                DB::raw('count(*) as perApellido2, personas.perApellido2'),
                'personas.perNombre',
                DB::raw('count(*) as perNombre, personas.perNombre'),
                'alumnos.aluClave',
                DB::raw('count(*) as aluClave, alumnos.aluClave'),
                'alumnos.id',
                DB::raw('count(*) as id, alumnos.id'),
                'cursos.curPrimariaFoto',
                DB::raw('count(*) as curPrimariaFoto, cursos.curPrimariaFoto'),
                'cursos.curEstado',
                DB::raw('count(*) as curEstado, cursos.curEstado'),
                'primaria_expediente_perfiles.utiliza_lentes',
                DB::raw('count(*) as utiliza_lentes, primaria_expediente_perfiles.utiliza_lentes'),
                'primaria_expediente_perfiles.observaciones',
                DB::raw('count(*) as observaciones, primaria_expediente_perfiles.observaciones'),
                'primaria_expediente_perfiles.curso_id',
                DB::raw('count(*) as curso_id, primaria_expediente_perfiles.curso_id')
            )
                ->join('primaria_expediente_perfiles', 'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', '=', 'primaria_expediente_perfiles.id')
                ->join('primaria_contenidos_fundamentales', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id', '=', 'primaria_contenidos_fundamentales.id')
                ->leftJoin('primaria_contenidos_calificadores', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '=', 'primaria_contenidos_calificadores.id')
                ->join('cursos', 'primaria_expediente_perfiles.curso_id', '=', 'cursos.id')
                ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
                ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
                ->join('planes', 'cgt.plan_id', '=', 'planes.id')
                ->join('programas', 'planes.programa_id', '=', 'programas.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
                ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
                ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
                ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.id', $periodo_id)
                ->where('cgt.cgtGradoSemestre', $gpoGrado)
                ->where('cgt.cgtGrupo', $gpoClave)
                ->groupBy('alumnos.aluClave')
                ->groupBy('personas.perApellido1')
                ->groupBy('personas.perApellido2')
                ->groupBy('personas.perNombre')
                ->groupBy('alumnos.aluClave')
                ->groupBy('alumnos.id')
                ->groupBy('cursos.curPrimariaFoto')
                ->groupBy('cursos.curEstado')
                ->groupBy('primaria_expediente_perfiles.utiliza_lentes')
                ->groupBy('primaria_expediente_perfiles.observaciones')
                ->groupBy('primaria_expediente_perfiles.curso_id')
                ->get();


            $grado = $primaria_expediente_perfiles_contenidos[0]->cgtGradoSemestre;
            $grupo = $primaria_expediente_perfiles_contenidos[0]->cgtGrupo;
            $perAnioPago = $primaria_expediente_perfiles_contenidos[0]->perAnioPago;
            $perAnioSiguiente = $primaria_expediente_perfiles_contenidos[0]->perAnioPago + 1;
            $cilo_escolar = $perAnioPago . '-' . $perAnioSiguiente;
            $perAnioPago = $primaria_expediente_perfiles_contenidos[0]->perAnioPago;
            $programa = $primaria_expediente_perfiles_contenidos[0]->progClave.'-'.$primaria_expediente_perfiles_contenidos[0]->progNombre;
            $ubicacion = $primaria_expediente_perfiles_contenidos[0]->ubiClave.'-'.$primaria_expediente_perfiles_contenidos[0]->ubiNombre;


            if ($grado == "1") {
                $gradoLetra = "Primer Grado";
            }
            if ($grado == "2") {
                $gradoLetra = "Segundo Grado";
            }
            if ($grado == "3") {
                $gradoLetra = "Tercer Grado";
            }
            if ($grado == "4") {
                $gradoLetra = "Cuarto Grado";
            }
            if ($grado == "5") {
                $gradoLetra = "Quinto Grado";
            }
            if ($grado == "6") {
                $gradoLetra = "Sexto Grado";
            }


            $parametro_NombreArchivo = "pdf_primaria_perfil_alumnos_general";
            $pdf = PDF::loadView('reportes.pdf.primaria.perfil_alumnos.' . $parametro_NombreArchivo, [
                'perfiles_contenidos' => $primaria_expediente_perfiles_contenidos,
                "grupo" => $grupo,
                "alumnos" => $alumnos,
                "cilo_escolar" => $cilo_escolar,
                "grado" => $gradoLetra,
                "perAnioPago" => $perAnioPago,
                "grado" => $grado,
                "programa" => $programa,
                "ubicacion" => $ubicacion
            ]);


            $pdf->setPaper('letter', 'landscape');
            $pdf->defaultFont = 'Times Sans Serif';

            return $pdf->stream($parametro_NombreArchivo . '.pdf');
            return $pdf->download($parametro_NombreArchivo  . '.pdf');
        } else {


            // cuando es un solo alumno 
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
                'alumnos.aluClave',
                'primaria_contenidos_categorias.categoria',
                'programas.progClave',
                'programas.progNombre',
                'ubicacion.ubiClave',
                'ubicacion.ubiNombre',
                'alumnos.aluClave'
            )
            ->join('primaria_expediente_perfiles', 'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', '=', 'primaria_expediente_perfiles.id')
            ->join('primaria_contenidos_fundamentales', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id', '=', 'primaria_contenidos_fundamentales.id')
            ->leftJoin('primaria_contenidos_calificadores', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '=', 'primaria_contenidos_calificadores.id')
            ->join('cursos', 'primaria_expediente_perfiles.curso_id', '=', 'cursos.id')
            ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
            ->join('cgt', 'cursos.cgt_id', '=', 'cgt.id')
            ->join('planes', 'cgt.plan_id', '=', 'planes.id')
            ->join('programas', 'planes.programa_id', '=', 'programas.id')
            ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
            ->join('departamentos', 'periodos.departamento_id', '=', 'departamentos.id')
            ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
            ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
            ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
            ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
            ->where('programas.id', $programa_id)
                ->where('planes.id', $plan_id)
                ->where('periodos.id', $periodo_id)
                ->where('cgt.cgtGradoSemestre', $gpoGrado)
                ->where('cgt.cgtGrupo', $gpoClave)
                ->where('alumnos.aluClave', $aluClave)
                ->orderBy('primaria_expediente_perfiles_contenidos.id')
                ->get();

                if ($primaria_expediente_perfiles_contenidos->isEmpty()) {
                    alert('Sin coincidencias', 'Aun no se ha capturado datos para este alumno.', 'warning')->showConfirmButton();
                    return back()->withInput();
                }

            $alumno = $primaria_expediente_perfiles_contenidos[0]->perApellido1 . ' ' . $primaria_expediente_perfiles_contenidos[0]->perApellido2 . ' ' . $primaria_expediente_perfiles_contenidos[0]->perNombre;
            $grado = $primaria_expediente_perfiles_contenidos[0]->cgtGradoSemestre;
            $grupo = $primaria_expediente_perfiles_contenidos[0]->cgtGrupo;
            $perAnioPago = $primaria_expediente_perfiles_contenidos[0]->perAnioPago;
            $perAnioSiguiente = $primaria_expediente_perfiles_contenidos[0]->perAnioPago + 1;
            $cilo_escolar = $perAnioPago . '-' . $perAnioSiguiente;
            $utiliza_lentes = $primaria_expediente_perfiles_contenidos[0]->utiliza_lentes;
            $estadoCurso = $primaria_expediente_perfiles_contenidos[0]->curEstado;
            $obsGeneral = $primaria_expediente_perfiles_contenidos[0]->observacionPerfil;
            $foto = $primaria_expediente_perfiles_contenidos[0]->curPrimariaFoto;
            $curos_id = $primaria_expediente_perfiles_contenidos[0]->curso_id;
            $programa = $primaria_expediente_perfiles_contenidos[0]->progClave.'-'.$primaria_expediente_perfiles_contenidos[0]->progNombre;
            $ubicacion = $primaria_expediente_perfiles_contenidos[0]->ubiClave.'-'.$primaria_expediente_perfiles_contenidos[0]->ubiNombre;
            $clave_pago = $primaria_expediente_perfiles_contenidos[0]->aluClave;


            // llamada procedure para mostrar promedio
            $resultado_array =  DB::select("call procPrimariaPromedioCurso(" . $curos_id . ")");
            $promedio_collection = collect($resultado_array);
            $promedioSep = $promedio_collection[0]->promedioCursoSEP;

            if ($grado == "1") {
                $gradoLetra = "Primer Grado";
            }
            if ($grado == "2") {
                $gradoLetra = "Segundo Grado";
            }
            if ($grado == "3") {
                $gradoLetra = "Tercer Grado";
            }
            if ($grado == "4") {
                $gradoLetra = "Cuarto Grado";
            }
            if ($grado == "5") {
                $gradoLetra = "Quinto Grado";
            }
            if ($grado == "6") {
                $gradoLetra = "Sexto Grado";
            }


            $parametro_NombreArchivo = "pdf_perfil_alumno";
            $pdf = PDF::loadView('reportes.pdf.primaria.perfil_alumnos.' . $parametro_NombreArchivo, [
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
    }
}
