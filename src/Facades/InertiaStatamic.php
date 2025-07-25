<?php

namespace InertiaStatamic\InertiaStatamic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \InertiaStatamic\InertiaStatamic\InertiaStatamic
 */
class InertiaStatamic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \InertiaStatamic\InertiaStatamic\InertiaStatamic::class;
    }
}
