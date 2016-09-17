<?php

namespace App\Services\AssettoCorsa;

use App\Events\AssettoCorsa\EventSignupUpdated;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcEventSignup;
use GuzzleHttp\Client;

class Signups
{
    public function getOpen()
    {
        $events = [];
        if (\Auth::check()) {
            foreach(\Auth::user()->driver->acEntries AS $entry) {
                foreach($entry->championship->events()->signupOpen()->get() AS $event) {
                    $event->status = $this->getStatus($event);
                    $event->selected = ($event->status !== null);
                    $events[] = $event;
                }
            }
        }

        return $events;
    }

    public function getStatus(AcEvent $event)
    {
        if (\Auth::check()) {

            $signup = $this->getSignup($event);

            if ($signup) {
                return $signup->status;
            } else {
                return null;
            }
        }
    }

    public function setStatus(AcEvent $event, $status, $entrant = null)
    {
        if (\Auth::check()) {

            $signup = $this->getSignup($event);

            if ($signup) {
                $signup->status = $status;
                $signup->save();
            } else {
                $signup = $event->signups()->create([
                    'ac_championship_entrant_id' => $this->getEntry($event)->id,
                    'status' => $status,
                ]);
            }
            \Event::fire(new EventSignupUpdated($signup));
        }
    }

    protected function getSignup(AcEvent $event)
    {
        return $event->signups()->where('ac_championship_entrant_id', $this->getEntry($event)->id)->first();
    }

    protected function getEntry(AcEvent $event)
    {
        return $event->championship->entrants()->where('driver_id', \Auth::user()->driver->id)->first();
    }

    public function updateSignup(AcEventSignup $signup)
    {
        // This cannot stay. It could be a url in the database against the championship,
        // but what if the format of the request changes?
        // Must convince Moose to poll the site for the entry list instead.

        $client = new Client();
        $client->request('GET', 'http://holymooses.com/gt3/uploadDriver.php',
            ['query' => [
                'f' => $signup->entrant->driver->ac_guid,
                'att' => $signup->status ? 'y' : 'n',
            ]]
        );
    }
}
