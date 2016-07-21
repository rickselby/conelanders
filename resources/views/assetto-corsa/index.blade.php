@extends('page')

@section('header')
    <div class="page-header">
        <h1>Assetto Corsa</h1>
    </div>
@endsection

@section('content')
    @foreach($championships AS $championship)
        <h2>{{ $championship->name }}</h2>
        <div class="btn-group" role="group">
            <a class="btn btn-primary"  role="button"
               href="{{ route('assetto-corsa.standings.championship', $championship) }}">Driver Standings</a>
        </div>

        <ul class="list-group list-group-condensed">
            @forelse($championship->events AS $event)
                <li class="list-group-item {{ $event->countReleasedSessions() > 0 ? 'list-group-item-info' : '' }}">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3">
                            <a href="{{ route('assetto-corsa.standings.event', [$championship, $event]) }}">
                                {{ $event->name }}
                            </a>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                            @if ($event->countReleasedSessions() == 0)
                                @if ($event->getNextUpdate())
                                    Results will be released {{ $event->getNextUpdate()->format('\o\n Y-m-d \a\t H:i:s e') }}
                                @endif
                            @elseif ($event->countReleasedSessions() < count($event->sessions))
                                Some results released; next update will be  {{ $event->getNextUpdate()->format('\o\n Y-m-d \a\t H:i:s e') }}
                            @else
                                @foreach(\ACResults::getWinner($event) AS $entrant)
                                    @include('assetto-corsa.driver.name', ['entrant' => $entrant])
                                    <br />
                                @endforeach
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-5 text-right">
                            @foreach($event->sessions AS $session)
                                @if ($session->playlist)
                                    <a class="btn btn-social btn-xs btn-youtube" href="{{ $session->playlist->link }}">
                                        <span class="fa fa-youtube-play"></span>
                                        {{ $session->shortName }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </li>
            @empty
                <li>No sessions</li>
            @endforelse
        </ul>

    @endforeach

@endsection
