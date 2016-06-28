@extends('page')

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('nation.create') }}">Add a new nation</a>
    </p>

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
                @if($nation->name)
                    <td>{{ $nation->name }}</td>
                    <td>
                        @include('nation.image')
                    </td>
                    <td>{{ $nation->acronym }}</td>
                    <td>{{ count($nation->drivers) }}</td>
                @else
                    <td colspan="4" class="danger">
                        <img src="{{ route('nation.image', $nation) }}" />
                        <strong>
                            New nation to set up
                        </strong>
                    </td>
                @endif
                <td>
                    <a class="btn btn-xs btn-success"
                       href="{{ route('nation.edit', $nation) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
