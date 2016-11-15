<?php

namespace App\Http\Controllers\RallyCross;

use App\Http\Controllers\Controller;
use App\Http\Requests\RallyCross\ChampionshipAdminRequest;
use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxChampionshipAdmin;
use App\Models\User;

class ChampionshipAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:rallycross-admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipAdminRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipAdminRequest $request, RxChampionship $championship)
    {
        $championship->admins()->attach($request->get('user'));

        \Notification::add('success', 'Admin added');
        return \Redirect::route('rallycross.championship.show', $championship);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RxChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(RxChampionship $championship, User $admin)
    {
        $championship->admins()->detach($admin->id);
        \Notification::add('success', 'Admin removed');
        return \Redirect::route('rallycross.championship.show', $championship);
    }
}