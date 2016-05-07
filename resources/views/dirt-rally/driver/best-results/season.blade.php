@if (count($dirtResults['best']['season']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $dirtResults['best']['season']['best'] }}</span>
            Season
            <span class="text-muted">
                @if (count($dirtResults['best']['season']['things']) < 2)
                    ({{ $dirtResults['best']['season']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['season']->fullName;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#best-seasons">
                        ({{ count($dirtResults['best']['season']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($dirtResults['best']['season']['things']) >= 2)
        <div id="best-seasons" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($dirtResults['best']['season']['things'] AS $result)
                    <li class="list-group-item">{{ $result['season']->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
