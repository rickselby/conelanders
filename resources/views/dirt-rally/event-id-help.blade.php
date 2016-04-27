@extends('page')

@section('header')
    <div class="header">
        <h1>Event ID Help</h1>
    </div>
@endsection

@section('content')
    <p>
        Go to the <a href="https://www.dirtgame.com/uk/leagues/league/25800/conelanders">league website</a>.
        View the source of the page (normally right-click and select "view source", but might be different depending on
        your browser).
    </p>
    <p>
        Search (normally Ctrl-F) for <strong>data-ng-model="eventId"</strong>.
        The second occurrence on the page has a list of events and their IDs.
        For example:
    </p>
    <pre>&lt;select class="event_id" data-ng-model="eventId" id="eventid" name="eventid"&gt;&lt;option value="46368"&gt;Event 1&lt;/option&gt;
    &lt;option value="46369"&gt;Event 2&lt;/option&gt;
    &lt;option value="46370"&gt;Event 3&lt;/option&gt;
&lt;/select&gt;</pre>
    <p>In this example, event 1 is 46368, event 2 is 46369, and event 3 is 46370.</p>

@endsection