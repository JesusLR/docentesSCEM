<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Primaria\Primaria_contenidos_calificadores;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class PrimariaCalificadoresContenidosController extends Controller
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
        return view('primaria.perfil_calificadores.show-list');
    }

    public function list()
    {
        $calificador = Primaria_contenidos_calificadores::select(
            'Primaria_contenidos_calificadores.*');

        return DataTables::of($calificador)

        // perAnioPAgo 
        ->filterColumn('clave_primaria',function($query,$keyword){
            $query->whereRaw("CONCAT(id) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('clave_primaria',function($query){
            return $query->id;
        })

        ->filterColumn('calificador_nombre',function($query,$keyword){
                $query->whereRaw("CONCAT(calificador) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('calificador_nombre',function($query){
            return $query->calificador;
        })

        ->addColumn('action',function($query){
            return '<a href="primaria_calificador/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="primaria_calificador/'.$query->id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
                
            <form id="delete_' . $query->id . '" action="primaria_calificador/' . $query->id . '" method="POST" style="display:inline; display:none;">
                 <input type="hidden" name="_method" value="DELETE">
                 <input type="hidden" name="_token" value="' . csrf_token() . '">
                 <a href="#" data-id="'  . $query->id . '" class="button button--icon js-button js-ripple-effect confirm-delete" title="Eliminar">
                    <i class="material-icons">delete</i>
                 </a>
             </form>';
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
        return view('primaria.perfil_calificadores.create');

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
                'calificador' => 'required|unique:primaria_contenidos_calificadores,calificador'
            ],
            [
                'calificador.unique' => "La calificador ".$request->calificador." ya existe",
                'calificador.required' => 'El campo Nombre calificador es obligatorio'
            ]
        );

        if ($validator->fails()) {
            return redirect('primaria_calificador/create')->withErrors($validator)->withInput();
        } else {
            try {

                Primaria_contenidos_calificadores::create([
                    'calificador' => $request->calificador
                ]);

                alert('Escuela Modelo', 'El calificador se ha creado con éxito', 'success')->showConfirmButton()->autoClose('5000');
                return redirect('primaria_calificador');
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                    ->error('Ups...' . $errorCode, $errorMessage)
                    ->showConfirmButton();
                return redirect('primaria_calificador/create')->withInput();
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
        $primaria_contenidos_calificadores = Primaria_contenidos_calificadores::findOrFail($id);
        return view('primaria.perfil_calificadores.show', [
            'primaria_contenidos_calificadores' => $primaria_contenidos_calificadores
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
        $primaria_contenidos_calificadores = Primaria_contenidos_calificadores::findOrFail($id);
        return view('primaria.perfil_calificadores.edit', [
            'primaria_contenidos_calificadores' => $primaria_contenidos_calificadores
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
        $primaria_contenidos_calificadores = Primaria_contenidos_calificadores::findOrFail($id);

        if($primaria_contenidos_calificadores->calificador == $request->calificador){
            $repetido = "required";
        }else{
            $repetido = "required|unique:primaria_contenidos_calificadores,calificador";
        }

        
        $validator = Validator::make(
            $request->all(),
            [
                'calificador' => $repetido
            ],
            [
                'calificador.unique' => "La calificador ".$request->calificador." ya existe",
                'calificador.required' => 'El campo Nombre calificador es obligatorio'
            ]
        );

        if ($validator->fails()) {
            // return redirect('primaria_calificador/create')->withErrors($validator)->withInput();
            return back()->withErrors($validator)->withInput();
        } else {
            try {

                $primaria_contenidos_calificadores->update([
                    'calificador' => $request->calificador
                ]);

                alert('Escuela Modelo', 'El calificador se ha actualizado con éxito', 'success')->showConfirmButton()->autoClose('5000');
                return redirect('primaria_calificador');
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                    ->error('Ups...' . $errorCode, $errorMessage)
                    ->showConfirmButton();
                return back()->withInput();
            }
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
        $primaria_contenidos_calificadores = Primaria_contenidos_calificadores::findOrFail($id);
        try {
            if ($primaria_contenidos_calificadores->delete()) {
                alert('Escuela Modelo', 'El calificador se ha eliminado con éxito', 'success')->showConfirmButton()->autoClose('5000');
            } else {
                alert()->error('Error...', 'No se puedo eliminar el calificador')->showConfirmButton();
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }

        return redirect('primaria_calificador');
    }
}
