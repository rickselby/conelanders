<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChampionshipRequest;
use App\Models\Championship;

class ChampionshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' =>
            ['index', 'show']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('championship.index')
            ->with('championships', Championship::with('seasons')->get()->sortBy('closes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('championship.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipRequest $request)
    {
        /** @var Championship $championship */
        $championship = Championship::create($request->all());
        \Notification::add('success', 'Championship "'.$championship->name.'" created');
        return \Redirect::route('dirt-rally.championship.show', $championship);
    }

    /**
     * Display the specified resource.
     *
     * @param  Championship $championship
     * @return \Illuminate\Http\Response
     */
    public function show(Championship $championship)
    {
        return view('championship.show')
            ->with('championship', $championship)
            ->with('seasons', $championship->seasons()->get()->sortBy('closes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Championship $championship
     * @return \Illuminate\Http\Response
     */
    public function edit(Championship $championship)
    {
        return view('championship.edit')
            ->with('championship', $championship);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipRequest $request
     * @param  Championship $championship
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipRequest $request, Championship $championship)
    {
        $championship->fill($request->all());
        $championship->save();

        \Notification::add('success', 'Championship "'.$championship->name.'" updated');
        return \Redirect::route('dirt-rally.championship.show', $championship);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Championship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(Championship $championship)
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
