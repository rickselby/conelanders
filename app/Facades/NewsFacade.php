<?php

namespace App\Facades;

use App\Services\News;
use \Illuminate\Support\Facades\Facade;

class NewsFacade extends Facade {
    protected static function getFacadeAccessor() { return News::class; }
}