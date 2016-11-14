<?php

namespace App\Http\Controllers\Races;

use App\Http\Controllers\Controller;
use App\Services\Races\Server;
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
        return view('races.server')
            ->with('entryList', $this->server->getCurrentEntryList())
            ->with('serverConfig', $this->server->getCurrentConfigFile())
            ->with('entryListLastUpdate', $this->server->getEntryListLastUpdate())
            ->with('serverConfigLastUpdate', $this->server->getServerConfigLastUpdate());
    }

    public function updateConfig(Request $request)
    {

        if ($this->server->updateEntryList($request->get('entry-list'))) {
            \Notification::add('success', 'Updated Entry List');
        }

        if ($this->server->updateServerConfig($request->get('server-config'))) {
           \Notification::add('success', 'Updated Server Config');
        }

        return \Redirect::route('races.server.index');
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