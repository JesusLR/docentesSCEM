<aside id="left-sidebar-nav" class="nav-expanded nav-lock nav-collapsible">
    <div class="brand-sidebar">
        <center><img src="{{ asset('images/logo-blanco.png') }}" width="25%" height="25%"></center>
    </div>
    <ul id="slide-out" class="side-nav fixed leftside-navigation">
    <li class="no-padding">
        <ul class="collapsible" data-collapsible="accordion">

            @php

                $userMaternal = Auth::user()->maternal;
                $userPreescolar = Auth::user()->preescolar;
                $userPrimaria = Auth::user()->primaria;
                $userSecundaria = Auth::user()->secundaria;
                $userBachiller = Auth::user()->bachiller;
                $userSuperior = Auth::user()->superior;
                $userPosgrado = Auth::user()->posgrado;
                $userEduContinua = Auth::user()->educontinua;
                $userCobranza = Auth::user()->departamento_cobranza;
                $userCME = Auth::user()->campus_cme;
                $userCVA = Auth::user()->campus_cva;
                $userCCH = Auth::user()->campus_cch;

            @endphp

            {{-- PREESCOLAR --}}
            @if ($userMaternal == 1 || $userPreescolar)
                {{--  Menú para preescolar   --}}
                @include('partials.preescolar.menu-preescolar')
                {{--  Fin Menú para preescolar   --}}
            @endif

            {{-- PRIMARIA --}}
            @if ($userPrimaria == 1)
                {{--  Menú para primaria   --}}
                @include('partials.primaria.menu-primaria')
                {{--  Fin Menú para primaria   --}}
                @include('partials.primaria.menu-reportes')
            @endif

            {{-- SECUNDARIA --}}
            @if ($userSecundaria == 1)
                {{-- menu para secundaria  --}}
                @include('partials.secundaria.menu-secundaria')
                {{-- fin menu secundaria  --}}
            @endif


            {{-- UNIVERSIDAD --}}
            @if (
                    (Auth::user()->superior == 1)  || (Auth::user()->posgrado == 1)
                    || (Auth::user()->educontinua == 1)
                )

                @include('partials.universidad.menu-universidad')

            @endif

    </ul>
    </li>
    </ul>
    {{--
    <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only gradient-45deg--cyan gradient-shadow">
        <i class="material-icons">menu</i>
    </a>
    --}}
</aside>
