@if (count($racesResults[$category->id]['best']['event']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $racesResults[$category->id]['best']['event']['best'] }}</span>
            Event
            <span class="text-muted">
                @if (count($racesResults[$category->id]['best']['event']['things']) == 1)
                    ({{ $racesResults[$category->id]['best']['event']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-events">
                        ({{ count($racesResults[$category->id]['best']['event']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($racesResults[$category->id]['best']['event']['things']) >= 2)
        <div id="races-best-events" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($racesResults[$category->id]['best']['event']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
