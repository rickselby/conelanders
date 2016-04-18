@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li class="active">Nations Standings</li>
    </ol>
@endsection

@section('content')

    <ul>
        @foreach($systems AS $system)
            <li>
                <a href="{{ route('nationstandings.system', [$system->id]) }}" class="tablesorter-noSort">
                    {{ $system->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection