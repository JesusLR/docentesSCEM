@if ((Auth::user()->preescolar == 1)  || (Auth::user()->maternal == 1))
        @php
            $userDepClave = "";
            $userClave = Auth::user()->username;
        @endphp

        @if (Auth::user()->maternal == 1)
            @php
              $userDepClave = "MAT";
            @endphp
        @endif
        @if (Auth::user()->preescolar == 1)
            @php
              $userDepClave = "PRE";
            @endphp
        @endif


        <li class="bold">
            <a class="collapsible-header waves-effect waves-cyan">
                <i class="material-icons">dashboard</i>
                <span class="nav-text">Preescolar</span>
            </a>
            <div class="collapsible-body">
                <ul>
                    <li>
                        <a href="{{ url('preescolar_grupo') }}">
                            <i class="material-icons">keyboard_arrow_right</i>
                            <span>Grupos</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>


@endif
