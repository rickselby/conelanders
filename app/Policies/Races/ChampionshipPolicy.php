<?php

namespace App\Policies\Races;

use App\Models\Races\RacesChampionship;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChampionshipPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $arguments)
    {
        if ($user->can('races-admin')) {
            return true;
        }
    }

    public function view(User $user, RacesChampionship $championship)
    {
        return $championship->isAdmin($user);
    }

    public function createEvent(User $user, RacesChampionship $championship)
    {
        return $championship->isAdmin($user);
    }

    public function manageEntrants(User $user, RacesChampionship $championship)
    {
        return $championship->isAdmin($user);
    }

    public function manageTeams(User $user, RacesChampionship $championship)
    {
        return $championship->isAdmin($user);
    }

}