<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use RickSelby\EloquentSluggableKeyed\SluggableKeyedTrait;

class AcEvent extends \Eloquent implements SluggableInterface
{
    use SluggableKeyedTrait;

    protected $fillable = ['name', 'time'];

    protected $dates = ['time'];

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

    public function canBeReleased() 
    {
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
