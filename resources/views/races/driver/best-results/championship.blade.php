@if (count($racesResults[$category->id]['best']['championship']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $racesResults[$category->id]['best']['championship']['best'] }}</span>
            Championship
            <span class="text-muted">
                @if (count($racesResults[$category->id]['best']['championship']['things']) == 1)
                    ({{ $racesResults[$category->id]['best']['championship']['things']->first()->name }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-championships">
                        ({{ count($racesResults[$category->id]['best']['championship']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($racesResults[$category->id]['best']['championship']['things']) >= 2)
        <div id="races-best-championships" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($racesResults[$category->id]['best']['championship']['things'] AS $result)
                    <li class="list-group-item">{{ $result->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
