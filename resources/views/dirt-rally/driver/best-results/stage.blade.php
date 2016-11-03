@if (count($dirtResults['best']['stage']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $dirtResults['best']['stage']['best'] }}</span>
            Stage
            <span class="text-muted">
                @if (count($dirtResults['best']['stage']['things']) == 1)
                    ({{ $dirtResults['best']['stage']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#best-stages">
                        ({{ count($dirtResults['best']['stage']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($dirtResults['best']['stage']['things']) >= 2)
        <div id="best-stages" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($dirtResults['best']['stage']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
