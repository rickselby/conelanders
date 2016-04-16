@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li class="active">Standings</li>
    </ol>
@endsection

@section('content')

    <ul>
        @foreach($systems AS $system)
            <li>
                <a href="{{ route('standings.system', [$system->id]) }}">
                    {{ $system->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection