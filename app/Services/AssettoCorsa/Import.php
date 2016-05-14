<?php

namespace App\Services\AssettoCorsa;

use App\Jobs\AssettoCorsa\ImportQualifyingJob;
use App\Jobs\AssettoCorsa\ImportRaceJob;
use App\Models\AssettoCorsa\AcEntrant;
use App\Models\AssettoCorsa\AcLaptime;
use App\Models\AssettoCorsa\AcRace;
use App\Models\AssettoCorsa\AcRaceLap;
use App\Models\Driver;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class Import
{
    use DispatchesJobs;

    public function getResults(AcRace $race, $type)
    {
        if (\ACRace::hasResultsFile($race, $type)) {
            $json = file_get_contents(\ACRace::getResultsFilePath($race, $type));
            return json_decode($json);
        } else {
            return null;
        }
    }

    public function saveUpload(Request $request, AcRace $race, $type)
    {
        if ($request->hasFile('file')) {
            $request->file('file')->move(\ACRace::getResultsFileDirectory(), \ACRace::getResultsFileName($race, $type));
        }
    }

    public function processEntrants(AcRace $race, $type)
    {
        $results = $this->getResults($race, $type);
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

    public function saveEntrants(Request $request, AcRace $race)
    {
        $results = $this->getResults($race, ($request->get('from') == 'qualifying'));
        $cars = $request->get('car');
        foreach($results->Cars AS $car) {
            $championshipEntrant = $this->getChampionshipEntrant($race->championship, $car->Driver->Name, $car->Driver->Guid);

            $raceEntrant = $race->entrants()->create(['car' => $cars[$car->Model], 'ballast' => $car->BallastKG]);
            $raceEntrant->championshipEntrant()->associate($championshipEntrant);
            $race->entrants()->save($raceEntrant);
        }

        if ($request->get('from') == 'qualifying') {
            $this->dispatch(new ImportQualifyingJob($race));
        } else {
            $this->dispatch(new ImportRaceJob($race));
        }
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

    public function saveQualifying(AcRace $race)
    {
        $results = $this->getResults($race, config('constants.QUALIFYING_RESULTS'));
        $entrants = $this->getEntrantsByID($race);
        $bestLaps = [];

        $position = 1;
        foreach($results->Result AS $result) {
            $entrant = $entrants[$result->DriverGuid];

            $entrant->qualifying_position = $position++;
            // Need to do qualifying lap
            $bestLaps[$result->DriverGuid] = $result->BestLap;

            $entrant->save();
        }

        foreach($results->Laps AS $lap) {
            if ($bestLaps[$lap->DriverGuid] == $lap->LapTime) {
                $acLap = $this->createLap($lap);
                // Associate with the entrant
                $entrants[$lap->DriverGuid]->qualifyingLap()->associate($acLap);
                $entrants[$lap->DriverGuid]->save();
            }
        }

        $race->qualifying_import = false;
        $race->save();
    }

    public function saveRace(AcRace $race)
    {
        $results = $this->getResults($race, config('constants.RACE_RESULTS'));
        $entrants = $this->getEntrantsByID($race);
        $bestLaps = [];

        $position = 1;
        $bestTime = 0;
        foreach($results->Result AS $result) {
            $entrant = $entrants[$result->DriverGuid];

            $entrant->race_position = $position++;
            $entrant->race_time = $result->TotalTime;
            $entrant->race_laps = 0;

            if ($bestTime) {
                $entrant->race_behind = $result->TotalTime - $bestTime;
            } else {
                $bestTime = $result->TotalTime;
                $entrant->race_behind = null;
            }
            $bestLaps[$result->DriverGuid] = $result->BestLap;
        }

        foreach($results->Laps AS $lap) {
            // Save the lap details
            $acLap = $this->createLap($lap);

            // Create a race lap entry for it
            $raceLap = AcRaceLap::create([
                'time' => $lap->Timestamp,
            ]);
            $raceLap->lap()->associate($acLap);
            $raceLap->save();

            // Associate the lap with the entrant
            $entrants[$lap->DriverGuid]->race_laps++;
            $entrants[$lap->DriverGuid]->raceLaps()->save($raceLap);

            // Is it their best lap?
            if ($bestLaps[$lap->DriverGuid] == $lap->LapTime) {
                $acLap = $this->createLap($lap);
                // Associate with the entrant
                $entrants[$lap->DriverGuid]->raceFastestLap()->associate($acLap);
            }
        }

        $fastestLaps = [];
        foreach($entrants AS $entrant) {
            $entrant->save();
            if ($entrant->raceFastestLap) {
                $fastestLaps[] = [
                    'entrant' => $entrant,
                    'lap' => $entrant->raceFastestLap->time,
                ];
            }
        }

        usort($fastestLaps, function($a, $b) {
            return $a['lap'] - $b['lap'];
        });

        $fastestLaps = \Positions::addToArray($fastestLaps, function($a, $b) {
            return $a['lap'] == $b['lap'];
        });

        foreach($fastestLaps AS $lapDetail) {
            $lapDetail['entrant']->race_fastest_lap_position = $lapDetail['position'];
            $lapDetail['entrant']->save();
        }

        $race->race_import = false;
        $race->save();
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

    private function getEntrantsByID(AcRace $race)
    {
        $entrants = [];
        foreach($race->entrants AS $entrant) {
            $entrants[$entrant->championshipEntrant->driver->ac_guid] = $entrant;
        }
        return $entrants;
    }

}