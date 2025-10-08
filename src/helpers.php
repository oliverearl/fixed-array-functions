<?php

declare(strict_types=1);

use Petrobolos\FixedArray\FixedArrayable;

if (! function_exists('fixedArray')) {
    /**
     * Create a new fixed array fluent interface.
     *
     * @param mixed|null $input
     * @return \Petrobolos\FixedArray\FixedArrayable
     */
    function fixedArray(mixed $input = null): FixedArrayable
    {
        return new FixedArrayable($input);
    }
}
