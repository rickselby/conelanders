<?php
/*
$eventIDs = [46860, 46861];
# $eventIDs = [46368, 46369, 46370];

$dirtEvent = new dirtEvent();

foreach($eventIDs AS $eventID) {
    echo $eventID.' : ';
    var_dump($dirtEvent->getEvent($eventID));
}
*/

namespace App\Services;

use App\Models\Event;

class ImportCSV extends ImportAbstract
{
    public function fromCSV($event_id, $times)
    {
        /** @var Event $event */
        $event = Event::with('stages.results')->find($event_id);
        $this->cacheStages($event);

        $stageTimes = [];

        // Reading the CSV we will get times for each driver; we want all times
        // for a stage, to make importing easier
        foreach($times AS $driverTimes) {
            $driver = $this->getDriver($driverTimes[0]);
            for ($i = 1; $i < count($driverTimes); $i++) {
                $stageTimes[$i][$driver] = $driverTimes[$i];
            }
        }

        foreach($stageTimes AS $stageNum => $times) {
            $stage = $this->getStage($event, $stageNum);
            if (count($times)) {
                $this->clearStageResults($stage);
                foreach ($times AS $driver => $time) {
                    if ($time == 'DNF') {
                        $time = \StageTime::toString($stage->long ? self::LONG_DNF : self::SHORT_DNF);
                    }
                    if ($time !== '') {
                        $this->saveResult($stage, $driver,
                            \StageTime::fromString($time));
                    }
                }
            }
        }

    }
}