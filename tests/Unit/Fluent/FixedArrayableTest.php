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

    it('can return the underlying fixed array', function (): void {
        expect($this->fluent->get())->toBeInstanceOf(SplFixedArray::class);
    });

    it('can return the underlying fixed array as a standard array', function (): void {
        expect($this->fluent->toArray())->toEqual($this->contents);
    });

    it('can return the underlying fixed array as a collection', function (): void {
        $collection = $this->fluent->toCollection();

        expect($collection)
            ->toBeInstanceOf(Collection::class)
            ->and($collection->toArray())
            ->toEqual($this->contents);
    });

    it('is an alias for get', function (): void {
        expect($this->fluent->toFixedArray())->toBe($this->fluent->get());
    });
});

describe('mutator methods', function (): void {
    beforeEach(function (): void {
        $this->fluent = FixedArrayable::make([1, 2, 3]);
    });

    it('map mutates the fluent instance and returns $this', function (): void {
        $returned = $this->fluent->map(fn(int $v): int => $v * 2);

        expect($returned)
            ->toBe($this->fluent)
            ->and($this->fluent->toArray())
            ->toEqual([2, 4, 6]);
    });

    it('can push (add) values fluently', function (): void {
        $returned = $this->fluent->push(4)->push(5);

        expect($returned)
            ->toBe($this->fluent)
            ->and($this->fluent->toArray())
            ->toEqual([1, 2, 3, 4, 5]);
    });

    it('can addFrom an iterable', function (): void {
        $this->fluent->addFrom([4, 5]);

        expect($this->fluent->toArray())->toEqual([1, 2, 3, 4, 5]);
    });

    it('can unshift (prepend) values fluently', function (): void {
        $returned = $this->fluent->unshift(0);

        expect($returned)
            ->toBe($this->fluent)
            ->and($this->fluent->toArray())
            ->toEqual([0, 1, 2, 3]);
    });

    it('can pop and return the last element', function (): void {
        $popped = $this->fluent->pop();

        expect($popped)->toBe(3);
    });

    it('can popToArray appending the popped value into provided array', function (): void {
        $output = [];
        $returned = $this->fluent->popToArray($output);

        expect($returned)
            ->toBe($this->fluent)
            ->and($output)
            ->toEqual([3]);
    });

    it('can shift and return the first element', function (): void {
        $shifted = $this->fluent->shift();

        // after shift, first() should now be the next element
        expect($shifted)
            ->toBe(1)
            ->and($this->fluent->first())
            ->toBe(2);
    });

    it('can setSize (resize) the array', function (): void {
        $returned = $this->fluent->setSize(5);

        expect($returned)
            ->toBe($this->fluent)
            ->and($this->fluent->getSize())
            ->toBe(5);
    });

    it('can merge with an iterable (array) source', function (): void {
        $returned = $this->fluent->merge([4, 5]);

        expect($returned)
            ->toBe($this->fluent)
            ->and($this->fluent->toArray())
            ->toEqual([1, 2, 3, 4, 5]);
    });

    it('can nullify values', function (): void {
        $returned = $this->fluent->nullify();

        expect($returned)->toBe($this->fluent);
        $arr = $this->fluent->toArray();

        foreach ($arr as $v) {
            expect($v)->toBeNull();
        }
    });

    it('can make values unique', function (): void {
        $f = FixedArrayable::make([1, 2, 2, 3]);
        $f->unique();

        expect($f->toArray())->toEqual([1, 2, 3]);
    });
});

describe('query methods', function (): void {
    beforeEach(function (): void {
        $this->fluent = FixedArrayable::make([10, 20, 30]);
    });

    it('can determine if a value exists', function (): void {
        expect($this->fluent->contains(20))
            ->toBeTrue()
            ->and($this->fluent->contains(99))
            ->toBeFalse();
    });

    it('can return the size via getSize', function (): void {
        expect($this->fluent->getSize())->toBe(3);
    });

    it('can be checked empty by getSize()', function (): void {
        expect(FixedArrayable::make([])
            ->getSize())
            ->toBe(0)
            ->and($this->fluent->getSize())
            ->toBeGreaterThan(0);
    });
});

describe('conversion & other sanity checks', function (): void {
    beforeEach(function (): void {
        $this->fluent = FixedArrayable::make([1, 2, 3]);
    });

    it('can return a nonsense array', function (): void {
        $dsfargeg = $this->fluent->dsfargeg()->toArray();
        expect($dsfargeg)->toEqual(['D', 'S', 'F', 'A', 'R', 'G', 'E', 'G']);
    });

    it('toArray and toCollection behave', function (): void {
        expect($this->fluent->toArray())->toEqual([1, 2, 3]);

        $collection = $this->fluent->toCollection();

        expect($collection)
            ->toBeInstanceOf(Collection::class)
            ->and($collection->toArray())
            ->toEqual([1, 2, 3]);
    });

    it('toFixedArray is alias for get', function (): void {
        expect($this->fluent->toFixedArray())->toBe($this->fluent->get());
    });

    it('merge accepts SplFixedArray', function (): void {
        $this->fluent->setSize(3);
        $this->fluent->merge(SplFixedArray::fromArray([4, 5]));

        expect($this->fluent->toArray())->toEqual([1, 2, 3, 4, 5]);
    });

    it('random returns one of the existing values or null if empty', function (): void {
        $val = $this->fluent->random();

        // should be one of 1, 2, 3 (or null if empty, but array is not empty!)
        expect(in_array($val, [1, 2, 3], true))->toBeTrue();
    });
});

describe('fluency', function (): void {
    beforeEach(function (): void {
        $this->fluent = FixedArrayable::make([1, 2, 3, 4, 5]);
    });

    it('ensures chainable methods return FixedArrayable and chain works', function (): void {
        $fluent = FixedArrayable::make([1, 2, 3])
            ->map(fn(int $v): int => $v)
            ->push(4)
            ->unshift(0)
            ->merge([5]);

        expect($fluent)
            ->toBeInstanceOf(FixedArrayable::class)
            ->and($fluent->toArray())->toEqual([0, 1, 2, 3, 4, 5]);
    });

    describe('add', function (): void {
        it('can add a single item and remain fluent', function (): void {
            $result = $this->fluent->add(6);

            expect($result)
                ->toBeInstanceOf(FixedArrayable::class)
                ->and($result->toArray())->toEqual([1, 2, 3, 4, 5, 6]);
        });
    });

    describe('chunk', function (): void {
        it('can split into smaller fixed arrays', function (): void {
            $chunks = $this->fluent->chunk(2);

            expect($chunks)
                ->toBeInstanceOf(FixedArrayable::class)
                ->and($chunks->toArray())
                ->toHaveCount(3);
        });
    });

    describe('chunkWhile', function (): void {
        it('can chunk while a condition holds', function (): void {
            $chunks = $this->fluent->chunkWhile(fn(int $v, int $k): bool => $v < 4);

            expect($chunks)
                ->toBeInstanceOf(FixedArrayable::class)
                ->and($chunks->toArray()[0]->toArray())->toEqual([1, 2, 3]);
        });
    });

    describe('each', function (): void {
        it('can iterate through each element fluently', function (): void {
            $sum = 0;
            $result = $this->fluent->each(function (int $v) use (&$sum): void {
                $sum += $v;
            });

            expect($sum)
                ->toBe(15)
                ->and($result)
                ->toBeInstanceOf(FixedArrayable::class);
        });
    });

    describe('fill', function (): void {
        it('can fill a fixed array with a single value', function (): void {
            $filled = $this->fluent->fill('x');

            expect($filled->toArray())->toEqual(['x', 'x', 'x', 'x', 'x']);
        });
    });

    describe('find', function (): void {
        it('can find a value matching a condition', function (): void {
            $found = $this->fluent->find(fn(int $v): bool => $v > 3);
            expect($found)->toBe(4);
        });
    });

    describe('findKey', function (): void {
        it('can find the key for a matching value', function (): void {
            $key = $this->fluent->findKey(fn(int $v): bool => $v === 3);
            expect($key)->toBe(2);
        });
    });

    describe('findIndex', function (): void {
        it('can find the numeric index for a matching value', function (): void {
            $index = $this->fluent->findIndex(fn(int $v): bool => $v === 2);
            expect($index)->toBe(1);
        });
    });

    describe('flatten', function (): void {
        it('can flatten nested fixed arrays', function (): void {
            $nested = FixedArrayable::make([[1, 2], [3, 4]]);
            $flattened = $nested->flatten();

            expect($flattened->toArray())->toEqual([1, 2, 3, 4]);
        });
    });

    describe('last', function (): void {
        it('can retrieve the last value', function (): void {
            expect($this->fluent->last())->toBe(5);
        });
    });

    describe('offsetExists', function (): void {
        it('can check if an index exists', function (): void {
            expect($this->fluent->offsetExists(2))
                ->toBeTrue()
                ->and($this->fluent->offsetExists(10))
                ->toBeFalse();
        });
    });

    describe('offsetGet', function (): void {
        it('can retrieve a value by offset', function (): void {
            expect($this->fluent->offsetGet(1))->toBe(2);
        });
    });

    describe('offsetSet', function (): void {
        it('can set a value by offset', function (): void {
            $this->fluent->offsetSet(1, 42);
            expect($this->fluent->toArray()[1])->toBe(42);
        });
    });

    describe('resize', function (): void {
        it('can increase or decrease size', function (): void {
            $resized = $this->fluent->resize(3);
            expect($resized->toArray())->toEqual([1, 2, 3]);
        });
    });

    describe('reverse', function (): void {
        it('can reverse the order of elements', function (): void {
            $reversed = $this->fluent->reverse();

            expect($reversed->toArray())->toEqual([5, 4, 3, 2, 1]);
        });
    });

    describe('second', function (): void {
        it('can return the second value', function (): void {
            expect($this->fluent->second())->toBe(2);
        });
    });

    describe('shuffle', function (): void {
        it('can shuffle the order randomly', function (): void {
            $shuffled = $this->fluent->shuffle();

            expect($shuffled)
                ->toBeInstanceOf(FixedArrayable::class)
                ->and($shuffled->toArray())->toHaveCount(5);
        });
    });

    describe('slice', function (): void {
        it('can slice a segment from the array', function (): void {
            $sliced = $this->fluent->slice(1, 3);

            expect($sliced->toArray())->toEqual([2, 3, 4]);
        });
    });

    describe('sort', function (): void {
        it('can sort the fixed array', function (): void {
            $unsorted = FixedArrayable::make([3, 1, 2]);
            $sorted = $unsorted->sort();

            expect($sorted->toArray())->toEqual([1, 2, 3]);
        });
    });
});
