@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('season.show', ['id' => $stage->event->season->id]) }}">{{ $stage->event->season->name }}</a>:
            <a href="{{ route('event.show', ['id' => $stage->event->id]) }}">{{ $stage->event->name }}</a>:
            {{ $stage->name }}
        </h1>
    </div>
@endsection

@section('content')

    <h2>Results?</h2>

@endsection