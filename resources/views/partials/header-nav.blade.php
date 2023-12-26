<header id="header" class="page-topbar">
    <!-- start header nav-->
    <div class="navbar-fixed">
      <nav class="navbar-color   darken-4">
        <div class="nav-wrapper">
          <div class="header-search-wrapper hide-on-med-and-down sideNav-lock">
            <center><h5>Control Escolar Modelo</h5></center>
          </div>
          <ul class="right hide-on-med-and-down">
              @if ((Auth::user()->preescolar == 1)  || (Auth::user()->maternal == 1))
                      @php
                          $primerNombre = (explode(' ',Auth::user()->empleado->persona->perNombre));
                      @endphp
                  <li>{{ $primerNombre[0] }} {{ Auth::user()->empleado->persona->perApellido1 }} {{ Auth::user()->empleado->persona->perApellido2 }}</li>
              @else
                  @if (Auth::user()->primaria == 1)
                      @php
                          $primerNombre = (explode(' ',Auth::user()->primaria_empleado->empNombre));
                      @endphp
                      <li>{{ $primerNombre[0] }} {{ Auth::user()->primaria_empleado->empApellido1 }} {{ Auth::user()->primaria_empleado->empApellido2 }}</li>
                  @else
                      @if (Auth::user()->secundaria == 1)
                          @php
                              $primerNombre = (explode(' ',Auth::user()->secundaria_empleado->empNombre));
                          @endphp
                          <li>{{ $primerNombre[0] }} {{ Auth::user()->secundaria_empleado->empApellido1 }} {{ Auth::user()->secundaria_empleado->empApellido2 }}</li>
                      @else
                          @if (Auth::user()->bachiller == 1)
                              @php
                                  $primerNombre = (explode(' ',Auth::user()->bachiller_empleado->empNombre));
                              @endphp
                              <li>{{ $primerNombre[0] }} {{ Auth::user()->bachiller_empleado->empApellido1 }} {{ Auth::user()->bachiller_empleado->empApellido2 }}</li>
                          @else
                              @if (
                                      (Auth::user()->superior == 1)
                                      || (Auth::user()->posgrado == 1)
                                      || (Auth::user()->educontinua == 1)
                                  )
                                  @php
                                      $primerNombre = (explode(' ',Auth::user()->empleado->persona->perNombre));
                                  @endphp
                                  <li>{{ $primerNombre[0] }} {{ Auth::user()->empleado->persona->perApellido1 }} {{ Auth::user()->empleado->persona->perApellido2 }}</li>
                              @endif
                          @endif
                      @endif
                  @endif
              @endif



            <li>
            <li>
              <a href="javascript:void(0);" class="waves-effect waves-block waves-light profile-button" data-activates="profile-dropdown">
                <i class="material-icons">more_vert</i>
              </a>
            </li>
          </ul>
          <!-- profile-dropdown -->
          <ul id="profile-dropdown" class="dropdown-content">
              @if (
                (Auth::user()->superior == 1)  || (Auth::user()->posgrado == 1)
                || (Auth::user()->educontinua == 1)
                )
                    <li>
                      {!! HTML::decode(link_to_route('cambiar_contraseña', '<i class="material-icons">account_box</i>Mi cuenta', array(), ['class' => 'grey-text text-darken-1'])) !!}
                    </li>
              @endif
            <li>
              {!! HTML::decode(link_to_route('logout', '<i class="material-icons">keyboard_tab</i> Salir', array(), ['class' => 'grey-text text-darken-1'])) !!}
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>
