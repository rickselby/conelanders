<?php

namespace App\Services\Races;

use App\Jobs\Races\ImportResultsJob;
use App\Models\Races\RacesCar;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEntrant;
use App\Models\Races\RacesLap;
use App\Models\Races\RacesSession;
use App\Models\Races\RacesSessionEntrant;
use App\Models\Driver;
use App\Services\Cached\Races\Handler;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class Import
{
    use DispatchesJobs;

    public function getResults(RacesSession $session)
    {
        if (\RacesSession::hasResultsFile($session)) {
            $json = file_get_contents(\RacesSession::getResultsFilePath($session));
            return json_decode($json);
        } else {
            return null;
        }
    }

    public function saveUpload(Request $request, RacesSession $session)
    {
        if ($request->hasFile('file')) {
            $request->file('file')->move(\RacesSession::getResultsFileDirectory(), \RacesSession::getResultsFileName($session));
        }
        $this->dispatch(new ImportResultsJob($session));
    }

    public function saveResults(RacesSession $session)
    {
        $this->readEntrants($session);

        $results = $this->getResults($session);
        $entrants = $this->getEntrantsByID($session);
        $bestLaps = [];

        // Tidy up things we're going to overwrite
        foreach($entrants AS $entrant) {
            $entrant->laps()->delete();
            $entrant->fastestLap()->delete();
        }

        $position = 1;
        foreach($results->Result AS $result) {
            if (isset($entrants[$result->DriverGuid])) {
                $entrants[$result->DriverGuid]->position = $position++;
                if ($session->type == RacesSession::TYPE_RACE) {
                    $entrants[$result->DriverGuid]->time = $result->TotalTime;
                }
                $entrants[$result->DriverGuid]->save();

                // Save the best lap time, for easier sorting later
                $bestLaps[$result->DriverGuid] = $result->BestLap;
            }
        }

        foreach($results->Laps AS $lap) {
            if (isset($entrants[$lap->DriverGuid])) {

                // Create a lap
                $racesLap = new RacesLap;
                $racesLap->sessionEntrant()->associate($entrants[$lap->DriverGuid]);
                $racesLap->laptime = $lap->LapTime;
                $racesLap->time_set = $lap->Timestamp;
                $racesLap->save();
                $sectorNum = 1;
                foreach($lap->Sectors AS $sector) {
                    $racesLap->sectors()->create([
                        'sector' => $sectorNum++,
                        'time' => $sector,
                    ]);
                }

                // Check if this is their fastest lap
                if ($bestLaps[$lap->DriverGuid] == $lap->LapTime) {
                    $entrants[$lap->DriverGuid]->fastestLap()->associate($racesLap);
                    $entrants[$lap->DriverGuid]->save();
                }
            }
        }
        
        $this->setFastestLapPositions($entrants);

        $session->importing = false;
        $session->save();
        // Clear the session cache
        app(Handler::class)->clearSessionCache($session);
    }

    public function readEntrants(RacesSession $session)
    {
        # Clear the current entrants
        $session->entrants()->delete();
        $results = $this->getResults($session);
        foreach($results->Cars AS $car) {
            $championshipEntrant = $this->getChampionshipEntrant($session->event->championship, $car->Driver->Name, $car->Driver->Guid);

            $sessionEntrant = new RacesSessionEntrant([
                'ballast' => $car->BallastKG,
            ]);
            $sessionEntrant->car()->associate(RacesCar::firstOrCreate(['ac_identifier' => $car->Model]));
            $sessionEntrant->championshipEntrant()->associate($championshipEntrant);
            $sessionEntrant->session()->associate($session);
            $sessionEntrant->save();
        }
    }

    public function getChampionshipEntrant(RacesChampionship $championship, $name, $guid)
    {
        $driver = $this->getDriver($name, $guid);

        $entrant = $championship->entrants()->where('driver_id', $driver->id)->first();
        if (!$entrant || !$entrant->exists) {
            $entrant = $championship->entrants()->create([
                'driver_id' => $driver->id
            ]);
        }
        return $entrant;
    }

    public function getDriver($name, $guid)
    {
        // Try by guid
        $driver = Driver::where('ac_guid', $guid)->first();
        if (!$driver || !$driver->exists) {
            // Try by name?
            $driver = Driver::where('name', $name)->first();
            if (!$driver || !$driver->exists) {
                $driver = Driver::create(['name' => $name, 'ac_guid' => $guid]);
            } else {
                $driver->ac_guid = $guid;
                $driver->save();
            }
        }
        return $driver;
    }

    private function setFastestLapPositions(&$entrants)
    {
        $fastestLaps = [];
        foreach($entrants AS $entrant) {
            $fastestLaps[] = [
                'entrant' => $entrant,
                // No lap? Push to back
                'lap' => $entrant->fastestLap ? $entrant->fastestLap->laptime : PHP_INT_MAX,
            ];
        }

        usort($fastestLaps, function($a, $b) {
            return $a['lap'] - $b['lap'];
        });

        $fastestLaps = \Positions::addToArray($fastestLaps, function($a, $b) {
            return $a['lap'] == $b['lap'];
        });

        foreach($fastestLaps AS $lapDetail) {
            $lapDetail['entrant']->fastest_lap_position = $lapDetail['position'];
            $lapDetail['entrant']->save();
        }
    }

    private function getEntrantsByID(RacesSession $session)
    {
        $entrants = [];
        foreach($session->entrants AS $entrant) {
            $entrants[$entrant->championshipEntrant->driver->ac_guid] = $entrant;
        }
        return $entrants;
    }

}
