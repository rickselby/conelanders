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

    <div class="responsive-iframe-container">
        <iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&hl=en&bgcolor=%23FFFFFF&src=mvmd5r13c9ui7sdqh9afg60l7s%40group.calendar.google.com&color=%2329527A{!! Auth::check() && Auth::user()->timezone ? '&ctz='.Auth::user()->timezone : '' !!}"
                style="border-width:0" frameborder="0" scrolling="no"></iframe>
    </div>

@endsection