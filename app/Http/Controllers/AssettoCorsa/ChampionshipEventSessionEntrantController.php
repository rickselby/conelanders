<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcSession;
use App\Models\AssettoCorsa\AcSessionEntrant;
use App\Models\PointsSequence;
use App\Services\AssettoCorsa\Entrants;
use App\Services\AssettoCorsa\Import;
use App\Services\AssettoCorsa\Session;
use Illuminate\Http\Request;

class ChampionshipEventSessionEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('assetto-corsa.validateSession');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @return \Illuminate\Http\Response
     */
    public function create($championshipStub, $eventStub, $sessionStub, Import $import)
    {
        $session = \Request::get('session');
        return view('assetto-corsa.session-entrants.create')
            ->with('session', $session)
            ->with('entrants', $import->processEntrants($session));;
    }

    /**
     * Store the entrants for the given session with the given form data
     * 
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Import $import
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $championshipStub, $eventStub, $sessionStub, Import $import)
    {
        $session = $request->get('session');
        $import->saveEntrants($request, $session);
        \Notification::add('success', 'Entrants added; results queued for processing');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Update the entrants for the given session
     * 
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Entrants $entrants
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $championshipStub, $eventStub, $sessionStub, Entrants $entrants)
    {
        $session = $request->get('session');
        $entrants->updateSessionEntrants($request, $session);
        \Notification::add('success', 'Entrants updated');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Destroy a single entrant
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param AcSessionEntrant $entrant
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $championshipStub, $eventStub, $sessionStub, AcSessionEntrant $entrant)
    {
        $session = $request->get('session');
        if ($entrant->session->id == $session->id) {
            if ($entrant->canBeDeleted()) {
                $entrant->delete();
                \Notification::add('success', $entrant->championshipEntrant->driver->name . ' removed as an entrant');
            } else {
                \Notification::add('warning', 'Cannot delete ' . $entrant->championshipEntrant->driver->name . ' - they have laps assigned');
            }
        } else {
            \Notification::add('warning', 'The entrant requested is not part of this session');
        }
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $sequence = PointsSequence::findOrFail($request->get('sequence'));
        $sessionService->applyPointsSequence($session, $sequence);
        \Notification::add('success', 'Points sequence applied to results');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Apply the given points sequence to the fastest lap results for this session
     *
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Session $sessionService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyFastestLapPointsSequence(Request $request, $championshipStub, $eventStub, $sessionStub, Session $sessionService)
    {
        $session = $request->get('session');
        $sequence = PointsSequence::findOrFail($request->get('sequence'));
        $sessionService->applyFastestLapPointsSequence($session, $sequence);
        \Notification::add('success', 'Points sequence applied to results');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $sessionService->setPoints($session, $request->get('points'));
        \Notification::add('success', 'Points updated');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Set the fastest lap points for the entrants to the given points
     *
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Session $sessionService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setFastestLapPoints(Request $request, $championshipStub, $eventStub, $sessionStub, Session $sessionService)
    {
        $session = $request->get('session');
        $sessionService->setFastestLapPoints($session, $request->get('points'));
        \Notification::add('success', 'Points updated');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Set starting positions to the results from the given session
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Session $sessionService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setStartedFromSession(Request $request, $championshipStub, $eventStub, $sessionStub, Session $sessionService)
    {
        $thisSession = $request->get('session');
        $fromSession = AcSession::findOrFail($request->get('from-session'));
        $sessionService->setStartedFromSession($thisSession, $fromSession);
        \Notification::add('success', 'Starting positions set to results from '.$fromSession->name);
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$thisSession->event->championship, $thisSession->event, $thisSession]);
    }

    /**
     * Set starting positions for the entrants
     *
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param Session $sessionService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setStarted(Request $request, $championshipStub, $eventStub, $sessionStub, Session $sessionService)
    {
        $session = $request->get('session');
        $sessionService->setStarted($session, $request->get('started'));
        \Notification::add('success', 'Starting positions updated');
        return \Redirect::route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

}