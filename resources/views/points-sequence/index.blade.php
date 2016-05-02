@extends('page')

@section('header')
    <div class="page-header">
        <h1>Points Sequences</h1>
    </div>
@endsection

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('points-sequence.create') }}">Add a new sequence</a>
    </p>

    <ul>
        @foreach($sequences as $sequence)
            <li>
                <a href="{{ route('points-sequence.show', $sequence) }}">
                    {{ $sequence->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection