<?php

namespace App\Services\AssettoCorsa;

use App\Jobs\AssettoCorsa\ImportResultsJob;
use App\Models\AssettoCorsa\AcEntrant;
use App\Models\AssettoCorsa\AcLaptime;
use App\Models\AssettoCorsa\AcSession;
use App\Models\AssettoCorsa\AcSessionLap;
use App\Models\Driver;
use App\Services\Cached\AssettoCorsa\Handler;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class Import
{
    use DispatchesJobs;

    public function getResults(AcSession $session)
    {
        if (\ACSession::hasResultsFile($session)) {
            $json = file_get_contents(\ACSession::getResultsFilePath($session));
            return json_decode($json);
        } else {
            return null;
        }
    }

    public function saveUpload(Request $request, AcSession $session)
    {
        if ($request->hasFile('file')) {
            $request->file('file')->move(\ACSession::getResultsFileDirectory(), \ACSession::getResultsFileName($session));
        }
    }

    public function processEntrants(AcSession $session)
    {
        $results = $this->getResults($session);
        $cars = $drivers = [];
        foreach($results->Cars AS $car) {
            $cars[] = $car->Model;
            $drivers[] = $this->getDriver($car->Driver->Name, $car->Driver->Guid);
        }
        return [
            'cars' => array_unique($cars),
            'drivers' => $drivers,
        ];
    }

    public function saveEntrants(Request $request, AcSession $session)
    {
        $results = $this->getResults($session);
        $cars = $request->get('car');
        foreach($results->Cars AS $car) {
            $championshipEntrant = $this->getChampionshipEntrant($session->event->championship, $car->Driver->Name, $car->Driver->Guid);

            $sessionEntrant = $session->entrants()->create(['car' => $cars[$car->Model], 'ballast' => $car->BallastKG]);
            $sessionEntrant->championshipEntrant()->associate($championshipEntrant);
            $session->entrants()->save($sessionEntrant);
        }

        $this->dispatch(new ImportResultsJob($session));
    }

    public function getChampionshipEntrant($championship, $name, $guid)
    {
        $driver = $this->getDriver($name, $guid);
        $entrant = $championship->entrants()->where('driver_id', $driver->id)->first();
        if (!$entrant || !$entrant->exists) {
            $entrant = $championship->entrants()->create([]);
            $entrant->driver()->associate($driver);
            $entrant->save();
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

    public function saveResults(AcSession $session)
    {
        $results = $this->getResults($session);
        $entrants = $this->getEntrantsByID($session);
        $bestLaps = [];

        // Tidy up things we're going to overwrite
        foreach($entrants AS $entrant) {
            $entrant->fastestLap()->delete();
            $entrant->laps()->delete();
        }

        $position = 1;
        foreach($results->Result AS $result) {
            if (isset($entrants[$result->DriverGuid])) {
                $entrants[$result->DriverGuid]->position = $position++;
                if ($session->type == AcSession::TYPE_RACE) {
                    $entrants[$result->DriverGuid]->time = $result->TotalTime;
                }
                $entrants[$result->DriverGuid]->save();

                // Save the best lap time, for easier sorting later
                $bestLaps[$result->DriverGuid] = $result->BestLap;
            }
        }

        foreach($results->Laps AS $lap) {
            if (isset($entrants[$lap->DriverGuid])) {

                // Save the lap details
                $acLap = $this->createLap($lap);

                // Create a race lap entry for it
                $sessionLap = AcSessionLap::create([
                    'time' => $lap->Timestamp,
                ]);
                $sessionLap->lap()->associate($acLap);
                $sessionLap->save();

                // And attach it to the entrant
                $entrants[$lap->DriverGuid]->laps()->save($sessionLap);

                // Check if this is their fastest lap
                if ($bestLaps[$lap->DriverGuid] == $lap->LapTime) {
                    $entrants[$lap->DriverGuid]->fastestLap()->associate($acLap);
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

    private function setFastestLapPositions(&$entrants)
    {
        $fastestLaps = [];
        foreach($entrants AS $entrant) {
            $fastestLaps[] = [
                'entrant' => $entrant,
                // No lap? Push to back
                'lap' => $entrant->fastestLap ? $entrant->fastestLap->time : PHP_INT_MAX,
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

    private function createLap($lap)
    {
        // Save the lap...
        $acLap = AcLaptime::create(['time' => $lap->LapTime]);
        // Save the sectors...
        $sectorNum = 1;
        foreach($lap->Sectors AS $sector) {
            $acLap->sectors()->create([
                'sector' => $sectorNum++,
                'time' => $sector,
            ]);
        }
        return $acLap;
    }

    private function getEntrantsByID(AcSession $session)
    {
        $entrants = [];
        foreach($session->entrants AS $entrant) {
            $entrants[$entrant->championshipEntrant->driver->ac_guid] = $entrant;
        }
        return $entrants;
    }

}