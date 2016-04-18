<?php

namespace App\Services;

use App\Models\Nation;

class Nations
{
    /**
     * Find a nation based on the dirt reference number, or create it
     * Save the flag for new nations
     *
     * @param integer $dirt_reference
     * @param string $imagePath
     * @return Nation
     */
    public function findOrAdd($dirt_reference, $imagePath = '')
    {
        $nation = Nation::where('dirt_reference', $dirt_reference)->first();
        if (!$nation) {
            $nation = Nation::create(['dirt_reference' => $dirt_reference]);

            $flagPath = $this->getFlagPath($nation);
            if ($imagePath && !file_exists($flagPath)) {
                file_put_contents($flagPath, file_get_contents($imagePath));
            }
            // TODO: Notify me that a new nation has been added and needs naming
        }
        return $nation;
    }

    /**
     * Get the path to the flag for the given nation
     * @param Nation $nation
     * @return string
     */
    public function getFlagPath(Nation $nation)
    {
        return storage_path('uploads/flags/'.$nation->id.'.jpg');
    }
}
