@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $session->event->championship->name }}: {{ $session->event->name }}: {{ $session->name }}</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['rallycross.championship.event.session.destroy', $session->event->championship, $session->event, $session], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('rallycross.championship.event.session.edit', [$session->event->championship, $session->event, $session]) }}">Edit Session</a>
        {!! Form::submit('Delete Session', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />

    @include('rallycross.session.show-add-result')

    @include('rallycross.session.show-entrants')

    @include('rallycross.session.show-points')

    @include('rallycross.session.show-show')

@endsection