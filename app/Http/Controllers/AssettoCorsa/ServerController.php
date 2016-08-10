<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Services\AssettoCorsa\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /** @var Server */
    protected $server;

    public function __construct(Server $server)
    {
        $this->middleware('can:ac-server-admin');
        $this->server = $server;
    }

    public function index()
    {
        return view('assetto-corsa.server')
            ->with('entryList', $this->server->getCurrentEntryList())
            ->with('serverConfig', $this->server->getCurrentConfigFile());
    }

    public function updateConfig(Request $request)
    {

        if ($this->server->updateEntryList($request->get('entry-list'))) {
            \Notification::add('success', 'Updated Entry List');
        }

        if ($this->server->updateServerConfig($request->get('server-config'))) {
           \Notification::add('success', 'Updated Server Config');
        }

        return \Redirect::route('assetto-corsa.server.index');
    }

    public function status()
    {
        return $this->server->status();
    }

    public function start()
    {
        $this->server->start();
    }

    public function stop()
    {
        $this->server->stop();
    }

}