@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li class="active">Total Time</li>
    </ol>
@endsection

@section('content')

    <ul>
        @foreach($championships AS $championship)
            <li>
                <a href="{{ route('times.championship', [$championship->id]) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection