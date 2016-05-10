@if (count($acResults['best']['race']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $acResults['best']['race']['best'] }}</span>
            Race
            <span class="text-muted">
                @if (count($acResults['best']['race']['things']) < 2)
                    ({{ $acResults['best']['race']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['race']->name;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#ac-best-races">
                        ({{ count($acResults['best']['race']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($acResults['best']['race']['things']) >= 2)
        <div id="ac-best-races" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($acResults['best']['race']['things'] AS $result)
                    <li class="list-group-item">{{ $result['race']->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
