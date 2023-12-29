<?php

namespace App\Http\Controllers\Secundaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Escuela;
use App\Models\Periodo;
use App\Models\Secundaria\Secundaria_porcentajes;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class SecundariaPorcentajeController extends Controller
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
        return view('secundaria.porcentaje.show-list');
    }


    public function list()
    {
        $periodos = Secundaria_porcentajes::select(
            'secundaria_porcentajes.id',
            'departamentos.id as departamento_id',
            'departamentos.depNombre',
            'periodos.id as periodo_id',
            'periodos.perAnioPago',
            'secundaria_porcentajes.porc_septiembre',
            'secundaria_porcentajes.porc_octubre',
            'secundaria_porcentajes.porc_noviembre',
            'secundaria_porcentajes.porc_periodo1',
            'secundaria_porcentajes.porc_diciembre',
            'secundaria_porcentajes.porc_enero',
            'secundaria_porcentajes.porc_febrero',
            'secundaria_porcentajes.porc_marzo',
            'secundaria_porcentajes.porc_periodo2',
            'secundaria_porcentajes.porc_abril',
            'secundaria_porcentajes.porc_mayo',
            'secundaria_porcentajes.porc_junio',
            'secundaria_porcentajes.porc_periodo3',
            'ubicacion.ubiClave',
            'ubicacion.ubiNombre'
        )
        ->join('departamentos', 'secundaria_porcentajes.departamento_id', '=', 'departamentos.id')
        ->join('periodos', 'secundaria_porcentajes.periodo_id', '=', 'periodos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id');

        return DataTables::of($periodos)

        ->filterColumn('ubicacion',function($query,$keyword){
            $query->whereRaw("CONCAT(ubiNombre) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('ubicacion',function($query){
            return $query->ubiNombre;
        })

        ->filterColumn('anio',function($query,$keyword){
            $query->whereRaw("CONCAT(perAnioPago) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('anio',function($query){
            return $query->perAnioPago;
        })
              
        
        ->addColumn('action',function($query){
            return '<a href="secundaria_porcentaje/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="secundaria_porcentaje/'.$query->id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
            
            <form id="delete_' . $query->id . '" action="secundaria_porcentaje/' . $query->id . '" method="POST" style="display:inline;">
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
        $ubicaciones = Ubicacion::all();

        return view('secundaria.porcentaje.create', [
            'ubicaciones' => $ubicaciones
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
                'departamento_id' => 'required',
                'periodo_id' => 'required',
                'porc_septiembre' => 'required',
                'porc_octubre' => 'required',
                'porc_noviembre' => 'required',
                'porc_enero' => 'required',
                'porc_febrero' => 'required',
                'porc_marzo' => 'required',
                'porc_abril' => 'required',
                'porc_mayo' => 'required',
                'porc_junio' => 'required'

            ],
            [
                'departamento_id.required' => "El campo departamento es obligatorio",
                'periodo_id.required' => "El campo periodo es obligatorio",
                'porc_septiembre.required' => "El campo porcentaje septiembre es obligatorio",
                'porc_octubre.required' => "El campo porcentaje octubre es obligatorio",
                'porc_noviembre.required' => "El campo porcentaje noviembre es obligatorio",
                'porc_enero.required' => "El campo porcentaje enero es obligatorio",
                'porc_febrero.required' => "El campo porcentaje febrero es obligatorio",
                'porc_marzo.required' => "El campo porcentaje marzo es obligatorio",
                'porc_abril.required' => "El campo porcentaje abril es obligatorio",
                'porc_mayo.required' => "El campo porcentaje mayo es obligatorio",
                'porc_junio.required' => "El campo porcentaje junio es obligatorio"


            ]
        );


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        try {
            
            
            $porc_septiembre = $request->porc_septiembre;
            $porc_octubre = $request->porc_octubre;
            $porc_noviembre = $request->porc_noviembre;
            $porc_periodo1 = $porc_septiembre + $porc_octubre + $porc_noviembre;
            $porc_enero = $request->porc_enero;
            $porc_febrero = $request->porc_febrero;
            $porc_marzo = $request->porc_marzo;
            $porc_periodo2 = $porc_enero + $porc_febrero + $porc_marzo;
            $porc_abril = $request->porc_abril;
            $porc_mayo = $request->porc_mayo;
            $porc_junio = $request->porc_junio;
            $porc_periodo3 = $porc_abril + $porc_mayo + $porc_junio;


            // valida que el porcentaje total no sea mayor a 100
            if($porc_periodo1 > 100){
                alert('Escuela Modelo', 'El porcentaje del período 1 es mayor a 100','info')->showConfirmButton();
                return back()->withInput();
            }

            if($porc_periodo2 > 100){
                alert('Escuela Modelo', 'El porcentaje del período 1 es mayor a 100','info')->showConfirmButton();
                return back()->withInput();
            }


            if($porc_periodo3 > 100){
                alert('Escuela Modelo', 'El porcentaje del período 1 es mayor a 100','info')->showConfirmButton();
                return back()->withInput();
            }

            Secundaria_porcentajes::create([
                'departamento_id' => $request->departamento_id,
                'periodo_id' => $request->periodo_id,
                'porc_septiembre' => $porc_septiembre,
                'porc_octubre' => $porc_septiembre,
                'porc_noviembre' => $porc_noviembre,
                'porc_periodo1' => $porc_periodo1,
                'porc_diciembre' => 0,
                'porc_enero' => $porc_enero,
                'porc_febrero' => $porc_febrero,
                'porc_marzo' => $porc_marzo,
                'porc_periodo2' => $porc_periodo2,
                'porc_abril' => $porc_abril,
                'porc_mayo' => $porc_mayo,
                'porc_junio' => $porc_junio,
                'porc_periodo3' => $porc_periodo3
            ]);

            alert('Escuela Modelo', 'Los porcentajes han creado con éxito','success')->showConfirmButton();
            return redirect()->route('secundaria.secundaria_porcentaje.index');

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return back()->withInput();
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
        $porcentajes = Secundaria_porcentajes::where('id', $id)->first();
        $departamento = Departamento::where('id', $porcentajes->departamento_id)->first();
        $ubicacion = Ubicacion::where('id', $departamento->ubicacion_id)->first();
        $escuela = Escuela::where('departamento_id', $departamento->id)->first();
        $periodo = Periodo::where('id', $porcentajes->periodo_id)->first();


        return view('secundaria.porcentaje.show', [
            'ubicacion' => $ubicacion,
            'porcentajes' => $porcentajes,
            'departamento' => $departamento,
            'escuela' => $escuela,
            'periodo' => $periodo
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
        $porcentajes = Secundaria_porcentajes::where('id', $id)->first();
        $departamento = Departamento::where('id', $porcentajes->departamento_id)->first();
        $ubicacion = Ubicacion::where('id', $departamento->ubicacion_id)->first();


        return view('secundaria.porcentaje.edit', [
            'ubicacion' => $ubicacion,
            'porcentajes' => $porcentajes,
            'departamento' => $departamento
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
        
        $validator = Validator::make($request->all(),
            [
                'departamento_id' => 'required',
                'periodo_id' => 'required',
                'porc_septiembre' => 'required',
                'porc_octubre' => 'required',
                'porc_noviembre' => 'required',
                'porc_enero' => 'required',
                'porc_febrero' => 'required',
                'porc_marzo' => 'required',
                'porc_abril' => 'required',
                'porc_mayo' => 'required',
                'porc_junio' => 'required'

            ],
            [
                'departamento_id.required' => "El campo departamento es obligatorio",
                'periodo_id.required' => "El campo periodo es obligatorio",
                'porc_septiembre.required' => "El campo porcentaje septiembre es obligatorio",
                'porc_octubre.required' => "El campo porcentaje octubre es obligatorio",
                'porc_noviembre.required' => "El campo porcentaje noviembre es obligatorio",
                'porc_enero.required' => "El campo porcentaje enero es obligatorio",
                'porc_febrero.required' => "El campo porcentaje febrero es obligatorio",
                'porc_marzo.required' => "El campo porcentaje marzo es obligatorio",
                'porc_abril.required' => "El campo porcentaje abril es obligatorio",
                'porc_mayo.required' => "El campo porcentaje mayo es obligatorio",
                'porc_junio.required' => "El campo porcentaje junio es obligatorio"


            ]
        );


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        try {
            
            
            $porc_septiembre = $request->porc_septiembre;
            $porc_octubre = $request->porc_octubre;
            $porc_noviembre = $request->porc_noviembre;
            $porc_periodo1 = $porc_septiembre + $porc_octubre + $porc_noviembre;
            $porc_enero = $request->porc_enero;
            $porc_febrero = $request->porc_febrero;
            $porc_marzo = $request->porc_marzo;
            $porc_periodo2 = $porc_enero + $porc_febrero + $porc_marzo;
            $porc_abril = $request->porc_abril;
            $porc_mayo = $request->porc_mayo;
            $porc_junio = $request->porc_junio;
            $porc_periodo3 = $porc_abril + $porc_mayo + $porc_junio;


            // valida que el porcentaje total no sea mayor a 100
            if($porc_periodo1 > 100){
                alert('Escuela Modelo', 'El porcentaje del período 1 es mayor a 100','info')->showConfirmButton();
                return back()->withInput();
            }

            if($porc_periodo2 > 100){
                alert('Escuela Modelo', 'El porcentaje del período 1 es mayor a 100','info')->showConfirmButton();
                return back()->withInput();
            }


            if($porc_periodo3 > 100){
                alert('Escuela Modelo', 'El porcentaje del período 1 es mayor a 100','info')->showConfirmButton();
                return back()->withInput();
            }

            $porcentaje_edit = Secundaria_porcentajes::where('id', $id)->first();

            $porcentaje_edit->update([
                'departamento_id' => $request->departamento_id,
                'periodo_id' => $request->periodo_id,
                'porc_septiembre' => $porc_septiembre,
                'porc_octubre' => $porc_septiembre,
                'porc_noviembre' => $porc_noviembre,
                'porc_periodo1' => $porc_periodo1,
                'porc_diciembre' => 0,
                'porc_enero' => $porc_enero,
                'porc_febrero' => $porc_febrero,
                'porc_marzo' => $porc_marzo,
                'porc_periodo2' => $porc_periodo2,
                'porc_abril' => $porc_abril,
                'porc_mayo' => $porc_mayo,
                'porc_junio' => $porc_junio,
                'porc_periodo3' => $porc_periodo3
            ]);

            alert('Escuela Modelo', 'Los porcentajes han actualizado con éxito','success')->showConfirmButton();
            return redirect()->route('secundaria.secundaria_porcentaje.index');

        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Error...' . $errorCode, $errorMessage)->showConfirmButton();
            return back()->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empleado = Secundaria_porcentajes::findOrFail($id);
        try {
            if ($empleado->delete()) {
                alert('Escuela Modelo', 'Los porcentajes seleccionados se han eliminado con éxito', 'success')->showConfirmButton();
            } else {
                alert()->error('Error...', 'No se pudieron eliminar los porcentajes seleccionados')->showConfirmButton();
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }
        return redirect()->route('secundaria.secundaria_porcentaje.index');
    }
}
