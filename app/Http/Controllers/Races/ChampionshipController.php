<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\ChampionshipUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\ChampionshipRequest;
use App\Models\Races\RacesCategory;
use App\Models\Races\RacesChampionship;
use App\Models\User;

class ChampionshipController extends Controller
{

    protected $resourceAbilityMap = [
        'index' => 'index',
    ];

    public function __construct()
    {
        $this->authorizeResource(RacesChampionship::class, 'championship');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(RacesCategory $category)
    {
        return view('races.championship.create')
            ->with('category', $category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipRequest $request, RacesCategory $category)
    {
        /** @var RacesChampionship $championship */
        $championship = $category->championships()->create($request->all());
        \Notification::add('success', 'Championship "'.$championship->name.'" created');
        return \Redirect::route('races.category.championship.show', [$category, $championship]);
    }

    /**
     * Display the specified resource.
     *
     * @param  RacesChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function show(RacesCategory $category, RacesChampionship $championship)
    {
        return view('races.championship.show')
            ->with('users', User::with('driver')->whereNotIn('id', $championship->admins->pluck('id'))->get()->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE))
            ->with('championship', $championship);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  RacesChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function edit(RacesCategory $category, RacesChampionship $championship)
    {
        return view('races.championship.edit')
            ->with('championship', $championship);         
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipRequest $request
     * @param  RacesChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipRequest $request, RacesCategory $category, RacesChampionship $championship)
    {
        $championship->fill($request->all());
        $championship->save();
        \Event::fire(new ChampionshipUpdated($championship));
        \Notification::add('success', 'Championship "'.$championship->name.'" updated');
        return \Redirect::route('races.category.championship.show', [$category, $championship]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RacesChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(RacesCategory $category, RacesChampionship $championship)
    {
        if ($championship->events->count()) {
            \Notification::add('error', 'Championship "'.$championship->name.'" cannot be deleted - there are races assigned to it');
            return \Redirect::route('races.category.championship.show', [$category, $championship]);
        } else {
            $championship->delete();
            \Event::fire(new ChampionshipUpdated($championship));
            \Notification::add('success', 'Championship "'.$championship->name.'" deleted');
            return \Redirect::route('races.category.show', $category);
        }
    }
}
