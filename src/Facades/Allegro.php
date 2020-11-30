<?php

namespace AwemaPL\Allegro\Facades;

use AwemaPL\Allegro\Contracts\Allegro as AllegroContract;
use Illuminate\Support\Facades\Facade;

class Allegro extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AllegroContract::class;
    }
}
