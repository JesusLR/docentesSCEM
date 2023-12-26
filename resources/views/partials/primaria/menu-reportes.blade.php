@if (Auth::user()->primaria == 1)
    @php
        $userDepClave = "PRI";
        $userClave = Auth::user()->username;
    @endphp

    <li class="bold">
        <a class="collapsible-header waves-effect waves-cyan">
            <i class="material-icons">dashboard</i>
            <span class="nav-text">PRI-Reportes</span>
        </a>
        <div class="collapsible-body">
            <ul>
                <li>
                    <a href="{{ route('primaria_reporte.calificaciones_grupo.reporte') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Calificaciones</span>
                    </a>
                </li>
              
            </ul>
        </div>
    </li>

@endif
