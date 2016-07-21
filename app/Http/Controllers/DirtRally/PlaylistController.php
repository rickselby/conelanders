<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\DirtEvent;
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
            $event = DirtEvent::findOrFail($id);
            if ($event->playlist) {
                if ($link) {
                    $event->playlist->fill(['link' => $link]);
                    $event->playlist->save();
                } else {
                    $event->playlist->delete();
                }
            } elseif ($link) {
                $event->playlist()->create(['link' => $link]);
            }
            $event->save();
        }
        \Notification::add('success', 'Dirt Rally playlists updated');
        return \Redirect::route('playlists.index');
    }

}
