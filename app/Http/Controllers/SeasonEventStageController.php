<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonEventStageRequest;
use App\Models\Event;
use App\Models\Stage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeasonEventStageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
        $this->middleware('validateEvent', ['only' => ['create', 'store']]);
        $this->middleware('validateStage', ['only' => ['show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $season
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function create($season, Event $event)
    {
        return view('stage.create')
            ->with('event', $event);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SeasonEventStageRequest $request
     * @param int $season
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonEventStageRequest $request, $season, Event $event)
    {
        $stage = Stage::create($request->all());
        $event->stages()->save($stage);
        \Notification::add('success', 'Stage "'.$stage->name.'" added to "'.$event->name.'" ('.$event->season->name.')');
        return \Redirect::route('season.event.show', [$season, $event->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $season
     * @param int $event
     * @param Stage $stage
     * @return \Illuminate\Http\Response
     */
    public function show($season, $event, Stage $stage)
    {
        return view('stage.show')
            ->with('stage', $stage)
            ->with('results', \Results::getStageResults($stage->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $season
     * @param int $event
     * @param Stage $stage
     * @return \Illuminate\Http\Response
     */
    public function edit($season, $event, Stage $stage)
    {
        return view('stage.edit')
            ->with('stage', $stage);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param SeasonEventStageRequest $request
     * @param int $season
     * @param int $event
     * @param Stage $stage
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonEventStageRequest $request, $season, $event, Stage $stage)
    {
        $stage->fill($request->all());
        $stage->save();
        \Notification::add('success', $stage->name . ' updated');
        return \Redirect::route('season.event.stage.show', [$season, $event, $stage->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $season
     * @param int $event
     * @param Stage $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy($season, $event, Stage $stage)
    {
        if ($stage->results->count()) {
            \Notification::add('error', $stage->name . ' cannot be deleted - there are results for this stage');
            return \Redirect::route('season.event.stage.show', [$season, $event, $stage->id]);
        } else {
            $stage->delete();
            \Notification::add('success', $stage->name . ' deleted');
            return \Redirect::route('season.event.show', [$season, $event]);
        }
    }
}
