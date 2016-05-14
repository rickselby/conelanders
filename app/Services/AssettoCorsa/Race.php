<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcRace;

class Race
{
    public function getResultsFilePath(AcRace $race, $type)
    {
        return $this->getResultsFileDirectory().$this->getResultsFileName($race, $type);
    }

    public function getResultsFileDirectory()
    {
        return storage_path('uploads/ac-results/');
    }

    public function getResultsFileName(AcRace $race, $type)
    {
        return $race->id.'-'.(($type == config('constants.RACE_RESULTS')) ? 'r' : 'q').'.json';
    }

    public function hasResultsFile(AcRace $race, $type)
    {
        return is_file($this->getResultsFilePath($race, $type));
    }
}