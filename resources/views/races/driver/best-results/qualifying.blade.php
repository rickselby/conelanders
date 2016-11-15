@if (count($racesResults[$category->id]['best']['qualifying']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $racesResults[$category->id]['best']['qualifying']['best'] }}</span>
            Qualifying
            <span class="text-muted">
                @if (count($racesResults[$category->id]['best']['qualifying']['things']) == 1)
                    ({{ $racesResults[$category->id]['best']['qualifying']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-qualifyings">
                        ({{ count($racesResults[$category->id]['best']['qualifying']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($racesResults[$category->id]['best']['qualifying']['things']) >= 2)
        <div id="races-best-qualifyings" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($racesResults[$category->id]['best']['qualifying']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
