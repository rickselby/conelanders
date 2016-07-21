@foreach($events AS $event)
    <li class="list-group-item">
        @if ($event->playlist)
            <span class="pull-right">
                @include('playlist.icon', ['playlist' => $event->playlist])
            </span>
        @endif
        <a href="{{ route('dirt-rally.standings.event', [$event->season->championship, $event->season, $event]) }}">{{ $event->fullName }}</a>
        is now complete.
    </li>
@endforeach
