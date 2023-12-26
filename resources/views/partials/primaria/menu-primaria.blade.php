@if (Auth::user()->primaria == 1)
    @php
        $userDepClave = "PRI";
        $userClave = Auth::user()->username;
    @endphp

    <li class="bold">
        <a class="collapsible-header waves-effect waves-cyan">
            <i class="material-icons">dashboard</i>
            <span class="nav-text">Primaria</span>
        </a>
        <div class="collapsible-body">
            <ul>
                <li>
                    <a href="{{ route('primaria_grupo.index') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Grupos del Docente</span>
                    </a>
                </li>
                {{--  <li>
                    <a href="{{route('primaria.primaria_planeacion_docente.index')}}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Planeación</span>
                    </a>
                </li>  --}}
                <li>
                    <a href="{{route('primaria.primaria_perfil.index')}}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Perfiles</span>
                    </a>
                </li>

                {{--
                <li>
                    <a href="{{route('primaria.primaria_seguimiento_escolar.index')}}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Seguimiento escolar</span>
                    </a>
                </li>
                --}}

                {{--
                <li>
                    <a href="{{route('primaria.primaria_ahorro_escolar.index')}}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Ahorro escolar</span>
                    </a>
                </li>
                --}}
                <li>
                    <a href="{{ route('primaria_calendario.index') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Agenda</span>
                    </a>
                </li>

            </ul>
        </div>
    </li>

@endif
