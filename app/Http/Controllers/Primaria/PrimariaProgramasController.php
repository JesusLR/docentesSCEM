<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Primaria\Primaria_empleado;
use App\Models\Programa;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class PrimariaProgramasController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('primaria.programas.show-list');
    }

    /**
     * Show user list.
     *
     */
    public function list()
    {
        $programas = Programa::select('programas.id as programa_id','programas.progNombre','programas.progClave','escuelas.escNombre','departamentos.depNombre','ubicacion.ubiNombre','primaria_empleados.empNombre','primaria_empleados.empApellido1','primaria_empleados.empApellido2')
        ->leftJoin('primaria_empleados', 'programas.empleado_id', '=', 'primaria_empleados.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('departamentos.depClave', 'PRI');

        return DataTables::of($programas)
        ->filterColumn('nombreCompleto',function($query,$keyword){
            return $query->whereHas('primaria_empleado', function($query) use($keyword){
                $query->whereRaw("CONCAT(empNombre, ' ', empApellido1, ' ', empApellido2) like ?", ["%{$keyword}%"]);
            });
        })
        ->addColumn('nombreCompleto',function($query){
            return $query->empNombre." ".$query->empApellido1." ".$query->empApellido2;
        })
        ->addColumn('action',function($query){
            return '<a href="primaria_programa/'.$query->programa_id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="primaria_programa/'.$query->programa_id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
            
            <form id="delete_' . $query->programa_id . '" action="primaria_programa/' . $query->programa_id . '" method="POST" style="display:inline;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <a href="#" data-id="'  . $query->programa_id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                    <i class="material-icons">delete</i>
                </a>
            </form>';
        })->make(true);
    }

    /**
     * Show programas.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProgramas(Request $request, $escuela_id)
    {
        if($request->ajax()){
            $programas = Programa::where('escuela_id','=',$escuela_id)->get();
            return response()->json($programas);
        }
    }





    /**
     * Show programas.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrograma(Request $request, $programa_id)
    {
        if($request->ajax()){
            $programa = Programa::with('escuela')->where('id','=',$programa_id)->first();
            return response()->json($programa);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (User::permiso("programa") == "A" || User::permiso("programa") == "B") {
            $empleados = Primaria_empleado::get();
            $ubicaciones = Ubicacion::all();

            return view('primaria.programas.create', [
                'ubicaciones' => $ubicaciones,
                'empleados' => $empleados
            ]);

        }else{
            alert()
            ->error('Ups...', 'Sin privilegios para esta acción!')
            ->showConfirmButton()
            ->autoClose(5000);
            return redirect('primaria_programa');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'escuela_id'        => 'required',
                'empleado_id'       => 'required',
                'progClave'         => 'required|unique:programas,progClave,NULL,id,escuela_id,'.$request->input('escuela_id').',deleted_at,NULL',
                'progNombre'        => 'required',
                'progNombreCorto'   => 'required'
            ],
            [
                'progClave.unique' => "El programa ya existe",
            ]
        );

        if ($validator->fails()) {
            return redirect ('primaria_programa/create')->withErrors($validator)->withInput();
        } else {
            try {
                $programa = Programa::create([
                    'escuela_id'        => $request->input('escuela_id'),
                    'empleado_id'       => $request->input('empleado_id'),
                    'progClave'         => $request->input('progClave'),
                    'progNombre'        => $request->input('progNombre'),
                    'progNombreCorto'   => $request->input('progNombreCorto'),
                    'progTituloOficial' => $request->input('progTituloOficial')
                ]);
                alert('Escuela Modelo', 'El Programa se ha creado con éxito','success')->showConfirmButton();
                return redirect('primaria_programa');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_programa/create')->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $programa = Programa::select('programas.id as programa_id', 
        'programas.progNombre', 
        'programas.progClave',
        'programas.progNombreCorto',
        'programas.progClaveSegey',
        'programas.progClaveEgre',
        'programas.progTituloOficial',
        'escuelas.escNombre', 
        'departamentos.depNombre', 
        'ubicacion.ubiNombre', 
        'primaria_empleados.empNombre', 
        'primaria_empleados.empApellido1', 
        'primaria_empleados.empApellido2')
        ->leftJoin('primaria_empleados', 'programas.empleado_id', '=', 'primaria_empleados.id')
        ->join('escuelas', 'programas.escuela_id', '=', 'escuelas.id')
        ->join('departamentos', 'escuelas.departamento_id', '=', 'departamentos.id')
        ->join('ubicacion', 'departamentos.ubicacion_id', '=', 'ubicacion.id')
        ->where('departamentos.depClave', 'PRI')
            ->findOrFail($id);

        return view('primaria.programas.show', [
            'programa' => $programa
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (User::permiso("programa") == "A" || User::permiso("programa") == "B") {
            $empleados = Primaria_empleado::get();
            $programa = Programa::with('primaria_empleado','escuela')->findOrFail($id);

            return view('primaria.programas.edit', [
                'programa' => $programa,
                'empleados' => $empleados
            ]);
        }else{
            alert()
            ->error('Ups...', 'Sin privilegios para esta acción!')
            ->showConfirmButton()
            ->autoClose(5000);
            return redirect('primaria_programa');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            [
                'empleado_id'       => 'required',
                'progClave'         => 'required',
                'progNombre'        => 'required',
                'progNombreCorto'   => 'required'
            ],
            [
                'progClave.unique' => "El programa ya existe",
            ]
        );

        if ($validator->fails()) {
            return redirect('primaria_programa/'.$id.'/edit')->withErrors($validator)->withInput();
        } else {
            try {
                $programa = Programa::findOrFail($id);
                $programa->empleado_id          = $request->input('empleado_id');
                $programa->progClave            = $request->input('progClave');
                $programa->progNombre           = $request->input('progNombre');
                $programa->progNombreCorto      = $request->input('progNombreCorto');
                $programa->progTituloOficial    = $request->input('progTituloOficial');
                $programa->save();
                alert('Escuela Modelo', 'El Programa se ha actualizado con éxito','success')->showConfirmButton();
                return redirect('primaria_programa');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_programa/'.$id.'/edit')->withInput();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::permiso("programa") == "A" || User::permiso("programa") == "B") {
            $programa = Programa::findOrFail($id);
            try {
                if($programa->delete()){
                    alert('Escuela Modelo', 'El programa se ha eliminado con éxito','success');
                }else{
                    alert()
                    ->error('Error...', 'No se puedo eliminar el programa')
                    ->showConfirmButton();
                }
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
            }
        }else{
            alert()
            ->error('Ups...', 'Sin privilegios para esta acción!')
            ->showConfirmButton()
            ->autoClose(5000);
        }
        return redirect('primaria_programa');
    }
}
