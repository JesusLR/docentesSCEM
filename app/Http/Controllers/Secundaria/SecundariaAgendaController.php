<?php

namespace App\Http\Controllers\Secundaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Secundaria\Secundaria_agenda_colores;
use App\Models\Secundaria\Secundaria_agendas;

class SecundariaAgendaController extends Controller
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
        $usuario_id = auth()->user()->id;        
        $secundaria_agenda_colores = Secundaria_agenda_colores::where("users_id", "=", $usuario_id)->first(); 
        
        /* --- inner para obtener el valor del nombre y colores de los usuarios -- */
        $colores_usuarios = Secundaria_agenda_colores::select('secundaria_agenda_colores.id', 'secundaria_agenda_colores.preesColor',
            'users.username', 'secundaria_empleados.empNombre as perNombre',
            'secundaria_empleados.empApellido1 as perApellido1',
            'secundaria_empleados.empApellido2 as perApellido2')
        ->join('users', 'users.id', '=', 'secundaria_agenda_colores.users_id')
        ->join('secundaria_empleados', 'secundaria_empleados.id', '=', 'users.empleado_id')
        ->get();
        

        return view('secundaria.calendario.show-list', [
            'secundaria_agenda_colores' => $secundaria_agenda_colores,
            'colores_usuarios' => $colores_usuarios
        ]);
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
        $datosEvento = request()->except(['_token','_method']);
        Secundaria_agendas::insert($datosEvento);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['eventos'] = Secundaria_agendas::select('secundaria_agendas.id', 'secundaria_agendas.title', 'secundaria_agendas.description',
        'secundaria_agendas.color', 'secundaria_agendas.textColor', 'secundaria_agendas.start', 'secundaria_agendas.end',
        'secundaria_agendas.usuario_at',
        'secundaria_empleados.empNombre as perNombre', 
        'secundaria_empleados.empApellido1 as perApellido1', 
        'secundaria_empleados.empApellido2 as perApellido2')
        ->join('users', 'users.id', '=', 'secundaria_agendas.usuario_at')
        ->join('secundaria_empleados', 'secundaria_empleados.id', '=', 'users.empleado_id')
        ->whereNull('secundaria_agendas.deleted_at')
        ->get();
        return response()->json($data['eventos']);
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
        $datosEvento = request()->except(['_token','_method']);
        $respuesta = Secundaria_agendas::where('id', '=', $id)->update($datosEvento);
        return response()->json($respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eventos = Secundaria_agendas::findOrFail($id);
        Secundaria_agendas::destroy($id);
        return response()->json($id);
    }
}
