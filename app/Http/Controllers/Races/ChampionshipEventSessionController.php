<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\EventUpdated;
use App\Events\Races\SessionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\ChampionshipEventSessionRequest;
use App\Jobs\Races\ImportQualifyingJob;
use App\Jobs\Races\ImportEventJob;
use App\Jobs\Races\ImportResultsJob;
use App\Models\Races\RacesEventEntrant;
use App\Models\Races\RacesPenalty;
use App\Models\Races\RacesSession;
use App\Services\Races\Import;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class ChampionshipEventSessionController extends Controller
{
    use DispatchesJobs;

    public function __construct()
    {
        $this->middleware('races.validateEvent', ['only' => ['create', 'store']]);
        $this->middleware('races.validateSession', ['except' => ['create', 'store']]);
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
        $this->authorize('create-session', $event);
        return view('races.session.create')
            ->with('event', $event)
            ->with('types', RacesSession::getTypes());
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
        $this->authorize('create-session', $event);
        // Could we do this as an event listener? Or something?
        $order = count($event->sessions) + 1;
        $session = $event->sessions()->create(array_merge(
            $request->all(),
            ['order' => $order]
        ));
        if ($request->get('playlistLink')) {
            $request->playlist()->create(['link' => $request->get('playlistLink')]);
        }
        \Event::fire(new EventUpdated($event));
        \Notification::add('success', 'Session "'.$session->name.'" created');
        return \Redirect::route('races.championship.event.session.show', [$event->championship, $event, $session]);
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
        $this->authorize('view', $session);
        return view('races.session.show')
            ->with('session', $session)
            ->with('sequences', \PointSequences::forSelect())
            ->with('sessions', $session->event->sessions()->where('order', '<', $session->order)->pluck('name', 'id'))
            ->with('penalties', RacesPenalty::forSession($session)->get());
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
        $session = \Request::get('session');
        $this->authorize('update', $session);
        return view('races.session.edit')
            ->with('session', $session)
            ->with('types', RacesSession::getTypes());
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
        $this->authorize('update', $session);
        $session->fill($request->all());
        if ($session->playlist) {
            if ($request->get('playlistLink')) {
                $session->playlist->fill(['link' => $request->get('playlistLink')]);
                $session->playlist->save();
            } else {
                $session->playlist->delete();
            }
        } elseif ($request->get('playlistLink')) {
            $session->playlist()->create(['link' => $request->get('playlistLink')]);
        }
        $session->save();
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Session "'.$session->name.'" updated');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $this->authorize('delete', $session);
        if ($session->entrants()->count()) {
            \Notification::add('error', 'Session "'.$session->name.'" cannot be deleted - there are results entered');
            return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
        } else {
            $session->delete();
            \Event::fire(new SessionUpdated($session));
            \Notification::add('success', 'Session "'.$session->name.'" deleted');
            return \Redirect::route('races.championship.event.show', [$session->event->championship, $session->event]);
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
        $this->authorize('update', $session);
        $import->saveUpload($request, $session);
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Rescan the results file for this session
     *
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resultsScan($championshipSlug, $eventSlug, $sessionSlug, Import $import)
    {
        $session = \Request::get('session');
        $this->authorize('update', $session);
        if (\RacesSession::hasResultsFile($session)) {
            $this->dispatch(new ImportResultsJob($session));
            \Notification::add('success', 'Results import job queued. Results will be imported shortly.');
        } else {
            \Notification::add('warning', 'No results file found to scan');
        }
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }
    
    /**
     * Update the release date for the event
     *
     * @param Request $request
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function releaseDate(Request $request, $championshipSlug, $eventSlug, $sessionSlug)
    {
        $session = \Request::get('session');
        $this->authorize('update', $session);
        $session->release = Carbon::createFromFormat('jS F Y, H:i', $request->release);
        $session->save();
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Release Date Updated');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }
}
