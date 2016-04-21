@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li class="active">Drivers</li>
    </ol>
@endsection

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