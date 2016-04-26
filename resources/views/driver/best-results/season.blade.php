@if (count($results['best']['season']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $results['best']['season']['best'] }}</span>
            Season
            <span class="text-muted">
                @if (count($results['best']['season']['things']) < 2)
                    ({{ $results['best']['season']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['season']->fullName;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#best-seasons">
                        ({{ count($results['best']['season']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($results['best']['season']['things']) >= 2)
        <div id="best-seasons" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($results['best']['season']['things'] AS $result)
                    <li class="list-group-item">{{ $result['season']->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
