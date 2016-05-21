<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirtRally\ChampionshipRequest;
use App\Models\DirtRally\DirtChampionship;

class ChampionshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dirt-rally.championship.index')
            ->with('championships', DirtChampionship::with('seasons')->get()->sortBy('closes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dirt-rally.championship.create')
            ->with('sequences', \PointSequences::forSelect());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipRequest $request)
    {
        /** @var DirtChampionship $championship */
        $championship = DirtChampionship::create($request->all());
        \Notification::add('success', 'Championship "'.$championship->name.'" created');
        return \Redirect::route('dirt-rally.championship.show', $championship);
    }

    /**
     * Display the specified resource.
     *
     * @param  DirtChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function show(DirtChampionship $championship)
    {
        return view('dirt-rally.championship.show')
            ->with('championship', $championship)
            ->with('seasons', $championship->seasons()->get()->sortBy('closes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DirtChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function edit(DirtChampionship $championship)
    {
        return view('dirt-rally.championship.edit')
            ->with('championship', $championship)
            ->with('sequences', \PointSequences::forSelect());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipRequest $request
     * @param  DirtChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipRequest $request, DirtChampionship $championship)
    {
        $championship->fill($request->all());
        $championship->save();

        \Notification::add('success', 'Championship "'.$championship->name.'" updated');
        return \Redirect::route('dirt-rally.championship.show', $championship);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DirtChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(DirtChampionship $championship)
    {
        if ($championship->seasons->count()) {
            \Notification::add('error', 'Championship "'.$championship->name.'" cannot be deleted - there are seasons assigned to it');
            return \Redirect::route('dirt-rally.championship.show', $championship);
        } else {
            $championship->delete();
            \Notification::add('success', 'Championship "'.$championship->name.'" deleted');
            return \Redirect::route('dirt-rally.championship.index');
        }
    }
}
