<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class AcCar extends \Eloquent
{
    protected $fillable = ['ac_identifier', 'name'];

    public function entrants()
    {
        return $this->hasMany(AcSessionEntrant::class);
    }

}
