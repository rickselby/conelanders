
@include('nation.image', ['nation' => $driver->nation])
<a href="{{ route('driver.show', $driver) }}">
    {{ $driver->name }}
</a>
