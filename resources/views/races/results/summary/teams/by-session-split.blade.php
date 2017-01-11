@foreach(\RacesTeamStandings::eventSummary($event) AS $size => $table)

    <h3>{{ $size }} car teams</h3>

    @include('races.results.summary.teams.by-session-table')

@endforeach