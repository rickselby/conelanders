<?php

namespace App\Http\Controllers;

use App\Events\Playlists\RequestPage;

class PlaylistController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:playlist-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('playlist.index')
            ->with('pages', \Event::fire(RequestPage::class));
    }

}
