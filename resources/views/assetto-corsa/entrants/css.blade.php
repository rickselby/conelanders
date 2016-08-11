@foreach($championship->entrants AS $entrant)
.entrant-{{ $entrant->id }} { {{ $entrant->css }} }
@endforeach
