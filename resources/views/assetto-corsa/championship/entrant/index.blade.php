@extends('page')

@push('stylesheets')
<link href="{{ route('assetto-corsa.championship-css', $championship) }}" rel="stylesheet" />
@endpush

@section('content')

    <h2>Entrants</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('assetto-corsa.championship.entrant.create', $championship) }}">Add another entrant</a>
    </p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th class="col-md-4">Driver</th>
            <th class="col-md-4">Lap Chart Line</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($championship->entrants()->with('driver.nation')->orderByName()->get() as $entrant)
            <tr>
                <div class="row">
                    <td>
                        @include('assetto-corsa.driver.name', ['entrant' => $entrant])
                    </td>
                    <td>
                        <div style="background-color: {{ $entrant->colour2 }}; border-color: {{ $entrant->colour }};" class="line-example"></div>
                    </td>
                    <td>
                        <a class="btn btn-xs btn-warning"
                           href="{{ route('assetto-corsa.championship.entrant.edit', [$championship, $entrant]) }}">Edit Entrant</a>
                        <a class="btn btn-xs btn-info"
                           href="{{ route('driver.edit', $entrant->driver) }}">Edit Driver</a>
                    </td>
                </div>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection