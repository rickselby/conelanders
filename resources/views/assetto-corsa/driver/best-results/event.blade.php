@if (count($acResults['best']['event']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $acResults['best']['event']['best'] }}</span>
            Event
            <span class="text-muted">
                @if (count($acResults['best']['event']['things']) < 2)
                    ({{ $acResults['best']['event']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['event']->fullName;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#ac-best-events">
                        ({{ count($acResults['best']['event']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($acResults['best']['event']['things']) >= 2)
        <div id="ac-best-events" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($acResults['best']['event']['things'] AS $result)
                    <li class="list-group-item">{{ $result['event']->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
