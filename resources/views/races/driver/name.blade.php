@include('nation.image', ['nation' => $entrant->driver->nation])
@include('races.driver.badge', ['driver' => $entrant])
@if ($entrant->rookie)
    <span class="badge pull-right">R</span>
@endif
<a href="{{ route('driver.show', $entrant->driver) }}">
    {{ $entrant->driver->name }}
</a>
