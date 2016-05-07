@if (count($acResults['best']['qualifying']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $acResults['best']['qualifying']['best'] }}</span>
            Qualifying
            <span class="text-muted">
                @if (count($acResults['best']['qualifying']['things']) < 2)
                    ({{ $acResults['best']['qualifying']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['race']->name;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#ac-best-qualifyings">
                        ({{ count($acResults['best']['qualifying']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($acResults['best']['qualifying']['things']) >= 2)
        <div id="ac-best-qualifyings" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($acResults['best']['qualifying']['things'] AS $result)
                    <li class="list-group-item">{{ $result['qualifying']->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
