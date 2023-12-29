<?php

namespace App\Http\Controllers\Secundaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Secundaria\Secundaria_resumenacademico;
use Yajra\DataTables\Facades\DataTables;

class SecundariaResumenAcademico extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('secundaria.resumen_academico.show-list');
    }

    public function list()
    {
        $alumno = Secundaria_resumenacademico::select(
            'secundaria_resumenacademico.id',
            'alumnos.aluClave',
            'personas.perApellido1',
            'personas.perApellido2',
            'personas.perNombre',
            'planes.planClave',
            'programas.progClave',
            'programas.progNombre',
            'escuelas.escNombre',
            'departamentos.depClave',
            'departamentos.depNombre',
            'ubicacion.ubiNombre',
            'periodosIngreso.perAnioPago'
        )
        ->join('alumnos', 'secundaria_resumenacademico.alumno_id', '=', 'alumnos.id')
        ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        ->join('planes', 'secundaria_resumenacademico.plan_id', '=', 'planes.id')
        ->join('programas', 'planes.programa_id', '=', 'programas.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->leftJoin('periodos  as periodosIngreso', 'secundaria_resumenacademico.resPeriodoIngreso', '=', 'periodosIngreso.id')
        ->orderBy('secundaria_resumenacademico.id', 'DESC');


        return DataTables::of($alumno)
      
        ->filterColumn('anio_ingreso', function ($query, $keyword) {
            $query->whereRaw("CONCAT(perAnioPago) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('anio_ingreso', function ($query) {
            return $query->perAnioPago;
        })

        ->filterColumn('clave_pago', function ($query, $keyword) {
            $query->whereRaw("CONCAT(aluClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('clave_pago', function ($query) {
            return $query->aluClave;
        })


        ->filterColumn('apellido_paterno', function ($query, $keyword) {
            $query->whereRaw("CONCAT(perApellido1) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('apellido_paterno', function ($query) {
            return $query->perApellido1;
        })

        ->filterColumn('apellido_materno', function ($query, $keyword) {
            $query->whereRaw("CONCAT(perApellido2) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('apellido_materno', function ($query) {
            return $query->perApellido2;
        })

        ->filterColumn('nombre_alumno', function ($query, $keyword) {
            $query->whereRaw("CONCAT(perNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('nombre_alumno', function ($query) {
            return $query->perNombre;
        })

        ->filterColumn('ubicacion', function ($query, $keyword) {
            $query->whereRaw("CONCAT(ubiNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('ubicacion', function ($query) {
            return $query->ubiNombre;
        })


        ->filterColumn('departamento', function ($query, $keyword) {
            $query->whereRaw("CONCAT(depNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('departamento', function ($query) {
            return $query->depNombre;
        })

        ->filterColumn('escuelas', function ($query, $keyword) {
            $query->whereRaw("CONCAT(escNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('escuelas', function ($query) {
            return $query->escNombre;
        })

        ->filterColumn('programa', function ($query, $keyword) {
            $query->whereRaw("CONCAT(progNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('programa', function ($query) {
            return $query->progNombre;
        })


        ->filterColumn('plan', function ($query, $keyword) {
            $query->whereRaw("CONCAT(planClave) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('plan', function ($query) {
            return $query->planClave;
        })

        ->addColumn('action',function($query){
            return '<a href="secundaria_resumen_academico/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>';
        })->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $resumenAcademico = Secundaria_resumenacademico::with('alumno.persona', 'plan.programa.escuela.departamento.ubicacion', 
        'periodoIngreso', 'periodoEgreso','periodoUltimo')->where('id', '=', $id)->first();


        // $resumenAcademico = Secundaria_resumenacademico::select(
        //     'secundaria_resumenacademico.*',
        //     'alumnos.aluClave',
        //     'personas.perApellido1',
        //     'personas.perApellido2',
        //     'personas.perNombre',
        //     'planes.planClave',
        //     'programas.progClave',
        //     'programas.progNombre',
        //     'escuelas.escNombre',
        //     'departamentos.depClave',
        //     'departamentos.depNombre',
        //     'ubicacion.ubiNombre',
        //     'periodosEgreso.perAnioPago as perAnioPagoEgreso',
        //     'periodosIngreso.perAnioPago as perAnioPagoIngreso',
        //     'periodosUltimo.perAnioPago as perAnioPagoUltimo'
        // )
        // ->join('alumnos', 'secundaria_resumenacademico.alumno_id', '=', 'alumnos.id')
        // ->join('personas', 'alumnos.persona_id', '=', 'personas.id')
        // ->join('planes', 'secundaria_resumenacademico.plan_id', '=', 'planes.id')
        // ->join('programas', 'planes.programa_id', '=', 'programas.id')
        // ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        // ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        // ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        // ->LeftJoin('periodos  as periodosEgreso', 'secundaria_resumenacademico.resPeriodoEgreso', '=', 'periodosEgreso.id')
        // ->LeftJoin('periodos  as periodosIngreso', 'secundaria_resumenacademico.resPeriodoIngreso', '=', 'periodosIngreso.id')
        // ->LeftJoin('periodos  as periodosUltimo', 'secundaria_resumenacademico.resPeriodoUltimo', '=', 'periodosUltimo.id')
        // ->findOrFail($id);

        return view('secundaria.resumen_academico.show', [
            'resumenAcademico' => $resumenAcademico
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
