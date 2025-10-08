<?php

declare(strict_types=1);

namespace Petrobolos\FixedArray;

use ArrayAccess;
use Illuminate\Support\Collection;
use SplFixedArray;

class FixedArray
{
    /**
     * Alias for push.
     *
     * @param mixed $value
     * @param \SplFixedArray $fixedArray
     * @return \SplFixedArray
     */
    public static function add(mixed $value, SplFixedArray $fixedArray): SplFixedArray
    {
        return self::push($value, $fixedArray);
    }

    /**
     * Adds values from a given array or array-like object into the current fixed array.
     *
     * @param \ArrayAccess|array $items
     * @param \SplFixedArray $array
     * @return \SplFixedArray
     */
    public static function addFrom(ArrayAccess|array $items, SplFixedArray $array): SplFixedArray
    {
        foreach ($items as $value) {
            self::add($value, $array);
        }

        return $array;
    }

    /**
     * Returns whether a given item is contained within the array.
     */
    public static function contains(mixed $item, SplFixedArray $array, bool $useStrict = true): bool
    {
        return in_array($item, self::toArray($array), $useStrict);
    }

    /**
     * Returns the size of the array.
     */
    public static function count(SplFixedArray $array): int
    {
        return $array->count();
    }

    /**
     * Create a new fixed array.
     */
    public static function create(int $size = 5): SplFixedArray
    {
        return new SplFixedArray($size);
    }

    /**
     * Apply a callback to each item in the array without modifying the original array.
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
     */
    public static function filter(SplFixedArray $array, callable $callback): SplFixedArray
    {
        $result = array_filter(self::toArray($array), $callback);

        return self::fromArray($result);
    }

    /**
     * Returns the first value from a fixed array.
     */
    public static function first(SplFixedArray $array): mixed
    {
        return self::offsetGet(0, $array);
    }

    /**
     * Import a PHP array into a fixed array.
     */
    public static function fromArray(array $array, bool $preserveKeys = true): SplFixedArray
    {
        return SplFixedArray::fromArray($array, $preserveKeys);
    }

    /**
     * Import a collection into a fixed array.
     */
    public static function fromCollection(Collection $collection, bool $preserveKeys = true): SplFixedArray
    {
        return SplFixedArray::fromArray($collection->toArray(), $preserveKeys);
    }

    /**
     * Gets the size of the array.
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
     */
    public static function last(SplFixedArray $array): mixed
    {
        return self::offsetGet(self::count($array) - 1, $array);
    }

    /**
     * Apply a callback to each item in the array and return the new array.
     */
    public static function map(SplFixedArray $array, callable|string $callback): SplFixedArray
    {
        $array = array_map($callback, self::toArray($array));

        return self::fromArray($array);
    }

    /**
     * Merges multiple fixed arrays, arrays, or collections into a single fixed array.
     */
    public static function merge(SplFixedArray $array, SplFixedArray|array|Collection ...$arrays): SplFixedArray
    {
        foreach ($arrays as $items) {
            foreach ($items as $item) {
                self::push($item, $array);
            }
        }

        return $array;
    }

    /**
     * Replaces the contents of a fixed array with nulls.
     */
    public static function nullify(SplFixedArray $array): void
    {
        for ($i = 0, $iMax = self::count($array); $i < $iMax; $i++) {
            self::offsetNull($i, $array);
        }
    }

    /**
     * Return whether the specified index exists.
     */
    public static function offsetExists(int $index, SplFixedArray $array): bool
    {
        return $array->offsetExists($index);
    }

    /**
     * Returns the value at the specified index.
     */
    public static function offsetGet(int $index, SplFixedArray $array): mixed
    {
        return $array->offsetGet($index);
    }

    /**
     * Set a given offset to a null value.
     */
    public static function offsetNull(int $index, SplFixedArray $array): void
    {
        self::offsetSet($index, null, $array);
    }

    /**
     * Sets a new value at a specified index.
     */
    public static function offsetSet(int $index, mixed $value, SplFixedArray $array): void
    {
        $array->offsetSet($index, $value);
    }

    /**
     * Pops the latest value from the array.
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
     */
    public static function push(mixed $value, SplFixedArray $array): SplFixedArray
    {
        foreach ($array as $index => $item) {
            if ($item === null) {
                $array[$index] = $value;

                return $array;
            }
        }

        self::setSize((self::count($array) + 1), $array);
        self::offsetSet(self::count($array) - 1, $value, $array);

        return $array;
    }

    /**
     * Alias for setSize.
     */
    public static function resize(int $size, SplFixedArray $array): bool
    {
        return self::setSize($size, $array);
    }

    /**
     * Returns the second value from a fixed array.
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
     */
    public static function setSize(int $size, SplFixedArray $array): bool
    {
        return $array->setSize($size);
    }

    /**
     * Returns a PHP array from the fixed array.
     */
    public static function toArray(SplFixedArray $array): array
    {
        return $array->toArray();
    }

    /**
     * Returns a collection from the fixed array.
     */
    public static function toCollection(SplFixedArray $array): Collection
    {
        return collect($array);
    }
}
