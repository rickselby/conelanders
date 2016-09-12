<h3>Results</h3>
@include('assetto-corsa.results.session.lap-table', ['lapTimes' => \ACResults::fastestLaps($session)] )
