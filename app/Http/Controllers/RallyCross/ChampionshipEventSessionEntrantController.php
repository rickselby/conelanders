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
use App\Services\RallyCross\Entrants;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChampionshipEventSessionEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('rallycross.validateSession');
    }

    /**
     * Add a result to a session
     *
     * @param ChampionshipEventSessionEntrantRequest $request
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @param Entrants $entrantsService
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipEventSessionEntrantRequest $request, $championshipSlug, $eventSlug, $sessionSlug, Entrants $entrantsService)
    {
        $session = \Request::get('session');
        $this->authorize('add-result', $session);

        $entrantsService->add(
            $session,
            RxEventEntrant::find($request->input('entrant')),
            $request->input('race'),
            $request->input('time'),
            $request->input('penalty'),
            $request->input('lap'),
            $request->input('dnf'),
            $request->input('dsq')
        );

        \Event::fire(new SessionUpdated($session));

        return \Redirect::route('rallycross.championship.event.session.show', [$session->event->championship, $session->event, $session]);
    }

    /**
     * Remove the given entrant from the session
     *
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @param RxSessionEntrant $entrant
     * @param Entrants $entrantsService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($championshipSlug, $eventSlug, $sessionSlug, RxSessionEntrant $entrant, Entrants $entrantsService)
    {
        $this->authorize('delete-result', $entrant->session);
        $entrantsService->delete($entrant);
        \Event::fire(new SessionUpdated($entrant->session));
        return \Redirect::route('rallycross.championship.event.session.show', [$entrant->session->event->championship, $entrant->session->event, $entrant->session]);
    }


}
