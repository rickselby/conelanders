@extends('page')

@section('content')

    @foreach($pages AS $page)
        {!! $page !!}
    @endforeach

@endsection
