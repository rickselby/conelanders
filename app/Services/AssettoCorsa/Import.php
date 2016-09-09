<?php

namespace App\Services\AssettoCorsa;

use App\Jobs\AssettoCorsa\ImportResultsJob;
use App\Models\AssettoCorsa\AcCar;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEntrant;
use App\Models\AssettoCorsa\AcLaptime;
use App\Models\AssettoCorsa\AcSession;
use App\Models\AssettoCorsa\AcSessionEntrant;
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
        $this->dispatch(new ImportResultsJob($session));
    }

    public function saveResults(AcSession $session)
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
                $sessionLap = $entrants[$lap->DriverGuid]->laps()->create([
                    'time' => $lap->Timestamp,
                    'ac_laptime_id' => $acLap->id,
                ]);
                $sessionLap->lap()->associate($acLap);
                $sessionLap->save();

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

    public function readEntrants(AcSession $session)
    {
        # Clear the current entrants
        $session->entrants()->delete();
        $results = $this->getResults($session);
        foreach($results->Cars AS $car) {
            $championshipEntrant = $this->getChampionshipEntrant($session->event->championship, $car->Driver->Name, $car->Driver->Guid);

            $sessionEntrant = new AcSessionEntrant([
                'ballast' => $car->BallastKG,
            ]);
            $sessionEntrant->car()->associate(AcCar::firstOrCreate(['ac_identifier' => $car->Model]));
            $sessionEntrant->championshipEntrant()->associate($championshipEntrant);
            $sessionEntrant->session()->associate($session);
            $sessionEntrant->save();
        }
    }

    public function getChampionshipEntrant(AcChampionship $championship, $name, $guid)
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
