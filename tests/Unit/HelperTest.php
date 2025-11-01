<?php

declare(strict_types=1);

use Petrobolos\FixedArray\Fluent\FixedArrayable;

it('can create a fixed array from a helper function', function (): void {
    if (! function_exists('fixedArray')) {
        $this::fail('The helper function "fixedArray" is not defined... why?');
    }

    $fixedArray = fixedArray([1, 2, 3]);

    expect($fixedArray)
        ->toBeInstanceOf(FixedArrayable::class)
        ->and($fixedArray->toArray())->toEqual([1, 2, 3]);
});
