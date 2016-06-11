<!-- Static navbar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/conelanders.png') }}" alt="Conelanders" />
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ route('dirt-rally.index') }}">Dirt Rally</a>
                </li>
                <li>
                    <a href="{{ route('assetto-corsa.index') }}">Assetto Corsa</a>
                </li>
            @if (Gate::check('role-admin'))
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Admin <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('role-admin')
                        <li>
                            <a href="{{ route('role.index') }}">Manage Roles</a>
                        </li>
                        @endcan
                        <li>
                            <a href="{{ route('nation.index') }}">Manage Nations</a>
                        </li>
                        <li>
                            <a href="{{ route('points-sequence.index') }}">Points Sequences</a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Dirt Rally</li>
                        <li>
                            <a href="{{ route('dirt-rally.championship.index') }}">Championship Management</a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Assetto Corsa</li>
                        <li>
                            <a href="{{ route('assetto-corsa.championship.index') }}">Championship/Race Management</a>
                        </li>
                    </ul>
                </li>
            @endif
                <li>
                    <a href="{{ route('about') }}">About</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                @if (Auth::check())
                    <a href="{{route('logout')}}">Logout</a>
                @else
                    <form method="get" action="{{ route('login.google') }}">
                        <button class="btn btn-social btn-google navbar-btn btn-sm">
                            <span class="fa fa-google"></span> Sign in with Google
                        </button>
                    </form>
                @endif
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>