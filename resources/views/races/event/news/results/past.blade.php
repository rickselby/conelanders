@foreach($events AS $event)
    <li class="list-group-item">

        Results for sessions at
        <a href="{{ route('races.results.event', [$event['event']->championship, $event['event']]) }}">{{ $event['event']->fullName }}</a>
        have been released:
    </li>
    @foreach($event['sessions'] AS $session)
        <li class="list-group-item list-group-item-indent">
            @if ($session->playlist)
                <span class="pull-right">
                    @include('playlist.icon', ['playlist' => $session->playlist])
                </span>
            @endif
            {{ $session->name }}
        </li>
    @endforeach
    <li class="list-group-item">
        @if ($event['event']->completeAt == $event['sessions'][0]->release)
            The event is now complete, and results are available.
        @else
            The event results have not yet been released; there are more session results to come.
        @endif
    </li>
@endforeach
