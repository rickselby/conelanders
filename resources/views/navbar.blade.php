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
                @foreach(\RacesCategories::getList() AS $navCat)
                    <li>
                        <a href="{{ route('races.index', $navCat) }}">
                            {{ $navCat->name }}
                        </a>
                    </li>
                @endforeach
            @if (Gate::check('role-admin') || Gate::check('user-admin') || Gate::check('nation-admin') || Gate::check('points-admin') || Gate::check('playlist-admin') || Gate::check('dirt-rally-admin') || Gate::check('races-admin') || Gate::check('ac-server-admin') )
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
                        @can('user-admin')
                            <li>
                                <a href="{{ route('user.assignments') }}">Driver Assignments</a>
                            </li>
                        @endcan
                        @can('nation-admin')
                        <li>
                            <a href="{{ route('nation.index') }}">Manage Nations</a>
                        </li>
                        @endcan
                        @can('points-admin')
                        <li>
                            <a href="{{ route('points-sequence.index') }}">Points Sequences</a>
                        </li>
                        @endcan
                        @can('playlist-admin')
                            <li>
                                <a href="{{ route('playlists.index') }}">YouTube Playlists</a>
                            </li>
                        @endcan
                        @can('dirt-rally-admin')
                        @if (Gate::check('role-admin') || Gate::check('user-admin') || Gate::check('nation-admin') || Gate::check('points-admin') || Gate::check('playlist-admin'))
                        <li role="separator" class="divider"></li>
                        @endif
                        <li class="dropdown-header">Dirt Rally</li>
                        <li>
                            <a href="{{ route('dirt-rally.championship.index') }}">Championship Management</a>
                        </li>
                        @endcan

                        @if (Gate::check('races-admin'))

                            @if (Gate::check('role-admin') || Gate::check('user-admin') || Gate::check('nation-admin') || Gate::check('points-admin') || Gate::check('playlist-admin') || Gate::check('dirt-rally-admin'))
                                <li role="separator" class="divider"></li>
                            @endif
                            <li class="dropdown-header">Race Results</li>
                            <li>
                                <a href="{{ route('races.category.index') }}">Category Management</a>
                            </li>
                        @endif

                        @can('rallycross-admin')
                            @if (Gate::check('role-admin') || Gate::check('user-admin') || Gate::check('nation-admin') || Gate::check('points-admin') || Gate::check('playlist-admin') || Gate::check('ac-server-admin') || Gate::check('assetto-corsa-admin'))
                                <li role="separator" class="divider"></li>
                            @endif
                            <li class="dropdown-header">Rallycross</li>
                            <li>
                                <a href="{{ route('rallycross.car.index') }}">Cars</a>
                            </li>
                            <li>
                                <a href="{{ route('rallycross.championship.index') }}">Championship Management</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
                <li>
                    <a href="{{ route('about') }}">About</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
                <li>
                    <a href="{{route('user.show')}}">My Profile</a>
                </li>
                <li>
                    <a href="{{route('logout')}}">Logout</a>
                </li>
            @else
                <li>
                    <form method="get" action="{{ route('login.google') }}">
                        <button class="btn btn-social btn-google navbar-btn btn-sm">
                            <span class="fa fa-google"></span> Sign in with Google
                        </button>
                    </form>
                </li>
            @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>