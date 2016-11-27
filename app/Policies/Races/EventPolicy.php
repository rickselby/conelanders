<?php

namespace App\Policies\Races;

use App\Models\Races\RacesEvent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $event)
    {
        return $user->can('races-admin')
            // $event may be a string of the class name, but those cases aren't handled here anyway
            || ($event instanceof RacesEvent && $event->championship->isAdmin($user));
    }

}