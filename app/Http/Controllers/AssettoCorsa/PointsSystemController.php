<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcPointsSystem;
use App\Services\AssettoCorsa\PointsSystems;
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
        return view('assetto-corsa.points-system.index')
            ->with('systems', AcPointsSystem::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('assetto-corsa.points-system.create')
            ->with('sequences', \PointSequences::forSelect());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $system = AcPointsSystem::create($request->all());

        if ($request->default) {
            $this->setDefault($system->id);
        }

        \Notification::add('success', 'Points System "'.$system->name.'" created');
        return \Redirect::route('assetto-corsa.points-system.show', $system);
    }

    /**
     * Display the specified resource.
     *
     * @param  AcPointsSystem $points_system
     * @param  PointsSystems $pointSystems
     * @return \Illuminate\Http\Response
     */
    public function show(AcPointsSystem $points_system, PointsSystems $pointsSystems)
    {
        return view('assetto-corsa.points-system.show')
            ->with('system', $points_system)
            ->with('points', $pointsSystems->forSystem($points_system));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  AcPointsSystem $points_system
     * @return \Illuminate\Http\Response
     */
    public function edit(AcPointsSystem $points_system)
    {
        return view('assetto-corsa.points-system.edit')
            ->with('system', $points_system)
            ->with('sequences', \PointSequences::forSelect());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  AcPointsSystem $points_system
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcPointsSystem $points_system)
    {
        $points_system->fill($request->all());
        $points_system->save();

        if ($request->default) {
            $this->setDefault($points_system->id);
        }

        \Notification::add('success', 'Points System "'.$points_system->name.'" updated');
        return \Redirect::route('assetto-corsa.points-system.show', $points_system);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AcPointsSystem $points_system
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcPointsSystem $points_system)
    {
        $points_system->delete();
        \Notification::add('success', 'Points System deleted');
        return \Redirect::route('assetto-corsa.points-system.index');
    }

    private function setDefault($id)
    {
        \DB::table('ac_points_systems')->update(['default' => false]);
        \DB::table('ac_points_systems')->where('id', $id)->update(['default' => true]);
    }
}
