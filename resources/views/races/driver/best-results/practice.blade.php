@if (count($acResults['best']['practice']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $acResults['best']['practice']['best'] }}</span>
            Practice
            <span class="text-muted">
                @if (count($acResults['best']['practice']['things']) == 1)
                    ({{ $acResults['best']['practice']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#ac-best-practices">
                        ({{ count($acResults['best']['practice']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($acResults['best']['practice']['things']) >= 2)
        <div id="ac-best-practices" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($acResults['best']['practice']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
