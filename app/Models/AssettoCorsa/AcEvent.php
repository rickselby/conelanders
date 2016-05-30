<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use RickSelby\EloquentSluggableKeyed\SluggableKeyedTrait;

class AcEvent extends \Eloquent implements SluggableInterface
{
    use SluggableKeyedTrait;

    protected $fillable = ['name', 'time', 'release'];

    protected $dates = ['time', 'release'];

    protected $sluggable = [
        'unique_key' => 'ac_championship_id',
    ];

    public function championship()
    {
        return $this->belongsTo(AcChampionship::class, 'ac_championship_id');
    }

    public function sessions()
    {
        return $this->hasMany(AcSession::class)->orderBy('order');
    }

    public function getFullNameAttribute()
    {
        return $this->championship->name.' - '.$this->name;
    }

    public function canBeReleased() {
        if (($this->release === NULL)
            || ($this->release >= Carbon::now())) {
            return false;
        }

        foreach($this->sessions AS $session) {
            if (!$session->canBeReleased()) {
                return false;
            }
        }
        
        return true;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
