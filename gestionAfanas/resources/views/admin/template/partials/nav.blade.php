<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <img alt="Tuto Laravel" class="admin-logo-nav" src="{{ asset('images/logo.jpg') }}">
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      @if(Auth::user())
      <ul class="nav navbar-nav">
        <li><a href="/">Inicio </a></li>
        @if(Auth::user()->admin())
          <li><a href="{{ route('admin.users.index') }}">Usuarios </a></li>
        @endif
        <li><a href="{{ route('admin.centros.index') }}">Centros</a></li>
        <li><a href="{{ route('admin.personas.index') }}">Personas</a></li>
        <!-- <li><a href="#">Diagnosticos</a></li> -->
        <!-- <li><a href="#">Tratamientos</a></li> -->
      </ul> 
      @endif

      <ul class="nav navbar-nav navbar-right">
          <li><a href="/">Pagina principal</a></li>
        <li class="dropdown"> 
          <ul class="nav navbar-nav navbar-right">
            <!-- Authentication Links -->
            @if(Auth::guest())
                <li><a href="{{ route('admin.auth.login') }}">Iniciar sesión</a></li>
                <li><a href="{{ url('/register') }}">Registrar</a></li>
              <!--  <li><a href="">Registrar</a></li> -->
            @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('admin.auth.logout') }}"><i class="fa fa-btn fa-sign-out"></i>Cerrar sesión</a></li>
                    </ul>
                </li>
            @endif
          </ul>
        </li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

