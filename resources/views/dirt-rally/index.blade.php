@extends('page')

@section('header')
    <div class="jumbotron">
        <h1>Dirt Rally</h1>
    </div>
@endsection

@section('content')

    @foreach($championships AS $championship)
        <h2>{{ $championship->name }}</h2>
        <div class="btn-group btn-group-before-panel" role="group">
            <a class="btn btn-primary"  role="button"
               href="{{ route('dirt-rally.standings.championship', $championship) }}">Driver Standings</a>
            <a class="btn btn-info"  role="button"
               href="{{ route('dirt-rally.nationstandings.championship', $championship) }}">Nation Standings</a>
            <a class="btn btn-info"  role="button"
               href="{{ route('dirt-rally.times.championship', $championship) }}">Total Times</a>
        </div>

        <div class="panel panel-default">

            @forelse($championship->getOrderedSeasons() AS $season)
            <ul class="list-group list-group-condensed">
                <li class="list-group-item {{ $season->isComplete() ? 'list-group-item-season' : '' }}">
                    <div class="row">
                        <div class="col-xs-6 col-sm-4 col-md-3">
                            <a href="{{ route('dirt-rally.standings.season', [$championship, $season]) }}">
                                {{ $season->name }}
                            </a>
                        </div>
                        <div class="col-xs-6 col-sm-8 col-md-9">
                            @if ($season->isComplete())
                                @foreach(\DirtRallyResults::getSeasonWinner($season) AS $driver)
                                    @include('nation.image', ['nation' => $driver->nation])
                                    <a href="{{ route('driver.show', $driver) }}">
                                        {{ $driver->name }}
                                    </a>
                                    <br />
                                @endforeach
                            @else
                                Season will be completed {{ $season->closes->format('\o\n Y-m-d \a\t H:i:s e') }}
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
                <ul class="list-group list-group-condensed list-group-">
                @foreach($season->events AS $event)
                        <li class="list-group-item {{ $event->isComplete() ? 'list-group-item-info' : '' }} list-group-item-indent">
                            <div class="row">
                                <div class="col-xs-6 col-sm-4 col-md-3">
                                    <a href="{{ route('dirt-rally.standings.event', [$championship, $season, $event]) }}">
                                        {{ $event->name }}
                                    </a>
                                </div>
                                <div class="col-xs-6 col-sm-8 col-md-9">
                                    @if ($event->isComplete())
                                        @foreach(\DirtRallyResults::getEventWinner($event) AS $driver)
                                            @include('nation.image', ['nation' => $driver->nation])
                                            <a href="{{ route('driver.show', $driver) }}">
                                                {{ $driver->name }}
                                            </a>
                                            <br />
                                        @endforeach
                                    @else
                                        Event will be completed {{ $event->closes->format('\o\n Y-m-d \a\t H:i:s e') }}
                                    @endif
                                </div>
                            </div>
                        </li>
                @endforeach
                </ul>
            @empty
                <p>No sessions</p>
            @endforelse

        </div>

    @endforeach

@endsection