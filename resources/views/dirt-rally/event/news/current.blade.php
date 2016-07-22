@foreach($events AS $event)
    <li class="list-group-item">
        @if ($event->playlist)
            <span class="pull-right">
                @include('playlist.icon', ['playlist' => $event->playlist])
            </span>
        @endif
        <a href="{{ route('dirt-rally.event', [$event->season->championship, $event->season, $event]) }}">{{ $event->fullName }}</a>
        is running until {{ \Times::userTimezone($event->closes) }}
    </li>
@endforeach
