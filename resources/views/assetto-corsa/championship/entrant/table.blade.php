
<table class="table table-striped">
    <thead>
    <tr>
        <th class="col-md-4">Driver</th>
        <th class="col-md-3">Lap Chart Line</th>
        <th class="col-md-2 col-lg-3">
            @if (!isset($car) || $car)
                Car
            @endif
        </th>
        <th class="col-md-3 col-lg-2"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($entrants->with('driver.nation')->orderByName()->get() as $entrant)
        <tr>
            <td>
                @include('assetto-corsa.driver.name', ['entrant' => $entrant])
            </td>
            <td>
                <div style="background-color: {{ $entrant->colour2 }}; border-color: {{ $entrant->colour }};" class="line-example"></div>
            </td>
            <td>
                @if (!isset($car) || $car)
                    {{ $entrant->car ? $entrant->car->full_name : '-' }}
                @endif
            </td>
            <td>
                <a class="btn btn-xs btn-warning"
                   href="{{ route('assetto-corsa.championship.entrant.edit', [$championship, $entrant]) }}">Edit Entrant</a>
                <a class="btn btn-xs btn-info"
                   href="{{ route('driver.edit', $entrant->driver) }}">Edit Driver</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
