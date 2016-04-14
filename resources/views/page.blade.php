<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Conelanders Dirt Rally League</title>

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
        <!-- Bootstrap -->
        <link href="{{ asset('bower/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/page.css') }}" rel="stylesheet">
        <link href="{{ asset('bower/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('bower/bootstrap-social/bootstrap-social.css') }}" rel="stylesheet">
        <link href="{{ asset('bower/tablesorter/dist/css/theme.bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="{{ asset('bower/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('bower/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('bower/tablesorter/dist/js/jquery.tablesorter.min.js') }}"></script>
        <script src="{{ asset('bower/tablesorter/dist/js/jquery.tablesorter.widgets.min.js') }}"></script>
        <script src="{{ asset('bower/moment/min/moment.min.js') }}"></script>
        <script src="{{ asset('bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
    </head>
    <body>
        <div class="container">
            @include('navbar')
            @yield('header')
            {!! Notification::showAll() !!}
            @include('formResponse')
            @yield('content')
        </div>
    </body>
</html>
