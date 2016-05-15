@if (count($acResults['all']))
    <h2>Best Results</h2>

    <div class="panel panel-default">
        @include('assetto-corsa.driver.best-results.championship')
        @include('assetto-corsa.driver.best-results.qualifying')
        @include('assetto-corsa.driver.best-results.race')
        @include('assetto-corsa.driver.best-results.race-lap')
    </div>

    <h2>All Results</h2>

    @include('assetto-corsa.driver.all-results.championships')

@else
    <h3>No results</h3>
@endif