<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace Petrobolos\FixedArray;

use Illuminate\Support\Collection;
use SplFixedArray;

class FixedArray
{
    /**
     * Alias for push.
     *
     * @param \SplFixedArray<mixed> $fixedArray
     *
     * @see \Petrobolos\FixedArray\FixedArray::push()
     *
     * @return \SplFixedArray<mixed>
     */
    public static function add(mixed $value, SplFixedArray $fixedArray): SplFixedArray
    {
        return self::push($value, $fixedArray);
    }

    /**
     * Adds values from a given array or array-like object into the current fixed array.
     *
     * @param iterable<mixed> $items
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function addFrom(iterable $items, SplFixedArray $array): SplFixedArray
    {
        foreach ($items as $value) {
            self::add($value, $array);
        }

        return $array;
    }

    /**
     * Returns whether a given item is contained within the array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function contains(mixed $item, SplFixedArray $array, bool $useStrict = true): bool
    {
        /** @phpstan-ignore-next-line The third parameter can be Boolean, not just true. */
        return in_array($item, self::toArray($array), $useStrict);
    }

    /**
     * Returns the size of the array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function count(SplFixedArray $array): int
    {
        return $array->count();
    }

    /**
     * Create a new fixed array.
     *
     * @throws \ValueError
     *
     * @return \SplFixedArray<mixed>
     */
    public static function create(int $size = 5): SplFixedArray
    {
        return new SplFixedArray($size);
    }

    /**
     * Apply a callback to each item in the array without modifying the original array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): void $callback
     *
     * @return \SplFixedArray<mixed>
     */
    public static function each(SplFixedArray $array, callable $callback): SplFixedArray
    {
        foreach ($array as $key => $value) {
            $callback($value, $key);
        }

        return $array;
    }

    /**
     * Apply a filter to a given fixed array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value): bool $callback
     *
     * @return \SplFixedArray<mixed>
     */
    public static function filter(SplFixedArray $array, callable $callback): SplFixedArray
    {
        $result = array_filter(self::toArray($array), $callback);

        // Reindex to avoid null-filled gaps caused by preserved keys.
        return self::fromArray(array_values($result), false);
    }

    /**
     * Returns the first value from a fixed array.
     *
     * @throws \RuntimeException
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function first(SplFixedArray $array): mixed
    {
        return self::offsetGet(0, $array);
    }

    /**
     * Import a PHP array into a fixed array.
     *
     * @param array<mixed, mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function fromArray(array $array, bool $preserveKeys = true): SplFixedArray
    {
        return SplFixedArray::fromArray($array, $preserveKeys);
    }

    /**
     * Import a collection into a fixed array.
     *
     * @param \Illuminate\Support\Collection<int|string, mixed> $collection
     *
     * @return \SplFixedArray<mixed>
     */
    public static function fromCollection(Collection $collection, bool $preserveKeys = true): SplFixedArray
    {
        return SplFixedArray::fromArray($collection->toArray(), $preserveKeys);
    }

    /**
     * Gets the size of the array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function getSize(SplFixedArray $array): int
    {
        return $array->getSize();
    }

    /**
     * Returns whether a given value is an SplFixedArray.
     */
    public static function isFixedArray(mixed $array): bool
    {
        return $array instanceof SplFixedArray;
    }

    /**
     * Retrieves the last item from the array.
     *
     * @throws \RuntimeException
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function last(SplFixedArray $array): mixed
    {
        return self::offsetGet(self::count($array) - 1, $array);
    }

    /**
     * Apply a callback to each item in the array and return a new fixed array.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @param callable(mixed): mixed $callback
     *
     * @return \SplFixedArray<mixed>
     */
    public static function map(SplFixedArray $array, callable $callback): SplFixedArray
    {
        $result = array_map($callback, self::toArray($array));

        return self::fromArray($result);
    }


    /**
     * Merge multiple fixed arrays, arrays, or collections into one fixed array.
     *
     * @template T
     *
     * @param \SplFixedArray<T> $target
     * @param (\SplFixedArray<T>|iterable<T>) ...$sources
     *
     * @return \SplFixedArray<T>
     */
    public static function merge(SplFixedArray $target, SplFixedArray|iterable ...$sources): SplFixedArray
    {
        foreach ($sources as $source) {
            foreach ($source as $item) {
                self::push($item, $target);
            }
        }

        return $target;
    }

    /**
     * Replaces the contents of a fixed array with nulls.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function nullify(SplFixedArray $array): void
    {
        for ($i = 0, $iMax = self::count($array); $i < $iMax; $i++) {
            self::offsetNull($i, $array);
        }
    }

    /**
     * Return whether the specified index exists.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function offsetExists(int $index, SplFixedArray $array): bool
    {
        return $array->offsetExists($index);
    }

    /**
     * Returns the value at the specified index.
     *
     * @throws \RuntimeException
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function offsetGet(int $index, SplFixedArray $array): mixed
    {
        return $array->offsetGet($index);
    }

    /**
     * Set a given offset to a null value.
     *
     * @throws \RuntimeException
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function offsetNull(int $index, SplFixedArray $array): void
    {
        self::offsetSet($index, null, $array);
    }

    /**
     * Sets a new value at a specified index.
     *
     * @throws \RuntimeException
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function offsetSet(int $index, mixed $value, SplFixedArray $array): void
    {
        $array->offsetSet($index, $value);
    }

    /**
     * Pops the latest value from the array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function pop(SplFixedArray $array): mixed
    {
        $count = self::count($array);

        if ($count === 0) {
            return null;
        }

        $item = self::offsetGet($count - 1, $array);
        self::offsetSet($count - 1, null, $array);

        return $item;
    }

    /**
     * Pushes a given value to the first available space on the array.
     * If the array is too small, the array size is extended by a single value.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function push(mixed $value, SplFixedArray $array): SplFixedArray
    {
        $size = self::count($array);

        self::setSize($size + 1, $array);
        self::offsetSet($size, $value, $array);

        return $array;
    }

    /**
     * Alias for setSize.
     *
     * @see \Petrobolos\FixedArray\FixedArray::setSize()
     *
     * @throws \ValueError
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function resize(int $size, SplFixedArray $array): bool
    {
        return self::setSize($size, $array);
    }

    /**
     * Returns a new SplFixedArray with items in reverse order.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function reverse(SplFixedArray $array): SplFixedArray
    {
        return self::fromArray(
            array_reverse(self::toArray($array)),
            preserveKeys: false,
        );
    }

    /**
     * Returns the second value from a fixed array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function second(SplFixedArray $array): mixed
    {
        if (self::offsetExists(1, $array)) {
            return self::offsetGet(1, $array);
        }

        return null;
    }

    /**
     * Change the size of an array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function setSize(int $size, SplFixedArray $array): bool
    {
        return $array->setSize($size);
    }

    /**
     * Removes and returns the first item from the array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function shift(SplFixedArray $array): mixed
    {
        $count = self::count($array);

        if ($count === 0) {
            return null;
        }

        $item = self::offsetGet(0, $array);

        // Shift all items to the left and nullify the last slot.
        for ($i = 1; $i < $count; $i++) {
            self::offsetSet($i - 1, self::offsetGet($i, $array), $array);
        }

        self::offsetNull($count - 1, $array);

        return $item;
    }


    /**
     * Returns a PHP array from the fixed array.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return array<int, mixed>
     */
    public static function toArray(SplFixedArray $array): array
    {
        return $array->toArray();
    }

    /**
     * Returns a collection from the fixed array.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \Illuminate\Support\Collection<int, mixed>
     */
    public static function toCollection(SplFixedArray $array): Collection
    {
        return collect($array);
    }

    /**
     * Prepends a value to the start of the array.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function unshift(mixed $value, SplFixedArray $array): SplFixedArray
    {
        $count = self::count($array);

        // Increase size by 1 to make space at index 0.
        self::setSize($count + 1, $array);

        // Shift all items one slot to the right and insert the new value at index 0.
        for ($i = $count - 1; $i >= 0; $i--) {
            self::offsetSet($i + 1, self::offsetGet($i, $array), $array);
        }

        self::offsetSet(0, $value, $array);

        return $array;
    }
}
