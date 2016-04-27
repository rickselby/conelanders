@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('dirt-rally.championship.index') }}">Results</a></li>
        <li class="active">{{ $championship->name }}</li>
    </ol>
@endsection

@section('content')

    @if (Auth::user() && Auth::user()->admin)
        {!! Form::open(['route' => ['dirt-rally.championship.destroy', $championship], 'method' => 'delete', 'class' => 'form-inline']) !!}
            <a class="btn btn-small btn-warning"
               href="{{ route('dirt-rally.championship.edit', $championship) }}">Edit championship</a>
            {!! Form::submit('Delete championship', array('class' => 'btn btn-danger')) !!}
        {!! Form::close() !!}
    @endif

    <h2>Seasons</h2>
    @if (Auth::user() && Auth::user()->admin)
        <p>
            <a class="btn btn-small btn-info"
               href="{{ route('dirt-rally.championship.season.create', $championship) }}">Add a new season</a>
        </p>
    @endif
    <ul>
        @forelse($seasons AS $season)
            <li>
                <a href="{{ route('dirt-rally.championship.season.show', [$championship, $season]) }}">
                    {{ $season->name }}
                </a>
            </li>
        @empty
            <li>No events</li>
        @endforelse
    </ul>

@endsection