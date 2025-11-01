<?php

declare(strict_types=1);

use Petrobolos\FixedArray\Facades\FixedArray;

it('can use the facade to create access the underlying functionality', function (): void {
    $fixedArray = FixedArray::create();

    expect($fixedArray)->toBeInstanceOf(SplFixedArray::class);
});
