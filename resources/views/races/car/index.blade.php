@extends('page')

@section('content')

    <h2>Cars</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('races.car.create') }}">Add another car</a>
    </p>

    <table class="table">
        <thead>
        <tr>
            <th>AC Identifier</th>
            <th>Full Name</th>
            <th>Short Name</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($cars as $car)
            <tr>
                <td>{{ $car->ac_identifier }}</td>
                <td>{{ $car->name }}</td>
                <td>{{ $car->short_name }}</td>
                <td>
                    <a class="btn btn-xs btn-warning"
                       href="{{ route('races.car.edit', $car) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


@endsection