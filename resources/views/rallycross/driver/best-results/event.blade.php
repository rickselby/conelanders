@if (count($rallyCrossResults['best']['event']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $rallyCrossResults['best']['event']['best'] }}</span>
            Event
            <span class="text-muted">
                @if (count($rallyCrossResults['best']['event']['things']) == 1)
                    ({{ $rallyCrossResults['best']['event']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-events">
                        ({{ count($rallyCrossResults['best']['event']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($rallyCrossResults['best']['event']['things']) >= 2)
        <div id="races-best-events" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($rallyCrossResults['best']['event']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
