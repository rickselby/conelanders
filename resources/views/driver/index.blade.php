@extends('page')

@section('content')

    <h2>Drivers</h2>

    <ul>
        @foreach($drivers as $d)
            <li>
                <a href="{{ route('driver.show', $d) }}">
                    {{ $d->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection