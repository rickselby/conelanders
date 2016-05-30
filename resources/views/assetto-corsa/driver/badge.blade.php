<span class="badge {{ $driver->colour == 'white' ? 'driver-number-white' : 'driver-number' }}"
      style="background-color: {{ $driver->colour }}; color: {{ $driver->colour2 or 'white' }};">
    {{ $driver->number }}
</span>
