@if (count($acResults['all']))
    <h2>Best Results</h2>

    <div class="panel panel-default">
        @include('races.driver.best-results.championship')
        @include('races.driver.best-results.event')
        @include('races.driver.best-results.practice')
        @include('races.driver.best-results.qualifying')
        @include('races.driver.best-results.race')
        @include('races.driver.best-results.race-lap')
    </div>

    <h2>All Results</h2>

    @include('races.driver.all-results.championships')

@else
    <h3>No results</h3>
@endif