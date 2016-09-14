@foreach($championship->teams->sortBy('name') AS $team)
    <h3>{{ $team->name }}</h3>

    @if (count($team->entrants))

        @include('assetto-corsa.championship.entrant.table', ['entrants' => $team->entrants(), 'car' => false])

    @else
        <p>No entrants</p>
    @endif
@endforeach

<h3>Independants (no team)</h3>

@include('assetto-corsa.championship.entrant.table', ['entrants' => $championship->entrants()->noTeam()])

