<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Petrobolos\FixedArray\Fluent\FixedArrayable;
use Petrobolos\FixedArray\FixedArray;

describe('factory methods', function (): void {
    it('can wrap an existing SplFixedArray', function (): void {
        $array = SplFixedArray::fromArray([1, 2, 3]);
        $fluent = FixedArrayable::make($array);

        expect($fluent)
            ->toBeInstanceOf(FixedArrayable::class)
            ->and($fluent->get())
            ->toBe($array);
    });

    it('can return itself if given an existing FixedArrayable instance', function (): void {
        $original = FixedArrayable::make([1, 2, 3]);
        $fluent = FixedArrayable::make($original);

        expect($fluent->toArray())->toBe($original->toArray());
    });

    it('can create a new default instance if inadvertently given a fixed array object', function (): void {
        $array = new class extends FixedArray {};
        $fluent = FixedArrayable::make($array);

        expect($fluent)->toBeInstanceOf(FixedArrayable::class)
            ->and($fluent->get())
            ->toBeInstanceOf(SplFixedArray::class);
    });

    it('can wrap an array', function (): void {
        $array = [1, 2, 3];
        $fluent = FixedArrayable::make($array);

        expect($fluent->toArray())
            ->toEqual($array)
            ->and($fluent->get())
            ->toBeInstanceOf(SplFixedArray::class);
    });

    it('can wrap an Arrayable (e.g., Collection)', function (): void {
        $collection = collect([1, 2, 3]);
        $fluent = FixedArrayable::make($collection);

        expect($fluent->toArray())->toEqual([1, 2, 3]);
    });

    it('can create a fixed array with a single value', function (): void {
        $fluent = FixedArrayable::make(42);

        expect($fluent->toArray())->toEqual([42]);
    });

    it('can create a fixed array of a given count with a single value pushed', function (): void {
        $fluent = FixedArrayable::make('x', 5);

        expect($fluent->get())
            ->toBeInstanceOf(SplFixedArray::class)
            ->and($fluent->toArray()[0])
            ->toBe('x')
            ->and($fluent->toArray())
            ->toHaveCount(5);
    });

    it('use is an alias for make', function (): void {
        $fluent1 = FixedArrayable::make([1, 2, 3]);
        $fluent2 = FixedArrayable::use([1, 2, 3]);

        expect($fluent2->toArray())->toEqual($fluent1->toArray());
    });
});

describe('value returning methods', function (): void {
    beforeEach(function (): void {
        $this->contents = [1, 2, 3];
        $this->fluent = FixedArrayable::make($this->contents);
    });

    describe('get', function (): void {
        it('can return the underlying fixed array', function (): void {
            expect($this->fluent->get())->toBeInstanceOf(SplFixedArray::class);
        });
    });

    describe('to array', function (): void {
        it('can return the underlying fixed array as a standard array', function (): void {
            expect($this->fluent->toArray())->toEqual($this->contents);
        });
    });

    describe('to collection', function (): void {
        it('can return the underlying fixed array as a collection', function (): void {
            $collection = $this->fluent->toCollection();

            expect($collection)
                ->toBeInstanceOf(Collection::class)
                ->and($collection->toArray())
                ->toEqual($this->contents);
        });
    });

    describe('to fixed array', function (): void {
        it('is an alias for get', function (): void {
            expect($this->fluent->toFixedArray())->toBe($this->fluent->get());
        });
    });
});
