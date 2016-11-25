@if (count($rallyCrossResults['all']))
    <h2>Best Results</h2>


    <div class="panel panel-default">
        @include('rallycross.driver.best-results.championship')
        @include('rallycross.driver.best-results.event')
        @include('rallycross.driver.best-results.race')
    </div>

    <h2>All Results</h2>

    @include('rallycross.driver.all-results.championships')

@else
    <h3>No results</h3>
@endif