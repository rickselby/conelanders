<?php

namespace App\Services\Races;

use App\Events\Races\EventSignupUpdated;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesEventSignup;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;

class Signups
{
    public function getOpen()
    {
        $events = [];
        if (\Auth::check() && \Auth::user()->driver) {
            foreach(\Auth::user()->driver->raceEntries AS $entry) {
                foreach($entry->championship->events()->signupOpen()->get() AS $event) {
                    $event->status = $this->getStatus($event);
                    $event->selected = ($event->status !== null);
                    $events[] = $event;
                }
            }
        }

        return $events;
    }

    public function getStatus(RacesEvent $event)
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

    public function setStatus(RacesEvent $event, $status, $entrant = null)
    {
        if (\Auth::check()) {

            $signup = $this->getSignup($event);

            if ($signup) {
                $signup->status = $status;
                $signup->save();
            } else {
                $signup = $event->signups()->create([
                    'races_championship_entrant_id' => $this->getEntry($event)->id,
                    'status' => $status,
                ]);
            }
            \Event::fire(new EventSignupUpdated($signup));
        }
    }

    protected function getSignup(RacesEvent $event)
    {
        return $event->signups()->where('races_championship_entrant_id', $this->getEntry($event)->id)->first();
    }

    protected function getEntry(RacesEvent $event)
    {
        return $event->championship->entrants()->where('driver_id', \Auth::user()->driver->id)->first();
    }

    public function updateSignup(RacesEventSignup $signup)
    {
        // This cannot stay. It could be a url in the database against the championship,
        // but what if the format of the request changes?
        // Must convince Moose to poll the site for the entry list instead.

        $client = new Client();
        $client->request('GET', 'http://holymooses.com/gt3/uploadDriver.php',
            ['query' => [
                'f' => $signup->entrant->driver->steam_id,
                'att' => $signup->status ? 'y' : 'n',
            ]]
        );
    }

    public function getCurrent()
    {
        return $this->getSignupsForEvent(
            $this->getFirstSignupEvent(
                RacesEvent::query()
            )
        );
    }

    public function getForChampionship(RacesChampionship $championship)
    {
        return $this->getSignupsForEvent(
            $this->getFirstSignupEvent(
                $championship->events()->getQuery()
            )
        );
    }

    protected function getFirstSignupEvent(Builder $eventQuery)
    {
        $eventQuery->getQuery()->orders = null;

        return $eventQuery->whereNotNull('signup_open')
            ->whereDate('signup_open', '<', Carbon::now())
            ->orderBy('signup_open', 'desc')
            ->first();
    }

    protected function getSignupsForEvent(RacesEvent $event = null)
    {
        if ($event !== NULL) {
            // Get a list of guids for the championship
            $guids = [];
            foreach ($event->championship->entrants AS $entrant) {
                $guids[$entrant->driver->steam_id] = $entrant->driver->steam_id;
            }

            $status = [
                'yes' => [],
                'no' => [],
                'unknown' => [],
            ];

            foreach ($event->signups AS $signup) {
                $guid = $signup->entrant->driver->steam_id;
                switch ($signup->status) {
                    case 1:
                        $status['yes'][] = $guid;
                        break;
                    case 0:
                        $status['no'][] = $guid;
                        break;
                }
                unset($guids[$guid]);
            }

            $status['unknown'] = array_values($guids);

            return $status;
        } else {
            return [];
        }
    }
}
