<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Primaria\Primaria_contenidos_categorias;
use App\Models\Primaria\Primaria_contenidos_fundamentales;
use App\Models\Primaria\Primaria_expediente_perfiles_contenidos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class PrimariaPerfilContenidosFundamentalesController extends Controller
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
        return view('primaria.perfil_contenido_fundamental.show-list');
    }


    public function list()
    {
        $alumno_entrevista = Primaria_contenidos_fundamentales::select(
            'primaria_contenidos_fundamentales.*', 'primaria_contenidos_categorias.categoria')
        ->leftJoin('primaria_materias', 'primaria_contenidos_fundamentales.primaria_materia_id', '=', 'primaria_materias.id')
        ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
        ->orderBy('primaria_contenidos_categorias.categoria');


        return DataTables::of($alumno_entrevista)

        // contenido 
        ->filterColumn('contenido_fundamental',function($query,$keyword){
            $query->whereRaw("CONCAT(contenido) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('contenido_fundamental',function($query){
            return $query->contenido;
        })

        ->filterColumn('categoria_fundamental',function($query,$keyword){
            $query->whereRaw("CONCAT(categoria) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('categoria_fundamental',function($query){
            return $query->categoria;
        })

  
        ->addColumn('action',function($query){
            return '<a href="primaria_contenido_fundamental/'.$query->id.'" class="button button--icon js-button js-ripple-effect" title="Ver">
                <i class="material-icons">visibility</i>
            </a>
            <a href="primaria_contenido_fundamental/'.$query->id.'/edit" class="button button--icon js-button js-ripple-effect" title="Editar">
                <i class="material-icons">edit</i>
            </a>
            
            <form id="delete_' . $query->id . '" action="primaria_contenido_fundamental/' . $query->id . '" method="POST" style="display:inline; display:none;">
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
        $primaria_contenidos_categorias = Primaria_contenidos_categorias::get();
        return view('primaria.perfil_contenido_fundamental.create', [
            'primaria_contenidos_categorias' => $primaria_contenidos_categorias
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
                'contenido'  => 'required',
                'categoria'  => 'required'   
            ],
            [
                'contenido.required' => 'El campo contenido es obligatorio.',
                'categoria.required' => 'El campo categoria es obligatorio'
            ]
        );


        if ($validator->fails()) {
            return redirect ('primaria_contenido_fundamental/create')->withErrors($validator)->withInput();
        }else{
            try {
                
            
                $nuevoContenidoFundamental = Primaria_contenidos_fundamentales::create([
                    'contenido' => $request->contenido,
                    'primaria_contenidos_categoria_id' => $request->categoria
                ]);


                // obtenemos todos los id de las tablas para agregar el nuevo dato en la misma 
                $perfil = DB::table('primaria_expediente_perfiles_contenidos')
                ->select(DB::raw('count(*) as primaria_expediente_perfiles_id, primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id'),
                DB::raw('count(*) as perFechaFinal, periodos.perFechaFinal'))
                ->join('primaria_expediente_perfiles', 'primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id', '=', 'primaria_expediente_perfiles.id')
                ->join('primaria_contenidos_fundamentales', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_fundamentales_id', '=', 'primaria_contenidos_fundamentales.id')
                ->leftJoin('primaria_contenidos_calificadores', 'primaria_expediente_perfiles_contenidos.primaria_contenidos_calificadores_id', '=', 'primaria_contenidos_calificadores.id')
                ->join('primaria_contenidos_categorias', 'primaria_contenidos_fundamentales.primaria_contenidos_categoria_id', '=', 'primaria_contenidos_categorias.id')
                ->join('cursos', 'primaria_expediente_perfiles.curso_id', '=', 'cursos.id')
                ->join('periodos', 'cursos.periodo_id', '=', 'periodos.id')
                ->groupBy('primaria_expediente_perfiles_contenidos.primaria_expediente_perfiles_id')
                ->groupBy('periodos.perFechaFinal')
                ->get();

                if(count($perfil) > 0){
                    // obtenemos fecha del sistema 
                    $fechaActual = Carbon::now('America/Merida');
                    setlocale(LC_TIME, 'es_ES.UTF-8');
                    // En windows
                    setlocale(LC_TIME, 'spanish');
                    $fechaHoy = $fechaActual->format('Y-m-d');


                    foreach ($perfil as $p) {
                        $p->primaria_expediente_perfiles_id;

                        // agregamos en nuevo dato dependiendo la validación 
                        if($p->perFechaFinal >= $fechaHoy){
                            $expediente_perfiles_contenido = array();
                            $expediente_perfiles_contenido = new Primaria_expediente_perfiles_contenidos();
                            $expediente_perfiles_contenido['primaria_expediente_perfiles_id'] = $p->primaria_expediente_perfiles_id;
                            $expediente_perfiles_contenido['primaria_contenidos_fundamentales_id'] = $nuevoContenidoFundamental->id;
                            $expediente_perfiles_contenido['primaria_contenidos_calificadores_id'] = NULL;
                            $expediente_perfiles_contenido['observacion_contenido'] = "";

                            $expediente_perfiles_contenido->save();
                        }
                        
                    }
                }
                
               
              
                alert('Escuela Modelo', 'La entrevista se ha creado con éxito','success')->showConfirmButton();
                return redirect()->route('primaria.primaria_contenido_fundamental.create');

            }catch (QueryException $e){
                $errorCode = $e->errorInfo[1];
                $errorMessage = $e->errorInfo[2];
                alert()
                ->error('Ups...'.$errorCode,$errorMessage)
                ->showConfirmButton();
                return redirect('primaria_contenido_fundamental/create')->withInput();
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
        $primaria_contenidos_fundamentales = Primaria_contenidos_fundamentales::where('id', $id)->first();

        return view('primaria.perfil_contenido_fundamental.show', [
            "contenido_fundamental" => $primaria_contenidos_fundamentales
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
        $primaria_contenidos_categorias = Primaria_contenidos_categorias::get();

        $primaria_contenidos_fundamentales = Primaria_contenidos_fundamentales::where('id', $id)->first();

        return view('primaria.perfil_contenido_fundamental.edit', [
            "contenido_fundamental" => $primaria_contenidos_fundamentales,
            "primaria_contenidos_categorias" => $primaria_contenidos_categorias
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
        $primaria_contenidos_fundamentales = Primaria_contenidos_fundamentales::where('id', $id)->first();

        $primaria_contenidos_fundamentales->update([
            'contenido' => $request->contenido,
            'categoria' => $request->categoria
        ]);


        alert('Escuela Modelo', 'El contenido fundamental se actualizo con éxito', 'success')->showConfirmButton();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $primaria_contenidos_fundamentales = Primaria_contenidos_fundamentales::findOrFail($id);
        try {
            if ($primaria_contenidos_fundamentales->delete()) {
                alert('Escuela Modelo', 'El contenido fundamental se ha eliminado con éxito', 'success')->showConfirmButton();
            } else {
                alert()->error('Error...', 'No se puedo eliminar el contenido fundamental')->showConfirmButton();
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            $errorMessage = $e->errorInfo[2];
            alert()->error('Ups...' . $errorCode, $errorMessage)->showConfirmButton();
        }

        return redirect('primaria_contenido_fundamental');
    }
}
