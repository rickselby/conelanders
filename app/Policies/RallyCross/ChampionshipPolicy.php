<?php

namespace App\Policies\RallyCross;

use App\Models\RallyCross\RxChampionship;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChampionshipPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $arguments)
    {
        if ($user->can('rallycross-admin')) {
            return true;
        }
    }

    public function view(User $user, RxChampionship $championship)
    {
        return $championship->isAdmin($user);
    }

    public function createEvent(User $user, RxChampionship $championship)
    {
        return $championship->isAdmin($user);
    }

}