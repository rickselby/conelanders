<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssettoCorsa\ChampionshipEventSessionRequest;
use App\Jobs\AssettoCorsa\ImportQualifyingJob;
use App\Jobs\AssettoCorsa\ImportEventJob;
use App\Jobs\AssettoCorsa\ImportResultsJob;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcEventEntrant;
use App\Models\AssettoCorsa\AcSession;
use App\Services\AssettoCorsa\Import;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class ChampionshipEventSessionController extends Controller
{
    use DispatchesJobs;

    public function __construct()
    {
        $this->middleware('can:assetto-corsa-admin');
        $this->middleware('assetto-corsa.validateEvent', ['only' => ['create', 'store']]);
        $this->middleware('assetto-corsa.validateSession', ['except' => ['create', 'store']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $championshipStub
     * @param string $eventStub
     * @return \Illuminate\Http\Response
     */
    public function create($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        return view('assetto-corsa.session.create')
            ->with('event', $event)
            ->with('types', AcSession::getTypes());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipEventSessionRequest $request
     * @param string $championshipStub
     * @param string $eventStub
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipEventSessionRequest $request, $championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        /** @var AcEvent $event */
        $session = AcSession::create($request->all());
        $event->sessions()->save($session);
        \Notification::add('success', 'Session "'.$session->name.'" created');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$event->championship, $event, $session]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @param  string $sessionSlug
     * @return \Illuminate\Http\Response
     */
    public function show($championshipSlug, $eventSlug, $sessionSlug)
    {
        $session = \Request::get('session');
        return view('assetto-corsa.session.show')
            ->with('session', $session)
            ->with('sequences', \PointSequences::forSelect())
            ->with('sessions', $session->event->sessions()->where('order', '<', $session->order)->pluck('name', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @param  string $sessionSlug
     * @return \Illuminate\Http\Response
     */
    public function edit($championshipSlug, $eventSlug, $sessionSlug)
    {
        return view('assetto-corsa.session.edit')
            ->with('session', \Request::get('session'))
            ->with('types', AcSession::getTypes());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipEventSessionRequest $request
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @param  string $sessionSlug
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipEventSessionRequest $request, $championshipSlug, $eventSlug, $sessionSlug)
    {
        $session = \Request::get('session');
        $session->fill($request->all());
        $session->save();
        \Notification::add('success', 'Session "'.$session->name.'" updated');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @param  string $sessionSlug
     * @return \Illuminate\Http\Response
     */
    public function destroy($championshipSlug, $eventSlug, $sessionSlug)
    {
        $session = \Request::get('session');
        if ($session->entrants()->count()) {
            \Notification::add('error', 'Session "'.$session->name.'" cannot be deleted - there are results entered');
            return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
        } else {
            $session->delete();
            \Notification::add('success', 'Session "'.$session->name.'" deleted');
            return \Redirect::route('assetto-corsa.championship.event.show', [$session->event->championship, $session->event]);
        }
    }

    /**
     * Upload a new results file for this session
     *
     * @param Request $request
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @param Import $import
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resultsUpload(Request $request, $championshipSlug, $eventSlug, $sessionSlug, Import $import)
    {
        $session = \Request::get('session');
        $import->saveUpload($request, $session);
        $session->entrants()->delete();
        return \Redirect::route('assetto-corsa.championship.event.session.entrants.create', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Rescan the results file for this session
     *
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resultsScan($championshipSlug, $eventSlug, $sessionSlug)
    {
        $session = \Request::get('session');
        if (\ACSession::hasResultsFile($session)) {
            $this->dispatch(new ImportResultsJob($session));
            \Notification::add('success', 'Results import job queued. Results will be imported shortly.');
        } else {
            \Notification::add('warning', 'No results file found to scan');
        }
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }
}
