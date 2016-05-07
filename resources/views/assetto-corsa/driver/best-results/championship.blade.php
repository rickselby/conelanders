@if (count($acResults['best']['championship']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $acResults['best']['championship']['best'] }}</span>
            Championship
            <span class="text-muted">
                @if (count($acResults['best']['championship']['things']) < 2)
                    ({{ $acResults['best']['championship']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['championship']->name;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#ac-best-championships">
                        ({{ count($acResults['best']['championship']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($acResults['best']['championship']['things']) >= 2)
        <div id="ac-best-championships" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($acResults['best']['championship']['things'] AS $result)
                    <li class="list-group-item">{{ $result['championship']->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
