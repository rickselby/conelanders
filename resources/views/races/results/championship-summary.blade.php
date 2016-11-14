@push('stylesheets')
<link href="{{ route('races.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

<div class="btn-group" role="group">
    <a class="btn btn-primary" role="button"
       href="{{ route('races.standings.drivers', $championship) }}">Driver Standings</a>
    @if(\RacesChampionships::multipleCars($championship))
        <a class="btn btn-info" role="button"
           href="{{ route('races.standings.constructors', $championship) }}">Constructors Standings</a>
    @endif
    @if(count($championship->teams))
        <a class="btn btn-info" role="button"
           href="{{ route('races.standings.teams', $championship) }}">Teams Standings</a>
    @endif
</div>

<ul class="list-group list-group-condensed">
    @forelse($championship->events AS $event)
    <li class="list-group-item {{ $event->countReleasedSessions() > 0 ? 'list-group-item-info' : '' }}">
        <div class="row">
            <div class="col-xs-4 col-sm-3 col-md-2">
                <a href="{{ route('races.results.event', [$championship, $event]) }}">
                    {{ $event->name }}
                </a>
            </div>
            @if ($event->countReleasedSessions() == 0)
                <div class="col-xs-8 col-sm-9 col-md-10">
                    @if ($event->getNextUpdate())
                        Results will be released on {{ \Times::userTimezone($event->getNextUpdate()) }}
                    @endif
                </div>
            @elseif ($event->countReleasedSessions() < count($event->sessions))
                <div class="col-xs-8 col-sm-9 col-md-10">
                    Some results released; next update will be on {{ \Times::userTimezone($event->getNextUpdate()) }}
                </div>
            @else
                <div class="col-xs-8 col-sm-4 col-lg-3">
                    @foreach(\RacesResults::getWinner($event) AS $entrant)
                        @include('races.driver.name', ['entrant' => $entrant])
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
