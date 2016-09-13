@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $championship->name }}</h1>
    </div>
@endsection

@section('content')

    <h4>{{ \ACChampionships::cars($championship)->implode('full_name', ', ') }}</h4>
    @include('assetto-corsa.results.championship-summary')

    @if (count($championship->teams))
        <h2>Teams</h2>

        @foreach($championship->teams AS $team)
            <h3>{{ $team->name }}</h3>
            @if ($team->car)
                <h4>{{ $team->car->full_name }}</h4>
            @endif

            <ul class="list-group list-group-condensed">
                @forelse($team->entrants()->orderByName()->get() AS $entrant)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4 col-lg-3">
                                @include('assetto-corsa.driver.name', ['entrant' => $entrant])
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">No members</li>
                @endforelse
            </ul>
        @endforeach

        <h3>Independent drivers</h3>

        <ul class="list-group list-group-condensed">
            @forelse($championship->entrants()->noTeam()->get() AS $entrant)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-4 col-lg-3">
                            @include('assetto-corsa.driver.name', ['entrant' => $entrant])
                        </div>
                        <div class="col-md-8 col-lg-9">
                            @if ($entrant->car)
                                {{ $entrant->car->full_name }}
                            @endif
                        </div>
                    </div>
                </li>
                @empty
                    <li class="list-group-item">No members</li>
            @endforelse
        </ul>

    @endif

@endsection
