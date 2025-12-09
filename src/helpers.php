<?php

declare(strict_types=1);

use Petrobolos\FixedArray\Fluent\FixedArrayable;

if (! function_exists('fixedArray')) {
    /**
     * Create a new FixedArrayable instance.
     */
    function fixedArray(mixed $value): FixedArrayable
    {
        return FixedArrayable::make($value);
    }
}
