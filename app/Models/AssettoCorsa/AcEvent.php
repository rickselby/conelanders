<?php

namespace App\Models\AssettoCorsa;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AcEvent extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name', 'time'];

    protected $dates = ['time'];

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

    /**
     * Sluggable configuration
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ]
        ];
    }

    /**
     * Sluggable configuration
     * Make the slugs unique based on the championship id
     */
    public function scopeWithUniqueSlugConstraints(Builder $query, Model $model, $attribute, $config, $slug)
    {
        return $query->where('ac_championship_id', $model->ac_championship_id);
    }    
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
