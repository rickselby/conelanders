<?php

namespace App\Http\Controllers\AcHotlap;

use App\Events\AcHotlap\SessionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcHotlap\SessionEntrantRequest;
use App\Models\AcHotlap\AcHotlapSession;
use App\Models\AcHotlap\AcHotlapSessionEntrant;
use App\Models\Driver;
use App\Models\Races\RacesCar;
use App\Services\AcHotlap\Entrants;

class SessionEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ac-hotlap-admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SessionEntrantRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SessionEntrantRequest $request, AcHotlapSession $session, Entrants $entrantService)
    {
        $entrant = $entrantService->add(
            $session,
            Driver::where('name', $request->input('driver'))->first(),
            RacesCar::find($request->input('car')),
            $request->input('time'),
            $request->input('sectors')
        );
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Lap for '.$entrant->driver->name.' in the '.$entrant->car->name.' added');
        return \Redirect::route('assetto-corsa.hotlaps.session.show', $session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AcHotlapSession $session
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AcHotlapSession $session, AcHotlapSessionEntrant $entrant, Entrants $entrants)
    {
        $entrants->delete($entrant);
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Lap time for  '.$entrant->driver->name.' deleted');
        return \Redirect::route('assetto-corsa.hotlaps.session.show', $session);
    }

}
