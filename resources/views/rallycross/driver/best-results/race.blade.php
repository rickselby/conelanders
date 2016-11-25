@if (count($rallyCrossResults['best']['race']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $rallyCrossResults['best']['race']['best'] }}</span>
            Race
            <span class="text-muted">
                @if (count($rallyCrossResults['best']['race']['things']) == 1)
                    ({{ $rallyCrossResults['best']['race']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-races">
                        ({{ count($rallyCrossResults['best']['race']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($rallyCrossResults['best']['race']['things']) >= 2)
        <div id="races-best-races" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($rallyCrossResults['best']['race']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
