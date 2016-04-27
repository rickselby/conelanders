@if (count($results['best']['stage']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $results['best']['stage']['best'] }}</span>
            Stage
            <span class="text-muted">
                @if (count($results['best']['stage']['things']) < 2)
                    ({{ $results['best']['stage']['things']->reduce(function($a, $b) {
                        return ($a ? $a.', ' : '').$b['stage']->fullName;
                    }) }})
                @else
                    <a role="button" data-toggle="collapse" href="#best-stages">
                        ({{ count($results['best']['stage']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($results['best']['stage']['things']) >= 2)
        <div id="best-stages" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($results['best']['stage']['things'] AS $result)
                    <li class="list-group-item">{{ $result['stage']->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
