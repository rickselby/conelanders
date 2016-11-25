@extends('page')

@section('header')

    <div class="page-header">
        <h1>Your Championships</h1>
    </div>

@endsection

@section('content')

    @if (count($championships))
        @foreach($championships AS $championship)
            {!! $championship !!}
        @endforeach
    @else
        <p>You have no championships you can manage.</p>
    @endif

@endsection