@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $session->name }}: {{ $session->cars->pluck('short_name')->implode(', ') }}</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.hotlaps.session.destroy', $session], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('assetto-corsa.hotlaps.session.edit', [$session]) }}">Edit Session</a>
        {!! Form::submit('Delete Session', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />

    @include('ac-hotlap.session.show-add-result')

    @include('ac-hotlap.session.show-entrants')

@endsection