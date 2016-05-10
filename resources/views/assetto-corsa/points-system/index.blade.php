@extends('page')

@section('header')
    <div class="page-header">
        <h1>Points Systems</h1>
    </div>
@endsection

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('assetto-corsa.points-system.create') }}">Add a new system</a>
    </p>

    <ul>
        @foreach($systems as $system)
            <li>
                <a href="{{ route('assetto-corsa.points-system.show', $system) }}">
                    {{ $system->name }}
                </a>
                @if($system->default)
                    <strong><em>default</em></strong>
                @endif
            </li>
        @endforeach
    </ul>

@endsection