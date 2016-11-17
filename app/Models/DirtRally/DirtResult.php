<?php

namespace App\Models\DirtRally;

use App\Models\Driver;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirtResult extends \Eloquent
{
    use SoftDeletes;

    protected $fillable = ['driver_id', 'time', 'dnf'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'time' => 'integer',
        'position' => 'integer',
        'dnf' => 'boolean',
    ];

    public function stage()
    {
        return $this->belongsTo(DirtStage::class, 'dirt_stage_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function car()
    {
        return $this->belongsTo(DirtCar::class, 'dirt_car_id');
    }
}
