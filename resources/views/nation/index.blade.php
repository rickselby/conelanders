@extends('page')

@section('content')

    @if (Auth::user() && Auth::user()->admin)
        <p>
            <a class="btn btn-small btn-info" href="{{ route('nation.create') }}">Add a new nation</a>
        </p>
    @endif

    <h2>Nations</h2>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nation</th>
            <th>Flag</th>
            <th>Code</th>
            <th>Drivers</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($nations as $nation)
            <tr>
                <td>{{ $nation->name }}</td>
                <td>
                    @include('nation.image')
                </td>
                <td>{{ $nation->acronym }}</td>
                <td>{{ count($nation->drivers) }}</td>
                <td>
                    <a class="btn btn-xs btn-success"
                       href="{{ route('nation.edit', $nation) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
