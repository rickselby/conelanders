<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Services\AssettoCorsa\Championships;

class AssettoCorsaController extends Controller
{
    public function index(Championships $championships)
    {
        return view('assetto-corsa.index')
            ->with('currentChampionship', $championships->getCurrent())
            ->with('completeChampionships', $championships->getComplete());
    }
}