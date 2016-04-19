@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('nationstandings.index') }}">Nations Standings</a></li>
        <li class="active">{{ $system->name }}</li>
    </ol>
@endsection

@section('content')

    <ul>
        @foreach($championships AS $championship)
            <li>
                <a href="{{ route('nationstandings.championship', [$system->id, $championship]) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection