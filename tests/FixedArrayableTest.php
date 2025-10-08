<?php

declare(strict_types=1);

use Petrobolos\FixedArray\FixedArray;

test('add from can add array data to the existing array', function () {
    $fluent = fixedArray([1, 2, 3])->addFrom([4, 5, 6]);

    $expected = FixedArray::fromArray([1, 2, 3, 4, 5, 6]);

    /** @phpstan-ignore-next-line */
    $this->assertEquals($expected, $fluent->get());
});
