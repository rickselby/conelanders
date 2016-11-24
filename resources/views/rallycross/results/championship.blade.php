@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $championship->name }}</h1>
    </div>
@endsection

@section('content')

    @include('rallycross.results.championship-summary')

@endsection
