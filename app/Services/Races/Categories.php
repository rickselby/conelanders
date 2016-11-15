<?php

namespace App\Services\Races;

use App\Interfaces\Races\CategoriesInterface;
use App\Models\Races\RacesCategory;

class Categories implements CategoriesInterface
{

    public function getList()
    {
        return RacesCategory::orderBy('name')->get();
    }

}