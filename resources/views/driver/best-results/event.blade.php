@if (count($results['best']['event']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $results['best']['event']['best'] }}</span>
            Event
            <span class="text-muted">
                @if (count($results['best']['event']['things']) < 2)
                    ({{ $results['best']['event']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['event']->fullName;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#best-events">
                        ({{ count($results['best']['event']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($results['best']['event']['things']) >= 2)
        <div id="best-events" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($results['best']['event']['things'] AS $result)
                    <li class="list-group-item">{{ $result['event']->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
