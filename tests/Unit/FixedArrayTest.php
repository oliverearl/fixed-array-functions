<?php

use Illuminate\Support\Collection;
use Petrobolos\FixedArray\FixedArray;

describe('add', function (): void {
    it('adds a value to the end of the array (alias for push)', function (): void {
        $array = FixedArray::fromArray([1, 2]);
        $result = FixedArray::add(3, $array);

        expect($array->toArray())
            ->toEqual([1, 2, 3])
            ->and($result)->toBe($array);
    });
});

describe('add from', function (): void {
    it('adds values from a PHP array', function (): void {
        $array = FixedArray::fromArray([1, 2]);
        $result = FixedArray::addFrom([3, 4], $array);

        expect($array->toArray())
            ->toEqual([1, 2, 3, 4])
            ->and($result)
            ->toBe($array);
    });

    it('adds values from another SplFixedArray', function (): void {
        $array = FixedArray::fromArray([1]);
        $source = FixedArray::fromArray([2, 3]);
        $result = FixedArray::addFrom($source, $array);

        expect($array->toArray())
            ->toEqual([1, 2, 3])
            ->and($result)
            ->toBe($array);
    });

    it('adds values from a collection', function (): void {
        $array = FixedArray::fromArray([1]);
        $source = collect([2, 3]);
        $result = FixedArray::addFrom($source, $array);

        expect($array->toArray())
            ->toEqual([1, 2, 3])
            ->and($result)
            ->toBe($array);
    });

    it('works with empty iterable', function (): void {
        $array = FixedArray::fromArray([1, 2]);
        $result = FixedArray::addFrom([], $array);

        expect($array->toArray())
            ->toEqual([1, 2])
            ->and($result)->toBe($array);
    });

    it('supports mixed types', function (): void {
        $array = FixedArray::fromArray([1]);
        $source = ['foo', null, true];
        $result = FixedArray::addFrom($source, $array);

        expect($array->toArray())
            ->toEqual([1, 'foo', null, true])
            ->and($result)
            ->toBe($array);
    });
});

describe('contains', function (): void {
    it('returns true if the item exists in the array (strict)', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        expect(FixedArray::contains(2, $array))->toBeTrue();
    });

    it('returns false if the item does not exist (strict)', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        expect(FixedArray::contains(4, $array))->toBeFalse();
    });

    it('respects strict parameter (type check)', function (): void {
        $array = FixedArray::fromArray([1, '2', 3]);

        expect(FixedArray::contains(2, $array))
            ->toBeFalse()
            ->and(FixedArray::contains(2, $array, false))
            ->toBeTrue();
    });

    it('works with mixed types and null', function (): void {
        $array = FixedArray::fromArray([1, null, 'foo', true]);

        expect(FixedArray::contains(null, $array))
            ->toBeTrue()
            ->and(FixedArray::contains('foo', $array))
            ->toBeTrue()
            ->and(FixedArray::contains(false, $array))
            ->toBeFalse();
    });

    it('returns false for array with no indices', function (): void {
        $array = new SplFixedArray(0);
        expect(FixedArray::contains(1, $array))->toBeFalse();
    });
});

describe('create', function (): void {
    it('creates a SplFixedArray with default size', function (): void {
        $array = FixedArray::create();

        expect($array)
            ->toBeInstanceOf(SplFixedArray::class)
            ->and($array->getSize())
            ->toBe(5)
            ->and($array->toArray())
            ->toEqual([null, null, null, null, null]);
    });

    it('creates a SplFixedArray with a custom size', function (): void {
        $array = FixedArray::create(3);

        expect($array->getSize())
            ->toBe(3)
            ->and($array->toArray())
            ->toEqual([null, null, null]);
    });

    it('creates an empty array if size is zero', function (): void {
        $array = FixedArray::create(0);

        expect($array->getSize())
            ->toBe(0)
            ->and($array->toArray())
            ->toEqual([]);
    });

    it('throws an error if negative size is provided', function (): void {
        FixedArray::create(-1);
    })->throws(ValueError::class);
});

describe('count', function (): void {
    it('returns the correct count for a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $count = FixedArray::count($array);

        expect($count)->toBe(3);
    });

    it('returns 0 for an empty array', function (): void {
        $array = new SplFixedArray(0);
        $count = FixedArray::count($array);

        expect($count)->toBe(0);
    });

    it('counts correctly after resizing', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::setSize(5, $array);
        $count = FixedArray::count($array);

        expect($count)->toBe(5);
    });
});

describe('each', function (): void {
    it('iterates over all items and calls the callback with value and key', function (): void {
        $array = FixedArray::fromArray([10, 20, 30]);
        $collected = [];

        $result = FixedArray::each($array, function (int $value, int $key) use (&$collected): void {
            $collected[$key] = $value * 2;
        });

        expect($collected)
            ->toEqual([0 => 20, 1 => 40, 2 => 60])
            ->and($result->toArray())
            ->toEqual([10, 20, 30]); // original array unchanged
    });

    it('works with array with no indices', function (): void {
        $array = new SplFixedArray(0);
        $collected = [];

        $result = FixedArray::each($array, function (mixed $value, int $key) use (&$collected): void {
            $collected[$key] = $value;
        });

        expect($collected)
            ->toEqual([])
            ->and($result->count())
            ->toBe(0);
    });

    it('supports mixed types', function (): void {
        $array = FixedArray::fromArray([1, null, 'foo', true]);
        $collected = [];

        FixedArray::each($array, function (mixed $value, int $key) use (&$collected): void {
            $collected[$key] = $value === null ? 'null' : (string) $value;
        });

        expect($collected)
            ->toEqual([0 => '1', 1 => 'null', 2 => 'foo', 3 => '1'])
            ->and($array->toArray())
            ->toEqual([1, null, 'foo', true]); // original array unchanged
    });
});

describe('filter', function (): void {
    it('filters values based on a callback', function (): void {
        $array = FixedArray::fromArray([1, 2, 3, 4]);
        $filtered = FixedArray::filter($array, fn(int $v): bool => $v % 2 === 0);

        expect($filtered->toArray())->toEqual([2, 4]);
    });

    it('returns an empty array when no items match', function (): void {
        $array = FixedArray::fromArray([1, 3, 5]);
        $filtered = FixedArray::filter($array, fn(int $v): bool => $v % 2 === 0);

        expect($filtered->count())
            ->toBe(0)
            ->and($filtered->toArray())
            ->toEqual([]);
    });

    it('works with mixed types and nulls', function (): void {
        $array = FixedArray::fromArray([1, null, 'foo', '', false]);
        $filtered = FixedArray::filter($array, fn(int|string|bool|null $v): bool => !empty($v));

        expect($filtered->toArray())->toEqual([1, 'foo']);
    });

    it('handles an array wtih no indices', function (): void {
        $array = new SplFixedArray(0);
        $filtered = FixedArray::filter($array, fn(mixed $v): true => true);

        expect($filtered->count())->toBe(0);
    });
});

describe('first', function (): void {
    it('returns the first item of a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $value = FixedArray::first($array);

        expect($value)->toBe(1);
    });

    it('returns null if the first item is null', function (): void {
        $array = FixedArray::fromArray([null, 2, 3]);
        $value = FixedArray::first($array);

        expect($value)->toBeNull();
    });

    it('throws exception if array has no indices', function (): void {
        $array = new SplFixedArray(0);
        FixedArray::first($array);
    })->throws(RuntimeException::class);
});

describe('from array', function (): void {
    it('imports a non-empty PHP array into a SplFixedArray', function (): void {
        $array = [1, 2, 3];
        $fixed = FixedArray::fromArray($array);

        expect($fixed)
            ->toBeInstanceOf(SplFixedArray::class)
            ->and($fixed->toArray())
            ->toEqual([1, 2, 3]);
    });

    it('imports an empty PHP array', function (): void {
        $array = [];
        $fixed = FixedArray::fromArray($array);

        expect($fixed->count())->toBe(0);
    });

    it('preserves numeric keys by default', function (): void {
        $array = [0 => 'a', 2 => 'b', 5 => 'c'];
        $fixed = FixedArray::fromArray($array);

        // SplFixedArray will always “fill in the gaps” when numeric keys are preserved.
        expect($fixed->toArray())->toEqual([
            0 => 'a',
            1 => null,
            2 => 'b',
            3 => null,
            4 => null,
            5 => 'c',
        ]);

    });

    it('can discard original keys when preserveKeys is false', function (): void {
        $array = [0 => 'a', 2 => 'b', 5 => 'c'];
        $fixed = FixedArray::fromArray($array, false);

        expect($fixed->toArray())->toEqual(['a', 'b', 'c']);
    });
});

describe('from collection', function (): void {
    it('imports a non-empty collection into a SplFixedArray', function (): void {
        $collection = collect([1, 2, 3]);
        $fixed = FixedArray::fromCollection($collection);

        expect($fixed->toArray())->toEqual([1, 2, 3]);
    });

    it('imports an empty collection', function (): void {
        $collection = collect();
        $fixed = FixedArray::fromCollection($collection);

        expect($fixed->count())->toBe(0);
    });

    it('preserves keys by default', function (): void {
        $collection = collect([0 => 'a', 2 => 'b', 5 => 'c']);
        $fixed = FixedArray::fromCollection($collection);

        expect($fixed->toArray())->toEqual([
            0 => 'a',
            1 => null,
            2 => 'b',
            3 => null,
            4 => null,
            5 => 'c',
        ]);
    });

    it('can discard keys when preserveKeys is false', function (): void {
        $collection = collect([0 => 'a', 2 => 'b', 5 => 'c']);
        $fixed = FixedArray::fromCollection($collection, false);

        expect($fixed->toArray())->toEqual(['a', 'b', 'c']);
    });
});

describe('get size', function (): void {
    it('returns the correct size for a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $size = FixedArray::getSize($array);

        expect($size)->toBe(3);
    });

    it('returns 0 for an array with no indices', function (): void {
        $array = new SplFixedArray(0);
        $size = FixedArray::getSize($array);

        expect($size)->toBe(0);
    });

    it('returns the correct size for an empty array with allocated size', function (): void {
        $array = new SplFixedArray(5);
        $size = FixedArray::getSize($array);

        expect($size)->toBe(5);
    });

    it('returns the updated size after resizing', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::setSize(5, $array);
        $size = FixedArray::getSize($array);

        expect($size)->toBe(5);
    });
});

describe('is fixed array', function (): void {
    it('returns true for an SplFixedArray', function (): void {
        $array = new SplFixedArray(3);
        $result = FixedArray::isFixedArray($array);

        expect($result)->toBeTrue();
    });

    it('returns false for a PHP array', function (): void {
        $array = [1, 2, 3];
        $result = FixedArray::isFixedArray($array);

        expect($result)->toBeFalse();
    });

    it('returns false for null', function (): void {
        $result = FixedArray::isFixedArray(null);

        expect($result)->toBeFalse();
    });

    it('returns false for objects that are not SplFixedArray', function (): void {
        $result = FixedArray::isFixedArray(new stdClass());

        expect($result)->toBeFalse();
    });
});

describe('last', function (): void {
    it('returns the last item of a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $value = FixedArray::last($array);

        expect($value)->toBe(3);
    });

    it('returns null if the array is empty', function (): void {
        $array = new SplFixedArray(3);
        $value = FixedArray::last($array);

        expect($value)->toBeNull();
    });

    it('throws exception for array with no indices', function (): void {
        $array = new SplFixedArray(0);
        FixedArray::last($array);
    })->throws(RuntimeException::class);
});

describe('map', function (): void {
    it('applies a callback to each element', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $mapped = FixedArray::map($array, fn($v) => $v * 2);

        expect($mapped->toArray())->toEqual([2, 4, 6]);
    });

    it('returns an empty array when mapping over an empty array', function (): void {
        $array = new SplFixedArray(0);
        $mapped = FixedArray::map($array, fn(int $v): int => $v * 2);

        expect($mapped->toArray())
            ->toEqual([])
            ->and($mapped->count())->toBe(0);
    });

    it('preserves types for mixed values', function (): void {
        $array = FixedArray::fromArray([1, 'two', null, true]);
        $mapped = FixedArray::map($array, fn(int|string|bool|null $v): string => (string) $v);

        expect($mapped->toArray())->toEqual(['1', 'two', '', '1']);
    });
});

describe('merge', function (): void {
    it('merges multiple arrays and preserves null values', function (): void {
        $target = FixedArray::fromArray([1, 'foo']);
        $source = ['bar', null, true];

        $merged = FixedArray::merge($target, $source);

        expect($merged->toArray())->toEqual([1, 'foo', 'bar', null, true]);
    });

    it('merges SplFixedArrays, PHP arrays, and collections', function (): void {
        $target = FixedArray::fromArray([1]);
        $source1 = [2, 3];
        $source2 = FixedArray::fromArray([4]);
        $source3 = collect([5, 6, null]);

        $merged = FixedArray::merge($target, $source1, $source2, $source3);

        expect($merged->toArray())->toEqual([1, 2, 3, 4, 5, 6, null]);
    });

    it('returns the original target after merge', function (): void {
        $target = FixedArray::fromArray([1]);
        $source = [2, 3];

        $result = FixedArray::merge($target, $source);

        expect($result)->toBe($target); // merged into same instance
    });
});

describe('nullify', function (): void {
    it('replaces all values with null in a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 'foo', true]);
        FixedArray::nullify($array);

        expect($array->toArray())->toEqual([null, null, null]);
    });

    it('works on an array without indices', function (): void {
        $array = new SplFixedArray(0);
        FixedArray::nullify($array);

        expect($array->toArray())->toEqual([]);
    });

    it('preserves array size while nullifying', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::nullify($array);

        expect($array->getSize())->toBe(3);
    });
});

describe('offset exists', function (): void {
    it('returns true for an existing index', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $exists = FixedArray::offsetExists(1, $array);

        expect($exists)->toBeTrue();
    });

    it('returns false for a non-existing index', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $exists = FixedArray::offsetExists(5, $array);

        expect($exists)->toBeFalse();
    });

    it('returns false if the value at the given index is null', function (): void {
        $array = FixedArray::fromArray([1, null, 3]);
        $exists = FixedArray::offsetExists(1, $array);

        expect($exists)->toBeFalse();
    });

    it('returns true if the value at the given index is false but not null', function (): void {
        $array = FixedArray::fromArray([1, false, 3]);
        $exists = FixedArray::offsetExists(1, $array);

        expect($exists)->toBeTrue();
    });
});

describe('offset get', function (): void {
    it('retrieves a value at a valid index', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $value = FixedArray::offsetGet(1, $array);

        expect($value)->toBe(2);
    });

    it('returns null if the value is null', function (): void {
        $array = FixedArray::fromArray([1, null, 3]);
        $value = FixedArray::offsetGet(1, $array);

        expect($value)->toBeNull();
    });

    it('throws exception when index is out of bounds', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::offsetGet(5, $array);
    })->throws(RuntimeException::class);
});

describe('offset null', function (): void {
    it('sets a value to null at a valid index', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::offsetNull(1, $array);

        expect($array[1])
            ->toBeNull()
            ->and($array->toArray())
            ->toEqual([1, null, 3]);
    });

    it('throws exception when index is out of bounds', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::offsetNull(5, $array);
    })->throws(RuntimeException::class);
});

describe('offset set', function (): void {
    it('sets a value at a valid index', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::offsetSet(1, 42, $array);

        expect($array[1])
            ->toBe(42)
            ->and($array->toArray())
            ->toEqual([1, 42, 3]);
    });

    it('overwrites a value at an existing index', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::offsetSet(0, 'foo', $array);

        expect($array[0])->toBe('foo');
    });

    it('throws exception when index is out of bounds', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::offsetSet(5, 99, $array);
    })->throws(RuntimeException::class);

    it('supports setting mixed types', function (): void {
        $array = FixedArray::fromArray([null, null, null]);
        FixedArray::offsetSet(2, [1, 2, 3], $array);

        expect($array[2])->toEqual([1, 2, 3]);
    });
});

describe('pop', function (): void {
    it('removes and returns the last item of a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $value = FixedArray::pop($array);

        expect($value)->toBe(3)
            ->and($array->toArray())
            ->toEqual([1, 2, null]);
    });

    it('returns null for an array with no indices', function (): void {
        $array = new SplFixedArray(0);
        $value = FixedArray::pop($array);

        expect($value)->toBeNull()
            ->and($array->toArray())
            ->toEqual([]);
    });

    it('works when the last value is null', function (): void {
        $array = FixedArray::fromArray([1, null]);
        $value = FixedArray::pop($array);

        expect($value)->toBeNull()
            ->and($array->toArray())
            ->toEqual([1, null]);
    });

    it('preserves array size but nulls out the last slot', function (): void {
        $array = FixedArray::fromArray([1, 2]);
        FixedArray::pop($array);

        expect($array->getSize())
            ->toBe(2)
            ->and($array[1])
            ->toBeNull();
    });
});

describe('push', function (): void {
    it('adds a value to the end of a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::push(4, $array);

        expect($array->toArray())
            ->toEqual([1, 2, 3, 4])
            ->and($result)->toBe($array);
    });

    it('adds a value to an empty array', function (): void {
        $array = new SplFixedArray(0);
        $result = FixedArray::push(1, $array);

        expect($array->toArray())
            ->toEqual([1])
            ->and($result)->toBe($array);
    });

    it('preserves existing null values', function (): void {
        $array = FixedArray::fromArray([1, null, 3]);
        $result = FixedArray::push(4, $array);

        expect($array->toArray())
            ->toEqual([1, null, 3, 4])
            ->and($result)->toBe($array);
    });

    it('supports mixed types', function (): void {
        $array = FixedArray::fromArray([1, 'foo']);
        $result = FixedArray::push([1, 2], $array);

        expect($array->toArray())
            ->toEqual([1, 'foo', [1, 2]])
            ->and($result)->toBe($array);
    });
});

describe('resize', function (): void {
    it('resizes the array (alias for setSize)', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::resize(5, $array);

        expect($result)->toBeTrue()
            ->and($array->toArray())->toEqual([1, 2, 3, null, null]);
    });

    it('can shrink the array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3, 4, 5]);
        $result = FixedArray::resize(2, $array);

        expect($result)->toBeTrue()
            ->and($array->toArray())
            ->toEqual([1, 2]);
    });

    it('throws an error on negative size', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        FixedArray::resize(-1, $array);
    })->throws(ValueError::class);
});

describe('reverse', function (): void {
    it('reverses a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $reversed = FixedArray::reverse($array);

        expect($reversed->toArray())->toEqual([3, 2, 1]);
    });

    it('works on an array with zero indices', function (): void {
        $array = new SplFixedArray(0);
        $reversed = FixedArray::reverse($array);

        expect($reversed->count())
            ->toBe(0)
            ->and($reversed->toArray())
            ->toEqual([]);
    });

    it('does not modify the original array', function (): void {
        $array = FixedArray::fromArray([1, 2]);
        $reversed = FixedArray::reverse($array);

        expect($array->toArray())
            ->toEqual([1, 2])
            ->and($reversed->toArray())
            ->toEqual([2, 1]);
    });

    it('supports mixed types and nulls', function (): void {
        $array = FixedArray::fromArray([null, 'a', 1, true]);
        $reversed = FixedArray::reverse($array);

        expect($reversed->toArray())->toEqual([true, 1, 'a', null]);
    });
});

describe('second', function (): void {
    it('returns the second item of a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $value = FixedArray::second($array);

        expect($value)->toBe(2);
    });

    it('returns null if the second item is null', function (): void {
        $array = FixedArray::fromArray([1, null, 3]);
        $value = FixedArray::second($array);

        expect($value)->toBeNull();
    });

    it('returns null if there is no second item', function (): void {
        $array = FixedArray::fromArray([1]);
        $value = FixedArray::second($array);

        expect($value)->toBeNull();
    });

    it('works with an array with no indices', function (): void {
        $array = new SplFixedArray(0);
        $value = FixedArray::second($array);

        expect($value)->toBeNull();
    });
});

describe('set size', function (): void {
    it('can increase the size of an SplFixedArray', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::setSize(5, $array);

        expect($result)
            ->toBeTrue()
            ->and($array->toArray())
            ->toHaveCount(5)
            ->and($array[3])
            ->toBeNull()
            ->and($array[4])
            ->toBeNull();
    });

    it('can decrease the size of an SplFixedArray', function (): void {
        $array = FixedArray::fromArray([1, 2, 3, 4, 5]);
        $result = FixedArray::setSize(3, $array);

        expect($result)
            ->toBeTrue()
            ->and($array->toArray())
            ->toHaveCount(3)
            ->and($array->toArray())
            ->toEqual([1, 2, 3]);
    });

    it('handles empty SplFixedArray', function (): void {
        $array = new SplFixedArray(0);
        $result = FixedArray::setSize(2, $array);

        expect($result)
            ->toBeTrue()
            ->and($array->toArray())
            ->toHaveCount(2);
    });

    it('does not allow negative sizes', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);

        FixedArray::setSize(-1, $array);
    })->throws(ValueError::class);

    it('handles setting the same size', function (): void {
        $array = SplFixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::setSize(3, $array);

        expect($result)
            ->toBeTrue()
            ->and($array->toArray())
            ->toEqual([1, 2, 3]);
    });
});

describe('shift', function (): void {
    it('removes and returns the first item of a non-empty array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $value = FixedArray::shift($array);

        expect($value)
            ->toBe(1)
            ->and($array->toArray())
            ->toEqual([2, 3, null]);
    });

    it('returns null for array with no indices', function (): void {
        $array = new SplFixedArray(0);
        $value = FixedArray::shift($array);

        expect($value)
            ->toBeNull()
            ->and($array->toArray())
            ->toEqual([]);
    });

    it('works when the first value is null', function (): void {
        $array = FixedArray::fromArray([null, 2, 3]);
        $value = FixedArray::shift($array);

        expect($value)
            ->toBeNull()
            ->and($array->toArray())
            ->toEqual([2, 3, null]);
    });

    it('preserves array size after shift', function (): void {
        $array = FixedArray::fromArray([1, 2]);
        FixedArray::shift($array);

        expect($array->getSize())
            ->toBe(2)
            ->and($array[1])
            ->toBeNull();
    });
});

describe('slice', function (): void {
    it('returns a portion of the array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3, 4, 5]);
        $sliced = FixedArray::slice($array, 1, 3);

        expect($sliced->toArray())->toEqual([2, 3, 4]);
    });

    it('returns from offset to end if length is null', function (): void {
        $array = FixedArray::fromArray([1, 2, 3, 4]);
        $sliced = FixedArray::slice($array, 2);

        expect($sliced->toArray())->toEqual([3, 4]);
    });

    it('works with negative offset', function (): void {
        $array = FixedArray::fromArray([1, 2, 3, 4]);
        $sliced = FixedArray::slice($array, -2);

        expect($sliced->toArray())->toEqual([3, 4]);
    });

    it('works with negative length', function (): void {
        $array = FixedArray::fromArray([1, 2, 3, 4, 5]);
        $sliced = FixedArray::slice($array, 1, -2);

        expect($sliced->toArray())->toEqual([2, 3]);
    });

    it('returns an empty array if offset exceeds array length', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $sliced = FixedArray::slice($array, 5);

        expect($sliced->toArray())->toEqual([]);
    });

    it('returns an empty array when slicing an empty SplFixedArray', function (): void {
        $array = new SplFixedArray(0);
        $sliced = FixedArray::slice($array, 0, 3);

        expect($sliced->count())
            ->toBe(0)
            ->and($sliced->toArray())
            ->toEqual([]);
    });
});

describe('to array', function (): void {
    it('converts a non-empty SplFixedArray to a PHP array', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::toArray($array);

        expect($result)
            ->toBeArray()
            ->toEqual([1, 2, 3]);
    });

    it('converts an empty SplFixedArray to an empty PHP array', function (): void {
        $array = new SplFixedArray(0);
        $result = FixedArray::toArray($array);

        expect($result)
            ->toBeArray()
            ->toHaveCount(0);
    });

    it('preserves mixed types', function (): void {
        $array = FixedArray::fromArray([1, 'two', null, 4.5, true]);
        $result = FixedArray::toArray($array);

        expect($result)
            ->toEqual([1, 'two', null, 4.5, true]);
    });
});

describe('to collection', function (): void {
    it('converts a non-empty SplFixedArray to a collection', function (): void {
        $array = FixedArray::fromArray([1, 2, 3]);
        $result = FixedArray::toCollection($array);

        expect($result)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(3)
            ->toEqual(collect([1, 2, 3]));
    });

    it('converts an empty SplFixedArray to an empty collection', function (): void {
        $array = new SplFixedArray(0);
        $result = FixedArray::toCollection($array);

        expect($result)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(0)
            ->toEqual(collect([]));
    });

    it('preserves mixed types in the collection', function (): void {
        $array = FixedArray::fromArray([1, 'two', null, 4.5, true]);
        $result = FixedArray::toCollection($array);

        expect($result)->toEqual(collect([1, 'two', null, 4.5, true]));
    });
});

describe('unshift', function (): void {
    it('prepends a value to a non-empty array', function (): void {
        $array = FixedArray::fromArray([2, 3]);
        $result = FixedArray::unshift(1, $array);

        expect($array->toArray())
            ->toEqual([1, 2, 3])
            ->and($result)
            ->toBe($array);
    });

    it('works on an array with zero indices', function (): void {
        $array = new SplFixedArray(0);
        $result = FixedArray::unshift(1, $array);

        expect($array->toArray())
            ->toEqual([1])
            ->and($result)
            ->toBe($array);
    });

    it('supports mixed types', function (): void {
        $array = FixedArray::fromArray(['b', 'c']);
        $result = FixedArray::unshift(null, $array);

        expect($array->toArray())
            ->toEqual([null, 'b', 'c'])
            ->and($result)
            ->toBe($array);
    });

    it('preserves original items after prepending', function (): void {
        $array = FixedArray::fromArray([true, false]);
        FixedArray::unshift('start', $array);

        expect($array->toArray())->toEqual(['start', true, false]);
    });
});
