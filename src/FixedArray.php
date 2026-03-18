<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace Petrobolos\FixedArray;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Petrobolos\FixedArray\Fluent\FixedArrayable;
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
    public static function add(SplFixedArray $fixedArray, mixed $value): SplFixedArray
    {
        return self::push($fixedArray, $value);
    }

    /**
     * Adds values from a given array or array-like object into the current fixed array.
     *
     * @param iterable<mixed> $items
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function addFrom(SplFixedArray $array, iterable $items): SplFixedArray
    {
        foreach ($items as $value) {
            self::add($array, $value);
        }

        return $array;
    }

    /**
     * Split a fixed array into chunks of a given size.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @throws \InvalidArgumentException
     *
     * @return \SplFixedArray<mixed>
     */
    public static function chunk(SplFixedArray $array, int $size): SplFixedArray
    {
        if ($size <= 0) {
            throw new InvalidArgumentException('Chunk size must be greater than zero.');
        }

        $chunks = array_chunk(self::toArray($array), $size);
        $fixedChunks = array_map(static fn(array $chunk): SplFixedArray => self::fromArray($chunk), $chunks);

        return self::fromArray($fixedChunks, false);
    }

    /**
     * Chunk a fixed array while the given condition is true.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, mixed $key, ?mixed $previous): bool $callback
     *
     * @return \SplFixedArray<mixed>
     */
    public static function chunkWhile(SplFixedArray $array, callable $callback): SplFixedArray
    {
        $chunks = [];
        $currentChunk = [];

        $previous = null;

        foreach ($array as $key => $value) {
            if ($currentChunk === []) {
                $currentChunk[] = $value;
            } elseif ($callback($value, $key, $previous)) {
                $currentChunk[] = $value;
            } else {
                $chunks[] = self::fromArray($currentChunk);
                $currentChunk = [$value];
            }

            $previous = $value;
        }

        if ($currentChunk !== []) {
            $chunks[] = self::fromArray($currentChunk);
        }

        return self::fromArray($chunks, false);
    }

    /**
     * Returns whether a given item is contained within the array.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function contains(SplFixedArray $array, mixed $item, bool $useStrict = true): bool
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
     * User was banned for this post.
     *
     * @return \SplFixedArray<mixed>
     */
    public static function dsfargeg(): SplFixedArray
    {
        return self::fromArray(str_split('DSFARGEG'));
    }

    /**
     * Create a new fluent interface.
     */
    public static function fluent(mixed $value, ?int $count = null, bool $preserveKeys = true): FixedArrayable
    {
        return FixedArrayable::make($value, $count, $preserveKeys);
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
     * Fill a fixed array with the given value.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function fill(SplFixedArray $array, mixed $value): SplFixedArray
    {
        for ($i = 0, $max = self::count($array); $i < $max; $i++) {
            self::offsetSet($array, $i, $value);
        }

        return $array;
    }

    /**
     * Apply a filter to a given fixed array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     *
     * @return \SplFixedArray<mixed>
     */
    public static function filter(SplFixedArray $array, callable $callback): SplFixedArray
    {
        $result = [];

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                $result[] = $value;
            }
        }

        return self::fromArray($result, false);
    }

    /**
     * Find the first element in the fixed array that satisfies the given callback.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     */
    public static function find(SplFixedArray $array, callable $callback): mixed
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Find the key (index) of the first element in the fixed array that satisfies the given callback.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     */
    public static function findKey(SplFixedArray $array, callable $callback): ?int
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Alias for findKey.
     *
     * @see \Petrobolos\FixedArray\FixedArray::findKey()
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     */
    public static function findIndex(SplFixedArray $array, callable $callback): ?int
    {
        return self::findKey($array, $callback);
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
     * Flatten a nested fixed array or iterable into a single-level fixed array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param int|null $depth The maximum depth to flatten. Null flattens all levels.
     *
     * @return \SplFixedArray<mixed>
     */
    public static function flatten(SplFixedArray $array, ?int $depth = null): SplFixedArray
    {
        $result = [];

        $flattenRecursive = static function (iterable $items, ?int $level) use (&$result, &$flattenRecursive): void {
            foreach ($items as $item) {
                if (($item instanceof SplFixedArray || is_iterable($item)) && ($level === null || $level > 0)) {
                    $flattenRecursive($item, $level === null ? null : $level - 1);
                } else {
                    $result[] = $item;
                }
            }
        };

        $flattenRecursive($array, $depth);

        return self::fromArray($result, false);
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
                self::push($target, $item);
            }
        }

        return $target;
    }

    /**
     * Replaces the contents of a fixed array with nulls.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function nullify(SplFixedArray $array): SplFixedArray
    {
        for ($i = 0, $iMax = self::count($array); $i < $iMax; $i++) {
            self::offsetNull($i, $array);
        }

        return $array;
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
        self::offsetSet($array, $index, null);
    }

    /**
     * Sets a new value at a specified index.
     *
     * @throws \RuntimeException
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function offsetSet(SplFixedArray $array, int $index, mixed $value): void
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
        self::offsetSet($array, $count - 1, null);

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
    public static function push(SplFixedArray $array, mixed $value): SplFixedArray
    {
        $size = self::count($array);

        self::setSize($size + 1, $array);
        self::offsetSet($array, $size, $value);

        return $array;
    }

    /**
     * Returns a random element from the fixed array, or null if empty.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @throws \Random\RandomException
     */
    public static function random(SplFixedArray $array): mixed
    {
        $count = self::count($array);

        if ($count === 0) {
            return null;
        }

        $randomIndex = random_int(0, $count - 1);

        return self::offsetGet($randomIndex, $array);
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
            self::offsetSet($array, $i - 1, self::offsetGet($i, $array));
        }

        self::offsetNull($count - 1, $array);

        return $item;
    }

    /**
     * Shuffle the fixed array in place using cryptographically secure randomness.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @throws \Random\RandomException
     *
     * @return \SplFixedArray<mixed>
     */
    public static function shuffle(SplFixedArray $array): SplFixedArray
    {
        $values = self::toArray($array);
        $count = count($values);

        for ($i = $count - 1; $i > 0; $i--) {
            $j = random_int(0, $i);

            [$values[$i], $values[$j]] = [$values[$j], $values[$i]];
        }

        return self::fromArray($values, false);
    }

    /**
     * Returns a portion of the array as a new SplFixedArray.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function slice(SplFixedArray $array, int $offset, ?int $length = null): SplFixedArray
    {
        return self::fromArray(
            array_slice(self::toArray($array), $offset, $length),
            preserveKeys: false,
        );
    }

    /**
     * Sort the fixed array in ascending order.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable|null $callback Optional custom sort callback.
     *
     * @return \SplFixedArray<mixed>
     */
    public static function sort(SplFixedArray $array, ?callable $callback = null): SplFixedArray
    {
        $values = self::toArray($array);

        if ($callback !== null) {
            usort($values, $callback);
        } else {
            sort($values);
        }

        return self::fromArray($values, false);
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
     * Returns a new SplFixedArray with duplicate values removed.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function unique(SplFixedArray $array, bool $strict = true): SplFixedArray
    {
        $values = self::toArray($array);
        $unique = [];

        foreach ($values as $v) {
            $found = false;

            foreach ($unique as $u) {
                if ($v === $u) {
                    $found = true;

                    break;
                }
            }

            if (!$found) {
                $unique[] = $v;
            }
        }

        if (!$strict) {
            /** @var array<int, mixed> $unique */
            $unique = array_values(array_unique($unique, SORT_REGULAR));
        }

        return self::fromArray($unique, false);
    }



    /**
     * Prepends a value to the start of the array.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function unshift(SplFixedArray $array, mixed $value): SplFixedArray
    {
        $count = self::count($array);

        // Increase size by 1 to make space at index 0.
        self::setSize($count + 1, $array);

        // Shift all items one slot to the right and insert the new value at index 0.
        for ($i = $count - 1; $i >= 0; $i--) {
            self::offsetSet($array, $i + 1, self::offsetGet($i, $array));
        }

        self::offsetSet($array, 0, $value);

        return $array;
    }

    /**
     * Returns whether the array is empty.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function isEmpty(SplFixedArray $array): bool
    {
        return self::count($array) === 0;
    }

    /**
     * Returns whether the array is not empty.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function isNotEmpty(SplFixedArray $array): bool
    {
        return ! self::isEmpty($array);
    }

    /**
     * Reduce the array to a single value using a callback.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $carry, mixed $value, int $key): mixed $callback
     */
    public static function reduce(SplFixedArray $array, callable $callback, mixed $initial = null): mixed
    {
        $carry = $initial;

        foreach ($array as $key => $value) {
            $carry = $callback($carry, $value, $key);
        }

        return $carry;
    }

    /**
     * Sum all numeric values in the array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param (callable(mixed $value): (int|float))|string|null $callback
     */
    public static function sum(SplFixedArray $array, callable|string|null $callback = null): int|float
    {
        if ($callback === null) {
            $sum = 0;
            foreach ($array as $value) {
                if (is_numeric($value)) {
                    $sum += $value;
                }
            }

            return $sum;
        }

        if (is_string($callback)) {
            // Support property access like Collection
            $sum = 0;
            foreach ($array as $value) {
                if (is_object($value) && property_exists($value, $callback)) {
                    /** @phpstan-ignore-next-line property.dynamicName */
                    $val = $value->{$callback};
                    if (is_numeric($val)) {
                        $sum += $val;
                    }
                }
            }

            return $sum;
        }

        $sum = 0;
        foreach ($array as $value) {
            $result = $callback($value);
            /** @phpstan-ignore-next-line function.alreadyNarrowedType */
            if (is_numeric($result)) {
                $sum += $result;
            }
        }

        return $sum;
    }

    /**
     * Calculate the average of all numeric values in the array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param (callable(mixed $value): (int|float))|string|null $callback
     */
    public static function avg(SplFixedArray $array, callable|string|null $callback = null): int|float|null
    {
        $count = self::count($array);

        if ($count === 0) {
            return null;
        }

        return self::sum($array, $callback) / $count;
    }

    /**
     * Alias for avg.
     *
     * @see \Petrobolos\FixedArray\FixedArray::avg()
     *
     * @param \SplFixedArray<mixed> $array
     * @param (callable(mixed $value): (int|float))|string|null $callback
     */
    public static function average(SplFixedArray $array, callable|string|null $callback = null): int|float|null
    {
        return self::avg($array, $callback);
    }

    /**
     * Find the minimum value in the array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param (callable(mixed $value): (int|float))|string|null $callback
     */
    public static function min(SplFixedArray $array, callable|string|null $callback = null): mixed
    {
        if (self::isEmpty($array)) {
            return null;
        }

        if ($callback === null) {
            $min = null;
            foreach ($array as $value) {
                if ($min === null || $value < $min) {
                    $min = $value;
                }
            }

            return $min;
        }

        if (is_string($callback)) {
            $min = null;
            foreach ($array as $value) {
                if (is_object($value) && property_exists($value, $callback)) {
                    /** @phpstan-ignore-next-line property.dynamicName */
                    $val = $value->{$callback};
                    if ($min === null || $val < $min) {
                        $min = $val;
                    }
                }
            }

            return $min;
        }

        $min = null;
        foreach ($array as $value) {
            $result = $callback($value);
            if ($min === null || $result < $min) {
                $min = $result;
            }
        }

        return $min;
    }

    /**
     * Find the maximum value in the array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param (callable(mixed $value): (int|float))|string|null $callback
     */
    public static function max(SplFixedArray $array, callable|string|null $callback = null): mixed
    {
        if (self::isEmpty($array)) {
            return null;
        }

        if ($callback === null) {
            $max = null;
            foreach ($array as $value) {
                if ($max === null || $value > $max) {
                    $max = $value;
                }
            }

            return $max;
        }

        if (is_string($callback)) {
            $max = null;
            foreach ($array as $value) {
                if (is_object($value) && property_exists($value, $callback)) {
                    /** @phpstan-ignore-next-line property.dynamicName */
                    $val = $value->{$callback};
                    if ($max === null || $val > $max) {
                        $max = $val;
                    }
                }
            }

            return $max;
        }

        $max = null;
        foreach ($array as $value) {
            $result = $callback($value);
            if ($max === null || $result > $max) {
                $max = $result;
            }
        }

        return $max;
    }

    /**
     * Determine if all items pass the given test.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     */
    public static function every(SplFixedArray $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if (! $callback($value, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Alias for every.
     *
     * @see \Petrobolos\FixedArray\FixedArray::every()
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     */
    public static function all(SplFixedArray $array, callable $callback): bool
    {
        return self::every($array, $callback);
    }

    /**
     * Determine if at least one item passes the given test.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     */
    public static function some(SplFixedArray $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filter out items that pass the given test (opposite of filter).
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     *
     * @return \SplFixedArray<mixed>
     */
    public static function reject(SplFixedArray $array, callable $callback): SplFixedArray
    {
        return self::filter($array, static fn(mixed $value, int $key): bool => ! $callback($value, $key));
    }

    /**
     * Partition the array into two arrays based on a callback.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(mixed $value, int $key): bool $callback
     *
     * @return \SplFixedArray<mixed> Array containing two SplFixedArrays: [passing, failing]
     */
    public static function partition(SplFixedArray $array, callable $callback): SplFixedArray
    {
        $passing = [];
        $failing = [];

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                $passing[] = $value;
            } else {
                $failing[] = $value;
            }
        }

        return self::fromArray([
            self::fromArray($passing, false),
            self::fromArray($failing, false),
        ], false);
    }

    /**
     * Extract values from a specific property or key.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function pluck(SplFixedArray $array, string $key): SplFixedArray
    {
        $result = [];

        foreach ($array as $item) {
            if (is_array($item) && array_key_exists($key, $item)) {
                $result[] = $item[$key];
            } elseif (is_object($item) && property_exists($item, $key)) {
                /** @phpstan-ignore-next-line property.dynamicName */
                $result[] = $item->{$key};
            } else {
                $result[] = null;
            }
        }

        return self::fromArray($result, false);
    }

    /**
     * Join array elements with a string.
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function join(SplFixedArray $array, string $glue = ''): string
    {
        $result = '';
        $count = self::count($array);

        foreach ($array as $index => $value) {
            if ($value === null) {
                $result .= '';
            } elseif (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
                $result .= (string) $value;
            } else {
                $result .= '';
            }

            if ($index < $count - 1) {
                $result .= $glue;
            }
        }

        return $result;
    }

    /**
     * Alias for join.
     *
     * @see \Petrobolos\FixedArray\FixedArray::join()
     *
     * @param \SplFixedArray<mixed> $array
     */
    public static function implode(SplFixedArray $array, string $glue = ''): string
    {
        return self::join($array, $glue);
    }

    /**
     * Get all keys (indices) from the array.
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function keys(SplFixedArray $array): SplFixedArray
    {
        $keys = [];
        foreach ($array as $key => $value) {
            $keys[] = $key;
        }

        return self::fromArray($keys, false);
    }

    /**
     * Get all values from the array (reindexed from 0).
     *
     * @param \SplFixedArray<mixed> $array
     *
     * @return \SplFixedArray<mixed>
     */
    public static function values(SplFixedArray $array): SplFixedArray
    {
        $values = [];
        foreach ($array as $value) {
            $values[] = $value;
        }

        return self::fromArray($values, false);
    }

    /**
     * Pass the array to a callback and return the array.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(\SplFixedArray<mixed> $array): void $callback
     *
     * @return \SplFixedArray<mixed>
     */
    public static function tap(SplFixedArray $array, callable $callback): SplFixedArray
    {
        $callback($array);

        return $array;
    }

    /**
     * Pass the array through a callback and return the result.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(\SplFixedArray<mixed> $array): mixed $callback
     */
    public static function pipe(SplFixedArray $array, callable $callback): mixed
    {
        return $callback($array);
    }

    /**
     * Apply the callback if the condition is true.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(\SplFixedArray<mixed> $array): bool|bool $condition
     * @param callable(\SplFixedArray<mixed> $array): \SplFixedArray<mixed> $callback
     * @param (callable(\SplFixedArray<mixed> $array): \SplFixedArray<mixed>)|null $default
     *
     * @return \SplFixedArray<mixed>
     */
    public static function when(
        SplFixedArray $array,
        callable|bool $condition,
        callable $callback,
        ?callable $default = null,
    ): SplFixedArray {
        $conditionResult = is_callable($condition) ? $condition($array) : $condition;

        if ($conditionResult) {
            return $callback($array);
        }

        if ($default !== null) {
            return $default($array);
        }

        return $array;
    }

    /**
     * Apply the callback if the condition is false.
     *
     * @param \SplFixedArray<mixed> $array
     * @param callable(\SplFixedArray<mixed> $array): bool|bool $condition
     * @param callable(\SplFixedArray<mixed> $array): \SplFixedArray<mixed> $callback
     * @param (callable(\SplFixedArray<mixed> $array): \SplFixedArray<mixed>)|null $default
     *
     * @return \SplFixedArray<mixed>
     */
    public static function unless(
        SplFixedArray $array,
        callable|bool $condition,
        callable $callback,
        ?callable $default = null,
    ): SplFixedArray {
        $conditionResult = is_callable($condition) ? $condition($array) : $condition;

        if (! $conditionResult) {
            return $callback($array);
        }

        if ($default !== null) {
            return $default($array);
        }

        return $array;
    }
}
