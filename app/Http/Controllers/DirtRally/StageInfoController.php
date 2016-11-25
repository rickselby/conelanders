<?php

namespace App\Http\Controllers\DirtRally;

use App\Events\DirtRally\ChampionshipUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\DirtRally\ChampionshipRequest;
use App\Http\Requests\DirtRally\StageInfoRequest;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtStage;
use App\Models\DirtRally\DirtStageInfo;

class StageInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:dirt-rally-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dirt-rally.stage-info.index')
            ->with('stages', DirtStageInfo::with('stages')->orderBy('location_name')->orderBy('stage_name')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dirt-rally.stage-info.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StageInfoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StageInfoRequest $request)
    {
        $stage = DirtStageInfo::create($request->only('location_name', 'stage_name', 'dnf_time'));
        \Notification::add('success', 'Stage "'.$stage->stage_name.'" created');
        return \Redirect::route('dirt-rally.stage-info.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  DirtChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function show(DirtChampionship $championship)
    {
        /*
        return view('dirt-rally.championship.show')
            ->with('championship', $championship)
            ->with('seasons', $championship->seasons()->get()->sortBy('closes'));
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DirtStageInfo $stage_info
     * @return \Illuminate\Http\Response
     */
    public function edit(DirtStageInfo $stage_info)
    {
        return view('dirt-rally.stage-info.edit')
            ->with('stage', $stage_info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StageInfoRequest $request
     * @param  DirtStageInfo $stage_info
     * @return \Illuminate\Http\Response
     */
    public function update(StageInfoRequest $request, DirtStageInfo $stage_info)
    {
        $stage_info->fill($request->only('location_name', 'stage_name', 'dnf_time'))->save();
        \Notification::add('success', 'Stage "'.$stage_info->stage_name.'" updated');
        return \Redirect::route('dirt-rally.stage-info.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DirtChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy(DirtStageInfo $stage_info)
    {
        if ($stage_info->stages->count()) {
            \Notification::add('error', 'Stage "'.$stage_info->stage_name.'" cannot be deleted - there are events using it');
        } else {
            $stage_info->delete();
            \Notification::add('success', 'Stage "'.$stage_info->stage_name.'" deleted');
        }
        return \Redirect::route('dirt-rally.stage-info.index');
    }
}
