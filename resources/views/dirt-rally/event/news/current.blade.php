@foreach($events AS $event)
    <li class="list-group-item">

        <a href="{{ route('dirt-rally.event', [$event->season->championship, $event->season, $event]) }}">{{ $event->fullName }}</a>
        is running until {{ $event->closes->format('l jS F Y, H:i e') }}
    </li>
@endforeach
