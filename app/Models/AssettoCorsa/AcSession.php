<?php

namespace App\Models\AssettoCorsa;

use App\Services\SlugTrait;
use Carbon\Carbon;
use ConstHelpers\Returning;
use Cviebrock\EloquentSluggable\SluggableInterface;
use RickSelby\EloquentSluggableKeyed\SluggableKeyedTrait;

class AcSession extends \Eloquent implements SluggableInterface
{
    const TYPE_PRACTICE = 1;
    const TYPE_QUALIFYING = 2;
    const TYPE_RACE = 3;

    use SluggableKeyedTrait;

    protected $fillable = ['name', 'order', 'type'];

    protected $casts = [
        'importing' => 'boolean',
        'order' => 'integer',
        'type' => 'integer',
    ];

    protected $sluggable = [
        'unique_key' => 'ac_event_id',
    ];

    public function event()
    {
        return $this->belongsTo(AcEvent::class, 'ac_event_id');
    }

    public function entrants()
    {
        return $this->hasMany(AcSessionEntrant::class);
    }
    
    public function getFullNameAttribute()
    {
        return $this->event->fullName.' - '.$this->name;
    }

    public function canBeReleased() {
        return !$this->importing;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    static public function getTypes()
    {
        return [
            self::TYPE_PRACTICE => 'Practice',
            self::TYPE_QUALIFYING => 'Qualifying',
            self::TYPE_RACE => 'Race',
        ];
    }
}
