@if (count($racesResults[$category->id]['best']['raceLap']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $racesResults[$category->id]['best']['raceLap']['best'] }}</span>
            Fastest Lap
            <span class="text-muted">
                @if (count($racesResults[$category->id]['best']['raceLap']['things']) == 1)
                    ({{ $racesResults[$category->id]['best']['raceLap']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-raceLaps">
                        ({{ count($racesResults[$category->id]['best']['raceLap']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($racesResults[$category->id]['best']['raceLap']['things']) >= 2)
        <div id="races-best-raceLaps" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($racesResults[$category->id]['best']['raceLap']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
