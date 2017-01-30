<?php

namespace App\Http\Controllers\AcHotlap;

use App\Http\Controllers\Controller;
use App\Models\AcHotlap\AcHotlapSession;
use App\Services\AcHotlap\Results;

class ResultsController extends Controller
{
    public function index(Results $results)
    {
        return view('ac-hotlap.index')
            ->with('sessions', $results->withWinners());
    }

    public function session(AcHotlapSession $session, Results $results)
    {
        return view('ac-hotlap.session')
            ->with('session', $session)
            ->with('results', $results->forSession($session))
            ->with('sectors', $results->getSectors($session));
    }
}
