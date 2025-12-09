<?php

declare(strict_types=1);

use Petrobolos\FixedArray\Facades\FixedArray;
use Petrobolos\FixedArray\FixedArray as BaseFixedArray;

it('can return a facade accessor', function (): void {
    $facade = new class extends FixedArray {
        /**
         * Expose the protected method for testing.
         */
        public function getFacade(): string
        {
            return static::getFacadeAccessor();
        }
    };

    expect($facade->getFacade())
        ->toBeClass()
        ->toEqual(BaseFixedArray::class);
});
