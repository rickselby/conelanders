@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            Conelanders Racing League
            <a class="btn btn-social btn-reddit btn-lg pull-right" href="https://www.reddit.com/r/conelanders" role="button">
                <span class="fa fa-reddit"></span> Conelanders Subreddit
            </a>
        </h1>
    </div>

@endsection

@section('content')

    @if(count($signups))
        @include('races.signup')
    @endif

    <div class="row">
        <div class="col-md-6 col-md-push-6">
            <h2>Current Events</h2>
            @foreach($currentNews AS $items)
                @foreach($items AS $type)
                    <div class="panel panel-primary">
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
            <h2>Upcoming Events</h2>
            @foreach($upcomingNews AS $time => $items)

                <h3>{{ \Times::userTimezone(\Carbon\Carbon::createFromTimestamp($time)) }}</h3>
                @foreach($items AS $type)
                    <div class="panel panel-info">
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
        <div class="col-md-6 col-md-pull-6">
            <h2>Past Events</h2>
            @foreach($pastNews AS $time => $items)

                <h3>{{ \Times::userTimezone(\Carbon\Carbon::createFromTimestamp($time)) }}</h3>
                @foreach($items AS $type)
                <div class="panel panel-info">
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
