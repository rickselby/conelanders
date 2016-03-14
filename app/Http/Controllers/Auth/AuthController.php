<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->middleware('auth', ['only' => 'logout']);
    }

    function index() {
        return view('login');
    }

    function loginGoogle() {
        return \SocialAuth::authorize('google');
    }

    function loginGoogleDone() {
        \SocialAuth::login('google', function($user, $details) {
            $user->email = $details->email;
            $user->save();
        });
        return \Redirect::intended();
    }

    function logout() {
        \Auth::logout();
        return \Redirect::to('/');
    }
}