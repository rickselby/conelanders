@if (count($racesResults[$category->id]['best']['race']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $racesResults[$category->id]['best']['race']['best'] }}</span>
            Race
            <span class="text-muted">
                @if (count($racesResults[$category->id]['best']['race']['things']) == 1)
                    ({{ $racesResults[$category->id]['best']['race']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-races">
                        ({{ count($racesResults[$category->id]['best']['race']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($racesResults[$category->id]['best']['race']['things']) >= 2)
        <div id="races-best-races" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($racesResults[$category->id]['best']['race']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
