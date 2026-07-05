<?php

namespace PiccmaQ\TraccarApiLaravel\Facades;

use Illuminate\Support\Facades\Facade;

final class TraccarLaravel extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'traccar_laravel';
    }
}
