<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirtRally\ChampionshipSeasonEventStageRequest;
use App\Models\DirtRally\Event;
use App\Models\DirtRally\Stage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChampionshipSeasonEventStageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
        $this->middleware('dirt-rally.validateEvent', ['only' => ['create', 'store']]);
        $this->middleware('dirt-rally.validateStage', ['only' => ['show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function create($championship, $season, $event)
    {
        return view('dirt-rally.stage.create')
            ->with('event', \Request::get('event'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ChampionshipSeasonEventStageRequest $request
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipSeasonEventStageRequest $request, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $stage = Stage::create($request->all());
        $event->stages()->save($stage);
        \Notification::add('success', 'Stage "'.$stage->name.'" added to "'.$event->name.'" ('.$event->season->name.')');
        return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @param  string $stage
     * @return \Illuminate\Http\Response
     */
    public function show($championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        return view('dirt-rally.stage.show')
            ->with('stage', $stage)
            ->with('results', \DirtRallyResults::getStageResults($stage->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @param  string $stage
     * @return \Illuminate\Http\Response
     */
    public function edit($championship, $season, $event, $stage)
    {
        return view('dirt-rally.stage.edit')
            ->with('stage', \Request::get('stage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipSeasonEventStageRequest $request
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @param  string $stage
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipSeasonEventStageRequest $request, $championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        $stage->fill($request->all());
        $stage->save();
        \Notification::add('success', $stage->name . ' updated');
        return \Redirect::route('dirt-rally.championship.season.event.stage.show', [$championship, $season, $event, $stage]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @param  string $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy($championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        if ($stage->results->count()) {
            \Notification::add('error', $stage->name . ' cannot be deleted - there are results for this stage');
            return \Redirect::route('dirt-rally.championship.season.event.stage.show', [$championship, $season, $event, $stage]);
        } else {
            $stage->delete();
            \Notification::add('success', $stage->name . ' deleted');
            return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
        }
    }
}
