<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Primaria\Primaria_contenidos_categorias;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class PrimariaCategoriaContenidosController extends Controller
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
        return view('primaria.perfil_categoria_contenido.show-list');
    }

    public function list()
    {
        $alumno_entrevista = Primaria_contenidos_categorias::select('primaria_contenidos_categorias.*')
        ->orderBy('primaria_contenidos_categorias.id');


        return DataTables::of($alumno_entrevista)

        // contenido 
        ->filterColumn('identificador',function($query,$keyword){
            $query->whereRaw("CONCAT(id) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('identificador',function($query){
            return $query->id;
        })

        ->filterColumn('categoria_nombre',function($query,$keyword){
            $query->whereRaw("CONCAT(categoria) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('categoria_nombre',function($query){
            return $query->categoria;
        })

  
        ->addColumn('action',function($query){
            return '<a href="primaria_categoria_contenido/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="primaria_categoria_contenido/'.$query->id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
            
            <form id="delete_' . $query->id . '" action="primaria_categoria_contenido/' . $query->id . '" method="POST" style="display:inline; display:none;">
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
        return view('primaria.perfil_categoria_contenido.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dejaBaja = DB::table('primaria_contenidos_categorias')
        ->whereNotNull('deleted_at')
        ->where('categoria', $request->categoria)    
        ->get();

        if(count($dejaBaja) > 0){
            $repetido = 'required';
        }else{
            $repetido = 'required|unique:primaria_contenidos_categorias,categoria';
        }

        $validator = Validator::make($request->all(),
            [
                'categoria' => $repetido
            ],
            [
                'categoria.unique' => "La categoría ya existe",
                'categoria.required' => 'El campo nombre categoría es obligatorio'
            ]
        );

        if ($validator->fails()) {
            return redirect ('primaria_categoria_contenido/create')->withErrors($validator)->withInput();
        }else{
            try {
        
                Primaria_contenidos_categorias::create([
                    'categoria' => $request->categoria
                ]);
    
                alert('Escuela Modelo', 'La categoría se ha creado con éxito','success')->showConfirmButton()->autoClose('5000');
                return redirect('primaria_categoria_contenido');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_categoria_contenido/create')->withInput();
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
        $primaria_contenidos_categorias = Primaria_contenidos_categorias::findOrFail($id);
        return view('primaria.perfil_categoria_contenido.show', [
            'primaria_contenidos_categorias' => $primaria_contenidos_categorias
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
       $primaria_contenidos_categorias = Primaria_contenidos_categorias::findOrFail($id);
       return view('primaria.perfil_categoria_contenido.edit', [
           'primaria_contenidos_categorias' => $primaria_contenidos_categorias
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
        $primaria_contenidos_categorias = Primaria_contenidos_categorias::where('id', $id)->first();

        // valida si es el mismo nombre 
        if($primaria_contenidos_categorias->categoria == $request->categoria){
            $necesario = "required";
        }else{
            $necesario = "required|unique:primaria_contenidos_categorias,categoria";
        }


        $validator = Validator::make($request->all(),
            [
                'categoria' => $necesario
            ],
            [
                'categoria.unique' => "La categoría ya existe",
                'categoria.required' => 'El campo nombre categoría es obligatorio'
            ]
        );

        if ($validator->fails()) {
            return redirect ('primaria_categoria_contenido/'.$primaria_contenidos_categorias->id.'/edit')->withErrors($validator)->withInput();
        }else{
            try {
        

                $primaria_contenidos_categorias->update([
                    'categoria' => $request->categoria
                ]);
    
                alert('Escuela Modelo', 'La categoría se ha creado con éxito','success')->showConfirmButton()->autoClose('5000');
                return redirect('primaria_categoria_contenido');
            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_categoria_contenido/'.$primaria_contenidos_categorias->id.'/edit')->withInput();
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
        $primaria_contenidos_categorias = Primaria_contenidos_categorias::findOrFail($id);
        try {
            if ($primaria_contenidos_categorias->delete()) {
                alert('Escuela Modelo', 'El categoría ha eliminado con éxito', 'success')->showConfirmButton()->autoClose('5000');
            } else {
                alert()->error('Error...', 'No se puedo eliminar la categoría')->showConfirmButton()->autoClose('5000');
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }

        return redirect('primaria_categoria_contenido');
    }
}
