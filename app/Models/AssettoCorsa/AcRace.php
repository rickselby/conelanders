<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class AcRace extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'order', 'time'];

    protected $dates = ['time', 'release'];

    protected $casts = [
        'show_results' => 'boolean',
        'order' => 'integer',
    ];

    protected $sluggable = [
        'unique' => false,
    ];

    public function championship()
    {
        return $this->belongsTo(AcChampionship::class, 'ac_championship_id');
    }

    public function entrants()
    {
        return $this->hasMany(AcRaceEntrant::class);
    }

    public function canBeReleased() {
        return !$this->qualifying_import
            && !$this->race_import
            && $this->release !== NULL
            && $this->release < Carbon::now();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
