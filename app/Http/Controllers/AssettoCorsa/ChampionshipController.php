<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Events\AssettoCorsa\ChampionshipUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssettoCorsa\ChampionshipRequest;
use App\Models\AssettoCorsa\AcChampionship;

class ChampionshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:assetto-corsa-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('assetto-corsa.championship.index')
            ->with('championships', AcChampionship::with('events')->get()->sortBy('ends'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('assetto-corsa.championship.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipRequest $request)
    {
        /** @var AcChampionship $championship */
        $championship = AcChampionship::create($request->all());
        \Notification::add('success', 'Championship "'.$championship->name.'" created');
        return \Redirect::route('assetto-corsa.championship.show', $championship);
    }

    /**
     * Display the specified resource.
     *
     * @param  AcChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function show(AcChampionship $championship)
    {
        return view('assetto-corsa.championship.show')
            ->with('championship', $championship);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  AcChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function edit(AcChampionship $championship)
    {
        return view('assetto-corsa.championship.edit')
            ->with('championship', $championship);         
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipRequest $request
     * @param  AcChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipRequest $request, AcChampionship $championship)
    {
        $championship->fill($request->all());
        $championship->save();
        \Event::fire(new ChampionshipUpdated($championship));
        \Notification::add('success', 'Championship "'.$championship->name.'" updated');
        return \Redirect::route('assetto-corsa.championship.show', $championship);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AcChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcChampionship $championship)
    {
        if ($championship->events->count()) {
            \Notification::add('error', 'Championship "'.$championship->name.'" cannot be deleted - there are races assigned to it');
            return \Redirect::route('assetto-corsa.championship.show', $championship);
        } else {
            $championship->delete();
            \Event::fire(new ChampionshipUpdated($championship));
            \Notification::add('success', 'Championship "'.$championship->name.'" deleted');
            return \Redirect::route('assetto-corsa.championship.index');
        }
    }
}
