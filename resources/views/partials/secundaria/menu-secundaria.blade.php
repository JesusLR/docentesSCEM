@if (Auth::user()->secundaria == 1)

    @php
        $userDepClave = "SEC";
        $userClave = Auth::user()->username;
    @endphp

    <li class="bold">
        <a class="collapsible-header waves-effect waves-cyan">
            <i class="material-icons">dashboard</i>
            <span class="nav-text">Secundaria</span>
        </a>
        <div class="collapsible-body">
            <ul>
                <li>
                    <a href="{{ url('secundaria_grupo') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Grupos</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

@endif
