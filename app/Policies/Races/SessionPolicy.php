<?php

namespace App\Policies\Races;

use App\Models\Races\RacesSession;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $session)
    {
        return $user->can('races-admin')
            // $session may be a string of the class name, but those cases aren't handled here anyway
            || ($session instanceof RacesSession && $session->event->championship->isAdmin($user));
    }

}