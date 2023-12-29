<?php

namespace App\Http\Controllers\Secundaria\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Primaria\Primaria_inscrito;
use App\Models\Secundaria\Secundaria_inscritos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

class SecundariaConstanciasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function imprimirCartaConducta($id_curso)
    {
        // query de seleccion de alumno 
        $curso_alumno = Curso::select(
            "cursos.id",
            "alumnos.id as alumno_id",
            "alumnos.aluClave",
            "personas.id as persona_id",
            "personas.perNombre",
            "personas.perApellido1",
            "personas.perApellido2",
            "personas.perSexo",
            "cgt.id as cgt_id",
            "cgt.cgtGradoSemestre",
            "cgt.cgtGrupo",
            "periodos.id as periodo_id",
            "periodos.perAnioPago"
        )
            ->join("alumnos", "cursos.alumno_id", "=", "alumnos.id")
            ->join("personas", "alumnos.persona_id", "=", "personas.id")
            ->join("cgt", "cursos.cgt_id", "=", "cgt.id")
            ->join("periodos", "cursos.periodo_id", "=", "periodos.id")
            ->where("cursos.id", $id_curso)
            ->first();


        $parametro_genero_alumno = "";
        $parametro_alumno = $curso_alumno->perApellido1 . ' ' . $curso_alumno->perApellido2 . ' ' . $curso_alumno->perNombre;
        $parametro_grado = $curso_alumno->cgtGradoSemestre;
        $parametro_periodo_inicio = $curso_alumno->perAnioPago;
        $parametro_periodo_fin = 1 + (int)$curso_alumno->perAnioPago;
        $periodo = $parametro_periodo_inicio . '-' . $parametro_periodo_fin;


        // buscar el grupo al que el alumno pertenece 
        $resultado_array =  DB::select("call procSecundariaObtieneGrupoCurso(" . $id_curso . ")");       

        if(empty($resultado_array)){
            alert()->warning('Sin coincidencias', 'El alumno no cuenta con grupo asignado.')->showConfirmButton();
            return back()->withInput();
        }
        $resultado_grupo = collect($resultado_array);
        $parametro_grupo = $resultado_grupo[0]->gpoClave;


        // valida el genero
        if ($curso_alumno->perSexo == "F") {
            $parametro_genero_alumno = "Que la niña ";
            $es = "alumna";
        } else {
            $parametro_genero_alumno = "Que el niño";
            $es = "alumno";
        }

        // valida que grado es para escribir lo que corresponda 
        $gradoEnLetras = "";
        if ($parametro_grado == 1) {
            $gradoEnLetras = "PRIMER GRADO";
        }
        if ($parametro_grado == 2) {
            $gradoEnLetras = "SEGUNDO GRADO";
        }
        if ($parametro_grado == 3) {
            $gradoEnLetras = "TERCER GRADO";
        }
        if ($parametro_grado == 4) {
            $gradoEnLetras = "CUARTO GRADO";
        }
        if ($parametro_grado == 5) {
            $gradoEnLetras = "QUINTO GRADO";
        }
        if ($parametro_grado == 6) {
            $gradoEnLetras = "SEXTO GRADO";
        }

        // obtener fecha del sistema 
        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $fechaDia = $fechaActual->format('d');
        $fechaMes = $fechaActual->format('m');
        $fechaAnio = $fechaActual->format('Y');


        $mesLetras = "";
        if ($fechaMes == "01") {
            $mesLetras = "ENERO";
        }
        if ($fechaMes == "02") {
            $mesLetras = "FEBRERO";
        }
        if ($fechaMes == "03") {
            $mesLetras = "MARZO";
        }
        if ($fechaMes == "04") {
            $mesLetras = "ABRIL";
        }
        if ($fechaMes == "05") {
            $mesLetras = "MAYO";
        }
        if ($fechaMes == "06") {
            $mesLetras = "JUNIO";
        }
        if ($fechaMes == "07") {
            $mesLetras = "JULIO";
        }
        if ($fechaMes == "08") {
            $mesLetras = "AGOSTO";
        }
        if ($fechaMes == "09") {
            $mesLetras = "SEPTIEMBRE";
        }
        if ($fechaMes == "10") {
            $mesLetras = "OCTUBRE";
        }
        if ($fechaMes == "11") {
            $mesLetras = "NOVIEMBRE";
        }
        if ($fechaMes == "12") {
            $mesLetras = "DICIEMBRE";
        }


        // fecha que se mostrara en PDF 
        $fechahoy = 'MÉRIDA, YUC., ' . $fechaDia . ' DE ' . $mesLetras . ' DE ' . $fechaAnio . '.';

        $parametro_NombreArchivo = "pdf_secundaria_carta_conducta";
        $pdf = PDF::loadView('reportes.pdf.secundaria.constancias.' . $parametro_NombreArchivo, [
            "genero" => $parametro_genero_alumno,
            "alumno" => $parametro_alumno,
            "grado" => $gradoEnLetras,
            "grupo" => $parametro_grupo,
            "fechaHoy" => $fechahoy,
            "periodo" => $periodo,
            "es" => $es
        ]);

        $pdf->defaultFont = 'Calibri';

        return $pdf->stream($parametro_NombreArchivo.'_'.$parametro_alumno .'_'.$parametro_grado.$parametro_grupo.'_'.$periodo. '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }


    public function imprimirConstanciaEstudio($id_curso)
    {

        // query de seleccion de alumno 
        $curso_alumno = Curso::select(
            "cursos.id",
            "alumnos.id as alumno_id",
            "alumnos.aluClave",
            "alumnos.aluMatricula",
            "personas.id as persona_id",
            "personas.perNombre",
            "personas.perApellido1",
            "personas.perApellido2",
            "personas.perSexo",
            "cgt.id as cgt_id",
            "cgt.cgtGradoSemestre",
            "cgt.cgtGrupo",
            "periodos.id as periodo_id",
            "periodos.perAnioPago"
        )
            ->join("alumnos", "cursos.alumno_id", "=", "alumnos.id")
            ->join("personas", "alumnos.persona_id", "=", "personas.id")
            ->join("cgt", "cursos.cgt_id", "=", "cgt.id")
            ->join("periodos", "cursos.periodo_id", "=", "periodos.id")
            ->where("cursos.id", $id_curso)
            ->first();


        $parametro_genero_alumno = "";
        $parametro_consideracion = "";
        $parametro_alumno = $curso_alumno->perApellido1 . ' ' . $curso_alumno->perApellido2 . ' ' . $curso_alumno->perNombre;
        $parametro_grado = $curso_alumno->cgtGradoSemestre;
        $parametro_periodo_inicio = $curso_alumno->perAnioPago;
        $parametro_periodo_fin = 1 + (int)$curso_alumno->perAnioPago;
        $periodo = $parametro_periodo_inicio . '-' . $parametro_periodo_fin;
        $parametro_matricula = $curso_alumno->aluMatricula;
        $parametro_clave = $curso_alumno->aluClave;

        // buscar el grupo al que el alumno pertenece 
        $resultado_array =  DB::select("call procSecundariaObtieneGrupoCurso(" . $id_curso . ")");       

        if(empty($resultado_array)){
            alert()->warning('Sin coincidencias', 'El alumno no cuenta con grupo asignado.')->showConfirmButton();
            return back()->withInput();
        }
        $resultado_grupo = collect($resultado_array);
        $parametro_grupo = $resultado_grupo[0]->gpoClave;


        // valida el genero
        if ($curso_alumno->perSexo == "F") {
            $parametro_genero_alumno = "Que la niña ";
            $parametro_consideracion = "está considerada como alumna ";
        } else {
            $parametro_genero_alumno = "Que el niño";
            $parametro_consideracion = "es alumno ";
        }

        // valida que grado es para escribir lo que corresponda 
        $gradoEnLetras = "";
        if ($parametro_grado == 1) {
            $gradoEnLetras = "PRIMER GRADO";
        }
        if ($parametro_grado == 2) {
            $gradoEnLetras = "SEGUNDO GRADO";
        }
        if ($parametro_grado == 3) {
            $gradoEnLetras = "TERCER GRADO";
        }
        if ($parametro_grado == 4) {
            $gradoEnLetras = "CUARTO GRADO";
        }
        if ($parametro_grado == 5) {
            $gradoEnLetras = "QUINTO GRADO";
        }
        if ($parametro_grado == 6) {
            $gradoEnLetras = "SEXTO GRADO";
        }

        // obtener fecha del sistema 
        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $fechaDia = $fechaActual->format('d');
        $fechaMes = $fechaActual->format('m');
        $fechaAnio = $fechaActual->format('Y');


        $mesLetras = "";
        if ($fechaMes == "01") {
            $mesLetras = "ENERO";
        }
        if ($fechaMes == "02") {
            $mesLetras = "FEBRERO";
        }
        if ($fechaMes == "03") {
            $mesLetras = "MARZO";
        }
        if ($fechaMes == "04") {
            $mesLetras = "ABRIL";
        }
        if ($fechaMes == "05") {
            $mesLetras = "MAYO";
        }
        if ($fechaMes == "06") {
            $mesLetras = "JUNIO";
        }
        if ($fechaMes == "07") {
            $mesLetras = "JULIO";
        }
        if ($fechaMes == "08") {
            $mesLetras = "AGOSTO";
        }
        if ($fechaMes == "09") {
            $mesLetras = "SEPTIEMBRE";
        }
        if ($fechaMes == "10") {
            $mesLetras = "OCTUBRE";
        }
        if ($fechaMes == "11") {
            $mesLetras = "NOVIEMBRE";
        }
        if ($fechaMes == "12") {
            $mesLetras = "DICIEMBRE";
        }


        // fecha que se mostrara en PDF 
        $fechahoy = 'MÉRIDA, YUC., A ' . $fechaDia . ' DE ' . $mesLetras . ' DE ' . $fechaAnio . '.';

        $parametro_NombreArchivo = "pdf_secundaria_constancia_estudios";
        $pdf = PDF::loadView('reportes.pdf.secundaria.constancias.' . $parametro_NombreArchivo, [
            "genero" => $parametro_genero_alumno,
            "alumno" => $parametro_alumno,     
            "grado" => $gradoEnLetras,
            "grupo" => $parametro_grupo,
            "fechaHoy" => $fechahoy,
            "periodo" => $periodo,
            "matricula" => $parametro_matricula,
            "clave" => $parametro_clave,
            "parametro_consideracion" => $parametro_consideracion     
        ]);

        $pdf->defaultFont = 'Calibri';

        return $pdf->stream($parametro_NombreArchivo.'_'.$parametro_alumno .'_'.$parametro_grado.$parametro_grupo.'_'.$periodo. '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }


    public function imprimirConstanciaNoAdeudo($id_curso)
    {
        
        // query de seleccion de alumno 
        $curso_alumno = Curso::select(
            "cursos.id",
            "alumnos.id as alumno_id",
            "alumnos.aluClave",
            "alumnos.aluMatricula",
            "personas.id as persona_id",
            "personas.perNombre",
            "personas.perApellido1",
            "personas.perApellido2",
            "personas.perSexo",
            "cgt.id as cgt_id",
            "cgt.cgtGradoSemestre",
            "cgt.cgtGrupo",
            "periodos.id as periodo_id",
            "periodos.perAnioPago"
        )
            ->join("alumnos", "cursos.alumno_id", "=", "alumnos.id")
            ->join("personas", "alumnos.persona_id", "=", "personas.id")
            ->join("cgt", "cursos.cgt_id", "=", "cgt.id")
            ->join("periodos", "cursos.periodo_id", "=", "periodos.id")
            ->where("cursos.id", $id_curso)
            ->first();


        $parametro_genero_alumno = "";
        $parametro_consideracion = "";
        $parametro_alumno = $curso_alumno->perApellido1 . ' ' . $curso_alumno->perApellido2 . ' ' . $curso_alumno->perNombre;
        $parametro_grado = $curso_alumno->cgtGradoSemestre;
        $parametro_periodo_inicio = $curso_alumno->perAnioPago;
        $parametro_periodo_fin = 1 + (int)$curso_alumno->perAnioPago;
        $periodo = $parametro_periodo_inicio . '-' . $parametro_periodo_fin;
        $parametro_matricula = $curso_alumno->aluMatricula;
        $parametro_clave = $curso_alumno->aluClave;


        // buscar el grupo al que el alumno pertenece 
        $resultado_array =  DB::select("call procSecundariaObtieneGrupoCurso(" . $id_curso . ")");       

        if(empty($resultado_array)){
            alert()->warning('Sin coincidencias', 'El alumno no cuenta con grupo asignado.')->showConfirmButton();
            return back()->withInput();
        }
        $resultado_grupo = collect($resultado_array);
        $parametro_grupo = $resultado_grupo[0]->gpoClave;

        // valida el genero
        if ($curso_alumno->perSexo == "F") {
            $parametro_genero_alumno = "Que la niña ";
            $parametro_consideracion = "fue alumna  ";
        } else {
            $parametro_genero_alumno = "Que el niño";
            $parametro_consideracion = "fue alumno  ";
        }

        // valida que grado es para escribir lo que corresponda 
        $gradoEnLetras = "";
        if ($parametro_grado == 1) {
            $gradoEnLetras = "PRIMER GRADO";
        }
        if ($parametro_grado == 2) {
            $gradoEnLetras = "SEGUNDO GRADO";
        }
        if ($parametro_grado == 3) {
            $gradoEnLetras = "TERCER GRADO";
        }
        if ($parametro_grado == 4) {
            $gradoEnLetras = "CUARTO GRADO";
        }
        if ($parametro_grado == 5) {
            $gradoEnLetras = "QUINTO GRADO";
        }
        if ($parametro_grado == 6) {
            $gradoEnLetras = "SEXTO GRADO";
        }

        // obtener fecha del sistema 
        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $fechaDia = $fechaActual->format('d');
        $fechaMes = $fechaActual->format('m');
        $fechaAnio = $fechaActual->format('Y');


        $mesLetras = "";
        if ($fechaMes == "01") {
            $mesLetras = "ENERO";
        }
        if ($fechaMes == "02") {
            $mesLetras = "FEBRERO";
        }
        if ($fechaMes == "03") {
            $mesLetras = "MARZO";
        }
        if ($fechaMes == "04") {
            $mesLetras = "ABRIL";
        }
        if ($fechaMes == "05") {
            $mesLetras = "MAYO";
        }
        if ($fechaMes == "06") {
            $mesLetras = "JUNIO";
        }
        if ($fechaMes == "07") {
            $mesLetras = "JULIO";
        }
        if ($fechaMes == "08") {
            $mesLetras = "AGOSTO";
        }
        if ($fechaMes == "09") {
            $mesLetras = "SEPTIEMBRE";
        }
        if ($fechaMes == "10") {
            $mesLetras = "OCTUBRE";
        }
        if ($fechaMes == "11") {
            $mesLetras = "NOVIEMBRE";
        }
        if ($fechaMes == "12") {
            $mesLetras = "DICIEMBRE";
        }


        // fecha que se mostrara en PDF 
        $fechahoy = 'MÉRIDA, YUC., A ' . $fechaDia . ' DE ' . $mesLetras . ' DE ' . $fechaAnio . '.';

        $parametro_NombreArchivo = "pdf_secundaria_constancia_NoAdeudo";
        $pdf = PDF::loadView('reportes.pdf.secundaria.constancias.' . $parametro_NombreArchivo, [
            "genero" => $parametro_genero_alumno,
            "alumno" => $parametro_alumno,     
            "grado" => $gradoEnLetras,
            "grupo" => $parametro_grupo,
            "fechaHoy" => $fechahoy,
            "periodo" => $periodo,
            "matricula" => $parametro_matricula,
            "clave" => $parametro_clave,
            "parametro_consideracion" => $parametro_consideracion     
        ]);

        $pdf->defaultFont = 'Calibri';

        return $pdf->stream($parametro_NombreArchivo.'_'.$parametro_alumno .'_'.$parametro_grado.$parametro_grupo.'_'.$periodo. '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }

    // constancia de cupo 
    public function imprimirConstanciaCupo($id_curso)
    {
        

        $inscrito = Secundaria_inscritos::select(
            'secundaria_inscritos.id', 
            'secundaria_inscritos.inscTrimestre1 as trimestre1',
            'secundaria_inscritos.inscTrimestre2 as trimestre2',
            'secundaria_inscritos.inscTrimestre2 as trimestre3',
            'secundaria_inscritos.inscPromedioTrim as promedioTrimestre',
            'secundaria_grupos.gpoGrado',
            'secundaria_grupos.gpoClave',
            'periodos.perAnioPago',
            'secundaria_materias.matNombre',
            'personas.perNombre',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perSexo'
        )
        ->leftJoin('secundaria_grupos', 'secundaria_inscritos.grupo_id', '=', 'secundaria_grupos.id')
        ->join('periodos', 'secundaria_grupos.periodo_id', '=', 'periodos.id')
        ->join('secundaria_materias', 'secundaria_grupos.secundaria_materia_id', '=', 'secundaria_materias.id')
        ->join('cursos', 'secundaria_inscritos.curso_id', '=', 'cursos.id')
        ->join('alumnos', 'cursos.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->where('secundaria_inscritos.curso_id', $id_curso)
        ->orderBy('secundaria_materias.matNombre', 'ASC')
        ->get();
       
        if($inscrito->isEmpty()){
            alert()->warning('Sin coincidencias', 'No se ha encontrado datos relacionados al alumno')->showConfirmButton();
            return back()->withInput();
        }
        $parametro_alumno = $inscrito[0]->perApellido1.' '.$inscrito[0]->perApellido2. ' '.$inscrito[0]->perNombre;
        $parametro_grupo = $inscrito[0]->gpoClave;
        $parametro_periodo_incio = $inscrito[0]->perAnioPago;
        $parametro_periodo_fin = 1 + (int)$inscrito[0]->perAnioPago;
        $parametro_periodo_sig = 1 + $parametro_periodo_fin;
        // valida el genero
        if ($inscrito[0]->perSexo == "F") {
            $parametro_genero_alumno = "que la alumna";
        } else {
            $parametro_genero_alumno = "que el alumno";
        }


        // obtener fecha del sistema 
        $fechaActual = Carbon::now('America/Merida');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        $fechaDia = $fechaActual->format('d');
        $fechaMes = $fechaActual->format('m');
        $fechaAnio = $fechaActual->format('Y');

         // valida que grado es para escribir lo que corresponda 
         $gradoEnLetras = "";
         $gradoSiguiente = "";
         $nivel = "";
         if ($inscrito[0]->gpoGrado == 1) {
             $gradoEnLetras = "primer grado";
             $gradoSiguiente = "2do";
             $nivel = "secundaria";
         }
         if ($inscrito[0]->gpoGrado == 2) {
             $gradoEnLetras = "segundo grado";
             $gradoSiguiente = "3er";
             $nivel = "secundaria";
         }
         if ($inscrito[0]->gpoGrado == 3) {
             $gradoEnLetras = "tercer grado";
             $gradoSiguiente = "1er";
             $nivel = "preparatoria";
         }
        //  if ($inscrito[0]->gpoGrado == 4) {
        //      $gradoEnLetras = "cuarto grado";
        //      $gradoSiguiente = "5to";
        //  }
        //  if ($inscrito[0]->gpoGrado == 5) {
        //      $gradoEnLetras = "quinto grado";
        //      $gradoSiguiente = "6to";
        //  }
        //  if ($inscrito[0]->gpoGrado == 6) {
        //      $gradoEnLetras = "sexto grado";
        //      $gradoSiguiente = "";
        //  }

        // meeses en letras 
        $mesLetras = "";
        if ($fechaMes == "01") {
            $mesLetras = "Enero";
        }
        if ($fechaMes == "02") {
            $mesLetras = "Febrero";
        }
        if ($fechaMes == "03") {
            $mesLetras = "Marzo";
        }
        if ($fechaMes == "04") {
            $mesLetras = "Abril";
        }
        if ($fechaMes == "05") {
            $mesLetras = "Mayo";
        }
        if ($fechaMes == "06") {
            $mesLetras = "Junio";
        }
        if ($fechaMes == "07") {
            $mesLetras = "Julio";
        }
        if ($fechaMes == "08") {
            $mesLetras = "Agosto";
        }
        if ($fechaMes == "09") {
            $mesLetras = "Septiembre";
        }
        if ($fechaMes == "10") {
            $mesLetras = "Octubre";
        }
        if ($fechaMes == "11") {
            $mesLetras = "Noviembre";
        }
        if ($fechaMes == "12") {
            $mesLetras = "Diciembre";
        }


        // fecha que se mostrara en PDF 
        $fechahoy = 'Mérida, Yucatán, a ' . $fechaDia . ' de ' . $mesLetras . ' de ' . $fechaAnio . '.';

        $parametro_NombreArchivo = "pdf_secundaria_constancia_cupo";
        $pdf = PDF::loadView('reportes.pdf.secundaria.constancias.' . $parametro_NombreArchivo, [
            'inscrito' => $inscrito,
            'fechaHoy' => $fechahoy,
            'alumno' => $parametro_alumno,
            'genero' => $parametro_genero_alumno,
            'grado' => $gradoEnLetras,
            'grupo' => $parametro_grupo,
            'periodo_inicio' => $parametro_periodo_incio,
            'periodo_fin' => $parametro_periodo_fin,
            'periodo_siguiente' => $parametro_periodo_sig,
            'gradoSiguiente' => $gradoSiguiente,
            'nivel' => $nivel
        ]);

        $pdf->defaultFont = 'Calibri';

        return $pdf->stream($parametro_NombreArchivo . '.pdf');
        return $pdf->download($parametro_NombreArchivo  . '.pdf');
    }
}
