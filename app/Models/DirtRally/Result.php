<?php

namespace App\Models\DirtRally;

use App\Models\Driver;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends \Eloquent
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
        return $this->belongsTo(Stage::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
