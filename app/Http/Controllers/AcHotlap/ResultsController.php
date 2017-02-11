<?php

namespace App\Http\Controllers\AcHotlap;

use App\Http\Controllers\Controller;
use App\Interfaces\AcHotlap\ResultsInterface;
use App\Models\AcHotlap\AcHotlapSession;

class ResultsController extends Controller
{
    public function index(ResultsInterface $results)
    {
        return view('ac-hotlap.index')
            ->with('sessions', $results->withWinners());
    }

    public function session(AcHotlapSession $session, ResultsInterface $results)
    {
        return view('ac-hotlap.session')
            ->with('session', $session)
            ->with('results', $results->forSession($session))
            ->with('sectors', $results->getSectors($session));
    }
}
