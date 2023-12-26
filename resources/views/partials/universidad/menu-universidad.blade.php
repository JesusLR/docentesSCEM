@php
use App\Http\Models\Docente_encuestas_realizadas;
@endphp
@if (
        (Auth::user()->superior == 1)  || (Auth::user()->posgrado == 1)
        || (Auth::user()->educontinua == 1)
    )
    {{--
    @if (Auth::user()->empleado->escuela->departamento->depClave == "SUP" ||
                    Auth::user()->empleado->escuela->departamento->depClave == "POS" ||
                    Auth::user()->empleado->escuela->departamento->depClave == "DIP" ||
                    Auth::user()->empleado->escuela->departamento->depClave == "AEX" ||
                    Auth::user()->empleado->escuela->departamento->depClave == "IDI")
    --}}

    @php
        $userDepClave = "SUP";
        $userClave = Auth::user()->username;
        $empleado_id = Auth::user()->empleado->id;
        $docenteEncuestasRealizadas = Docente_encuestas_realizadas::where('empleado_id', $empleado_id)->first();
    @endphp

    <li class="bold">
        <a class="collapsible-header waves-effect waves-cyan">
            <i class="material-icons">dashboard</i>
            <span class="nav-text">Universidad</span>
        </a>
        <div class="collapsible-body">
            <ul>
                <li>
                    <a href="{{ url('grupo') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Grupos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('extraordinarios') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Extraordinarios</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ url('extracurricular') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Extracurricular</span>
                    </a>
                </li> --}}

                @if($ENCUESTA_ACTIVA)
                    <li>
                        <a href="{{ url('encuesta') }}">
                            <i class="material-icons">keyboard_arrow_right</i>
                            <span>Encuesta</span>
                        </a>
                    </li>
                @endif

                @if ($docenteEncuestasRealizadas)
                <li>
                    <a target="_blank" href="{{ $docenteEncuestasRealizadas->derUrl }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Resultados encuestas</span>
                    </a>
                </li>
                @endif

                @if($BIBLIOTECA_DOCENTE_ACTIVA)
                    @if(Auth::user()->empleado->escuela->departamento->depClave == 'SUP')
                    <li>
                        <a href="{{ url('biblioteca') }}">
                            <i class="material-icons">keyboard_arrow_right</i>
                            <span>BIBLIOTECA</span>
                        </a>
                    </li>
                    @endif
                @endif
            </ul>
        </div>
    </li>

@endif
