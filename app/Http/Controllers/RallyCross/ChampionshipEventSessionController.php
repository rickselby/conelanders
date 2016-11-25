<?php

namespace App\Http\Controllers\RallyCross;

use App\Events\RallyCross\EventUpdated;
use App\Events\RallyCross\SessionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\RallyCross\ChampionshipEventSessionEntrantRequest;
use App\Http\Requests\RallyCross\ChampionshipEventSessionRequest;
use App\Models\Driver;
use App\Models\PointsSequence;
use App\Models\RallyCross\RxCar;
use App\Models\RallyCross\RxEventEntrant;
use App\Models\RallyCross\RxSessionEntrant;
use App\Services\RallyCross\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChampionshipEventSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('rallycross.validateEvent', ['only' => ['create', 'store']]);
        $this->middleware('rallycross.validateSession', ['except' => ['create', 'store']]);
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
        return view('rallycross.session.create')
            ->with('event', $event);
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
            $request->only('name', 'heat'),
            ['order' => $order]
        ));
        \Event::fire(new EventUpdated($event));
        \Notification::add('success', 'Session "'.$session->name.'" created');
        return \Redirect::route('rallycross.championship.event.show', [$event->championship, $event]);
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

        return view('rallycross.session.show')
            ->with('session', $session)
            ->with('sequences', \PointSequences::forSelect())
            # Get entrants without an entry to this session
            ->with('entrants', $session->event->entrants()->whereNotIn('id', $session->entrants->pluck('eventEntrant.id'))->get()->sortBy('driver.name'))
            ;
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

        return view('rallycross.session.edit')
            ->with('session', $session);
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
        $session->fill($request->only('name', 'heat'))->save();
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Session "'.$session->name.'" updated');
        return \Redirect::route('rallycross.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
            return \Redirect::route('rallycross.championship.event.session.show', [$session->event->championship, $session->event, $session]);
        } else {
            $session->delete();
            \Event::fire(new SessionUpdated($session));
            \Notification::add('success', 'Session "'.$session->name.'" deleted');
            return \Redirect::route('rallycross.championship.event.show', [$session->event->championship, $session->event]);
        }
    }


    /**
     * Apply the given points sequence to the results for this session
     *
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Session $sessionService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyPointsSequence(Request $request, $championshipStub, $eventStub, $sessionStub, Session $sessionService)
    {
        $session = $request->get('session');
        $this->authorize('set-points', $session);

        $sequence = PointsSequence::findOrFail($request->get('sequence'));
        $sessionService->applyPointsSequence($session, $sequence);
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Points sequence applied to results');
        return \Redirect::route('rallycross.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }


    /**
     * Set the points for the entrants to the given points
     *
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Session $sessionService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPoints(Request $request, $championshipStub, $eventStub, $sessionStub, Session $sessionService)
    {
        $session = $request->get('session');
        $this->authorize('set-points', $session);

        $sessionService->setPoints($session, $request->get('points'));
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Points updated');
        return \Redirect::route('rallycross.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Mark the session as complete (results entered)
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markComplete($championshipSlug, $eventSlug, $sessionSlug)
    {
        $session = \Request::get('session');
        $this->authorize('update', $session);

        $session->show = true;
        $session->save();

        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Session "'.$session->name.'" updated');
        return \Redirect::route('rallycross.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }
}
