@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li class="active">Nations</li>
    </ol>
@endsection

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
                <td><img src="{{ route('nation.image', $nation->id) }}" alt="{{ $nation->name }}" /></td>
                <td>{{ $nation->acronym }}</td>
                <td>{{ count($nation->drivers) }}</td>
                <td>
                    <a class="btn btn-xs btn-success"
                       href="{{ route('nation.edit', [$nation->id]) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
