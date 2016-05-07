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

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;

class ImportCSV extends ImportAbstract
{
    /**
     * Read times from the given array for the given event
     * @param integer $event_id
     * @param mixed[] $times
     */
    public function fromCSV($event_id, $times)
    {
        /** @var DirtEvent $event */
        $event = DirtEvent::with('stages.results')->find($event_id);
        $this->startEventImport($event);

        $stageTimes = [];

        // Reading the CSV we will get times for each driver; we want all times
        // for a stage, to make importing easier
        foreach($times AS $driverTimes) {
            for ($i = 1; $i < count($driverTimes); $i++) {
                $stageTimes[$i][$driverTimes[0]] = $driverTimes[$i];
            }
        }

        // Now we can step through each stage
        foreach($stageTimes AS $stageNum => $times) {
            if (count($times)) {
                $stage = $this->getStage($event, $stageNum);
                $this->clearStageResults($stage);
                foreach ($times AS $driver => $time) {
                    if ($time == 'DNF') {
                        $time = \Times::toString($stage->long ? self::LONG_DNF : self::SHORT_DNF);
                    }
                    if ($time !== '') {
                        $this->saveResult($stage, $this->getDriver($driver),
                            \Times::fromString($time));
                    }
                }
                \DirtRallyPositions::updateStagePositions($stage);
            }
        }

        $this->completeEventImport($event);
    }
}
