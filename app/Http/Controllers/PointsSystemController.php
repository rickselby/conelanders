<?php

namespace App\Http\Controllers;

use App\Models\PointsSequence;
use App\Models\PointsSystem;
use Illuminate\Http\Request;

use App\Http\Requests;

class PointsSystemController extends Controller
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
        return view('points-system.index')
            ->with('systems', PointsSystem::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('points-system.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $system = PointsSystem::create($request->all());
        // Also create the two sequences
        $system->eventSequence()->associate(PointsSequence::create([]));
        $system->stageSequence()->associate(PointsSequence::create([]));
        $system->save();
        \Notification::add('success', 'Points System "'.$system->name.'" created');
        return \Redirect::route('points-system.show', [$system->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $system = PointsSystem::findOrFail($id);
        return view('points-system.show')
            ->with('system', $system)
            ->with('points', \Points::forSystem($system));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('points-system.edit')
            ->with('system', PointsSystem::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $system = PointsSystem::findOrFail($id);
        $system->fill($request->all());
        $system->save();

        \Notification::add('success', 'Points System "'.$system->name.'" updated');
        return \Redirect::route('points-system.show', [$system->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $system = PointsSystem::findOrFail($id);
        $system->delete();
        \Notification::add('success', 'Points System deleted');
        return \Redirect::route('points-system.index');
    }

    public function points(Request $request, $id)
    {
        /** @var PointsSystem $system */
        $system = PointsSystem::with(['eventSequence', 'stageSequence'])->findOrFail($id);
        \Points::setForSequence($system->eventSequence, $request['event']);
        \Points::setForSequence($system->stageSequence, $request['stage']);
        \Notification::add('success', 'Points updated');
        return \Redirect::route('points-system.show', [$system->id]);
    }
}
