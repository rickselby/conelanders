<?php

namespace App\Policies\RallyCross;

use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxEvent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $event)
    {
        return $user->can('rallycross-admin')
            // $event may be a string of the class name, but those cases aren't handled here anyway
            || ($event instanceof RxEvent && $event->championship->isAdmin($user));
    }

}