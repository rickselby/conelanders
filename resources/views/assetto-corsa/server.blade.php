@extends('page')

@section('header')
    <div class="page-header">
        <h1>Assetto Corsa Server Management</h1>
    </div>
@endsection

@section('content')

    <div class="alert alert-info">
        <strong>Server status:</strong>
        <span id="server-status">loading...</span>
    </div>
    
    <h3>Start/Stop Server</h3>

    <button id="start" type="button" class="btn btn-success">Start Server</button>
    <button id="stop" type="button" class="btn btn-danger">Stop Server</button>

    <h3>Update Server Configuration</h3>
    <p class="alert alert-warning">
        File uploads have gone; open the configs below to edit the files.
        You can always copy-paste your own files over the whole thing if you want.
    </p>

    {!! Form::open(['route' => 'assetto-corsa.server.update-config', 'class' => 'form-horizontal']) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" href="#entryList">
                    Entry List
                </a> <span class="caret"></span>
            </h3>
        </div>
        <div class="panel-collapse collapse" id="entryList" role="tabpanel">
            <div class="panel-body">
                <textarea name="entry-list" class="form-control" rows="40">{{ $entryList }}</textarea>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" href="#serverConfig">
                    Server Config
                </a> <span class="caret"></span>
            </h3>
        </div>
        <div class="panel-collapse collapse" id="serverConfig" role="tabpanel">
            <div class="panel-body">
                <textarea name="server-config" class="form-control" rows="40">{{ $serverConfig }}</textarea>
            </div>
        </div>
    </div>

        {!! Form::submit('Update configuration', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}

    <script type="text/javascript">
        $( document ).ready(function() {

            getStatus();

            // We can attach the `fileselect` event to all file inputs on the page
            $(document).on('change', ':file', function() {
                var input = $(this);
                var numFiles = input.get(0).files ? input.get(0).files.length : 1;
                var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });

            $(':file').on('fileselect', function(event, numFiles, label) {

                var input = $(this).parents('.input-group').find(':text');
                var log = numFiles > 1 ? numFiles + ' files selected' : label;

                if( input.length ) {
                    input.val(log);
                }
            });

            $('#start').click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('assetto-corsa.server.start') }}",
                    async: true
                });
            })
            $('#stop').click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('assetto-corsa.server.stop') }}",
                    async: true
                });
            })

        });

        function getStatus() {
            $.ajax({
                type: "GET",
                url: "{{ route('assetto-corsa.server.status') }}",
                async: true,
                success: function(data) {
                    $('#server-status').html(data);
                    setTimeout(function(){getStatus();}, 10000);
                }
            });
        }

    </script>

@endsection
