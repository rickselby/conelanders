@if (count($dirtResults['best']['event']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $dirtResults['best']['event']['best'] }}</span>
            Event
            <span class="text-muted">
                @if (count($dirtResults['best']['event']['things']) == 1)
                    ({{ $dirtResults['best']['event']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#best-events">
                        ({{ count($dirtResults['best']['event']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($dirtResults['best']['event']['things']) >= 2)
        <div id="best-events" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($dirtResults['best']['event']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
