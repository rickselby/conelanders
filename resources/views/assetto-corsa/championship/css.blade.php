/** Teams come first, so entrants can override them **/
@foreach($championship->teams AS $team)
.team-{{ $team->id }} { {{ $team->css }} }
@endforeach

@foreach($championship->entrants AS $entrant)
.entrant-{{ $entrant->id }} { {{ $entrant->css }} }
@endforeach
