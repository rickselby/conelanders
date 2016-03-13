<?php namespace App\Services\Facades;

use App\Services\ImportResults;
use \Illuminate\Support\Facades\Facade;

/**
 * Facade class to be called whenever the class PokemonService is called
 */
class ImportResultsFacade extends Facade {

    /**
     * Get the registered name of the component. This tells $this->app what record to return
     * (e.g. $this->app[‘pokemonService’])
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return ImportResults::class; }

}