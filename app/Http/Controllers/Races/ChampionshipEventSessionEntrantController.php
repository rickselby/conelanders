<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\SessionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\PenaltyRequest;
use App\Models\Races\RacesPenalty;
use App\Models\Races\RacesSession;
use App\Models\Races\RacesSessionEntrant;
use App\Models\PointsSequence;
use App\Services\Races\Entrants;
use App\Services\Races\Import;
use App\Services\Races\Penalties;
use App\Services\Races\Session;
use Illuminate\Http\Request;

class ChampionshipEventSessionEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('races.validateSession');
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
        $this->authorize('update', $session);
        $entrants->updateSessionEntrants($request, $session);
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Entrants updated');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Destroy a single entrant
     * @param Request $request
     * @param string $championshipStub
     * @param string $eventStub
     * @param string $sessionStub
     * @param RacesSessionEntrant $entrant
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $championshipStub, $eventStub, $sessionStub, RacesSessionEntrant $entrant)
    {
        $session = $request->get('session');
        $this->authorize('update', $session);
        if ($entrant->session->id == $session->id) {
            if ($entrant->canBeDeleted()) {
                $entrant->delete();
                \Event::fire(new SessionUpdated($session));
                \Notification::add('success', $entrant->championshipEntrant->driver->name . ' removed as an entrant');
            } else {
                \Notification::add('warning', 'Cannot delete ' . $entrant->championshipEntrant->driver->name . ' - they have laps assigned');
            }
        } else {
            \Notification::add('warning', 'The entrant requested is not part of this session');
        }
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $this->authorize('update', $session);
        $sequence = PointsSequence::findOrFail($request->get('sequence'));
        $sessionService->applyPointsSequence($session, $sequence);
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Points sequence applied to results');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $this->authorize('update', $session);
        $sequence = PointsSequence::findOrFail($request->get('sequence'));
        $sessionService->applyFastestLapPointsSequence($session, $sequence);
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Points sequence applied to results');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $this->authorize('update', $session);
        $sessionService->setPoints($session, $request->get('points'));
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Points updated');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $this->authorize('update', $session);
        $sessionService->setFastestLapPoints($session, $request->get('points'));
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Points updated');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
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
        $this->authorize('update', $thisSession);
        $fromSession = RacesSession::findOrFail($request->get('from-session'));
        $sessionService->setStartedFromSession($thisSession, $fromSession);
        \Event::fire(new SessionUpdated($thisSession));
        \Notification::add('success', 'Starting positions set to results from '.$fromSession->name);
        return \Redirect::route('races.championship.event.session.show', [$thisSession->event->championship, $thisSession->event, $thisSession]);
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
        $this->authorize('update', $session);
        $sessionService->setStarted($session, $request->get('started'));
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Starting positions updated');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    public function addPenalty(PenaltyRequest $request, $championshipStub, $eventStub, $sessionStub, Penalties $penaltiesService)
    {
        $session = $request->get('session');
        $this->authorize('update', $session);
        $penaltiesService->add($request, $session);
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Penalty Added');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    public function deletePenalty(Request $request, $championshipStub, $eventStub, $sessionStub, RacesPenalty $penalty, Penalties $penaltiesService)
    {
        $session = $request->get('session');
        $this->authorize('update', $session);
        $penalty->delete();
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Penalty Removed');
        return \Redirect::route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

}