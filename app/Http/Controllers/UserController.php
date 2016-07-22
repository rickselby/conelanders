<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:user-admin', ['only' => ['assignments', 'assign']]);
    }

    public function show()
    {
        return view('user.show')
            ->with('user', \Auth::user())
            ->with('drivers', Driver::notLinkedToUser()->orderBy('name')->get());
    }
    
    public function selectDriver(Request $request)
    {
        $driver = Driver::findOrFail($request->get('driver'));
        \Auth::user()->driver()->associate($driver);
        \Auth::user()->save();
        return \Redirect::route('user.show');
    }

    /**
     * Show the list of pending user / driver assignments
     */
    public function assignments()
    {
        return view('user.assignments')
            ->with('users', User::with('driver')->where('driver_confirmed', false)->whereNotNull('driver_id')->get());
    }

    /**
     * Assign the given user to their selected driver
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assign(User $user)
    {
        if ($user->driver) {
            $user->driver_confirmed = true;
            $user->save();
        }
        return \Redirect::route('user.assignments');
    }
    
    public function updateProfile(Request $request)
    {
        \Auth::user()->timezone = $request->get('timezone');
        \Auth::user()->save();
        \Notification::add('success', 'Timezone updated.');
        return \Redirect::route('user.show');
    }
}