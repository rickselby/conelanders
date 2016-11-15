<?php

namespace App\Http\Controllers\RallyCross;

use App\Events\RallyCross\ChampionshipUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\RallyCross\ChampionshipRequest;
use App\Models\RallyCross\RxChampionship;
use App\Models\User;

class ChampionshipController extends Controller
{
    protected $resourceAbilityMap = [
        'index' => 'index',
    ];

    public function __construct()
    {
        $this->authorizeResource(RxChampionship::class, 'championship');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rallycross.championship.index')
            ->with('championships', RxChampionship::with('events')->get()->sortBy('ends'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rallycross.championship.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipRequest $request)
    {
        /** @var RxChampionship $championship */
        $championship = RxChampionship::create($request->all());
        \Notification::add('success', 'Championship "'.$championship->name.'" created');
        return \Redirect::route('rallycross.championship.show', $championship);
    }

    /**
     * Display the specified resource.
     *
     * @param  RxChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function show(RxChampionship $championship)
    {
        return view('rallycross.championship.show')
            ->with('users', User::with('driver')->whereNotIn('id', $championship->admins->pluck('id'))->get()->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE))
            ->with('championship', $championship);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  RxChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function edit(RxChampionship $championship)
    {
        return view('rallycross.championship.edit')
            ->with('championship', $championship);         
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipRequest $request
     * @param  RxChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipRequest $request, RxChampionship $championship)
    {
        $championship->fill($request->all());
        $championship->save();
        \Event::fire(new ChampionshipUpdated($championship));
        \Notification::add('success', 'Championship "'.$championship->name.'" updated');
        return \Redirect::route('rallycross.championship.show', $championship);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RxChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(RxChampionship $championship)
    {
        if ($championship->events->count()) {
            \Notification::add('error', 'Championship "'.$championship->name.'" cannot be deleted - there are races assigned to it');
            return \Redirect::route('rallycross.championship.show', $championship);
        } else {
            $championship->delete();
            \Event::fire(new ChampionshipUpdated($championship));
            \Notification::add('success', 'Championship "'.$championship->name.'" deleted');
            return \Redirect::route('rallycross.championship.index');
        }
    }
}
