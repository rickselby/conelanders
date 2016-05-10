@if (count($acResults['best']['raceLap']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $acResults['best']['raceLap']['best'] }}</span>
            Fastest Lap
            <span class="text-muted">
                @if (count($acResults['best']['raceLap']['things']) < 2)
                    ({{ $acResults['best']['raceLap']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['race']->name;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#ac-best-raceLaps">
                        ({{ count($acResults['best']['raceLap']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($acResults['best']['raceLap']['things']) >= 2)
        <div id="ac-best-raceLaps" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($acResults['best']['raceLap']['things'] AS $result)
                    <li class="list-group-item">{{ $result['race']->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
