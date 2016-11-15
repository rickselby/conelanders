@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $event->championship->name }}: {{ $event->name }}</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['rallycross.championship.event.destroy', $event->championship, $event], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('rallycross.championship.event.edit', [$event->championship, $event]) }}">Edit Event</a>
        {!! Form::submit('Delete Event', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />


    <h2>Entrants</h2>

    <div class="btn-group" role="group">
        <a class="btn btn-small btn-primary"
           href="{{ route('rallycross.championship.event.entrant.index', [$event->championship, $event]) }}">Manage Entrants</a>
    </div>

    <h2>Sessions</h2>

    @include('rallycross.event.show-sessions')

    <h2>Heat Results</h2>

    @include('rallycross.event.show-heat-results')

    <h2>Release Results</h2>

    @include('rallycross.event.show-release-date')



@endsection