@if (Auth::user()->bachiller == 1 && Auth::user()->campus_cme == 1 || Auth::user()->campus_cva == 1)

    @php
        $userDepClave = "BAC";
        $userClave = Auth::user()->username;
    @endphp

    <li class="bold">
        <a class="collapsible-header waves-effect waves-cyan">
            <i class="material-icons">dashboard</i>
            <span class="nav-text">Bachiller UADY</span>
        </a>
        <div class="collapsible-body">
            <ul>
                <li>
                    <a href="{{ route('bachiller.bachiller_grupo_yucatan.index') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Grupos</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

@endif


@if (Auth::user()->bachiller == 1 && Auth::user()->campus_cch == 1)

    @php
        $userDepClave = "BAC";
        $userClave = Auth::user()->username;
    @endphp

    <li class="bold">
        <a class="collapsible-header waves-effect waves-cyan">
            <i class="material-icons">dashboard</i>
            <span class="nav-text">Bachiller SEQ</span>
        </a>
        <div class="collapsible-body">
            <ul>
                <li>
                    <a href="{{ route('bachiller.bachiller_grupo_seq.index') }}">
                        <i class="material-icons">keyboard_arrow_right</i>
                        <span>Grupos</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>

@endif
