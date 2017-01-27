<?php

namespace App\Http\Controllers\AcHotlap;

use App\Events\AcHotlap\SessionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcHotlap\SessionRequest;
use App\Models\AcHotlap\AcHotlapSession;
use App\Models\Races\RacesCar;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ac-hotlap-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ac-hotlap.session.index')
            ->with('sessions', AcHotlapSession::all()->sortByDesc('finish'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ac-hotlap.session.create')
            ->with('cars', RacesCar::all()->sortBy('name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SessionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SessionRequest $request)
    {
        $session = AcHotlapSession::create($request->all());
        foreach($request->input('car') AS $carID => $checked) {
            $session->cars()->attach($carID);
        }
        \Notification::add('success', 'Session "'.$session->name.'" created');
        return \Redirect::route('assetto-corsa.hotlaps.session.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  AcHotlapSession $session
     * @return \Illuminate\Http\Response
     */
    public function show(AcHotlapSession $session)
    {
        return view('ac-hotlap.session.show')
            ->with('session', $session);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  AcHotlapSession $session
     * @return \Illuminate\Http\Response
     */
    public function edit(AcHotlapSession $session)
    {
        return view('ac-hotlap.session.edit')
            ->with('cars', RacesCar::all()->sortBy('name'))
            ->with('session', $session);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SessionRequest $request
     * @param  AcHotlapSession $session
     * @return \Illuminate\Http\Response
     */
    public function update(SessionRequest $request, AcHotlapSession $session)
    {
        $session->fill($request->all());
        $session->cars()->detach();
        foreach($request->input('car') AS $carID => $checked) {
            $session->cars()->attach($carID);
        }
        $session->save();
        \Event::fire(new SessionUpdated($session));
        \Notification::add('success', 'Session "'.$session->name.'" updated');
        return \Redirect::route('assetto-corsa.hotlaps.session.show', $session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AcHotlapSession $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcHotlapSession $session)
    {
        if ($session->entrants->count()) {
            \Notification::add('error', 'Session "'.$session->name.'" cannot be deleted - there are entrants assigned to it');
            return \Redirect::route('assetto-corsa.hotlaps.session.show', $session);
        } else {
            $session->delete();
            \Event::fire(new SessionUpdated($session));
            \Notification::add('success', 'Session "'.$session->name.'" deleted');
            return \Redirect::route('assetto-corsa.hotlaps.session.index');
        }
    }

}
