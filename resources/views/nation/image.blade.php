@if($nation)
    <img src="{{ route('nation.image', $nation) }}" alt="{{ $nation->name }}" />
@endif