@foreach($championship['seasons'] AS $season)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" href="#season-{{ $season['season']->id }}">
                    {{ $season['season']->name }}
                </a> <span class="caret"></span>
                <span class="position pull-right">{{ $season['position'] ?: 'In Progress' }}</span>
            </h4>
        </div>
        <div id="season-{{ $season['season']->id }}" class="panel-collapse collapse" role="tabpanel">
            <div class="panel-body">

                @include('dirt-rally.driver.all-results.events')

            </div>
        </div>
    </div>
@endforeach
