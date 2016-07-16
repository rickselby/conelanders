@extends('page')

@section('header')
    <div class="page-header">
        <h1>Conelanders Racing League</h1>
        <p>
            <a class="btn btn-social btn-reddit btn-lg" href="https://www.reddit.com/r/conelanders" role="button">
                <span class="fa fa-reddit"></span> Conelanders Subreddit
            </a>
        </p>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-push-6">
            <h2>Upcoming Events</h2>
        </div>
        <div class="col-md-6 col-md-pull-6">
            <h2>Past Events</h2>
            @foreach($news AS $time => $items)

                <h3>{{ \Carbon\Carbon::createFromTimestamp($time)->format('l jS F Y, H:i e') }}</h3>
                @foreach($items AS $type)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            {{ $type['type'] }}
                        </h4>
                    </div>
                    <ul class="list-group">
                    @foreach($type['content'] AS $item)
                        {!! $item !!}
                    @endforeach
                    </ul>
                </div>
                @endforeach
            @endforeach
        </div>
    </div>
@endsection
