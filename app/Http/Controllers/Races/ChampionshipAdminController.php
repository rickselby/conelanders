<?php

namespace App\Http\Controllers\Races;

use App\Http\Controllers\Controller;
use App\Http\Requests\Races\ChampionshipAdminRequest;
use App\Models\Races\RacesCategory;
use App\Models\Races\RacesChampionship;
use App\Models\User;

class ChampionshipAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:races-admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipAdminRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipAdminRequest $request, RacesCategory $category, RacesChampionship $championship)
    {
        $championship->admins()->attach($request->get('user'));

        \Notification::add('success', 'Admin added');
        return \Redirect::route('races.category.championship.show', [$championship->category, $championship]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RacesChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(RacesCategory $category, RacesChampionship $championship, User $admin)
    {
        $championship->admins()->detach($admin->id);
        \Notification::add('success', 'Admin removed');
        return \Redirect::route('races.category.championship.show', [$championship->category, $championship]);
    }
}