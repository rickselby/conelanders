@extends('page')

@section('header')
    <div class="page-header">
        <h1>Assetto Corsa Hotlaps</h1>
    </div>
@endsection

@section('content')

    <ul class="list-group list-group-condensed">
        @forelse($sessions AS $session)
            <li class="list-group-item {{ $session->isComplete() ? '' : 'list-group-item-info' }}">
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <a href="{{ route('assetto-corsa.hotlaps.session', $session) }}">
                            {{ $session->name }}
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-5">
                        {{ $session->cars->pluck('short_name')->implode(', ') }}
                    </div>
                    @if (!$session->isComplete())
                        <div class="col-xs-12 col-sm-4">
                            Session will finish on {{ \Times::userTimezone($session->finish) }}
                        </div>
                    @else
                        <div class="col-xs-12 col-sm-4">
                            @foreach($session->winners AS $driver)
                                @include('ac-hotlap.driver.name', ['driver' => $driver])
                                <br />
                            @endforeach
                        </div>
                    @endif
                </div>
            </li>
        @empty
            <li class="list-group-item">No sessions</li>
        @endforelse
    </ul>

@endsection
