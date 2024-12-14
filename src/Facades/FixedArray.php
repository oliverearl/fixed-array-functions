<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray\Facades;

use Illuminate\Support\Facades\Facade;
use Petrobolos\FixedArray\FixedArray as BaseFixedArray;

/**
 * @see FixedArray
 */
class FixedArray extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseFixedArray::class;
    }
}
