<?php

namespace App\Http\Controllers\Primaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Primaria\Primaria_agenda;
use App\Models\Primaria\Primaria_agenda_colores;
use App\Models\Primaria\Primaria_empleado;

class PrimariaAgendaController extends Controller
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
        $empleado_id = auth()->user()->primaria_empleado->id;        
        $primaria_agenda_colores = Primaria_agenda_colores::where("empleado_id", "=", $empleado_id)->first(); 

        $primaria_empleados = Primaria_empleado::where('empEstado', '!=', 'B')->get();
        
        /* --- inner para obtener el valor del nombre y colores de los usuarios -- */
        $colores_usuarios = Primaria_agenda_colores::select('primaria_agenda_colores.id', 'primaria_agenda_colores.preesColor',
            'primaria_empleados.empNombre as perNombre',
            'primaria_empleados.empApellido1 as perApellido1',
            'primaria_empleados.empApellido2 as perApellido2')
        ->join('primaria_empleados', 'primaria_agenda_colores.empleado_id', '=', 'primaria_empleados.id')
        ->get();
        

        return view('primaria.calendario.show-list', [
            'primaria_agenda_colores' => $primaria_agenda_colores,
            'colores_usuarios' => $colores_usuarios,
            'primaria_empleados' => $primaria_empleados
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
        Primaria_agenda::insert($datosEvento);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empleado_id = auth()->user()->primaria_empleado->id;        

        $data['eventos'] = Primaria_agenda::select('primaria_agendas.id', 'primaria_agendas.title', 'primaria_agendas.description', 'primaria_agendas.lugarEvento',
        'primaria_agendas.color', 'primaria_agendas.textColor', 'primaria_agendas.start', 'primaria_agendas.end',
        'primaria_agendas.usuario_at',
        'empCreador.id as empleado_id_creador',
        'empCreador.empNombre as perNombreCreador', 
        'empCreador.empApellido1 as perApellido1Creador', 
        'empCreador.empApellido2 as perApellido2Creador',
        'emp1.id as empleado_id_uno',
        'emp1.empNombre as perNombreUno', 
        'emp1.empApellido1 as perApellido1Uno', 
        'emp1.empApellido2 as perApellido2Uno',
        'emp2.id as empleado_id_dos',
        'emp2.empNombre as perNombreDos', 
        'emp2.empApellido1 as perApellido1Dos', 
        'emp2.empApellido2 as perApellido2Dos',
        'emp3.id as empleado_id_tres',
        'emp3.empNombre as perNombreTres', 
        'emp3.empApellido1 as perApellido1Tres', 
        'emp3.empApellido2 as perApellido2Tres')
        ->join('primaria_empleados as empCreador', 'primaria_agendas.primaria_empleado_creador', '=', 'empCreador.id')
        ->leftJoin('primaria_empleados as emp1', 'primaria_agendas.primaria_empleado_id1', '=', 'emp1.id')
        ->leftJoin('primaria_empleados as emp2', 'primaria_agendas.primaria_empleado_id2', '=', 'emp2.id')
        ->leftJoin('primaria_empleados as emp3', 'primaria_agendas.primaria_empleado_id3', '=', 'emp3.id')
        ->whereNull('primaria_agendas.deleted_at')
        ->where('emp1.id', $empleado_id)
        ->orWhere('emp2.id', $empleado_id)
        ->orWhere('emp3.id', $empleado_id)
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
        $respuesta = Primaria_agenda::where('id', '=', $id)->update($datosEvento);
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
        $eventos = Primaria_agenda::findOrFail($id);
        Primaria_agenda::destroy($id);
        return response()->json($id);
    }
}
