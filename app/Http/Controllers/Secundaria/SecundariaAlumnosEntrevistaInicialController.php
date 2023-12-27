<?php

namespace App\Http\Controllers\Secundaria;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pais;
use App\Models\Primaria\Primaria_alumnos_entrevista;
use Illuminate\Database\QueryException;

class SecundariaAlumnosEntrevistaInicialController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paises = Pais::get();
  
        return view('secundaria.entrevista_inicial.create', [
            'paises' => $paises
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
        $validator = Validator::make($request->all(),
            [
                'expNombreAlumno'               => 'required',
                'expApellidoAlumno1'            => 'required',
                'expApellidoAlumno2'            => 'required',
                'expFechaNacAlumno'             => 'required',
                'expMunicioAlumno_id'           => 'required',
                'expCurpAlumno'                 => 'required',
                'expEdadAlumno'                 => 'required',
                'expTipoSangre'                 => 'required',
                'expAlergias'                   => 'nullable',
                'expEscuelaProcedencia'         => 'required',
                'expGradosCursados'             => 'required',
                'expAnioRecursado'              => 'required',
                'expTutorMadre'                 => 'required',
                'expEdadTutorMadre'             => 'required',
                'expFechaNacimientoTutorMadre'  => 'required',
                'exMunicipioMadre_id'           => 'required',
                'expOcupacionTutorMadre'        => 'required',
                'expEmpresaLaboralTutorMadre'   => 'nullable',
                'expCelularTutorMadre'          => 'required|min:10',
                'expTelefonoCasaTutorMadre'     => 'nullable|min:7',
                'expEmailTutorMadre'            => 'required',
                'expTutorPadre'                 => 'required',
                'expEdadTutorPadre'             => 'required',
                'expFechaNacimientoTutorPadre'  => 'required',
                'expMunicipioPadre_id'          => 'required',
                'expOcupacionTutorPadre'        => 'required',
                'expEmpresaLaboralTutorPadre'   => 'nullable',    
                'expCelularTutorPadre'          => 'required|min:10',
                'expTelefonoCasaTutorPadre'     => 'nullable|min:7',
                'expEmailTutorPadre'            => 'required',
                'expEstadoCivilPadres'          => 'required',
                'expEstadoCivilOtro'            => 'nullable',
                'expReligonPadres'              => 'required',
                'expNombreFamiliar'             => 'required',
                'expTelefonoFamiliar'           => 'required|min:10',
                'expPersona1Autorizada'         => 'required',
                'expPersona2Autorizada'         => 'nullable',
                'expPadresEgresados'            => 'required',
                'expFamiliarModelo'             => 'required',
                'expNombreFamiliarModelo'       => 'nullable'
            ],
            [
                'expCurpAlumno.unique'          => "La CURP ya se encuentra registrada",
                'expCelularTutorMadre.min'      => "El celular de la madre debe contener al menos 10 dígitos",
                'expTelefonoCasaTutorMadre.min' => "El télefono de la madre debe contener al menos 7 dígitos",
                'expCelularTutorPadre.min'      => "El celular del madre debe contener al menos 10 dígitos",
                'expTelefonoCasaTutorPadre'     => "El télefono del padre debe contener al menos 7 dígitos"
            ]
        );
 
        if ($validator->fails()) {
            return redirect ('secundaria_entrevista_inicial')->withErrors($validator)->withInput();
        }else{
            try {
                Primaria_alumnos_entrevista::create([
                'expNombreAlumno' => $request->expNombreAlumno, 
                'expApellidoAlumno1' => $request->expApellidoAlumno1, 
                'expApellidoAlumno2' => $request->expApellidoAlumno2, 
                'expFechaNacAlumno' => $request->expFechaNacAlumno, 
                'expMunicioAlumno_id' => $request->expMunicioAlumno_id, 
                'expCurpAlumno' => $request->expCurpAlumno, 
                'expEdadAlumno' => $request->expEdadAlumno, 
                'expTipoSangre' => $request->expTipoSangre, 
                'expAlergias' => $request->expAlergias, 
                'expEscuelaProcedencia' => $request->expEscuelaProcedencia, 
                'expGradosCursados' => $request->expGradosCursados, 
                'expAnioRecursado' => $request->expAnioRecursado, 
                'expTutorMadre' => $request->expTutorMadre, 
                'expEdadTutorMadre' => $request->expEdadTutorMadre, 
                'expFechaNacimientoTutorMadre' => $request->expFechaNacimientoTutorMadre, 
                'exMunicipioMadre_id' => $request->exMunicipioMadre_id, 
                'expOcupacionTutorMadre' => $request->expOcupacionTutorMadre, 
                'expEmpresaLaboralTutorMadre' => $request->expEmpresaLaboralTutorMadre, 
                'expCelularTutorMadre' => $request->expCelularTutorMadre, 
                'expTelefonoCasaTutorMadre' => $request->expTelefonoCasaTutorMadre, 
                'expEmailTutorMadre' => $request->expEmailTutorMadre, 
                'expTutorPadre' => $request->expTutorPadre, 
                'expEdadTutorPadre' => $request->expEdadTutorPadre, 
                'expFechaNacimientoTutorPadre' => $request->expFechaNacimientoTutorPadre, 
                'expMunicipioPadre_id' => $request->expMunicipioPadre_id, 
                'expOcupacionTutorPadre' => $request->expOcupacionTutorPadre, 
                'expEmpresaLaboralTutorPadre' => $request->expEmpresaLaboralTutorPadre, 
                'expCelularTutorPadre' => $request->expCelularTutorPadre, 
                'expTelefonoCasaTutorPadre' => $request->expTelefonoCasaTutorPadre, 
                'expEmailTutorPadre' => $request->expEmailTutorPadre, 
                'expEstadoCivilPadres' => $request->expEstadoCivilPadres, 
                'expEstadoCivilOtro' => $request->expEstadoCivilOtro, 
                'expReligonPadres' => $request->expReligonPadres, 
                'expNombreFamiliar' => $request->expNombreFamiliar, 
                'expTelefonoFamiliar' => $request->expTelefonoFamiliar, 
                'expPersona1Autorizada' => $request->expPersona1Autorizada, 
                'expPersona2Autorizada' => $request->expPersona2Autorizada, 
                'expPadresEgresados' => $request->expPadresEgresados, 
                'expFamiliarModelo' => $request->expFamiliarModelo, 
                'expNombreFamiliarModelo' => $request->expNombreFamiliarModelo
                ]);
    
                alert('Escuela Modelo', 'La entrevista se ha creado con éxito','success');
                return redirect()->route('secundaria_entrevista_inicial.create');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('secundaria_entrevista_inicial')->withInput();
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
        //
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
