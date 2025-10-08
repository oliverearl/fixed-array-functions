<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Facades;

use Illuminate\Support\Facades\Facade;
use Petrobolos\FixedArray\FixedArray as BaseFixedArray;

/** @see FixedArray */
class FixedArray extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return class-string<BaseFixedArray>
     */
    protected static function getFacadeAccessor(): string
    {
        return BaseFixedArray::class;
    }
}
