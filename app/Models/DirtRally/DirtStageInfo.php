<?php

namespace App\Models\DirtRally;

use Cviebrock\EloquentSluggable\Sluggable;

class DirtStageInfo extends \Eloquent
{
    use Sluggable;

    protected $table = 'dirt_stage_info';
    protected $fillable = ['location_name', 'stage_name', 'dnf_time'];

    protected $casts = [
        'dnf_time' => 'integer',
    ];

    public function stages()
    {
        return $this->hasMany(DirtStage::class);
    }

    public function getStageNameShortAttribute()
    {
        return str_replace(['(L)', '(S)'], '', $this->stage_name);
    }

    public function getNameAttribute()
    {
        return $this->stageNameShort;
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
                'source' => 'stage_name_short',
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
