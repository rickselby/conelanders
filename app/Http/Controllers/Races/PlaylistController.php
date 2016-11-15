<?php

namespace App\Http\Controllers\Races;

use App\Http\Controllers\Controller;
use App\Models\Races\RacesSession;
use Illuminate\Http\Request;

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
    public function update(Request $request)
    {
        foreach($request->playlist AS $id => $link) {
            $session = RacesSession::findOrFail($id);
            if ($session->playlist) {
                if ($link) {
                    $session->playlist->fill(['link' => $link]);
                    $session->playlist->save();
                } else {
                    $session->playlist->delete();
                }
            } elseif ($link) {
                $session->playlist()->create(['link' => $link]);
            }
            $session->save();
        }
        \Notification::add('success', 'Assetto Corsa playlists updated');
        return \Redirect::route('playlists.index');
    }

}
