<?php

namespace App\Policies\RallyCross;

use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxSession;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $session)
    {
        return $user->can('rallycross-admin')
            // $session may be a string of the class name, but those cases aren't handled here anyway
            || ($session instanceof RxSession && $session->event->championship->isAdmin($user));
    }

}