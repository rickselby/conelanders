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
use App\Models\Stage;

class ImportCSV extends ImportAbstract
{
    /** @var Stage[] */
    protected $stages;

    public function fromCSV($event_id, $times)
    {
        /** @var Event $event */
        $event = Event::with('stages.results')->find($event_id);
        $this->cacheStages($event);

        foreach($times AS $driverTimes) {
            $driver = $this->getDriver($driverTimes[0]);
            for ($i = 1; $i < count($driverTimes); $i++) {
                $stage = $this->getStage($event, $i);
                if ($driverTimes[$i] == 'DNF') {
                    $driverTimes[$i] = \StageTime::toString($stage->long ? self::LONG_DNF : self::SHORT_DNF);
                }
                if ($driverTimes[$i] !== '') {
                    $this->saveResult($stage, $driver, \StageTime::fromString($driverTimes[$i]));
                }
            }
        }
    }
}