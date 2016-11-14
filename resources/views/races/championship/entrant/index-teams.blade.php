@foreach($championship->teams->sortBy('name') AS $team)
    <h3>{{ $team->name }}</h3>

    @if (count($team->entrants))

        @include('races.championship.entrant.table', ['entrants' => $team->entrants(), 'car' => false])

    @else
        <p>No entrants</p>
    @endif
@endforeach

<h3>Independants (no team)</h3>

@include('races.championship.entrant.table', ['entrants' => $championship->entrants()->noTeam()])

