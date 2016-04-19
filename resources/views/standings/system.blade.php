@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('standings.index') }}">Standings</a></li>
        <li class="active">{{ $system->name }}</li>
    </ol>
@endsection

@section('content')

    <ul>
        @foreach($championships AS $championship)
            <li>
                <a href="{{ route('standings.championship', [$system->id, $championship]) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection