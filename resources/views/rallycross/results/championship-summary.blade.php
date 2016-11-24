
<div class="btn-group" role="group">
    <a class="btn btn-primary" role="button"
       href="{{ route('rallycross.standings.drivers', $championship) }}">Driver Standings</a>
    @if(\RXChampionships::multipleCars($championship))
        <a class="btn btn-info" role="button"
           href="{{ route('rallycross.standings.constructors', $championship) }}">Constructors Standings</a>
    @endif
</div>

<ul class="list-group list-group-condensed">
    @forelse($championship->events AS $event)
    <li class="list-group-item {{ $event->canBeReleased() ? '' : 'list-group-item-info' }}">
        <div class="row">
            <div class="col-xs-4 col-sm-3 col-md-2">
                <a href="{{ route('rallycross.results.event', [$championship, $event]) }}">
                    {{ $event->name }}
                </a>
            </div>
            @if (!$event->canBeReleased())
                <div class="col-xs-8 col-sm-9 col-md-10">
                    @if ($event->release)
                        Results will be released on {{ \Times::userTimezone($event->release) }}
                    @endif
                </div>
            @else
                <div class="col-xs-8 col-sm-4 col-lg-3">
                    @foreach(\RXResults::getWinner($event) AS $entrant)
                        @include('rallycross.driver.name', ['driver' => $entrant->driver])
                        <br />
                    @endforeach
                </div>
                <div class="col-xs-12 col-md-6 col-lg-7 text-right">
                    @foreach($event->sessions AS $session)
                        @if ($session->playlist)
                            <a class="btn btn-social btn-xs btn-youtube" href="{{ $session->playlist->link }}">
                                <span class="fa fa-youtube-play"></span>
                                {{ $session->shortName }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </li>
    @empty
    <li class="list-group-item">No sessions</li>
    @endforelse
</ul>
