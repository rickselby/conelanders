<?php

namespace App\Providers;

use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;
use App\Policies;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        RxChampionship::class => Policies\RallyCross\ChampionshipPolicy::class,
        RxEvent::class => Policies\RallyCross\EventPolicy::class,
        RxSession::class => Policies\RallyCross\SessionPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
    }
}
