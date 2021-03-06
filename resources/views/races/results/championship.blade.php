@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $championship->name }}</h1>
    </div>
@endsection

@section('content')

    <h4>{{ \RacesChampionships::cars($championship)->implode('name', ', ') }}</h4>
    @include('races.results.championship-summary')

    @if (count($championship->teams))
        <h2>Teams</h2>

        @foreach($championship->teams->sortBy('name') AS $team)
            <h3>{{ $team->name }}</h3>
            @if ($team->car)
                <h4>{{ $team->car->name }}</h4>
            @endif

            <ul class="list-group list-group-condensed">
                @forelse($team->sortedEntrants AS $entrant)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4 col-lg-3">
                                @include('races.driver.name', ['entrant' => $entrant])
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
            @forelse($championship->noTeamEntrantsSorted AS $entrant)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-4 col-lg-3">
                            @include('races.driver.name', ['entrant' => $entrant])
                        </div>
                        <div class="col-md-8 col-lg-9">
                            @if ($entrant->car)
                                {{ $entrant->car->name }}
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
