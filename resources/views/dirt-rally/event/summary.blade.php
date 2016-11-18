
<div class="panel panel-default">
    <ul class="list-group">
        @foreach($event->stages AS $stage)
            <li class="list-group-item">
                <a href="{{ route('dirt-rally.standings.stage', [$event->season->championship, $event->season, $event, $stage]) }}" class="tablesorter-noSort">
                    <strong>{{ $stage->ss }}:</strong>
                    {{ $stage->stageInfo->fullName }} : {{ $stage->time_of_day }} / {{ $stage->weather }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
