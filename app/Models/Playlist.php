<?php

namespace App\Models;

class Playlist extends \Eloquent
{
    protected $fillable = ['link'];

    public function playlistable()
    {
        return $this->morphTo();
    }

}
