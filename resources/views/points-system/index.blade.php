@extends('page')

@section('header')
    <div class="page-header">
        <h1>Points Systems</h1>
    </div>
@endsection

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('points-system.create') }}">Add a new system</a>
    </p>

    <ul>
        @foreach($systems as $system)
            <li>
                <a href="{{ route('points-system.show', [$system->id]) }}">
                    {{ $system->name }}
                </a>
                @if($system->default)
                    <strong><em>default</em></strong>
                @endif
            </li>
        @endforeach
    </ul>

@endsection