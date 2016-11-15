<h3>Results</h3>
@include('races.results.session.lap-table', ['lapTimes' => \RacesResults::fastestLaps($session), 'showLaps' => true])
