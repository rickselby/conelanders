<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Driver;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('user.show')
            ->with('user', \Auth::user())
            ->with('drivers', Driver::orderBy('name')->get());
    }
    
    public function selectDriver(Request $request)
    {
        $driver = Driver::findOrFail($request->get('driver'));
        \Auth::user()->driver()->associate($driver);
        \Auth::user()->save();
        return \Redirect::route('user.show');
    }
}