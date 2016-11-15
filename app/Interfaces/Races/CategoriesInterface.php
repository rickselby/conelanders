<?php

namespace App\Interfaces\Races;

use App\Models\Races\RacesCategory;

interface CategoriesInterface
{
    /**
     * Get a sorted list of the current categories
     *
     * @return RacesCategory[]
     */
    public function getList();

}